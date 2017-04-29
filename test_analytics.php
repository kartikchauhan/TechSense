<!DOCTYPE html>
<html>
<head>

</head>
<body>
  <p>hey</p>
</body>
</html>

<?php

// Load the Google API PHP Client Library.
require_once __DIR__ . '/vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/client_secrets.json');
$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);


// If the user has already authorized this app then get an access token
// else redirect to ask the user to authorize access to Google Analytics.
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  // Set the access token on the client.
  $client->setAccessToken($_SESSION['access_token']);

  // Create an authorized analytics service object.
  $analytics = new Google_Service_AnalyticsReporting($client);

  // Call the Analytics Reporting API V4.
  $response = getReport($analytics);

  // Print the response.
  printResults($response);

} else {
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] .'/TechWit/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}


/**
 * Queries the Analytics Reporting API V4.
 *
 * @param service An authorized Analytics Reporting API V4 service object.
 * @return The Analytics Reporting API V4 response.
 */
function getReport($analytics) {

  // Replace with your view ID, for example XXXX.
  $VIEW_ID = "149090607";

  // Create the DateRange object.
  $dateRange = new Google_Service_AnalyticsReporting_DateRange();
  $dateRange->setStartDate("yesterday");
  $dateRange->setEndDate("yesterday");  

  // Create the Metrics object.
  $metrics = new Google_Service_AnalyticsReporting_Metric();
  $metrics->setExpression("ga:uniquePageviews");
  $metrics->setAlias("uniquePageviews");

  $dimensions = new Google_Service_AnalyticsReporting_Dimension();
  $dimensions->setName("ga:pagePathLevel2");


  // Create the ReportRequest object.
  $request = new Google_Service_AnalyticsReporting_ReportRequest();
  $request->setViewId($VIEW_ID);
  $request->setDateRanges($dateRange);
  $request->setDimensions(array($dimensions));
  $request->setMetrics(array($metrics));
  

  $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
  $body->setReportRequests( array( $request) );
  return $analytics->reports->batchGet( $body );
}


/**
 * Parses and prints the Analytics Reporting API V4 response.
 *
 * @param An Analytics Reporting API V4 response.
 */
function printResults($reports) {
  for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
    $report = $reports[ $reportIndex ];
    $header = $report->getColumnHeader();
    $dimensionHeaders = $header->getDimensions();
    $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
    $rows = $report->getData()->getRows();

    for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
      $row = $rows[ $rowIndex ];
      $dimensions = $row->getDimensions();
      $metrics = $row->getMetrics();
      for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
        print($dimensionHeaders[$i] . ": " . $dimensions[$i] . "\n");
        echo "<br>"."/TechWit".$dimensions[$i]."<br>";
        echo "<br>".$_SERVER['REQUEST_URI']."<br>";
        if("/TechWit".$dimensions[$i] == $_SERVER['REQUEST_URI'])
        {
          echo "yes";
        }
        else
        {
          echo "no";
        }

      }

      for ($j = 0; $j < count($metrics); $j++) {
        $values = $metrics[$j]->getValues();
        for ($k = 0; $k < count($values); $k++) {
          $entry = $metricHeaders[$k];
          print($entry->getName() . "<br>" . $values[$k] . "<br>");
        }
      }
    }
  }
}

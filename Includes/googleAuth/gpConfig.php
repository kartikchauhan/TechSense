<?php

//Include Google client library 
include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '1084607052036-i2q1bbu1qnheeftrvpla974le9k5lmrf.apps.googleusercontent.com'; //Google client ID
$clientSecret = 'ImGI6dV5I4lH440IDkkDjVwK'; //Google client secret
$redirectURL = 'http://localhost/Blog_temp/index.php'; //Callback URL

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('blogSparta');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>
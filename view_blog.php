<?php

// require_once'Includes/googleAuth/gpConfig.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once'Core/init.php';

$GLOBALS['path'] = explode('/', $_SERVER['SCRIPT_NAME']);
$custom_path = '';
for($i = 1; $i < count($path); $i++)
{
	$custom_path .= '/'.$path[$i];	// custom_path for setting redirecting_uri in oauthcallback.php
}

$user = new User;

// $authUrl = $gClient->createAuthUrl();

// if(Input::get('code'))
// {
// 	$gClient->authenticate(Input::get('code'));
// 	Session::put('googleToken', $gClient->getAccessToken());
// 	Redirect::to('social_login.php');
// }


if(Input::exists('get'))
{
	if(Input::get('blog_id'))
	{
		$blogId = Input::get('blog_id');
		$blog = new Blog;	// creating an Instance of Blog so that we could update the views
		$blog->getBlog('blogs', array('id', '=', $blogId));
		if(!$blog->count())
		{
			Redirect::to(404);
		}
		$author = DB::getInstance()->join(array('users', 'blogs'), array('id', 'users_id'), array('id', '=', $blogId))->first(); // fetching the author of the blog and his corresponding details


		// Code for updating the unique views of a blog using google Analytics and it's reporting library starts from here
		// Load the Google API PHP Client Library.

		$analytics = initializeAnalytics();	// initialises the API library
		$response = getReport($analytics);	// queries the library taking specified metrics, dimensions as determining parameters
		updateViews($response, $blog, $blogId);	// prebuilt function which later customized to fetch the uniquePageViews from the response object. Fetching uniquePageViews and updating the views of the corresponding blog.

		$date=strtotime($blog->data()->created_on);
		if($user->isLoggedIn())
		{
			$userLoggedIn = true;
			$blogStatus = DB::getInstance()->getAnd('users_blogs_status', array('user_id' => $user->data()->id, 'blog_id' => $blogId));
			$blogStatusCount = $blogStatus->count();
			if($blogStatusCount)
			{
				$blogStatus = $blogStatus->first()->blog_status;
			}
		}
		else
		{
			$userLoggedIn = false;
		}
	}
	else
	{
		Redirect::to('index.php');
	}
}
else
{
	Redirect::to('index.php');
}

/**
 * Initializes an Analytics Reporting API V4 service object.
 *
 * @return An authorized Analytics Reporting API V4 service object.
 */
function initializeAnalytics() 
{

    // Use the developers console and download your service account
    // credentials in JSON format. Place them in this directory or
    // change the key file location if necessary.
    $KEY_FILE_LOCATION = __DIR__.'/client_secrets.json';

    // Create and configure a new client object.
    $client = new Google_Client();
    $client->setApplicationName("TechWit Analytics Reporting");
    $client->setAuthConfig($KEY_FILE_LOCATION);
    $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
    $analytics = new Google_Service_AnalyticsReporting($client);

    return $analytics;
}

/**
 * Queries the Analytics Reporting API V4.
 *
 * @param service An authorized Analytics Reporting API V4 service object.
 * @return The Analytics Reporting API V4 response.
 */

function getReport($analytics) 
{

    // Replace with your view ID, for example XXXX.
    $VIEW_ID = "149090607";

    // Create the DateRange object.
    $dateRange = new Google_Service_AnalyticsReporting_DateRange();
    $dateRange->setStartDate("2017-04-26");
    $dateRange->setEndDate("today");

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
    $body->setReportRequests(array($request));
    return $analytics->reports->batchGet($body);
}

/**
 * Parses and prints the Analytics Reporting API V4 response.
 *
 * @param An Analytics Reporting API V4 response.
 */
function updateViews($reports, $blog, $blogId) 
{
    for ($reportIndex = 0; $reportIndex < count($reports); $reportIndex++) 
    {
        $report = $reports[$reportIndex];
        $header = $report->getColumnHeader();
        $dimensionHeaders = $header->getDimensions();
        $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
        $rows = $report->getData()->getRows();

        $flag = false; // setting flag = false, flag will keep track whether current URI matches with the dimensions or not, if it matches, update the views
        for ($rowIndex = 0; $rowIndex < count($rows); $rowIndex++) 
        {
            $row = $rows[$rowIndex];
            $dimensions = $row->getDimensions();
            $metrics = $row->getMetrics();
            for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) 
            {
                $custom_path = '';
                for ($j = 1; $j < count($GLOBALS['path']) - 1; $j++) 
                {
                    $custom_path .= '/'.$GLOBALS['path'][$j];
                }
                $dimensions[$i] = $custom_path.$dimensions[$i];
                if ($dimensions[$i] == $_SERVER['REQUEST_URI']) 
                {
                    $flag = true;
                }
            }

            for ($j = 0; $j < count($metrics); $j++) 
            {
                $values = $metrics[$j]->getValues();
                for ($k = 0; $k < count($values); $k++) 
                {
                    $entry = $metricHeaders[$k];
                    if ($flag == true) 
                    {
                        try 
                        {
                            if (DB::getInstance()->update('blogs', $blogId, array('views' => $values[$k])) == false)
                                throw new Exception("Unable to update views of the blog.");
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                        $flag = false; // set flag = false so that no updation doesn't occur for any other page
                    }
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
	<head>
		<script>
		  	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  	ga('create', 'UA-98214883-3', 'auto');
		  	ga('send', 'pageview');

		</script>

		<!-- Load the JavaScript API client and Sign-in library. -->
		<script src="https://apis.google.com/js/client:platform.js"></script>
		<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
		<link rel="preload" as="script" href="Includes/js/materialize.min.js">
    	<link rel="preload" as="script" href="https://use.fontawesome.com/819d78ad52.js">
    	<link rel="preload" as="script" href="Includes/js/jquery.min.js">
    	<link rel="preload" as="style" href="//fonts.googleapis.com/icon?family=Material+Icons">

		<title><?php echo $blog->data()->title; ?></title>
		
		<meta name="google-signin-client_id" content="285926229424-cm218npu455mta48b8r6uq4nassnedvj.apps.googleusercontent.com">
  		<meta name="google-signin-scope" content="https://www.googleapis.com/auth/analytics.readonly">
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:site" content="@TechSense" />
		<meta name="twitter:title" content="<?php echo $blog->data()->title; ?>" />
		<meta name="twitter:description" content="<?php echo $blog->data()->description; ?>" />
		<meta name="twitter:image:src" content="https://uvmbored.com/wp-content/uploads/2015/05/blog.jpg" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!-- <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css"> -->
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
	    
	    <style type="text/css">
	        nav
	        {
	            border-bottom: 1px white solid;
	        }
	        .brand-logo
	        {
	            display: inline-block;
	            height: 100%;
	        }
	        .brand-logo > img {
	            vertical-align: middle
	        }
            input[type="search"]
	        {
	            height: 64px !important; /* or height of nav */
	        }
            nav ul .dropdown-button
	        {
	            width: 200px !important;
	        }
			p
			{
				font-size: 16px;
			}
			a
			{
				cursor: pointer;
				text-decoration: none;
				color: none;
			}
			.tabs .indicator
			{
				background-color: #2196F3 !important; 
			}
			.col.s12 > .btn
			{
				width: 100%;
			}
			nav ul .dropdown-button
	        {
	            width: 200px !important;
	        }
	        div .section
	        {
	        	padding-bottom: 0rem !important;
	        }
	        /*.modal .modal-content
	        {
	        	padding-bottom: 0px;
	        }*/
	        .error
	        {
	        	display: none;
	        }
	        .card .card-content
	        {
	        	padding:0px;
	        }
	        .login_button_container
	        {
	        	margin-top: 12%;
	        }
	        /* Hide AddToAny vertical share bar when screen is less than 980 pixels wide */
			@media screen and (max-width: 980px) {
			    .a2a_floating_style.a2a_vertical_style { display: none; }
			}
			@media screen and (min-width: 980px) {
			    .a2a_floating_style.a2a_default_style { display: none; }
			}
			.card-reposition
			{
				position: absolute;
				margin: 2rem 3rem 1rem 3rem;
			}
			.chip
			{
			    margin-bottom: 10px;
    			margin-right: 10px;
			}
	    </style>

	</head>
	<body>
		<?php 

			include'header.php';

		?>
		
		<script type="text/javascript">
	    	document.getElementById('nav-bar').classList.remove('transparent');
	    </script>	    

	    <!-- NOTE remove it when hosting the website -->
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.9";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

		<header class="blue">
			<section class='z-depth-2'>
				<div class="container">
					<div class="section">
						<div class="row">
							<div class="col s12 l12">
								<h1 class="white-text thin"> <?php echo strtoupper($blog->data()->title); ?></h1>
							</div>
						</div>
						<div class="row">
							<div class="col s12 l10">
								<h5 class="white-text thin"> <?php echo ucfirst($blog->data()->description); ?></h5>
							</div>
						</div>
						<div class="row">
							<div class="col l10 s12">
								<div class="row">
									<div class="col s4 l4">
										<h6 class="white-text"><?php echo date('M d, Y', $date); ?></h6>
									</div>
									<div class="col s4">
										<h6 class="white-text"><?php echo $blog->data()->blog_minutes_read ?> min read</h6>
									</div>
									<div class="col s4 l4">
										<h6 class="white-text" >- <?php echo ucwords($author->name) ?></h6>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</header>

		<article>
			<section>		
				<div class="container">
					<div class="section">
						<div class="row">
							<div class="col s12 l10">
								<p class="flow-text"><?php echo $blog->data()->blog; ?></p>
								<div class="section">
									<div class="row">
										<div class="col s6 offset-s1 l5 offset-l2">
											<h6 class="center-align">Was this article helpful?</h6>
										</div>									
										<div class="_token" id="_token" data-attribute="<?php echo Token::generate(); ?>"></div>
										<?php 
											if($userLoggedIn)
											{
												if($blogStatusCount)
												{
													if($blogStatus == 1)
													{
														echo 
														"<div class='col s1'>
															<a class='likes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: green'></i></a>
														</div>
														<div class='col s1 offset-s1 m1 l1'>
															<a class='dislikes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: grey'></i></a>
														</div>";
													}
													else if($blogStatus == -1)
													{
														echo 
														"<div class='col s1'>
															<a class='likes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: grey'></i></a>
														</div>
														<div class='col s1 offset-s1 m1 l1'>
															<a class='dislikes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: red'></i></a>
														</div>";
													}
													else if($blogStatus == 0)
													{
														echo
														"<div class='col s1'>
															<a class='likes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: grey'></i></a>
														</div>
														<div class='col s1 offset-s1 m1 l1'>
															<a class='dislikes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: grey'></i></a>
														</div>";
													}
												}
												else
												{
													echo
													"<div class='col s1'>
														<a class='likes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: grey'></i></a>
													</div>
													<div class='col s1 offset-s1 m1 l1'>
														<a class='dislikes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: grey'></i></a>
													</div>";
												}

											}
											else
											{
												echo
												"<div class='col s1'>
													<a href='#modal1' class='likes-not-logged-in' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: grey'></i></a>
												</div>
												<div class='col s1 offset-s1 m1 l1'>
													<a href='#modal1' class='dislikes-not-logged-in' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: grey'></i></a>
												</div>";
											}
										?>
									</div>									
								</div>
							</div>
							<div class="col l2 hide-on-med-and-down">
						        <div class="card-panel blue card-reposition z-depth-1 center-align">
						        	<p><h5 class='center-align white-text'>Tags</h5></p>
						        	<div class="left-align">
							        	<?php
							        		$distinctTags = DB::getInstance()->distinctRecords('blog_tags', array('tags'));
											$distinctTags = $distinctTags->results();
											$countArray = [];
											foreach($distinctTags as $distinctTag)
											{
												$tag = $distinctTag->tags;
												$count = DB::getInstance()->countRecords('blog_tags', array('tags', '=', $tag))->first()->count;
												$testArray['blogsCount'] = +$count;
												array_push($countArray, $testArray);
												echo
												"<a class='chip z-depth-1 white' href='".Config::get('url/endpoint')."/view_blogs_tag.php?tag={$tag}'>												
												    {$tag}
												</a>";
											}
							        	?>
						        	</div>
					        	</div>
							</div>
						</div>
					</div>
				</div>
				<div class="container">
					<div class="row">
						<div class="col s12 l10" id="disqus_thread"></div>
						<script>
							/**
							var disqus_config = function () {
							this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
							this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
							};
							*/
							(function() { // DON'T EDIT BELOW THIS LINE
							var d = document, s = d.createElement('script');
							s.src = 'https://techsense-1.disqus.com/embed.js';
							s.setAttribute('data-timestamp', +new Date());
							(d.head || d.body).appendChild(s);
							})();
						</script>
						<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
					</div>
				</div>
			</section>
		</article>

		<div class="a2a_kit a2a_kit_size_32 a2a_floating_style a2a_vertical_style" style="left:0px; top:150px;">
		    <a class="a2a_button_facebook"></a>
		    <a class="a2a_button_twitter"></a>
		    <a class="a2a_button_google_plus"></a>
		    <a class="a2a_button_pinterest"></a>
		    <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
		</div>

		<div class="a2a_kit a2a_kit_size_32 a2a_floating_style a2a_default_style center-align" style="width: 80%; bottom: 0px;">
		    <a class="a2a_button_facebook"></a>
		    <a class="a2a_button_twitter"></a>
		    <a class="a2a_button_google_plus"></a>
		    <a class="a2a_button_pinterest"></a>
		    <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
		</div>

		<script async src="https://static.addtoany.com/menu/page.js"></script>
		
		<div id="modal1" class="modal modal-fixed-footer">
		    <div class="modal-content">
		      	<div id="login-form">
					<h5 class="center-align condensed light">Sign in to TechSense</h5>
					<div class="row">
						<div class="col s12 l6 m8 offset-m2 offset-l3">
						<ul class='collection center-align z-depth-1 error'>
							<li class='collection-item red-text'></li>
						</ul>
							<div class="card">
								<div class="card-content">
									<div class="row">
										<form class="col s12 l12" action="" method="post">
											<div class="row">
												<div class="input-field col s12">
													<i class="material-icons prefix">email</i>
													<input type="text" name="login_email" id="login_email"/>
													<label for="login_email">Email</label>
												</div>
												<div class="input-field col s12">
													<i class="material-icons prefix">lock</i>
													<input type="password" name="password" id="password" />
													<label for="password">Password</label>
												</div>
												<div class="col s4 l4" id="remember-me-container">
													<input type="checkbox" id="remember_me" name="remember_me">
													<label for="remember_me"> Remember Me</label>
												</div>
												<div class="col s4 l4 offset-l4 offset-s4">
													<a class="red-text" href="forgot_password.php">Forgot password?</a>
												</div>
												<div class="input-field col s12 l8 offset-l2 login_button_container">
													<input type="submit" class="btn waves-effect waves-light" value="login" id="login">
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="center-align">Or</div>
							<div class="row">
								<div class="col s12 l12">
									<a href="<?php echo $authUrl ?>" class="waves-effect waves-light btn red">Sign in with google</a>
								</div>
							</div>
							<div class="section">
								<ul class="collection center-align z-depth-1">
									<li class="collection-item">New to TechSense? <a href="register.php">Create an account</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
		      	<a href="javascript:void(0)" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
		    </div>
    	</div>

		<script async defer src="https://buttons.github.io/buttons.js"></script>
		<footer class="page-footer blue lighten-1">
			<div class="container">
				<div class="row">
					<div class="col s8 offset-s2 l5 m6 offset-m3">
						<div class="row">
							<div class="col s12 l8 m12">
								<img class="materialboxed responsive-img z-depth-2" style="height: 200px" data-caption="<?php echo ucwords($author->name); ?>" src="<?php echo Config::get('url/upload_dir').'/'.$author->image_url?>"> <!-- <?php //echo Config::get('url/upload_dir').'/'.$user->data()->image_url ?> -->
							</div>
						</div>
						<div class="row hide-on-med-and-down">
							<?php
								if($author->facebook_username != '')
								{
									echo "<div class='col l12' style='margin-bottom: 5px;'>
									 		<div class='fb-follow' data-href='https://www.facebook.com/".$author->facebook_username."' data-layout='button_count' data-size='large' data-show-faces='true'></div>
								 		</div>";
								}
								if($author->google_profileId != '')
								{
									echo "<div class='col l12'>
											<div class='g-follow' data-annotation='bubble' data-height='24' data-href='//plus.google.com/u/0/".$author->google_profileId."' data-rel='author'></div>
								 		</div>";
								}
								if($author->github_username != '')
								{
									echo "<div class='col l12'>
												<a class='github-button' href='https://github.com/{$author->github_username}' data-size='large' data-show-count='true' aria-label='Follow @{$author->github_username} on GitHub'>Follow @{$author->github_username}</a>
								 		</div>";
								}
								if($author->twitter_username != '')
								{
									
									echo "<div class='col l12'>
											<a class='twitter-follow-button' href='https://twitter.com/".$author->twitter_username."' data-size='large'> Follow @".$author->twitter_username."</a>
										</div>";
								}
							?>
						</div>
					</div>
					<div class="col s12 l7 m12">
						<div class="row">
							<div class="col l12 hide-on-med-and-down">
								<h5 class="white-text center-align">Writer's Info</h5>
							</div>
						</div>
						<div class="row">
							<div class="col s8 offset-s2 l12 center-align">
								<h5 class="white-text"><?php echo ucwords($author->name); ?></h5>	<!-- author's name of the blog -->
							</div>
						</div>
						<div class="row">
							<div class="col s12 l12">
								<p class="white-text"><?php echo ucfirst($author->user_description); ?></p>	
							</div>
						</div>
						<div class="row">
							<div class="col s12">
								<h6 class="white-text">Email: <?php echo $author->email; ?></h6>
							</div>
						</div>
						<div class="row hide-on-large-only">
							<?php								
								if($author->facebook_username != '')
								{
									echo "<div class='col s12' style='margin-bottom: 5px;'>
									 		<div class='fb-follow' data-href='https://www.facebook.com/".$author->facebook_username."' data-layout='button_count' data-size='large' data-show-faces='true'></div>
								 		</div>";
								}
								if($author->google_profileId != '')
								{
									echo "<div class='col s12'>
											<div class='g-follow' data-annotation='bubble' data-height='24' data-href='//plus.google.com/u/0/".$author->google_profileId."' data-rel='author'></div>
								 		</div>";
								}
								if($author->github_username != '')
								{
									echo "<div class='col s12'>
											<a class='github-button' href='https://github.com/".$author->github_username."' data-size='large' data-show-count='true' aria-label='Follow @".$author->github_username." on GitHub'>Follow ".$author->github_username."</a>
								 		</div>";
								}
								if($author->twitter_username != '')
								{
									
									echo "<div class='col s12'>
											<a class='twitter-follow-button' href='https://twitter.com/".$author->twitter_username."' data-size='large'> Follow @".$author->twitter_username."</a>
										</div>";
								}
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-copyright">
				<div class="container center-align">
					© 2017 Software Incubator
				</div>
			</div>
		</footer>

		<script src="Includes/js/jquery.min.js"></script>
	    <script src="https://use.fontawesome.com/819d78ad52.js"></script>
	    <script type="text/javascript" src="Includes/js/materialize.min.js"></script>
	    <script>
	    	if(typeof(Storage) !== "undefined")
	        {
	            if(sessionStorage.getItem("flashMessage") !== null)
	            {
	                Materialize.toast(sessionStorage.getItem("flashMessage"), 5000 ,'green');
	                sessionStorage.removeItem('flashMessage');
	            }
	        }
	    	$(document).ready(function(){
	    		// $('.nav-bar').removeClass('transparent');

	    		$(".button-collapse").sideNav();

	    		$('.modal').modal();

	    		// $('.likes-not-logged-in, .dislikes-not-logged-in').click(function(e){	// if user is not logged in, restrict him from voting
	    		// 	e.preventDefault();
	    		// 	// Materialize.toast("You need to login to vote", 5000, "red");
	    		// });

	    		$('.likes, .dislikes').click(function(e){
	                e.preventDefault();
	                var object = $(this);
	                
	                var blog_id = $(this).attr('data-attribute');
	                // var _token = $('#_token').attr('data-attribute');
	                var className = getClassName(this);

	                $.ajax({
	                    type: 'POST',
	                    url: 'blog_attributes.php',
	                    data: {blog_id: blog_id, field: className},
	                    dataType: "json",
	                    cache: false,
	                    success: function(response)
	                    {
	                        // var response = JSON.parse(response);
	                        console.log(response);
	                        // $('#_token').attr('data-attribute', response._token);
	                        if(response.error_status)
	                        {
	                            console.log(response.error);
	                            Materialize.toast(response.error, 5000, 'red');
	                            // return false;
	                        }
	                        else
	                        {
	                            if(response.blog_status == 1)
	                            {
	                            	$('.likes').find('i').css('color', 'green');
	                            	$('.dislikes').find('i').css('color', 'grey');
	                            }
	                            else if(response.blog_status == -1)
	                            {
	                            	$('.dislikes').find('i').css('color', 'red');
	                            	$('.likes').find('i').css('color', 'grey');
	                            }
	                            else
	                            {
	                            	$('.likes').find('i').css('color', 'grey');
	                            	$('.dislikes').find('i').css('color', 'grey');
	                            }
	                        }
	                    }
	                });
	            });

				$('#login').click(function(e) {
            		e.preventDefault();
            		var _token = $('#_token').attr('data-attribute');
            		var email = $('#login_email').val();
            		var password = $('#password').val();
            		var remember_me = null;
            		if($('#remember_me').prop('checked') == true)
            		{
            			remember_me = true;
            		}
            		else
            		{
            			remember_me = false;
            		}
            		if(typeof(Storage) !== "undefined")
					{
						sessionStorage.setItem('Redirect', document.URL);
					}

            		$.ajax({
            			url: 'login_backend.php',
            			data: {email: email, password: password, remember_me: remember_me, _token: _token},
            			type: 'POST',
            			dataType: "json",
            			cache: false,
            			success : function(response)
            			{
            				// var response = JSON.parse(response);
            				console.log(response);
            				if(response.error_status == true)
            				{
				        		$('.error').show().find('li').text(response.error);
				        		$('#password').val('');
            					console.log(response._token);
				        		if(response.error_code != 1)
				        		{
				        			$('#_token').attr('data-attribute', response._token);
				        		}
				        		Materialize.toast(response.error, 5000, "red");
            				}
            				else
            				{
            					$('#_token').attr('data-attribute', response._token);
            					if(typeof(Storage) !== 'undefined')
								{
									// sessionStorage.setItem('flashMessage', 'You have successfully logged in');
									if(sessionStorage.getItem('Redirect') !== null)
									{
										var url = sessionStorage.getItem('Redirect');
										sessionStorage.removeItem('Redirect');
										window.location = url;
									}
									else
										window.location = 'index.php';
								}
            				}
            			}
            		});
            	});

				function getClassName(object)
	            {
	                var className = $(object).attr('class');
	                if(className === 'likes')
	                {
	                    className = 'likes';
	                }
	                else if(className === 'dislikes')
	                {
	                    className = 'dislikes';
	                }
	                return className;
	            }

	    	});
	    </script>
	</body>
</html>

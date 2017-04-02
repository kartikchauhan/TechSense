<?php

//Include Google client library 
include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '32311185941-27cgcnttts33kbg0ujchu3nn4ffsbn2t.apps.googleusercontent.com'; //Google client ID
$clientSecret = '4NI-BVLx1A96qwYs0nJF7QVT'; //Google client secret
$redirectURL = 'http://localhost/TechWit/login.php'; //Callback URL

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('TechWit');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>
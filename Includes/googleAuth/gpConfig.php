<?php

//Include Google client library 
include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '285926229424-cm218npu455mta48b8r6uq4nassnedvj.apps.googleusercontent.com'; //Google client ID
$clientSecret = '2iQeZP1nxnGKoAAEnRUDMVmi'; //Google client secret
$redirectURL = 'https://techsense.herokuapp.com/login.php'; //Callback URL

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('TechSense');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>
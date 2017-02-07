<?php
require_once'Core/init.php';
require_once'Includes/googleAuth/gpConfig.php';	


if(Session::exists('googleToken'))
{
	$gClient->setAccessToken(Session::get('googleToken'));
}
else
{
	echo 'googleToken session does not exists';
	// Redirect::to('index.php');
}

if ($gClient->getAccessToken()) 
{
	//Get user profile data from google
	$gpUserProfile = $google_oauthV2->userinfo->get();
	
	//Initialize User class
	$user = new User();
	
	//Insert or update user data to the database
    $gpUserData = array(
        'oauth_provider'=> 'google',
        'oauth_uid'     => $gpUserProfile['id'],
        'email'         => $gpUserProfile['email'],
    );
    $userData = $user->googleCheckUser($gpUserData);

    if(!$userData)
    {
    	die("You are not a registered User of out website. <a href='register.php'>Register first</a>");
    }
    else
    {
    	Session::put(Config::get('session/session_name'), $userData->id);
    	Session::delete('googleToken');
    	Redirect::to('index.php');
    }
}

?>

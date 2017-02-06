<?php

include_once'Includes/googleAuth/gpConfig.php';	

if(isset($_GET['code']))
{
	$gClient->authenticate($_GET['code']);
	Session::put('googleToken', $gClient->getAccessToken());
	// $_SESSION['googleToken'] = ;
	Redirect::to($redirectURL);
}
else
{
	echo 'not set $_GET[code]';
}

if (Session::exists('googleToken')) {
	$gClient->setAccessToken(Session::get('googleToken'));
}
else
{
	echo 'not exists googleToken';
}

if ($gClient->getAccessToken()) 
{
	//Get user profile data from google
	$gpUserProfile = $google_oauthV2->userinfo->get();
	
	//Initialize User class
	// $user = new User();
	
	//Insert or update user data to the database
    $gpUserData = array(
        'oauth_provider'=> 'google',
        'oauth_uid'     => $gpUserProfile['id'],
        'email'         => $gpUserProfile['email'],
    );
    $userData = $user->googleCheckUser($gpUserData);

    Session::put(Config::get('session/session_name'), $userData->username);

    echo $userData->name;
    echo $userData->username;
	
}
else
{
	echo 'google not logged in';
	$authUrl = $gClient->createAuthUrl();
}

?>

<a href="<?php echo $authUrl ?>"><img src="Includes/googleAuth/images/glogin.png"></a>
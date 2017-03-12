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
    	echo
        "<html>
            <head>
                <style>
                    body
                    {
                        position: absolute;
                        background-color: #eee;
                    }
                    #login-form
                    {
                        position:absolute;
                        top:50px;
                        left:50px;
                    }
                </style>
            </head>
            <body>
            <a href='register.php'><h2>Register yourself first</h2></a>
            </body>
        </html>";
    }
    else
    {
    	Session::put(Config::get('session/session_name'), $userData->id);
    	Session::delete('googleToken');
        echo 
        "<script>
            if(typeof(Storage) !== 'undefined')
            {
                sessionStorage.setItem('flashMessage', 'You are successfully logged in');
                if(sessionStorage.getItem('Redirect') !== null)
                {
                    var url = sessionStorage.getItem('Redirect');
                    sessionStorage.removeItem('Redirect');
                    window.location = url;  
                }
                else
                    window.location = 'index.php';
            }
        </script>";
    	// Redirect::to('index.php');
    }
}

?>

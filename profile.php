<?php


require_once('Core/init.php');

if(!$username = Input::get('user') )
{
	Redirect::to('index.php');
}
else
{
	$user = new User($username);
	if(!$user->exists())
	{
		Redirect::to(404);
	}
	else
	{
		echo "ok";
	}
}


?>
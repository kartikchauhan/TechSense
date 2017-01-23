<?php

require_once'Core/init.php';

$user = DB::getInstance()->query("SELECT * FROM users");
if(!$user)
{
	echo "error occured";
}
else
{
	if($user->count() == 0)
		echo "No record found";
	else
		{
			echo $user->first()->password;
		}
}
?>
<?php

require_once'Core/init.php';

$user = DB::getInstance()->get('users', array('username', '=', 'kartik'));
if(!$user)
{
	echo "error occured";
}
else
{
	if($user->count() == 0)
		echo "No record found";
	else
		echo "We found a match";
}
?>
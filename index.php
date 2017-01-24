<?php

require_once'Core/init.php';

$user = DB::getInstance()->update('users', 6, array(
	'name'=>'kartik',
	'username'=>'testing',
	'email'=>'kartik',
	'password'=>'kartik'
	));

if(!$user)
{
	echo "error";
}
else
{
	echo "UPDATED ".$user->count()." rows";
}

?>
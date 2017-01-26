<?php

require_once'Core/init.php';

$user = new User();

if($user->isLoggedIn())
{
	echo $user->data()->name;
}
else
{
	echo "You need to log in or register";
}
?>
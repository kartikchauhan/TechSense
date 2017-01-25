<?php

require_once'Core/init.php';

if(Session::exists('username'))
{
	echo "Hi ".Session::get('username');
}

?>
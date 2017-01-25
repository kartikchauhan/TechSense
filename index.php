<?php

require_once'Core/init.php';

if(Session::exists('success'))
{
	echo Session::flash('success');
}

?>
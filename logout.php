<?php

require_once'Core/init.php';

$user = new User;
$user->logout();
Redirect::to('index.php');

?>
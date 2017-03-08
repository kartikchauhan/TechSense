<?php

require_once'Core/init.php';

$user = new User;
$user->logout();
echo 
"<script>
	if(typeof(Storage) !== 'undefined')
	{
		sessionStorage.setItem('flashMessage', 'You have successfully logged out');
	}
	window.location = 'index.php';
</script>";
// Redirect::to('index.php');

?>
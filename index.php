<?php

require_once'Core/init.php';

$user = new User;

if($user->isLoggedIn())
{
	echo "Hi ".$user->data()->name;
?>
	<hr>
	<a href="logout.php">Logout</a><hr>
	<a href="update.php">Update Profile</a>

<?php
}
else
{
?>
	<a href="login.php">Login</a>
<?php
}
?>
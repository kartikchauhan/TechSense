<?php

require_once'Core/init.php';

$user = new User;
if($user->isLoggedIn())
{
	echo "Hi ".$user->data()->name;
?>
	<a href="logout.php">Logout</a>

<?php
}
else
{
?>
	<a href="login.php">Login</a>
<?php
}
?>
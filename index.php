<?php

require_once'Core/init.php';

$user = new User;

if($user->isLoggedIn())
{
?>
	Hii <a href="profile.php?user=<?php echo $user->data()->username ?>"><?php echo $user->data()->name ?></a>
	<hr>
	<a href="logout.php">Logout</a><hr>
	<a href="update.php">Update Profile</a><hr>
	<a href="changePassword.php">Change Password</a><hr>

<?php
}
else
{
?>
	<a href="login.php">Login</a>
<?php
}
?>

<html>
<head>
	<title>ckeditor</title>
</head>
	<body>
		<textarea name="ckeditor" id="ckeditor"></textarea>
	</body>
	<script src="Includes/ckeditor/ckeditor.js"></script>
	<script type="text/javascript">
		CKEDITOR.replace('ckeditor');
	</script>
</html>
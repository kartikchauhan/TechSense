<?php

require_once'Core/init.php';

// if timestamp > 1 hour
// echo session expired try again
// else

?>


<!DOCTYPE html>
<html>
<head>
	<title>Reset Password</title>
</head>
<body>

	<form action="" method="post">
		<input type="password" name="password" id="password">
		<input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
		<input type="submit" value="Reset Password">
	</form>
</body>
</html>
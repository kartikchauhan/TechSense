<?php

require_once'Core/init.php';

?>

<!DOCTYPE html>
<html>
<head>
	<title>
		Forgot Password
	</title>
</head>
<body>

	<form action="forgot_password_backend.php" method="post">
		<label for="text">Email</label>
		<input type="email" name="email" id="email">
		<input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
		<input type="submit" value="submit">
	</form>
</body>
</html>
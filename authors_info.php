<?php

require_once'Core/init.php';

?>

<!DOCTYPE html>
<html>
<head>
	<title>
		Author's Information
	</title>
</head>
<body>
	<form action="authors_info_backend.php" method="post" enctype="multipart/form-data">
		<input type="file" name="profile_pic" id="profile_pic">
		<br>
		<label for="authors_info">Write about yourself</label>
		<textarea name="authore_info" id="authors_info" placeholder="Write about your interests and designation" rows="4"></textarea>
		<br>
		<label for="github_url">Github Url</label>
		<input type="text" name="github_url" id="github_url">
		<br>
		<label for="google_url">Google Url</label>
		<input type="text" name="google_url" id="google_url">
		<br>
		<label for="facebook_url">Facebook Url</label>
		<input type="text" name="facebook_url" id="facebook_url">
		<br>
		<input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
		<input type="submit" value="submit">
	</form>

</body>
</html>
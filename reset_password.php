<?php

require_once'Core/init.php';

if(Cookie::exists(Config::get('remember/reset_password')))	// is cookie of reset password exists then proceed
{
	if(Input::get('token'))	// check if token is sent in the url from the mail to this page
	{
		$_token = Input::get('token');
		if(Input::get('user'))	// check if user_email is sent in the url from the mail to this page
		{
			$email = Input::get('user');
			$user = new User;
			$userData = DB::getInstance()->get('users', array('email', '=', $email));	// fetch record to verify user
			if($userData->count() > 0)	// if user exists then proceed
			{
				$userData = $userData->first();
				if($userData->email === $email)	// if fetched_email from database matches with the value sent from mail then proceed
				{
					if($userData->forgot_password_token === $_token)	// if fetched_token from database matches with the value sent from mail then proceed
					{
					?>
						<form action="reset_password_backend.php" method="post">
							<label for="password">New Password</label>
							<input type="password" name="password" id="password">
							<label for="confirm_password">Confirm Password</label>
							<input type="password" name="confirm_password" id="confirm_password">
							<input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
							<input type="hidden" name="email" id="email" value="<?php echo $email; ?>">
							<input type="submit" value="submit">
						</form>

					<?php	
						// Cookie::delete(Config::get('remember/reset_password'));
					}
					else
					{
						Redirect::to('forgot_password.php');
					}
				}
				else
				{
					Redirect::to('forgot_password.php');
				}
			}
		}
		else
		{
			Redirect::to('forgot_password.php');
		}
	}
	else
	{
		Redirect::to('forgot_password.php');
	}
}
else
{
	die('Session expired. Try again');	// if cookie does not exists then tell user to reset password again 
}

?>
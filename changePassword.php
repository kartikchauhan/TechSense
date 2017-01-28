<?php

require_once'Core/init.php';

$user = new User;

if(!$user->isLoggedIn())
{
	Redirect::to('index.php');
}

if(Input::exists('post'))
{
	if(Token::check(Input::get('_token')))
	{
		$Validate = new Validate;
		$Validate->check($_POST, array(
			'current_password' => array(
				'required' => true,
				'min' => 2,
				),
			'new_password' => array(
				'required' => true,
				'min' => 2,
				),
			'confirm_new_password' => array(
				'required' => true,
				'min' => 2,
				'matches' => 'new_password'
				),
			));

		if($Validate->passed())
		{
			if(Hash::make(Input::get('current_password'), $user->data()->salt) === $user->data()->password)
			{
				try
				{
					$salt = Hash::salt(32);

					$user->update('users', $user->data()->id, array(
						'password' => Hash::make(Input::get('new_password'), $salt),
						'salt' => $salt
						));

					echo "password has been changed";
				}
				catch(Exception $e)
				{
					$e->getMessage();
				}
			}
			else
			{
				echo "Your current password doesn't match";
			}
			// $salt = Hash::salt(32);
			// $hash = Hash::make(Input::get('new_password'), $salt);
		}
		else
		{
			foreach($Validate->errors() as $error)
			{
				echo $error.'<br>';
			}
		}
	}
}

?>

<form action="" method="post">

<div>
	<label for="current_password">Current Password</label>
	<input type="password" name="current_password" id="current_password">
</div>

<div>
	<label for="new_password">New Password</label>
	<input type="password" name="new_password" id="new_password">
</div>

<div>
	<label for="confirm_new_password">Confirm Password</label>
	<input type="password" name="confirm_new_password" id="confirm_new_password">
</div>

<input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">

<input type="submit" value="change password">

</form>
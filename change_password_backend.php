<?php

require_once'Core/init.php';

$user = new User;

if(Input::exists('post'))
{
	if(Token::check(Input::get('_token')))
	{
		$json['_token'] = Token::generate();
		$json['error_status'] = false;

		if($user->isLoggedIn())
		{
			$Validate = new Validate;
			$Validate->check($_POST, array(
				'current_password' => array(
					'required' => true,
					),
				'password' => array(
					'required' => true,
					'min' => 6,
					),
				'confirm_password' => array(
					'required' => true,
					'min' => 2,
					'matches' => 'password'
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
							'password' => Hash::make(Input::get('password'), $salt),
							'salt' => $salt
							));
					}
					catch(Exception $e)
					{
						$e->getMessage();
					}
				}
				else
				{
					$json['error_status'] = true;
					$json['error'] = "Your current password doesn't match";
				}
			}
			else
			{
				$json['error_status'] = true;
				$json['error'] = $Validate->errors()[0];
			}
		}
		else
		{
			$json['error_status'] = true;
			$json['error'] = "You need to log in";
		}
		header("Content-Type: application/json", true);
		echo json_encode($json);
	}
	else
	{
		$json['error_status'] = true;
		$json['error'] = "Token mismatch error, try again after refreshing the page";
		header("Content-Type: application/json", true);
		echo json_encode($json);
	}
}
else
{
	Redirect::to('change_password.php');
}

?>
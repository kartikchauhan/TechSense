<?php

require_once'Core/init.php';

if(Input::exists())
{
	if(Token::check(Input::get('_token')))
	{
		$json['_token'] = Token::generate();
		$Validate = new Validate;
		$Validate->check($_POST, array(
			'name'     		   => array(
				'required' => true,
				'min'      => 2,
				'max'      => 25
				),
			'username' 		   => array(
				'required' => true,
				'min'      => 2,
				'max'      => 25,
				'unique'   => 'users'
				),
			'email'            => array(
				'required' => true,
				'email'    => true,
				'unique'   => 'users'
				),
			'password' 		   => array(
				'required' => true,
				'min'      => 6,
				),
			'confirm_password' => array(
				'required' => true,
				'matches'  => 'password'
				),
			));

		if($Validate->passed())
		{
			$json['username_exists'] = false;
			$user = new User();	
			$salt = Hash::salt(32);
			$user->create('users', array(
				'name' => Input::get('name'),
				'username' => Input::get('username'),
				'email'=> Input::get('email'),
				'password'=> Hash::make(Input::get('password'), $salt),
				'salt' => $salt,
				'created_on' => Date('Y-m-d H:i:s'),
				'image_url' => 'default.jpg'	// saving a default image while creating new user
				));
			$json['status'] = 0;
			$json['message'] = "You're account has been successfully created";
		}
		else
		{
			$json['status'] = 2;
			$json['message'] = $Validate->errors()[0];
			if($Validate->isUsernameExists())
			{
				$json['username_exists'] = true;
				$json['usernames_available'] = generateUsernames(Input::get('name'));
			}
		}
		echo json_encode($json);
	}
}

function generateUsernames($name)
{
	$name = explode(' ', $name);
	$tokens = array('_', '-', '');
	for($i=0; $i<3; $i++)
	{

		$username[$i] = mt_rand(9,99)%2 ? current($name).$tokens[array_rand($tokens)].mt_rand(9, 9999) : end($name).$tokens[array_rand($tokens)].mt_rand(9, 9999);
	}
	return $username;
}

?>

<?php

require_once'Core/init.php';

if(Input::exists('post'))
{
	if(Token::check(Input::get('_token')))
	{
		$json['_token'] = Token::generate();
		$Validate = new Validate;
		$Validate->check($_POST, array(
			'email' => array(
				'required' => true
				),
			'password' => array(
				'required' => true,
				'min' => 6
				)
			));
		if($Validate->passed())
		{
			$user = new User;
			if($user->login(Input::get('email'), Input::get('password'), Input::get('remember_me')))
			{
				$json['status'] = 0;	// status 0 => when successfully logged in
				$json['message'] = "You've been successfully logged in";
				// Redirect::to('index.php');
			}
			else
			{
				$json['status'] = 1;	// status 1 => when credentials are wrong
				$json['message'] = 'Either email or password wrong';
				
			}
		}
		else
		{
			$json['status'] = 2;	// status 2 => if validation fails
			$json['message'] = $Validate->errors()[0];
		}
		echo json_encode($json);
	}
}
else
{
	Redirect::to('index.php');
}


?>
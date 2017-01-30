<?php

require_once'Core/init.php';

if(Input::exists())
{
	if(Token::check(Input::get('_token')))
	{
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
				'min'      => 7,
				'max'      => 40,
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
			$user = new User();	// we created an instance of User class because we wanted to invoke it's constructor to get instance of DB class, otherwise we could've called create function statically
			$salt = Hash::salt(32);
			$user->create('users', array(
				'name' => Input::get('name'),
				'username' => Input::get('username'),
				'email'=> Input::get('email'),
				'password'=> Hash::make(Input::get('password'), $salt),
				'salt' => $salt,
				'created_on' => Date('Y-m-d H:i:s')
				));
			$json['status'] = 0;
			$json['message'] = "You're account has been successfully created";
			// Redirect::to('index.php');
			// Session::flash('success', "You've been successfully registered");
			// header('Location: index.php');
		}
		else
		{
			$json['status'] = 2;
			$json['message'] = $Validate->errors()[0];
		}
		echo json_encode($json);
	}
}

?>

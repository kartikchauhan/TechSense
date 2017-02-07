<?php

require_once'Core/init.php';

if(Input::exists('post'))
{
	if(Token::check(Input::get('_token')))
	{
		// $json['_token'] = Token::generate();
		$Validate = new Validate;
		$Validate->check($_POST, array(
			'password' => array(
				'required' => true,
				'min' => 6
				),
			'confirm_password' => array(
				'required' => true,
				'matches' => 'password'
				)
			));
		if($Validate->passed())
		{
			$user = new User;
			$userData = DB::getInstance()->get('users', array('email', '=', Input::get('email')));
			$salt = Hash::salt(32);
			$userId = $userData->first()->id;
			$user->update('users', $userId, array(
				'password' => Hash::make(Input::get('password'), $salt),
				'salt' => $salt
				));
			echo 'updated';
		}
		else
		{
			echo 'password could not be changed';
		}
	}
}





?>
<?php

require_once'Core/init.php';

$user = new User;


if(Input::exists('post'))
{
	if(Token::check(Input::get('_token')))
	{
		$json['error_code'] = 0;	// error_code = 0 => for all type of errors except token_mismatch
		$json['error_status'] = false;
		$json['_token'] = Token::generate();

		$email = Input::get('email');
		$Validate = new Validate;
		$Validate->check($_POST, array(
			'email' => array(
				'required' => true,
				'email' => true
				),
			'password' => array(
				'required' => true,
				'min' => 6
				)
			));
		if($Validate->passed())
		{
			if($user->login(Input::get('email'), Input::get('password'), Input::get('remember_me')))
			{
				$json['error_status'] = false;
				// creating a flashMessage to show user once he's logged in
				// checking if there's any javascript session 'Redirect', here being used for redirecting the user to the page where he came from before logging in
				// redirecting the user to the page from where ha came before he was logged in
				// redirecting the user to the home page if no javascript session exists
				// echo 
				// "<script>
				// 	if(typeof(Storage) !== 'undefined')
				// 	{
				// 		sessionStorage.setItem('flashMessage', 'You have successfully logged in');
				// 		if(sessionStorage.getItem('Redirect') !== null)
				// 		{
				// 			var url = sessionStorage.getItem('Redirect');
				// 			sessionStorage.removeItem('Redirect');
				// 			window.location = url;
				// 		}
				// 		else
				// 			window.location = 'index.php';
				// 	}
				// </script>";
			}
			else
			{
				$json['error_status'] = true;
				$json['error'] = "Either email or password wrong";
			}
		}
		else
		{
			$json['error_status'] = true;
			$json['error'] = $Validate->errors()[0];
		}
		header("Content-Type: application/json", true);
		echo json_encode($json);
	}
	else
	{
		$json['error_code'] = 1;	// error_code = 1 => for token_mismatch error
		$json['error_status'] = true;
		$json['error'] = "Token mismatch error, try again after refreshing the page";
		header("Content-Type: application/json", true);
		echo json_encode($json);
	}
}
else
{
	Redirect::to('index.php');
}

?>
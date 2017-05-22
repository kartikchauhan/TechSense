<?php
// changes that needed to be implement that are linked with this file - 
// 1) creating a new record in forgot_password table because no method avaiable to insert a record with "WHERE" consition avaialble
// 2) no clickable reset_password being given right now in the email. 
// 3) no idea how to comapare token value sent in email to the value stored in database. Same with timestamp

require_once'Core/init.php';
require 'Includes/PHPMailer/PHPMailerAutoload.php';

if(Input::exists('post'))
{
	if(Token::check(Input::get('_token')))
	{
		$json['error_code'] = 0;	// error_code = 0 => for all type of errors except token_mismatch
		$json['error_status'] = false;
		$json['_token'] = Token::generate();
		$Validate = new Validate;
		$Validate->check($_POST, array(
			'email' => array(
				'required' => true,
				'email' => true
				)
			));

		if($Validate->passed())
		{
			$user = new User;
			$userData = DB::getInstance()->get('users', array('email', '=', Input::get('email')));
			if($userData->count()==1)
			{
				date_default_timezone_set('asia/kolkata');
				$hash = Hash::unique(); // create a unique hash to store it in the database and pass it in the mail to the user
				$userData = $userData->first();
				$email = $userData->email;	// get user email address from database
				$userId = $userData->id;	// get user's id to update record

				Cookie::put(Config::get('remember/reset_password'), $hash, 300000);	// creating session of 5 minutes. The user can only reset his password in this 5 minute duration
				$user->update('users', $userId, array('forgot_password_token' => $hash));	// store hash in the database

				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->SMTPDebug = 1;
				$mail->Debugoutput = 'html';
				$mail->Host = 'smtp.gmail.com';
				$mail->Port = 587;
				$mail->SMTPSecure = 'tls';
				$mail->SMTPAuth = true;
				$mail->Username = "chauhan.kartik25@gmail.com";
				$mail->Password = "Kartik@25K"; 
				$mail->setFrom("chauhan.kartik25@gmail.com", "kartik chauhan");
				$mail->addAddress($email, 'kartik chauhan');
				$mail->subject = "password reset link";
				// now sending msg including hash along with user's email address.
				$mail->msgHTML("This is a password reset link. Click on it and change your password within 5 minutes else session will expire<br> <a href='https://www.techwit.herokuapp.com/reset_password.php?token={$hash}&user={$email}'>Click this link to reset the password</a>");
				try
				{
					if(!$mail->send())
						throw new Exception("Coudn't reset password right now. Please try resetting password after few minutes");
				}
				catch(Exception $e)
				{
					$json['error_status'] = true;
					$json['error'] = $e->getMessage();
				}
			}
			else
			{
				$json['error_status'] = true;
				$json['error'] = 'no such user exists';
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
	Redirect::to('forgot_password.php');
}

?>
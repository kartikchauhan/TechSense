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
		$Validate = new Validate;
		$Validate->check($_POST, array(
			'email' => array(
				'required' => true,
				'min'      => 7,
				'max'      => 40
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
				$mail->SMTPDebug = 0;
				$mail->Debugoutput = 'html';
				$mail->Host = 'smtp.gmail.com';
				$mail->Port = 587;
				$mail->SMTPSecure = 'tls';
				$mail->SMTPAuth = true;
				$mail->Username = "chauhan.kartik25@gmail.com";
				$mail->Password = "Kartik@25K";
				$mail->setFrom("chauhan.kartik25@gmail.com", "kartik chauhan");
				$mail->addAddress($email, 'anonymous');
				$mail->subject = "password reset link";
				// now sending msg including hash along with user's email address.
				$mail->msgHTML("This is a password reset link. Click on it and change your password within 1 hour else session will expire<br> <a href='http://localhost/Blog_temp/reset_password.php?token={$hash}&user={$email}'>Click this link</a>");
				try
				{
					if(!$mail->send())
						throw new Exception("Coudn't reset password right now. Please try resetting password after few minutes");
					echo 'mail sent successfully';
				}
				catch(Exception $e)
				{
					echo $e->getMessage();
				}
			}
			else
			{
				echo 'no such user exists';
			}
		}
		else
		{
			$error = $Validate->errors()[0];
		}
	}

}

?>
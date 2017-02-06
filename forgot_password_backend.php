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
			$user = DB::getInstance()->get('users', array('email', '=', Input::get('email')));
			if($user->count()==1)
			{
				date_default_timezone_set('asia/kolkata');
				$hash = Hash::unique();
				$email = Input::get('email');
				$user->insert('forgot_password', array(
					'email' => $email,
					'token' => $hash,
					'timestamp' => date('Y-m-d h:i:s')
					));
				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->SMTPDebug = 0;
				$mail->Debugoutput = 'html';
				$mail->Host = 'smtp.gmail.com';
				$mail->Port = 587;
				$mail->SMTPSecure = 'tls';
				$mail->SMTPAuth = true;
				$mail->Username = "chauhan.kartik25@gmail.com";
				$mail->Password = "Kartik@25";
				$mail->setFrom("chauhan.kartik25@gmail.com", "kartik chauhan");
				$mail->addAddress($email, 'anonymous');
				$mail->subject = "password reset link";
				$mail->msgHTML("This is a password reset link. Click on it and change your password within 1 hour else session will expire<br> {$hash} <br> {$email}");
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
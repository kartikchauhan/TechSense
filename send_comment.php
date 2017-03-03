<?php

require_once'Core/init.php';

$user = new User;

if(!$user->isLoggedIn())
{
	Redirect::to('index.php');
}

if(Input::exists())
{
	if(Token::check(Input::get('_token')))
	{
		$json['error_status'] = false;
		$json['_token'] = Token::generate();

		$Validate = new Validate;

		$Validate->check($_POST, array(
			'comment' => array(
				'required' => true
				)
			));

		if($Validate->passed())
		{
			$user_id = $user->data()->id;
			try
			{
				$comment = DB::getInstance()->insert('comments', array(
				'comment' => Input::get('comment'),
				'blog_id' => Input::get('blog_id'),
				'user_id' => $user_id
				));

				if(!$comment)
					throw new Exception("Unable to add your comment right now. Please try again later");					
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
			$json['error'] = $Validate->errors()[0];
		}
		echo json_encode($json);
	}
	else
	{
		Redirect::to('index.php');
	}
}
else
{
	Redirect::to('index.php');
}

?>
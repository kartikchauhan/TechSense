<?php

require_once'Core/init.php';

$user = new User;

if(Input::exists('post'))
{
	if(Token::check(Input::get('_token')))
	{
		$json['error_status'] = false;
		$json['_token'] = Token::generate();

			if($user->isLoggedIn())
			{
			$Validate = new Validate;

			$Validate->check($_POST, array(
				'title' => array(
					'required' => true,
					'min' => 5,
					'max' => 100,
					),
				'description' => array(
					'required' => true,
					'min' => 30,
					'max'=> 200,
					),
				'blog'=> array(
					'required' => true,
					'min' => 500,
					)
				));

			if($Validate->passed())
			{
				$blog = DB::getInstance()->insert('blogs', array(
					'title' => Input::get('title'),
					'description' => Input::get('description'),
					'blog' => Input::get('blog'),
					'users_id' => $user->data()->id
					));
				$lastInsertId = DB::getInstance()->getLastInsertId();
				$tags = Input::get('blog_tags');
				foreach($tags as $tag)
				{
					DB::getInstance()->insert('blog_tags', array(
						'blog_id' => $lastInsertId,
						'tags' => $tag
					));
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
			$json['error'] = "You need to login to write a blog";
		}


		echo json_encode($json);
	}
	else
	{
		$json['error_status'] = true;
		$json['error'] = "Token mismatch error, try again by refreshing page";
		echo json_encode($json);
	}
}
else
{
	Redirect::to('authors_info.php');
}


?>
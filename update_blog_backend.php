<?php

require_once'Core/init.php';

if(Input::exists('post'))
{
	if(Token::check(Input::get('_token')))
	{
		$json['error_status'] = false;
		$json['_token'] = Token::generate();

		$user = new User;
		
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
				$blog = new Blog;
				try
				{
					$blog->update('blogs', Input::get('blog_id'), array(
						'title' => Input::get('title'),
						'description' => Input::get('description'),
						'blog' => Input::get('blog')
						));
					if(!$blog)
						throw new Exception("Unable to update blog. Please try again later");

					DB::getInstance()->delete('blog_tags', array('blog_id', '=', Input::get('blog_id')));

					$tags = Input::get('blog_tags');

					foreach($tags as $tag)
					{
						DB::getInstance()->insert('blog_tags', array(
							'blog_id' => Input::get('blog_id'),
							'tags' => $tag
						));
					}
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
		}
		else
		{
			$json['error_status'] = true;
			$json['error'] = "You need to login to update this blog";
		}
		
		header("Content-Type: application/json", true);
		echo json_encode($json);
	}
	else
	{
		$json['error_status'] = true;
		$json['error'] = "Token mismatch error, try again after refreshing the page";
		header("Content-Type: application/json", true);
		echo json_encode($json);
	}
}
else
{
	Redirect::to('authors_info.php');
}

?>
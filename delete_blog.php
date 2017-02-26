<?php

require_once'Core/init.php';

$user = new User;

if(!$user->isLoggedIn())
{
	Redirect::to('authors_info.php');
}

if(Input::exists())
{
	if(Token::check(Input::get('_token')))
	{
		$json['error_status'] = false;
		$json['_token'] = Token::generate();

		$blog = new Blog;
		try
		{
			if(!$blog->deleteBlog('blogs', array('id', '=', Input::get('blog_id'))))
				throw new Exception("Some error occured while deleting this blog. Please try again later");
			if(!$blog->count())
			{
				$json['error_status'] = true;
				$json['error'] = "Blog doesn't exists";
			}
		}
		catch(Exception $e)
		{
			$json['error_status'] = true;
			$json['error'] = $e->getMessage();
		}
		echo json_encode($json);
	}
}

?>
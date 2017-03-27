<?php

require_once'Core/init.php';

$user = new User;

if(Input::exists('post'))
{
	// if(Token::check(Input::get('_token')))
	// {
		$json['error_status'] = false;
		// $json['_token'] = Token::generate();

		if($user->isLoggedIn())
		{
			$comment = new Comment;
			try
			{
				if(!$comment->deleteComment('comments', array('id', '=', Input::get('comment_id'))))
					throw new Exception("Some error occured while deleting this comment. Please try again later");
				if(!$comment->count())
				{
					$json['error_status'] = true;
					$json['error'] = "Comment doesn't exists";
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
			$json['error'] = "You need to log in to perform this action";
		}
		header("Content-Type: application/json", true);
		echo json_encode($json);
	// }
	// else
	// {
	// 	$json['error_status'] = true;
	// 	$json['error'] = "Token mismatch error, try again after refreshing the page";
	// 	echo json_encode($json);
	// }
}
else
{
	Redirect::to('index.php');
}

?>
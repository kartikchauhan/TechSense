<?php

require_once'Core/init.php';

if(Input::exists('post'))
{
	if(Token::check(Input::get('_token')))
	{
		$json['error_staus'] = false;
		$json['_token'] = Token::generate();	// generating token for another request
		$field = Input::get('field');	// fetching the field to be updated
		$flag = true;	// to check if user has already set the attributes like 'view', 'like', 'dislike'

		$blogAttributes = new Blog;

		if($field === 'likes')
		{
			if($blogAttributes->checkLike())
			{
				$flag = false;
				$json['error_staus'] = true;
				$json['error_status'] = "You've already upvoted this post.";
			}
			else
			{
				$count = Input::get('count') + 1;	// increasing the value of count if field is 'likes'
			}
		}
		else if($field === 'dislikes')
		{
			if($blogAttributes->checkDislike())
			{
				$flag = false;
				$json['error_staus'] = true;
			$json['error_status'] = "You've already downvoted this post.";
			}
			else
			{
				$count = Input::get('count') - 1;	// increasing the value of count if field is 'likes'
			}
		}
	
		if($flag)
		{
			$json = insert_data($json, $count);
		}
		
		echo json_encode($json);
	}
}

	function insert_data($json, $count)
	{
		$db = DB::getInstance();
		if($db->update('blogs', Input::get('blog_id'), array(Input::get('field') => $count)))
		{
			$json['count'] = $count;
		}
		else
		{
			$json['error_staus'] = $db->error();
			$json['error'] = $e->getMessage();
		}

		return $json;
	}


?>
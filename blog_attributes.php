<?php

require_once'Core/init.php';

if(Input::exists('post'))
{
	if(Token::check(Input::get('_token')))
	{
		$json['error_staus'] = false;
		$json['_token'] = Token::generate();	// generating token for another request
		$field = Input::get('field');	// fetching the field to be updated
		if($field === 'likes')
		{
			$count = Input::get('count') + 1;	// increasing the value of count if field is 'likes'
		}
		else if(Input::get('field') === 'dislikes')
		{
			$count = Input::get('count') - 1;	// increasing the value of count if field is 'likes'
		}
	
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
		// if($currentRecord = DB::getInstance()->get('blogs', array('id', '=', Input::get('blog_id')))->first())
			// throw new Exception("Error Processing Request");
		
		echo json_encode($json);
	}
}


?>
<?php

require_once'Core/init.php';

$user = new User;

if(Input::exists())
{
	if(Token::check(Input::get('_token')))
	{
		$json['error_status'] = false;
		$json['_token'] = Token::generate();

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
				'users_id' => 1,
				));
		}
		else
		{
			$json['error_status'] = true;
			$json['error'] = $Validate->errors()[0];
		}

		echo json_encode($json);
	}
}


?>
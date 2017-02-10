<?php

require_once'Core/init.php';

if(Input::exists('post'))
{
	if(Token::check(Input::get('_token')))
	{
		$Validate = new Validate();

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
			foreach($Validate->errors() as $error)
			{
				echo $error.'<br>';				
			}
		}
	}
}

?>
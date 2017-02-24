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

		// $blogs = DB::get()
		//  leaving here for the deletion process, since no foreign key set yet. Need to figure out how
		//  to work with objects of another table in PDO.
		echo json_encode($json);
	}
}

?>
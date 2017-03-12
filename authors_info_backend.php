<?php

require_once'Core/init.php';

$user = new User;

if(Input::exists())
{
	if(Token::check(Input::get('_token')))
	{
		$json['error_status'] = false;
		$json['_token'] = Token::generate();
		if($user->isLoggedIn())
		{
			$Validate = new Validate;
			$Validate->check($_POST, array(
				"name" => array(
					"required" => true,
					'min' => 2,
					'max' => 25
					),
				"github_username" => array(
					"min" => 10
					),
				"facebook_username" => array(
					"min" => 10
					),
				"description" => array(
					"required" => true,
					"min" => 10
					)
				));
			if($Validate->passed())
			{
				$fields = array();
				$fields['user_description'] = Input::get('description');		// add description to the array that is gonna be passed for the insert query
				$fields["name"] = Input::get('name');	// add github_username if user has entered github_username
				$fields["github_username"] = Input::get('github_username');	// add github_username if user has entered github_username
				$fields["facebook_username"] = Input::get('facebook_username');
				$fields["twitter_username"] = Input::get("twitter_username");
				if(!empty($_FILES))	// insert the data if no image uploaded
				{
					$target_dir = Config::get('url/upload_dir').'/';	// target directory where images are gonna be stored
					$target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);	// basename of our file
					$target_file_type = getimagesize($_FILES["profile_pic"]["tmp_name"])[2];	// getting file_type (extension too)
				 	try
					{
						if(!in_array($target_file_type, array(IMAGETYPE_JPEG, IMAGETYPE_PNG)))
						{
							throw new Exception("Incompatible file extension. Only jpeg or png format files allowed");
						}
						if(!move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file))
						{
							throw new Exception("couldn't process profile updation. Please try again later");
						}
						$fields["image_url"] = basename($_FILES["profile_pic"]["name"])	;	// creating index "image_url" if image uploaded is verified
						updateInfo('users', $user->data()->id, $fields);
					}
					catch(Exception $e)
					{
						$json['error_status'] = true;
						$json['error'] = $e->getMessage();
					}
				}
				else
				{
					updateInfo('users', $user->data()->id, $fields);	// insert the data even if there's no image
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
			$json['error'] = "You need to log in";
		}
		
		echo json_encode($json);
	}
	else
	{
		Redirect::to('authors_info.php');
	}
}
else
{
	Redirect::to('authors_info.php');
}

function updateInfo($table, $id, $fields)
{
	global $user;	// setting $user as global so that it could be used in functions
	try 
	{
		$user->update($table, $id, $fields);
	}
	catch(Exception $e)
	{
		$json['error_status'] = true;
		$json['error'] = $e->getMessage();
	}
}

?>
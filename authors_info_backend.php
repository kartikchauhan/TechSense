<?php

require_once'Core/init.php';

if(Input::exists())
{
	if(Token::check(Input::get('_token')))
	{
		$Validate = new Validate;
		$Validate->check($_POST, array(
			"github_url" => array(
				"min" => 10
				),
			"twitter_url" => array(
				"min" => 10
				),
			"facebook_url" => array(
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
			$fields['description'] = Input::get('description');
			if(!empty(Input::get('github_url')))
			{
				$fields["github_url"] = Input::get('github_url');
			}
			if(!empty(Input::get('facebook_url')))
			{
				$fields["facebook_url"] = Input::get('facebook_url');
			}
			if(!empty(Input::get('twitter_url')))
			{
				$fields["twitter_url"] = Input::get("twitter_url");
			}
			if(!empty($_FILES["profile_pic"]["name"]))
			{
				$target_dir = Config::get('url/upload_dir').'/';
				$target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
				$target_file_type = getimagesize($_FILES["profile_pic"]["tmp_name"])[2];	// getting file_type (extension too)
			 	try
				{
					if(!in_array($target_file_type, array(IMAGETYPE_JPEG, IMAGETYPE_PNG)))
					{
						throw new Exception("Uncompatible file extension. Only jpeg or png format files allowed");
					}
					echo "hey";
					if(!move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file))
					{
						throw new Exception("couldn't process profile updation. Please try again later");
					}
					$fields["image_url"] = basename($_FILES["profile_pic"]["name"])	;

					try 
					{
						if(!DB::getInstance()->insert('authors_info', $fields))
							throw new Exception("Unable to insert values right now. Please try again later");
						echo "record has been inserted";
					}
					catch(Exception $e)
					{
						echo $e->getMessage();
					}
				}
				catch(Exception $e)
				{
					echo $e->getMessage();
				}
			}
		}
		else
		{
			foreach ($Validate->errors() as $error)
			{
				echo $error."<br>";
			}
		}
		
	}
}


?>
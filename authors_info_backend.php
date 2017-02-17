<?php

require_once'Core/init.php';

if(Input::exists())
{
	if(Token::check(Input::get('_token')))
	{
		$target_dir = "Includes/uploads/";
		$target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
		$target_file_type = getimagesize($_FILES["profile_pic"]["tmp_name"])[2];	// getting file_type (extension too)
     	try
		{
			if(!in_array($target_file_type, array(IMAGETYPE_JPEG, IMAGETYPE_PNG)))
			{
				throw new Exception("Uncompatible file extension. Only jpeg or png format files allowed");
			}
			if(!move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file))
			{
				throw new Exception("couldn't process profile updation. Please try again later");
			}

		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}

    // if(in_array($image_type , array(IMAGETYPE_JPEG ,IMAGETYPE_PNG)))
    // {
    //     echo "yes";
    // }
    // else
    // {
    // 	echo "false";
    // }
		
	}
}

?>
<?php

class Redirect
{
	public static function to($location = null)
	{
		if($location)
		{
			if(is_numeric($location))
			{
				header('HTTP/1.0 404 Not Found');
				include('Includes/Errors/404.html');
				exit();
			}
			else
			{
				header('Location: '.$location);
				exit();
			}
		}
	}
}

?>
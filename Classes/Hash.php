<?php

class Hash
{
	public static function make($string, $salt = '')
	{
		return hash('sha256', $string.$salt);	// we are appending $salt to the password here.
												// it's being added for more secured passwords. At time of log in we check with this value, if it matches then proceed
	}

	public static function salt($length)
	{
		return mcrypt_create_iv($length);
	}

	public static function unique()
	{
		return self::make(uniqid());
	}

}


?>
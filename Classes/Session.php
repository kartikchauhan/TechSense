<?php

class Session
{
	public static function exists($name)
	{
		if(isset($_SESSION[$name]))
			return true;
		else
			return false;
	}

	public static function put($name, $value)
	{
   		return $_SESSION[$name] = $value;
	}

	public static function get($name)
	{
		return $_SESSION[$name];
	}

	public static function delete($name)
	{
		if(self::exists($name))
			unset($_SESSION[$name]);
	}

	public static function flash($name, $value = '')
	{
		if(self::exists($name))
		{
			$flash_message = self::get($name);
			self::delete($name);
			return $flash_message;
		}
		else
		{
			self::put($name, $value);
		}
	}

}

?>
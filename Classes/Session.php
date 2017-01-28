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
		echo "reached to the flash method<br>";
		if(self::exists($name))
		{
			echo "flash exists";
			$flash_message = self::get($name);
			self::delete($name);
			return $flash_message;
		}
		else
		{
			echo "flash does not exists";
			self::put($name, $value);
		}
	}

}

?>
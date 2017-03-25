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

	public static function put($name, $value, $token = false)
	{
		if($token === true)
		{
			if(!isset($_SESSION[$name]))
			{
				$_SESSION[$name] = array();
				array_push($_SESSION[$name], $value);
				return $value;
			}
			else
			{	
				array_push($_SESSION[$name], $value);
				return $value;
			}
		}
		else
		{
   			return $_SESSION[$name] = $value;
		}
	}

	public static function get($name)
	{
		return $_SESSION[$name];
	}

	public static function delete($name, $key = null)
	{
		if(self::exists($name))
		{
			if($key != null)
			{
				unset($_SESSION[$name][$key]);
			}
			else
			{
				unset($_SESSION[$name]);
			}
		}
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
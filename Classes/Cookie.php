<?php

class Cookie
{
	public static function exists($name)
	{
		if(isset($_COOKIE[$name]))
			return true;
		else
			return false;
	}

	public static function put($name, $value ,$cookie_expiry)
	{
		setcookie($name, $value, time() + $cookie_expiry, '/');
	}

	public static function get($name)
	{
		return $_COOKIE[$name];
	}

	public static function delete($name)
	{
		if(self::exists($name))
		{
			Session::delete($name);
			self::put($name, '', time() - 1);

		}
	}
}

?>
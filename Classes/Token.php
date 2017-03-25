<?php

class Token
{
	public static function generate()
	{
		return Session::put(Config::get('session/token_name'), md5(uniqid()), true);
		// return Session::put(Config::get('session/token_name'), md5(uniqid()));
	}

	public static function check($token)
	{
		$token_name = Config::get('session/token_name');
		if(Session::exists($token_name) && in_array($token, Session::get($token_name)))
		{
			if(($key = array_search($token, Session::get($token_name))) !== false)
			{
				Session::delete($token_name, $key);
			    return true;
			}
		}
		return false;
	}
}

?>
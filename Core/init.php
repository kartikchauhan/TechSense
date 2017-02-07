<?php
session_start();

$GLOBALS['config'] = array(

	'mysql'=>array(
		'host'=>'127.0.0.1',
		'user'=>'root',
		'password'=>'',
		'database'=>'blog'
		),
	'remember'=>array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800
		),
	'session'=>array(
		'session_name' => 'user',
		'token_name' => 'token'
		)

	);

spl_autoload_register(function($class){
	require_once "Classes/$class.php";
});

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name')))
{
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$hashCheck = DB::getInstance()->get('users_cookie', array('hash', '=', $hash));
	if($hashCheck->count())
	{
		$user = new User($hashCheck->first()->user_id);
		$user->login();
	}
}

?>
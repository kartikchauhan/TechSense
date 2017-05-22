<?php
session_start();

// define('DEBUG', true);
// error_reporting(E_ALL);

// if (DEBUG)
// {
//     ini_set('display_errors', 'On');        
// }
// else
// {
//     ini_set('display_errors', 'Off');
// }
$server_host = 'https://';	// hardcoding it for the purpose of deploying
// $server_host = ((((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') || $_SERVER['SERVER_PORT']==443) ? 'https://':'http://' ).$_SERVER['HTTP_HOST']);
$base = dirname($_SERVER['REQUEST_URI']);	// current_page along with query_string
$endpoint = $server_host.$base;		// current_url without current_page
$current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$GLOBALS['config'] = array(
	'mysql'=>array(
		'host'=> 'us-cdbr-iron-east-03.cleardb.net',	// neeed to be changed to $_SERVER['HTTP_HOST']
		'user'=> 'b94ae7ed563da7',
		'password'=> '83a4e95b',
		'port' => '3306',
		'database'=> 'heroku_f0f9a018d9ec460'
		),
	'remember'=>array(
		'cookie_name' => 'hash',
		'reset_password' => 'hash',
		'cookie_expiry' => 604800
		),
	'session'=>array(
		'session_name' => 'user',
		'token_name' => 'tokens',
		),
	'url'=>array(
		'current_url' => $current_url,
		'endpoint' => $endpoint,
		'upload_dir' =>"Includes/uploads"
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
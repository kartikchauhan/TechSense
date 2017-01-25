<?php
session_start();

$GLOBALS['config'] = array(

	'mysql'=>array(
		'host'=>'127.0.0.1',
		'user'=>'root',
		'password'=>'',
		'database'=>'blog'
		),
	'session'=>array(
		'session_name' => 'username',
		'token_name' => 'token'
		)

	);

spl_autoload_register(function($class){
	
	require_once "Classes/$class.php";

});

?>
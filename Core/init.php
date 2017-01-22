<?php

$GLOBALS['config'] = array(

	'mysql'=>array(

		'host'=>'127.0.0.1',
		'user'=>'root',
		'password'=>'',
		'database'=>'blog'

		)

	);

spl_autoload_register(function($class){
	
	require_once "Classes/$class.php";

});

?>
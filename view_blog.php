<?php

require_once'Core/init.php';

$blogId = getBlogId($_SERVER['PATH_INFO']);		// stripping forward slashed fetched from the URL

getBlogId($pathInfo)
{
	return str_replace('/', '', $blogId);
}

$blogAttributes = new Blog;

if($blogAttributes->checkView())
{
	
}

?>
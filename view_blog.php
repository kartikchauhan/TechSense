<?php

require_once'Core/init.php';

if(Input::exists('get'))
{
	if(Input::get('blog_id'))
	{
		$blog = new Blog;
		$blogId = Input::get('blog_id');
		$blog = $blog->getBlog('blogs', array('id', '=', $blogId));
		if(!$blog)
		{
			Redirect::to(404);
		}
		$date=strtotime($blog->created_on);
	}
	else
	{
		Redirect::to('index.php');
	}
}
else
{
	Redirect::to('index.php');
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>View Blog</title>
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
    <style type="text/css">
        nav
        {
            border-bottom: 1px white solid;
        }
        input[type="search"]
        {
  			height: 64px !important; /* or height of nav */
		}
		p
		{
			font-size: 16px;
		}
    </style>
</head>
<body>
	<?php include('header.html'); ?>

	<header class="blue">
		<section>
			<div class="container">
				<div class="section">
					<div class="row">
						<div class="col s10">
							<h1 class="white-text thin"> <?php echo strtoupper($blog->title); ?></h1>
						</div>
					</div>
					<div class="row">
						<div class="col s10">
							<h5 class="white-text thin"> <?php echo ucfirst($blog->description); ?></h5>
						</div>
					</div>
					<div class="row">
						<div class="col s10">
							<div class="row">
								<div class="col s4">
									<h6 class="white-text"><?php echo date('M d, Y', $date); ?></h6>
								</div>
								<div class="col s6 offset-s2">
									<h6 class="white-text" >Written by - Kartik Chauhan</h6>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</header>

	<article>
		<section>
			<div class="container">
				<div class="section">
					<div class="row">
						<div class="col s8">
							<p class="flow-text"><?php echo $blog->blog; ?></p>
						</div>
					</div>
				</div>
			</div>
		</section>
	</article>

	<script src="Includes/js/jquery.min.js"></script>
    <script type="text/javascript" src="Includes/js/materialize.min.js"></script>
    <script>
    	$(document).ready(function(){
    		$('.nav-bar').removeClass('transparent');
    	});
    </script>
</body>
</html>
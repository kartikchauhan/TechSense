<?php

require_once'Core/init.php';

$blogId = str_replace('/', '', $_SERVER["PATH_INFO"]);		// stripping forward slash fetched from the URL
$blog = new Blog;
$blog = $blog->getBlog('blogs', array('id', '=', $blogId));
$date=strtotime($blog->created_on);

?>

<!DOCTYPE html>
<html>
<head>
	<title>View Blog</title>
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
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
						<div class="col s4">
							<h6 class="white-text thin"><?php echo date('M d, Y', $date); ?></h6>
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
							<?php echo $blog->blog; ?>
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
    		// $('.nav-bar').removeClass('transparent');
    	});
    </script>
</body>
</html>
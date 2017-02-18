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
	<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
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
		a
		{
			cursor: pointer;
			text-decoration: none;
			color: none;
		}
/*		._token
		{
			display: none;
		}*/
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
							<div class="section">
								<div class="row">
									<div class="col s5 offset-s2">
										<h6 class="center-align">Was this article helpful?</h6>
									</div>
									<div class="_token" id="_token" data-attribute="<?php echo Token::generate(); ?>"></div>
									<div class="col s1">
										<a class="likes" data-attribute="<?php echo $blog->id; ?>"><i class="fa fa-thumbs-up fa-2x" aria-hidden="true" ></i></a>
									</div>
									<div class="col s1 offset-s1 m1 l1">
										<a class="dislikes" data-attribute="<?php echo $blog->id; ?>"><i class="fa fa-thumbs-down fa-2x" aria-hidden="true"></i></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</article>

	<footer class="page-footer blue lighten-1">
		<div class="container">
			<div class="row">
				<div class="col s6">
					<div class="row">
						<div class="col s8">
							<img class="materialboxed responsive-img z-depth-2" data-caption="Author's Name" src="Includes/images/art1.jpg">
						</div>
					</div>
					<div class="row">
						<div class="col s1">
							<i class="fa fa-github-square fa-4x" aria-hidden="true"></i>
						</div>
						<div class="col s1 offset-s1">
							<i class="fa fa-facebook-square fa-4x" aria-hidden="true"></i>
						</div>
						<div class="col s1 offset-s1">
							<i class="fa fa-twitter-square fa-4x" aria-hidden="true"></i>
						</div>
					</div>
				</div>
				<div class="col s6">
					hey
				</div>
			</div>
		</div>
	</footer>

	<script src="Includes/js/jquery.min.js"></script>
    <script src="https://use.fontawesome.com/17e854d5bf.js"></script>
    <script type="text/javascript" src="Includes/js/materialize.min.js"></script>
    <script>
    	$(document).ready(function(){
    		$('.nav-bar').removeClass('transparent');

    		$('.likes, .dislikes').click(function(e){
                e.preventDefault();
                var object = $(this);
                
                var blog_id = $(this).attr('data-attribute');
                var _token = $('#_token').attr('data-attribute');
                var className = getClassName(this);

                $.ajax({
                    type: 'POST',
                    url: 'blog_attributes.php',
                    data: {blog_id: blog_id, _token: _token, field: className},
                    cache: false,
                    success: function(response)
                    {
                        var response = JSON.parse(response);
                        console.log(response);
                        $('#_token').attr('data-attribute', response._token);
                        if(response.error_status)
                        {
                            consol.log(response.error);
                            Materialize.toast(response.error, 5000, 'red');
                            return false;
                        }
                        else
                        {
                            if(getClassName(object) === 'likes')
                            {
                            	$(object).find('i').css('color', '#4caf50');
                            }
                            else if(getClassName(object) === 'dislikes')
                            {
                            	$(object).find('i').css('color', '#f44336');
                            }

                        }
                    }
                });
            });

			function getClassName(object)
            {
                var className = $(object).attr('class');
                if(className === 'likes')
                {
                    className = 'likes';
                }
                else if(className === 'dislikes')
                {
                    className = 'dislikes';
                }
                return className;
            }
    	});
    </script>
</body>
</html>
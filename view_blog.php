<?php

require_once'Core/init.php';

$user = new User;
if(!$user->isLoggedIn())
{
	Redirect::to('index.php');
}

if(Input::exists('get'))
{
	if(Input::get('blog_id'))
	{
		$blogs = new Blog;
		$blogId = Input::get('blog_id');
		$blog = $blogs->getBlog('blogs', array('id', '=', $blogId));
		if(!$blog)
		{
			Redirect::to(404);
		}
		$views = $blog->views + 1;
		$blogs->update('blogs', $blogId, array('views' => $views));
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
	<!-- <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css"> -->
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
									<h6 class="white-text" >Written by - <?php echo ucwords($user->data()->name) ?></h6>
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
										<a class="likes" data-attribute="<?php echo $blog->id; ?>"><i class="fa fa-thumbs-up fa-2x" aria-hidden="true" style="color: grey"></i></a>
									</div>
									<div class="col s1 offset-s1 m1 l1">
										<a class="dislikes" data-attribute="<?php echo $blog->id; ?>"><i class="fa fa-thumbs-down fa-2x" aria-hidden="true" style="color: grey"></i></a>
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
				<div class="col s5">
					<div class="row">
						<div class="col s8">
							<img class="materialboxed responsive-img z-depth-2" data-caption="Author's Name" src="<?php echo Config::get('url/upload_dir').'/'.$user->data()->image_url ?>">
						</div>
					</div>
					<div class="row">
						<div class="col s1 offset-s1">
							<a href="<?php echo $user->data()->github_url; ?>" target="_blank"><i class="fa fa-github-square fa-3x" aria-hidden="true" style="color:black"></i></a>
						</div>
						<div class="col s1 offset-s1">
							<a href="<?php echo $user->data()->facebook_url; ?>" target="_blank"><i class="fa fa-facebook-square fa-3x" aria-hidden="true" style="color:black"></i></a>
						</div>
						<div class="col s1 offset-s1">
							<a href="<?php echo $user->data()->twitter_url; ?>" target="_blank"><i class="fa fa-twitter-square fa-3x" aria-hidden="true" style="color:black"></i></a>
						</div>
					</div>
				</div>
				<div class="col s7">
					<div class="row">
						<div class="col s12">
							<h5 class="white-text"><?php echo ucwords($user->data()->name) ?></h5>
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<p class="white-text"><?php echo ucfirst($user->data()->description) ?></p>
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<h6 class="white-text">Email: <?php echo $user->data()->email ?></h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="footer-copyright">
			<div class="container center-align">
				© 2017 Software Incubator
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
                            // return false;
                        }
                        else
                        {
                            if(response.blog_status == 1)
                            {
                            	$('.likes').find('i').css('color', 'green');
                            	$('.dislikes').find('i').css('color', 'grey');
                            }
                            else if(response.blog_status == -1)
                            {
                            	$('.dislikes').find('i').css('color', 'red');
                            	$('.likes').find('i').css('color', 'grey');
                            }
                            else
                            {
                            	$('.likes').find('i').css('color', 'grey');
                            	$('.dislikes').find('i').css('color', 'grey');
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
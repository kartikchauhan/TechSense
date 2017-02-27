<?php

require_once'Core/init.php';

$user = new User;

if(Input::exists('get'))
{
	if(Input::get('blog_id'))
	{
		$blog = new Blog;
		$blogId = Input::get('blog_id');
		$blog->getBlog('blogs', array('id', '=', $blogId));
		if(!$blog)
		{
			Redirect::to(404);
		}
		$views = $blog->data()->views + 1;
		try
		{
			if($blog->update('blogs', $blogId, array('views' => $views)) != 1)	// if number of records returned are not equal to 1
				throw new Exception("Unable to update views of the blog.");
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$date=strtotime($blog->data()->created_on);
		if($user->isLoggedIn())
		{
			$userLoggedIn = true;
			$blogStatus = DB::getInstance()->getAnd('users_blogs_status', array('user_id' => $user->data()->id, 'blog_id' => $blogId));
			$blogStatusCount = $blogStatus->count();
			if($blogStatusCount)
			{
				$blogStatus = $blogStatus->first()->blog_status;
			}
		}
		else
		{
			$userLoggedIn = false;
		}
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
	<?php 

		include'header.php';

	?>

	<header class="blue">
		<section>
			<div class="container">
				<div class="section">
					<div class="row">
						<div class="col s10">
							<h1 class="white-text thin"> <?php echo strtoupper($blog->data()->title); ?></h1>
						</div>
					</div>
					<div class="row">
						<div class="col s10">
							<h5 class="white-text thin"> <?php echo ucfirst($blog->data()->description); ?></h5>
						</div>
					</div>
					<div class="row">
						<div class="col s10">
							<div class="row">
								<div class="col s4">
									<h6 class="white-text"><?php echo date('M d, Y', $date); ?></h6>
								</div>
								<div class="col s6 offset-s2">
									<h6 class="white-text" >Written by - <?php echo ucwords("Kartik Chauhan") ?></h6>
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
							<p class="flow-text"><?php echo $blog->data()->blog; ?></p>
							<div class="section">
								<div class="row">
									<div class="col s5 offset-s2">
										<h6 class="center-align">Was this article helpful?</h6>
									</div>
									<div class="_token" id="_token" data-attribute="<?php echo Token::generate(); ?>"></div>
									<?php 
										if($userLoggedIn)
										{
											if($blogStatusCount)
											{
												if($blogStatus == 1)
												{
													echo 
													"<div class='col s1'>
														<a class='likes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: green'></i></a>
													</div>
													<div class='col s1 offset-s1 m1 l1'>
														<a class='dislikes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: grey'></i></a>
													</div>";
												}
												else if($blogStatus == -1)
												{
													echo 
													"<div class='col s1'>
														<a class='likes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: grey'></i></a>
													</div>
													<div class='col s1 offset-s1 m1 l1'>
														<a class='dislikes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: red'></i></a>
													</div>";
												}
												else if($blogStatus == 0)
												{
													echo
													"<div class='col s1'>
														<a class='likes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: grey'></i></a>
													</div>
													<div class='col s1 offset-s1 m1 l1'>
														<a class='dislikes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: grey'></i></a>
													</div>";
												}
											}
											else
											{
												echo
												"<div class='col s1'>
													<a class='likes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: grey'></i></a>
												</div>
												<div class='col s1 offset-s1 m1 l1'>
													<a class='dislikes' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: grey'></i></a>
												</div>";
											}

										}
										else
										{
											echo
											"<div class='col s1'>
												<a class='likes-not-logged-in' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: grey'></i></a>
											</div>
											<div class='col s1 offset-s1 m1 l1'>
												<a class='dislikes-not-logged-in' data-attribute=".$blog->data()->id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: grey'></i></a>
											</div>";
										}
									?>
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
							<img class="materialboxed responsive-img z-depth-2" data-caption="Author's Name" src="Includes/images/code2.png"> <!-- <?php //echo Config::get('url/upload_dir').'/'.$user->data()->image_url ?> -->
						</div>
					</div>
					<div class="row">
						<div class="col s1 offset-s1">
							<a href="" target="_blank"><i class="fa fa-github-square fa-3x" aria-hidden="true" style="color:black"></i></a> <!-- <?php //echo $user->data()->github_url; ?> -->
						</div>
						<div class="col s1 offset-s1">
							<a href="" target="_blank"><i class="fa fa-facebook-square fa-3x" aria-hidden="true" style="color:black"></i></a>	<!-- <?php //echo $user->data()->facebook_url; ?> -->
						</div>
						<div class="col s1 offset-s1">
							<a href="" target="_blank"><i class="fa fa-twitter-square fa-3x" aria-hidden="true" style="color:black"></i></a> <!-- <?php //echo $user->data()->twitter_url; ?> -->
						</div>
					</div>
				</div>
				<div class="col s7">
					<div class="row">
						<div class="col s12">
							<h5 class="white-text">Author's Name</h5>	<!-- <?php //echo ucwords($user->data()->name) ?> -->
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<p class="white-text">Author's Description</p>	<!-- <?php //echo ucwords($user->data()->description) ?> -->
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<h6 class="white-text">Email: Author's Email</h6>	<!-- <?php //echo ucwords($user->data()->email) ?> -->
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

    		$('.likes-not-logged-in, .dislikes-not-logged-in').click(function(e){
    			e.preventDefault();
    			Materialize.toast("You need to login to vote", 5000, "red");
    		});

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
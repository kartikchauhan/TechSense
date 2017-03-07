<?php

require_once'Core/init.php';

$user = new User;

if(Input::exists('get'))
{
	if(Input::get('blog_id'))
	{
		$blog = new Blog;	// creating an Instance of Blog so that we could update the views
		$blogId = Input::get('blog_id');
		$author = DB::getInstance()->join(array('users', 'blogs'), array('id', 'users_id'), array('id', '=', $blogId))->first(); // fetching the author of the blog and his corresponding details
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
	<meta property="og:url"           content="http://localhost/Blog_temp2/view_blog.php?blog_id=118" />
	<meta property="og:type"          content="website" />
	<meta property="og:title"         content="Aster" />
	<meta property="og:description"   content="A place to read and write blogs about any technology" />
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
	<!-- facebook SDK for sharing button -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  	var js, fjs = d.getElementsByTagName(s)[0];
	  	if (d.getElementById(id)) return;
	  	js = d.createElement(s); js.id = id;
	  	js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=1145090692169938";
	  	fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>


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
									<h6 class="white-text" >Written by - <?php echo ucwords($author->name) ?></h6>
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
								<div class="row">
									<div class="col s12 offset-s2">
										<div class="row">
											<div class="col s4">
												<h5>Share this blog</h5>
											</div>
											<div class="col s2">
												<a href="https://twitter.com/share" class="twitter-share-button" data-size="large" data-text="Check Out this blog" data-show-count="false">Tweet</a><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
											</div>
											<div class="col s2">
												<div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button" data-size="large" data-mobile-iframe="false"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Share</a></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="container">	
				<h5>Comments</h5>
				<div class="row">
					<div class="col s8">
					<?php

						if($userLoggedIn)
						{
							echo
							"<form acion='' method='post'>
								<label for='comment'>Add a Comment</label>
								<div class='section'>
									<textarea class='materialize-textarea' id='comment' name='comment' data-attribute='".$blogId."'></textarea>
								</div>
								<button type='button' class='btn waves-effect waves-light blue' name='send_comment' id='send_comment'>Comment</button>
							</form>";
						}
						else
						{
							echo
							"<div class='center-align'>
								<h6><a href='login.php'>Login</a> to post a comment</h6>
							</div>";
						}

					?>
					</div>
				</div>
				<div class="row">
					<div class="col s8">
						<div class="comment-section" id="comment-section">
							<?php
							// getting count of comments on the current opened blog
							$count_comments =  DB::getInstance()->join(array('comments', 'blogs'), array('blog_id', 'id'), array('id', '=', $blogId));
							// if there's any comment on the current blog, show it otherwise print no comments
							if($count_comments->count())
							{
								// getting all the comments posted on the current blog in DESCENDING order
								$comments = DB::getInstance()->joinSortComments(array('users', 'comments', 'blogs'), array('id', 'user_id', 'id', 'blog_id'), array('created_on', 'DESC'), array('id', '=', $blogId), array('id', 'comment_id', 'created_on', 'comment_created_on', 'likes', 'comment_likes', 'dislikes', 'comment_dislikes'));
								$comments = $comments->results();
								$counter = 1;	// initiating counter, later used for designing
								if($userLoggedIn)	// if user is logged in, enable the functionality of voting
								{
									foreach($comments as $comment)
									{
										$commentStatus = DB::getInstance()->getAnd('users_comments_status', array('user_id' => $user->data()->id, 'comment_id' => $comment->comment_id));
										$commentStatusCount = $commentStatus->count();
										if($commentStatusCount)
										{
											$commentStatus = $commentStatus->first()->comment_status;
										}
										$date = strtotime($comment->comment_created_on);
										if($counter%2)	// if counter%2 != 0, not adding offset-s1 class to the div
										{
											echo
												"<div class='row'>
													<div class='col s11 blue z-depth-2'>
														<div class='white-text'>"
															.$comment->comment.
														"</div>
														<div class='divider'></div>
														<div class='section white-text'>
															<div class='row white-text'>
																<div class='col s4'>
																	<img class='responsive-img materialboxed' height='50px' width='50px' src='".Config::get('url/upload_dir').'/'.$comment->image_url."'>".$comment->name."
																</div>													
																<div class='col s4'>
																	<div class='row'>";
																		if($commentStatusCount)
																		{
																			if($commentStatus == 1)
																			{
																				echo 
																				"<div class='col s4'>
																					<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: green'></i></a>
																				</div>
																				<div class='col s2'>
																					<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
																				</div>
																				<div class='col s4'>
																					<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
																				</div>
																				<div class='col s2'>
																					<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
																				</div>";
																			}
																			else if($commentStatus == -1)
																			{
																				echo 
																				"<div class='col s4'>
																					<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
																				</div>
																				<div class='col s2'>
																					<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
																				</div>
																				<div class='col s4'>
																					<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: red'></i></a>
																				</div>
																				<div class='col s2'>
																					<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
																				</div>";
																			}
																			else if($commentStatus == 0)
																			{
																				echo
																				"<div class='col s4'>
																					<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
																				</div>
																				<div class='col s2'>
																					<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
																				</div>
																				<div class='col s4'>
																					<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
																				</div>
																				<div class='col s2'>
																					<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
																				</div>";
																			}
																		}
																		else
																		{
																			echo
																			"<div class='col s4'>
																				<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
																			</div>
																			<div class='col s2'>
																				<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
																			</div>
																			<div class='col s4'>
																				<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
																			</div>
																			<div class='col s2'>
																				<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
																			</div>";
																		}
																	echo
																	"</div>
																</div>
																<div class='col s3 offset-s1'>"
																	.date('M d Y', $date).
																"</div>
															</div>
														</div>
													</div>
												</div>";
										}
										else    // if counter%2 == 0, adding offset-s1 class to the div, to make zig-zag pattern
										{
											echo
												"<div class='row'>
													<div class='col s11 blue offset-s1 z-depth-2'>
														<div class='white-text'>"
															.$comment->comment.
														"</div>
														<div class='divider'></div>
														<div class='section white-text'>
															<div class='row white-text'>
																<div class='col s4'>
																	<img class='responsive-img materialboxed' height='50px' width='50px' src='".Config::get('url/upload_dir').'/'.$comment->image_url."'>".$comment->name."
																</div>													
																<div class='col s4'>
																	<div class='row'>";
																		if($commentStatusCount)
																		{
																			if($commentStatus == 1)
																			{
																				echo 
																				"<div class='col s4'>
																					<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: green'></i></a>
																				</div>
																				<div class='col s2'>
																					<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
																				</div>
																				<div class='col s4'>
																					<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
																				</div>
																				<div class='col s2'>
																					<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
																				</div>";
																			}
																			else if($commentStatus == -1)
																			{
																				echo 
																				"<div class='col s4'>
																					<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
																				</div>
																				<div class='col s2'>
																					<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
																				</div>
																				<div class='col s4'>
																					<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: red'></i></a>
																				</div>
																				<div class='col s2'>
																					<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
																				</div>";
																			}
																			else if($commentStatus == 0)
																			{
																				echo
																				"<div class='col s4'>
																					<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
																				</div>
																				<div class='col s2'>
																					<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
																				</div>
																				<div class='col s4'>
																					<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
																				</div>
																				<div class='col s2'>
																					<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
																				</div>";
																			}
																		}
																		else
																		{
																			echo
																			"<div class='col s4'>
																				<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
																			</div>
																			<div class='col s2'>
																				<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
																			</div>
																			<div class='col s4'>
																				<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
																			</div>
																			<div class='col s2'>
																				<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
																			</div>";
																		}
																	echo
																	"</div>
																</div>
																<div class='col s3 offset-s1'>"
																	.date('M d Y', $date).
																"</div>
															</div>
														</div>
													</div>
												</div>";
										}
										++$counter;
									}
								}
								else    // if user is not logged in, disable the functionality of voting, giving response "You need to log in to vote" through JS
								{
									foreach($comments as $comment)
									{
										$date = strtotime($comment->comment_created_on);	// fetching date of comments to converting them into suitable format
										if($counter%2)
										{
											echo
												"<div class='row'>
													<div class='col s11 blue z-depth-2'>
														<div class='white-text'>"
															.$comment->comment.
														"</div>
														<div class='divider'></div>
														<div class='section white-text'>
															<div class='row white-text'>
																<div class='col s4'>
																	<img class='responsive-img materialboxed' height='50px' width='50px' src='".Config::get('url/upload_dir').'/'.$comment->image_url."'>".$comment->name."
																</div>													
																<div class='col s4'>
																	<div class='row'>
																		<div class='col s4'>
																			<a class='comment-like-not-logged-in' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
																		</div>
																		<div class='col s2'>
																			<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
																		</div>
																		<div class='col s4'>
																			<a class='comment-dislike-not-logged-in' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
																		</div>
																		<div class='col s2'>
																			<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
																		</div>
																	</div>
																</div>
																<div class='col s3 offset-s1'>"
																	.date('M d Y', $date).
																"</div>
															</div>
														</div>
													</div>
												</div>";
										}
										else
										{
											echo
												"<div class='row'>
													<div class='col s11 blue offset-s1 z-depth-2'>
														<div class='white-text'>"
															.$comment->comment.
														"</div>
														<div class='divider'></div>
														<div class='section white-text'>
															<div class='row white-text'>
																<div class='col s4'>
																	<img class='responsive-img materialboxed' height='50px' width='50px' src='".Config::get('url/upload_dir').'/'.$comment->image_url."'>".$comment->name."
																</div>													
																<div class='col s4'>
																	<div class='row'>
																		<div class='col s4'>
																			<a class='comment-like-not-logged-in' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
																		</div>
																		<div class='col s2'>
																			<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
																		</div>
																		<div class='col s4'>
																			<a class='comment-dislike-not-logged-in' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
																		</div>
																		<div class='col s2'>
																	  		<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
																		</div>
																	</div>
																</div>
																<div class='col s3 offset-s1'>"
																	.date('M d Y', $date).
																"</div>
															</div>
														</div>
													</div>
												</div>";
										}
										++$counter;
									}
								}
							}
							else
							{
								echo
								"<div class='center-align'>No comments</div>";
							}

							?>
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
							<a href="<?php echo $author->github_url; ?>" target="_blank"><i class="fa fa-github-square fa-3x" aria-hidden="true" style="color:black"></i></a> 	<!-- author's github url -->
						</div>
						<div class="col s1 offset-s1">
							<a href="<?php echo $author->facebook_url; ?>" target="_blank"><i class="fa fa-facebook-square fa-3x" aria-hidden="true" style="color:black"></i></a>	<!-- author's facebook url -->
						</div>
					</div>
				</div>
				<div class="col s7">
					<div class="row">
						<div class="col s12">
							<h5 class="white-text center-align">Writer's Info</h5>
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<h5 class="white-text"><?php echo ucwords($author->name); ?></h5>	<!-- author's name of the blog -->
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<p class="white-text"><?php echo ucfirst($author->user_description); ?></p>	
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<h6 class="white-text">Email: <?php echo $author->email; ?></h6>
						</div>
					</div>
					<?php
						if($author->twitter_username !== '')
						{
							echo
							"<div class='row'>
								<div class='col s6'>
									<a class='twitter-follow-button' href='https://twitter.com/".$author->twitter_username."' data-size='large'> Follow @".$author->twitter_username."</a>
								</div>
							</div>";
						}
					?>
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
    <script src="vendor/tinymce/tinymce/tinymce.min.js"></script>
    <script>
    	$(document).ready(function(){
    		$('.nav-bar').removeClass('transparent');

    			tinymce.init({
                    selector: '#comment',
                    height: 100,
                    theme: 'modern',
                    plugins: [
                      'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                      'searchreplace wordcount visualblocks visualchars code fullscreen',
                      'insertdatetime media nonbreaking save table contextmenu directionality',
                      'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
                    ],
                    toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                    toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
                    image_advtab: true,
                    templates: [
                      { title: 'Test template 1', content: 'Test 1' },
                      { title: 'Test template 2', content: 'Test 2' }
                    ],
                    content_css: [
                      '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                      '//www.tinymce.com/css/codepen.min.css'
                    ]
                });

    		$('.likes-not-logged-in, .dislikes-not-logged-in').click(function(e){	// if user is not logged in, restrict him from voting
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

			$('.comment-like-not-logged-in, .comment-dislike-not-logged-in').click(function(e){	// if user is not logged in, restrict him from voting
    			e.preventDefault();
    			Materialize.toast("You need to login to vote", 5000, "red");
    		});

			$('.comment-section').on('click', '.comment-like, .comment-dislike', function(e){	// request server to check if the request is valid, if valid add the user's reponse
                e.preventDefault();
                var object = $(this);	// anchor tag, user just clicked
                
                var comment_id = $(this).attr('data-attribute');	// comment_id of the comment, user wants to vote
                var _token = $('#_token').attr('data-attribute');
                var className = getClassName(this);		// checking if user clicked on comment-like or comment-dislike

                $.ajax({
                    type: 'POST',
                    url: 'comment_attributes.php',
                    data: {comment_id: comment_id, _token: _token, field: className},
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
                            if(response.comment_status == 1)
                            {
                            	$(object).parent().parent().find('.comment-like').find('i').css('color', 'green');	// changing the color of the icons according to the received response
                            	$(object).parent().parent().find('.comment-dislike').find('i').css('color', 'white');
                            	$(object).parent().parent().find('.comment-count-likes').html(response.count_likes);	// changing the counts of likes and dislikes of a comment according to the received response
                            	$(object).parent().parent().find('.comment-count-dislikes').html(response.count_dislikes);
                            }
                            else if(response.comment_status == -1)
                            {
                            	$(object).parent().parent().find('.comment-like').find('i').css('color', 'white');
                            	$(object).parent().parent().find('.comment-dislike').find('i').css('color', 'red');
                            	$(object).parent().parent().find('.comment-count-likes').html(response.count_likes);
                            	$(object).parent().parent().find('.comment-count-dislikes').html(response.count_dislikes);
                            }
                            else if(response.comment_status == 0)
                            {
                            	$(object).parent().parent().find('.comment-like').find('i').css('color', 'white');
                            	$(object).parent().parent().find('.comment-dislike').find('i').css('color', 'white');
                            	$(object).parent().parent().find('.comment-count-likes').html(response.count_likes);
                            	$(object).parent().parent().find('.comment-count-dislikes').html(response.count_dislikes);
                            }
                        }
                    }
                });
            });			

			$('#send_comment').on('click', function(){
				var blog_id = $('#comment').attr('data-attribute');
				var comment = tinyMCE.activeEditor.getContent();
				var _token = $('#_token').attr('data-attribute');
				$.ajax({
					type: 'POST',
					url: 'send_comment.php',
					data: {blog_id: blog_id, comment: comment, _token: _token},
					success: function(response)
					{
						var response = JSON.parse(response);
						console.log(response);
						$('#_token').attr('data-attribute', response._token);
						if(response.error_status === false)
						{
							$('.comment-section').prepend(response.content);
							// Materialize.toast('Your comment has been added successfully', 5000, 'green');
						}
						else
						{
							Materialize.toast(response.error, 5000, "red");
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
<?php

require_once'Core/init.php';

$user = new User;

if(Input::exists())
{
	if(Token::check(Input::get('_token')))
	{
		$json['error_status'] = false;
		$json['_token'] = Token::generate();

		if($user->isLoggedIn())
		{

		}
		else
		{
			$json['error_status'] = true;
			$json['error'] = "You need to log in to post a comment";
		}
		$Validate = new Validate;
		$Validate->check($_POST, array(
			'comment' => array(
				'required' => true
				)
			));

		if($Validate->passed())
		{
			$user_id = $user->data()->id;
			try
			{
				$comment = DB::getInstance()->insert('comments', array(
				'comment' => Input::get('comment'),
				'blog_id' => Input::get('blog_id'),
				'user_id' => $user_id
				));

				if(!$comment)
					throw new Exception("Unable to add your comment right now. Please try again later");
				else
				{
					$lastComment = $comments = DB::getInstance()->joinSortComments(array('users', 'comments', 'blogs'), array('id', 'user_id', 'id', 'blog_id'), array('created_on', 'DESC'), array('id', '=', Input::get('blog_id')), array('id', 'comment_id', 'created_on', 'comment_created_on', 'likes', 'comment_likes', 'dislikes', 'comment_dislikes'))->first();	//fetching the comment user just submitted
					$date = strtotime($lastComment->created_on);
					// preparing response to be added in the view_blog page
					$json['content'] = "<div class='row'>
											<div class='col s11 offset-s1 blue z-depth-2'>
												<div class='white-text'>"
													.$lastComment->comment.
												"</div>
												<div class='divider'></div>
												<div class='section white-text'>
													<div class='row white-text'>
														<div class='col s4'>
															<img class='responsive-img' height='50px' width='50px' src='".Config::get('url/upload_dir').'/'.$lastComment->image_url."'>".$lastComment->name."
														</div>													
														<div class='col s4'>
															<div class='row'>
																<div class='col s4'>
																	<a class='comment-like' data-attribute=".$lastComment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
																</div>
																<div class='col s2'>
																	<div class='white-text comment-count-likes'>".$lastComment->comment_likes."</div>
																</div>
																<div class='col s4'>
																	<a class='comment-dislike' data-attribute=".$lastComment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
																</div>
																<div class='col s2'>
																	<div class='white-text comment-count-dislikes'>".$lastComment->comment_dislikes."</div>
																</div>
															</div>
														</div>
														<div class='col s4'>"
															.date('M d Y', $date).
														"</div>
													</div>
												</div>
											</div>
										</div>";
				}					
			}
			catch(Exception $e)
			{
				$json['error_status'] = true;
				$json['error'] = $e->getMessage();
			}
		}
		else
		{
			$json['error_status'] = true;
			$json['error'] = $Validate->errors()[0];
		}
		echo json_encode($json);
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
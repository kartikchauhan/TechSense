<?php

require_once'Core/init.php';

if(Input::exists())
{
	if(Token::check(Input::get('_token')))
	{
		$json['_token'] = Token::generate();
		$user = new User;

		if($user->isLoggedIn())
		{
			$userLoggedIn = true;
		}
		else
		{
			$userLoggedIn = false;
		}

		$field = Input::get('field');
		$blogId = Input::get('blog_id');

		// getting all the comments posted on the current blog in DESCENDING order
		if($field === 'new_comments')
		{
			$comments = DB::getInstance()->joinSortComments(array('users', 'comments', 'blogs'), array('id', 'user_id', 'id', 'blog_id'), array('created_on', 'DESC'), array('id', '=', $blogId), array('id', 'comment_id', 'created_on', 'comment_created_on', 'likes', 'comment_likes', 'dislikes', 'comment_dislikes'));
		}
		else if($field === 'popular_comments')
		{
			$comments = DB::getInstance()->joinSortComments(array('users', 'comments', 'blogs'), array('id', 'user_id', 'id', 'blog_id'), array('likes', 'DESC'), array('id', '=', $blogId), array('id', 'comment_id', 'created_on', 'comment_created_on', 'likes', 'comment_likes', 'dislikes', 'comment_dislikes'));
		}

		$json['content'] = '';

		// if there's any comment on the current blog, show it otherwise print no comments
		if($comments->count())
		{
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
						$json['content'] = $json['content'].
							"<div class='row'>
								<div class='col s11 blue z-depth-2'>
									<div class='white-text'>"
										.$comment->comment.
									"</div>
									<div class='divider'></div>
									<div class='section white-text'>
										<div class='row white-text'>
											<div class='col s2 l4'>
												<img class='responsive-img materialboxed' height='50px' width='50px' src='".Config::get('url/upload_dir').'/'.$comment->image_url."'>".$comment->name."
											</div>													
											<div class='col s5 offset-s1 l4'>
												<div class='row'>";
													if($commentStatusCount)
													{
														if($commentStatus == 1)
														{
															$json['content'] = $json['content'].
															"<div class='col s3'>
																<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: green'></i></a>
															</div>
															<div class='col s3'>
																<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
															</div>
															<div class='col s3'>
																<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
															</div>
															<div class='col s3'>
																<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
															</div>";
														}
														else if($commentStatus == -1)
														{
															$json['content'] = $json['content'].
															"<div class='col s3'>
																<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
															</div>
															<div class='col s3'>
																<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
															</div>
															<div class='col s3'>
																<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: red'></i></a>
															</div>
															<div class='col s3'>
																<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
															</div>";
														}
														else if($commentStatus == 0)
														{
															$json['content'] = $json['content'].
															"<div class='col s3'>
																<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
															</div>
															<div class='col s3'>
																<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
															</div>
															<div class='col s3'>
																<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
															</div>
															<div class='col s3'>
																<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
															</div>";
														}
													}
													else
													{
														$json['content'] = $json['content'].
														"<div class='col s3'>
															<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
														</div>
														<div class='col s3'>
															<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
														</div>
														<div class='col s3'>
															<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
														</div>
														<div class='col s3'>
															<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
														</div>";
													}
												$json['content'] = $json['content'].
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
						$json['content'] = $json['content'].
							"<div class='row'>
								<div class='col s11 blue offset-s1 z-depth-2'>
									<div class='white-text'>"
										.$comment->comment.
									"</div>
									<div class='divider'></div>
									<div class='section white-text'>
										<div class='row white-text'>
											<div class='col s2 l4'>
												<img class='responsive-img materialboxed' height='50px' width='50px' src='".Config::get('url/upload_dir').'/'.$comment->image_url."'>".$comment->name."
											</div>													
											<div class='col s5 offset-s1 l4'>
												<div class='row'>";
													if($commentStatusCount)
													{
														if($commentStatus == 1)
														{
															$json['content'] = $json['content'].
															"<div class='col s3'>
																<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: green'></i></a>
															</div>
															<div class='col s3'>
																<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
															</div>
															<div class='col s3'>
																<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
															</div>
															<div class='col s3'>
																<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
															</div>";
														}
														else if($commentStatus == -1)
														{
															$json['content'] = $json['content'].
															"<div class='col s3'>
																<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
															</div>
															<div class='col s3'>
																<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
															</div>
															<div class='col s3'>
																<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: red'></i></a>
															</div>
															<div class='col s3'>
																<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
															</div>";
														}
														else if($commentStatus == 0)
														{
															$json['content'] = $json['content'].
															"<div class='col s3'>
																<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
															</div>
															<div class='col s3'>
																<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
															</div>
															<div class='col s3'>
																<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
															</div>
															<div class='col s3'>
																<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
															</div>";
														}
													}
													else
													{
														$json['content'] = $json['content'].
														"<div class='col s3'>
															<a class='comment-like' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
														</div>
														<div class='col s3'>
															<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
														</div>
														<div class='col s3'>
															<a class='comment-dislike' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
														</div>
														<div class='col s3'>
															<div class='white-text comment-count-dislikes'>".$comment->comment_dislikes."</div>
														</div>";
													}
												$json['content'] = $json['content'].
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
						$json['content'] = $json['content'].
							"<div class='row'>
								<div class='col s11 blue z-depth-2'>
									<div class='white-text'>"
										.$comment->comment.
									"</div>
									<div class='divider'></div>
									<div class='section white-text'>
										<div class='row white-text'>
											<div class='col s2 l4'>
												<img class='responsive-img materialboxed' height='50px' width='50px' src='".Config::get('url/upload_dir').'/'.$comment->image_url."'>".$comment->name."
											</div>													
											<div class='col s5 offset-s1 l4'>
												<div class='row'>
													<div class='col s3'>
														<a class='comment-like-not-logged-in' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
													</div>
													<div class='col s3'>
														<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
													</div>
													<div class='col s3'>
														<a class='comment-dislike-not-logged-in' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
													</div>
													<div class='col s3'>
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
						$json['content'] = $json['content'].
							"<div class='row'>
								<div class='col s11 blue offset-s1 z-depth-2'>
									<div class='white-text'>"
										.$comment->comment.
									"</div>
									<div class='divider'></div>
									<div class='section white-text'>
										<div class='row white-text'>
											<div class='col s2 l4'>
												<img class='responsive-img materialboxed' height='50px' width='50px' src='".Config::get('url/upload_dir').'/'.$comment->image_url."'>".$comment->name."
											</div>													
											<div class='col s5 offset-s1 l4'>
												<div class='row'>
													<div class='col s3'>
														<a class='comment-like-not-logged-in' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color: white'></i></a>
													</div>
													<div class='col s3'>
														<div class='white-text comment-count-likes'>".$comment->comment_likes."</div>
													</div>
													<div class='col s3'>
														<a class='comment-dislike-not-logged-in' data-attribute=".$comment->comment_id."><i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color: white'></i></a>
													</div>
													<div class='col s3'>
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
			$json['content'] = $json['content'].
			"<div class='center-align'>No comments</div>";
		}

		echo json_encode($json);
	}
}
	

?>
<?php

require_once'Core/init.php';


if(Input::exists('post'))
{
	// if(Token::check(Input::get('_token')))
	// {
		$json['error_staus'] = false;
		$json['_token'] = Token::generate();

		$user = new User;

		if($user->isLoggedIn())	//checking if user is logged In or not, if logged in => proceed else show error "You need to login"
		{
			$comment_id = Input::get('comment_id');	// comment's id of the current comment
			$user_id = $user->data()->id;	// user's id of the current user
			$field = Input::get('field');	// get the field ie. likes or dislikes
			$commentStatus = DB::getInstance()->getAnd('users_comments_status', array(
				'user_id' => $user_id,
				'comment_id' => $comment_id
				));		// fetch if there exists any record in the table users_comments_status		
			if($commentStatus->count() == 1)
			{
				$commentStatus = $commentStatus->first();
				$comment_user_id = $commentStatus->id;	// get the id of the record where user_id is current user's id and comment_id is current comment's id
				$count = DB::getInstance()->get('comments', array('id', '=', $comment_id));
				if($count->count() == 1)
				{
					$count_likes = $count->first()->likes;
					$count_dislikes = $count->first()->dislikes;
				}
				if($field == 'comment-like')
				{
					if($commentStatus->comment_status == 1)	// status 1 => like, status 0 => neutral, status -1 => dislike
					{
						try
						{
							if(!DB::getInstance()->update('users_comments_status', $comment_user_id, array('comment_status' => 0)))	// if user clicks again to like than nullify(neutral) the response
								throw new Exception("Your response couldn't be added right now. Please try again later 37");
							$count_likes = $count_likes - 1;
							if(!DB::getInstance()->update('comments', $comment_id, array('likes' => $count_likes)))
								throw new Exception("Your response couldn't be added right now. Please try again later 44");
							$json['count_likes'] = $count_likes;
							$json['count_dislikes'] = $count_dislikes;
							$json['comment_status'] = 0;
						}
						catch(Exception $e)
						{
							$json['error_staus'] = true;
							$json['error'] = $e->getMessage();
						}
					}
					else if($commentStatus->comment_status == 0)
					{
						try
						{
							if(!DB::getInstance()->update('users_comments_status', $comment_user_id, array('comment_status' => 1)))
								throw new Exception("Your response couldn't be added right now. Please try again later 56");
							$count_likes = $count_likes + 1;
							if(!DB::getInstance()->update('comments', $comment_id, array('likes' => $count_likes)))
								throw new Exception("Your response couldn't be added right now. Please try again later 63");
							$json['count_likes'] = $count_likes;
							$json['count_dislikes'] = $count_dislikes;
							$json['comment_status'] = 1;
						}
						catch(Exception $e)
						{
							$json['error_staus'] = true;
							$json['error'] = $e->getMessage();
						}
					}
					else if($commentStatus->comment_status == -1)
					{
						try
						{
							if(!DB::getInstance()->update('users_comments_status', $comment_user_id, array('comment_status' => 1)))
								throw new Exception("Your response couldn't be added right now. Please try again later 56");
							$count_likes = $count_likes + 1;
							$count_dislikes = $count_dislikes - 1;
							if(!DB::getInstance()->update('comments', $comment_id, array('likes' => $count_likes, 'dislikes' => $count_dislikes)))
								throw new Exception("Your response couldn't be added right now. Please try again later 63");
							$json['count_likes'] = $count_likes;
							$json['count_dislikes'] = $count_dislikes;
							$json['comment_status'] = 1;
						}
						catch(Exception $e)
						{
							$json['error_staus'] = true;
							$json['error'] = $e->getMessage();
						}
					}
				}
				else if($field == 'comment-dislike')
				{
					if($commentStatus->comment_status == -1)
					{
						try
						{
							if(!DB::getInstance()->update('users_comments_status', $comment_user_id, array('comment_status' => 0)))
								throw new Exception("Your response couldn't be added right now. Please try again later 78");
							$count_dislikes = $count_dislikes - 1;
							if(!DB::getInstance()->update('comments', $comment_id, array('dislikes' => $count_dislikes)))
								throw new Exception("Your response couldn't be added right now. Please try again later 85");
							$json['count_likes'] = $count_likes;
							$json['count_dislikes'] = $count_dislikes;
							$json['comment_status'] = 0;
						}
						catch(Exception $e)
						{
							$json['error_staus'] = true;
							$json['error'] = $e->getMessage();
						}
					}
					else if($commentStatus->comment_status == 0)
					{
						try
						{
							if(!DB::getInstance()->update('users_comments_status', $comment_user_id, array('comment_status' => -1)))
								throw new Exception("Your response couldn't be added right now. Please try again later 97");
							$count_dislikes = $count_dislikes + 1;
							if(!DB::getInstance()->update('comments', $comment_id, array('dislikes' => $count_dislikes)))
								throw new Exception("Your response couldn't be added right now. Please try again later 104");
							$json['count_likes'] = $count_likes;
							$json['count_dislikes'] = $count_dislikes;
							$json['comment_status'] = -1;
						}
						catch(Exception $e)
						{
							$json['error_staus'] = true;
							$json['error'] = $e->getMessage();
						}
					}
					else if($commentStatus->comment_status == 1)
					{
						try
						{
							if(!DB::getInstance()->update('users_comments_status', $comment_user_id, array('comment_status' => -1)))
								throw new Exception("Your response couldn't be added right now. Please try again later 97");
							$count_dislikes = $count_dislikes + 1;
							$count_likes = $count_likes - 1;
							if(!DB::getInstance()->update('comments', $comment_id, array('dislikes' => $count_dislikes, 'likes' => $count_likes)))
								throw new Exception("Your response couldn't be added right now. Please try again later 104");
							$json['count_likes'] = $count_likes;
							$json['count_dislikes'] = $count_dislikes;
							$json['comment_status'] = -1;
						}
						catch(Exception $e)
						{
							$json['error_staus'] = true;
							$json['error'] = $e->getMessage();
						}
					}
				}
			}
			else
			{
				if($field == 'comment-like')
				{
					try
					{
						$commentStatus = DB::getInstance()->insert('users_comments_status', array(
							'user_id' => $user_id,
							'comment_id' => $comment_id,
							'comment_status' => 1
							));
						if(!$commentStatus)
							throw new Exception("Your response couldn't be added right now. Please try again later 125");
						$count = DB::getInstance()->get('comments', array('id', '=', $comment_id));
						if($count->count() == 1)
						{
							$count_likes = $count->first()->likes + 1;
						}
						if(!DB::getInstance()->update('comments', $comment_id, array('likes' => $count_likes)))
								throw new Exception("Your response couldn't be added right now. Please try again later 132");
						$json['count_likes'] = $count_likes;
						$json['count_dislikes'] = 0;
						$json['comment_status'] = 1;
					}
					catch(Exception $e)
					{
						$json['error_staus'] = true;
						$json['error'] = $e->getMessage();
					}
				}
				else if($field == 'comment-dislike')
				{
					try
					{
						$commentStatus = DB::getInstance()->insert('users_comments_status', array(
							'user_id' => $user_id,
							'comment_id' => $comment_id,
							'comment_status' => -1
							));
						if(!$commentStatus)
							throw new Exception("Your response couldn't be added right now. Please try again later 149");
						$count = DB::getInstance()->get('comments', array('id', '=', $comment_id));
						if($count->count() == 1)
						{
							$count_dislikes = $count->first()->dislikes + 1;
						}
						if(!DB::getInstance()->update('comments', $comment_id, array('dislikes' => $count_dislikes)))
								throw new Exception("Your response couldn't be added right now. Please try again later 156");
						$json['count_likes'] = 0;
						$json['count_dislikes'] = $count_dislikes;
						$json['comment_status'] = -1;
					}
					catch(Exception $e)
					{
						$json['error_staus'] = true;
						$json['error'] = $e->getMessage();
					}
				}
			}
		}
		else
		{
			$json['error_staus'] = true;
			$json['error'] = "You need to login to vote";
		}
		header("Content-Type: application/json", true);
		echo json_encode($json);
	}
	// else
	// {
	// 	$json['error_status'] = true;
	// 	$json['error'] = "Token mismatch error, try again after refreshing the page";
	// 	header("Content-Type: application/json", true);
	// 	echo json_encode($json);
	// }
}
else
{
	Redirect::to('index.php');
}

?>
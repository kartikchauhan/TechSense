<?php

require_once'Core/init.php';

if(Input::exists('post'))
{
	if(Token::check(Input::get('_token')))
	{
		$json['error'] = false;
		$json['_token'] = Token::generate();

		$user = new User;

		if($user->isLoggedIn())
		{
			$blog_id = Input::get('blog_id');	// blog's id of the current blog
			$user_id = $user->data()->id;	// user's id of the current user
			$field = Input::get('field');	// get the field ie. likes or dislikes
			$blogStatus = DB::getInstance()->getAnd('users_blogs_status', array(
			'user_id' => $user_id,
			'blog_id' => $blog_id
			));		// fetch if there exists any record in the table users_blogs_status		
			if($blogStatus->count() == 1)
			{
			$blogStatus = $blogStatus->first();
			$blog_user_id = $blogStatus->id;	// get the id of the record where user_id is current user's id and blog_id is current blog's id
			$count = DB::getInstance()->get('blogs', array('id', '=', $blog_id));
			if($count->count() == 1)
			{
				$count_likes = $count->first()->likes;
				$count_dislikes = $count->first()->dislikes;
			}
			if($field == 'likes')
			{
				if($blogStatus->blog_status == 1)	// status 1 => like, status 0 => neutral, status -1 => dislike
				{
					try
					{
						if(!DB::getInstance()->update('users_blogs_status', $blog_user_id, array('blog_status' => 0)))	// if user clicks again to like than nullify(neutral) the response
							throw new Exception("Your response couldn't be added right now. Please try again later 37");
						$count_likes = $count_likes - 1;
						if(!DB::getInstance()->update('blogs', $blog_id, array('likes' => $count_likes)))
							throw new Exception("Your response couldn't be added right now. Please try again later 44");
						$json['blog_status'] = 0;
					}
					catch(Exception $e)
					{
						$json['error_staus'] = true;
						$json['error'] = $e->getMessage();
					}
				}
				else if($blogStatus->blog_status == 0)
				{
					try
					{
						if(!DB::getInstance()->update('users_blogs_status', $blog_user_id, array('blog_status' => 1)))
							throw new Exception("Your response couldn't be added right now. Please try again later 56");
						$count_likes = $count_likes + 1;
						if(!DB::getInstance()->update('blogs', $blog_id, array('likes' => $count_likes)))
							throw new Exception("Your response couldn't be added right now. Please try again later 63");
						$json['blog_status'] = 1;
					}
					catch(Exception $e)
					{
						$json['error_staus'] = true;
						$json['error'] = $e->getMessage();
					}
				}
				else if($blogStatus->blog_status == -1)
				{
					try
					{
						if(!DB::getInstance()->update('users_blogs_status', $blog_user_id, array('blog_status' => 1)))
							throw new Exception("Your response couldn't be added right now. Please try again later 56");
						$count_likes = $count_likes + 1;
						$count_dislikes = $count_dislikes - 1;
						if(!DB::getInstance()->update('blogs', $blog_id, array('likes' => $count_likes, 'dislikes' => $count_dislikes)))
							throw new Exception("Your response couldn't be added right now. Please try again later 63");
						$json['blog_status'] = 1;
					}
					catch(Exception $e)
					{
						$json['error_staus'] = true;
						$json['error'] = $e->getMessage();
					}
				}
			}
			else if($field == 'dislikes')
			{
				if($blogStatus->blog_status == -1)
				{
					try
					{
						if(!DB::getInstance()->update('users_blogs_status', $blog_user_id, array('blog_status' => 0)))
							throw new Exception("Your response couldn't be added right now. Please try again later 78");
						$count_dislikes = $count_dislikes - 1;
						if(!DB::getInstance()->update('blogs', $blog_id, array('dislikes' => $count_dislikes)))
							throw new Exception("Your response couldn't be added right now. Please try again later 85");
						$json['blog_status'] = 0;
					}
					catch(Exception $e)
					{
						$json['error_staus'] = true;
						$json['error'] = $e->getMessage();
					}
				}
				else if($blogStatus->blog_status == 0)
				{
					try
					{
						if(!DB::getInstance()->update('users_blogs_status', $blog_user_id, array('blog_status' => -1)))
							throw new Exception("Your response couldn't be added right now. Please try again later 97");
						$count_dislikes = $count_dislikes + 1;
						if(!DB::getInstance()->update('blogs', $blog_id, array('dislikes' => $count_dislikes)))
							throw new Exception("Your response couldn't be added right now. Please try again later 104");
						$json['blog_status'] = -1;
					}
					catch(Exception $e)
					{
						$json['error_staus'] = true;
						$json['error'] = $e->getMessage();
					}
				}
				else if($blogStatus->blog_status == 1)
				{
					try
					{
						if(!DB::getInstance()->update('users_blogs_status', $blog_user_id, array('blog_status' => -1)))
							throw new Exception("Your response couldn't be added right now. Please try again later 97");
						$count_dislikes = $count_dislikes + 1;
						$count_likes = $count_likes - 1;
						if(!DB::getInstance()->update('blogs', $blog_id, array('dislikes' => $count_dislikes, 'likes' => $count_likes)))
							throw new Exception("Your response couldn't be added right now. Please try again later 104");
						$json['blog_status'] = -1;
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
			if($field == 'likes')
			{
				try
				{
					$blogStatus = DB::getInstance()->insert('users_blogs_status', array(
						'user_id' => $user_id,
						'blog_id' => $blog_id,
						'blog_status' => 1
						));
					if(!$blogStatus)
						throw new Exception("Your response couldn't be added right now. Please try again later 125");
					$count = DB::getInstance()->get('blogs', array('id', '=', $blog_id));
					if($count->count() == 1)
					{
						$count_likes = $count->first()->likes + 1;
					}
					if(!DB::getInstance()->update('blogs', $blog_id, array('likes' => $count_likes)))
							throw new Exception("Your response couldn't be added right now. Please try again later 132");
					$json['blog_status'] = 1;
				}
				catch(Exception $e)
				{
					$json['error_staus'] = true;
					$json['error'] = $e->getMessage();
				}
			}
			else if($field == 'dislikes')
			{
				try
				{
					$blogStatus = DB::getInstance()->insert('users_blogs_status', array(
						'user_id' => $user_id,
						'blog_id' => $blog_id,
						'blog_status' => -1
						));
					if(!$blogStatus)
						throw new Exception("Your response couldn't be added right now. Please try again later 149");
					$count = DB::getInstance()->get('blogs', array('id', '=', $blog_id));
					if($count->count() == 1)
					{
						$count_dislikes = $count->first()->dislikes + 1;
					}
					if(!DB::getInstance()->update('blogs', $blog_id, array('dislikes' => $count_dislikes)))
							throw new Exception("Your response couldn't be added right now. Please try again later 156");
					$json['blog_status'] = -1;
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
<?php

require_once'Core/init.php';

$user = new User;

if(!$user->isLoggedIn())
{
	Redirect::to('index.php');
}

if(Input::exists())
{
	if(Token::check(Input::get('_token')))
	{
		$json['_token'] = Token::generate();
		$records_per_page = 3;

		$page_id = Input::get('page_id') - 1;	// get page_if from user ie get the page number of which user wants to see blogs
												// subtraction 1 is to maintain offset. 
												// example => if page_id = 2 and records_per_page = 3
												// then offset = 3, records in the range of 3-6 will show on the second page
		$offset = $page_id * $records_per_page;
		if(Input::get('author'))
		{
			$blogs = DB::getInstance()->getRangeSortUser('blogs', $records_per_page, $offset, array('created_on', 'DESC'), array('users_id', '=', 1)); // for temporary usage , using users_id = 1
		}
		else
		{
			$blogs = DB::getInstance()->getRangeSort('blogs', $records_per_page, $offset, array('created_on', 'DESC'));	// get records in descending order within a certian range based upon the offset and records_per_page values
		}
		if($blogs->count())
		{
			$blogs = $blogs->results();
			$json['content'] = '';	// content will hold the html 
			foreach($blogs as $blog)
			{
				$date=strtotime($blog->created_on);
				$json['content'] = $json['content'].
					"<div class='row'>
			            <div class='col s2'>
			                <blockquote>".
			                    date('M', $date)."<br>".
			                    date('Y d', $date).
			                "</blockquote>
			            </div>
			            <div class='col s10'>
			                <h5><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h5>
			                <h6>".ucfirst($blog->description)."</h6><br>
			                <div class='row'>
			                    <div class='measure-count' data-attribute='{$blog->id}'>
			                        <div class='col s1'>
			                            <i class='fa fa-eye fa-2x' aria-hidden='true' style='color:grey'></i>
			                        </div>
			                        <div class='col s1'>
			                            {$blog->views}
			                        </div>
			                        <div class='col s1 offset-s1'>
			                            <i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color:grey'></i>
			                        </div>
			                        <div class='col s1'>
			                            {$blog->likes}
			                        </div>
			                        <div class='col s1 offset-s1'>
			                            <i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color:grey'></i>
			                        </div>
			                        <div class='col s1'>
			                            {$blog->dislikes}
			                        </div>
			                    </div>
			                </div>
			                <div class='divider'></div>
			            </div>
			        </div>";
			}
		}
		else
		{
			$json['content'] = 'Sorry, no more blogs';
		}

		echo json_encode($json);
	}
}



?>
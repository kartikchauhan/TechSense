<?php

require_once'Core/init.php';

$user = new User;

if(Input::exists())
{
	// if(Token::check(Input::get('_token')))
	// {
		// $json['_token'] = Token::generate();
		$records_per_page = 5;

		$page_id = Input::get('page_id') - 1;	// get page_if from user ie get the page number of which user wants to see blogs
												// subtraction 1 is to maintain offset. 
												// example => if page_id = 2 and records_per_page = 3
												// then offset = 3, records in the range of 3-6 will show on the second page
		$offset = $page_id * $records_per_page;
		if(!empty(Input::get('author')))
		{
			$blogs = DB::getInstance()->getRangeSortUser('blogs', $records_per_page, $offset, array('created_on', 'DESC'), array('users_id', '=', $user->data()->id)); // for temporary usage , using users_id = 1
		}
		else 
		{
			$blogs = DB::getInstance()->getRangeSort('blogs', $records_per_page, $offset, array('created_on', 'DESC'));	// get records in descending order within a certian range based upon the offset and records_per_page values
		}
		if($blogs->count() && !empty(Input::get('author')))
		{
			$blogs = $blogs->results();
			$json['content'] = '';	// content will hold the html 
			foreach($blogs as $blog)
			{
				$blog_tags = DB::getInstance()->get('blog_tags', array('blog_id', '=', $blog->id));
                $blog_tags = $blog_tags->results();
				$date=strtotime($blog->created_on);
				$json['content'] = $json['content'].
					"<div class='row blog'>
						<div class='col s12 hide-on-med-and-up'>
                    		<div class='col s6'>
                                <blockquote>".
                                    date('M d', $date).' '.
                                    date('Y', $date).
                                "</blockquote>
                            </div>
                            <div class='col s6'>
                            	<a href='#' class='blue-text delete-blog' data-attribute='{$blog->id}'><i class='material-icons right'>delete</i></a> <a href='update_blog.php?blog_id={$blog->id}' class='blue-text edit-blog' data-attribute='{$blog->id}'><i class='material-icons right'>mode_edit</i></a> 
                            </div>
                    	</div>
                        <div class='col s2 l2 hide-on-small-only'>
                            <blockquote>".
                                date('M', $date)."<br>".
                                date('Y d', $date).
                            "</blockquote>
                        </div>
			            <div class='col s12 l10'>
		            		<div class='row'>
	                        	<div class='col s12 l10'>
	                            	<h5><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h5>
	                            </div>
                            	<div class='hide-on-small-only'>
	                            	<a href='#' class='blue-text delete-blog' data-attribute='{$blog->id}'><i class='material-icons right'>delete</i></a> <a href='update_blog.php?blog_id={$blog->id}' class='blue-text edit-blog' data-attribute='{$blog->id}'><i class='material-icons right'>mode_edit</i></a> 
                            	</div>
	                        </div>
                			<h6>".ucfirst($blog->description)."</h6><br>
			                <div class='row'>
			                    <div class='measure-count' data-attribute='{$blog->id}'>
			                        <div class='col s2 l1'>
			                            <i class='fa fa-eye fa-2x' aria-hidden='true' style='color:grey'></i>
			                        </div>
			                        <div class='col s1 l1'>
			                            {$blog->views}
			                        </div>
			                        <div class='col s2 l1 offset-s1 offset-l1'>
			                            <i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color:grey'></i>
			                        </div>
			                        <div class='col s1 l1'>
			                            {$blog->likes}
			                        </div>
			                        <div class='col s2 l1 offset-s1 offset-l1'>
			                            <i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color:grey'></i>
			                        </div>
			                        <div class='col s1 l1'>
			                            {$blog->dislikes}
			                        </div>
			                    </div>
			                </div>";
                            foreach($blog_tags as $blog_tag)
                            {
                                $json['content'] = $json['content'].
                                "<div class='chip'>".$blog_tag->tags."</div>";
                            }
                            $json['content'] = $json['content'].
			                "<div class='section'>
			                	<div class='divider'></div>
			                </div>
			            </div>
			        </div>";
			}
		}
		else if($blogs->count() && empty(Input::get('author')) && (Input::get('pagination_item') == 0))
		{
			$blogs = $blogs->results();
			$json['content'] = '';	// content will hold the html 
			foreach($blogs as $blog)
			{
				$blog_tags = DB::getInstance()->get('blog_tags', array('blog_id', '=', $blog->id));
                $blog_tags = $blog_tags->results();
				$date=strtotime($blog->created_on);
				$json['content'] = $json['content'].
					"<div class='pagination_item_value' data-attribute='0'></div>
					<div class='row blog'>
			            <div class='col s12 hide-on-med-and-up'>
                            <div class='col s6'>
                                <blockquote>".
                                    date('M d', $date).' '.
                                    date('Y', $date).
                                "</blockquote>
                            </div>
                        </div>
                        <div class='col s2 l2 hide-on-small-only'>
                            <blockquote>".
                                date('M', $date)."<br>".
                                date('Y d', $date).
                            "</blockquote>
                        </div>
			            <div class='col s12 l10'>
		            		<div class='row'>
	                        	<div class='col s12 l10'>
	                            	<h5><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h5>
                					<h6>".ucfirst($blog->description)."</h6><br>
	                            </div>
	                        </div>
			                <div class='row'>
			                    <div class='measure-count' data-attribute='{$blog->id}'>
			                        <div class='col s2 l1'>
			                            <i class='fa fa-eye fa-2x' aria-hidden='true' style='color:grey'></i>
			                        </div>
			                        <div class='col s1 l1'>
			                            {$blog->views}
			                        </div>
			                        <div class='col s2 l1 offset-s1 offset-l1'>
			                            <i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color:grey'></i>
			                        </div>
			                        <div class='col s1 l1'>
			                            {$blog->likes}
			                        </div>
			                        <div class='col s2 l1 offset-s1 offset-l1'>
			                            <i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color:grey'></i>
			                        </div>
			                        <div class='col s1 l1'>
			                            {$blog->dislikes}
			                        </div>
			                    </div>
			                </div>";
                            foreach($blog_tags as $blog_tag)
                            {
                                $json['content'] = $json['content'].
                                "<div class='chip'>".$blog_tag->tags."</div>";
                            }
                            $json['content'] = $json['content'].
			                "<div class='divider'></div>
			            </div>
			        </div>";
			}
		}
		else if(empty(Input::get('author')) && (Input::get('pagination_item') == 1 || Input::get('pagination_item') == 2 || Input::get('pagination_item') == 3 || Input::get('pagination_item') == 4))
		{
			$search = DB::getInstance();
			$query = Input::get('query');
			$query = split(":", $query);
			$query_field = strtolower(trim($query[0]));
			$query_field_value = strtolower(trim($query[1]));
			if($query_field === 'user')
			{
				$result = $search->searchIdViaUser('users', array('username', '=', $query_field_value));		// fetching the id of the user
				try
				{
					if(!$result)
					{
						throw new Exception("Some error occured while fetching results. Please try again later");
					}
					if($result->count() != 1)
					{
						throw new Exception("There is no user with Username ".$query_field_value);
					}
					$result_id = $result->results()[0]->id;
					$resultBlogs = $search->searchBlogsViaUser('blogs', array('users_id', '=', $result_id), array('created_on', 'DESC'), $records_per_page, $offset);
					if(!$resultBlogs)
					{
						throw new Exception("Some error occured while fetching results. Please try again later");
					}
					if($resultBlogs->count() == 0)
					{
						throw new Exception("There are no blogs associated to User ".$query_field_value);
					}
					$resultBlogs = $resultBlogs->results();
					$json['content'] = '';
					foreach($resultBlogs as $blog)
					{
						$blog_tags = DB::getInstance()->get('blog_tags', array('blog_id', '=', $blog->id));
		                $blog_tags = $blog_tags->results();
						$date=strtotime($blog->created_on);
						$json['content'] = $json['content'].
							"<div class='pagination_item_value' data-attribute='1'></div>
							<div class='row blog'>
					            <div class='col s12 hide-on-med-and-up'>
		                            <div class='col s6'>
		                                <blockquote>".
		                                    date('M d', $date).' '.
		                                    date('Y', $date).
		                                "</blockquote>
		                            </div>
		                        </div>
		                        <div class='col s2 l2 hide-on-small-only'>
		                            <blockquote>".
		                                date('M', $date)."<br>".
		                                date('Y d', $date).
		                            "</blockquote>
		                        </div>
					            <div class='col s12 l10'>
				            		<div class='row'>
			                        	<div class='col s12 l10'>
			                            	<h5><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h5>
		                					<h6>".ucfirst($blog->description)."</h6><br>
			                            </div>
			                        </div>
					                <div class='row'>
					                    <div class='measure-count' data-attribute='{$blog->id}'>
					                        <div class='col s2 l1'>
					                            <i class='fa fa-eye fa-2x' aria-hidden='true' style='color:grey'></i>
					                        </div>
					                        <div class='col s1 l1'>
					                            {$blog->views}
					                        </div>
					                        <div class='col s2 l1 offset-s1 offset-l1'>
					                            <i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color:grey'></i>
					                        </div>
					                        <div class='col s1 l1'>
					                            {$blog->likes}
					                        </div>
					                        <div class='col s2 l1 offset-s1 offset-l1'>
					                            <i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color:grey'></i>
					                        </div>
					                        <div class='col s1 l1'>
					                            {$blog->dislikes}
					                        </div>
					                    </div>
					                </div>";
		                            foreach($blog_tags as $blog_tag)
		                            {
		                                $json['content'] = $json['content'].
		                                "<div class='chip'>".$blog_tag->tags."</div>";
		                            }
		                            $json['content'] = $json['content'].
					                "<div class='divider'></div>
					            </div>
					        </div>";
					}
				}
				catch(Exception $e)
				{
					$json['content'] = 
						"<div class='pagination_item_value' data-attribute='1'></div>
						<div class='section'>
							<h6 class='center'>".$e->getMessage()."</h6>
						</div>";
				}
			}
		}
		else
		{
			$json['content'] = 'Sorry, no blogs';
		}
		header("Content-Type: application/json", true);
		echo json_encode($json);
	// }
	// else
	// {
	// 	$json['error_status'] = true;
	// 	$json['error'] = "Token mismatch error, try again after refreshing the page";
	// 	echo json_encode($json);
	// }
}
else
{
	Redirect::to('index.php');
}



?>
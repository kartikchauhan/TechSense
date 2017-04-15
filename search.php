<?php

require_once'Core/init.php';

if(Input::exists('post'))
{
	if(Token::check(Input::get('_token')))
	{
		$records_per_page = 5;
		$offset = 0;
		$json['error_code'] = 0;	// error_code = 0 => for all type of errors except token_mismatch
		$json['error_status'] = false;
		$json['_token'] = Token::generate();
		$json['content'] = '';

		$search = DB::getInstance();
		$query = Input::get('query');
		try
		{
			$query = split(":", $query);
			if(count($query) != 2)
			{
				throw new Exception("You've entered an invalid query");
			}
			$query_field = strtolower(trim($query[0]));
			$query_field_value = strtolower(trim($query[1]));
			if($query_field === 'user')
			{
				$result = $search->searchIdViaUser('users', array('username', '=', $query_field_value));		// fetching the id of the user
				try
				{
					if(!$result)	// throw error if unable to fetch desired result
					{
						throw new Exception("Some error occured while fetching results. Please try again later");
					}
					if($result->count() != 1)	// throw error if unable to find user with name provided by user
					{
						throw new Exception("There is no user with Username ".$query_field_value);
					}
					$result_id = $result->results()[0]->id;		// fetch the id of the user
					// count the number of blogs fetched
					$countBlogs = $search->searchBlogsViaUser('blogs', array('users_id', '=', $result_id))->count();
					//  compute the number of paged we will require to show every blog associated with the respective user
					$countBlogs = ceil($countBlogs/$records_per_page);
					// fetch the first 5 blogs in descending order of their creation date
					$resultBlogs = $search->searchBlogsViaUser('blogs', array('users_id', '=', $result_id), array('created_on', 'DESC'), $records_per_page, $offset);
					if(!$resultBlogs)
					{
						throw new Exception("Some error occured while fetching results. Please try again later");
					}
					if($resultBlogs->count() == 0)
					{
						throw new Exception("There are no blogs associated with User ".$query_field_value);
					}
					$resultBlogs = $resultBlogs->results();
					$json['content'] = 	$json['content'].
											"<div class='pagination_item_value' data-attribute='true'></div>
												<div class='content' id='content'>";
					addHtmlToResponse($json, $resultBlogs, $countBlogs, true);		// add HTML content to the resonse
				}
				catch(Exception $e)
				{
					addErrorToResponse($json, $e->getMessage());
				}
			}
			else if($query_field === 'title')
			{
				$query_field_value = '%'.$query_field_value.'%';

				$countBlogs = $search->searchBlogsViaTitle('blogs', array('title', 'like', $query_field_value))->count();
				$countBlogs = ceil($countBlogs/$records_per_page);
				$resultBlogs = $search->searchBlogsViaTitle('blogs', array('title', 'like', $query_field_value), array('created_on', 'DESC'), $records_per_page, $offset);
				try
				{
					if(!$resultBlogs)
					{
						throw new Exception("Some error occured while fetching results. Please try again later");
					}
					if($resultBlogs->count() == 0)
					{
						$query_field_value = substr($query_field_value, 1, -1);		// striping first and last character wiz. '%'
						throw new Exception("There are no blogs associated with the title ".$query_field_value);
					}
					$resultBlogs = $resultBlogs->results();
					$json['content'] = 	$json['content'].
											"<div class='pagination_item_value' data-attribute='true'></div>
												<div class='content' id='content'>";
					addHtmlToResponse($json, $resultBlogs, $countBlogs, true);

				}
				catch(Exception $e)
				{
					addErrorToResponse($json, $e->getMessage());
				}
			}
			else if($query_field === 'name')
			{
				$query_field_value = '%'.$query_field_value.'%';
				$result = $search->searchIdViaName('users', array('name', 'like', $query_field_value));		// fetching the id of the user
				try
				{
					if(!$result)
					{
						throw new Exception("Some error occured while fetching results. Please try again later");
					}
					if($result->count() == 0)
					{
						$query_field_value = substr($query_field_value, 1, -1);		// striping first and last character wiz. '%'
						throw new Exception("There is no user with the name ".$query_field_value);
					}
					$counter = 0;	// counter for checking if there're any blogs associated with this name
					$paginationCounter = 0;
					$temp_records_per_page = $records_per_page;
					$result_copy = clone $result;	// cloning the object result to use it's properties later again
					foreach($result->results() as $user)
					{
						$paginationCounter = $paginationCounter + $search->searchBlogsViaName('blogs', array('users_id', '=', $user->id))->count();
					}
					if($paginationCounter != 0)
					{
						$json['content'] = 	$json['content'].
												"<div class='pagination_item_value' data-attribute='true'></div>
													<div class='content' id='content'>";
						foreach($result_copy->results() as $user)	// looping over all the users with the respective name we got
						{
							$resultBlogs = $search->searchBlogsViaName('blogs', array('users_id', '=', $user->id), array('created_on', 'DESC'), $temp_records_per_page, $offset);		// fetching all blogs associated with every user with the name provided by user
							$temp_records_per_page = $temp_records_per_page - $resultBlogs->count();
							if($resultBlogs->count() != 0)
							{
								$counter = $counter + $resultBlogs->count();
								addHtmlToResponse($json, $resultBlogs->results(), null, false);
							}
							if($counter == 5 || $temp_records_per_page == 0)
							{
								break;
							}
						}
						$countBlogs = ceil($paginationCounter/$records_per_page);
						addPaginationComponents($json, $countBlogs);
					}else if($paginationCounter == 0)
					{
						throw new Exception("There are no blogs associated with the name ".$query_field_value);
					}
				}
				catch(Exception $e)
				{
					addErrorToResponse($json, $e->getMessage());
				}
			}
			else if($query_field === 'tags')
			{
				$tags = split(',', $query_field_value);
				$tags = array_map('trim', $tags);
				$result = $search->searchBlogIdViaTags('blog_tags', $tags, array('tags', '='), array('blog_id', 'DESC'));
				try
				{
					if(!$result)
					{
						throw new Exception("Some error occured while fetching results. Please try again later");
					}
					if($result->count() == 0)	
					{
						throw new Exception("There is no blog associated with the provided tags.");
					}
					$paginationCounter = $result->count();
									
					if($paginationCounter != 0)
					{
						$result = $search->searchBlogIdViaTags('blog_tags', $tags, array('tags', '='), array('blog_id', 'DESC'), $records_per_page, $offset);
						$json['content'] = 	$json['content'].
												"<div class='pagination_item_value' data-attribute='true'></div>
													<div class='content' id='content'>";
						foreach($result->results() as $blog_obj)	// looping over all the users with the respective name we got
						{
							$resultBlogs = $search->searchBlogsViaTags('blogs', array('id', '=', $blog_obj->blog_id));		// fetching all blogs associated with every user with the name provided by user
							addHtmlToResponse($json, $resultBlogs->results(), null, false);
						}
						$countBlogs = ceil($paginationCounter/$records_per_page);
						addPaginationComponents($json, $countBlogs);
					}
					else if($paginationCounter == 0)
					{
						throw new Exception("There are no blogs associated with the name ".$query_field_value);
					}				
				}
				catch(Exception $e)
				{
					addErrorToResponse($json, $e->getMessage());
				}
			}
			else
			{
				addInvalidQueryError($json, "You've entered an invalid query");
			}		

		}
		catch(Exception $e)
		{
			addInvalidQueryError($json, $e->getMessage());
		}
		
	}
	else
	{
		$json['error_code'] = 1;	// error_code = 1 => for token_mismatch error
		$json['error_status'] = true;
		$json['error'] = "Token mismatch error, try again after refreshing the page";
	}
	header("Content-Type: application/json", true);
	echo json_encode($json);
}
else
{
	Redirect::to('index.php');
}


function addHtmlToResponse($json, $resultBlogs, $countBlogs = null, $flag = true)
{
	global $json;
	foreach($resultBlogs as $blog)
	{
		$blog_tags = DB::getInstance()->get('blog_tags', array('blog_id', '=', $blog->id));
        $blog_tags = $blog_tags->results();
		$date=strtotime($blog->created_on);
		$writer = DB::getInstance()->get('users', array('id', '=', $blog->users_id))->first()->username;
		$json['content'] = $json['content'].
			"<div class='row blog'>
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
            		<div class='row margin-eliminate'>
                    	<div class='col s12 l10'>
                        	<h5><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h5>
        					<h6>".ucfirst($blog->description)."</h6><br>
                        </div>
                    </div>
                    <div class='row margin-eliminate'>                                        
                        <div class='col l4 s6'>
                            <p class='grey-text'>".$blog->blog_minutes_read." minutes read</p>
                        </div>
                        <div class='col l4 s6'>
                            <p class='grey-text right-align'>- ".$writer."</p>
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
	if($flag === true)
	{
		$json['content'] = $json['content'].
		"</div>
		<div class='section center-align'>
			<ul class='pagination'>";
		        for($x = 1; $x <= $countBlogs; $x++)
		        {					        	
		        	if($x == 1)
		        	{
		        		$json['content'] .= "<li class='waves-effect pagination active'><a href='#' class='blog-pagination'>".$x."</a></li>";
		        	}
		        	else
		        	{
		        		$json['content'] .= "<li class='waves-effect pagination'><a href='#' class='blog-pagination'>".$x."</a></li>";
		        	}
		        }
	        $json['content'] = $json['content'].
	        "</ul>
    	</div>";
	}
						
}

function addErrorToResponse($json, $errorMessage)
{
	global $json;
	$json['error_status'] = true;
	$json['content'] = $json['content'].
						"</div>
						<div class='section'>
							<h6 class='center'>".$errorMessage."</h6>
						</div>";
}

function addInvalidQueryError($json, $errorMessage)
{
	global $json;
	$json['error_status'] = true;
	$json['content'] = $json['content'].
						"<div class='section'>
							<h6 class='center'>".$errorMessage."</h6>
						</div>";
}

function addPaginationComponents($json, $countBlogs)
{
	global $json;
	$json['content'] = $json['content'].
						"</div>
							<div class='section center-align'>
        						<ul class='pagination'>";
							        for($x = 1; $x <= $countBlogs; $x++)
							        {					        	
							        	if($x == 1)
							        	{
							        		$json['content'] .= "<li class='waves-effect pagination active'><a href='#' class='blog-pagination'>".$x."</a></li>";
							        	}
							        	else
							        	{
							        		$json['content'] .= "<li class='waves-effect pagination'><a href='#' class='blog-pagination'>".$x."</a></li>";
							        	}
							        }
						        $json['content'] = $json['content'].
						        "</ul>
					        </div>";
}


?>
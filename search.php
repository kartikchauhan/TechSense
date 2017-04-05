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
		$query = split(":", $query);
		$query_field = strtolower(trim($query[0]));
		$query_field_value = strtolower(trim($query[1]));

		if($query_field === 'user')
		{
			$result = $search->searchIdViaUser('users', array('username', '=', $query_field_value));		// fetching the id of the user
			try
			{
				if(!$result)	// throw error if unable to fetch desired result
				{
					throw new Exception("Some error occured while fetching results. Please try again later 28");
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
					throw new Exception("Some error occured while fetching results. Please try again later 37");
				}
				if($resultBlogs->count() == 0)
				{
					throw new Exception("There are no blogs associated with User ".$query_field_value);
				}
				$resultBlogs = $resultBlogs->results();
				$json['content'] = 	$json['content'].
										"<div class='content' id='content'>
											<div class='pagination_item_value' data-attribute='1'></div>";
				addHtmlToResponse($json, $resultBlogs, $countBlogs);		// add HTML content to the resonse
			}
			catch(Exception $e)
			{
				addErrorToResponse($json, $e->getMessage());
			}
		}
		else if($query_field === 'tags')
		{
			echo 'tags';
			$tags = split(',', $query_field_value);
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
										"<div class='content' id='content'>
											<div class='pagination_item_value' data-attribute='1'></div>";
				addHtmlToResponse($json, $resultBlogs, $countBlogs);

			}
			catch(Exception $e)
			{
				addErrorToResponse($json, $e->getMessage());
			}
		}
		else if($query_field === 'name')
		{
			$result = $search->searchIdViaName('users', array('name', '=', $query_field_value));		// fetching the id of the user
			try
			{
				if(!$result)
				{
					throw new Exception("Some error occured while fetching results. Please try again later");
				}
				if($result->count() == 0)
				{
					throw new Exception("There is no user with the name ".$query_field_value);
				}
				$counter = 0;
				foreach($result->results() as $key)
				{
					echo "<br><br>fetching results from name <br><br>";
					$resultBlogs = $search->searchBlogsViaName('blogs', array('users_id', '=', $key->id));
					if($resultBlogs->count() != 0)
					{
						$counter += $resultBlogs->count();
						foreach ($resultBlogs->results() as $key) {
							var_dump($key->title);
						}
					}
				}
				if($counter == 0)
				{
					throw new Exception("There are no blogs associated with the name ".$query_field_value);
					
				}
				// $resultBlogs = $search->searchBlogsViaUser('blogs', array('users_id', '=', $result->results()[0]->id));
				// if(!$resultBlogs)
				// {
				// 	throw new Exception("Some error occured while fetching results. Please try again later");
				// }
				// if($resultBlogs->count() == 0)
				// {
				// 	throw new Exception("There are no blogs related to User ".$query_field_value);
				// }
				
			}
			catch(Exception $e)
			{
				echo($e->getMessage());
			}
		}
		header("Content-Type: application/json", true);
		echo json_encode($json);
	}
	else
	{

		$json['error_code'] = 1;	// error_code = 1 => for token_mismatch error
		$json['error_status'] = true;
		$json['error'] = "Token mismatch error, try again after refreshing the page";
		header("Content-Type: application/json", true);
		echo json_encode($json);
	}
}
else
{
	Redirect::to('index.php');
}


function addHtmlToResponse($json, $resultBlogs, $countBlogs)
{
	global $json;
	foreach($resultBlogs as $blog)
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

function addErrorToResponse($json, $errorMessage)
{
	global $json;
	$json['content'] = $json['content'].
						"<div class='section'>
							<h6 class='center'>".$errorMessage."</h6>
						</div>";

}
?>
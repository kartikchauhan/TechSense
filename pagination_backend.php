<?php

require_once'Core/init.php';

$user = new User;

if(Input::exists())
{
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
		$determining_factor = Input::get('determining_factor');	// determining_factor is the variable holding the value of recent_blog/recommended_blog.
		if($determining_factor == 'recent_blogs')
			$fieldToSort = 'created_on';
		else if($determining_factor == 'recommended_blogs')
			$fieldToSort = 'views';
		$blogs = DB::getInstance()->getRangeSort('blogs', $records_per_page, $offset, array($fieldToSort, 'DESC'));	// get records in descending order within a certian range based upon the offset and records_per_page values
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
					<div class='col s12 hide-on-large-only'>
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
                    <div class='col s2 l2 hide-on-med-and-down'>
                        <blockquote>".
                            date('M', $date)."<br>".
                            date('Y d', $date).
                        "</blockquote>
                    </div>
		            <div class='col s12 l10'>
	            		<div class='row hide-on-med-and-down'>
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
	else if($blogs->count() && empty(Input::get('author')))
	{
		$blogs = $blogs->results();
		$json['content'] = '';	// content will hold the html 
		foreach($blogs as $blog)
		{
			$blog_tags = DB::getInstance()->get('blog_tags', array('blog_id', '=', $blog->id));
            $blog_tags = $blog_tags->results();
			$date=strtotime($blog->created_on);
			$writer = DB::getInstance()->get('users', array('id', '=', $blog->users_id))->first()->username;
			$json['content'] = $json['content'].
			"<div class='fadedfx'>
            	<div class='col s12 m12'>
	                <div class='card horizontal white'>
	                    <div class='card-content'> <span class='card-title'>".date('M d Y', $date)."</span>
	                        <div class='row margin-eliminate'>
	                            <div class='col s12'>
	                                <h5><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h5>
	                                <h6>".ucfirst($blog->description)."</h6>
	                            </div>
	                        </div>
	                        <div class='row margin-eliminate'>  
	                            <div class='valign-wrapper'>
	                                <div class='col l6 s4'>
	                                    <div class='valign-wrapper'>
	                                        <i class='material-icons hide-on-small-only' style='color:grey'>book</i>
	                                        <p class='grey-text'>".$blog->blog_minutes_read." min read</p>
	                                    </div>
	                                </div>
	                                <div class='col l6 s8'>
	                                    <div class='chip'>
	                                        <img src='Includes/images/og_image.jpg' alt='Contact Person'>{$writer}
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class='row'>
	                            <div class='measure-count' data-attribute='{$blog->id}'>
	                                <div class='col s2 l1 m1'>
	                                    <i class='fa fa-eye fa-2x' aria-hidden='true' style='color:grey'></i>
	                                </div>
	                                <div class='col s1 l1 m1'>
	                                    {$blog->views}
	                                </div>
	                                <div class='col s2 l1 m1 offset-m1 offset-s1 offset-l1'>
	                                    <i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color:grey'></i>
	                                </div>
	                                <div class='col s1 l1 m1'>
	                                    {$blog->likes}
	                                </div>
	                                <div class='col s2 l1 m1 offset-m1 offset-s1 offset-l1'>
	                                    <i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color:grey'></i>
	                                </div>
	                                <div class='col s1 l1 m1'>
	                                    {$blog->dislikes}
	                                </div>
	                            </div>
	                        </div>
	                        <div class='row'>
	                            <div class='col s12'>";
	                            foreach($blog_tags as $blog_tag)
	                            {
	                                $json['content'] = $json['content']."<div class='chip'>".$blog_tag->tags."</div>";
	                            }
	                            $json['content'] = $json['content'].
	                            "</div>
	                        </div>
	                    </div>
	                </div>
	            </div>
        	</div>";
		}
	}		
	else
	{
		$json['content'] = 'Sorry, no blogs';
	}
	// header("Content-Type: application/json", true);
	echo json_encode($json);
	
}
else
{
	Redirect::to('index.php');
}

?>
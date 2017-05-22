<?php

Include'Core/init.php';

if(Input::exists('get'))	// check if there's a query string or not
{	
	if(Input::get('tag'))	// check if there's a query tag with name 'tag'
	{
		$tag = Input::get('tag');
		$blogs_based_on_tag = DB::getInstance()->get('blog_tags', array('tags', '=', $tag));
		$blog_count = $blogs_based_on_tag->count();	// getting the total count of blogs based on the queried tag
		$blogs_based_on_tag = $blogs_based_on_tag->results();		// getting the id of blogs who have queried tag in them
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
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	<title>Results for tag <?php echo $tag; ?></title>

	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
    <style type="text/css">
    	body
    	{
    		background-color: #fafafa;
    	}
        .brand-logo
        {
            display: inline-block;
            height: 100%;
        }
        .brand-logo > img 
        {
	            vertical-align: middle
        }
        input[type="search"]
        {
            height: 64px !important; /* or height of nav */
        }
        nav ul .dropdown-button
        {
            width: 200px !important;
        }
        .card .card-content
        {
            padding-bottom: 0px;
            padding-top: 10px;
        }
        div .margin-eliminate
        {
            margin-bottom: 10px;
        }
        p .margin-eliminate
        {
            margin: 0px;
        }       
		ul
		{
			overflow: hidden;
		} 
		.align-inline
		{
			display: inline-block;
			margin-right: 10px;
		}
        .num-result
        {
        	margin: 2rem 0 2rem 0 !important;
        }
/*        footer
        {
        	position: absolute;
        	bottom: 0;
        	right: 0;
        	left: 0;
        }*/
        .chip
        {
        	margin: 0 20px 0 0;
        }
	</style>
</head>
<body>
	<?php 

		include'header.php';

	?>

	<script type="text/javascript">
    	document.getElementById('nav-bar').classList.remove('transparent');
    </script>
    <div class="container">
    	<div class="section">
    		<?php
    			if($blog_count == 0)
    			{
    				$all_distinct_tags = DB::getInstance()->distinctRecords('blog_tags', array('tags'));
    				$all_distinct_tags = $all_distinct_tags->results();
    				echo
    				"<ul class='hide-on-med-and-down'>
    					<li class='align-inline'>
    						<h5 class='red-text num-result'>Sorry, No results found for <em> {$tag} </em></h5>
						</li>
						<li class='align-inline'>
							<div class='chip'><h6 class='div'><a href='histogram.php'>See popular tags with visual aids</a></h6></div>
						</li>
						<li>
							<h5>Other tags you can follow</h5>
						</li>
    				</ul>
    				<ul class='center-align hide-on-large-only'>
    					<li>
    						<h5 class='red-text num-result'>Sorry, No results found for <em> {$tag} </em></h5>
						</li>
						<li>
							<div class='chip'><h6 class='div'><a href='histogram.php'>See popular tags with visual aids</a></h6></div>
						</li>
						<li>
							<h5>Other tags you can follow</h5>
						</li>
    				</ul>
	    				<div class='card-panel'>
	    					<div class='left-align'>";
								foreach($all_distinct_tags as $all_distinct_tag)
								{
									$tag = $all_distinct_tag->tags;
									echo 
									"<a class='chip' href='/view_blogs_tag.php?tag={$tag}'>{$tag}</a>";	// iterating through all distinct tags
								}
							echo	
							"</div>
						</div>";
    			}
    			else
    			{
    				echo
    				"<ul class='hide-on-med-and-down'>
    					<li>
    						<h5 class='green-text num-result'>{$blog_count} Result(s) found for <em> {$tag} </em></h5>
						</li>
						<li>
							<div class='chip'><h6 class='div'><a class='black-text' href='histogram.php'> See popular tags with visual aids</a></h6></div>
						</li>
    				</ul>
    				<ul class='center-align hide-on-large-only'>
    					<li>
    						<h5 class='green-text num-result'>{$blog_count} Result(s) found for <em> {$tag} </em></h5>
						</li>
						<li>
							<div class='chip'><h6 class='div'><a class='black-text' href='histogram.php'> See popular tags with visual aids</a></h6></div>
						</li>
    				</ul>";
    				foreach($blogs_based_on_tag as $blogs_based_on_tag)
                	{
                		$blog = DB::getInstance()->get('blogs', array('id', '=', $blogs_based_on_tag->blog_id));
						$blog = $blog->first();
						$sortBlogsArray[$blog->id] = strtotime($blog->created_on);
                	}
                	$BlogsSortedViaDate = arsort($sortBlogsArray);
    				echo 
    				"<div class='content'>";
	    				foreach($sortBlogsArray as $blog_id => $date)
	    				{
	    					$blog = DB::getInstance()->get('blogs', array('id', '=', $blog_id));		// fetch blogs from table 'blogs' with blog_id of $blog_based_on_tag as parameter
							$blog = $blog->first();		// getting the first blog 
		                    $blog_tags = DB::getInstance()->get('blog_tags', array('blog_id', '=', $blog->id));	// getting all blog_tags associated with the fetched blog
		                    $blog_tags = $blog_tags->results();
		                    $date=strtotime($blog->created_on); // changing the format of timestamp fetched from the database, converting it to milliseconds
		                    $writer = DB::getInstance()->get('users', array('id', '=', $blog->users_id))->first();

		                    echo
	                        "<div class='col s12 m12'>
	                            <div class='card horizontal white'>
	                                <div class='card-content'> <span class='card-title'>".date('M d Y', $date)."</span>
	                                    <div class='row margin-eliminate'>
	                                        <div class='col s12'>
	                                            <h5><a class='views' data-attribute='{$blog->views}' href='/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h5>
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
	                                                <a class='chip' href='/user_profile.php?user={$writer->username}'>
                                                    	<img src='".Config::get('url/upload_dir')."/{$writer->image_url}' alt='Contact Person'>{$writer->username}
                                                	</a>
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
	                                            $tag = $blog_tag->tags;
                                            	echo "<a class='chip' href='/view_blogs_tag.php?tag={$tag}'>{$tag}</a>";
	                                        }
	                                        echo
	                                        "</div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>";
	    				}
    				echo
    				"</div>";
    			}
    		?>
    	</div>
    </div>

    <footer class="page-footer blue lighten-1">
        <div class="container">
            <div class="row">
                <div class="col l6 s12">
                    <h5 class="white-text">TechSense</h5>
                    <p class="grey-text text-lighten-4">Publish your passions your way. Whether you'd like to share your knowledge, experiences or the latest tech news, create a unique and beautiful blog for free.</p>
                </div>
                <div class="col l4 offset-l2 s12">
                    <h5 class="white-text">View Our Other Projects</h5>
                    <ul>
                        <li><a class="grey-text text-lighten-3" href="http://www.silive.in" target="blank">silive.in</a></li>
                        <li><a class="grey-text text-lighten-3" href="#!" target="blank">Blood Donation Campaign 2017</a></li>
                        <li><a class="grey-text text-lighten-3" href="#!" target="blank">Table Tennis Tournament</a></li>
                    </ul>
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
    <script src="https://use.fontawesome.com/819d78ad52.js"></script>
    <script type="text/javascript" src="Includes/js/materialize.min.js"></script>
    <script type="text/javascript">
    	$(document).ready(function() {

            $(".dropdown-button").dropdown({hover: false});   // activate dropdown in the nav-bar

    		$(".button-collapse").sideNav();
    		
    	});

    </script>
	
</body>
</html>
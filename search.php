<?php

require'Core/init.php';

if(Input::exists('post'))
{
	$error = true;	// initialising error to true, showing content to user according to the error's value
	$Validate = new Validate;
	$Validate->check($_POST, array(
		"searchParameter" => array(
			"required" => true       // user must ask by writing something inside search field
			)
		));

	if($Validate->passed())
	{
		$error = false;		
		$searchResults = DB::getInstance()->search(Input::get('searchParameter'));
		$searchResultsCount = $searchResults->count();	// getting count of blog_id that we got according to the query user asked
		$searchResults = $searchResults->results();		// storing result in a searchResults
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
		<title>Search Results for <?php Input::get('searchParameter'); ?></title>
		<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
    	<style type="text/css">
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
					if($error == true)	// if error's true, show user with an error message
					{
						echo
						"
						<div class='section center-align'>
							<h5 class='red-text'>Enter a value in the search field</h5>
							<h6>You can search blogs by entering a username, tag, title or a name</h6>
						</div>";
					}
					else     // if error's false, show the relevant result to the user
					{
						echo
		                "<div class='section'>
		                	<h5>Search results for <em>".Input::get('searchParameter')."</em></h5>
	                	</div>
		                <div class='content'>";
		                if($searchResultsCount == 0)
		                {
		                	echo
		                	"<h6>Oops!! Seems like there isn't any blog associated with what you asked</h6>";
		                }
		                else
		                {
		                	echo
		                	"<div class='row'>
		                		<div class='col s12'>
		                			<h6 class='green-text'>{$searchResultsCount} Results found</h6>
	                			</div>   
	                		</div>";
		                	$sortBlogsArray = [];
		                	foreach($searchResults as $searchResult)
		                	{
		                		$blog = DB::getInstance()->get('blogs', array('id', '=', $searchResult->blog_id));
								$blog = $blog->first();
								$sortBlogsArray[$blog->id] = strtotime($blog->created_on);
		                	}
		                	$BlogsSortedViaDate = arsort($sortBlogsArray);
							foreach($sortBlogsArray as $blog_id => $date)
							{
								$blog = DB::getInstance()->get('blogs', array('id', '=', $blog_id));
								$blog = $blog->first();
			                    $blog_tags = DB::getInstance()->get('blog_tags', array('blog_id', '=', $blog->id));
			                    $blog_tags = $blog_tags->results();
			                    $date=strtotime($blog->created_on); // changing the format of timestamp fetched from the database, converting it to milliseconds
			                    $writer = DB::getInstance()->get('users', array('id', '=', $blog->users_id))->first()->username;
			                    
			                    echo
		                        "<div class='col s12 m12'>
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
		                                            echo "<div class='chip'>".$blog_tag->tags."</div>";
		                                        }
		                                        echo
		                                        "</div>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>";
							}	                
		                }
		                echo
		                "</div>";	// end of <div class='content'>
					}

				?>
			</div>
		</div>

	<script src="Includes/js/jquery.min.js"></script>
    <script src="https://use.fontawesome.com/819d78ad52.js"></script>
    <script type="text/javascript" src="Includes/js/materialize.min.js"></script>
	</body>
</html>
<?php

require_once'Core/init.php';

$user = new User;

if(!$user->isLoggedIn())
	Redirect::to('index.php');

?>

<!DOCTYPE html>
<html>
<head>
	<title>
		Update Profile
	</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="keywords" content="blog, technology, code, program, alorithms"/>
	<meta name="description" content="We emphaisze on solving problems">
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
	<style>
	input[type="search"]
    {
        height: 64px !important; /* or height of nav */
    }
	</style>
</head>
<body>
	<?php 
		include'header.php'; 
	?>
	<div class="container">
		<div class="section">
			<form action="" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col s4">
						<img class="img-responsive materialboxed" src="<?php echo Config::get('url/upload_dir').'/'.$user->data()->image_url ?>" width="100%">
						<div class="row">
							<div class="col s12">
								<div class="file-field input-field">
							    	<div class="btn blue">
							        	<span>Upload</span>
							        	<input type="file" name="profile_pic" id="profile_pic">
							      	</div>
							      	<div class="file-path-wrapper">
							        	<input class="file-path validate" type="text" placeholder="Upload image" >
							      	</div>
							    </div>
							    <div class="row">
							    	<div class="col s4 center-align" >
							    		<h6>Blogs</h6>32
							    	</div>
							    	<div class="col s4 center-align">
							    		<h6>Followers</h6>43
							    	</div>
							    	<div class="col s4 center-align">
							    		<h6>Following</h6>21
							    	</div>
							    </div>
							</div>
						</div>
					</div>
					<div class="col s6 offset-s2">
						<div class="row">
							<div class="input-field col s12">
								<input type="text" name="name" id="name" value="<?php echo $user->data()->name; ?>">
								<label for="name">Name</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input disabled type="text" name="username" id="username" value="<?php echo $user->data()->username ?>">
								<label for="username">Username</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input type="text" name="github_url" id="github_url" value="<?php echo $user->data()->github_url ?>">
								<label for="github_url">Github Url</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input type="text" name="facebook_url" id="facebook_url" value="<?php echo $user->data()->facebook_url ?>">
								<label for="facebook_url">Facebook Url</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input type="text" name="twitter_url" id="twitter_url" value="<?php echo $user->data()->twitter_url ?>">
								<label for="twitter_url">Twitter Url</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col s12">
						<label for="description">Tell us about youself</label>
						<textarea class="materialize-textarea" id="description" name="description"><?php echo $user->data()->description ?></textarea>
					</div>
				</div>
				<div class="row">
					<button type="button" class="btn waves-effect waves-light blue" name="update" id="update">Update</button>
				</div>
			</form>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="section">
				<div class="row">
					<div class="col s3">
						<h5>Blogs Written</h5>
					</div>
					<div class="col s1">
						<a class="btn-floating btn-small waves-effect waves-light blue toggle-user-blogs"><i class="material-icons">add</i></a>
					</div>
				</div>
			</div>
			<div class="user-blogs">
				<?php
	                $blogs = DB::getInstance()->sortUser('blogs', array('created_on', 'DESC'), array('users_id', '=', 1));
	                $num_blogs = $blogs->count();
	                $num_pages = ceil($num_blogs/3);
	                $blogs = $blogs->results();
	                $blogs = array_splice($blogs, 0, 3);
	                if($num_blogs)  // show blogs if there are any, otherwise show message 'No blogs'
	                {   
	                    echo "<div class='content' id='content'>";
                        foreach($blogs as $blog)
                        {
                            $date=strtotime($blog->created_on); // changing the format of timestamp fetched from the database, converting it to milliseconds
                            echo 
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
	                    echo 
	                        "</div>
	                        <div class='section center-align'>
	                            <ul class='pagination'>";
	                                    for($x = 1; $x <= $num_pages; $x++)
	                                    {
	                                        if($x == 1)
	                                        {
	                                            echo "<li class='waves-effect pagination active'><a href='#' class='blog-pagination'>".$x."</a></li>";
	                                        }
	                                        else
	                                        {
	                                            echo "<li class='waves-effect pagination'><a href='#' class='blog-pagination'>".$x."</a></li>";
	                                        }
	                                    }   
	                            echo
	                            "</ul>
	                        </div>";
	                }
	                else
	                {
	                    echo "<div class='section center-align'>No blogs yet. <a href='write_blog.php'>Write the very first blog.</a></div>";
	                }
	            ?>
            </div>
		</div>
	</div>
		
	<script src="Includes/js/jquery.min.js"></script>
	<script src="https://use.fontawesome.com/17e854d5bf.js"></script>
	<script type="text/javascript" src="Includes/js/materialize.min.js"></script>
	<script type="text/javascript">
		$("#update").off('click');
		$('.nav-bar').removeClass('transparent');
		$(document).ready(function(){
				$('#update').on('click', function(){
					var data = new FormData();
				    var file_data = $('input[type="file"]')[0].files;
				    if(file_data.length)
				    {
				    	data.append("profile_pic", file_data[0]);
				    }
				    var input_data = $('form').serializeArray();
				    $.each(input_data,function(key, input){
				        data.append(input.name, input.value);
				    });
				    $.ajax({
				        url: 'authors_info_backend.php',
				        data: data,
				        contentType: false,
				        processData: false,		// not processing the data
				        type: 'POST',
				        success: function(response)
				        {
				        	var response = JSON.parse(response);
				        	console.log(response);
				        	$('#_token').val(response._token);
				        	if(response.error_status === true)
				        	{
				        		Materialize.toast(response.error, 5000, "red");
				        		$('#description').focus();
				        	}
				        	else
				        	{
				        		Materialize.toast("Your Information has been added successfully", 5000, "green");
				        	}
				        }
				    });
				});

				$('.toggle-user-blogs').click(function(){
					$('.user-blogs').slideToggle('slow');
				});

				$('.blog-pagination').click(function(e){
                e.preventDefault();
                $('.active').removeClass('active');
                $(this).parent().addClass('active');
                var page_id = $(this).html();
                var _token = $('#_token').val();

                $.ajax({
                    type: 'POST',
                    url: 'pagination_backend.php',
                    data: {page_id: page_id, _token: _token, author: true},
                    cache: false,
                    success: function(response)
                    {
                        var response = JSON.parse(response);
                        console.log(response);
                        console.log(response._token);
                        $('#_token').val(response._token);
                        $('.content').html(response.content);
                    }
                });
            });

		});
	</script>

</body>
</html>
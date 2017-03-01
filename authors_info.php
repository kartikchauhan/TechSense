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
						<textarea class="materialize-textarea" id="description" name="description"><?php echo $user->data()->user_description ?></textarea>
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
	                $blogs = DB::getInstance()->sortUser('blogs', array('created_on', 'DESC'), array('users_id', '=', 27));
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
                                "<div class='row blog'>
                                    <div class='col s2'>
                                        <blockquote>".
                                            date('M', $date)."<br>".
                                            date('Y d', $date).
                                        "</blockquote>
                                    </div>
                                    <div class='col s10'>
                                    	<div class='row'>
	                                    	<div class='col s4'>
	                                        	<h5><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h5>
	                                        </div>
	                                        	<a href='#' class='blue-text delete-blog' data-attribute='{$blog->id}'><i class='material-icons right'>delete</i></a> <a href='update_blog.php?blog_id={$blog->id}' class='blue-text edit-blog' data-attribute='{$blog->id}'><i class='material-icons right'>mode_edit</i></a> 
                                        </div>
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
                                        <div class='section'>
                                        	<div class='divider'></div>
                                    	</div>
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

			function pagination(object)
			{	
                $('.pagination').find('.active').removeClass('active');
                $(object).parent().addClass('active');
                var page_id = $(object).html();
                console.log("page_id  = " + page_id);
                var _token = $('#_token').val();

                $.ajax({
                    type: 'POST',
                    url: 'pagination_backend.php',
                    data: {page_id: page_id, _token: _token, author: true},
                    cache: false,
                    async: false,
                    success: function(response)
                    {
                        var response = JSON.parse(response);
                        console.log(response);
                        console.log("count records is " + response.count);
                        $('#_token').val(response._token);
                        $('.content').html(response.content);
                    }
                });

			}

			function alterPagination()
			{
				var object = $('li.active').find('.blog-pagination');	//getting the child of active class of pagination
				pagination(object);		// fetching the blogs again whenever a blog gets deleted in order to maintain pagination
				var counter = 0;
				$('.content').find('.blog').each(function(){
					++counter;
				});
				if(counter < 3)		// 3 is the value of maximum that can be shown in one page
				{
					if($('li.active').next().length)
						$('li.active').next().remove();
				}
				if(counter == 0)
				{
					var current_page = parseInt($('li.active').find('.blog-pagination').html());	// getting which page is active right now
					if(current_page > 1)	// if current_page > 1 then proceed
					{
						var obj = $('.pagination').find('li.active');		// getting active class
						pagination($(obj).prev().find('.blog-pagination'));	// fetching the blogs again, because here switching from one page to another page is needed to be done
						$(obj).remove();	// removing the current page since there's no blog in it
					}
					else if(current_page == 1)
					{
						$('.pagination').remove();
					}
				}
			}

			$('.blog-pagination').click(function(e){
            	e.preventDefault();
            	pagination(this);

            });

			$('.content').on('click', '.delete-blog', function(e){
				e.preventDefault();
				var user_response = confirm("Are you sure you want to delete this blog?");
				if(user_response == true)
				{
					var blog_id = $(this).attr('data-attribute');
					var _token = $('#_token').val();
					var object = $(this);
					$.ajax({
						type: 'POST',
						url: 'delete_blog.php',
						data: {blog_id: blog_id, _token: _token},
						cache: false,
						success: function(response)
						{
							var response = JSON.parse(response);
							// console.log(response);
							$('#_token').val(response._token);
							if(response.error_status == true)
							{
								Materialize.toast(response.error, 5000, "red");
							}
							else
							{
								Materialize.toast("The blog has been deleted successfully", 5000, "green");
								$(object).parent().parent().parent().remove();	// to remove the blog
								alterPagination();
							}
						}
					});
				}
				else
				{
					return false;
				}
			});

		});
	</script>

</body>
</html>

<?php

require_once'Core/init.php';

$user = new User;

if(!$user->isLoggedIn())
	Redirect::to('index.php');

?>

<!DOCTYPE html>
<html>
<head>
	<link rel="preload" as="script" href="Includes/js/materialize.min.js">
	<link rel="preload" as="script" href="https://use.fontawesome.com/819d78ad52.js">
    <link rel="preload" as="script" href="Includes/js/jquery.min.js">
    <link rel="preload" as="style" href="http://fonts.googleapis.com/icon?family=Material+Icons">
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
	.brand-logo
    {
        display: inline-block;
        height: 100%;
    }
    .brand-logo > img {
        vertical-align: middle
    }
    .col.s12 > .btn
    {
    	width: 100%;
    }
    nav ul .dropdown-button
    {
        width: 200px !important;
    }
    .authors_profile_pic
    {
    	max-height: 300px !important;
    }
    .pagination li.active
    {
        background-color: #42A5F5;
    }
    blockquote 
    {
        border-left: 5px solid #42A5F5;
    }
    .token_container
    {
    	display: none;
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
			<form action="" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col l4 s12 m6 offset-m3">
						<img class="responsive-img materialboxed authors_profile_pic" src="<?php echo Config::get('url/upload_dir').'/'.$user->data()->image_url ?>" width="100%">
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
							</div>
						</div>
					</div>
					<div class="col l6 offset-l2 s12">
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
								<input type="text" name="github_username" id="github_username" value="<?php echo $user->data()->github_username; ?>">
								<label for="github_username">Github Username</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input type="text" name="facebook_username" id="facebook_username" value="<?php echo $user->data()->facebook_username ?>">
								<label for="facebook_username">Facebook Username</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input type="text" name="twitter_username" id="twitter_username" value="<?php echo $user->data()->twitter_username ?>">
								<label for="twitter_username">Twitter Username</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input type="text" name="google_profileId" id="google_profileId" value="<?php echo $user->data()->google_profileId ?>">
								<label for="google_profileId">Google ProfileId</label>
							</div>
						</div>
						<div class="row token_container">
							<div class="input-field col s12">
								<input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
							</div>
						</div>
						<div class="row hide-on-large-only">
							<div class="input-field col s12">
								<label for="description">Tell us about youself</label>
								<textarea class="materialize-textarea" id="description" name="description"><?php echo $user->data()->user_description ?></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="row hide-on-med-and-down">
					<div class="col s12">
						<label for="description">Tell us about youself</label>
						<textarea class="materialize-textarea" id="description" name="description"><?php echo $user->data()->user_description ?></textarea>
					</div>
				</div>
				<div class="row">
					<div class="col s12 l3 m4">
						<button type="button" class="btn waves-effect waves-light blue" name="update" id="update">Update</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="section">
				<div class="row">
					<div class="col l3 s6 m3 offset-s2">
						<h5>Blogs Written</h5>
					</div>
					<div class="col l1 s2 m1">
						<a class="btn-floating btn-small waves-effect waves-light blue toggle-user-blogs"><i class="material-icons">add</i></a>
					</div>
				</div>
			</div>
			<div class="user-blogs">
				<?php
	                $blogs = DB::getInstance()->sortUser('blogs', array('created_on', 'DESC'), array('users_id', '=', $user->data()->id));
	                $num_blogs = $blogs->count();
	                $num_pages = ceil($num_blogs/5);
	                $blogs = $blogs->results();
	                $blogs = array_splice($blogs, 0, 5);
	                if($num_blogs)  // show blogs if there are any, otherwise show message 'No blogs'
	                {   
	                    echo "<div class='content' id='content'>";
                        foreach($blogs as $blog)
                        {
                        	$blog_tags = DB::getInstance()->get('blog_tags', array('blog_id', '=', $blog->id));
                            $blog_tags = $blog_tags->results();
                            $date=strtotime($blog->created_on); // changing the format of timestamp fetched from the database, converting it to milliseconds
                            echo 
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
                                            echo "<div class='chip'>".$blog_tag->tags."</div>";
                                        }
                                        echo
                                        "<div class='section'>
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

		$("#update").off('click');
		// $('.nav-bar').removeClass('transparent');
		if(typeof(Storage) !== "undefined")
        {
            console.log('not undefined');
            if(sessionStorage.getItem("flashMessage") !== null)
            {
                Materialize.toast(sessionStorage.getItem("flashMessage"), 5000 ,'green');
                sessionStorage.removeItem('flashMessage');
            }
        }

		$(document).ready(function(){
			
			$(".button-collapse").sideNav();

			$("#profile_pic").change(readURL);	

			$('#update').on('click', function(){
				console.log('update button clicked');
				if(!validateData())
				{
					return false;
				}
				var data = new FormData();
			    var file_data = $('input[type="file"]')[0].files;
			    if(file_data.length)
			    {
			    	data.append("profile_pic", file_data[0]);
			    }
			    var input_data = $('form').serializeArray();
			    $.each(input_data, function(key, input){
			        data.append(input.name, input.value);
			    });
			    $.ajax({
			        url: 'authors_info_backend.php',
			        data: data,
			        type: 'POST',
			        contentType: false,
			        dataType: "json",
			        processData: false,		// not processing the data
			        success: function(response)
			        {
			        	// var response = JSON.parse(response);
			        	console.log(response._token);
			        	if(response.error_status === true)
			        	{
			        		console.log(response._token);
			        		if(response.error_code != 1)
			        		{
			        			$('#_token').val(response._token);
			        		}
			        		Materialize.toast(response.error, 5000, "red");
			        		$('#description').focus();
			        	}
			        	else
			        	{
			        		$('#_token').val(response._token);
			        		Materialize.toast("Your Information has been updated successfully", 5000, "green");
			        	}
			        }
			    });
			});
	
			$('.toggle-user-blogs').click(function(){
				$('.user-blogs').slideToggle('slow');
			});

			function readURL()
			{
				$('.authors_profile_pic').attr('src', '').hide();	// remove the current src of image
				if (this.files && this.files[0]) {	// checking if any present in the files array
					var reader = new FileReader();	// instantiating fileReader
					$(reader).load(function(e) {	// when loading finishes, set the src to the new image's url
						$('.authors_profile_pic').attr('src', e.target.result)
					});
					reader.readAsDataURL(this.files[0]);	// generating thumbnail
				}
			}

			$('.authors_profile_pic').load(function(e) {
				$(this).css('height', '300px').show();	// setti
			});

			function validateData()
			{
				var name = $('#name').val();
				var description = $('#description').val();
				if(name === '')
				{
					Materialize.toast('Name is required', 5000, 'red');
					return false;
				}
				if(description === '')
				{
					Materialize.toast('Description is required', 5000, 'red');
					return false;
				}
				return true;
			}

			function pagination(object)
			{	
                $('.pagination').find('.active').removeClass('active');
                $(object).parent().addClass('active');
                var page_id = $(object).html();
                console.log("page_id  = " + page_id);
                // var _token = $('#_token').val();

                $.ajax({
                    type: 'POST',
                    url: 'pagination_backend.php',
                    data: {page_id: page_id, author: true},
                    dataType: "json",
                    cache: false,
                    async: false,
                    success: function(response)
                    {
                        // var response = JSON.parse(response);
                        console.log(response);
                        if(response.error_status)
                        {
                            Materialize.toast(response.error, 5000, 'red');
                        }
                        else
                        {
	                        // console.log("count records is " + response.count);
	                        // $('#_token').val(response._token);
	                        $('.content').html(response.content);
                        	
                        }
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
				if(counter < 5)		// 5 is the value of maximum that can be shown in one page
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
					// var _token = $('#_token').val();
					var object = $(this);
					$.ajax({
						type: 'POST',
						url: 'delete_blog.php',
						data: {blog_id: blog_id},
						dataType: "json",
						cache: false,
						success: function(response)
						{
							// var response = JSON.parse(response);
							// console.log(response);
							// $('#_token').val(response._token);
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
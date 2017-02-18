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
	<?php include'header.html'; ?>
	<div class="container">
		<div class="section">
			<form action="" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col s4">
						<img class="img-responsive materialboxed" src="<?php echo Config::get('url/upload_dir').'/'.$user->data()->image_url ?>" width="100%">
						<div class="row">
							<div class="col s12">
								<div class="file-field input-field">
							    	<div class="btn">
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
							<label for="name">Name</label>
							<input type="text" name="name" id="name" value="<?php echo $user->data()->name; ?>">
							<label for="username">Username</label>
							<input disabled type="text" name="username" id="username" value="<?php echo $user->data()->username ?>">
							<label for="github_url">Github URL</label>
							<input type="text" name="github_url" id="github_url" value="<?php echo $user->data()->github_url ?>">
							<label for="facebook_url">Facebook URL</label>
							<input type="text" name="facebook_url" id="facebook_url" value="<?php echo $user->data()->facebook_url ?>">
							<label for="twitter_url">Twitter URL</label>
							<input type="text" name="twitter_url" id="twitter_url" value="<?php echo $user->data()->twitter_url ?>">
							<input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
					</div>
				</div>
				<div class="row">
					<div class="col s12">
						<label for="description">Tell us about youself</label>
						<textarea class="materialize-textarea" id="description" name="description"><?php echo $user->data()->description ?></textarea>
					</div>
				</div>
				<div class="row">
					<button type="button" class="btn waves-effect waves-light" name="update" id="update">Update</button>
				</div>
			</form>
		</div>
	</div>
		
	<script src="Includes/js/jquery.min.js"></script>
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
		});
	</script>

</body>
</html>
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
		Author's Information
	</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="keywords" content="blog, technology, code, program, alorithms"/>
	<meta name="description" content="We emphaisze on solving problems">
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
</head>
<body>
	<form id="add_info" action="" method="post" enctype="multipart/form-data">
		<input type="file" name="profile_pic" id="profile_pic">
		<br>
		<label for="description">Write about yourself</label>
		<textarea name="description" id="description" placeholder="Write about your interests and designation" rows="4"></textarea>
		<br>
		<label for="github_url">Github Url</label>
		<input type="text" name="github_url" id="github_url">
		<br>
		<label for="twitter_url">Google Url</label>
		<input type="text" name="twitter_url" id="twitter_url">
		<br>
		<label for="facebook_url">Facebook Url</label>
		<input type="text" name="facebook_url" id="facebook_url">
		<br>
		<input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
		<input type="submit" value="submit" id="submit">
	</form>
	<script src="Includes/js/jquery.min.js"></script>
	<script type="text/javascript" src="Includes/js/materialize.min.js"></script>
	<script type="text/javascript">
		$("#submit").off('click');
		$(document).ready(function(){
				$('#submit').on('click', function(e){
					e.preventDefault();
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
				        	if(response.error_status === true)
				        	{
				        		Materialize.toast(response.error, 5000, "red");
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
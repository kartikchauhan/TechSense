<?php

require_once'Core/init.php';

?>

<!DOCTYPE html>
<html>
<head>
	<title>
		Author's Information
	</title>
</head>
<body>
	<form id="add_info" action="authors_info_backend.php" method="post" enctype="multipart/form-data">
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
				        processData: false,
				        type: 'POST',
				        success: function(data){
				            console.log(data);
				        }
				    });
				});
		});
	</script>

</body>
</html>
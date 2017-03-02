<?php

require_once'Core/init.php';

$user = new User;

// if(!$user->isLoggedIn())
// {
// 	Redirect::to('index.php');
// }

?>

<!DOCTYPE html>
<html>
<head>
	<title>
		Change Password
	</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="keywords" content="blog, technology, code, program, alorithms"/>
    <meta name="description" content="We emphaisze on solving problems">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
    <style type="text/css">
    	body
		{
			position: absolute;
			height: 100%;
			width: 100%;
			background-color: #eee;
		}
		#forgot-password-form
		{
			position:relative;
		  	top:50%;
		    left:50%;
			-ms-transform: translateX(-50%) translateY(-50%);
			-webkit-transform: translate(-50%,-50%);
			transform: translate(-50%,-50%);
		}
		.error
		{
			display: none;
		}
    </style>
</head>

<body>
	<div id="forgot-password-form">
		<h5 class="center-align condensed light">Send Password Reset Mail</h5>
		<div class="row">
			<div class="col s4 offset-s4">
				<ul class="collection center-align z-depth-1 error">
					<li class="collection-item red-text"></li>
				</ul>
				<div class="card">
					<div class="card-content">
						<div class="row">
							<form class="col s12" action="" method="post">
								<div class="row">
									<div class="input-field col s12">
										<i class="material-icons prefix">mail</i>
										<input type="email" name="email" id="email" />
										<label for="email">Email</label>
									</div>
									<div class="input-field col s12">
										<input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
									</div>
									<input type="submit" class="btn waves-effect waves-light col s6 offset-s3" value="Send Mail" id="send_mail">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="Includes/js/jquery.min.js"></script>
    <script type="text/javascript" src="Includes/js/materialize.min.js"></script>
    <script>
    	$('#send_mail').click('off');
    	$(document).ready(function(){
    		$('body').on('click', '#send_mail', function(e){
    			e.preventDefault();
    			var _token = $('#_token').val();
    			var email = $('#email').val();
    			$.ajax({
    				type: "POST",
    				url: 'forgot_password_backend.php',
    				data: {email: email, _token: _token},
    				cache: false,
    				success: function(response)
    				{
    					var response = JSON.parse(response);
    					$('#_token').val(response._token);
    					if(response.error_status === true)
    					{
    						Materialize.toast(response.error, 5000, "red");
    					}
    					else
    					{
    						$(window.location).attr('href', 'login.php');
    						// Materialize.toast("Mail sent successfully", 5000, "green");
    					}
    				}

    			});
    		});
    	});
    </script>
</body>
</html>



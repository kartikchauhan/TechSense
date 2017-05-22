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
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
			margin-left:auto;
			margin-right:auto;
			/*position:relative;
		  	top:50%;*/
		    /*left:50%;
			-ms-transform: translateX(-50%) translateY(-50%);
			-webkit-transform: translate(-50%,-50%);
			transform: translate(-50%,-50%);*/
		}
		.error
		{
			display: none;
		}
		.loader-container
		{
			display: none;
		}
		.loader
		{
		    border: 3px solid #f3f3f3; /* Light grey */
		    border-top: 3px solid #009688; /* Blue */
		    border-radius: 50%;
		    width: 30px;
		    height: 30px;
		    animation: spin 2s linear infinite;
		}
		@keyframes spin {
		    0% { transform: rotate(0deg); }
		    100% { transform: rotate(360deg); }
		}
    </style>
</head>

<body>
	<div class="container">
		<div id="forgot-password-form">
			<h5 class="center-align condensed light">Send Password Reset Mail</h5>
			<div class="row">
				<div class="col s12 l4 m6 offset-m3 offset-l4">
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
									<div class='row loader-container'>
										<div class='col s2 offset-s5'>
											<div class='loader'></div>
										</div>
									</div>
								</form>
							</div>
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
    		$('form').on('click', '#send_mail', function(e){
    			e.preventDefault();
    			if(!validateData())
    			{
    				return false;
    			}
    			$('.loader-container').show();
    			var _token = $('#_token').val();
    			var email = $('#email').val();
    			$.ajax({
    				type: "POST",
    				url: 'forgot_password_backend.php',
    				data: {email: email, _token: _token},
    				dataType: "json",
    				cache: false,
    				success: function(response)
    				{
    					// var response = JSON.parse(response);
						$('.loader-container').hide();
    					if(response.error_status === true)
    					{
    						if(response.error_code != 1)
			        		{
			        			$('#_token').val(response._token);
			        		}
    						Materialize.toast(response.error, 5000, "red");
    					}
    					else
    					{
    						$('#_token').val(response._token);
    						if(typeof(Storage) !== 'undefined')
	                        {
	                            sessionStorage.setItem('flashMessage', 'The mail has been sent successfully');
	                        }
    						$(window.location).attr('href', 'login.php');
    						// Materialize.toast("Mail sent successfully", 5000, "green");
    					}
    				}

    			});
    		});

    		function validateData()
    		{
    			var email = $('#email').val();
    			if(email === '')
    			{
    				Materialize.toast('email is required', 5000, 'red');
    				return false;
    			}
    			return true;
    		}

    	});
    </script>
</body>
</html>



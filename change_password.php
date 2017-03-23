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
		#change-password-form
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
	<div id="change-password-form">
		<h5 class="center-align condensed light">Change Password</h5>
		<div class="row">
			<div class="col s12 l4 offset-l4">
				<ul class="collection center-align z-depth-1 error">
					<li class="collection-item red-text"></li>
				</ul>
				<div class="card">
					<div class="card-content">
						<div class="row">
							<form class="col s12" action="" method="post">
								<div class="row">
									<div class="input-field col s12">
										<i class="material-icons prefix">lock</i>
										<input type="password" name="current_password" id="current_password" />
										<label for="current_password">Current Password</label>
									</div>
									<div class="input-field col s12">
										<i class="material-icons prefix">lock</i>
										<input type="password" name="password" id="password" />
										<label for="password">New Password</label>
									</div>
									<div class="input-field col s12">
										<i class="material-icons prefix">lock</i>
										<input type="password" name="confirm_password" id="confirm_password" />
										<label for="confirm_password">Confirm Password</label>
									</div>
									<div class="input-field col s12">
										<input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
									</div>
									<input type="submit" class="btn waves-effect waves-light col s8 offset-s2 l6 offset-l3" value="Change Password" id="change_password">
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
    	$('#change_password').click('off');
    	$(document).ready(function(){
    		$('body').on('click', '#change_password', function(e){
    			e.preventDefault();
    			if(!validateData())
    			{
    				return false;
    			}
    			var _token = $('#_token').val();
    			var current_password = $('#current_password').val();
    			var new_password = $('#password').val();
    			var confirm_password = $('#confirm_password').val();
    			$.ajax({
    				type: "POST",
    				url: 'change_password_backend.php',
    				data: {current_password: current_password, password: new_password, confirm_password: confirm_password, _token: _token},
                    dataType: "json",
    				cache: false,
    				success: function(response)
    				{
    					// var response = JSON.parse(response);
    					$('#_token').val(response._token);
    					if(response.error_status === true)
    					{
    						Materialize.toast(response.error, 5000, "red");
    					}
    					else
    					{
							if(typeof(Storage) !== 'undefined')
							{
								sessionStorage.setItem('flashMessage', 'Your password has been changed successfully');
								window.location = 'index.php';
							}		
    					}
    				}

    			});
    		});

    		function validateData()
    		{
    			var current_password = $('#current_password').val();
    			var password = $('#password').val();
    			var confirm_password = $('#confirm_password').val();
    			if(current_password === '')
    			{
    				Materialize.toast('Enter your current password', 5000, 'red');
    				return false;
    			}
    			if(password === '')
    			{
    				Materialize.toast('Enter your new password', 5000, 'red');
    				return false;
    			}
    			if(confirm_password === '')
    			{
    				Materialize.toast('Confirm your new password', 5000, 'red');
    				return false;
    			}
    			if(password !== confirm_password)
    			{
    				Materialize.toast("Your password doesn't match", 5000, 'red');
    				return false;
    			}
    			return true;
    		}
    	});
    </script>
</body>
</html>



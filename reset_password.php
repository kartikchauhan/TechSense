<?php

require_once'Core/init.php';


$user = new User;

if($user->isLoggedIn())
{
	Redirect::to('index.php');
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Reset Password</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta name="keywords" content="blog, technology, code, program, alorithms"/>
		<meta name="description" content="We emphaisze on solving problems">
		<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">

		<style>
			body
			{
				position: absolute;
				height: 100%;
				width: 100%;
				background-color: #eee;
			}
			#reset-password-form
			{
				margin-left:auto;
				margin-right:auto;
				/*position:relative;
			  	top:50%;
			    left:50%;
				-ms-transform: translateX(-50%) translateY(-50%);
				-webkit-transform: translate(-50%,-50%);
				transform: translate(-50%,-50%);*/
			}
			.error
			{
				display: none;
			}
		</style>
	</head>
	<body>

		<?php
			try
			{
				if(!Cookie::exists(Config::get('remember/reset_password')))
					throw new Exception("The session has expired.");
				try
				{
					if(!Input::get('token'))
						throw new Exception("No token");

					$_token = Input::get('token');

					if(!Input::get('user'))
						throw new Exception("No user");

					$email = Input::get('user');

					$userData = DB::getInstance()->get('users', array('email', '=', $email));

					if($userData->count() == 0)
						throw new Exception("Unauthorized User");

					if($userData->first()->email != $email)
						throw new Exception("Unauthorized User");

					if($userData->first()->forgot_password_token != $_token)
						throw new Exception("Invalid Token");
				}
				catch(Exception $e)
				{
					Redirect::to('forgot_password.php');
				}

			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		?>
		<div class="container">
			<div id="reset-password-form">
				<h5 class="center-align condensed light">Reset Password</h5>
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
												<i class="material-icons prefix">lock</i>
												<input type="password" name="password" id="password" />
												<label for="password">Password</label>
											</div>
											<div class="input-field col s12">
												<i class="material-icons prefix">lock</i>
												<input type="password" name="confirm_password" id="confirm_password" />
												<label for="confirm_password">Confirm Password</label>
											</div>
											<div class="input-field col s12">
												<input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
												<input type="hidden" name="email" id="email" value="<?php echo $email ?>">
											</div>
											<input type="submit" class="btn waves-effect waves-light col s6 offset-s3" value="Reset Password" id="reset_password">
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
	    	$('#reset_password').click('off');
	    	$(document).ready(function(){
	    		$('body').on('click', '#reset_password', function(e){
	    			e.preventDefault();
	    			if(!validateData())
	    			{
	    				return false;
	    			}
	    			var _token = $('#_token').val();
	    			var email = $('#email').val();
	    			var password = $('#password').val();
	    			var confirm_password = $('#confirm_password').val();
	    			$.ajax({
	    				type: "POST",
	    				url: 'reset_password_backend.php',
	    				data: {email: email, password: password, confirm_password: confirm_password, _token: _token},
	    				dataType: "json",
	    				cache: false,
	    				success: function(response)
	    				{
	    					// var response = JSON.parse(response);
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
		                            sessionStorage.setItem("flashMessage", 'Your password has been successfully changed');
		                        }
	    						$(window.location).attr('href', 'login.php');
	    					}
	    				}

	    			});
	    		});

	    		function validateData()
	    		{
	    			var password = $('#password').val();
	    			var confirm_password = $('#confirm_password').val();
	    			if(password === '')
	    			{
	    				Materialize.toast('password is required', 5000, 'red');
	    				return false;
	    			}
	    			if(confirm_password === '')
	    			{
	    				Materialize.toast('Confirm Password is required', 5000, 'red');
	    				return false;
	    			}
	    			if(password !== confirm_password)
	    			{
	    				Materialize.toast("The password doesn't match", 5000, 'red');
	    				return false;
	    			}
	    			return true;
	    		}
	    	});
	    </script>
	</body>
</html>
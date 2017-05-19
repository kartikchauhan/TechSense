<?php

require_once'Core/init.php';
require_once'Includes/googleAuth/gpConfig.php';

$user = new User;

if($user->isLoggedIn())
{
	Redirect::to('index.php');
}

$authUrl = $gClient->createAuthUrl();

if(Input::get('code'))
{
	$gClient->authenticate(Input::get('code'));
	Session::put('googleToken', $gClient->getAccessToken());
	Redirect::to('social_login.php');
}

// else
// {
// 	require_once'Includes/googleAuth/gpConfig.php';
// 	$authUrl = $gClient->createAuthUrl();
// }

?>

<!Doctype html>

<html>
	<head>
		<title>
			Login
		</title>
		<meta charset="utf-8">
		<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"/> -->
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<meta name="viewport" content="width=device-width" />
		<meta name="keywords" content="blog, technology, code, program, alorithms"/>
		<meta name="description" content="Publish your passions your way. Whether you'd like to share your knowledge, experiences or the latest tech news, create a unique and beautiful blog for free.">
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
			#login-form
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
			#remember-me-container
			{
				margin-left: 7px;
				margin-top: 15px;
				margin-bottom: 10px;
			}
			.col.s12 > .btn
			{
				width: 100%;
			}
			.error
			{
				display: none;
			}
		</style>

	</head>
	<body>
		
		<div class="container">
			<div id="login-form">
				<h5 class="center-align condensed light">Sign in to TechSense</h5>
				<div class="row">
					<div class="col s12 l4 m6 offset-m3 offset-l4 ">
						<ul class='collection center-align z-depth-1 error'>
							<li class='collection-item red-text'></li>
						</ul>
						<div class="card">
							<div class="card-content">
								<div class="row">
									<form class="col s12 m12 l12" action="" method="post">
										<div class="row">
											<div class="input-field col s12">
												<i class="material-icons prefix">email</i>
												<input type="text" name="email" id="email" value="" />
												<label for="email">Email</label>
											</div>
											<div class="input-field col s12">
												<i class="material-icons prefix">lock</i>
												<input type="password" name="password" id="password" />
												<label for="password">Password</label>
											</div>
											<div class="col s6 offset-s3" id="remember-me-container">
												<input type="checkbox" id="remember_me" name="remember_me">
												<label for="remember_me"> Remember Me</label>
											</div>
											<div class="input-field col s12">
												<input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
											</div>
											<input type="submit" class="btn waves-effect waves-light col s4 offset-s4" value="login" id="submit">
											<div class="center-align">
												<a class="red-text" href="forgot_password.php">Forgot password?</a>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="center-align">Or</div>
						<div class="row">
							<div class="col s12 l8 offset-l2">
								<a href="<?php echo $authUrl ?>" class="waves-effect waves-light btn red">Sign in with google</a>
							</div>
						</div>
						<div class="section">
							<ul class="collection center-align z-depth-1">
								<li class="collection-item">New to TechSense? <a href="register.php">Create an account</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<script src="Includes/js/jquery.min.js"></script>
		<script type="text/javascript" src="Includes/js/materialize.min.js"></script>
		<script>
			if(typeof(Storage) !== 'undefined')
            {
            	if(sessionStorage.getItem("flashMessage") !== null)
            	{
                	Materialize.toast(sessionStorage.getItem("flashMessage"), 5000, 'green');
                	sessionStorage.removeItem("flashMessage");
            	}
            }
            $(document).ready(function() {
            	$('#submit').click(function(e) {
            		e.preventDefault();
            		var _token = $('#_token').val();
            		var email = $('#email').val();
            		var password = $('#password').val();
            		var remember_me = null;
            		if($('#remember_me').prop('checked') == true)
            		{
            			remember_me = true;
            		}
            		else
            		{
            			remember_me = false;
            		}
            		console.log(_token + email + password + remember_me);

            		$.ajax({
            			url: 'login_backend.php',
            			data: {email: email, password: password, remember_me: remember_me, _token: _token},
            			type: 'POST',
            			dataType: "json",
            			cache: false,
            			success : function(response)
            			{
            				// var response = JSON.parse(response);
            				console.log(response);
            				if(response.error_status == true)
            				{
            					$('.error').show();
            					console.log(response._token);
				        		if(response.error_code != 1)
				        		{
				        			$('#_token').val(response._token);
				        		}
				        		$('.error').show().find('li').text(response.error);
				        		Materialize.toast(response.error, 5000, "red");
            				}
            				else
            				{
            					window.location = 'index.php';
            					$('#_token').val(response._token);
            					if(typeof(Storage) !== 'undefined')
								{
									sessionStorage.setItem('flashMessage', 'You have successfully logged in');
									if(sessionStorage.getItem('Redirect') !== null)
									{
										var url = sessionStorage.getItem('Redirect');
										sessionStorage.removeItem('Redirect');
										window.location = url;
									}
									else
										window.location = 'index.php';
								}
            				}
            			}
            		});
            	});
            });
		</script>
	</body>
</html>
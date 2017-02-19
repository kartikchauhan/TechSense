<?php

require_once'Core/init.php';

$user = new User;

if($user->isLoggedIn())
{
	$user->logout();
}

?>

<!Doctype html>

<html>
	<head>
		<title>
			Register
		</title>
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
			#registration-form
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
			.usernames-available
			{
				display: none;
			}
		</style>

	</head>
	<body>
	
		<div id="registration-form">
			<h5 class="center-align condensed light">Register to BlogSparta</h5>
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
											<i class="material-icons prefix">account_box</i>
											<input type="text" name="name" id="name" />
											<label for="name">Name</label>
										</div>
										<div class="input-field col s12">
											<i class="material-icons prefix">person</i>
											<input type="text" name="username" id="username" />
											<label for="username">Username</label>
										</div>
										<div class="center-align">
											<span class="usernames-available red-text"></span>
										</div>
										<div class="input-field col s12">
											<i class="material-icons prefix">email</i>
											<input type="text" name="email" id="email" />
											<label for="email">Email</label>
										</div>
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
										</div>
										<input type="submit" class="btn waves-effect waves-light col s4 offset-s4" value="submit" id="submit">
									</div>
								</form>
							</div>
						</div>
					</div>
					<ul class="collection center-align z-depth-1">
						<li class="collection-item">Already have an account? <a href="login.php">Login</a></li>
					</ul>
				</div>
			</div>
		</div>
		
		<script src="Includes/js/jquery.min.js"></script>
		<script type="text/javascript" src="Includes/js/materialize.min.js"></script>
		<script type="text/javascript">
			// $('#submit').off('click');
			$(document).ready(function(){
				$('body').on('click', '#submit', function(e){
					e.preventDefault();
					var name = $('#name').val();
					var username = $('#username').val();
					var email = $('#email').val();
					var password = $('#password').val();
					var confirm_password = $('#confirm_password').val();
					var _token = $('#_token').val();
					
					$.ajax({
						type : "POST",
						url : "register_backend.php",
						data : {name: name, username: username, email: email, password: password, confirm_password: confirm_password, _token: _token},
						cache: false,
						success: function(response)
						{
							var response = JSON.parse(response);
							$('#_token').val(response._token);
							if(response.status==0)
							{
								Materialize.toast(response.message, 4000, 'green');
							}
							else
							{
								if(response.username_exists === true)
								{
									var usernamesAvailable = "Usernames available: ";									
									for(var i=0; i<3; i++)
									{
										if(i<2)
											usernamesAvailable = usernamesAvailable + response.usernames_available[i] + ', ';
										else
											usernamesAvailable = usernamesAvailable + response.usernames_available[i];
									}	
								}
								$('.usernames-available').text(usernamesAvailable);
								$('.usernames-available').show();
								$('.error li').text(response.message);
								$('.error').show();
							}
						}
					});

				});

			});
		</script>
	</body>
</html>

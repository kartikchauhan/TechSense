<?php

require_once'Core/init.php';

$user = new User;

if($user->isLoggedIn())
{
	$user->logout();
}

$error_status = false;
$name = '';
$email = '';
$usernames_available = '';

if(Input::exists())
{
    if(Token::check(Input::get('_token')))
    {
        $name = Input::get('name');
        $email = Input::get('email');

        $Validate = new Validate;
        $Validate->check($_POST, array(
        'name'           => array(
        'required' => true,
        'min'      => 2,
        'max'      => 25
        ),
        'username'       => array(
        'required' => true,
        'min'      => 2,
        'max'      => 25,
        'unique'   => 'users'
        ),
        'email'            => array(
        'required' => true,
        'email'    => true,
        'unique'   => 'users'
        ),
        'password'       => array(
        'required' => true,
        'min'      => 6,
        ),
        'confirm_password' => array(
        'required' => true,
        'matches'  => 'password'
        ),
        ));

        if($Validate->passed())
        {
            $username_exists = false;
            $salt = Hash::salt(32);
            try
            {
                $createUser = $user->create('users', array(
                    'name' => Input::get('name'),
                    'username' => Input::get('username'),
                    'email'=> Input::get('email'),
                    'password'=> Hash::make(Input::get('password'), $salt),
                    'salt' => $salt,
                    'created_on' => Date('Y-m-d H:i:s'),
                    'image_url' => 'default.jpg'  // saving a default image while creating new user
                ));
                if(!$createUser)
                    throw new Exception("Unable to create account right now. Please try again later");
                    
                if($user->login(Input::get('email'), Input::get('password')))
                {
                    echo
                    "<script>
                        if(typeof(Storage) !== 'undefined')
                        {
                            sessionStorage.setItem('flashMessage', 'You have successfully registered');
                            window.location = 'index.php';
                        }
                    </script>";
                }
            }
            catch(Exception $e)
            {
                $error_status = true;
                $error = $e->getMessage();
            }
        }
        else
        {
            $error_status = true;
            $error = $Validate->errors()[0];

            if($Validate->isUsernameExists())
            {
                $username_exists = true;
                $usernames_available = generateUsernames(Input::get('name'));
            }
        }
    }
}

function generateUsernames($name)
{
    $name = explode(' ', $name);
    $tokens = array('_', '-', '');
    $username_string = "Usernames available: ";
    for($i=0; $i<3; $i++)
    {
        $username[$i] = mt_rand(9,99)%2 ? current($name).$tokens[array_rand($tokens)].mt_rand(9, 9999) : end($name).$tokens[array_rand($tokens)].mt_rand(9, 9999);
        if($i < 2)
        {
            $username_string = $username_string.$username[$i].', ';
        }
        else
        {
            $username_string = $username_string.$username[$i];
        }
    }
    return $username_string;
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
		</style>

	</head>
	<body>
	
		<div id="registration-form">
			<h5 class="center-align condensed light">Register to BlogSparta</h5>
			<div class="row">
				<div class="col s12 l4 offset-l4">
					<!-- <ul class="collection center-align z-depth-1 error"> -->
                        <?php
                            if($error_status)
                            {
                                echo 
                                "<ul class='collection center-align z-depth-1 error'>
                                    <li class='collection-item red-text'>".$error."</li>
                                </ul>";
                            }
                        ?>
						<!-- <li class="collection-item red-text"></li> -->
					<!-- </ul> -->
					<div class="card">
						<div class="card-content">
							<div class="row">
								<form class="col s12" action="" method="post">
									<div class="row">
										<div class="input-field col s12">
											<i class="material-icons prefix">account_box</i>
											<input type="text" name="name" id="name" value="<?php echo $name; ?>"/>
											<label for="name">Name</label>
										</div>
										<div class="input-field col s12">
											<i class="material-icons prefix">person</i>
											<input type="text" name="username" id="username" />
											<label for="username">Username</label>
                                            <?php
                                                if(!empty($usernames_available))
                                                {
                                                    echo "<span class='red-text center-align'>".$usernames_available."</span>";
                                                }
                                            ?>
										</div>
										<div class="center-align">
											<span class="usernames-available red-text"></span>
										</div>
										<div class="input-field col s12">
											<i class="material-icons prefix">email</i>
											<input type="text" name="email" id="email" value="<?php echo $email ?>"/>
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
	</body>
</html>

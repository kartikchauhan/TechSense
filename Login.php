<?php

require_once'Core/init.php';

if(Input::exists('post'))
{
	if(Token::check(Input::get('_token')))
	{
		$Validate = new Validate;
		$Validate->check($_POST, array(
			'email' => array(
				'required' => true
				),
			'password' => array(
				'required' => true,
				'min' => 6
				)
			));

		if($Validate->passed())
		{
			$user = new User;
			if($user->login(Input::get('email'), Input::get('password'), Input::get('remember_me')))
				Redirect::to('index.php');
			else
				echo "Sorry wrong credentials";
		}
		else
		{
			foreach($Validate->errors() as $error)
			{
				echo $error.'<br>';
			}
		}
	}
}

?>


<form action="" method="post">
<div>
	<label for="email">Email</label>
	<input type="text" name="email" id="email" >
</div>

<div>
	<label for="password">Password</label>
	<input type="password" name="password" id="password" >
</div>

<div>
	<label for="remember_me">
		<input type="checkbox" name="remember_me" id="remember_me" >Remember me
	</label>
</div>
<input type="hidden" name="_token" value="<?php echo Token::generate(); ?>">

<input type="submit" value="login">
</form>

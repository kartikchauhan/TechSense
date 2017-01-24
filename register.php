<?php

require_once'Core/init.php';

if(Input::exists())
{
	if(Token::check(Input::get('_token')))
	{
		$Validate = new Validate;
		$Validate->check($_POST, array(

			'name'     		   => array(
				'required' => true,
				'min'      => 2,
				'max'      => 25
				),
			'username' 		   => array(
				'required' => true,
				'min'      => 2,
				'max'      => 25,
				'unique'   => 'users'
				),
			'email'            => array(
				'required' => true,
				'min'      => 7,
				'max'      => 40,
				'unique'   => 'users'
				),
			'password' 		   => array(
				'required' => true,
				'min'      => 6,
				),
			'confirm_password' => array(
				'required' => true,
				'matches'  => 'password'
				),
			));

		if($Validate->passed())
			echo "passed";
		else
		{
			foreach($Validate->errors() as $error)			
				echo $error.'<br>';
		}
	}
}

?>

<form method="post" action="">
<div>
<label for="name">Name</label>
<input type="text" name="name" id="name" value="<?php echo Input::get('username')?>">
</div>

<div>
<label for="username">Username</label>
<input type="text" name="username" id="username">
</div>

<div>
<label for="email">Email</label>
<input type="text" name="email" id="email" value="<?php echo 	Input::get('email')?>">
</div>

<div>
<label for="password">Password</label>
<input type="password" name="password" id="password">
</div>

<div>
<label for="confirm_password">Confirm Password</label>
<input type="password" name="confirm_password" id="confirm_password">
</div>

<input type="hidden" name="_token" value="<?php echo Token::generate(); ?>">

<input type="submit" value="register">

</form>

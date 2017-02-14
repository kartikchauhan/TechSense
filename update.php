<?php

require_once'Core/init.php';

$user = new User;
if(!$user->isLoggedIn())
{
	Redirect::to('index.php');
	return false;
}

if(Input::exists())
{
	if(Token::check(Input::get('_token')))
	{
		$Validate = new Validate;
		$Validate->check($_POST, array(
			'name' => array(
				'required' => true,
				'min' => 2,
				'max' => 25
				),
			));

		if($Validate->passed())
		{
			try
			{
				$user->update('users', $user->data()->id, array('name' => Input::get('name')));
				Redirect::to('index.php');
			}
			catch(Exception $e)
			{
				echo $e->getMessage();
			}
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
	<label for="name">Name</label>
	<input type="text" name="name" id="name" value="<?php echo $user->data()->name ?>">
</div>
<input type="hidden" name="_token" id="_token" value="<?php echo Token::generate() ?>">
<input type="submit" value="update">

</form>


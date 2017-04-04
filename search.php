<?php

require_once'Core/init.php';

// if(Input::exists('post'))
// {
	// $json['error_status'] = false;
	$search = DB::getInstance();
	$query = "name: kartik chauhan";
	$query = split(":", $query);
	$query_field = strtolower(trim($query[0]));
	$query_field_value = strtolower(trim($query[1]));
	echo $query_field.'<br>'.$query_field_value;
	if($query_field === 'user')
	{
		$result = $search->searchIdViaUser('users', array('username', '=', $query_field_value));		// fetching the id of the user
		try
		{
			if(!$result)
			{
				throw new Exception("Some error occured while fetching results. Please try again later");
			}
			if($result->count() != 1)
			{
				throw new Exception("There is no user with Username ".$query_field_value);
			}
			$resultBlogs = $search->searchBlogsViaUser('blogs', array('users_id', '=', $result->results()[0]->id));
			if(!$resultBlogs)
			{
				throw new Exception("Some error occured while fetching results. Please try again later");
			}
			if($resultBlogs->count() == 0)
			{
				throw new Exception("There are no blogs associated to User ".$query_field_value);
			}
			
		}
		catch(Exception $e)
		{
			echo($e->getMessage());
		}
	}
	else if($query_field === 'tags')
	{
		echo 'tags';
		$tags = split(',', $query_field_value);
	}
	else if($query_field === 'title')
	{
		$query_field_value = '%'.$query_field_value.'%';
		$resultBlogs = $search->searchBlogsViaTitle('blogs', array('title', 'like', $query_field_value));		// fetching the id of the user
		try
		{
			if(!$resultBlogs)
			{
				throw new Exception("Some error occured while fetching results. Please try again later");
			}
			if($resultBlogs->count() == 1)
			{
				throw new Exception("There are no blogs associated with the title ".$query_field_value);
			}
			var_dump($resultBlogs);

		}
		catch(Exception $e)
		{
			echo($e->getMessage());
		}
	}
	else if($query_field === 'name')
	{
		$result = $search->searchIdViaName('users', array('name', '=', $query_field_value));		// fetching the id of the user
		try
		{
			if(!$result)
			{
				throw new Exception("Some error occured while fetching results. Please try again later");
			}
			if($result->count() == 0)
			{
				throw new Exception("There is no user with the name ".$query_field_value);
			}
			$counter = 0;
			foreach($result->results() as $key)
			{
				echo "<br><br>fetching results from name <br><br>";
				$resultBlogs = $search->searchBlogsViaName('blogs', array('users_id', '=', $key->id));
				if($resultBlogs->count() != 0)
				{
					$counter += $resultBlogs->count();
					foreach ($resultBlogs->results() as $key) {
						var_dump($key->title);
					}
				}
			}
			if($counter == 0)
			{
				throw new Exception("There are no blogs associated with the name ".$query_field_value);
				
			}
			// $resultBlogs = $search->searchBlogsViaUser('blogs', array('users_id', '=', $result->results()[0]->id));
			// if(!$resultBlogs)
			// {
			// 	throw new Exception("Some error occured while fetching results. Please try again later");
			// }
			// if($resultBlogs->count() == 0)
			// {
			// 	throw new Exception("There are no blogs related to User ".$query_field_value);
			// }
			
		}
		catch(Exception $e)
		{
			echo($e->getMessage());
		}
	}

// }

?>
<?php

class User
{
	private $_db,
			$_sessionName,
			$_isLoggedIn = false,
			$_data = null;

	public function __construct($user = null)
	{
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/session_name');

		if(!$user)
		{
			if(Session::exists($this->_sessionName))
			{
				$user = Session::get($this->_sessionName);
				if($user)
				{
					if($this->find($user))
					{
						$this->_isLoggedIn = true;
					}
				}
				else
				{
					// process log out
				}
			}
		}
		else
		{
			$this->find($user);
		}

	}

	public function create($table, $fields = array())
	{	
		$this->_db->insert($table, $fields);
	}

	private function find($user)
	{
		if($user)
		{
			$field = is_numeric($user) ? 'id' : 'email';
		}
		$data = $this->_db->get('users', array($field, '=', $user));
		
		if($data->count())
		{
			$this->_data = $data->first();
			return true;
		}
		return false;
	}

	public function login($email = null, $password = null)
	{
		$user = $this->find($email);
		if($user)
		{
			if($this->data()->password === Hash::make($password, $this->data()->salt))
			{
				Session::put($this->_sessionName, $this->data()->id);
				return true;
			}
		}
		return false;
	}

	public function data()
	{
		return $this->_data;
	}

	public function isLoggedIn()
	{
		return $this->_isLoggedIn;
	}
}

?>
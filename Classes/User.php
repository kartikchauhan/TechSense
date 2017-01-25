<?php

class User
{
	private $_db,
			$_sessionName,
			$_data = null;

	public function __construct()
	{
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/session_name');

	}

	public function create($table, $fields = array())
	{	
		$this->_db->insert($table, $fields);
	}

	private function find($email)
	{
		$data = $this->_db->get('users', array('email', '=', $email));
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
				Session::put($this->_sessionName, $this->data()->username);
				return true;
			}
		}
		return false;
	}

	public function data()
	{
		return $this->_data;
	}
}

?>
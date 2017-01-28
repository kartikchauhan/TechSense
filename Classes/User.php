<?php

class User
{
	private $_db,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn = false,
			$_data = null;

	public function __construct($user = null)
	{
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

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

	public function update($table, $id, $fields = array())
	{
		if($this->isLoggedIn())
		{
			$id = $this->data()->id;
		}

		if(!$this->_db->update($table, $id, $fields))
		{
			throw new Exception("There was some problem updating your profile, please try again later");
		}
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

	public function login($email = null, $password = null, $remember_me = false)
	{
		if(!$email && !$password && $this->exists())
		{
			Session::put($this->_sessionName, $this->data()->id);
		}
		else
		{
			$user = $this->find($email);
			if($user)
			{
				if($this->data()->password === Hash::make($password, $this->data()->salt))
				{
					Session::put($this->_sessionName, $this->data()->id);

					if($remember_me)
					{
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('users_cookie', array('user_id', '=', $this->data()->id));
						if(!$hashCheck->count())
						{
							$this->_db->insert('users_cookie', array(
									'user_id' => $this->data()->id,
									'hash' => $hash,
								));
						}
						else
						{
							$hash = $hashCheck->first()->hash;
						}
						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));

					}

					return true;
				}
			}
		}
		return false;
	}

	private function exists()
	{
		return (!empty($this->data()) ? true : false);
	}

	public function data()
	{
		return $this->_data;
	}

	public function logout()
	{
		$this->_db->delete('users_cookie', array('user_id', '=', $this->data()->id));
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
		$this->_isLoggedIn = false;
	}

	public function isLoggedIn()
	{
		return $this->_isLoggedIn;
	}
}

?>
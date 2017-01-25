<?php

class User
{
	private $_db;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	public function create($table, $fields = array())
	{	
		$this->_db->insert($table, $fields);
	}
}

?>
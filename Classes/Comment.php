<?php

class Comment
{
	private $_data = null,
			$_db;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	public function getComments($table, $fields = array())
	{
		if($data = $this->_db->get($table, $fields))
		{
			if($data->count())
			{
				$this->_data = $data;
				return true;
			}
		}
		return false;
	}

	public function count()
	{
		return $this->_db->count();
	}

	public function data()
	{
		return $this->_data;
	}
}

?>
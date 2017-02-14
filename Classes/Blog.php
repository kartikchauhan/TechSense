<?php

class Blog
{
	private $_view = false,
			$_like = false,
			$_dislike = false,
			$_db;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	public function setView()
	{
		$this->_view = true;
	}

	public function checkView()
	{
		return $this->_view;
	}

	public function setLike()
	{
		$this->_view = true;
	}

	public function checkLike()
	{
		return $this->_view;
	}

	public function setDislike()
	{
		$this->_view = true;
	}

	public function checkDislike()
	{
		return $this->_view;
	}

	public function update($table, $id, $fields = array())
	{
		if(!$this->_db->update($table, $id, $fields))
			throw new Exception("There was some problem getting your response right now, please try again later");
	}
}

?>
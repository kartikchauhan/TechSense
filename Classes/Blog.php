<?php

class Blog
{
	private $_data = null,
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
		{
			throw new Exception("Unable to update views of the blog.");
		}
		else
		{
			return $this->_db->count();
		}
	}

	public function getBlog($table, $fields = array())
	{
		if($data = $this->_db->get($table, $fields))
		{
			if($data->count())
			{
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function deleteBlog($table, $fields = array())
	{
		if($blog = $this->_db->delete($table, $fields))
		{
			return $this;
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
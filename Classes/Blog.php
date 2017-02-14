<?php

class Blog
{
	private $_view = false,
			$_like = false,
			$_dislike = false;

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
}

?>
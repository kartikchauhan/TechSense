<?php

class Validate
{
	private $_errors = array(),
			$_passed = false;

	public function check($source, $items = array())
	{
		foreach($items as $item => $rules)
		{

			$value = $source[$item];
			foreach($rules as $rule => $rule_value)
			{
				if($rule==='required' && empty($value))
				{
					$this->addError("{$item} is required");
				}
			}
		}
		if(empty($this->_errors))
		{
			$this->_passed = true;
		}
		
		return $this;
	}

	private function addError($error)
	{
		$this->_errors[] = $error;
	}

	public function errors()
	{
		return $this->_errors;
	}

	public function passed()
	{
		return $this->_passed;
	}

}





?>
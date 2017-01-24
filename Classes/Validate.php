<?php

class Validate
{
	private $_errors = array(),
			$_db = null,
			$_passed = false;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}
	public function check($source, $items = array())
	{
		foreach($items as $item => $rules)
		{

			$value = trim($source[$item]);
			foreach($rules as $rule => $rule_value)
			{
				if($rule==='required' && empty($value))
				{
					$this->addError("{$item} is required");
				}
				else if(!empty($value))
				{
					switch($rule)
					{
						case 'min': 
									if(strlen($value) < $rule_value)
										$this->addError("{$item} must contain atleast {$rule_value} characters");
						break;
						case 'max': 
									if(strlen($value) > $rule_value)
										$this->addError("{$item} can contain atmost {$rule_value} characters");
						break;
						case 'matches': 
										if($value!=$source[$rule_value])
											$this->addError("{$item} doesn't match with the {$rule_value}");
						break;
						case 'unique': 
										$this->_db->get($rule_value, array($item, '=', $value));
										if($this->_db->count() > 0)
											$this->addError("{$item} already exists");
						break;
					}
				}
			}
		}
		if(empty($this->_errors))
		{
			$this->_passed = true;
		}

		// return $this;
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
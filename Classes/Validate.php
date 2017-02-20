<?php

class Validate
{
	private $_errors = array(),
			$_db = null,
			$_usernameExists = 0,
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
											$this->addError("{$item} should match with the {$rule_value}");
						break;
						case 'unique': 
										$this->_db->get($rule_value, array($item, '=', $value));
										if($this->_db->count() > 0)
										{
											$this->addError("{$item} already exists");
											if($item === "username")
												$this->_usernameExists = 1;
										}
						case 'email':
										if(!filter_var($value, FILTER_VALIDATE_EMAIL))
											$this->addError("Wrong Email format");
						break;
					}
				}
			}
		}
		if(empty($this->_errors))
		{
			$this->_passed = true;
		}
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

	public function isUsernameExists()
	{
		return $this->_usernameExists;
	}

}

?>
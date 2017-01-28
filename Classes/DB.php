<?php

class DB
{
	private static $_instance = null;
	private $_pdo, $_query, $_count=0, $_error = false, $_results;

	private function __construct()
	{
		try
		{
			$this->_pdo = new PDO("mysql:host=".Config::get('mysql/host')."; dbname=".Config::get('mysql/database'), Config::get('mysql/user'), Config::get('mysql/password'));
		}
		catch(PDOException $e)
		{
			die($e->getMessage());
		}
	}

	public static function getInstance()
	{
		if(!isset(self::$_instance))	// if $_instance property not set then calling the constructor to set it
		{
			self::$_instance = new DB();
		}
		return self::$_instance;	// passing the $_instance property so that other methods could be accessed
	}

	public function query($sql, $params = array())	// this method accepts a unprepared sql query and an array that contains values and needed to be binded to the query
	{
		$this->_error = false;	// set error = false because the previous query might have set it to true
		if($this->_query = $this->_pdo->prepare($sql))	// preparing the query $sql, that is turning {$variable} into a valid sql_query
		{
			if($this->_query->execute(array_values($params)))	// bind the array values to the $sql and execute the query
			{
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);	// setting the result to the $_results property
				$this->_count = $this->_query->rowCount();	// getting count of total number of rows
			}
			else
			{
				$this->_error = true;
			}
		}
		return $this;
	}

	private function action($action, $table, $where = array())
	{
		if(count($where)==3)	// if there are three values inside $where array then proceed else return false to the get function which in turn will return false to the calling function
		{
			$operators = array('=', '<', '>', '<=', '>=');
			$fieldname = $where[0];	
			$operator = $where[1];
			$value = $where[2];

			if(in_array($operator, $operators))	// checking if the passed operator is a valid operator or not
			{
				$sql = "{$action} FROM {$table} WHERE {$fieldname} {$operator} ?"; // this query will be prepared after passing into the query method, preparing means the curly braces will change into a valid query

				if(!$this->query($sql, array($value))->error())	// if no error return the current object to the get method which will return the object to the calling function
					return $this;
			}
		}
		return false;
	}

	public function get($table, $where = array())		// passed table_name and where condition in the form of array as parameter to the get function
	{
		return $this->action('Select *', $table, $where);	// calling action method by passing these three parameters where $where is an array consisting of fieldname, operator and a value
	}

	public function insert($table, $fields = array())
	{
		// In order to make a valid INSERT query our syntax should be in the form -
		// "INSERT INTO table_name(first_field, second_field) VALUES(?, ?)"; 
		// where the '?' will be replaced by the $field_values after binding
		// we need to implode ',' to with all the array_keys and '?'
		if(count($fields))
		{
			$keys = array_keys($fields);
			$values = array();
			$fields_count = count($fields);
			for($x=0; $x<$fields_count; $x++)
			{
				$values[$x] = '?';
			}
			$values = implode(',', $values);
			$keys = implode(',', $keys);
			$sql = "INSERT INTO {$table} ({$keys}) VALUES({$values});";
			if(!$this->query($sql, $fields)->error())
			{
				return true;
			}
		}
		return false;
	}

	public function update($table, $id, $fields = array())
	{
		$set = '';
		$x = 1;
		$fields_count = count($fields);
		foreach($fields as $field=>$field_value)
		{
			if($x < $fields_count)
				$set .= $field.' = ?, ';
			else
				$set .= $field.' = ? ';
			$x++;
		}

		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

		if(!$this->query($sql, $fields)->error())
		{
			return $this;
		}
		return false;

	}

	public function delete($table, $where = array())
	{
		return $this->action("DELETE", $table, $where);
	}

	public function count()
	{
		return $this->_count;
	}

	public function results()
	{
		return $this->_results;
	}

	public function first()
	{
		return $this->results()[0];
	}

	public function error()
	{
		return $this->_error;
	}
}

?>
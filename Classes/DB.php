<?php

class DB
{
	private static $_instance = null;
	private $_pdo, $_query, $_count=0, $_error = false, $_results, $_lastInsertId = null;

	private function __construct()
	{
		try
		{
			$this->_pdo = new PDO("mysql:host=".Config::get('mysql/host')."; dbname=".Config::get('mysql/database')."; port=".Config::get('mysql/port'), Config::get('mysql/user'), Config::get('mysql/password'));
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

	public function query($sql, $params = array(), $insert_query = false)	// this method accepts a unprepared sql query and an array that contains values and needed to be binded to the query
	{
		$this->_error = false;	// set error = false because the previous query might have set it to true
		if($this->_query = $this->_pdo->prepare($sql))	// preparing the query $sql, that is passing '?' as parameters. Compiler compiles and parses the sql statement and stores it into the database. this helps in query optimization and prevents sql Injection.
		{
			if($this->_query->execute(array_values($params)))	// bind the array values to the $sql and execute the query
			{
				if($insert_query == true)
				{
					$this->_lastInsertId = $this->_pdo->lastInsertId();
				}
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
			$operators = array('=', '!=', '<', '>', '<=', '>=');
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
		return $this->action('SELECT *', $table, $where);	// calling action method by passing these three parameters where $where is an array consisting of fieldname, operator and a value
	}

	public function getAnd($table, $fields = array())
	{
		$where = '';
		$x = 1;
		$field_count = count($fields);
		foreach ($fields as $field => $value)
		{
			if($x < $field_count)
				$where = $where.$field.' = ? AND ';
			else
				$where = $where.$field.' = ?';
			$x++;
		}

		$sql = "SELECT * FROM {$table} WHERE {$where}";

		if(!$this->query($sql, $fields)->error())
		{
			return $this;
		}
		return false;
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
			if(!$this->query($sql, $fields, $insert_query = true)->error())
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

	public function getLastInsertId()
	{
		return $this->_lastInsertId;
	}

	public function results()
	{
		return $this->_results;
	}

	public function first()
	{
		return $this->results()[0];
	}

	public function sort($table, $fields = array())
	{
		if(count($fields) == 2)
		{
			$field = $fields[0];
			$order = $fields[1];
			$sql = "SELECT * FROM {$table} ORDER BY {$field} {$order}";

			if(!$this->query($sql)->error())
			{
				return $this;
			}
		}
		return false;
	}

	public function SortByField($table, $fields = array(), $where = array())	// function name 'sortByField' has been changed from 'sortUser', remove anomalies if there're any, message needs to be removed during production
	{
		if(count($fields) == 2)
		{
			$field = $fields[0];
			$order = $fields[1];
			$where_field = $where[0];
			$operator = $where[1];
			$value = $where[2];
			$sql = "SELECT * FROM {$table} WHERE {$where_field} {$operator} ? ORDER BY {$field} {$order}";

			if(!$this->query($sql, array($value))->error())
			{
				return $this;
			}
		}
		return false;
	}

	public function fetchRecords($numRecords)
	{
		if($numRecords)
		{
			$records = array();
			if($this->count() >= $numRecords)
			{
				for($x = 0; $x < $numRecords; $x++)
				{
					$records[$x] = $this->results()[$x];
				}
				return $records;
			}
			else if($this->results())
			{
				return $this->results();	
			}
		}
		return false;
	}

	public function error()
	{
		return $this->_error;
	}

	public function join($tables = array(), $joinFields = array(), $where = array())	// $table2 should be the one on which we are putting WHERE condition
	{
		$table1 = $tables[0];
		$table2 = $tables[1];
		$joinField1 = $joinFields[0];
		$joinField2 = $joinFields[1];
		$where_field = $where[0];
		$operator = $where[1];
		$value = $where[2];
		$sql = "SELECT * FROM {$table1} JOIN {$table2} ON {$table1}.{$joinField1} = {$table2}.{$joinField2} WHERE {$table2}.{$where_field} {$operator} ?";
		if(!$this->query($sql, array($value))->error())
			return $this;
		return false;
	}

	public function getRange($table, $records_per_page, $offset)
	{
		$sql = "SELECT * FROM {$table} ORDER BY id LIMIT {$records_per_page} OFFSET {$offset}";

		if(!$this->query($sql)->error())
			return $this;
		return false;
	}

	// function for getting records within a certian range 
	public function getRangeSort($table, $records_per_page, $offset, $fields = array())
	{
		$field = $fields[0];
		$order = $fields[1];

		$sql = "SELECT * FROM {$table} ORDER BY {$field} {$order} LIMIT {$records_per_page} OFFSET {$offset}";
		if(!$this->query($sql)->error())
			return $this;
		return false;
	}

	// function for getting records within a certain range for a certain user
	public function getRangeSortUser($table, $records_per_page, $offset, $fields = array(), $where = array())
	{
		$field = $fields[0];
		$order = $fields[1];
		$where_field = $where[0];
		$operator = $where[1];
		$value = $where[2];
		$sql = "SELECT * FROM {$table} WHERE {$where_field} {$operator} ? ORDER BY {$field} {$order} LIMIT {$records_per_page} OFFSET {$offset}";
		if(!$this->query($sql, array($value))->error())
			return $this;
		return false;
	}

	public function joinSortComments($tables = array(), $joinFields = array(), $fields = array(), $where = array(), $alias = array())
	{
		// not using associative arrays for alias array, need to be changed later
		$alias_field1 = $alias[0];	// comments.id
		$alias1 = $alias[1];		// comment_id
		$alias_field2 = $alias[2];	// comments.created_on
		$alias2 = $alias[3];		// comment_created_on
		$alias_field3 = $alias[4];	// comments.likes
		$alias3 = $alias[5];		// comment_likes
		$alias_field4 = $alias[6];	// comments.dislikes
		$alias4 = $alias[7];		// comments_dislikes
		$table1 = $tables[0];	// users table
		$table2 = $tables[1];	// comments table
		$table3 = $tables[2];	// blogs table
		$joinField1 = $joinFields[0];	// users.id
		$joinField2 = $joinFields[1];	// comments.user_id
		$joinField3 = $joinFields[2];	// blogs.id
		$joinField4 = $joinFields[3];	// comments.blog_id
		$where_field = $where[0];	// blogs.id
		$operator = $where[1];
		$value = $where[2];
		$field = $fields[0];	// comments.created_on
		$order = $fields[1];	// DESC
		// SELECT *, comments.id as comment_id, comment.created_on AS comment_created_on, comments.likes as comment_likes, comments.dislikes as comment_dislikes FROM users Join comments ON users.id = comments.user_id Join blogs ON blogs.id = comments.blog_id WHERE blogs.id = 122 ORDER BY comments.created_on DESC
		$sql = "SELECT *, {$table2}.{$alias_field1} AS {$alias1}, {$table2}.{$alias_field2} AS {$alias2}, {$table2}.{$alias_field3} AS {$alias3}, {$table2}.{$alias_field4} AS {$alias4} FROM {$table1} JOIN {$table2} ON {$table1}.{$joinField1} = {$table2}.{$joinField2} JOIN {$table3} ON {$table3}.{$joinField3} = {$table2}.{$joinField4} WHERE {$table3}.{$where_field} {$operator} ? ORDER BY {$table2}.{$field} {$order}";
		if(!$this->query($sql, array($value))->error())
		{
			return $this;
		}
		return false;
	}

	public function search($searchParameter)
	{
		$searchParameterArray = [];		// searchParameterArray will hold the value of searchParameter that the user entered
		$searchParameterForLikeClause = '%'.$searchParameter.'%';	// $searchParameterForLikeClause will hold the value of searchParameter that the user entered for queries having LIKE clause.
		$searchParameterArray[0] = $searchParameter;
		$searchParameterArray[1] =  $searchParameterForLikeClause;
		$searchParameterArray[2] = $searchParameter;
		$searchParameterArray[3] =  $searchParameterForLikeClause;
		$sql = "SELECT blog_id FROM blog_tags WHERE tags = ? UNION SELECT id FROM blogs WHERE title LIKE ? UNION SELECT blogs.id FROM blogs JOIN users ON blogs.users_id = users.id WHERE users.username = ? UNION SELECT blogs.id FROM blogs JOIN users ON blogs.users_id = users.id WHERE users.name LIKE ?";
		if(!$this->query($sql, $searchParameterArray)->error())
		{
			return $this;
		}
		return false;
	}

	public function distinctRecords($table, $field = array())
	{
		$field = $field[0];
		$sql = "SELECT DISTINCT {$field} FROM {$table}";
		if(!$this->query($sql, array($field))->error())
		{
			return $this;
		}
		return false;
	}

	public function countRecords($table, $fields = array())
	{
		$field = $fields[0];
		$operator = $fields[1];
		$value = $fields[2];
		$sql = "SELECT count(*) AS count FROM {$table} WHERE {$field} {$operator} ?";
		if(!$this->query($sql, array($value))->error())
		{
			return $this;
		}
		return false;
	}
}

?>
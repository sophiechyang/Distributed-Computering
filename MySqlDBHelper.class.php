<?php

header("content-type:text/html;charset=utf-8");

class MySqlDBHelper
{
	private static $instance;

	private $connection;

	private $result;

	private $config = array(
		'host' => 'localhost',
		'username' => 'yangch',
		'password' => '7745814',
		'database' => 'yangch_BANKACCOUNTS',
		'port' => '13149'
	);

	/**
	 * create the connect to mysql
	 * @param $config array of configuration
	 */
	private function __construct($config)
	{
		if($config == false)
		{
			$config = $this->config;
		}
		$host = $config['host'];
		$username = $config['username'];
		$password = $config['password'];
		$database = $config['database'];
		$port = $config['port'];

		$this->connection = @mysql_connect("{$host}:{$port}", $username, $password) or die('could not connect to the database.\n');
		$this->query("set names 'utf8'");
		$this->query("use {$database}");
	}

	public static function getInstance($config = false)
	{
		if (!self::$instance instanceof MySqlDBHelper)
		{
			self::$instance = new MySqlDBHelper($config);
		}
		return self::$instance;
	}

	public function query($sql)
	{
		if (!$result = mysql_query($sql, $this->connection))
		{
			echo 'SQL execute failed<br>';
			echo 'Error No：' . mysql_errno(), '<br>';
			echo 'Error Info：' . mysql_error(), '<br>';
			echo 'Error SQL：' . $sql, '<br>';
			exit;
		}
		return $result;
	}

	public function select($table, $field, $where)
	{
		$sql = "SELECT * FROM {$table}";
		if (!empty($field)) {
			$field = '`' . implode('`,`', $field) . '`';
			$sql = str_replace('*', $field, $sql);
		}
		if (!empty($where)) {
			$sql = $sql . ' WHERE ' . $where;
		}

		$rs = $this->query($sql);
		while ($rows = mysql_fetch_assoc($rs)) {
			return $rows;
		}
		return null;
	}

	public function insert($table, $data)
	{
		foreach ($data as $key => $value) {
			$data[$key] = mysql_real_escape_string($value);
		}
		$keys = '`' . implode('`,`', array_keys($data)) . '`';
		$values = '\'' . implode("','", array_values($data)) . '\'';
		$sql = "INSERT INTO {$table}( {$keys} )VALUES( {$values} )";
		return $this->query($sql);
	}

	public function update($table, $data, $where)
	{
		foreach ($data as $key => $value) {
			$data[$key] = mysql_real_escape_string($value);
		}
		$sets = array();
		foreach ($data as $key => $value) {
			$kstr = '`' . $key . '`';
			$vstr = '\'' . $value . '\'';
			array_push($sets, $kstr . '=' . $vstr);
		}
		$kav = implode(',', $sets);
		$sql = "UPDATE {$table} SET {$kav} WHERE {$where}";
		return $this->query($sql);
	}

	public function checkAccountExists($accountid)
	{
		$field = array('accountid', 'balance');
		$where = 'accountid = ' . $accountid;
		// check account exists or not 
		$result = $this->select('yangch_BANKACCOUNTS', $field, $where);
		return $result;
	}
}

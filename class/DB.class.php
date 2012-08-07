<?php

/*
 * A DB helper class to make connecting to/querying with PDO a little easier.
 */
class DB{
	protected $PDO; // Protected so in var_dump it will not overload your page with data.
	private $tableSalt;
	public $dbName, $makeSQL, $transients;
	
	/**
	 * Used for connecting via PDO to a mysql database.
	 * 
	 * @param	string	user
	 * 					The username for the DB
	 * @param	string	pass
	 * 					The password for the DB
	 * @param	string	dbName
	 * 					The name of the DB
	 * @param	string	host
	 * 					The host for the DB
	 * @param	array	options
	 * 					The options for the DB connection.
	 */ 
	public function __construct($user, $pass=false, $dbName=false, $host='localhost', $options=array()){
		// Set up the default options & merge them into the users ones
		$options = array_replace(array(
			'tableSalt' => 'slt_', // Table salts are appended to the start of a table name to make sql injections harder.
			'transients' => false, // Turn of transients by default, no point adding more memory if not used.
			'driver_options', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING /*, PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true*/) // Turns on errors by default
		), $options);
		
		$this->dbName = $dbName;
		$this->tableSalt = $options['tableSalt'];
		$this->PDO = null;
		
		if($options->transients){
			$options->transients = new Transients($this->PDO);
		}
		
		// Build the Data Source Name
		$dsn = 'mysql:host='.$host.';';
		if($dbName){
			$dsn .= 'dbname='.$dbName.';'; // We might not always be connecting to a created database.
		}
		
		// Now try to connect to MySQL.
		try {
			$this->PDO = new PDO($dsn, $user, $pass, $options['driver_options']);
		} catch(PDOException $e) {
			// If something goes wrong, PDO throws an exception with a nice error message.
			echo 'Connection failed: ' . $e->getMessage();
		}
	}
	
	/*
	 * Makes the table name with the salt.
	 */
	public function getTableName($tableName){
		return '`'.$this->dbName.'`.`'.$this->tableSalt.$tableName.'`';
	}
	
	/*
	 * Creates the database. It the DB exists already, it clears it first. Than it selects it for use.
	 */
	public function createDatabase($dbName=false){
		if($dbName){
			$this->dbName = $dbName;
		}
		
		$this->PDO->exec('CREATE DATABASE IF NOT EXISTS `'.$this->dbName.'`; DROP DATABASE `'.$this->dbName.'`; CREATE DATABASE IF NOT EXISTS `'.$this->dbName.'`;');
		return $this->selectDatabase();
	}
	
	/*
	 * Selects the database for use.
	 */
	public function selectDatabase($dbName=false){
		if($dbName){
			$this->dbName = $dbName;
		}
		
		return $this->PDO->exec('use `'.$this->dbName.'`');
	}
	
	/*
	 * Drop database
	 */
	public function dropDatabase(){
		return $this->PDO->exec('DROP DATABASE `'.$this->dbName.'`;');
	}
	
	/*
	 * Creates a basic table
	 */
	public function createTable($tableName, $tableStructure){
		
	}
}
?>
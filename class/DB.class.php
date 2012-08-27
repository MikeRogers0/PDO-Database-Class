<?php
/*
 * A DB helper class to make connecting to/querying with PDO a little easier.
 */

require_once('MakeSQL.class.php');
require_once('Transients.class.php');

class DB{
	public $tablePrefix, $PDO, $dbName, $makeSQL, $transients, $options;
	
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
		$options = array_merge(array(
			'tablePrefix' => 'pfx_', // Table salts are appended to the start of a table name to make sql injections harder.
			'transients' => false, // Turn of transients by default, no point adding more memory if not used.
			'showErrors' => true // It will throw errors.
		), $options);

		$this->options = $options;
		
		$this->dbName = $dbName;
		
		// Build the Data Source Name
		$dsn = 'mysql:host='.$host.';';
		if($dbName){
			$dsn .= 'dbname='.$dbName.';'; // We might not always be connecting to a created database.
		}

		// Now try to connect to MySQL.
		try{
			$this->PDO = new PDO($dsn, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}catch(PDOException $e){
			echo $e->getMessage();
			return false; 
		}

		if(!$this->options['showErrors']){
			$this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
		}

		if($this->options['transients']){
			$this->transients = new Transients($this->PDO, $this->options['tablePrefix']);
		}
	}
	
	/*
	 * Makes the table name with the prefix.
	 */
	public function getTableName($tableName){
		return '`'.$this->dbName.'`.`'.$this->options['tablePrefix'].$tableName.'`';
	}
	
	/*
	 * Creates the database. It the DB exists already, it clears it first. Than it selects it for use.
	 */
	public function createDatabase(){
		$this->PDO->exec('CREATE DATABASE IF NOT EXISTS `'.$this->dbName.'`; DROP DATABASE `'.$this->dbName.'`; CREATE DATABASE IF NOT EXISTS `'.$this->dbName.'`;');
		$this->selectDatabase();
		return $this;
	}
	
	/*
	 * Selects the database for use.
	 */
	public function selectDatabase(){
		$this->PDO->exec('USE `'.$this->dbName.'`');
		return $this;
	}
	
	/*
	 * Drop database
	 */
	public function dropDatabase(){
		$this->PDO->exec('DROP DATABASE `'.$this->dbName.'`;');
		return $this;
	}
	
	/*
	 * Creates a basic table.
	 *
	 * @param	string	tableName
	 * 					The unprefixed table name.
	 * @param	string	tableStructure
	 * 					The SQL for after the CREATE TABLE `derpy` bit of SQL.
	 *					In future I wanna make this accept an Array instead.
	 */
	public function createTable($tableName, $tableStructure){
		if(is_array($tableStructure)){
			return $this; // Something for the future. 
		}

		$this->PDO->exec('CREATE TABLE  '.$this->getTableName($tableName).' '.$tableStructure);

		return $this;
	}
}
?>
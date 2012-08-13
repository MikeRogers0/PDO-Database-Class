<?php
/*
 * Lets you manage transients
 */
class Transients{
	protected $PDO;
	private $tableName;

	public function __construct($PDO, $tablePrefix){
		$this->PDO = $PDO; // Use the PDO reference.
		$this->tableName = $tablePrefix.'transients';
	}
	
	// Transient functions blew my mind when i saw them on http://codex.wordpress.org/Transients_API
	// Ganna make these at some point.
	public function install(){
		try{
			$this->PDO->exec('CREATE TABLE `'.$this->tableName.'` (
			`ID` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`name` VARCHAR( 255 ) NOT NULL,
			`value` LONGTEXT NOT NULL,
			`expires` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
			) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;');
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public function uninstall(){
		$this->PDO->exec('DROP TABLE `'.$this->tableName.'`');
	}
	
	public function set($name, $value=NULL, $expiration=3600){
		$set = array(
				'name' => $name,
				'value' => serialize($value),
				'expires' => date("Y-m-d H:i:s", time() + $expiration)
			);

		$query = $this->PDO->prepare('INSERT INTO `'.$this->tableName.'` '.MakeSQL::insert($set));
		$query->execute(MakeSQL::bind($set));

		return true;
	}
	
	public function get($name){

		$query = $this->PDO->prepare('SELECT `value` FROM `'.$this->tableName.'` WHERE `name` = :name AND `expires` > CURRENT_TIMESTAMP ORDER BY `expires` DESC LIMIT 0,1;');
		
		$query->execute(array(':name' => $name));
		$result = $query->fetchColumn();

		// If no data.
		if($result === FALSE){
			return FALSE;
		}

		return unserialize($result);
	}
	
	public function delete($name){
		$query = $this->PDO->prepare('DELETE FROM `'.$this->tableName.'` WHERE `name` = :name;');
		$query->execute(array(':name' => $name));
	}
	
	/*
	 * Removes expired transients, should be used in a cron.
	 */
	public function clearExpired(){
		$query = $this->PDO->prepare('DELETE FROM `'.$this->tableName.'` WHERE `expires` < CURRENT_TIMESTAMP;');
		$query->execute();
	}
}
?>
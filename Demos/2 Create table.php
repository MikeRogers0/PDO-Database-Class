<?php
/*
 * How to make a table.
 */

require_once('../class/DB.class.php');
require_once('db-config.inc.php');

// Connect to the DB.
$db = new DB($user, $pass, $dbName, $host, $options);

/*
 * Lets make a table called user. The first argument is the table name, 
 * the second bit is the SQL after the `CREATE TABLE 'table_'` (I copied mine from PHPMyAdmin).
 * The try & catch is for error handling.
 */
try{
	$db->createTable('user', ' (
	`ID` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`name` VARCHAR( 255 ) NOT NULL,
	`site` VARCHAR( 255 ) NULL,
	`bio` TEXT NOT NULL,
	`lastUpdated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;');
}catch(PDOException $e){
	echo $e->getMessage();
}
?>
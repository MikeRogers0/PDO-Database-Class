<?php
/*
 * Delete data from table
 */

require_once('../class/DB.class.php');
require_once('db-config.inc.php');

$db = new DB($user, $pass, $dbName, $host, $options);

// Set up the where query
$where = array(
	'ID' => 1
);

try{
	$query = $db->PDO->prepare('DELETE FROM '.$db->getTableName('user').'  '.MakeSQL::where($where));

	$query->execute(MakeSQL::bind($where));
}catch(PDOException $e){
	echo $e->getMessage();
}

echo 'No errors? Huzzah! It worked.';
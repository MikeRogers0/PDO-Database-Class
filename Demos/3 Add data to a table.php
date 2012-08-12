<?php
/*
 * Add data to a table.
 */

require_once('../class/DB.class.php');
require_once('db-config.inc.php');

$db = new DB($user, $pass, $dbName, $host, $options);

// Set up the user data.
$newUserDate= array(
	'name' => 'Mike',
	'site' => 'http://www.fullondesign.co.uk/',
	'bio' => 'Somehting about Mike!'
);

// Try to add it in.
try{
	// Prepare the statement pretty much generates nice SQL.
	$query = $db->PDO->prepare('INSERT INTO '.$db->getTableName('user').' '.MakeSQL::insert($newUserDate));
	// Than, execute binds the values to the array making SQL injections harder.
	$query->execute(MakeSQL::bind($data));
}catch(PDOException $e){
	echo $e->getMessage();
}

?>
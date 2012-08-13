<?php
/*
 * Select data from a table
 */

require_once('../class/DB.class.php');
require_once('db-config.inc.php');

$db = new DB($user, $pass, $dbName, $host, $options);

// Set up the where query
$where = array(
	'name' => 'Mike'
);

/*
 * Do a simple fetch of all the data from the one table. 
 */
echo 'Do a simple fetch of all the data from the one table. ';
try{
	// Prepare the statement pretty much generates nice SQL.
	$query = $db->PDO->prepare('SELECT * FROM '.$db->getTableName('user').' '.MakeSQL::where($where));
	// Than bind the values to the array making SQL injections harder.
	$query->execute(MakeSQL::bind($where));

	// Fetch all the results.
	$results = $query->fetchAll();

	var_dump($results);
}catch(PDOException $e){
	echo $e->getMessage();
}

/*
 * Select with a limit
 */
echo 'Select with a limit';
try{
	// Prepare the statement pretty much generates nice SQL.
	$query = $db->PDO->prepare('SELECT * FROM '.$db->getTableName('user').' '.MakeSQL::where($where).' LIMIT 0,1');
	// Than bind the values to the array making SQL injections harder.
	$query->execute(MakeSQL::bind($where));

	// Fetch all the results.
	$results = $query->fetchAll();

	var_dump($results);
}catch(PDOException $e){
	echo $e->getMessage();
}
?>
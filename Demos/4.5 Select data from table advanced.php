<?php
/*
 * Select data from a table (Advanced)
 */

require_once('../class/DB.class.php');
require_once('db-config.inc.php');

$db = new DB($user, $pass, $dbName, $host, $options);

// Set up the where query
$where = array(
	'name' => array('Mike', 'Steve') // So this will make WHERE (`name` = 'Mike' OR `name` = 'Steve')
);

/*
 * Select with an OR statment in the WHERE
 */
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
?>
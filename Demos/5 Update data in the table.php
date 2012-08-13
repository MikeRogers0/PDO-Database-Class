<?php
/*
 * Update data in the table
 */

require_once('../class/DB.class.php');
require_once('db-config.inc.php');

$db = new DB($user, $pass, $dbName, $host, $options);

// Set up the where query
$where = array(
	'ID' => 1
);

// Set up the user data.
$updatedUserData= array(
	'name' => 'Mike Rogers',
	'bio' => 'Some better information about me.'
);

try{
	$query = $db->PDO->prepare('UPDATE '.$db->getTableName('user').' '.MakeSQL::update($updatedUserData).' '.MakeSQL::where($where));
	// Because were are using two arrays, we need to merge them into 1 for the bind.
	$mergedArray = array_merge($updatedUserData, $where);

	$query->execute(MakeSQL::bind($mergedArray));
}catch(PDOException $e){
	echo $e->getMessage();
}

echo 'No errors? Huzzah! It worked.';
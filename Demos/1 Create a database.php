<?php
/*
 * How to make a connection with the DB class.
 */

require_once('../class/db.class.php');

// Set up the sql variables,
$user = 'root';
$pass = false;
$dbName = false;
$host = 'localhost';

// Get the DB
$db = new DB($user, $pass, $dbName, $host);

// Create the database called this_demo
if($db->createDatabase('this_demo') !== false){
	echo 'Database created';
}
?>
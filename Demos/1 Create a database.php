<?php
/*
 * How to make a connection with the DB class.
 */

/*
 * Require the DB class. Require kills the page if it can't find the file.
 * _once means if the file is already loaded, it will ignore the require.
 */
require_once('../class/DB.class.php');

// Set up the sql variables,
$user = 'root';
$pass = false;
$dbName = false;
$host = 'localhost';

// Connect to the SQL Server. As $dbName is false, it will not select a database for use.
$db = new DB($user, $pass, $dbName, $host);

// Create the database called this_demo
try{
	$db->dbName = 'this_demo'; // Set the Database name we wanna make.
	$db->createDatabase();
}catch(PDOException $e){ // Any errors will be caught & displayed.
	echo $e->getMessage();
}

echo 'No Errors? Database was made ok than!';
?>
<?php
/*
 * How to make a connection with the DB class.
 */

require_once('../class/db.class.php');

// Set up the sql variables,
$user = 'root';
$pass = false;
$dbName = 'this_demo';
$host = 'localhost';
$options = array('tableSalt' => 'mySlt_');

// Get the DB
$db = new DB($user, $pass, $dbName, $host, $options);

//$db->createTable('demo', );

//$db->PDO->query();
?>
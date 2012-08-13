<?php
/*
 * Using Transients
 */

require_once('../class/DB.class.php');
require_once('db-config.inc.php');

// Small tweak of the options to enable transients.
$options['transients'] = true;

$db = new DB($user, $pass, $dbName, $host, $options);

// Firstly install it.
$db->transients->install();


$db->transients->set('Hello', 'World', 60); // Sets a transient with the name 'hello', the value 'world' which expires in 60 seconds.

var_dump($db->transients->get('Hello')); // returns: string(5) "World"

$db->transients->delete('Hello'); // Deleted all the Transients called 'hello'

$db->transients->clearExpired(); // Clears all the expired transients

// Uninstalls the table from the DB.
$db->transients->uninstall();
?>
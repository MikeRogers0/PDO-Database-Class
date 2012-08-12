<?php
/*
 * This is the example configuration file for the DB.
 */
$user = 'root';
$pass = false;
$dbName = 'this_demo';
$host = 'localhost';

// Add some additonal DB options.
$options = array(
	'tablePrefix' => 'mySlt_',
	'showErrors' => true
);

/*
 * Consider storing your DB details in an .ini file & getting PHP you parse it instead of the above.
 *
	$db = parse_ini_file('db-config.ini', true);
	print_r($db);
 */
?>
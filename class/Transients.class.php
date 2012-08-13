<?php
/*
 * Lets you manage transients
 */
class Transients{
	protected $db;
	
	public function __construct($db){
		$this->db = $db; // Use the PDO reference.
	}
	
	// Transient functions blew my mind when i saw them on http://codex.wordpress.org/Transients_API
	// Ganna make these at some point.
	public function install(){}
	public function uninstall(){}
	
	public function set($name, $value=false, $expiration=3600){}
	
	public function get($name){}
	
	public function delete($name){}
	
	/*
	 * Removes expired transients, should be used in a cron.
	 */
	public function clearExpired(){
		
	}
}
?>
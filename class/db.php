<?php

# This makes it easier for me to do SQL querys by generating bits of SQL / binding data.
class db extends pdo{
	public function createDatabase($name){ // Clear the database first.
		return $this->query('CREATE DATABASE IF NOT EXISTS `'.$name.'`; DROP DATABASE `'.$name.'`; CREATE DATABASE IF NOT EXISTS `'.$name.'`;');
	}
	public function tableName($name){
		return '`'.db_database.'`.`'.db_database_salt.'_'.$name.'`';
	}
	
	// Set up a where statement for PDO from an array
	public function where($keyValue){ 
		if(is_array($keyValue)){ // Should be an array of the fieldNames and the value.
			$sql = 'WHERE ';
			foreach($keyValue as $key => $value){ // cycle though the key => value
				if(is_array($value)){ // if the value is array, lets assume I want an OR statement.
					$sWhere = null;
					$count = -1;
					foreach($value as $sValue){
						$count++;
						$sWhere[] = '`'.$key.'` = :'.$key.$count;
					}
					$where[] = '('.implode(' OR ', $sWhere).')';
				}else {
					$where[] = '`'.$key.'` = :'.$key;
				}
			}
			
			return $sql.implode(' AND ',$where);
		}
		return '';
	}
	
	/**
	 * This makes the SQL Update query - these save coding time + make the product more maintainble / tidy.
	 * 
	 * @param	KeyValue
	 * 			Array of fields as keys with their new values attached.
	 */ 
	public function update($keyValue){ 
		if(is_array($keyValue)){
			$sql = 'SET ';
			foreach($keyValue as $key => $value){
				$update[] = '`'.$key.'` = :'.$key;
			}
			return $sql.implode(' , ',$update);
		}
		return '';
	}
	
	// This makes the SQL insert query
	public function insert($keyValue){ 
		if(is_array($keyValue)){
			foreach($keyValue as $key => $value){
				$fields[] = '`'.$key.'`';
				$values[] = ':'.$key;
			}
			
			return '('.implode(' , ',$fields).') VALUES '.'('.implode(' , ',$values).')';
		}
		return '';
	}
	
	// Change the key to be :key to stop injections
	public function bind($keyValue){
		if(is_array($keyValue)){
			foreach($keyValue as $key => $value){
				if(is_array($value)){ // if the value is array, lets assume I want an OR statement.
					$count = -1;
					foreach($value as $sValue){
						$count++;
						$where[':'.$key.$count] = $sValue;
					}
				} else {
					$where[':'.$key] = $value;
				}
			}
			return $where;
		}
		return array();
	}
	
	// Transient functions blew my mind when i saw them on http://codex.wordpress.org/Transients_API
	// Ganna make these at some point.
	public function installTransient(){}
	
	public function cronTransient(){}
	
	public function setTransient($transient, $value=null, $expiration=3600){}
	
	public function getTransient($transient){}
	
	public function deleteTransient($transient){}
	
	
}
?>
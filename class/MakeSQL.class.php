<?php
/*
 * This is a very lazy way to make parts of SQL queries.
 */
class MakeSQL{
	
	/*
	 * Make the `key` = :key: string for SQL.
	 */
	 public static function keyEqualsKey($key, $value=0){
		if(is_string($key) && $value === 0){
			return '`'.$key.'` = :'.$key;
		} elseif(is_array($key) && $value === 0){
			$i = 0;
			foreach(array_keys($key) as $ke){
				$keys[] = '`'.$ke.'` = :'.$ke;
			}
			return $keys;
		}else{ // for when the bind needs a count.
			$valueCount = count($value);
			for($i = 0; $i < $valueCount; $i++){
				$keys[] = '`'.$key.'` = :'.$key.$i;
			}
			return $keys;
		}
	 }
	
	/*
	 * Binds an array to a query with a prefix. So `key` becomes `:key:` .
	 * 
	 * @param	array	keyValue
	 * 					The values with keys to be binded.
	 */
	public static function bind($keyValue){
		$return = null;
		if(is_array($keyValue)){
			foreach($keyValue as $key => $value){
				if(is_array($value)){ // If the value has sub values, for use in OR statments.
					$count = 0;
					foreach($value as $sValue){
						$return[':'.$key.$count++] = $sValue;
					}
				} else {
					$return[':'.$key] = $value;
				}
			}
		}
		return $return;
	}
	
	// Set up a where statement for PDO from an array
	public static function where($keyValue){ 
		if(is_array($keyValue)){ // Should be an array of the fieldNames and the value.
			$sql = 'WHERE ';
			foreach($keyValue as $key => $value){ // cycle though the key => value
				if(is_array($value)){ // if the value is array, lets assume I want an OR statement.
					$where[] = '('.implode(' OR ', MakeSQL::keyEqualsKey($key, $value)).')';
				}else {
					$where[] = MakeSQL::keyEqualsKey($key);
				}
			}
			
			return $sql.implode(' AND ',$where);
		}
		return '';
	}
	
	/**
	 * This makes the start of a SQL Update query - these save coding time + make the product more maintainble / tidy.
	 * 
	 * @param	KeyValue
	 * 			Array of fields as keys with their new values attached.
	 */ 
	public static function update($keyValue){ 
		if(is_array($keyValue)){
			return 'SET '.implode(' , ', MakeSQL::keyEqualsKey($keyValue));
		}
		return '';
	}
	
	// This makes the SQL insert query
	public static function insert($keyValue){ 
		if(is_array($keyValue)){
			foreach($keyValue as $key => $value){
				$fields[] = '`'.$key.'`';
				$values[] = ':'.$key;
			}
			
			return '('.implode(' , ',$fields).') VALUES '.'('.implode(' , ',$values).')';
		}
		return '';
	}
	
}
?>
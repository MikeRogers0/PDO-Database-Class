<?php
/*
 * This is a very lazy way to make parts of SQL queries.
 */
class MakeSQL{
	
	/*
	 * Make the `key` = :key: string for SQL.
	 */
	 public static function keyEqualsKey($key){
		if(is_string($key)){
			return '`'.$key.'` = :'.$key.':';
		}
		elseif(is_array($key)){ // for when the bind needs a count.
			$key_count = count($key); // cache the count for performance.
			for($i = 1; $i <= $key_count; $i++){
				$keys[] = '`'.$key.'` = :'.$key.$i.':';
			}
			return $keys;
		}
	 }
	
	/*
	 * Binds an array to a query with a prefix. So `key` becomes `:key:` .
	 * 
	 * @param	PDO		query
	 * 					The PDO query.
	 * @param	array	keyValue
	 * 					The values with keys to be binded.
	 */
	public static function bind($query, $keyValue){
		if(is_array($keyValue)){
			foreach($keyValue as $key => $value){
				if(is_array($value)){ // If the value has sub values, for use in OR statments.
					$count = 0;
					foreach($value as $sValue){
						$query->bindParam(':'.$key.$count++.':', $sValue);
					}
				} else {
					$query->bindParam(':'.$key.':', $value);
				}
			}
		}
		return $query;
	}
	
	// Set up a where statement for PDO from an array
	public static function where($keyValue){ 
		if(is_array($keyValue)){ // Should be an array of the fieldNames and the value.
			$sql = 'WHERE ';
			foreach($keyValue as $key => $value){ // cycle though the key => value
				if(is_array($value)){ // if the value is array, lets assume I want an OR statement.
					$where[] = '('.implode(' OR ', $this->keyEqualsKey($key)).')';
				}else {
					$where[] = $this->keyEqualsKey($key);
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
			return 'SET '.implode(' , ', $this->keyEqualsKey(array_keys($keyValue)));
		}
		return '';
	}
	
	// This makes the SQL insert query
	public static function insert($keyValue){ 
		if(is_array($keyValue)){
			foreach($keyValue as $key => $value){
				$fields[] = '`'.$key.'`';
				$values[] = ':'.$key.':';
			}
			
			return '('.implode(' , ',$fields).') VALUES '.'('.implode(' , ',$values).')';
		}
		return '';
	}
	
}
?>
<?php 

require_once('dbmanager.php');

abstract class DBRow
{
	protected static $table_name;
	protected static $primary_keys;
    
    
    public static function select($ids)
    {
    	if(count($ids) != count(static::$primary_keys))
    		return NULL;
    	
    	// check primary key conditions
    	foreach(static::$primary_keys as $pk)
    		if(!isset($ids[$pk]))
    			return NULL;
		
    	// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], $ids);
    	$obj = $conn->getObject(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	
	public static function selectAll()
    {
    	// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name);
    	$obj = $conn->getObjectArray(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	
	public function insert()
    {
    	// check that object primary key is empty
    	foreach(static::$primary_keys as $pk)
    		if(isset($this->{$pk}))
    			return false;
		
    	// insert values
    	$insertValues = [];
    	foreach($this as $field => $value)
    	{
    		if(!in_array($field, static::$primary_keys))
    			$insertValues[$field] = $value;
    	}
		
    	// execute insert
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execInsert(static::$table_name, $insertValues);
    	$count = $conn->getCount();
    	
    	// fill primary key values
    	if($count > 0)
    	{
    		foreach(static::$primary_keys as $pk)
    			$this->{$pk} = $conn->getLastInsertID($pk);
    	}
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $count > 0;
	}
	
	
	public function update()
    {
    	// check that object has values for its primary key
    	foreach(static::$primary_keys as $pk)
    		if(!isset($this->{$pk}))
    			return false;
		
    	// update values which don't belong to primary key
    	$updateValues = [];
    	$whereValues = [];
    	foreach($this as $field => $value)
    	{
    		if(!in_array($field, static::$primary_keys))
    			$updateValues[$field] = $value;
    		else
    			$whereValues[$field] = $value;
    	}
		
    	// execute update
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execUpdate(static::$table_name, $updateValues, $whereValues);
    	$count = $conn->getCount();
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $count > 0;
	}


	public function delete()
    {
    	// check that object has values for its primary key
    	foreach(static::$primary_keys as $pk)
    	if(!isset($this->{$pk}))
    		return false;
    	
    	// create delete condition
    	$whereValues = [];
    	foreach(static::$primary_keys as $pk)
			$whereValues[$pk] = $this->{$pk};
		
		// execute select
		$conn = DBManager::getInstance()->getConnection();
    	$conn->execDelete(static::$table_name, $whereValues);
    	$count = $conn->getCount();
    	
    	DBManager::getInstance()->freeConnection($conn);
    	
    	// invalidate this object
    	if($count > 0)
    		foreach(static::$primary_keys as $pk)
	    		$this->{$pk} = NULL;
    	
    	return $count > 0;
	}
	
	
	public function toJSON()
    {
		return json_encode($this); 
    }
} 
 
?>
<?php 

require_once('dbrow.php');

class Entrant extends DBRow
{
	protected static $table_name = 'entrant';
	protected static $primary_keys = array('ett_id');
	
	public $ett_id, $ett_gawid, $ett_email, $ett_fullname, $ett_fbid;
	
	public static function selectById($id)
    {
    	return static::select(array(static::$primary_keys[0]=>$id));
    }
	
	public static function selectByGawId($ett_gawid)
    {
		// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('ett_gawid'=>$ett_gawid));
    	$obj = $conn->getObjectArray(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	public static function selectByGawIdAndEmail($ett_gawid, $ett_email)
    {
		// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('ett_gawid'=>$ett_gawid, 'ett_email'=>$ett_email));
    	$obj = $conn->getObject(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	public static function selectByGawIdAndFbId($ett_gawid, $ett_fbid)
    {
		// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('ett_gawid'=>$ett_gawid, 'ett_fbid'=>$ett_fbid));
    	$obj = $conn->getObject(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	function __construct($ett_gawid = NULL, $ett_email = NULL, $ett_fullname = NULL, 
						$ett_fbid = NULL)
	{
		if(isset($ett_gawid))
			$this->ett_gawid = $ett_gawid;
		if(isset($ett_email))
			$this->ett_email = $ett_email;
		if(isset($ett_fullname))
			$this->ett_fullname = $ett_fullname;
		if(isset($ett_fbid))
			$this->ett_fbid = $ett_fbid;
	}
} 
 
?>
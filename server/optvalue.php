<?php 

require_once('dbrow.php');

class Optvalue extends DBRow
{
	protected static $table_name = 'optvalue';
	protected static $primary_keys = array('ovl_id');
	
	public $ovl_id, $ovl_optid, $ovl_tag, $ovl_value;
	
	public static function selectById($id)
    {
    	return static::select(array(static::$primary_keys[0]=>$id));
    }
	
	public static function selectByOptId($id)
    {
		// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('ovl_optid'=>$id));
    	$obj = $conn->getObjectArray(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	public static function selectByOptIdAndTag($id, $tag)
    {
		// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('ovl_optid'=>$id, 'ovl_tag'=>$tag));
    	$obj = $conn->getObject(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	function __construct($ovl_optid = NULL, $ovl_tag = NULL, $ovl_value = NULL)
	{
		if(isset($ovl_optid))
			$this->ovl_optid = $ovl_optid;
		if(isset($ovl_tag))
			$this->ovl_tag = $ovl_tag;
		if(isset($ovl_value))
			$this->ovl_value = $ovl_value;
	}
} 
 
?>
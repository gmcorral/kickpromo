<?php 

require_once('dbrow.php');

class Gawext extends DBRow
{
	protected static $table_name = 'gawext';
	protected static $primary_keys = array('gex_gawid', 'gex_extid');
	
	public $gex_gawid, $gex_extid;
	
	public static function selectById($gaw_id, $ext_id)
    {
		// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('gex_gawid'=>$gaw_id, 'gex_extid'=>$ext_id));
    	$obj = $conn->getObject(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	public static function selectByGawId($id)
    {
		// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('gex_gawid'=>$id));
    	$obj = $conn->getObjectArray(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	function __construct($gex_gawid, $gex_extid)
	{
		if(isset($gex_gawid))
			$this->gex_gawid = $gex_gawid;
		if(isset($gex_extid))
			$this->gex_extid = $gex_extid;
	}
} 
 
?>
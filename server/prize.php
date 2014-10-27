<?php 

require_once('dbrow.php');

class Prize extends DBRow
{
	protected static $table_name = 'prize';
	protected static $primary_keys = array('pri_id');
	
	public $pri_id, $pri_gawid, $pri_name, $pri_quantity, $pri_image, $pri_order;
	
	public static function selectById($id)
    {
    	return static::select(array(static::$primary_keys[0]=>$id));
    }
	
	public static function selectByGawId($id)
    {
		// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('pri_gawid'=>$id));
    	$obj = $conn->getObjectArray(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	function __construct($pri_gawid = NULL, $pri_name = NULL, $pri_quantity = NULL, 
						$pri_image = NULL, $pri_order = NULL)
	{
		if(isset($pri_gawid))
			$this->pri_gawid = $pri_gawid;
		if(isset($pri_name))
			$this->pri_name = $pri_name;
		if(isset($pri_quantity))
			$this->pri_quantity = $pri_quantity;
		if(isset($pri_image))
			$this->pri_image = $pri_image;
		if(isset($pri_order))
			$this->pri_order = $pri_order;
	}
} 
 
?>
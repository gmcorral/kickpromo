<?php 

require_once('dbrow.php');
require_once('prize.php');

class Winner extends DBRow
{
	protected static $table_name = 'winner';
	protected static $primary_keys = array('wnn_id');
	
	public $wnn_id, $wnn_ettid, $wnn_gawid, $wnn_priid, $wnn_published, $wnn_discarded, $wnn_date;
	
	public static function selectById($id)
    {
    	return static::select(array(static::$primary_keys[0]=>$id));
    }
	
	public static function selectByPrizeId($id)
    {
    	// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('wnn_priid'=>$id));
    	$obj = $conn->getObjectArray(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
    }
    
	public static function selectByGawId($id)
    {
    	// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('wnn_gawid'=>$id));
    	$obj = $conn->getObjectArray(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
    }
    
	function __construct($wnn_ettid = NULL, $wnn_gawid = NULL, $wnn_priid = NULL, $wnn_published = NULL, $wnn_discarded = NULL, 
						$wnn_date = NULL)
	{
		if(isset($wnn_ettid))
			$this->wnn_ettid = $wnn_ettid;
		if(isset($wnn_gawid))
			$this->wnn_gawid = $wnn_gawid;
		if(isset($wnn_priid))
			$this->wnn_priid = $wnn_priid;
		if(isset($wnn_published))
			$this->wnn_published = $wnn_published;
		if(isset($wnn_discarded))
			$this->wnn_discarded = $wnn_discarded;
		if(isset($wnn_date))
			$this->wnn_date = $wnn_date;
	}
} 
 
?>
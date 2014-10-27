<?php 

require_once('dbrow.php');

class Option extends DBRow
{
	protected static $table_name = 'option';
	protected static $primary_keys = array('opt_id');
	
	public $opt_id, $opt_gawid, $opt_otyid, $opt_points, $opt_daily, $opt_mandatory;
	
	public static function selectById($id)
    {
    	return static::select(array(static::$primary_keys[0]=>$id));
    }
	
	public static function selectByGawId($id)
    {
		// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('opt_gawid'=>$id));
    	$obj = $conn->getObjectArray(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	function __construct($opt_gawid = NULL, $opt_otyid = NULL, $opt_points = NULL, 
						$opt_daily = NULL, $opt_mandatory = NULL)
	{
		if(isset($opt_gawid))
			$this->opt_gawid = $opt_gawid;
		if(isset($opt_otyid))
			$this->opt_otyid = $opt_otyid;
		if(isset($opt_points))
			$this->opt_points = $opt_points;
		if(isset($opt_daily))
			$this->opt_daily = $opt_daily;
		if(isset($opt_mandatory))
			$this->opt_mandatory = $opt_mandatory;
	}
} 
 
?>
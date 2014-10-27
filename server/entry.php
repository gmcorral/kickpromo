<?php 

require_once('dbrow.php');

class Entry extends DBRow
{
	protected static $table_name = 'entry';
	protected static $primary_keys = array('ety_id');
	 
	public $ety_id, $ety_ettid, $ety_optid, $ety_time, $ety_answer, $ety_ip;
	
	public static function selectById($id)
    {
    	return static::select(array(static::$primary_keys[0]=>$id));
    }
	
	public static function selectByEttId($ety_ettid)
    {
		// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('ety_ettid'=>$ety_ettid));
    	$obj = $conn->getObjectArray(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	function __construct($ety_ettid = NULL, $ety_optid = NULL, $ety_time = NULL, 
						$ety_answer = NULL, $ety_ip = NULL)
	{
		if(isset($ety_ettid))
			$this->ety_ettid = $ety_ettid;
		if(isset($ety_optid))
			$this->ety_optid = $ety_optid;
		if(isset($ety_time))
			$this->ety_time = $ety_time;
		if(isset($ety_answer))
			$this->ety_answer = $ety_answer;
		if(isset($ety_ip))
			$this->ety_ip = $ety_ip;
	}
} 
 
?>
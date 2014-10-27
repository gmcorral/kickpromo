<?php 

require_once('dbrow.php');

class Giveaway extends DBRow
{
	protected static $table_name = 'giveaway';
	protected static $primary_keys = array('gaw_id');
	
	public $gaw_id, $gaw_usrid, $gaw_name, $gaw_owner, $gaw_starttime, $gaw_endtime, 
		   $gaw_description, $gaw_terms, $gaw_widgetkey, $gaw_creationdate, $gaw_url,
		   $gaw_closed;
	
	// giveaway state
	const Scheduled = 0;
    const Ongoing = 1;
    const WaitWinner = 3;
    const Finished = 3;
    
	public static function selectById($id)
    {
    	return static::select(array(static::$primary_keys[0]=>$id));
    }
	
	public static function selectByWidgetKey($key)
    {
    	// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('gaw_widgetkey'=>$key));
    	$obj = $conn->getObject(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
    }
    
	public static function selectByUsrId($id)
    {
		// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('gaw_usrid'=>$id));
    	$obj = $conn->getObjectArray(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	function __construct($gaw_usrid = NULL, $gaw_name = NULL, $gaw_owner = NULL, 
						$gaw_starttime = NULL, $gaw_endtime = NULL, $gaw_description = NULL,
						$gaw_terms = NULL, $gaw_widgetkey = NULL, $gaw_creationdate = NULL, 
						$gaw_url = NULL, $gaw_closed = NULL)
	{
		if(isset($gaw_usrid))
			$this->gaw_usrid = $gaw_usrid;
		if(isset($gaw_name))
			$this->gaw_name = $gaw_name;
		if(isset($gaw_owner))
			$this->gaw_owner = $gaw_owner;
		if(isset($gaw_starttime))
			$this->gaw_starttime = $gaw_starttime;
		if(isset($gaw_endtime))
			$this->gaw_endtime = $gaw_endtime;
		if(isset($gaw_description))
			$this->gaw_description = $gaw_description;
		if(isset($gaw_terms))
			$this->gaw_terms = $gaw_terms;
		if(isset($gaw_widgetkey))
			$this->gaw_widgetkey = $gaw_widgetkey;
		if(isset($gaw_creationdate))
			$this->gaw_creationdate = $gaw_creationdate;
		if(isset($gaw_url))
			$this->gaw_url = $gaw_url;
		if(isset($gaw_closed))
			$this->gaw_closed = $gaw_closed;
	}
	
	public function state()
	{
		$currtime = date("Y-m-d H:i:s");
		if ($currtime < $this->gaw_starttime)
			return Giveaway::Scheduled;
		else if ($currtime >= $this->gaw_starttime && $currtime < $this->gaw_endtime)
			return Giveaway::Ongoing;
		if ($currtime >= $this->gaw_endtime)
		{
			if ($this->gaw_closed)
				return Giveaway::Finished;
			else
				return Giveaway::WaitWinner;
		}
	}
} 
 
?>
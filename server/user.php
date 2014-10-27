<?php 

require_once('dbrow.php');

class User extends DBRow
{
	protected static $table_name = 'user';
	protected static $primary_keys = array('usr_id');
	
	public $usr_id, $usr_email, $usr_password, $usr_fullname, $usr_birthdate, 
			$usr_timezone, $usr_regtime, $usr_regip, $usr_lastvisit, $usr_fbid,
			$usr_sessionid;
	
	public static function selectById($id)
    {
    	return static::select(array(static::$primary_keys[0]=>$id));
    }
	
	public static function selectByEmail($email)
    {
    	// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	
    	$conn->execSelect(static::$table_name, [], 
    					array('usr_email' => $email));
    	$obj = $conn->getObject(get_called_class());
    	if($conn->getCount() == 0)
    		$obj = NULL;
    	
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	public static function selectBySessionId()
    {
    	// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	
    	$conn->execSelect(static::$table_name, [], 
    					array('usr_sessionid' => session_id()));
    	$obj = $conn->getObject(get_called_class());
    	if($conn->getCount() == 0)
    		$obj = NULL;
    	
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	function __construct($usr_email = NULL, $usr_password = NULL, $usr_fullname = NULL, 
						$usr_timezone = NULL, $usr_regtime = NULL, $usr_regip = NULL,
						$usr_birthdate = NULL, $usr_fbid = NULL, $usr_sessionid = NULL)
	{
		if(isset($usr_email))
			$this->usr_email = $usr_email;
		if(isset($usr_password))
			$this->usr_password = $usr_password;
		if(isset($usr_fullname))
			$this->usr_fullname = $usr_fullname;
		if(isset($usr_birthdate))
			$this->usr_birthdate = $usr_birthdate;
		if(isset($usr_timezone))
			$this->usr_timezone = $usr_timezone;
		if(isset($usr_regtime))
			$this->usr_regtime = $usr_regtime;
		if(isset($usr_regip))
			$this->usr_regip = $usr_regip;
		if(isset($usr_fbid))
			$this->usr_fbid = $usr_fbid;
		if(isset($usr_sessionid))
			$this->usr_sessionid = $usr_sessionid;
	}
} 
 
?>
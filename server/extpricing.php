<?php 

require_once('dbrow.php');

class Extpricing extends DBRow
{
	protected static $table_name = 'extpricing';
	protected static $primary_keys = array('epr_id');
	 
	public $epr_id, $epr_extid, $epr_locale, $epr_amount, $epr_currency, $ety_ip;
	
	public static function selectById($id)
    {
    	return static::select(array(static::$primary_keys[0]=>$id));
    }
	
	
	public static function selectByExtId($id)
    {
		// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('ext_id'=>$id));
    	$obj = $conn->getObject(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	
	function __construct($epr_extid = NULL, $epr_locale = NULL, $epr_amount = NULL, 
						$epr_currency = NULL, $ety_ip = NULL)
	{
		if(isset($epr_extid))
			$this->epr_extid = $epr_extid;
		if(isset($epr_locale))
			$this->epr_locale = $epr_locale;
		if(isset($epr_amount))
			$this->epr_amount = $epr_amount;
		if(isset($epr_currency))
			$this->epr_currency = $epr_currency;
	}
} 
 
?>
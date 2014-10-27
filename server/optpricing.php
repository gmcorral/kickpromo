<?php 

require_once('dbrow.php');

class Optpricing extends DBRow
{
	protected static $table_name = 'optpricing';
	protected static $primary_keys = array('opr_id');
	
	public $opr_id, $opr_otyid, $opr_locale, $opr_amount, $opr_currency;
	
	public static function selectById($id)
    {
    	return static::select(array(static::$primary_keys[0]=>$id));
    }
	
	public static function selectByOtyId($id)
    {
		// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], array('oty_id'=>$id));
    	$obj = $conn->getObject(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
	}
	
	function __construct($opr_otyid = NULL, $opr_locale = NULL, $opr_amount = NULL, 
						$opr_currency = NULL)
	{
		if(isset($opr_otyid))
			$this->opr_otyid = $opr_otyid;
		if(isset($opr_locale))
			$this->opr_locale = $opr_locale;
		if(isset($opr_amount))
			$this->opr_amount = $opr_amount;
		if(isset($opr_currency))
			$this->opr_currency = $opr_currency;
	}
} 
 
?>
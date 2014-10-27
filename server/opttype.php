<?php 

require_once('dbrow.php');

class Opttype extends DBRow
{
	protected static $table_name = 'opttype';
	protected static $primary_keys = array('oty_id');
	
	public $oty_id, $oty_name, $oty_description, $oty_canbedaily, $oty_canbemandatory;
	
	public static function selectById($id)
    {
    	return static::select(array(static::$primary_keys[0]=>$id));
    }
	
	
	function __construct($oty_name = NULL, $oty_description = NULL, $oty_canbedaily = NULL, 
						$oty_canbemandatory = NULL)
	{
		if(isset($oty_name))
			$this->oty_name = $oty_name;
		if(isset($oty_description))
			$this->oty_description = $oty_description;
		if(isset($oty_canbedaily))
			$this->oty_canbedaily = $oty_canbedaily;
		if(isset($oty_canbemandatory))
			$this->oty_canbemandatory = $oty_canbemandatory;
	}
} 
 
?>
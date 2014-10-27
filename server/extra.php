<?php 

require_once('dbrow.php');

class Extra extends DBRow
{
	protected static $table_name = 'extra';
	protected static $primary_keys = array('ext_id');
	
	public $ext_id, $ext_name, $ext_description;
	
	public static function selectById($id)
    {
    	return static::select(array(static::$primary_keys[0]=>$id));
    }
    
	
	function __construct($ext_name = NULL, $ext_description = NULL)
	{
		if(isset($ext_name))
			$this->ext_name = $ext_name;
		if(isset($ext_description))
			$this->ext_description = $ext_description;
	}
} 
 
?>
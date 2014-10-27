<?php 

require_once('dbrow.php');

class Language extends DBRow
{
	protected static $table_name = 'language';
	protected static $primary_keys = array('lng_id');
	
	public $lng_id, $lng_tag, $lng_locale, $lng_text;
	
	public static function selectById($id)
    {
    	return static::select(array(static::$primary_keys[0]=>$id));
    }
	
	
	public static function selectByTagAndLocale($tag, $locale)
    {
		// execute select
    	$conn = DBManager::getInstance()->getConnection();
    	$conn->execSelect(static::$table_name, [], 
    						array('lng_tag'=>$tag, 'lng_locale'=>$locale));
    	$obj = $conn->getObject(get_called_class());
		
    	DBManager::getInstance()->freeConnection($conn);
    	
    	return $obj;
    }
    
    
	function __construct($lng_tag = NULL, $lng_locale = NULL, $lng_text = NULL)
	{
		if(isset($lng_tag))
			$this->lng_tag = $lng_tag;
		if(isset($lng_locale))
			$this->lng_locale = $lng_locale;
		if(isset($lng_text))
			$this->lng_text = $lng_text;
	}
} 
 
?>
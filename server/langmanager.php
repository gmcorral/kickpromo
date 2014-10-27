<?php 
require_once('language.php');
require_once('configmanager.php');

class LangManager
{
	private $dictionary;
	
	private static $instance;
	
	private function __construct()
	{
		// load dictionary from DB
		ConfigManager::getInstance()->logDebug('Loading translations from DB...');
		$translations = Language::selectAll();
		foreach($translations as $tr)
		{
			$this->dictionary[$tr->lng_locale][$tr->lng_tag] = $tr->lng_text;
			ConfigManager::getInstance()->logDebug('  '.$tr->lng_locale.'-'.$tr->lng_tag."=".$tr->lng_text);
		}
		ConfigManager::getInstance()->logDebug('Done');
	}
    
    public static function getInstance()
    {
    	if(!isset($instance))
    		$instance = new LangManager();
    	return $instance;
    }
    
    public function translate($tag, $locale)
    {
    	$value = $this->dictionary[$locale][$tag];
    	ConfigManager::getInstance()->logDebug('Value='.$value);
    	if(isset($value))
    		return $value;
    	else
    		return $tag;
    }
    
	public function getLocales()
	{
		$locales = [];
		foreach($this->dictionary as $locale)
			$locales[] = $locale;
		return $locales;
	}
}

?>
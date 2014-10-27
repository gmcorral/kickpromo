<?php 
require_once('Klogger.php');

class ConfigManager
{
	private $dbMgr, $dbHost, $dbSchema, $dbUser, $dbPasswd, $dbCharset;
	private $logDir, $logLevel;
	
	private $logger;
	
	private static $instance;
	
	private function __construct()
	{
		// TODO read from file
		$this->dbMgr = 'mysql';
		$this->dbHost = 'localhost';
		$this->dbSchema = 'raffles';
		$this->dbUser = 'root';
		$this->dbPasswd = 'root';
		$this->dbCharset = 'utf8';
		$this->logDir = 'log';
		$this->logLevel = 7;
		
		$this->logger = new KLogger($this->logDir, $this->logLevel);
	}
    
    public static function getInstance()
    {
    	if(!isset($instance))
    		$instance = new ConfigManager();
    	return $instance;
    }
    
    public function logDebug($text, $args = NULL)
    {
    	if(isset($args))
    		$this->logger->logDebug($text, $args);
    	else
	    	$this->logger->logDebug($text);
    }
    
    public function logInfo($text, $args = NULL)
    {
    	if(isset($args))
    		$this->logger->logInfo($text, $args);
    	else
	    	$this->logger->logInfo($text);
    }
    
    public function logError($text, $args = NULL)
    {
    	if(isset($args))
    		$this->logger->logError($text, $args);
    	else
	    	$this->logger->logError($text);
    }
    
    public function getDBMgr()
    {
    	return $this->dbMgr; 
    }
    
    public function getDBHost()
    {
    	return $this->dbHost; 
    }
    
    public function getDBSchema()
    {
    	return $this->dbSchema; 
    }
    
    public function getDBUser()
    {
    	return $this->dbUser; 
    }
    
    public function getDBPasswd()
    {
    	return $this->dbPasswd; 
    }
    
    public function getDBCharset()
    {
    	return $this->dbCharset; 
    }
}

?>
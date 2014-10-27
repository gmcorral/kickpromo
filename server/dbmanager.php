<?php 
require_once('connection.php');
require_once('configmanager.php');

class DBManager
{
	private $dbMgr, $dbHost, $dbSchema, $dbUser, $dbPasswd, $dbCharset;
	private $connPool;
	
	private static $instance;
	
	private function __construct($dbMgr, $dbHost, $dbSchema, $dbUser, $dbPasswd, $dbCharset)
	{
		$this->dbMgr = $dbMgr;
		$this->dbHost = $dbHost;
		$this->dbSchema = $dbSchema;
		$this->dbUser = $dbUser;
		$this->dbPasswd = $dbPasswd;
		$this->dbCharset = $dbCharset;
		$this->connPool = [];
	}
    
    public static function getInstance()
    {
    	if(!isset($instance))
    		$instance = new DBManager(	ConfigManager::getInstance()->getDBMgr(),
							    		ConfigManager::getInstance()->getDBHost(),
							    		ConfigManager::getInstance()->getDBSchema(),
							    		ConfigManager::getInstance()->getDBUser(),
							    		ConfigManager::getInstance()->getDBPasswd(),
							    		ConfigManager::getInstance()->getDBCharset());
		
    	return $instance;
    }
    
    public function getConnection()
    {
    	// find available connectiom
    	/*foreach($connPool as $conn)
    		if(!$conn->isLocked())
    		{
    			$conn->setLocked(true);
				return $conn;
    		}
    	*/
    	// if no connection is available, create a new one
		$conn = new Connection($this->dbMgr, $this->dbHost, $this->dbSchema, 
								$this->dbUser, $this->dbPasswd, $this->dbCharset);
		$conn->setLocked(true);
		//$connPool[] = $conn;
		
		return $conn;
    }
    
    public function freeConnection($conn)
    {
		$conn->setLocked(false); 
    }
}
 
?>
<?php 

require_once('configmanager.php');

class Connection
{
	private $isLocked;
	private $pdo, $sentence;
	
	
	function __construct($dbMgr, $dbHost, $dbSchema, $dbUser, $dbPasswd, $dbCharset)
	{
		try
		{
			$this->pdo = new PDO($dbMgr.':host='.$dbHost.';dbname='.$dbSchema, $dbUser, $dbPasswd);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e)
		{
    		ConfigManager::getInstance()->logError('SQL connection error: '.$e->getMessage());
		}

		if(isset($dbCharset))
    		$this->pdo->exec("SET CHARACTER SET ".$dbCharset);
	    $this->sentence = null;
	}
    
    
    public function setLocked($locked)
    {
    	$this->sentence = null;
		if($this->isLocked == $locked)
		{
			return false;
		}
		else
		{
			$this->isLocked = $locked;
			return true;
		}
    }
    
    
    public function isLocked()
    { 
		return $this->isLocked;
    }
    
    
    public function execSelect($table, $selectCols = array(), $whereConds = array(), 
    							$whereSep = 'AND', $orderBy = NULL)
    {
    	// select clause
    	if(count($selectCols) == 0)
    	{
			$sql = 'SELECT *';
		}
		else
		{
			$sql = 'SELECT';
			$firstCol = true;
			
			foreach($selectCols as $col)
			{
				if(!$firstCol)
					$sql .= ',';
				
				$sql .= ' '.$col;
				$firstCol = false;
			}
		}
		
		// from clause
		$sql .= ' FROM `'.$table.'`';
		
		// where clause
		$queryValues=[];
		if(count($whereConds) > 0)
    	{
			$sql .= ' WHERE';
			$firstCond = true;
			
			foreach($whereConds as $field => $value)
			{
				if(!$firstCond)
					$sql .= ' '.$whereSep;
				
				$sql .= ' `'.$field.'` = :'.$field;
				$queryValues[':'.$field] = $value;
				
				$firstCond = false;
			}
		}
				
		// order by clause
		if(isset($orderBy))
		{
			$sql .= ' ORDER BY '.$orderBy;
		}
		
		// execute query
		try
		{
			$this->sentence = $this->pdo->prepare($sql);
			$this->sentence->execute($queryValues);
		}
		catch (PDOException $e)
		{
    		ConfigManager::getInstance()->logError('SQL select error: '.$e->getMessage());
		}
		
		ConfigManager::getInstance()->logDebug($sql.' returned '.$this->sentence->rowCount().' row(s)');
	}
	
	
	public function execInsert($table, $insertValues)
    {
    	// insert clause
		$sql = 'INSERT INTO `'.$table.'`';
		
		// fields clause
		$firstVal = true;
		$fields = ' (';
		$values = ' VALUES (';
		$queryValues = [];
		foreach($insertValues as $field => $value)
		{
			if(!$firstVal)
			{
				$fields = $fields.',';
				$values = $values.',';
			}
			
			$fields .= ' `'.$field.'`';
			$values .= ' :'.$field;
			$queryValues[':'.$field] = $value;
			
			$firstVal = false;
		}
		$fields .= ')';
		$values .= ')';
		
		$sql .= $fields.$values;
		
		// execute query
		try
		{
			$this->sentence = $this->pdo->prepare($sql);
			$this->sentence->execute($queryValues);
		}
		catch (PDOException $e)
		{
    		ConfigManager::getInstance()->logError('SQL insert error: '.$e->getMessage());
		}
		
		ConfigManager::getInstance()->logDebug($sql.' affected '.$this->sentence->rowCount().' row(s)');
	}
	
	
	public function execUpdate($table, $updateValues, $whereConds = array(), $whereSep = 'AND')
    {
    	// update clause
		$sql = 'UPDATE `'.$table.'` SET ';
		$queryValues = [];
		
		// set clause
		$firstVal = true;
		foreach($updateValues as $field => $value)
		{
			if(!$firstVal)
				$sql .= ',';
			
			$sql .= ' `'.$field.'` =  :u'.$field;
			$queryValues[':u'.$field] = $value;
			
			$firstVal = false;
		}
		
		// where clause
		if(count($whereConds) > 0)
    	{
			$sql .= ' WHERE';
		
			$firstCond = true;
			foreach($whereConds as $field => $value)
			{
				if(!$firstCond)
					$sql .= ' '.$whereSep;
				
				$sql .= ' `'.$field.'` = :w'.$field;
				$queryValues[':w'.$field] = $value;
				
				$firstCond = false;
			}
		}
		
		// execute query
		try
		{
			$this->sentence = $this->pdo->prepare($sql);
			$this->sentence->execute($queryValues);
		}
		catch (PDOException $e)
		{
    		ConfigManager::getInstance()->logError('SQL update error: '.$e->getMessage());
		}
		
		ConfigManager::getInstance()->logDebug($sql.' affected '.$this->sentence->rowCount().' row(s)');
	}
	
	
	public function execDelete($table, $whereConds = array(), $whereSep = 'AND')
    {
    	// update clause
		$sql = 'DELETE FROM `'.$table.'`';
		
		// where clause
		if(count($whereConds) > 0)
    	{
			$sql .= ' WHERE';
		
			$queryValues = [];
			$firstCond = true;
			
			foreach($whereConds as $field => $value)
			{
				if(!$firstCond)
					$sql .= ' '.$whereSep;
				
				$sql .= ' `'.$field.'` = :'.$field;
				$queryValues[':'.$field] = $value;
				
				$firstCond = false;
			}
		}
		
		// execute query
		try
		{
			$this->sentence = $this->pdo->prepare($sql);
			$this->sentence->execute($queryValues);
		}
		catch (PDOException $e)
		{
    		ConfigManager::getInstance()->logError('SQL delete error: '.$e->getMessage());
		}
		
		ConfigManager::getInstance()->logDebug($sql.' affected '.$this->sentence->rowCount().' row(s)');
	}
	
	
	public function getLastInsertID($name = NULL)
    {
    	if(isset($this->pdo))
    		return $this->pdo->lastInsertId($name);
    	else
    		return false;
    }
    
    
	public function getCount()
    {
    	if(isset($this->sentence))
    		return $this->sentence->rowCount();
    	else
    		return false;
    }
    
    
	public function getNext()
    {
    	if(isset($this->sentence))
    		return $this->sentence->fetch();
    	else
    		return false;
    }


	public function getAll()
    {
    	if(isset($this->sentence))
    		return $this->sentence->fetchAll();
    	else
    		return false;
    }
    
    
	public function getObject($className)
    {
    	if(isset($this->sentence))
    		return $this->sentence->fetchObject($className);
    	else
    		return false;
    }
    
    
    public function getObjectArray($className)
    {
	    $result = [];
    	if(isset($this->sentence))
    	{
			while($obj = $this->sentence->fetchObject($className))
				$result[] = $obj;
    	}
    	return $result;
    }
} 
 
?>
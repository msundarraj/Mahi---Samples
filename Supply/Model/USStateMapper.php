<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';

class EP_Model_USStateMapper extends EP_Mapper
{
	public function existsByAbbrev($abbrev)
	{
		$db = $this->getDatabaseConnection();
		$sql = "SELECT EXISTS (SELECT 1 FROM usstates WHERE abbrev = ?)";
		$sth = $db->prepare($sql);
		$sth->execute(array($abbrev));
		$exists = $sth->fetchColumn(0);
		return $exists;
	}

       /**
	  @return all us sates
	*/
	public function fetchStates()
	{
		$db = EP_Util_Database::pdo_connect();
 
		$sql = "SELECT abbrev,name FROM usstates order by abbrev";
		$sth = $db->prepare($sql);
		if($this->getUsePublicVars())
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		
		$sth->execute();
		return $sth->fetchAll();
	}

      
}

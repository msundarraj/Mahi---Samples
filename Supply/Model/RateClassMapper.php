<?php
require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Model/RateClass.php';
/**
 * DateMapper for RateClass model
 */
class EP_Model_RateClassMapper extends EP_Mapper 
{
	/**
	   @param state_id id (states.id) of state
	   @return all rateclass entries application to that state.
	*/
	public function fetchByState($state_id)
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT `group`, description, distrib FROM rateclass JOIN utility2 ON rateclass.distrib = utility2.code WHERE state = ?";
		$sth = $db->prepare($sql);
		if($this->getUsePublicVars())
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_RateClass' );
		}
		$sth->execute(array($state_id));
		return $sth->fetchAll();
	}

	/**
	   @param $distrib utility2.code, corresponding to distrib here
	   @param $group group column
	   @return boolean indicating whether row exists
	*/
	public function existsByDistribAndGroup($distrib, $group)
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT EXISTS (SELECT 1 FROM rateclass WHERE distrib = ? AND `group` = ? )";
		$sth = $db->prepare($sql);
		$sth->execute(array($distrib, $group));
		return $sth->fetchColumn(0);
	}
}

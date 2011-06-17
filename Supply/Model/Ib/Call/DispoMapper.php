<?php 

require_once dirname(__FILE__) . '/../../../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Util/Database.php';
require_once 'EP/Model/Ib/Call/Dispo.php';

/**
 * 
 * Data Mapper for Dispo model 
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Ib_Call_DispoMapper extends EP_Mapper 
{
	function fetch( $id, $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM ib_call_dispos ";
		$sql .= "WHERE id = ? ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Ib_Call_Dispo' );
		}

		$sth->execute( array( $id ));
		return $sth->fetch();
	}

	function fetchAllNotAutoDispo( $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM ib_call_dispos ";
		$sql .= " WHERE auto_dispo = 0 ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Ib_Call_Dispo' );
		}

		$sth->execute();
		return $sth->fetchAll();
	}
	
	function fetchAll( $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM ib_call_dispos ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Ib_Call_Dispo' );
		}

		$sth->execute();
		return $sth->fetchAll();
	}
}



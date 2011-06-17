<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Model/Utility.php';

/**
 * 
 * Data Mapper for Utility model 
 *
 */
class EP_Model_UtilityMapper extends EP_Mapper 
{
	// 					$util_sql = "select * from utility2 where code='$util'";
	public  function fetchByCode( $code, $fields = array( '*' ) )
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM utility2 ";
		$sql .= " WHERE code = :code ";
		$sql .= " AND active = 1 ";

		$sth = $db->prepare( $sql );

		$sth->bindParam( ':code', $code, PDO::PARAM_STR, 3 );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Utility' );
		}

		$sth->execute( );
		$result = $sth->fetch();
		return $result;		
	}
	
	public function fetchByTdsp( $tdsp, $fields = array( '*' ) )
	{
		$props = EP_Model_Utility::getAllClassProperties( 'EP_Model_Utility' ) ;
		
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM utility2 ";
		$sql .= " WHERE ";
		$sql .= " active = 1 ";
		$sql .= " AND tdspduns = :tdsp ";

		$sth = $db->prepare( $sql );

		// $sth->bindParam( ':state', $state, PDO::PARAM_INT );
		$sth->bindParam( ':tdsp', $tdsp, PDO::PARAM_STR );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Utility' );
		}

		$sth->execute( );
		$result = $sth->fetch();
		return $result;
		
	}

	/**
	   @param $Service_State_Id service state id, from states table
	   @param $fields array of fields to access, defaults *
	   @param $orderBy ordering, defaults utility ASC
	   @param $commodity, id from commodity table, defaults to 1 (Electric)
	*/
	public function fetchByStateID($Service_State_Id, $fields = array('*'), $orderBy = array( 'utility' => 'ASC' ), $commodity = 1 )
	{
		$props = EP_Model_Utility::getAllClassProperties( 'EP_Model_Utility' ) ;
		
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM utility2 ";
		$sql .= " WHERE state = :state ";
		$sql .= " AND commodity = :commodity ";
		$sql .= " AND active = 1 ";
		$sql .= "ORDER BY " ;
		
		foreach ( $orderBy as $col => $dir )
		{
			if ( in_array( $col, $props ) && in_array( strtoupper( $dir ), array('ASC', 'DESC')))
			{
				$sql .= "$col $dir , ";
			}
		}
	
		$sql = trim( $sql, " ," );
		
		// if the params are bad, we remove the "ORDER BY ";
		// this is weird, should be fixed above, no time right now
		$sql = trim( $sql, "ORDER BY " );

		$sth = $db->prepare( $sql );

		$sth->bindParam( ':state', $Service_State_Id, PDO::PARAM_INT);
		$sth->bindParam( ':commodity', $commodity, PDO::PARAM_INT);
	
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Utility' );
		}

		$sth->execute( );
		$arr = $sth->fetchAll();
		return $arr;	
	}
	
	public function fetchAllByState( $stateId, $fields = array( '*' ), $orderBy = array( 'utility' => 'ASC' ) )
	{
		$props = EP_Model_Utility::getAllClassProperties( 'EP_Model_Utility' ) ;
		
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM utility2 ";
		$sql .= " WHERE state = :state ";
		$sql .= " AND active = 1 ";
		$sql .= "ORDER BY " ;
		
		foreach ( $orderBy as $col => $dir )
		{
			if ( in_array( $col, $props ) && in_array( strtoupper( $dir ), array('ASC', 'DESC')))
			{
				$sql .= "$col $dir , ";
			}
		}
	
		$sql = trim( $sql, " ," );
		
		// if the params are bad, we remove the "ORDER BY ";
		// this is weird, should be fixed above, no time right now
		$sql = trim( $sql, "ORDER BY " );

		$sth = $db->prepare( $sql );

		$sth->bindParam( ':state', $stateId, PDO::PARAM_INT);
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Utility' );
		}

		$sth->execute( );
		$arr = $sth->fetchAll();
		return $arr;
	}

	
	public function fetchAll( $fields = array( '*' ), $orderBy = array( 'id' => 'ASC' ) )
	{
		$props = EP_Model_Utility::getAllClassProperties( 'EP_Model_Utility' ) ;
		
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM utility2 ";
		$sql .= "ORDER BY " ;
		
		foreach ( $orderBy as $col => $dir )
		{
			if ( in_array( $col, $props ) && in_array( strtoupper( $dir ), array('ASC', 'DESC')))
			{
				$sql .= "$col $dir , ";
			}
		}
	
		$sql = trim( $sql, " ," );
		
		// if the params are bad, we remove the "ORDER BY ";
		// this is weird, should be fixed above, no time right now
		$sql = trim( $sql, "ORDER BY " );

		$sth = $db->prepare( $sql );

		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Utility' );
		}

		$sth->execute( );
		$arr = $sth->fetchAll();
		return $arr;
	}


	
}





<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Model/State.php';

/**
 * 
 * Data Mapper for State model 
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_StateMapper extends EP_Mapper 
{
	function fetch( $id, $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM states ";
		$sql .= "WHERE active = 1 ";
		$sql .= "AND id = ? ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_State' );
		}

		$sth->execute( array( $id ));
		$result = $sth->fetch();
		return $result;		
	}

	/**
	   @param $abbrev states.abbrev
	   @param $fields array of fields to select

	   @return EP_Model_State, or object
	*/
	public function fetchByAbbrev($abbrev, $fields = array('*'))
	{
		$db = EP_Util_Database::pdo_connect();
		$fields_str = implode(',', $fields);
		$sql = <<< EOF
			SELECT $fields_str
			FROM states
			WHERE active = 1
			AND abbrev = UPPER(?)
			LIMIT 1
EOF;
		$sth = $db->prepare($sql);
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_State' );
		}
		$sth->execute(array($abbrev));
		$result = $sth->fetch();
		return $result;
	}
	
	
	function fetchAll( $fields = array('*'), $orderBy = array('name'), $active = 1 )
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM states ";
		$sql .= "WHERE active = ? AND abbrev IN (SELECT abbrev from usstates)"; // abbrev part is hack for Texas Fixed Price.
		$sql .= "ORDER BY " . implode( ',', $orderBy );	
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_State' );
		}

		$sth->execute( array( $active ));
		$arr = $sth->fetchAll();
		return $arr;
	}
}





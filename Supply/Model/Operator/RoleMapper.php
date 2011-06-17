<?php 

require_once dirname(__FILE__) . '/../../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Model/Operator/Role.php';

/**
 * 
 * Data Mapper for Operator model
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Operator_RoleMapper extends EP_Mapper 
{
	function fetch( $id, $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM operator_roles ";
		$sql .= "WHERE id = ? ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Operator_Role' );
		}

		$sth->execute( array( $id ));
		$arr = $sth->fetch();
		return $arr;
	}
	
	function fetchAllIn( $in, $fields = array('*'), $orderBy = array('name') )
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM operator_roles ";
		$sql .= " WHERE id IN ( " . implode( ',', $in ) . ") ";
		$sql .= "ORDER BY " . implode( ',', $orderBy );	
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Operator_Role' );
		}

		$sth->execute();
		$arr = $sth->fetchAll();
		return $arr;
	}
	
	function fetchAll( $fields = array('*'), $orderBy = array('name') )
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM operator_roles ";
		$sql .= "ORDER BY " . implode( ',', $orderBy );	
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Operator_Role' );
		}

		$sth->execute();
		$arr = $sth->fetchAll();
		return $arr;
	}
	
	function fetchByOperator( $operator_id )
	{
		$sql = "SELECT a.* FROM operator_roles a, operators b, operators_roles_asgn c ";
		$sql .= "WHERE c.operator_id = b.id AND c.operator_role_id = a.id AND b.id = ? ";
		
		$db = EP_Util_Database::pdo_connect();
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Operator_Role' );
		}

		$sth->execute( array( $operator_id ));
		$arr = $sth->fetchAll();
		return $arr;
	}
}





<?php 

require_once dirname(__FILE__) . '/../../../../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Util/Database.php';
require_once 'EP/Model/Ib/Call/Dispo/EnhancementsAsgn.php';

/**
 * 
 * Data Mapper for EnhancementsAsgn model 
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Ib_Call_Dispo_EnhancementsAsgnMapper extends EP_Mapper 
{

	function fetch( $id, $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM ib_call_dispo_enhancements_asgn ";
		$sql .= "WHERE id = ? ";
	
		$sth = $db->prepare( $sql );
	
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Ib_Call_Dispo_EnhancementsAsgn' );
		}

		$sth->execute( array( $id ));
		return $sth->fetch();
	}
	
	function fetchAll( $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM ib_call_dispo_enhancements_asgn";
		
		$sth = $db->prepare( $sql );
	
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Ib_Call_Dispo_EnhancementsAsgn' );
		}

		$sth->execute();
		return $sth->fetchAll();
	}
	
		/**
	 * 
	 * Save the record to the db
	 * @param object $obj
	 */
	public function save( $obj )
	{	
		if ( $obj->hasMessages() )
		{
			return false;
		}
		
		$date = date( 'Y-m-d H:i:s' );
		
		$type = 'insert';
			
		$sql = "INSERT INTO ib_call_dispo_enhancements_asgn VALUES ( null, ";
		$sql .= ":call_dispo_asgn_id, :call_dispo_enhancement_id, :date_created )";
	
		$db = EP_Util_Database::pdo_connect();
		$sth = $db->prepare( $sql );
// print_r( $sth->errorInfo() );
// print_r( $db->errorInfo() );
	   	
		if ( is_null( $obj->getDateCreated() ))
		{
			$obj->setDateCreated( $date );
			$sth->bindParam( ':date_created', $obj->getDateCreated(), PDO::PARAM_STR, 45 );
		}
		
	    $sth->bindParam( ':call_dispo_asgn_id', $obj->getCallDispoAsgnId(), PDO::PARAM_INT);
	    $sth->bindParam( ':call_dispo_enhancement_id', $obj->getCallDispoEnhancementId(), PDO::PARAM_INT);
		
		$result = $sth->execute();
// print_r( $db->errorInfo() );
// print_r( $sth->errorInfo() );		
		if ( $result == 1 )
		{
			if ( $type == 'insert' )
			{
				$obj->setId( (int)$db->lastInsertId() );
			}
			return true;
		}    
		return false;
	}
	
	
}


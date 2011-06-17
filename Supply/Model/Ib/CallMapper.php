<?php 

require_once dirname(__FILE__) . '/../../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Util/Database.php';
require_once 'EP/Model/Ib/Call.php';
require_once 'EP/Model/Ib/CallXRegister.php';
require_once 'EP/Model/Ib/CallXRegisterMapper.php';

/**
 * 
 * Data Mapper for Call model 
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Ib_CallMapper extends EP_Mapper 
{

	function fetchByUid( $id, $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM ib_calls ";
		$sql .= "WHERE uid = ? ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Ib_Call' );
		}

		$sth->execute( array( $id ));
		return $sth->fetch();
	}
	
	function fetch( $id, $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM ib_calls ";
		$sql .= "WHERE id = ? ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Ib_Call' );
		}

		$sth->execute( array( $id ));
		return $sth->fetch();
	}
	
	/**
	 * 
	 * Save the cal record to the db
	 * @param object $call
	 */
	public function save( $call )
	{	
		$date = date( 'Y-m-d H:i:s' );
		
		if ( $call->getId() > 0 )
		{
			$type = 'update';
		}
		else
		{
			$type = 'insert';
		}
		
		if ( is_null( $call->getDateCreated() ))
		{
			$call->setDateCreated( $date );
		}
		
		if ( $call->hasMessages() )
		{
			return false;
		}
		
		$db = $this->getDatabaseConnection();
		
		if ( $type == 'insert' )
		{
		    $sql = "INSERT INTO ib_calls ( ext_cust_id, vendor_id, username, ";
		    $sql .= "rep_id, cust_phone, source_phone, date_created, date_ended ";
		    $sql .= " ) VALUES ( ";
		    $sql .= ":ext_cust_id, ";
		    $sql .= ":vendor_id, ";
		    $sql .= ":username, ";
		    $sql .= ":rep_id, ";
		    $sql .= ":cust_phone, ";
		    $sql .= ":source_phone, ";
		    $sql .= ":date_created, ";
		    $sql .= ":date_ended ";
		    $sql .= ")";
		}
		else 
		{
		    $sql = "UPDATE ib_calls ";
		    $sql .= "SET ";
		    $sql .= "ext_cust_id=:ext_cust_id, ";
		    $sql .= "vendor_id=:vendor_id, ";
		    $sql .= "username=:username, ";
		    $sql .= "rep_id=:rep_id, ";
		    $sql .= "cust_phone=:cust_phone, ";
		    $sql .= "source_phone=:source_phone, ";
		    $sql .= "date_created=:date_created, ";
		    $sql .= "date_ended=:date_ended ";
		    $sql .= "WHERE id = :id ";		
		}

	    $sth = $db->prepare( $sql );

		$sth->bindParam(':ext_cust_id', $call->getExtCustId(), PDO::PARAM_STR, 32 );
		$sth->bindParam(':vendor_id', $call->getVendorId(), PDO::PARAM_INT);
		$sth->bindParam(':username', $call->getUsername(), PDO::PARAM_STR, 40 );
		$sth->bindParam(':rep_id', $call->getRepId(), PDO::PARAM_INT );
		$sth->bindParam(':cust_phone', $call->getCustPhone(), PDO::PARAM_STR, 10 );
		$sth->bindParam(':source_phone', $call->getSourcePhone(), PDO::PARAM_STR, 10 );
		$sth->bindParam(':date_created', $call->getDateCreated(), PDO::PARAM_STR );
		$sth->bindParam(':date_ended', $call->getDateEnded(), PDO::PARAM_STR );

		if ( $type == 'update' )
		{
			$sth->bindParam(':id', $call->getId(), PDO::PARAM_INT );
		}
		
		$result = $sth->execute();
// print_r( $db->errorInfo() );
// print_r( $sth->errorInfo() );

		if ( $result == 1 )
		{
			if ( $type == 'insert' )
			{
				$call->setId( $db->lastInsertId() );
			}
			return true;
		}
		return false;
	}
}


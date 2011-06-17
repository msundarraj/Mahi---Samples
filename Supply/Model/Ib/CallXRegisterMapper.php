<?php 

require_once dirname(__FILE__) . '/../../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Util/Database.php';
require_once 'EP/Model/Ib/CallXRegister.php';

/**
 * 
 * Data Mapper for Call model 
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Ib_CallXRegisterMapper extends EP_Mapper 
{

	function fetch( $id, $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM ib_calls_x_register ";
		$sql .= "WHERE id = ? ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Ib_CallXRegister' );
		}

		$sth->execute( array( $id ));
		return $sth->fetch();
	}

	function fetchByCallId( $id, $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM ib_calls_x_register ";
		$sql .= "WHERE call_id = ? ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Ib_CallXRegister' );
		}

		$sth->execute( array( $id ));
		return $sth->fetchAll();
	}
	
	function fetchByUid( $id, $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM ib_calls_x_register ";
		$sql .= "WHERE uid = ? ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Ib_CallXRegister' );
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
		    $sql = "INSERT INTO ib_calls_x_register ( call_id, uid, reason, call_dispo_id, ";
		    $sql .= "call_dispo_enhancement_id, end_status_id, date_created, date_ended ";
		    $sql .= " ) VALUES ( ";
		    $sql .= ":call_id, ";
		    $sql .= ":uid, ";
		    $sql .= ":reason, ";
		    $sql .= ":call_dispo_id, ";
		    $sql .= ":call_dispo_enhancement_id, ";
		    $sql .= ":end_status_id, ";
		    $sql .= ":date_created, ";
		    $sql .= ":date_ended ";
		    $sql .= ")";
		}
		else 
		{
		    $sql = "UPDATE ib_calls_x_register ";
		    $sql .= "SET ";
		    $sql .= "call_id=:call_id, ";
		    $sql .= "uid=:uid, ";
		    $sql .= "reason=:reason, ";
		    $sql .= "call_dispo_id=:call_dispo_id, ";
		    $sql .= "call_dispo_enhancement_id=:call_dispo_enhancement_id, ";
		    $sql .= "end_status_id=:end_status_id, ";
		    $sql .= "date_created=:date_created, ";
		    $sql .= "date_ended=:date_ended ";
		    $sql .= "WHERE id = :id ";		
		}
// echo $sql;
	    $sth = $db->prepare( $sql );
// print_r( $db->errorInfo() );
// print_r( $sth->errorInfo() );
		$sth->bindParam(':call_id', $call->getCallId(), PDO::PARAM_INT);
		$sth->bindParam(':uid', $call->getUid(), PDO::PARAM_STR );
		$sth->bindParam(':reason', $call->getReason(), PDO::PARAM_STR);
		$sth->bindParam(':call_dispo_id', $call->getCallDispoId(), PDO::PARAM_INT );
		$sth->bindParam(':call_dispo_enhancement_id', $call->getCallDispoEnhancementId(), PDO::PARAM_INT  );
		$sth->bindParam(':end_status_id', $call->getEndStatusId(), PDO::PARAM_INT  );
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


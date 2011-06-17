<?php 

require_once dirname(__FILE__) . '/../../../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Util/Database.php';
require_once 'EP/Model/Ib/Call/DisposAsgn.php';

/**
 * 
 * Data Mapper for Dispo model 
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Ib_Call_DisposAsgnMapper extends EP_Mapper 
{
	function fetch( $id, $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM ib_calls_dispos_asgn ";
		$sql .= "WHERE id = ? ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Ib_Call_DisposAsgn' );
		}

		$sth->execute( array( $id ));
		return $sth->fetch();
	}

	function fetchAll( $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM ib_calls_dispos_asgn ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Ib_Call_DisposAsgn' );
		}

		$sth->execute();
		return $sth->fetchAll();
	}
	
	/*
	 * 
	        `id` INT UNSIGNED AUTO_INCREMENT,
        `call_id` INT UNSIGNED NOT NULL,
        `dispo_id` INT UNSIGNED NOT NULL,
        `reason` TEXT,
        `date_created` DATETIME NOT NULL,
	 */
	
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
		
		if ( $obj->getId() > 0 )
		{
			$type = 'update';
			$sql = "UPDATE ib_calls_dispos_asgn ";
			$sql .= "SET call_id=:call_id, call_dispo_id=:call_dispo_id, reason=:reason ";
			$sql .= "WHERE id = :id ";
		}
		else
		{
			$type = 'insert';
			
			$sql = "INSERT INTO ib_calls_dispos_asgn VALUES ( null, ";
			$sql .= ":call_id, :call_dispo_id, :reason, :date_created )";
			
		}
		
		$db = $this->getDatabaseConnection();
		$sth = $db->prepare( $sql );
// print_r( $sth->errorInfo() );
// print_r( $db->errorInfo() );
		   	
		if ( $type == 'update' )
	   	{
	   		$sth->bindParam( ':id', $obj->getId(), PDO::PARAM_INT);
	   	}

	   	
		if ( is_null( $obj->getDateCreated() ))
		{
			$obj->setDateCreated( $date );
			$sth->bindParam( ':date_created', $obj->getDateCreated(), PDO::PARAM_STR, 45 );
		}
		
	    $sth->bindParam( ':call_id', $obj->getCallId(), PDO::PARAM_INT);
	    $sth->bindParam( ':call_dispo_id', $obj->getCallDispoId(), PDO::PARAM_INT);
	    $sth->bindParam( ':reason', $obj->getReason(), PDO::PARAM_STR );
		
		$result = $sth->execute();
		
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



<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Model/Lead.php';

/**
 * 
 * Data Mapper for Lead model 
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_LeadMapper extends EP_Mapper 
{
	/*
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
	
	*/
	
	function fetchAllCount( )
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT count(*) AS counter ";
		$sql .= " FROM leads ";
		
		$sth = $db->prepare( $sql );
		$sth->setFetchMode( PDO::FETCH_OBJ );

		$sth->execute( );
		$result = $sth->fetch();
		if ( $result )
		{
			return $result->counter;
		}
		return false;
	}
		
	function fetchAll( $fields = array( '*' ), $orderBy = array( 'id' => 'ASC' ) )
	{
		$props = EP_Model_Lead::getAllClassProperties( 'EP_Model_Lead' ) ;
		
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM leads ";
		// $sql .= "ORDER BY " . implode( ',', $orderBy );	
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
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Lead' );
		}

		$sth->execute( );
		$arr = $sth->fetchAll();
		return $arr;
	}

		
	/**
	 * 
	 * Save the record to the db
	 * @param object $obj
	 */
	public function save( $obj )
	{	
		$date = date( 'Y-m-d H:i:s' );
		
		if ( $obj->getId() > 0 )
		{
			$type = 'update';
		}
		else
		{
			$type = 'insert';
		}
		
		if ( is_null( $obj->getDateCreated() ))
		{
			$obj->setDateCreated( $date );
		}
		
		if ( $obj->hasMessages() )
		{
			return false;
		}
		
		$db = $this->getDatabaseConnection();
			
		if ( $type == 'insert' )
		{
		    $sql = "INSERT INTO leads ( type_id, name_first, name_last, addr1, addr2, city, state, zip5, ";
		    $sql .= "zip4, email, phone, partner, utility, date_created ) VALUES ( ";
		    $sql .= ":type_id, ";
		    $sql .= ":name_first, ";
		    $sql .= ":name_last, ";
		    $sql .= ":addr1, ";
		    $sql .= ":addr2, ";
		    $sql .= ":city, ";
		    $sql .= ":state, ";
		    $sql .= ":zip5, ";
		    $sql .= ":zip4, ";
		    $sql .= ":email, ";
		    $sql .= ":phone, ";
		    $sql .= ":partner, ";
		    $sql .= ":utility, ";
		    $sql .= ":date_created ";
		    $sql .= ")";
		}
		else 
		{
		    $sql = "UPDATE leads ";
		    $sql .= "SET type_id=:type_id, ";
		    $sql .= "name_first=:name_first, ";
		    $sql .= "name_last=:name_last, ";
		    $sql .= "addr1=:addr1, ";
		    $sql .= "addr2=:addr2, ";
		    $sql .= "city=:city, ";
		    $sql .= "state=:state, ";
		    $sql .= "zip5=:zip5, ";
		    $sql .= "zip4=:zip4, ";
		    $sql .= "email=:email, ";
		    $sql .= "phone=:phone, ";
		    $sql .= "partner=:partner, ";
		    $sql .= "utility=:utility, ";
		    $sql .= "date_created=:date_created ";
		    $sql .= "WHERE id = :id ";		
		}

	    $sth = $db->prepare( $sql );

	    $sth->bindParam(':type_id', $obj->getTypeId(), PDO::PARAM_INT);
	    $sth->bindParam(':name_first', $obj->getNameFirst(), PDO::PARAM_STR, 60 );
		$sth->bindParam(':name_last', $obj->getNameLast(), PDO::PARAM_STR, 60 );
		$sth->bindParam(':addr1', $obj->getAddr1(), PDO::PARAM_STR, 64 );
		$sth->bindParam(':addr2', $obj->getAddr2(), PDO::PARAM_STR, 64 );
		$sth->bindParam(':city', $obj->getCity(), PDO::PARAM_STR, 20 );
		$sth->bindParam(':state', $obj->getState(), PDO::PARAM_STR, 2 );
		$sth->bindParam(':zip5', $obj->getZip5(), PDO::PARAM_STR, 5 );
		$sth->bindParam(':zip4', $obj->getZip4(), PDO::PARAM_STR, 4 );
		$sth->bindParam(':email', $obj->getEmail(), PDO::PARAM_STR, 150 );
		$sth->bindParam(':phone', $obj->getPhone(), PDO::PARAM_STR, 32 );
		$sth->bindParam(':partner', $obj->getPartner(), PDO::PARAM_STR, 10 );
		$sth->bindParam(':utility', $obj->getUtility(), PDO::PARAM_STR, 30 );
		
		$sth->bindParam(':date_created', $obj->getDateCreated(), PDO::PARAM_STR );

		if ( $type == 'update' )
		{
			$sth->bindParam(':id', $obj->getId(), PDO::PARAM_INT );
		}
		
		$result = $sth->execute();

		// print_r( $db->errorInfo() );
		// print_r( $sth->errorInfo() );

		if ( $result == 1 )
		{
			if ( $type == 'insert' )
			{
				$obj->setId( $db->lastInsertId() );
			}
			return true;
		}
		return false;
	}
	
}





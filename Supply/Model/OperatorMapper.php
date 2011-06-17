<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Model/Operator.php';
require_once 'EP/Util/Database.php';

/**
 * 
 * Data Mapper for Partner model
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_OperatorMapper extends EP_Mapper 
{ 	
	function fetchAllCount()
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT count(*) AS count FROM operators";
		$sth = $db->prepare( $sql );
		$sth->setFetchMode( PDO::FETCH_OBJ );

		$sth->execute( );
		$result = $sth->fetch();
		
		if ( $result )
		{
			return $result->count;
		}
		return false;
	}
	
	function fetchAll( $fields = array('*'), $orderBy = array('id') )
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM operators ";
		$sql .= "ORDER BY " . implode( ',', $orderBy );	
		$sql .= " LIMIT " . $this->getOffset() . ", " . $this->getLimit() . " ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Operator' );
		}

		$sth->execute( );
		$arr = $sth->fetchAll();
		
		if ( $this->getUsePublicVars() == false )
		{
			foreach ( $arr as $num => $obj )
			{
				$obj->setPassword( null );
	
				// set the role
				$obj->setRoles( null );
				
				// set the vendor
				$obj->setVendor( null );
			}
		}
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
		
		$obj->setDateMod( $date );
		
		if ( $obj->hasMessages() )
		{
			return false;
		}
		
				
		if ( $type == 'insert' )
		{
		    $sql = "INSERT INTO operators ( abbrev, name_first, name_last, email, external_id, vendor_id, active, ";
		    $sql .= "date_created, date_mod, password ";
		    $sql .= ") VALUES ( ";
		    $sql .= ":abbrev, ";
		    $sql .= ":name_first, ";
		    $sql .= ":name_last, ";
		    $sql .= ":email, ";
		    $sql .= ":external_id, ";
		    $sql .= ":vendor_id, ";
		    $sql .= ":active, ";
		    $sql .= ":date_created, ";
		    $sql .= ":date_mod, ";
		    $sql .= ":password ";
		    $sql .= ")";
		    
		    
		}
		else 
		{
		    $sql = "UPDATE operators ";
		   	$sql .= "SET abbrev=:abbrev, ";
		    $sql .= "name_first=:name_first, ";
		    $sql .= "name_last=:name_last, ";
		    $sql .= "email=:email, ";
		    $sql .= "external_id=:external_id, ";
		    $sql .= "vendor_id=:vendor_id, ";
		    $sql .= "active=:active, ";
		    $sql .= "password=:password, ";
		    $sql .= "date_mod=:date_mod ";
		    $sql .= "WHERE id = :id ";		
		}
//echo $sql;
		$db = EP_Util_Database::pdo_connect();

	    $sth = $db->prepare( $sql );
// print_r( $db->errorInfo() );
// print_r( $sth->errorInfo() );
	   	$sth->bindParam(':abbrev', $obj->getAbbrev(), PDO::PARAM_STR, 4);
	    $sth->bindParam(':name_first', $obj->getNameFirst(), PDO::PARAM_STR, 50 );
		$sth->bindParam(':name_last', $obj->getNameLast(), PDO::PARAM_STR, 50 );
		$sth->bindParam(':email', $obj->getEmail(), PDO::PARAM_STR, 100 );
		$sth->bindParam(':external_id', $obj->getExternalId(), PDO::PARAM_INT );
		$sth->bindParam(':vendor_id', $obj->getVendorId(), PDO::PARAM_INT );
		$sth->bindParam(':active', $obj->getActive() );
		$sth->bindParam(':password', $obj->getPassword(), PDO::PARAM_STR, 64 );
		$sth->bindParam(':date_mod', $obj->getDateMod(), PDO::PARAM_STR );
// echo '<pre>' .print_r( $obj, true ) . '</pre>';
		if ( $type == 'update' )
		{
			//echo "getting here";
			$sth->bindParam(':id', $obj->getId(), PDO::PARAM_INT );
		}
		else
		{
			$sth->bindParam(':date_created', $obj->getDateCreated(), PDO::PARAM_STR );
		}
		
		$result = $sth->execute();
// echo "result: $result ";
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





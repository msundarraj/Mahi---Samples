<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Model/Supplier.php';

/**
 * 
 * Data Mapper for Supplier model 
 *
 */
class EP_Model_SupplierMapper extends EP_Mapper 
{
	function fetchAllByStateAndType( $stateId, $type, $fields = array( '*' ), $orderBy = array( 'name' => 'ASC' ) )
	{
		$props = EP_Model_Supplier::getAllClassProperties( 'EP_Model_Supplier' ) ;
		
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM suppliers ";
		$sql .= " WHERE state = :state ";
		
		if( $type == 'residential' )
		{
			$sql .= "AND residential = 1 ";
		}
		else if ( $type == 'business' )
		{
			$sql .= "AND business = 1 ";
		}
		else  // for both scenario
		{
			
		}
		
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
}





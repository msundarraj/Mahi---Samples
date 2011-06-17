<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Model/Corpsitepartner.php';

/**
 * 
 * Data Mapper for Corp Site Partner model
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_CorpsitepartnerMapper extends EP_Mapper 
{
	function fetchAll( $fields = array('*'), $orderBy = array('name') )
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM corp_site_partners ";
		$sql .= "ORDER BY " . implode( ',', $orderBy );	
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Corpsitepartner' );
		}

		$sth->execute();
		$arr = $sth->fetchAll();
		return $arr;
	}
}


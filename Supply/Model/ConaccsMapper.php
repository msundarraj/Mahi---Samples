<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Model/Conaccs.php';

/**
 * 
 * Data Mapper for Conaccs model
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_ConaccsMapper extends EP_Mapper 
{ 
	function fetch( $memnum, $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM conaccs ";
		$sql .= " WHERE memnum = :memnum ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Conaccs' );
		}

		$sth->bindParam( ':memnum', $memnum, PDO::PARAM_STR, 10 );
		
		$sth->execute();
		$result = $sth->fetch();
		if ( !$result )
		{
			return false;
		}
		$sth->closeCursor();
                
		return $result;
	}
}





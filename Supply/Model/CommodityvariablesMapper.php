<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Model/Commodityvariables.php';


/**
 * 
 * Data Mapper for Commodityvariables model 
 * @author SAynkaran
 *
 */
class EP_Model_CommodityvariablesMapper extends EP_Mapper 
{
	
	function fetch( $id, $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM commodity_variables ";
		$sql .= "WHERE id = ? ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Commodityvariables' );
		}

		$sth->execute( array( $id ));
		return $sth->fetch();
	}
}
	
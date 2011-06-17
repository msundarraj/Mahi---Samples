<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';

class EP_Model_CountyMapper extends EP_Mapper
{
	public function fetchByPrefixAndZip($prefix, $zip5, $fields=array('*'))
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = 'SELECT ' . implode($fields, ',') . ' FROM zip_county_lookup WHERE prefix = ? AND zip=?';
		$sth = $db->prepare($sql);
		$sth->bindParam(1, $prefix, PDO::PARAM_STR);
		$sth->bindParam(2, $zip5, PDO::PARAM_INT);
		$sth->execute();
		$row = $sth->fetch(PDO::FETCH_OBJ);
		return $row;
	}
}


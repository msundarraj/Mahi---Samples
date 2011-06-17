<?php
require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Model/PartnerCategory.php';
/**
 * DateMapper for PartnerCategory model
 */
class EP_Model_PartnerCategoryMapper extends EP_Mapper 
{
	public function fetchAll()
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT id, name FROM partner_categories";
		if($this->getUsePublicVars())
		{
			$sth = $db->query($sql, PDO::FETCH_OBJ);
		}
		else
		{
			$sth = $db->query($sql, PDO::FETCH_CLASS, 'EP_Model_PartnerCategory');
		}
		return $sth->fetchAll();
	}

	public function fetchAllByState( $state )
	{
		$sql = "SELECT DISTINCT(a.category) AS id, b.name ";
		$sql .= "FROM partnercode a, partner_categories b ";
		$sql .= "WHERE a.state=:state ";
		$sql .= "AND a.category = b.id ";
		$sql .= "AND a.allow_inbound = 1 ";
		$sql .= "ORDER BY b.name";
	/*
	 * 
	$sql = "SELECT p.* ";
	$sql .= "FROM partnercode p, states s ";
	$sql .= "WHERE s.id = p.state ";
	$sql .= "AND p.category = :category ";
	$sql .= "AND s.active = 1 ";
	$sql .= "AND s.id = :state ";
	$sql .= "AND p.allow_inbound = 1 ";
	 */
		$db = EP_Util_Database::pdo_connect();
		if($this->getUsePublicVars())
		{
			$sth = $db->query($sql, PDO::FETCH_OBJ );
		}
		else
		{
			$sth = $db->query($sql, PDO::FETCH_CLASS, 'EP_Model_PartnerCategory' );
		}
// print_r( $db->errorInfo() );
// print_r( $sth->errorInfo() );		
		$sth->bindParam(':state', $state, PDO::PARAM_INT );
		$result = $sth->execute( );
		if ( $result )
		{
			return $sth->fetchAll();
		}
		return false;
	}
}



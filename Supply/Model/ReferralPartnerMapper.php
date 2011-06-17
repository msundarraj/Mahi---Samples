<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Model/ReferralPartner.php';

/**
 * 
 * Data Mapper for Partner model
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_ReferralPartnerMapper extends EP_Mapper 
{
	function fetchByReferralId( $refid, $type, $state )
	{
		if ( strtolower( $type ) == 'residential' )
		{
			$accountType = 'res';
		}
		else
		{
			$accountType = 'bus';
		}
		
		$db = EP_Util_Database::pdo_connect();		

		$sql = "SELECT c.partnerid AS partner_id, b.id AS campaign_id, d.promocode AS promo_code, d.promodesc AS promo_desc, ";
		$sql .= "a.partnercode as partner_code, b.campaign_code AS campaign_code, c.refid ";
		$sql .= "FROM partnercode a, partner_campaign b, referral_partners c, partner_promocode d ";
		$sql .= "WHERE c.refid = ? ";
		$sql .= "AND a.id = b.partnerid ";
		$sql .= "AND b.partnerid = c.partnerid ";
		$sql .= "AND b.id = d.campaignid ";
		$sql .= "AND d.promodesc LIKE '${accountType}%' ";  // 'bus%' 
		$sql .= "AND a.state = ? ";

		$sth = $db->prepare( $sql );
// print_r( $db->errorInfo() );
// print_r( $sth->errorInfo() );
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_ReferralPartner' );
		}
		
		$sth->execute( array( $refid, $state ));
		$result = $sth->fetch();

//print_r( $sth->errorInfo() );
//print_r( $db->errorInfo() );
//print_r( $arr );

		return $result;

	}
	
	function fetchByStateCampaignRefid( $partner, $campaign, $refid, $state, $type )
	{
		if ( strtoupper( $type ) == 0 )
		{
			$accountType = 'res';
		}
		else
		{
			$accountType = 'bus';
		}
		
		$db = EP_Util_Database::pdo_connect();

		$sql = "SELECT c.busname AS referral_partner, a.state as state, a.partner_dir as partner_dir, ";
		$sql .= "d.promocode, a.partnercode as partner_code, ";
		$sql .= "a.id as partner_id,  b.id as campaign_id, b.campaign_code as campaign_code, d.promodesc, ";
		$sql .= "a.checksum, a.affinity, a.pfname ";
		$sql .= "FROM partnercode a, partner_campaign b, referral_partners c, partner_promocode d ";
		$sql .= "WHERE a.state = ? ";
		$sql .= "AND b.campaign_code = ? ";
		$sql .= "AND a.partnercode = ? ";
		$sql .= "AND a.id = b.partnerid ";
		$sql .= "AND a.state = c.stateid ";
		$sql .= "AND b.partnerid = c.partnerid ";
		$sql .= "AND c.refid = ? ";
		$sql .= "AND c.active = 1 ";
		
		$sql .= "AND b.id = d.campaignid ";
		$sql .= "AND d.promodesc LIKE '${accountType}%' ";  // 'bus%'  

 // echo $sql ;
 // echo "state: $state ";
 // echo "refid: $refid ";
 // echo "campaign: $campaign ";
 // echo "partner: $partner ";
		
		$sth = $db->prepare( $sql );
// print_r( $sth->errorInfo() );
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_ReferralPartner' );
		}
		
		$sth->execute( array( $state, $campaign, $partner, $refid ));
		$result = $sth->fetch();
//print_r( $sth->errorInfo() );
//print_r( $db->errorInfo() );
//print_r( $arr );

		return $result;
	}
}
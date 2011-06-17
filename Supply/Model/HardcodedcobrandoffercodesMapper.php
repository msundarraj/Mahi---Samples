<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';

/**
 * 
 * Data Mapper for Hardcodedcobrandoffers model
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_HardcodedcobrandoffercodesMapper extends EP_Mapper 
{
	function fetchByStatePromoCampaignPartner( $partner, $campaign, $promo, $state )
	{
		$db = EP_Util_Database::pdo_connect();
		// promocode, campaign, partnercode
		$sql = "SELECT url FROM hardcoded_cobrand_offer_codes WHERE ";
		$sql .= "state_id = ? AND partner_code = ? AND campaign_code = ? AND promo_code = ? ";
		
		$sth = $db->prepare( $sql );

		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		
		$sth->execute( array( $state, $partner, $campaign, $promo ));
		$arr = $sth->fetchAll();

		return $arr;
	}
	

}





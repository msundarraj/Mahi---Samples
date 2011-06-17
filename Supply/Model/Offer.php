<?php
require_once 'EP/Model/PartnerMapper.php';
require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';
require_once 'EP/Util/Database.php';
require_once 'EP/Model/StateMapper.php';
require_once 'EP/Model/StarburstvariablesMapper.php';

class EP_Model_Offer extends EP_Model
{
	protected $rate;
	protected $promo;
	protected $campaign;
	protected $partner;

	/**
	 *
	 * Return the cashback offer
	 * @param id $stateId
	 * @param string $accountType
	 */
	function fetchCashbackByState( $stateId, $accountType = 'residential' ) // or business
	{
		if ( $accountType == 'residential' )
		{
			$partnerCode = 'BRC';
			$promo = 'promocode';
		}
		else	// business
		{
			// States don't have a business cash back option for inbound
			// so we flip it to residential ( new inbound 5/5/2011 )
			// if ( $stateId == 3 )
			// {
				$partnerCode = 'BRC';
				$promo = 'promocode';
			// }
			// else
			// {
			//	$partnerCode = 'BRD';
			//	$promo = 'promocode_bus';
			// }
		}

		$sql = "SELECT id, state, partnercode, description, $promo, campaign ";
		$sql .= "FROM partnercode WHERE partnercode = :partner_code AND state = :state ";

// echo $sql;
// echo "<br >" . $stateId . " " . $partnerCode;

		$db = EP_Util_Database::pdo_connect();
		$sth = $db->prepare( $sql );

		$sth->setFetchMode( PDO::FETCH_OBJ );

		$sth->bindParam( ':state', $stateId, PDO::PARAM_INT);
		$sth->bindParam( ':partner_code', $partnerCode, PDO::PARAM_STR, 3 );

		$sth->execute();
		$result = $sth->fetch();
		$sth->closeCursor();
		return $result;
	}

	// valid partnerTypes = ( brd, cobrand, aff )
	// accountType seen a lot as "resbus"  = 1, 2

	/**
	 *
	 * Get the first month price for the offer
	 * @param integer $state
	 * @param string $partnerCode
	 * @param int $accountType  0 = residential, 1 = business
	 * @param mixed $utilityCode
	 * @param unknown_type $green
	 * @param int $kwHours 1000 || 2500
	 * @param string $revenueClass
	 * @param int $numberFormat
	 * @param int $display 0 = for display, 1 = for database (not for display)
	 */
	public function fetchFirstMonthPrice( $state, $partnerCode, $accountType, $utilityCode, $green=null, $kwHours = null, $revenueClass = null, $numberFormat = 4,$display=0)
	{
		// Display Flag is 1 for O & R.
		if($utilityCode==30)
		{
			$display=1;
		}

		//echo $green;
        	// Get the partner record.
		$partner_mapper = new EP_Model_PartnerMapper();
		$partner = $partner_mapper->fetchByStateAndPartnercode($state, $partnerCode);

		// Get the state record.
		$stateMapper = new EP_Model_StateMapper();
		$stateObj = $stateMapper->fetch( $state );

		if ( ! $stateObj || ! $partner )
		{
			return false;
		}

		$partnerType = $partner->getPartnerType();

		$taxRate = $stateObj->getTaxRate();

		$db = EP_Util_Database::pdo_connect();

		// NY does not have pricing based on offercodes
		// so there is a separate table in db where EP personnel
		// can enter the first month pricing for NY
		if ( $state == 1 )
		{
			$month = date('n');
			$year = date('y');

			$sql = "SELECT * ";
			$sql .= "FROM ny_pricing ";
			$sql .= "WHERE state = :state ";
			$sql .= "AND partner_type = :partner_type ";
			$sql .= "AND account_type = :account_type ";
			$sql .= "AND utility = :utility_code ";
			$sql .= "AND month = $month AND year = $year ";

			$sth = $db->prepare( $sql );
// print_r( $db->errorInfo() );
// print_r( $sth->errorInfo() );
			$sth->setFetchMode( PDO::FETCH_OBJ );

			$sth->bindParam( ':state', $state, PDO::PARAM_INT );
			$sth->bindParam( ':partner_type', $partnerType, PDO::PARAM_STR, 6 );
			$sth->bindParam( ':account_type', $accountType, PDO::PARAM_INT );
			$sth->bindParam( ':utility_code', $utilityCode, PDO::PARAM_STR, 3 );
			$sth->execute();
			$result = $sth->fetch();
			if(!$result)
			{
				return NULL;
			}
			$sth->closeCursor();
			$rate = $result->price;

            // Special case for Starwood partner, no VAS adder eventhough partner is always green.
			if (($green == '001' || $green == '002') && $partner->getPartnerCode() != 'SPG')
			{
				$rate = $rate+0.01;
			}
		} // if (state == 1)
        // All other states.
		else
		{

			$sqlOffer = "SELECT offercode ";
			$sqlOffer .= "FROM default_offer_mapping ";
			$sqlOffer .= "WHERE partner_type = :partner_type ";	// partnerType
			$sqlOffer .= "AND account_type = :account_type ";
			$sqlOffer .= "AND utility = :utility_code ";
			$sqlOffer .= "AND state = :state ";



			$sth = $db->prepare( $sqlOffer );
			$sth->setFetchMode( PDO::FETCH_OBJ );

			// bind our vars
			$sth->bindParam( ':state', $state, PDO::PARAM_INT );
			$sth->bindParam( ':partner_type', $partnerType, PDO::PARAM_STR, 8 );
			$sth->bindParam( ':account_type', $accountType, PDO::PARAM_INT );
			$sth->bindParam( ':utility_code', $utilityCode, PDO::PARAM_INT );


			// execute it
			$sth->execute();

			$result = $sth->fetch();
			if(!$result)
			{
				return NULL;
			}

			$sth->closeCursor();

			if ( ! $result )
			{
				return false;
			}

			$offerCode = $result->offercode;

			$sqlRate = "SELECT rate, tdspvariable, tdspfixed, vas_rate FROM util_offers WHERE code = :offer_code ";
			$sqlRate .= "AND util_code = :utility_code ";
			$sqlRate .= "ORDER BY effdate DESC LIMIT 1";

			$sth = $db->prepare( $sqlRate );
			$sth->setFetchMode( PDO::FETCH_OBJ );

			$sth->bindParam( ':offer_code', $offerCode, PDO::PARAM_STR, 6 );
			$sth->bindParam( ':utility_code', $utilityCode, PDO::PARAM_INT );
			$sth->execute();
			$result = $sth->fetch();

			$sth->closeCursor();

			$rate = $result->rate;
			$tdspVariable = $result->tdspvariable;
			$tdspFixed = $result->tdspfixed;

            // Special case for Starwood partner, no VAS adder eventhough partner is always green.
			if (($green == '001' || $green == '002') && $partner->getPartnerCode() != 'SPG')
			{
                $vasRate = $result->vas_rate;
				//$rate += $vasRate;
				$rate = $rate+0.01;

			}

		} // end if state == 1

		// init our rates
		$rateFinal = 0;
		$rateTmp = 0;

		// as of 3/17/2011, only Texas used revenueClass
		if ( $kwHours && $revenueClass )
		{
			switch ( $revenueClass )
			{
				// this doesn't handle demand vs non demand pricing yet

				// case 2:
				// case 3:
					//$rate_d1500 = $offerrow->tdsp_dem_fixed / 1500;
					// $rate5d = $rate_d1500 + $offerrow->rate + $offerrow->tdsp_dem_var;

					// $rate_nd1500 = $offerrow->tdsp_non_dem_fixed / 1500;
					// $rate5nd = $rate_nd1500 + $offerrow->rate + $offerrow->tdsp_non_dem_var;

					// break;
				default:
					$rateAddition = $tdspFixed / (int)$kwHours;
					$rate = $rateAddition + $rate + $tdspVariable;
					break;
			}

		}




		if( $taxRate > 0 && $display==0)
		{
			if( $state == 5 )
			{
				// NJ needs to be rounded, should be in states table
				$rateTmp = round( $rate + ( $rate * ( $taxRate / 100)),4);
			}
			else
			{
				$rateTmp = $rate + ( $rate * ( $taxRate / 100));
			}
		}
		else
		{
			// shouldn't all values be rounded?
			if( $state == 5 )
			{
				$rateTmp = round( $rate, 4);
			}
			else
			{
				$rateTmp = $rate;

			}
		}

		//$rateFinal = number_format( $rateTmp, $numberFormat );

		 //$rateFinal = sprintf("%0.3f&cent;",$rateTmp*100);
 		 $rateFinal = $rateTmp;

		return $rateFinal;
	}





	public function fetchDescription( $state, $promoCode, $campaignCode, $partnerCode, $base_url, $accountType=null )
	{
		// accountType =  residential || business
		// echo "rewardType: $rewardType <br />";
		// echo "partner: $partnerCode <br >";

		$sql = "SELECT a.affinity, a.state as state, a.partner_dir as partner_dir, c.promocode as promocode, a.partnercode as partner_code, ";
		$sql .= "a.id as partner_id,  b.id as campaign_id, b.campaign_code as campaign_code, c.promodesc ";
		$sql .= "FROM partnercode a, partner_campaign b, partner_promocode c ";
		$sql .= "WHERE a.state = :state ";
		$sql .= "AND c.promocode = :promo_code ";
		$sql .= "AND b.campaign_code = :campaign_code ";
		$sql .= "AND a.partnercode = :partner_code ";
		$sql .= "AND a.id = b.partnerid ";
		$sql .= "AND b.id = c.campaignid ";

		$db = EP_Util_Database::pdo_connect();

		$sth = $db->prepare( $sql );
		$sth->setFetchMode( PDO::FETCH_OBJ );

		// bind our vars
		$sth->bindParam( ':state', $state, PDO::PARAM_INT);
		$sth->bindParam( ':promo_code', $promoCode, PDO::PARAM_STR, 5 );
		$sth->bindParam( ':campaign_code', $campaignCode, PDO::PARAM_STR, 5 );
		$sth->bindParam( ':partner_code', $partnerCode, PDO::PARAM_STR, 5 );

		// execute it
		$sth->execute();
		$result = $sth->fetch();
//echo '<pre>' . print_r( $result, true ) . '</pre>';
		$sth->closeCursor();

		// print_r( $db->errorInfo() );
		// print_r( $sth->errorInfo() );

		// if there's no result then we have to abandon
		if ( !$result )
		{
			return false;
		}


		$partnerCode = strtolower( $partnerCode );

		switch( $partnerCode )
		{
			case 'brc':
			case 'brd':
			case 'upr': // upromise
			case 'slm': // sallie mae
				$rewardType = 'cashback';
				break;
			default:
				$rewardType = 'miles_or_points';
				break;
		}

		$affinity = false;
		if ( $result->affinity )
		{
			$affinity = true;
		}

		// get partnerId from the result set
		$partnerId = $result->partner_id;
		$sql = "SELECT * FROM partner_variables WHERE partner_id = :partner_id ";
		$sth = $db->prepare( $sql );

		// print_r( $db->errorInfo() );
		// print_r( $sth->errorInfo() );

		$sth->bindParam( ':partner_id', $partnerId, PDO::PARAM_INT);
		$sth->setFetchMode( PDO::FETCH_OBJ );
		$sth->execute();
		$partnerVars = $sth->fetchAll();
		$sth->closeCursor();

		if ( !$partnerVars )
		{
			return false;
		}


		// we do the cash back stuff first for "brand" residential
		if ( $partnerCode == 'brc')
		{
			$offerDesc = "This program offers you a {text1_miles} activation bonus check that will be processed after ";
			$offerDesc .= "your {bonus_mon} {A}  with Energy Plus. In addition you will earn a ";
			$offerDesc .= "cash back rebate of {text2_miles} of your total kWh supply charges that will be processed ";
			$offerDesc .= "after {award_mons} {B}.";
		}
		else if ( $partnerCode == 'brd' )	// "brand" business
		{
			$offerDesc = "This program offers you a {bonus_biz} activation bonus check that will be processed after ";
			$offerDesc .= "your {bonus_mon} {A}  with Energy Plus. In addition you will earn a ";
			$offerDesc .= "cash back rebate of {text2_miles} of your total kWh supply charges that will be ";
			$offerDesc .= "processed after {award_mons} {B}.";
		}
		else if ( $affinity )
		{
			// different descriptions for residential and business
			if (strtolower($accountType)  == 'residential' )
			{
				$offerDesc = 'This program offers you a ${resaffin} activation bonus check that will be ';
				$offerDesc .= 'processed after your second {A} with Energy Plus.  In ';
				$offerDesc .= 'addition you will earn a cash back rebate of {aff_res_ongoing}% of your total ';
				$offerDesc .= 'kWh supply charges that will be processed after {award_mons} {B}.';
			}
			else
			{
				$offerDesc = 'This program offers you a ${bizaffin} activation bonus check that will be ';
				$offerDesc .= 'processed after your second {A} with Energy Plus. In ';
				$offerDesc .= 'addition you will earn a cash back rebate of {aff_biz_ongoing}% of your total ';
				$offerDesc .= 'kWh supply charges that will be processed after {award_mons} {B}.';
			}
		}
		else 	// this should cover the "cobrand" cases
		{
			$offerDesc = "This program offers you as a {full_program} member  the ability to earn {text2_miles} ";
			$offerDesc .= "{reward_type} for every $1  you spend on the supply portion of your electricity bill. ";
			$offerDesc .= "In addition you will earn {text1_miles} {reward_type} after your {bonus_mon} {A} as ";
			$offerDesc .= "an Energy Plus customer";
			if($partnerCode=='con')
			{
			$offerDesc .= "{bullet_snippet}.";
			}
			if($partnerCode=='aal')
			{
			$offerDesc .= "{bullet_snippet2}.";
			}


		}



		/* not applied yet
		$cashback_bizres = "This program offers you a {bonus_biz} activation bonus check for your business account ";
		$cashback_bizres .= "and a {bonus_res} activation bonus check for your residential account that will be ";
		$cashback_bizres .= "processed after 2 months of active service from Energy Plus. In addition you will earn a ";
		$cashback_bizres .= "cash back rebate for your business account of {ongoing_biz} of your total kWh supply ";
		$cashback_bizres .= "charges and {ongoing _res} per residential account that will be processed after {award_mon} billing cycles.";
		*/

		// replace all the basic partner variables
		foreach ( $partnerVars as $key => $partner_var )
		{
			$offerDesc = str_replace('{'.$partner_var->variable_name.'}',$partner_var->variable_value, $offerDesc );
		}

		// fetch the starburst variables
		$sbMapper = new EP_Model_StarburstvariablesMapper();
		$sb = $sbMapper->fetchVariablesByPartnerAndPromo( $partnerId, $promoCode );

		if( $sb )
		{

/*
{A}			IF STATE= NY, IL, MD, OR NJ : ?billing cycle?
{A}			IF STATE = CT, TX, OR PA: ?month of active service?
{B}			IF STATE= NY, IL, MD, OR NJ : ?billing cycles?
{B}			IF STATE = CT, TX, OR PA: ?months?
{C}			IF STATE= NY, IL, MD, OR NJ : ?billing cycles?
{C}			IF STATE = CT, TX, OR PA: ?months for active service.?
*/


			if (($state ==1) || ($state==7) || ($state==6) || ($state==5))
			{
				$offerDesc = str_replace('{A}', 'billing cycle', $offerDesc );
				$offerDesc = str_replace('{B}', 'billing cycles', $offerDesc );
				$offerDesc = str_replace('{C}', 'billing cycles', $offerDesc );
			}
			else if(($state ==2) || ($state==3) || ($state==4))
			{
				$offerDesc = str_replace('{A}', 'month of active service', $offerDesc );
				$offerDesc = str_replace('{B}', 'months', $offerDesc );
				$offerDesc = str_replace('{C}', 'months of active service', $offerDesc );
			}


				// we do the cash back stuff first for "brand" residential
			if ( $partnerCode == 'brc')
			{
				$offerDesc = str_replace('{text1_miles}', $sb->text1_miles, $offerDesc );
				$offerDesc = str_replace('{text2_miles}', $sb->text2_miles, $offerDesc );
				$offerDesc = str_replace('{bonus_mon}', $sb->bonus_mon, $offerDesc );
				$offerDesc = str_replace('{award_mons}', $sb->award_mons, $offerDesc );
			}
			else if ( $partnerCode == 'brd' )	// "brand" business
			{
				$offerDesc = str_replace('{text1_miles}', $sb->text1_miles, $offerDesc );
				$offerDesc = str_replace('{bonus_mon}', $sb->bonus_mon, $offerDesc );
				$offerDesc = str_replace('{text2_miles}', $sb->text2_miles, $offerDesc );
				$offerDesc = str_replace('{award_mons}', $sb->award_mons, $offerDesc );

			}
			else if ( $affinity )
			{
				// different descriptions for residential and business
				if ( strtolower($accountType) == 'residential' )
				{
					$offerDesc = str_replace('{resaffin}', $sb->resaffin, $offerDesc );
					$offerDesc = str_replace('{aff_res_ongoing}', $sb->aff_res_ongoing, $offerDesc );
					$offerDesc = str_replace('{award_mons}', $sb->award_mons, $offerDesc );
				}
				else
				{
					$offerDesc = str_replace('{bizaffin}', $sb->bizaffin, $offerDesc );
					$offerDesc = str_replace('{aff_biz_ongoing}', $sb->aff_biz_ongoing, $offerDesc );
					$offerDesc = str_replace('{award_mons}', $sb->award_mons, $offerDesc );
				}
			}
			else 	// this should cover the "cobrand" cases
			{
				$offerDesc = str_replace('{text2_miles}', $sb->text2_miles, $offerDesc );
				$offerDesc = str_replace('{text1_miles}', $sb->text1_miles, $offerDesc );
				$offerDesc = str_replace('{bonus_mon}', $sb->bonus_mon, $offerDesc );
				$offerDesc = str_replace('{ib_desc}', $sb->ib_desc, $offerDesc );
				$offerDesc = str_replace('{bullet_snippet}', $sb->bullet_snippet, $offerDesc );
				$offerDesc = str_replace('{bullet_snippet2}', $sb->bullet_snippet2, $offerDesc );
			}
/*
$_SESSION['sb']['aff_res_ongoing']=$sb->aff_res_ongoing;
$_SESSION['sb']['award_mons']=$sb->award_mons;
$_SESSION['sb']['bizaffin']=$sb->bizaffin;
$_SESSION['sb']['bonus_mon']=$sb->bonus_mon;
$_SESSION['sb']['ib_desc']=$sb->ib_desc;
$_SESSION['sb']['resaffin']=$sb->resaffin;
$_SESSION['sb']['bizaffin']=$sb->bizaffin;
$_SESSION['sb']['rewtxt']=$sb->text1_miles;
$_SESSION['sb']['text1_miles']=$sb->text1_miles;
$_SESSION['sb']['bullet_snippet']=$sb->bullet_snippet;
$_SESSION['sb']['bullet_snippet2']=$sb->bullet_snippet2;
*/

		}

		$vars = '';


		$resp = array(
			'success' => true,
			'rewardType' => $rewardType,
			'rewardDesc' => $offerDesc,
			'vars' => $sb
		);

		return $resp;
	}

	public function fetchOfferCode( $state, $partnerType, $accountType, $utilityCode, $partnerCode = null, $campaignCode = null )
	{
		$db = EP_Util_Database::pdo_connect();
		// For Texas, we look in camp_pcode_lookup first for offer code
		// and return that result, otherwise, we go thru the normal process
		// Note: the busres is different in that table, so we need to add +1 to the normal
		// busres value
		if ( $state == 3 )
		{
			$tmpBusRes = $accountType + 1;

			$sql = "SELECT a.pricecode AS offercode ";
			$sql .= "FROM camp_pcode_lookup a, partnercode b, partner_campaign c ";
			$sql .= "WHERE b.partnercode = :partner_code ";
			$sql .= "AND a.partner_id = b.id ";
			$sql .= "AND b.id = c.partnerid ";
			$sql .= "AND c.campaign_code = :campaign_code ";
			$sql .= "AND c.id = a.camp_id ";
			$sql .= "AND b.state = 3 ";
			$sql .= "AND ( a.busres = :busres || a.busres = 3 ) ";

			$sth = $db->prepare( $sql );
			// print_r( $db->errorInfo() );
			// print_r( $sth->errorInfo() );
			$sth->bindParam( ':partner_code', $partnerCode, PDO::PARAM_STR, 3 );
			$sth->bindParam( ':campaign_code', $campaignCode, PDO::PARAM_STR, 4 );
			$sth->bindParam( ':busres', $tmpBusRes, PDO::PARAM_INT );

			$sth->setFetchMode( PDO::FETCH_OBJ );
			$sth->execute();
			$result = $sth->fetch();
			$sth->closeCursor();

			if ( $result )
			{
				return $result->offercode;
			}
		}

		$sql = "SELECT offercode FROM default_offer_mapping ";
		$sql .= "WHERE partner_type = :partner_type ";
		$sql .= "AND account_type = :account_type ";
		$sql .= "AND utility = :utility_code ";
		$sql .= "AND state = :state ";

		$sth = $db->prepare( $sql );
		// print_r( $db->errorInfo() );
		// print_r( $sth->errorInfo() );
		$sth->bindParam( ':partner_type', $partnerType, PDO::PARAM_STR, 10 );
		$sth->bindParam( ':account_type', $accountType, PDO::PARAM_INT );
		$sth->bindParam( ':utility_code', $utilityCode, PDO::PARAM_STR, 3 );
		$sth->bindParam( ':state', $state, PDO::PARAM_INT );

		$sth->setFetchMode( PDO::FETCH_OBJ );
		$sth->execute();
		$result = $sth->fetch();
		$sth->closeCursor();

		if ( $result )
		{
			return $result->offercode;
		}

		return false;
	}

	/**
	 *
	 * Global function for calculating the bumped up promo
	 * This is for Continental promos where the member number is
	 * also in the conaccs table.
	 *
	 * @param string $promo 3 digit promo code
	 */
	public function fetchBumpedPromo( $promo )
	{
		if( $promo == '046' ) return '058';
		if( $promo == '035' ) return '052';
		if( $promo == '004' ) return '045';
		if( $promo == '071' ) return '058';

		return false;
	}
	/**
	   Returns contractterm (or NULL if not found), given a state id

	   @param state_id id of state
	   @return contract term, in months
	*/
	public function getContractterm($state_id)
	{
		$sql = <<< EOF
			SELECT contractterm
			FROM util_offers JOIN utility2 ON util_offers.util_code = utility2.code
			WHERE state = ? AND contractterm IS NOT NULL
			ORDER BY util_offers.effdate DESC LIMIT 1;
EOF;
		$db = EP_Util_Database::pdo_connect();
		$sth = $db->prepare($sql);
		$sth->execute(array($state_id));
		$row = $sth->fetch(PDO::FETCH_OBJ);
		return isset($row->contractterm) ? $row->contractterm : NULL;
	}

	/**
	   @param $offer_code util_offers.code
	   @param $util_code utility2.code

	   @return chargeid (also known as ProdCode or product code) for given offer and utility2, or NULL for failure
	*/
	public function fetchChargeid($offer_code, $util_code)
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = <<< EOF
			SELECT chargeid FROM util_offers
			WHERE code = ?
			AND util_code = ?
			ORDER BY effdate DESC
			LIMIT 1
EOF;
		$sth = $db->prepare($sql);
		$sth->execute(array($offer_code, $util_code));
		$row = $sth->fetch(PDO::FETCH_OBJ);
		return isset($row->chargeid) ? $row->chargeid : NULL;
	}
}

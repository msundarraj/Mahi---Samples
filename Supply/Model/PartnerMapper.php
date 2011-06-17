<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Model/Partner.php';

/**
 * 
 * Data Mapper for Partner model
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_PartnerMapper extends EP_Mapper 
{ 


	
	function fetchAllInboundByState( $state_id, $fields = array('a.*'), $orderBy = array('description') )
	{
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM partnercode a, states b ";
		$sql .= "WHERE b.id = a.state AND b.active = 1 ";
		$sql .= "AND b.id = ? ";
		$sql .= "AND a.allow_inbound = 1 ";
		$sql .= "ORDER BY " . implode( ',', $orderBy );	

		$db = EP_Util_Database::pdo_connect();
		$sth = $db->prepare( $sql );

		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Partner' );
		}

		$sth->execute( array( $state_id ) );
		$result = $sth->fetchAll();
		return $result;
	}

	function getVariableById($variable_name, $partner_id)
	{
		$sql = 'SELECT variable_value FROM partner_variables WHERE variable_name = ? AND partner_id = ?';
		$db = $this->getDatabaseConnection();
		$sth = $db->prepare($sql);
		$sth->execute(array($variable_name, $partner_id));
		return $sth->fetchColumn(0);
	}

	function fetchByStateAndPartnercode( $state, $partnercode, $fields = array('*'))
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM partnercode ";
		$sql .= " WHERE state = ? AND partnercode = ? ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Partner' );
		}
// print_r( $sth->errorInfo() );
// print_r( $db->errorInfo() );
		$sth->execute( array( $state, $partnercode ) );
		$result = $sth->fetch();
		return $result;
	}
	
	/**
	   @param $state_id states.id
	   @param $partner_dir partnercode.partner_dir
	   @param $fields array of fields to select
	   
	   @return EP_Model_Partner object, or object
	*/
	public function fetchByStateAndPartnerDir($state_id, $partner_dir, $fields = array('*'))
	{
		$db = EP_Util_Database::pdo_connect();
		$fields_str = implode(',', $fields);
		$sql = <<< EOF
			SELECT $fields_str
			FROM partnercode
			WHERE state = ?
			AND partner_dir = ?
EOF;
		$sth = $db->prepare($sql);
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Partner' );
		}
		$sth->execute(array($state_id, $partner_dir));
		return $sth->fetch();
	}
	
	function fetchByAffinityPartnerCampaign( $state, $partner, $campaign, $accountType )
	{
		$db = EP_Util_Database::pdo_connect();
		
		if ( ! in_array( $accountType, array( 'residential', 'business')))
		{
			$accountType = 'residential';
		}
		$promoDescSql = '';
		if ( $accountType == 'residential' )
		{
			$promoDescSql = "AND ( c.promodesc LIKE '%residential%' OR c.promodesc LIKE '%res%' ) ";
		}
		else
		{
			$promoDescSql = "AND ( c.promodesc LIKE '%business%' OR c.promodesc LIKE '%bus%' OR c.promodesc LIKE '%biz%' ) ";
		}
		// $accountType = $accountType . "%";
		
		// promocode, campaign, partnercode
		$sql = "SELECT a.id, a.state as state, a.partner_dir as partner_dir, c.promocode as promocode, a.partnercode as partner_code, ";
		$sql .= "a.id as partner_id,  b.id as campaign_id, b.campaign_code as campaign_code, c.promodesc, a.checksum, ";
		$sql .= "a.affinity, a.pfname, a.allow_invalid_checksum ";
		$sql .= "FROM partnercode a, partner_campaign b, partner_promocode c ";
		$sql .= "WHERE a.state = :state ";
		// $sql .= "AND c.promocode = ? ";
		$sql .= "AND b.campaign_code = :campaign ";
		$sql .= "AND a.partnercode = :partner ";
		$sql .= "AND a.id = b.partnerid ";
		$sql .= "AND b.id = c.campaignid ";
		// $sql .= "AND c.promodesc LIKE :type ";
		$sql .= $promoDescSql;
// echo $sql ;
// echo "state: $state <br>";
// echo "accountType: $accountType  <br >";
// echo "campaign: $campaign <br >";
// echo "partner: $partner <br >";
		
		$sth = $db->prepare( $sql );
// print_r( $sth->errorInfo() );
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Partner' );
		}
		
		$sth->bindParam( ':state', $state, PDO::PARAM_INT );
		$sth->bindParam( ':campaign', $campaign, PDO::PARAM_STR, 4 );
		$sth->bindParam( ':partner', $partner, PDO::PARAM_STR, 3 );
		// $sth->bindParam( ':type', $accountType, PDO::PARAM_STR, 12 );

		
		$sth->execute();
		$arr = $sth->fetchAll();
// print_r( $sth->errorInfo() );
// print_r( $db->errorInfo() );
// echo '<pre>' . print_r( $arr, true ) . '</pre>';
		return $arr;		
	}
	
	function fetchByStatePromoCampaignPartner( $partner, $campaign, $promo, $state )
	{
		$db = EP_Util_Database::pdo_connect();
		// promocode, campaign, partnercode
		$sql = "SELECT a.id, a.state as state, a.partner_dir as partner_dir, c.promocode as promocode, a.partnercode as partner_code, ";
		$sql .= "a.id as partner_id,  b.id as campaign_id, b.campaign_code as campaign_code, c.promodesc, a.checksum, ";
		$sql .= "a.affinity, a.pfname, a.allow_invalid_checksum ";
		$sql .= "FROM partnercode a, partner_campaign b, partner_promocode c ";
		$sql .= "WHERE a.state = ? ";
		$sql .= "AND c.promocode = ? ";
		$sql .= "AND b.campaign_code = ? ";
		$sql .= "AND a.partnercode = ? ";
		$sql .= "AND a.id = b.partnerid ";
		$sql .= "AND b.id = c.campaignid ";
 // echo $sql ;
 // echo "state: $state ";
 // echo "promo: $promo ";
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
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Partner' );
		}
		
		$sth->execute( array( $state, $promo, $campaign, $partner ));
		$arr = $sth->fetchAll();
//print_r( $sth->errorInfo() );
//print_r( $db->errorInfo() );
//print_r( $arr );
		return $arr;
	}
	
	function fetchByStateAndReward( $state, $reward )
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT id, state, partnercode, partner_dir, description, apptype, promocode, campaign, default_cellcode ";
		$sql .= "FROM partnercode WHERE state = ? AND id = ? AND affinity != 1 ";
		$sth = $db->prepare( $sql );

		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Partner' );
		}
		
		$sth->execute( array( $state, $reward ));
		$arr = $sth->fetchAll();
		return $arr;
	}
	
	
	function fetchAllNonAffinity( $fields = array('*'), $orderBy = array('description') )
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM partnercode ";
		$sql .= " WHERE affinity != 1 ";
		$sql .= "ORDER BY " . implode( ',', $orderBy );	
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Partner' );
		}

		$sth->execute();
		$arr = $sth->fetchAll();
		return $arr;
	}
	
	/**
	 * 
	 * Returns array of "partnercod" table records
	 * @param integer $state
	 * @param integer $category
	 * @param boolean $referral
	 * @param boolean $showBrd Whether to include "BRD" partners
	 * @param array $fields List of fields to return
	 * @param array $orderBy List of fields to order by
	 * 
	 * WARNING:  With the default parameters, this method does not return the BRD
	 * partners since we so frequently leave them out of lists.  If you need to fetch those
	 * then set $showBrd === true
	 */
	function fetchAllByStateAndCategory( $state, $category = null, $referral = false, $showBrd = false, $fields = array('p.*'), $orderBy = array('description') )
	{
		
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= "FROM partnercode p, states s ";
		$sql .= "WHERE s.id = p.state ";
		$sql .= "AND s.active = 1 ";
		$sql .= "AND s.id = :state ";
		$sql .= "AND p.allow_inbound = 1 ";
		
		if ( $showBrd === false )
		{
			// have to omit BRD for new inbound
			$sql .= "AND p.partnercode != 'BRD' ";
		}
		
		// check for referral
		if ( $referral === true )
		{
			$sql .= "AND p.use_referral = 1 ";
		}
		
		if ( $category != null )
		{
			$sql .= "AND p.category = :category ";
		}
		
		$sql .= "ORDER BY p.description";	
			
		// get the database connection
		$db = EP_Util_Database::pdo_connect();
		$sth = $db->prepare( $sql );
// print_r( $db->errorInfo() );

		// bind params
		$sth->bindParam( ':state', $state, PDO::PARAM_INT);
		
		if ( $category != null )
		{
			$sth->bindParam( ':category', $category, PDO::PARAM_INT);
		} 

		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Partner' );
		}

		$sth->execute();
		$arr = $sth->fetchAll();
		return $arr;
	}
	
	function fetch( $id )
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT * ";
		$sql .= " FROM partnercode ";
		$sql .= " WHERE id = :id ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Partner' );
		}

		$sth->bindParam( ':id', $id, PDO::PARAM_INT);
		
		$sth->execute();
		$result = $sth->fetch();
		if ( !$result )
		{
			return false;
		}
		$sth->closeCursor();
		
		// add the partner_variables
		$sql = "SELECT * FROM partner_variables ";
		$sql .= "WHERE partner_id = :id ";
		$sql .= "ORDER BY variable_name ";
		$sth = $db->prepare( $sql );
		$sth->setFetchMode( PDO::FETCH_OBJ );
		$sth->bindParam( ':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$varResult = $sth->fetchAll();
		if ( $varResult )
		{
			// arrange the variables to use their name as key
			$arr = array();
			foreach ( $varResult as $num => $var )
			{
				$arr[$var->variable_name] = $var;
			}	
			$result->setVariables( $arr );
		}
		
		$sth->closeCursor();
		// $vars = getList('partner_variables',' where partner_id='.$prec->id.' order by variable_name');
		/*
         [22] => Array
                (
                    [id] => 324
                    [partner_id] => 43
                    [variable_name] => website
                    [variable_value] => http://www.aa.com
                    [help_text] => The full URL of the partner web site, e.g., http://www.aa.com
                    [new] => 0
                )
		*/
                
		return $result;
	}
	
	function fetchAll( $fields = array('*'), $orderBy = array('description') )
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM partnercode ";
		$sql .= "ORDER BY " . implode( ',', $orderBy );	
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Partner' );
		}

		$sth->execute();
		$arr = $sth->fetchAll();
		return $arr;
	}
}





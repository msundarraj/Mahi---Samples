<?php
/**
 * Description of Partners
 *
 * @author zaguanno
 */
require_once "$base_dir/config.php";
require_once "EP/Util/Database.php";
require_once "EP/Helpers/Records.class.php";

class Partners {
    /**
	 * DB Connection
	 */
	private $PDODB;
	
	/**
	 * Count of PriceCodes
	 */
	public $PriceCodeCount;

	/**
	 * __construct()
	 * Constructor of Partners Class
	 */
    public function  __construct() {
		// Connect to the DB
		$this->PDODB = EP_Util_Database::pdo_connect();
	}

	/**
	 * getPartnerByDirState()
	 * Gets an individual partner object by DIR and State
	 *
	 * @param string $dir
	 * @param int $state
	 * @return object $PartnercodeObject
	 */
	public function getPartnerByDirState($dir, $state) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM partnercode WHERE partner_dir = :dir AND state = :state ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":dir", $dir);
		$statement->bindValue(":state", $state);

		// Execute the query
		$statement->execute();

		// Fetch object
		$PartnercodeObject = $statement->fetchObject();

		// Return the object
		return $PartnercodeObject;
	}

	/**
	 * getPartnerByPartnercodeState()
	 * Gets an individual partner object by Partnercode and State
	 *
	 * @param string $partnercode
	 * @param int $state
	 * @param string $cellcode [optional]
	 * @param bool $referral [optional] Defaults to false
	 * @return object $PartnercodeObject
	 */
	public function getPartnerByPartnercodeState($partnercode, $state, $cellcode = "", $referral = false) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM partnercode WHERE partnercode = :partnercode AND state = :state ";
		if($cellcode != "")
		{
			$sql .= "AND default_cellcode = :cellcode ";
		}
		if($referral == true)
		{
			$sql .= "AND use_referral = 1 ";
		}
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnercode", $partnercode);
		$statement->bindValue(":state", $state);
		if($cellcode != "")
		{
			$statement->bindValue(":cellcode", $cellcode);
		}

		// Execute the query
		$statement->execute();

		// Fetch object
		$PartnercodeObject = $statement->fetchObject();

		// Return the object
		return $PartnercodeObject;
	}

	/**
	 * getPartnerByID()
	 * Gets an individual partner object by ID
	 *
	 * @param string $partnerID
	 * @return object $PartnercodeObject
	 */
	public function getPartnerByID($partnerID) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM partnercode WHERE id = :partnerID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnerID", $partnerID);

		// Execute the query
		$statement->execute();

		// Fetch object
		$PartnercodeObject = $statement->fetchObject();

		// Return the object
		return $PartnercodeObject;
	}

	/**
	 * getReferralPartnerByRefcode()
	 * Gets an individual referral partner object by refcode
	 *
	 * @param string $refcode
	 * @return object $ReferralObject
	 */
	public function getReferralPartnerByRefcode($refcode) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM referal_partners WHERE refcode = :refcode ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":refcode", $refcode);

		// Execute the query
		$statement->execute();

		// Fetch object
		$ReferralObject = $statement->fetchObject();

		// Return the object
		return $ReferralObject;
	}

	/**
	 * getReferralPartnerByRefID()
	 * Gets an individual referral partner object by refcode
	 *
	 * @param string $refID
	 * @return object $ReferralObject
	 */
	public function getReferralPartnerByRefID($refID) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM referral_partners WHERE refid = :refid ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":refid", $refID);

		// Execute the query
		$statement->execute();

		// Fetch object
		$ReferralObject = $statement->fetchObject();

		// Return the object
		return $ReferralObject;
	}

	/**
	 * getCampaignByID()
	 * Gets an individual campaign object by ID
	 *
	 * @param string $campaignID
	 * @return object $CampaignObject
	 */
	public function getCampaignByID($campaignID) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM partner_campaign WHERE id = :campaignID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":campaignID", $campaignID);

		// Execute the query
		$statement->execute();

		// Fetch object
		$CampaignObject = $statement->fetchObject();

		// Return the object
		return $CampaignObject;
	}

	/**
	 * getPartnerByCampaignID()
	 * Gets an individual partner object by campaignID
	 *
	 * @param string $campaignID
	 * @return object $PartnercodeObject
	 */
	public function getPartnerByCampaignID($campaignID) {
		// Build query, prep, and bind
		$sql = "SELECT partnercode.* FROM partnercode 
			LEFT JOIN partner_campaign ON partnercode.id = partner_campaign.partnerid 
			WHERE partner_campaign.id = :campaignID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":campaignID", $campaignID);

		// Execute the query
		$statement->execute();

		// Fetch object
		$PartnercodeObject = $statement->fetchObject();

		// Return the object
		return $PartnercodeObject;
	}

	/**
	 * getCampaignByPromoID()
	 * Gets an individual campaign object by promoID
	 *
	 * @param string $promoID
	 * @return object $CampaignObject
	 */
	public function getCampaignByPromoID($promoID) {
		// Build query, prep, and bind
		$sql = "SELECT partner_campaign.* FROM partner_campaign 
			LEFT JOIN partner_promocode ON partner_campaign.id = partner_promocode.campaignid 
			WHERE partner_promocode.id = :promoID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":promoID", $promoID);

		// Execute the query
		$statement->execute();

		// Fetch object
		$CampaignObject = $statement->fetchObject();

		// Return the object
		return $CampaignObject;
	}

	/**
	 * getPartnersWithRegisterCountsByStates()
	 * Gets all partners and their associated register counts for the given states
	 *
	 * @param array $states
	 * @return array $partners
	 */
	public function getPartnersWithRegisterCountsByStates($states) {
		//Initialize variables
		$partners = array();
		
		// Build query, prep, and bind
		$sql = "SELECT states.abbrev, COUNT(register.id) AS registerCount, partnercode.* FROM partnercode 
			LEFT JOIN states ON partnercode.state = states.id 
			LEFT JOIN register ON (partnercode.partnercode = register.partnercode AND partnercode.state = register.stateid) 
			WHERE partnercode.state IN (";
		// Add state identifiers
		for($i = 0; $i < count($states); $i++)
		{
			$sql .= ":state_$i, ";
		}
		$sql = rtrim($sql, ", ");
		$sql .= ") 
			GROUP BY partnercode.id 
			ORDER BY sequence ";
		
		$statement = $this->PDODB->prepare($sql);
		
		for($i = 0; $i < count($states); $i++)
		{
			$statement->bindValue(":state_$i", $states[$i]);
		}

		// Execute the query
		$statement->execute();

		// Fetch the results
		$partners = $statement->fetchAll(PDO::FETCH_CLASS);
		
		// Return partner list
		return $partners;
	}
	
	/**
	 * getPromocodesByCampaign()
	 * Get a list of promocode for a given campaign
	 * 
	 * @param string $campaign
	 * @param string $partnercode [optional]
	 * @return array $promocodes
	 */
	public function getPromocodesByCampaign($campaign, $partnercode = "") {
		$sql = "SELECT DISTINCT promocode FROM register 
			WHERE campaign = :campaign ";
		if($partnercode != "")
		{
			$sql .= "AND partnercode = :partnercode ";
		}
		$sql .= "ORDER BY promocode ";
		$statement = $this->PDODB->prepare($sql);
		
		$statement->bindValue(":campaign", $campaign);
		if($partnercode != "")
		{
			$statement->bindValue(":partnercode", $partnercode);
		}

		// Execute the query
		$statement->execute();

		// Fetch the results
		$promocodes = $statement->fetchAll(PDO::FETCH_CLASS);
		
		// Return promocodes list
		return $promocodes;
	}

	/**
	 * getPartnercodeByDir()
	 * Gets the partnercode from the DIR
	 *
	 * @param string $dir Partner_dir
	 * @return string $partnercode
	 */
	public function getPartnercodeByDir($dir) {
		$partnercode = "";
		
		// Build query, prep, and bind
		$sql = "SELECT partnercode FROM partnercode WHERE partner_dir = :dir ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":dir", $dir);

		// Execute the query
		$statement->execute();

		// Fetch object
		$PartnercodeObject = $statement->fetchObject();

		if(is_object($PartnercodeObject))
		{
			$partnercode = $PartnercodeObject->partnercode;
		}

		// Return partnercode
		return $partnercode;
	}

	/**
	 * getAffinityByDir()
	 * Gets the affinity from the DIR
	 *
	 * @param string $dir Partner_dir
	 * @return string $affinity
	 */
	public function getAffinityByDir($dir) {
		// Build query, prep, and bind
		$sql = "SELECT affinity FROM partnercode WHERE partner_dir = :dir ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":dir", $dir);

		// Execute the query
		$statement->execute();

		// Fetch object
		$PartnercodeObject = $statement->fetchObject();

		$affinity = $PartnercodeObject->affinity;

		// Return affinity
		return $affinity;
	}

	/**
	 * getAffinityByPartnercode()
	 * Gets the affinity from the partnercode
	 *
	 * @param string $partnercode Partnercode
	 * @return string $affinity
	 */
	public function getAffinityByPartnercode($partnercode) {
		// Build query, prep, and bind
		$sql = "SELECT affinity FROM partnercode WHERE partnercode = :partnercode ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnercode", $partnercode);

		// Execute the query
		$statement->execute();

		// Fetch object
		$PartnercodeObject = $statement->fetchObject();

		$affinity = $PartnercodeObject->affinity;

		// Return affinity
		return $affinity;
	}

	/**
	 * getCampaignsByPartnerID()
	 * Gets the campaign list from the partner ID
	 *
	 * @param string $partnerID
	 * @param book $fullList [optional] Defaults to false
	 * @return array $campaigns
	 */
	public function getCampaignsByPartnerID($partnerID, $fullList = false) {
		//Initialize variables
		$campaigns = array();

		// Build query, prep, and bind
		if($fullList == true)
		{
			$sql = "SELECT * FROM partner_campaign WHERE partnerid = :partnerID ORDER BY campaign_code ";
		}
		else
		{
			$sql = "SELECT DISTINCT c.campaign_code, c.id, c.campaign_desc
				FROM partner_campaign c, partner_promocode p
				WHERE p.campaignid = c.id AND p.show_inbound = 1 AND c.partnerid = :partnerID
				ORDER BY campaign_code ";
		}
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnerID", $partnerID);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$campaigns = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return campaign list
		return $campaigns;
	}

	/**
	 * getPromosByCampaignID()
	 * Gets the promo list from the campaign ID
	 *
	 * @param string $campaignID
	 * @param int $showInbound [optional]
	 * @return array $promos
	 */
	public function getPromosByCampaignID($campaignID, $showInbound = 0) {
		//Initialize variables
		$promos = array();

		// Build query, prep, and bind
		$sql = "SELECT * FROM partner_promocode WHERE campaignid = :campaignID ";
		if($showInbound == 1)
		{
			$sql .= "AND show_inbound = 1 ";
		}
		$sql .= "ORDER BY promocode ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":campaignID", $campaignID);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$promos = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return promo list
		return $promos;
	}

	/**
	 * getInboundCellcode()
	 * Gets the data from inbound_cellcode or inbound_htld_cellcode
	 *
	 * @param string $dir Partner_dir
	 * @param int $htldFlag [optional]
	 * @return string $cellcodeContents
	 */
	public function getInboundCellcode($dir, $htldFlag = 0) {
		//Initialize variables
		$cellField = "inbound_cellcode";

		// Check htld flag
		if($htldFlag != 0)
		{
			$cellField = "inbound_htld_cellcode";
		}

		// Build query, prep, and bind
		$sql = "SELECT $cellField FROM partnercode WHERE partner_dir = :dir ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":dir", $dir);

		// Execute the query
		$statement->execute();

		// Fetch object
		$PartnercodeObject = $statement->fetchObject();

		$cellcodeContents = $PartnercodeObject->$cellField;

		// Return the cell contents
		return $cellcodeContents;
	}
	
	/**
	 * getDistinctRegisterValues()
	 * Get a list of unique column values from the register table
	 * 
	 * @param string $columnName
	 * @param bool $orderBy [optional]
	 * @param string $partnercode [optional]
	 * @return array $uniqueColumnValues
	 */
	public function getDistinctRegisterValues($columnName, $orderBy = false, $partnercode = "") {
		// Build query, prep, and bind
		$sql = "SELECT DISTINCT $columnName FROM register ";
		if($partnercode != "")
		{
			$sql .= "WHERE partnercode = :partnercode ";
		}
		if($orderBy == true)
		{
			$sql .= "ORDER BY $columnName ";
		}
		$statement = $this->PDODB->prepare($sql);
		if($partnercode != "")
		{
			$statement->bindValue(":partnercode", $partnercode);
		}

		// Execute the query
		$statement->execute();

		// Fetch the results
		$uniqueColumnValues = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the list of Unique Column Values
		return $uniqueColumnValues;
	}
	
	/**
	 * getCampaignCodesByPartnerID()
	 * Gets the list of CampaignCodes for a given partner
	 * 
	 * @param int $partnerID
	 * @return array $campaignCodes
	 */
	public function getCampaignCodesByPartnerID($partnerID) {
		// Build query, prep, and bind
		$sql = "SELECT campaign_code FROM partner_campaign WHERE partnerid = :partnerID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnerID", $partnerID);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$campaignCodes = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the list of CampaignCodes
		return $campaignCodes;
	}
	
	/**
	 * getPartnersByStateID()
	 * Gets the list of partners for a given state
	 * 
	 * @param int $stateID
	 * @param string $orderBy [optional]
	 * @param string $refMode [optional] Defaults to false
	 * @param string $refDealer [optional] Defaults to false
	 * @return array $partners
	 */
	public function getPartnersByStateID($stateID, $orderBy = "", $refMode = false, $refDealer = false) {
		$partners = array();
		
		// Build query, prep, and bind
		$sql = "SELECT * FROM partnercode WHERE state = :stateID ";
		
		if($refMode == true)
		{
			$sql .= "AND use_referral = 1 ";
		}
		elseif($refDealer == true)
		{
			$sql .= "AND referral_dealer = 1 ";
		}
		
		if($orderBy != "")
		{
			$sql .= "ORDER BY $orderBy ";
		}
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":stateID", $stateID);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$partners = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the list of Partners
		return $partners;
	}
	
	/**
	 * getInboundPartnersByStateID()
	 * Gets the list of partners for a given state
	 * 
	 * @param int $stateID
	 * @param string $orderBy [optional]
	 * @return array $partners
	 */
	public function getInboundPartnersByStateID($stateID, $orderBy = "") {
		$partners = array();
		
		// Build query, prep, and bind
		$sql = "SELECT partnercode.* FROM partnercode 
			LEFT JOIN states ON partnercode.state = states.id 
			WHERE partnercode.allow_inbound = 1 
			AND states.active = 1 
			AND states.id = :stateID ";
				
		if($orderBy != "")
		{
			$sql .= "ORDER BY $orderBy ";
		}
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":stateID", $stateID);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$partners = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the list of Partners
		return $partners;
	}
	
	/**
	 * getPartnersByStateAbbrev()
	 * Gets the list of partners for a given state
	 * 
	 * @param string $stateAbbrev
	 * @param string $orderBy [optional]
	 * @return array $partners
	 */
	public function getPartnersByStateAbbrev($stateAbbrev, $orderBy = "") {
		$partners = array();
		
		// Build query, prep, and bind
		$sql = "SELECT states.abbrev, partnercode.* FROM partnercode 
			LEFT JOIN states ON partnercode.state = states.id 
			WHERE states.abbrev = :stateAbbrev ";
		if($orderBy != "")
		{
			$sql .= "ORDER BY $orderBy ";
		}
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":stateAbbrev", $stateAbbrev);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$partners = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the list of Partners
		return $partners;
	}
	
	/**
	 * getAllPartners()
	 * Gets the list of all partners
	 * 
	 * @param string $orderBy [optional]
	 * @return array $partners
	 */
	public function getAllPartners($orderBy = "") {
		$partners = array();
		
		// Build query, prep, and bind
		$sql = "SELECT states.abbrev, partnercode.* FROM partnercode 
			LEFT JOIN states ON partnercode.state = states.id ";
		if($orderBy != "")
		{
			$sql .= "ORDER BY partnercode.$orderBy ";
		}
		$statement = $this->PDODB->prepare($sql);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$partners = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the list of Partners
		return $partners;
	}
	
	/**
	 * getUniquePartners()
	 * Gets the list of unique partners
	 * 
	 * @param string $orderBy [optional]
	 * @return array $partners
	 */
	public function getUniquePartners($orderBy = "") {
		$partners = array();
		
		// Build query, prep, and bind
		$sql = "SELECT DISTINCT partnercode, partner_dir, description FROM partnercode ";
		if($orderBy != "")
		{
			$sql .= "ORDER BY $orderBy ";
		}
		$statement = $this->PDODB->prepare($sql);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$partners = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the list of Partners
		return $partners;
	}
	
	/**
	 * getUniqueReferralPartnersByPartnerID()
	 * Gets the list of unique referral partners
	 * 
	 * @param int $partnerID
	 * @param string $orderBy [optional]
	 * @return array $partners
	 */
	public function getUniqueReferralPartnersByPartnerID($partnerID, $orderBy = "") {
		$partners = array();
		
		// Build query, prep, and bind
		$sql = "SELECT DISTINCT busname, refid FROM referral_partners 
			WHERE in_list = 1 AND base_partnerid = :partnerID ";
		if($orderBy != "")
		{
			$sql .= "ORDER BY $orderBy ";
		}
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnerID", $partnerID);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$partners = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the list of Partners
		return $partners;
	}
	
	/**
	 * getUniquePromosByPartnerID()
	 * Gets the list of unique promos for a given partner
	 * 
	 * @param int $partnerID
	 * @param string $orderBy [optional]
	 * @return array $promos
	 */
	public function getUniquePromosByPartnerID($partnerID, $orderBy = "") {
		$partners = array();
		
		// Build query, prep, and bind
		$sql = "SELECT DISTINCT promocode, promodesc FROM partner_promocode 
			WHERE partnerid = :partnerID ";
		if($orderBy != "")
		{
			$sql .= "ORDER BY $orderBy ";
		}
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnerID", $partnerID);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$promos = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the list of Promos
		return $promos;
	}
	
	/**
	 * getUniqueRegisterCleansedPartners()
	 * Gets the list of unique partners
	 * 
	 * @param string $tableCode cln = cleansed, reg = register
	 * @param string $state
	 * @return array $partners
	 */
	public function getUniqueRegisterCleansedPartners($tableCode, $state) {
		$partners = array();
		
		$tableName = "register";
		if($tableCode == "cln")
		{
			$tableName = "cleansed";
		}
		
		// Build query, prep, and bind
		$sql = "SELECT DISTINCT $tableName.partnercode, description
			FROM $tableName 
			LEFT JOIN states ON $tableName.state = states.abbrev
			LEFT JOIN partnercode ON $tableName.partnercode = partnercode.partnercode
			AND states.id = partnercode.state
			WHERE $tableName.state = :state 
			ORDER BY $tableName.partnercode ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":state", $state);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$partners = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the list of Partners
		return $partners;
	}
	
	/**
	 * isPartnerGas()
	 * Check if Partner has gas option
	 * 
	 * @param string $partnerDir
	 * @return bool $gasCheck
	 */
	public function isPartnerGas($partnerDir) {
		// Build query, prep, and bind
		$sql = "SELECT has_gas_option FROM partnercode WHERE partner_dir = :partnerDir ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnerDir", $partnerDir);

		// Execute the query
		$statement->execute();

		// Fetch object
		$PartnerObject = $statement->fetchObject();
		
		$gasCheck = false;
		if($PartnerObject->has_gas_option == 1)
		{
			$gasCheck = true;
		}
		
		return $gasCheck;
	}
	
	/**
	 * isCampaignGas()
	 * Check if Campaign has gas option
	 * 
	 * @param string $campaignID
	 * @return bool $gasCheck
	 */
	public function isCampaignGas($campaignID) {
		// Build query, prep, and bind
		$sql = "SELECT has_gas_option FROM partner_campaign WHERE id = :campaignID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":campaignID", $campaignID);

		// Execute the query
		$statement->execute();

		// Fetch object
		$PartnerObject = $statement->fetchObject();
		
		$gasCheck = false;
		if($PartnerObject->has_gas_option == 1)
		{
			$gasCheck = true;
		}
		
		return $gasCheck;
	}
	
	/**
	 * addCampaign()
	 * Adds a new campaign
	 * 
	 * @param int $partnerID
	 * @param string $code
	 * @param string $description
	 */
	public function addCampaign($partnerID, $code, $description) {
		// Build query, prep, and bind
		$sql = "INSERT INTO partner_campaign (partnerid, campaign_code, campaign_desc, comments, has_gas_option)
			VALUES (:partnerID, :code, :description, '', 0) ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnerID", $partnerID);
		$statement->bindValue(":code", $code);
		$statement->bindValue(":description", $description);

		// Execute the query
		$statement->execute();
	}
	
	/**
	 * removeCampaign()
	 * Deletes a campaign and associated promos
	 * 
	 * @param int $campaignID
	 */
	public function removeCampaign($campaignID) {	
		// Build query, prep, and bind
		$sql = "DELETE FROM partner_campaign WHERE id = :campaignID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":campaignID", $campaignID);

		// Execute the query
		$statement->execute();
		
		// Build next query, prep, and bind
		$sql = "DELETE FROM partner_promocode WHERE campaignid = :campaignID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":campaignID", $campaignID);

		// Execute the query
		$statement->execute();
	}
	
	/**
	 * editCampaign()
	 * Edits a given campaign
	 * 
	 * @param int $campaignID
	 * @param string $code
	 * @param string $description
	 */
	public function editCampaign($campaignID, $code, $description) {
		// Build query, prep, and bind
		$sql = "UPDATE partner_campaign 
			SET campaign_code = :code, campaign_desc = :description 
			WHERE id = :campaignID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":campaignID", $campaignID);
		$statement->bindValue(":code", $code);
		$statement->bindValue(":description", $description);

		// Execute the query
		$statement->execute();
	}
	
	/**
	 * addPromo()
	 * Adds a new promo
	 * 
	 * @param int $partnerID
	 * @param int $campaignID
	 * @param string $code
	 * @param string $description
	 * @param int $inbound
	 */
	public function addPromo($partnerID, $campaignID, $code, $description, $inbound) {
		// Build query, prep, and bind
		$sql = "INSERT INTO partner_promocode (partnerid, campaignid, promocode, promodesc, show_inbound)
			VALUES (:partnerID, :campaignID, :code, :description, :inbound) ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnerID", $partnerID);
		$statement->bindValue(":campaignID", $campaignID);
		$statement->bindValue(":code", $code);
		$statement->bindValue(":description", $description);
		$statement->bindValue(":inbound", $inbound);

		// Execute the query
		$statement->execute();
	}
	
	/**
	 * removePromo()
	 * Deletes a promo
	 * 
	 * @param int $promoID
	 */
	public function removePromo($promoID) {	
		// Build query, prep, and bind
		$sql = "DELETE FROM partner_promocode WHERE id = :promoID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":promoID", $promoID);

		// Execute the query
		$statement->execute();
	}
	
	/**
	 * editPromo()
	 * Edits a given promo
	 * 
	 * @param int $promoID
	 * @param string $code
	 * @param string $description
	 * @param int $inbound
	 */
	public function editPromo($promoID, $code, $description, $inbound) {
//      echo $sql;
		// Build query, prep, and bind
		$sql = "UPDATE partner_promocode 
			SET promocode = :code, promodesc = :description, show_inbound = :inbound 
			WHERE id = :promoID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":promoID", $promoID);
		$statement->bindValue(":code", $code);
		$statement->bindValue(":description", $description);
		$statement->bindValue(":inbound", $inbound);

		// Execute the query
		$statement->execute();
	}
	
	/**
	 * addReferralPartner()
	 * Adds a new Referral Partner
	 * 
	 * @param array $postedValues
	 * @return int $referralPartnerID
	 */
	public function addReferralPartner($postedValues) {
		$entryTime = time();
		
		$sql = "INSERT INTO referral_partners (fname, lname, busname, billing_address, bcity, billing_state, bzip1, bzip2, 
			phone1, phone2, phone3, email, termsagree, entry_date, in_list, active) 
			VALUES (:firstName, :lastName, :businessName, :address, :city, :state, :zip5, :zip4, :phone1, :phone2, :phone3, :email, :terms, :entryDate, 0, 1) ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":firstName", $postedValues['fname']);
		$statement->bindValue(":lastName", $postedValues['lname']);
		$statement->bindValue(":businessName", $postedValues['busname']);
		$statement->bindValue(":address", $postedValues['billing_address']);
		$statement->bindValue(":city", $postedValues['bcity']);
		$statement->bindValue(":state", $postedValues['billing_state']);
		$statement->bindValue(":zip5", $postedValues['bzip1']);
		$statement->bindValue(":zip4", $postedValues['bzip2']);
		$statement->bindValue(":phone1", $postedValues['phone1']);
		$statement->bindValue(":phone2", $postedValues['phone2']);
		$statement->bindValue(":phone3", $postedValues['phone3']);
		$statement->bindValue(":email", $postedValues['email']);
		$statement->bindValue(":terms", $postedValues['termsagree']);
		$statement->bindValue(":entryDate", $entryDate);

		// Execute the query
		$statement->execute();
		
		$referralPartnerID = $this->PDODB->lastInsertID();

		$refID = $referralPartnerID + 1925;
		
		if(isset($postedValues['bussel']))
		{
			$refID = $postedValues['bussel'];
		}
		
		$this->editReferralPartnerRefID($refID, $referralPartnerID);
		
		return $referralPartnerID;
	}
	
	/**
	 * editReferralPartnerRefID()
	 * Edit the RefID for a given Referral Partner
	 * 
	 * @param int $refID
	 * @param int $referralPartnerID
	 */
	private function editReferralPartnerRefID($refID, $referralPartnerID) {
		$sql = "UPDATE referral_partners SET refid = :refID WHERE id = :referralPartnerID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":refID", $refID);
		$statement->bindValue(":referralPartnerID", $referralPartnerID);

		// Execute the query
		$statement->execute();
	}
	
	/**
	 * addStarburst()
	 * Adds a starburst value
	 * 
	 * @param array $postedValues
	 * @param string $imagePath
	 */
	public function addStarburst($postedValues, $imagePath) {
		$sql = "INSERT INTO starburst_variables (partner_id, promo_trigger, text1_miles, text2_miles, 
			imagepath, bullet_snippet, bullet_snippet2, disc_sentence, resaffin, bizaffin, aff_biz_ongoing, aff_res_ongoing, 
			footnote, ib_desc, bonus_mon, is_gas, award_mons) 
			VALUES (:partnerID, :promoTrigger, :text1Miles, :text2Miles, :imagePath, :bulletSnippet, :bulletSnippet2, 
			:discSentence, :resAffin, :bizAffin, :affBizOngoing, :affResOngoing, :footnote, :ibDesc, :bonusMon, :isGas, :awardMons) ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnerID", $postedValues['partner_id']);
		$statement->bindValue(":promoTrigger", $postedValues['promo_trigger']);
		$statement->bindValue(":text1Miles", $postedValues['text1_miles']);
		$statement->bindValue(":text2Miles", $postedValues['text2_miles']);
		$statement->bindValue(":imagePath", $imagePath);
		$statement->bindValue(":bulletSnippet", $postedValues['bullet_snippet']);
		$statement->bindValue(":bulletSnippet2", $postedValues['bullet_snippet2']);
		$statement->bindValue(":discSentence", $postedValues['disc_sentence']);
		$statement->bindValue(":resAffin", $postedValues['resaffin']);
		$statement->bindValue(":bizAffin", $postedValues['bizaffin']);
		$statement->bindValue(":affBizOngoing", $postedValues['aff_biz_ongoing']);
		$statement->bindValue(":affResOngoing", $postedValues['aff_res_ongoing']);
		$statement->bindValue(":footnote", $postedValues['footnote']);
		$statement->bindValue(":ibDesc", $postedValues['ib_desc']);
		$statement->bindValue(":bonusMon", $postedValues['bonus_mon']);
		$statement->bindValue(":isGas", $postedValues['is_gas']);
		$statement->bindValue(":awardMons", $postedValues['award_mons']);

		// Execute the query
		$statement->execute();
		
		$starburstID = $this->PDODB->lastInsertID();
		
		$RecordsClass = new Records();
		$RecordsClass->addDatabaseAccessRecord("starburst_variables", "Starburst ID $starburstID added.");
	}
	
	/**
	 * removeStarburst()
	 * Remove a Starburst Value by ID
	 * 
	 * @param int $starburstID
	 */
	public function removeStarburst($starburstID) {
		$sql = "DELETE FROM starburst_variables WHERE id = :starburstID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":starburstID", $starburstID);

		// Execute the query
		$statement->execute();
	}
	
	/**
	 * editStarburst()
	 * Edits a starburst value
	 * 
	 * @param array $postedValues
	 * @param int $starburstID
	 */
	public function editStarburst($postedValues, $starburstID) {
		$sql = "UPDATE starburst_variables SET 
			text1_miles = :text1Miles, 
			text2_miles = :text2Miles, ";
		if(isset($postedValues['imagepath']))
		{
			$sql .= "imagepath = :imagePath, ";
		}
		$sql .= "bullet_snippet = :bulletSnippet, 
			bullet_snippet2 = :bulletSnippet2, 
			disc_sentence = :discSentence, 
			resaffin = :resAffin, 
			bizaffin = :bizAffin, 
			aff_biz_ongoing = :affBizOngoing, 
			aff_res_ongoing = :affResOngoing, 
			footnote = :footnote, 
			ib_desc = :ibDesc, 
			bonus_mon = :bonusMon, 
			award_mons = :awardMons 
			WHERE id = :starburstID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":text1Miles", $postedValues['text1_miles']);
		$statement->bindValue(":text2Miles", $postedValues['text2_miles']);
		if(isset($postedValues['imagepath']))
		{
			$statement->bindValue(":imagePath", $postedValues['imagepath']);
		}
		$statement->bindValue(":bulletSnippet", $postedValues['bullet_snippet']);
		$statement->bindValue(":bulletSnippet2", $postedValues['bullet_snippet2']);
		$statement->bindValue(":discSentence", $postedValues['disc_sentence']);
		$statement->bindValue(":resAffin", $postedValues['resaffin']);
		$statement->bindValue(":bizAffin", $postedValues['bizaffin']);
		$statement->bindValue(":affBizOngoing", $postedValues['aff_biz_ongoing']);
		$statement->bindValue(":affResOngoing", $postedValues['aff_res_ongoing']);
		$statement->bindValue(":footnote", $postedValues['footnote']);
		$statement->bindValue(":ibDesc", $postedValues['ib_desc']);
		$statement->bindValue(":bonusMon", $postedValues['bonus_mon']);
		$statement->bindValue(":awardMons", $postedValues['award_mons']);
		$statement->bindValue(":starburstID", $starburstID);

		// Execute the query
		$statement->execute();
		
		$RecordsClass = new Records();
		$RecordsClass->addDatabaseAccessRecord("starburst_variables", "Record $starburstID updated.");
	}

	/**
	 * getStarburstsByPartnerID()
	 * Gets starburst variables for a given partner
	 *
	 * @param string $partnerID
	 * @param string $orderBy [optional]
	 * @param string $isGas [optional]
	 * @param string $promoTrigger [optional]
	 * @return array $starburstVariables
	 */
	public function getStarburstsByPartnerID($partnerID, $orderBy = "", $isGas = "", $promoTrigger = "") {
		$starburstVariables = array();
		
		// Build query, prep, and bind
		$sql = "SELECT * FROM starburst_variables WHERE partner_id = :partnerID ";
		if($isGas != "")
		{
			$sql .= "AND is_gas = :isGas ";
		}
		if($promoTrigger != "")
		{
			$sql .= "AND promo_trigger = :promoTrigger ";
		}
		if($orderBy != "")
		{
			$sql .= "ORDER BY $orderBy ";
		}
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnerID", $partnerID);
		if($isGas != "")
		{
			$statement->bindValue(":isGas", $isGas);
		}
		if($promoTrigger != "")
		{
			$statement->bindValue(":promoTrigger", $promoTrigger);
		}

		// Execute the query
		$statement->execute();

		// Fetch objects
		$starburstVariables = $statement->fetchAll(PDO::FETCH_CLASS);
		
		if(count($starburstVariables) == 1)
		{
			$starburstVariables = $starburstVariables[0];
		}

		// Return the starburst variables
		return $starburstVariables;
	}
	
	/**
	 * getStarburstColumns()
	 * Gets the list of used columns in srarburst_variables
	 * 
	 * @return array $starburstUsedColumns
	 */
	public function getStarburstColumns() {
		$ignoredColumns = array("id", "partner_id", "new", "image_miles", "is_gas");
		$starburstUsedColumns = array();
		
		$sql = "SHOW COLUMNS FROM starburst_variables ";
		$statement = $this->PDODB->prepare($sql);
		$statement->execute();
		$StarburstColumns = $statement->fetchAll(PDO::FETCH_CLASS);
		foreach($StarburstColumns AS $StarburstColumn)
		{
			if(!in_array($StarburstColumn->Field, $ignoredColumns))
			{
				$starburstUsedColumns[] = $StarburstColumn->Field;
			}
		}
		
		return $starburstUsedColumns;
	}
	
	/**
	 * getPriceCodeByPartnerIDPriceCode()
	 * Gets a single pricecode by PartnerID and PriceCode
	 * 
	 * @param int $partnerID
	 * @param string $priceCode
	 * @return object $PriceCodeObject
	 */
	public function getPriceCodeByPartnerIDPriceCode($partnerID, $priceCode) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM camp_pcode_lookup WHERE partner_id = :partnerID AND pricecode = :priceCode ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnerID", $partnerID);
		$statement->bindValue(":priceCode", $priceCode);
		
		// Execute the query
		$statement->execute();

		// Fetch object
		$PriceCodeObject = $statement->fetchObject();
		
		// Return the object
		return $PriceCodeObject;
	}
	
	/**
	 * getPriceCodesByPartnerIDCampaignID()
	 * Gets a list of pricecodes by PartnerID and CampaignID
	 * 
	 * @param int $partnerID
	 * @param int $campaignID
	 * @return array $priceCodes
	 */
	public function getPriceCodesByPartnerIDCampaignID($partnerID, $campaignID) {
		$priceCodes = array();
		
		// Build query, prep, and bind
		$sql = "SELECT * FROM camp_pcode_lookup WHERE partner_id = :partnerID AND camp_id = :campaignID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnerID", $partnerID);
		$statement->bindValue(":campaignID", $campaignID);
		
		// Execute the query
		$statement->execute();

		// Fetch object
		$priceCodes = $statement->fetchAll(PDO::FETCH_CLASS);
		
		$this->PriceCodeCount = count($priceCodes);
		
		// Return the pricecodes
		return $priceCodes;
	}

	/**
	 * getReferralSettingsByRefID()
	 * Gets the referral settings based on the RefID
	 *
	 * @param int $refID
	 * @return object $ReferralSettingsObject
	 */
	public function getReferralSettingsByRefID($refID) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM refsettings WHERE refid = :refID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":refID", $refID);

		// Execute the query
		$statement->execute();

		// Fetch object
		$ReferralSettingsObject = $statement->fetchObject();

		// Return the object
		return $ReferralSettingsObject;
	}
	
	/**
	 * getOffercode()
	 * Gets the default offercode from the DB
	 * 
	 * @param string $partnerCode
	 * @param int $stateID
	 * @param int $accountType
	 * @param int $utilityID
	 * @return string $offercode
	 */
	public function getOffercode($partnerCode, $stateID, $accountType, $utilityID) {
		$PartnerObject = $this->getPartnerByPartnercodeState($partnerCode, $stateID);

		$partnerType = "cobrand";
		if($partnerCode == "BRD")
		{
			$partnerType = "brand";
		}
		elseif(is_object($PartnerObject) && $PartnerObject->affinity == 1)
		{
			$partnerType = "affinity";
		}
		
		// For TX, there is a "Large Business" category, but outside TX, there are faux big businesses
		// Treat those as regular businesses
		if($stateID != 1 && $accountType == 2)
		{
			$accountType = 1;
		}
		
		// Build query, prep, and bind
		$sql = "SELECT * FROM default_offer_mapping 
			WHERE partner_type = :partnerType AND state = :stateID 
			AND account_type = :accountType AND utility = :utilityID";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnerType", $partnerType);
		$statement->bindValue(":stateID", $stateID);
		$statement->bindValue(":accountType", $accountType);
		$statement->bindValue(":utilityID", $utilityID);

		// Execute the query
		$statement->execute();

		// Fetch object
		$OffercodeObject = $statement->fetchObject();
		
		$offercode = "";
		if(is_object($OffercodeObject))
		{
			$offercode = $OffercodeObject->offercode;
		}
		
		return $offercode;
	}
}
?>

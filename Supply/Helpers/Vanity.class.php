<?php
/**
 * Description of Vanity
 *
 * @author zaguanno
 */
require_once "$base_dir/config.php";
require_once "EP/Util/Database.php";

class Vanity {
	/**
	 * DB Connection
	 */
	private $PDODB;

	/**
	 * __construct()
	 * Constructor of Vanity Class
	 */
	public function __construct() {
		// Connect to the DB
		$this->PDODB = EP_Util_Database::pdo_connect();
	}
	
	/**
	 * getVanityList()
	 * Gets the list of vanity url objects
	 * 
	 * @return array $vanities
	 */
	public function getVanityList() {
		// Build query and prep
		$sql = "SELECT * FROM vanity ORDER BY vanity ";
		$statement = $this->PDODB->prepare($sql);
		
		// Execute the query
		$statement->execute();

		// Fetch the results
		$vanities = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the list of Vanity urls
		return $vanities;
	}
	
	/**
	 * getVanityByVanity()
	 * Gets the vanity object by vanity value
	 * 
	 * @param string $vanity
	 * @return object $VanityObject
	 */
	public function getVanityByVanity($vanity) {
		// Build query and prep
		$sql = "SELECT * FROM vanity WHERE vanity = :vanity ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":vanity", $vanity);
		
		// Execute the query
		$statement->execute();

		// Fetch the results
		$VanityObject = $statement->fetchObject();

		// Return the Vanity Object
		return $VanityObject;
	}
	
	/**
	 * removeVanityByID()
	 * Remove a specific vanity url
	 * 
	 * @param int $vanityID
	 */
	public function removeVanityByID($vanityID) {
		// Build query, prep and bind
		$sql = "DELETE FROM vanity WHERE id = :vanityID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":vanityID", $vanityID);
		
		// Execute the query
		$statement->execute();
	}
	
	/**
	 * addNewVanity()
	 * Adds a new vanity url object
	 * 
	 * @param type $state
	 * @param type $vanity
	 * @param type $pDir
	 * @param type $appType
	 * @param type $cellcode
	 * @param type $campaign
	 * @param type $promo
	 * @param type $promoBus
	 * @param type $partnercode
	 * @param type $refID
	 * @param type $offercodeRes
	 * @param type $offercodeBus
	 * @param type $offercodePV
	 * @param type $offercode
	 * @param type $mid
	 * @param type $repID 
	 */
	public function addNewVanity($state, $vanity, $pDir, $appType, $cellcode, $campaign, $promo, $promoBus, $partnercode, $refID, $offercodeRes, $offercodeBus, $offercodePV, $offercodeGS, $mid, $repID, $version, $gasPromo, $gasPromoBus) {
		// Build query, prep and bind
		$sql = "INSERT INTO vanity (state, vanity, pdir, apptype, cellcode, campaign, promo, promo_bus, 
			partnercode, refid, offercode_res, offercode_bus, offercode_pv, offercode_gs, mid, repid, 
			version, gas_promo, gas_promo_bus) 
			VALUES (:state, :vanity, :pDir, :appType, :cellcode, :campaign, :promo, :promo_bus, :partnercode, 
			:refID, :offercodeRes, :offercodeBus, :offercodePV, :offercodeGS, :mid, :repID, 
			:version, :gasPromo, :gasPromoBus) ";
		$statement = $this->PDODB->prepare($sql);
		
		$statement->bindValue(":state", $state);
		$statement->bindValue(":vanity", $vanity);
		$statement->bindValue(":pDir", $pDir);
		$statement->bindValue(":appType", $appType);
		$statement->bindValue(":cellcode", $cellcode);
		$statement->bindValue(":campaign", $campaign);
		$statement->bindValue(":promo", $promo);
		$statement->bindValue(":promo_bus", $promoBus);
		$statement->bindValue(":partnercode", $partnercode);
		$statement->bindValue(":refID", $refID);
		$statement->bindValue(":offercodeRes", $offercodeRes);
		$statement->bindValue(":offercodeBus", $offercodeBus);
		$statement->bindValue(":offercodePV", $offercodePV);
		$statement->bindValue(":offercodeGS", $offercodeGS);
		$statement->bindValue(":mid", $mid);
		$statement->bindValue(":repID", $repID);
		$statement->bindValue(":version", $version);
		$statement->bindValue(":gasPromo", $gasPromo);
		$statement->bindValue(":gasPromoBus", $gasPromoBus);
		
		// Execute the query
		$statement->execute();
	}
	
	/**
	 * buildVanityURL()
	 * Builds and returns the vanity URL from the VanityObject
	 * 
	 * @param object $VanityObject
	 * @return string $vanityURL
	 */
	public function buildVanityURL($VanityObject) {
		$vanityURL = $base_url; //'http://www.epcqa.com/';
		$vanityURL .= $VanityObject->pdir.'/'.strtolower($VanityObject->state).'?at='.$VanityObject->apptype;
		if(strlen($VanityObject->cellcode) > 0) $vanityURL .= '&cc='.$VanityObject->cellcode;
		if(strlen($VanityObject->campaign) > 0) $vanityURL .= '&ci='.$VanityObject->campaign;
		if(strlen($VanityObject->promo) > 0) $vanityURL .= '&pc='.$VanityObject->promo;
		if(strlen($VanityObject->promo_bus) > 0) $vanityURL .= '&pcb='.$VanityObject->promo_bus;
		if(strlen($VanityObject->partnercode) > 0) $vanityURL .= '&pac='.$VanityObject->partnercode;
		if(strlen($VanityObject->offercode_res) > 0) $vanityURL .= '&pr='.$VanityObject->offercode_res;
		if(strlen($VanityObject->offercode_bus) > 0) $vanityURL .= '&pb='.$VanityObject->offercode_bus;
		if(strlen($VanityObject->offercode_pv) > 0) $vanityURL .= '&pv='.$VanityObject->offercode_pv;
		if(strlen($VanityObject->offercode_gs) > 0) $vanityURL .= '&gs='.$VanityObject->offercode_gs;
		if(strlen($VanityObject->repid) > 0) $vanityURL .= '&repid='.$VanityObject->repid;
		if(strlen($VanityObject->vendorid) > 0) $vanityURL .= '&mid='.$VanityObject->vendorid;
		if(strlen($VanityObject->refid) > 0) $vanityURL .= '&refid='.$VanityObject->refid;
		
		return $vanityURL;
	}

}

?>

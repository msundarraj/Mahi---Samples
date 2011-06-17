<?php
/**
 * Description of Customers
 *
 * @author zaguanno
 */
//require_once "$base_dir/config.php";
require_once "EP/Util/Database.php";
require_once "EP/Helpers/Records.class.php";
require_once "EP/Helpers/Partners.class.php";
require_once "EP/Helpers/States.class.php";
require_once "EP/Helpers/Utilities.class.php";

class Customers {
	/**
	 * DB Connection
	 */
	private $PDODB;
	
	/**
	 * GreenApp Account Values
	 */
	private $GreenAppAccountValues = array();
	
	/**
	 * Registers Count
	 */
	public $RegistersCount;
	
	/**
	 * Application Variables
	 */
	private $ReferralPartnercodes = array('BRD', 'OHA', 'IPA', 'RLT');
	private $RequiredPostFields = array("partcode", "refid", "vendorid", "busname", "promocode", "promocodeb", "incgas", 
		"apptype", "first_name", "middle_initial", "last_name", "Suffix", "busname", "moreBusQuestions", 
		"Service_phone_number_prefix", "Service_phone_number_first", "Service_phone_number_last", "Service_Address", 
		"Service_City", "Service_Zip5", "Service_Zip4", "Local_Utility", "billing", 
		"phone_number_prefix", "phone_number_first", "phone_number_last", "Billing_Address", 
		"Billing_State", "Billing_City", "Billing_Zip5", "Billing_Zip4", "email_addr", 
		"years_inbiz", "years_bizaddr", "late_payment6", "busname_change", "elec_supp_prevyear", "years_creditbiz", 
		"Account_Number", "priceplan", "campaign", "cellcode", "Dividend_Miles_Number", "dividend_miles_level", 
		"authorize", "Multiple_Accounts", "currsupp", "pfname", "plname", "namekey", "greenopt");
	private $RequiredSessionFields = array("application" => array("state", "partner_id"), "aff", "mid", "st_abbrev", "pc", 
		"repid", "fname", "lname", "refid", "urlinfo", "budget", "op", "op2", "green");
	private $RequiredValues = array();
	private $ApplicationState;
	private $ApplicationPartner;
	private $ApplicationErrorCode;
	private $ApplicationVendor;
	private $ApplicationAccountType;
	private $ApplicationPromoCode;
	private $ApplicationRevClass;
	private $ApplicationISO;
	private $ApplicationRateClass;
	private $ApplicationPricePlan;
	private $ApplicationNumberOfAccounts;

	/**
	 * __construct()
	 * Constructor of Customers Class
	 */
	public function __construct() {
		// Connect to the DB
		$this->PDODB = EP_Util_Database::pdo_connect();
	}

	/**
	 * getRegisterByID()
	 * Gets the Register by ID
	 *
	 * @param int $registerID
	 * @return object $RegisterObject
	 */
	public function getRegisterByID($registerID) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM register WHERE id = :registerID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":registerID", $registerID);

		// Execute the query
		$statement->execute();

		// Fetch object
		$RegisterObject = $statement->fetchObject();

		// Return the object
		return $RegisterObject;
	}
	
	/**
	 * setSearchByNameFlag()
	 * Sets the flag whether first name, last name, or both is set
	 * 
	 * @param string $firstName
	 * @param string $lastName
	 * @return string $searchFlag
	 */
	private function setSearchByNameFlag($firstName, $lastName) {
		$first = $last = false;
		if(isset($firstName) && $firstName != "")
		{
			$first = true;
		}
		if(isset($lastName) && $lastName != "")
		{
			$last = true;
		}
		
		if($first == true && $last == true)
		{
			$searchFlag = "BOTH";
		}
		elseif($first == true && $last == false)
		{
			$searchFlag = "FIRST";
		}
		elseif($first == false && $last == true)
		{
			$searchFlag = "LAST";
		}
		else
		{
			$searchFlag = "NONE";
		}
		
		return $searchFlag;
	}

	/**
	 * getRegistersByName()
	 * Gets the Register Records by Name Match
	 *
	 * @param string $firstName
	 * @param string $lastName
	 * @return array $registers
	 */
	public function getRegistersByName($firstName, $lastName) {
		$registers = array();
		
		$searchFlag = $this->setSearchByNameFlag($firstName, $lastName);
		
		// Build query, prep, and bind
		$sql = "SELECT * FROM register ";
		switch($searchFlag)
		{
			case "BOTH":
				$sql .= "WHERE first_name LIKE :firstName AND last_name LIKE :lastName ";
				break;
			case "FIRST":
				$sql .= "WHERE first_name LIKE :firstName ";
				break;
			case "LAST":
				$sql .= "WHERE last_name LIKE :lastName ";
				break;
			default:
				return "No Search Terms";
				break;
		}
		$statement = $this->PDODB->prepare($sql);
		switch($searchFlag)
		{
			case "BOTH":
				$statement->bindValue(":firstName", "$firstName");
				$statement->bindValue(":lastName", "$lastName");
				break;
			case "FIRST":
				$statement->bindValue(":firstName", "$firstName");
				break;
			case "LAST":
				$statement->bindValue(":lastName", "$lastName");
				break;
		}

		// Execute the query
		$statement->execute();

		// Fetch results
		$registers = $statement->fetchAll(PDO::FETCH_CLASS);
		
		$this->RegistersCount = count($registers);

		// Return the results
		return $registers;
	}
	
	/**
	 * getAllVips()
	 * Gets the priority statuses
	 * 
	 * @param string $orderBy [optional]
	 * @return array $vips
	 */
	public function getAllVips($orderBy = "") {
		$vips = array();
		
		// Build query and prep
		$sql = "SELECT * FROM vip_priority ";
		if($orderBy != "")
		{
			$sql .= "ORDER BY $orderBy ";
		}
		$statement = $this->PDODB->prepare($sql);

		// Execute the query
		$statement->execute();
		
		// Fetch results
		$vips = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the vips
		return $vips;
	}
	
	/**
	 * editRegisterValue()
	 * Edits a Register Record in a specific field for a given value
	 * 
	 * @param int $registerID
	 * @param string $fieldName
	 * @param string $newValue
	 */
	public function editRegisterValue($registerID, $fieldName, $newValue) {
		if(!is_numeric($fieldName))
		{
			// Build query, prep and bind
			$sql = "UPDATE register SET $fieldName = :$fieldName WHERE id = :registerID ";
			$statement = $this->PDODB->prepare($sql);
			$statement->bindValue(":registerID", $registerID);
			$statement->bindValue(":$fieldName", $newValue);

			// Execute the query
			$statement->execute();
		}
	}
	
	/**
	 * buildDBPhoneNumber()
	 * Builds a DB formatted phone number from phone parts
	 * 
	 * @param int $phonePart1
	 * @param int $phonePart2
	 * @param int $phonePart3
	 * @return int $dbPhoneNumber
	 */
	private function buildDBPhoneNumber($phonePart1, $phonePart2, $phonePart3) {
		$dbPhoneNumber = $phonePart1.$phonePart2.$phonePart3;
		
		return $dbPhoneNumber;
	}
	
	/**
	 * buildDBAddressParts()
	 * Builds DB formatted address parts from full address
	 * 
	 * @param string $address
	 * @return array $dbAddressParts
	 */
	private function buildDBAddressParts($address) {
		$addressParts = array(1 => "", 2 => "", 3 => "");
		$addressLines = explode("\n", $address);
		
		for($i = 0; $i < count($addressLines); $i++)
		{
			if(strlen($addressLines[$i]) > 1)
			{
				$addressParts[$i + 1] = $addressLines[$i];
			}
		}
		
		return $addressParts;
	}
	
	/**
	 * editRegisterRecord()
	 * Edits an entire Register Record
	 * 
	 * @param array $postedValues
	 */
	public function editRegisterRecord($postedValues) {
		$servicePhone = $this->buildDBPhoneNumber($postedValues['servicephone1'], $postedValues['servicephone2'], $postedValues['servicephone3']);
		$billingPhone = $this->buildDBPhoneNumber($postedValues['bphone1'], $postedValues['bphone2'], $postedValues['bphone3']);
		
		$addressParts = $this->buildDBAddressParts($postedValues['Service_Address']);
		$billingAddressParts = $this->buildDBAddressParts($postedValues['Billing_Address']);
		
		$sql = "UPDATE register SET 
			first_name = :firstName, 
			mid_init = :middleInitial, 
			last_name = :lastName, 
			email = :email, 
			addr1 = :address1,
			addr2 = :address2,
			addr3 = :address3,
			city = :city,
			state = :state,
			zip5 = :zip5, 
			zip4 = :zip4, 
			servicephone = :servicePhone, 
			baddr1 = :billingAddress1,
			baddr2 = :billingAddress2,
			baddr3 = :billingAddress3,
			bcity = :billingCity,
			bstate = :billingState,
			bzip5 = :billingZip5, 
			bzip4 = :billingZip4, 
			billphone = :billingPhone, 
			today = :today, 
			territory_code = :territoryCode, 
			entype = :enType,
			account = :account,
			partner_memnum = :partnerMemNum, 
			pfname = :partnerFirstName, 
			plname = :partnerLastName, 
			greenopt = :greenOption, 
			noexport = :noExport,
			partnercode = :partnerCode,
			vip = :VIP, 
			promocode = :promoCode,
			vendorid = :vendorID,
			refid = :refID,
			billmeth = :billMethod,
			rateclass = :rateClass,
			distrib = :distrib, 
			introgroup = :introGroup, 
			enrollcustid = :enrollCustomerID, 
			paymeth = :payMethod, 
			mkgroup = :mkGroup 
			WHERE id = :registerID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":firstName", $postedValues['first_name']);
		$statement->bindValue(":middleInitial", $postedValues['mid_init']);
		$statement->bindValue(":lastName", $postedValues['last_name']);
		$statement->bindValue(":email", $postedValues['email']);
		$statement->bindValue(":address1", $addressParts[1]);
		$statement->bindValue(":address2", $addressParts[2]);
		$statement->bindValue(":address3", $addressParts[3]);
		$statement->bindValue(":city", $postedValues['city']);
		$statement->bindValue(":state", $postedValues['state']);
		$statement->bindValue(":zip5", $postedValues['zip5']);
		$statement->bindValue(":zip4", $postedValues['zip4']);
		$statement->bindValue(":servicePhone", $servicePhone);
		$statement->bindValue(":billingAddress1", $billingAddressParts[1]);
		$statement->bindValue(":billingAddress2", $billingAddressParts[2]);
		$statement->bindValue(":billingAddress3", $billingAddressParts[3]);
		$statement->bindValue(":billingCity", $postedValues['bcity']);
		$statement->bindValue(":billingState", $postedValues['bstate']);
		$statement->bindValue(":billingZip5", $postedValues['bzip5']);
		$statement->bindValue(":billingZip4", $postedValues['bzip4']);
		$statement->bindValue(":billingPhone", $billingPhone);
		$statement->bindValue(":today", $postedValues['today']);
		$statement->bindValue(":territoryCode", $postedValues['territory_code']);
		$statement->bindValue(":enType", $postedValues['entype']);
		$statement->bindValue(":account", $postedValues['account']);
		$statement->bindValue(":partnerMemNum", $postedValues['partner_memnum']);
		$statement->bindValue(":partnerFirstName", $postedValues['pfname']);
		$statement->bindValue(":partnerLastName", $postedValues['plname']);
		$statement->bindValue(":greenOption", $postedValues['greenopt']);
		$statement->bindValue(":noExport", $postedValues['noexport']);
		$statement->bindValue(":partnerCode", $postedValues['partnercode']);
		$statement->bindValue(":VIP", $postedValues['vip']);
		$statement->bindValue(":promoCode", $postedValues['promocode']);
		$statement->bindValue(":vendorID", $postedValues['vendorid']);
		$statement->bindValue(":refID", $postedValues['refid']);
		$statement->bindValue(":billMethod", $postedValues['billmeth']);
		$statement->bindValue(":rateClass", $postedValues['rateclass']);
		$statement->bindValue(":distrib", $postedValues['distrib']);
		$statement->bindValue(":introGroup", $postedValues['introgroup']);
		$statement->bindValue(":enrollCustomerID", $postedValues['enrollcustid']);
		$statement->bindValue(":payMethod", $postedValues['paymeth']);
		$statement->bindValue(":mkGroup", $postedValues['mkgroup']);
		
		$statement->bindValue(":registerID", $postedValues['id']);

		// Execute the query
		$statement->execute();
		
		for($i = 2; $i < 5; $i++)
		{
			if(isset($postedValues['extra_acc'.$i]))
			{
				$this->editRegisterValue($postedValues['id'.$i], "account", $postedValues['extra_acc'.$i]);
			}
		}
	}
	
	/**
	 * addRegisterRecord()
	 * 
	 * @param array $postedValues
	 * @return int $insertedID
	 */
	public function addRegisterRecord($postedValues) {
		$sql = "INSERT INTO register (accept, account, addr1, addr2, appby, apptype, auth, baddr1, baddr2, 
			bcity, billmeth, bstate, budget, bzip4, bzip5, campaign, cellcode, city, confcode, contractterm, 
			CurrentSupplier, distrib, entby, entype, FixedIntro, iso, kWh, noexport, nowtime, origurl, partnercode, 
			ProdCode, promocode, rateclass, refid, regdate, revclass, sequence, servicetype, sourceip, 
			state, stateid, taxex, today, uid, vendorid, zip5) 
			VALUES (:accept, :account, :addr1, :addr2, :appBy, :appType, :auth, :bAddr1, :bAddr2, 
			:bCity, :billMeth, :bState, :budget, :bZip4, :bZip5, :campaign, :cellcode, :city, :confCode, :contractTerm, 
			:currentSupplier, :distrib, :entBy, :enType, :fixedIntro, :iso, :kWh, :noExport, :nowTime, :origURL, :partnercode, 
			:prodCode, :promoCode, :rateClass, :refID, :regDate, :revClass, :sequence, :serviceType, :sourceIP, 
			:state, :stateID, :taxEx, :today, :uID, :vendorID, :zip5) ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":accept", $postedValues['accept']);
		$statement->bindValue(":account", $postedValues['account']);
		$statement->bindValue(":addr1", $postedValues['addr1']);
		$statement->bindValue(":addr2", $postedValues['addr2']);
		$statement->bindValue(":appBy", $postedValues['appby']);
		$statement->bindValue(":appType", $postedValues['apptype']);
		$statement->bindValue(":auth", $postedValues['auth']);
		$statement->bindValue(":bAddr1", $postedValues['baddr1']);
		$statement->bindValue(":bAddr2", $postedValues['baddr2']);
		$statement->bindValue(":bCity", $postedValues['bcity']);
		$statement->bindValue(":billMeth", $postedValues['billmeth']);
		$statement->bindValue(":bState", $postedValues['bstate']);
		$statement->bindValue(":budget", $postedValues['budget']);
		$statement->bindValue(":bZip4", $postedValues['bzip4']);
		$statement->bindValue(":bZip5", $postedValues['bzip5']);
		$statement->bindValue(":campaign", $postedValues['campaign']);
		$statement->bindValue(":cellcode", $postedValues['cellcode']);
		$statement->bindValue(":city", $postedValues['city']);
		$statement->bindValue(":confCode", $postedValues['confcode']);
		$statement->bindValue(":contractTerm", $postedValues['contractterm']);
		$statement->bindValue(":currentSupplier", $postedValues['CurrentSupplier']);
		$statement->bindValue(":distrib", $postedValues['distrib']);
		$statement->bindValue(":entBy", $postedValues['entby']);
		$statement->bindValue(":enType", $postedValues['entype']);
		$statement->bindValue(":fixedIntro", $postedValues['FixedIntro']);
		$statement->bindValue(":iso", $postedValues['iso']);
		$statement->bindValue(":kWh", $postedValues['kWh']);
		$statement->bindValue(":noExport", $postedValues['noexport']);
		$statement->bindValue(":nowTime", $postedValues['nowtime']);
		$statement->bindValue(":origURL", $postedValues['origurl']);
		$statement->bindValue(":partnercode", $postedValues['partnercode']);
		$statement->bindValue(":prodCode", $postedValues['ProdCode']);
		$statement->bindValue(":promoCode", $postedValues['promocode']);
		$statement->bindValue(":rateClass", $postedValues['rateclass']);
		$statement->bindValue(":refID", $postedValues['refid']);
		$statement->bindValue(":regDate", $postedValues['regdate']);
		$statement->bindValue(":revClass", $postedValues['revclass']);
		$statement->bindValue(":sequence", $postedValues['sequence']);
		$statement->bindValue(":serviceType", $postedValues['servicetype']);
		$statement->bindValue(":sourceIP", $postedValues['sourceip']);
		$statement->bindValue(":state", $postedValues['state']);
		$statement->bindValue(":stateID", $postedValues['stateid']);
		$statement->bindValue(":taxEx", $postedValues['taxex']);
		$statement->bindValue(":today", $postedValues['today']);
		$statement->bindValue(":uID", $postedValues['uid']);
		$statement->bindValue(":vendorID", $postedValues['vendorid']);
		$statement->bindValue(":zip5", $postedValues['zip5']);

		// Execute the query
		$statement->execute();
		
		$insertedID = $this->PDODB->lastInsertID();
		
		$RecordsClass = new Records();
		$RecordsClass->addDatabaseAccessRecord("register", "New record added.");
		
		return $insertedID;
	}
	
	/**
	 * getRegisterIDByAccountNumber()
	 * Get a Register ID by the Account Number
	 * 
	 * @param string $accountNumber
	 * @return int $registerID
	 */
	public function getRegisterIDByAccountNumber($accountNumber) {
		$RegisterObject = $this->getRegisterByAccountNumber($accountNumber);

		$registerID = 0;
		
		if(is_object($RegisterObject))
		{
			$registerID = $RegisterObject->id;
		}

		// Return register ID
		return $registerID;
	}
	
	/**
	 * getRegisterByAccountNumber()
	 * Get a Register by the Account Number
	 * 
	 * @param string $accountNumber
	 * @return object $RegisterObject
	 */
	public function getRegisterByAccountNumber($accountNumber) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM register WHERE account = :accountNumber ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":accountNumber", $accountNumber);

		// Execute the query
		$statement->execute();

		// Fetch object
		$RegisterObject = $statement->fetchObject();

		// Return register object
		return $RegisterObject;
	}
	
	/**
	 * getRegistersByConfirmationCode()
	 * Gets the register records by confirmation code
	 * 
	 * @param string $confirmationCode
	 * @param string $orderBy [optional]
	 * @return array $registers
	 */
	public function getRegistersByConfirmationCode($confirmationCode, $orderBy = "") {
		$registers = array();
		
		// Build query, prep, and bind
		$sql = "SELECT * FROM registers WHERE confcode = :confirmationCode ";
		if($orderBy != "")
		{
			$sql .= "ORDER BY $orderBy ";
		}
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":confirmationCode", $confirmationCode);

		// Execute the query
		$statement->execute();
		
		// Fetch results
		$registers = $statement->fetchAll(PDO::FETCH_CLASS);
		
		$this->RegistersCount = count($registers);

		// Return the registers
		return $registers;
	}
	
	/**
	 * addGreenAppAccount()
	 * Adds GreenApp Accounts to greenapps and greenapps_accounts
	 * 
	 * @param array $postedValues
	 */
	public function addGreenAppAccount($postedValues) {
		$this->setGreenAppAccountValues($postedValues);
		
		foreach($this->GreenAppAccountValues AS $accountValues)
		{
			$originalID = $this->getRegisterIDByAccountNumber($accountValues['account']);

			$sql = "INSERT INTO greenapps 
				(orig_app_id, firstname, lastname, telephone, email) 
				VALUES (:originalID, :firstname, :lastname, :telephone, :email) ";
			$statement = $this->PDODB->prepare($sql);

			$statement->bindValue(":originalID", $originalID);
			$statement->bindValue(":firstname", $postedValues["first_name"]);
			$statement->bindValue(":lastname", $postedValues["last_name"]);
			$statement->bindValue(":telephone", $postedValues["Phone"]);
			$statement->bindValue(":email", $postedValues["email"]);

			// Execute the query
			$statement->execute();

			$greenAppsID = $this->PDODB->lastInsertID();
		
			$this->addGreenAppAccountData($greenAppsID, $accountValues);
		}
		
	}
	
	/**
	 * setGreenAppAccountValues()
	 * Sets the values from the posted values for a new GreenApp Account
	 * 
	 * @param array $postedValues
	 */
	private function setGreenAppAccountValues($postedValues) {
		$startLoop = true;
		$i = 1;
		while($startLoop)
		{
			if(!isset($postedValues['Local_Utility_'.$i]))
			{
				$startLoop = false;
				break;
			}
			
			$n = $i - 1;
			
			$this->GreenAppAccountValues[$n]["util"] = $postedValues["Local_Utility_$i"];
			$this->GreenAppAccountValues[$n]["account"] = $postedValues["Account_Number_$i"];
			$this->GreenAppAccountValues[$n]["address"] = $postedValues["address_$i"];
			$this->GreenAppAccountValues[$n]["city"] = $postedValues["city_$i"];
			$this->GreenAppAccountValues[$n]["state"] = $postedValues["state_$i"];
			$this->GreenAppAccountValues[$n]["zip5"] = $postedValues["zip5_$i"];
			$this->GreenAppAccountValues[$n]["zip4"] = $postedValues["zip4_$i"];
			
			$i++;
		}
	}
	
	/**
	 * addGreenAppAccountData()
	 * Inserts GreenApp data into the greenapps_accounts table
	 * 
	 * @param int $greenAppsID
	 * @param array $accountValues
	 */
	private function addGreenAppAccountData($greenAppsID, $accountValues) {
		$sql = "INSERT INTO greenapps_accounts 
			(greenap_id, util, account, address, city, state, zip5, zip4) 
			VALUES (:greenAppsID, :util, :account, :address, :city, :state, :zip5, :zip4) ";
		$statement = $this->PDODB->prepare($sql);
		
		$statement->bindValue(":greenAppsID", $greenAppsID);
		$statement->bindValue(":util", $accountValues["util"]);
		$statement->bindValue(":account", $accountValues["account"]);
		$statement->bindValue(":address", $accountValues["address"]);
		$statement->bindValue(":city", $accountValues["city"]);
		$statement->bindValue(":state", $accountValues["state"]);
		$statement->bindValue(":zip5", $accountValues["zip5"]);
		$statement->bindValue(":zip4", $accountValues["zip4"]);

		// Execute the query
		$statement->execute();
	}
	
	/**
	 * getCleansedRecordByCorrespondenceID()
	 * Gets cleansed record by correspondence ID
	 * 
	 * @param int $correspondenceID
	 * @return object $CleansedObject
	 */
	public function getCleansedRecordByCorrespondenceID($correspondenceID) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM cleansed WHERE correspondenceid = :correspondenceID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":correspondenceID", $correspondenceID);

		// Execute the query
		$statement->execute();

		// Fetch object
		$CleansedObject = $statement->fetchObject();

		// Return cleansed object
		return $CleansedObject;
	}
	
	/**
	 * removeCleansedRecord()
	 * Removes a given Cleansed Record
	 * 
	 * @param int $cleansedID
	 */
	public function removeCleansedRecord($cleansedID) {
		// Build query, prep, and bind
		$sql = "DELETE FROM cleansed WHERE id = :cleansedID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":cleansedID", $cleansedID);

		// Execute the query
		$statement->execute();
	}
	
	/**
	 * setApplicationState()
	 * Sets the Application State
	 * 
	 * @param int $sessionState [optional] 
	 */
	private function setApplicationState($sessionState = NULL) {
		$applicationState = 1;
		
		if($sessionState != NULL && $sessionState != 0)
		{
			$applicationState = $sessionState;
		}
		
		$this->ApplicationState = $applicationState;
		$_SESSION['application']['state'] = $applicationState;
	}
	
	/**
	 * setApplicationPartner()
	 * Sets the Partner Object
	 * 
	 * @param int $sessionPartnerID [optional]
	 * @param string $partnercode [optional]
	 */
	private function setApplicationPartner($sessionPartnerID = NULL, $partnercode = "") {
		$PartnerClass = new Partners();
		
		if($sessionPartnerID != NULL)
		{
			$this->ApplicationPartner = $PartnerClass->getPartnerByID($sessionPartnerID);
		}
		else
		{
			$this->ApplicationPartner = $PartnerClass->getPartnerByPartnercodeState($partnercode, $this->ApplicationState);
		}
	}
	
	/**
	 * setApplicationErrorCode()
	 * Sets the Error Code
	 * 
	 * @param int $referralID [optional]
	 * @param int $sessionAffinity [optional]
	 */
	private function setApplicationErrorCode($referralID = NULL, $sessionAffinity = NULL) {
		$applicationErrorCode = "";
		
		if(!in_array($this->ApplicationPartner->partnercode, $this->ReferralPartnercodes))
		{
			if($referralID != NULL && strlen($referralID) > 2)
			{
				$applicationErrorCode = "REFERR $referralID REMOVED! - AFF=$sessionAffinity";
			}
		}
		
		$this->ApplicationErrorCode = $applicationErrorCode;
	}
	
	/**
	 * setApplicationVendor()
	 * Sets the Vendor (MID)
	 * 
	 * @param int $MID [optional]
	 * @param int $sessionMID [optional]
	 */
	private function setApplicationVendor($MID = NULL, $sessionMID = NULL) {		
		$vendorMID = "9000";
		
		if($MID != NULL)
		{
			$vendorMID = $MID;
		}
		elseif($sessionMID != NULL)
		{
			$vendorMID = $sessionMID;
		}
		
		$this->ApplicationVendor = $vendorMID;
	}
	
	/**
	 * setApplicationValue
	 * Sets a given application value to the correct length and whitespace fills where necessary
	 * 
	 * @param mixed $applicationValue [optional]
	 * @param int $padLength [optional]
	 * @param bool $upper [optional] Default to true
	 * @return mixed $formattedValue
	 */
	private function setApplicationValue($applicationValue = NULL, $padLength = 0, $upper = true) {
		$formattedValue = "";
		
		if($applicationValue != NULL)
		{
			$formattedValue = $applicationValue;
		}
		
		if($padLength > 0)
		{
			$formattedValue = str_pad($formattedValue, $padLength);
		}
		
		if($upper == true)
		{
			$formattedValue = strtoupper($formattedValue);
		}
		
		return $formattedValue;
	}
	
	/**
	 * setApplicationAccountType()
	 * Sets the Account Type
	 * 
	 * @param string $businessName [optional]
	 */
	private function setApplicationAccountType($businessName = NULL) {
		$accountType = 0;
		
		if($businessName != NULL && $businessName != "" && strlen($businessName) > 2)
		{
			$accountType = 1;
		}
		
		$this->ApplicationAccountType = $accountType;
	}
	
	/**
	 * setApplicationPromoCode()
	 * Sets the promocode based on account type and gas status
	 * 
	 * @param string $promoCode [optional]
	 * @param string $businessPromoCode [optional]
	 * @param int $gasOption [optional]
	 */
	private function setApplicationPromoCode($promoCode = NULL, $businessPromoCode = NULL, $gasOption = 0) {
		if($gasOption == 1)
		{
			$applicationPromoCode = "989";
		}
		elseif($this->RequiredValues['p_partcode'] == "CON")
		{
			$applicationPromoCode = $this->RequiredValues['s_pc'];
		}
		else
		{
			$applicationPromoCode = "000";

			if($promoCode != NULL)
			{
				$applicationPromoCode = $promoCode;
			}

			if($this->ApplicationAccountType == 1 || $this->ApplicationAccountType == 2)
			{
				if($this->ApplicationPartner->affinity == 1 && $businessPromoCode != NULL && strlen($businessPromoCode) > 2)
				{
					$applicationPromoCode = $businessPromoCode;
				}

				if($applicationPromoCode == $this->ApplicationPartner->promocode || $applicationPromoCode == "000")
				{
					$applicationPromoCode = $this->ApplicationPartner->promocode_bus;
				}
			}
			elseif($applicationPromoCode == "000")
			{
				$applicationPromoCode = $this->ApplicationPartner->promocode;
			}
		}
		
		$this->ApplicationPromoCode = $applicationPromoCode;
	}
	
	/**
	 * setApplicationRequiredValues()
	 * Sets all the required values for application processing
	 * 
	 * @param array $postedValues
	 */
	private function setApplicationRequiredValues($postedValues) {		
		foreach($this->RequiredPostFields AS $postValue)
		{
			if(isset($postedValues[$postValue]))
			{
				$this->RequiredValues["p_".$postValue] = $postedValues[$postValue];
			}
			else
			{
				$this->RequiredValues["p_".$postValue] = "";
			}
		}
		
		if(isset($postedValues['localutility']))
		{
			$this->RequiredValues['p_Local_Utility'] = $postedValues['localutility'];
		}
		
		foreach($this->RequiredSessionFields AS $sessionKey => $sessionValue)
		{
			if(is_array($sessionValue))
			{
				foreach($sessionValue AS $sessionSubValue)
				{
					if(isset($_SESSION[$sessionKey][$sessionSubValue]))
					{
						$this->RequiredValues["s_".$sessionKey."_".$sessionSubValue] = $_SESSION[$sessionKey][$sessionSubValue];
					}
					else
					{
						$this->RequiredValues["s_".$sessionKey."_".$sessionSubValue] = "";
					}
				}
			}
			else
			{
				if(isset($_SESSION[$sessionValue]))
				{
					$this->RequiredValues["s_".$sessionValue] = $_SESSION[$sessionValue];
				}
				else
				{
					$this->RequiredValues["s_".$sessionValue] = "";
				}
			}
		}
	}
	
	/**
	 * setApplicationRevClass()
	 * Sets the Rev Class
	 */
	private function setApplicationRevClass() {
		$revClass = 1;
		
		if($this->RequiredValues['p_busname'] != "")
		{
			$revClass = 2;
			
			if($this->RequiredValues['p_moreBusQuestions'] == 4)
			{
				$revClass = 3;
			}
		}
		
		$this->ApplicationRevClass = $revClass;
	}
	
	/**
	 * buildApplicationAddressParts()
	 * Builds Address Parts for the application
	 * 
	 * @param string $address
	 * @return array $addressParts
	 */
	private function buildApplicationAddressParts($address) {
		$addressLines = preg_split("/[\x0D\x0A+;,]+/", $address);
		
		for($i = 0; $i < 3; $i++)
		{
			if(isset($addressLines[$i]))
			{
				$addressParts[$i] = $this->setApplicationValue($addressLines[$i], 40);
			}
			else
			{
				$addressParts[$i] = $this->setApplicationValue(" ", 40);
			}
		}
		
		return $addressParts;
	}
	
	/**
	 * buildApplicationCounty()
	 * Builds the county for a given State and Zip
	 * 
	 * @param string $abbrev
	 * @param int $zipCode
	 * @param string $state
	 * @return $countyName
	 */
	private function buildApplicationCounty($abbrev, $zipCode, $state) {
		$StatesClass = new States();
		
		if($zipCode == "")
		{
			$countyName = "No County";
		}
		else
		{
			$countyName = $StatesClass->getCountyNameByAbbrevZip($abbrev, $zipCode);
			
			if($countyName == "NO")
			{
				$countyName = $state;
			}
		}
		$countyName = $this->setApplicationValue($countyName, 20);
		
		return $countyName;
	}
	
	/**
	 * setApplicationISO()
	 * Sets the ISO
	 * 
	 * @param 
	 */
	private function setApplicationISO($state, $zipCode, $utilityObject) {
		$StatesClass = new States();
		$sessionStateAbbrev = $this->RequiredValues['s_st_abbrev'];
		
		if($sessionStateAbbrev == 'CT')
		{
			$iso = $state;
		}
		elseif(in_array($sessionStateAbbrev, array("PA", "NJ", "MD", "IL")))
		{
			$iso = $utilityObject->abbrev;
		}
		else
		{
			$iso = $StatesClass->getISOByAbbrevZip($utilityObject->table_code, $zipCode, $utilityObject->code);
		}
		
		$this->ApplicationISO = $iso;
	}
	
	/**
	 * setApplicationRateClass()
	 * Sets the Rate Class
	 * 
	 * @param int $utilityID
	 */
	private function setApplicationRateClass($utilityID) {
		$rateClass = "000";
		
		if($utilityID == 2 && $this->RequiredValues['p_Rate_Class'] != "")
		{
			$rateClass = $this->RequiredValues['p_Rate_Class'];
		}
		
		$this->ApplicationRateClass = $rateClass;
	}
	
	/**
	 * setApplicationPricePlan()
	 * Sets the Price Plan
	 */
	private function setApplicationPricePlan($utilityID, $partnercode) {
		$PartnerClass = new Partners();
		
		$pricePlan = $this->setApplicationValue($this->RequiredValues['p_priceplan'], 5);
		
		if($pricePlan == $this->setApplicationValue("", 5))
		{
			$pricePlan = "V1.9";
			if($utilityID == 1)
			{
				$pricePlan = "V2.9";
			}
			elseif($utilityID == 2)
			{
				$affinity = $PartnerClass->getAffinityByPartnercode($partnercode);
				
				if($affinity == 1)
				{
					$pricePlan = "NAP";
				}
			}
		}
		
		$this->ApplicationPricePlan = $pricePlan;
	}
	
	/**
	 * setApplicationNumberOfAccounts()
	 * Sets how many Accounts are being set up
	 */
	private function setApplicationNumberOfAccounts($postedValues) {
		$accountCount = 1;
		if($this->RequiredValues['p_Multiple_Accounts'] == "Yes" || $this->ApplicationRevClass == 2)
		{
			$moreAccounts = true;
			
			while($moreAccounts == true)
			{
				if(isset($postedValues['account_'.$accountCount]) && $postedValues['account_'.$accountCount] != "")
				{
					$accountCount++;
				}
				else
				{
					$moreAccounts = false;
				}
			}
		}
		
		$this->ApplicationNumberOfAccounts = $accountCount;
	}
	
	/**
	 * addNewApplication()
	 * Processes and adds a new application into the DB
	 * 
	 * @param string $partnercode
	 * @param array $postedValues
	 * @return string $confcode 
	 */
	public function addNewApplication($partnercode, $postedValues) {
		$StatesClass = new States();
		$UtilitiesClass = new Utilities();
		$RecordsClass = new Records();
		
		$this->setApplicationRequiredValues($postedValues);
		
		$state = "";
		$dbState = $StatesClass->getStateByID($this->RequiredValues['s_application_state']);
		if(is_object($dbState))
		{
			$state = $dbState->abbrev;
		}
		
		$terrobj = $UtilitiesClass->getUtilityByCode($this->RequiredValues['p_Local_Utility']);
		$terr = $terrobj->utility;
		$countyPrefix = trim($terrobj->table_code);
		
		$this->setApplicationState($this->RequiredValues['s_application_state']);
		$this->setApplicationPartner($this->RequiredValues['s_application_partner_id'], $this->RequiredValues['p_partcode']);
		$this->setApplicationErrorCode($this->RequiredValues['p_refid'], $this->RequiredValues['s_aff']);
		$this->setApplicationVendor($this->RequiredValues['p_vendorid'], $this->RequiredValues['s_mid']);
		$this->setApplicationAccountType($this->RequiredValues['p_busname']);
		$this->setApplicationPromoCode($this->RequiredValues['p_promocode'], $this->RequiredValues['p_promocodeb'], $this->RequiredValues['p_incgas']);
		$this->setApplicationRevClass();
		
		$this->setApplicationNumberOfAccounts($postedValues);
		
		$partnerinfo = $this->ApplicationPartner;
		$partcode = $this->ApplicationPartner->partnercode;
		$errcode = $this->ApplicationErrorCode;
		$affinity = $this->ApplicationPartner->affinity;
		$mid = $this->ApplicationVendor;
		$stateid = $this->ApplicationState;
		$busres = $this->ApplicationAccountType;
		$promocode = $this->ApplicationPromoCode;
		$revclass = $this->ApplicationRevClass;
		
		$service_address = $this->RequiredValues['p_Service_Address'];
		$years_inbiz = $this->RequiredValues['p_years_inbiz'];
		$years_bizaddr = $this->RequiredValues['p_years_bizaddr'];
		$late_payment6 = $this->RequiredValues['p_late_payment6'];
		$busname_change = $this->RequiredValues['p_busname_change'];
		$elec_supp_prevyear = $this->RequiredValues['p_elec_supp_prevyear'];
		$years_creditbiz = $this->RequiredValues['p_years_creditbiz'];
		$terr_code = $this->RequiredValues['p_Local_Utility'];
		$fpover = $this->RequiredValues['s_repid'];
		$partner_memnum = $this->RequiredValues['p_Dividend_Miles_Number'];
		$multi = $this->RequiredValues['p_Multiple_Accounts'];
		$currsupp = $this->RequiredValues['p_currsupp'];
		$pfname = $this->RequiredValues['p_pfname'];
		$plname = $this->RequiredValues['p_plname'];
		
		$today = strftime("%m%d%Y");
		$todayfile = "daily".$partnercode.$today.".txt";
		$sequence = "001";
		$sphone_ext = $this->setApplicationValue(" ", 7);
		$attline = $this->setApplicationValue(" ", 44);
		$servicetype = "1";
		$terr_code_out = $terr_code;
		$marketer = "01";
		$taxex = 0;
		$entype = "1";
		$today = strftime("%Y%m%d");
		$distrib = $terr_code_out;
		$bdate = $this->setApplicationValue(" ", 8);
		$edate = $this->setApplicationValue(" ", 8);
		$edate_plan = $this->setApplicationValue(" ", 8);
		$saledate = $today;
		$accept = "1";
		$hpsemail = "";
		$fullemail = "";
		$introgroup = "";
		$mkgroup = "";
		$enrollcustid = "";
		$fico = "";
		$paysrc = "";
		$paymeth = "";
		$payamt = "";
		$contractterm = $errcode;
		$spanishbill = "";
		$notificationwaiver = "";
		$dob = "";
		$mothersmaiden = "";
		$taxid = "";
		$credit1 = "";
		$credit2 = "";
		$kwh = "";
		$rentown = "";
		$spfname = "";
		$splname = "";
		$residencelength = 0;
		$employeecount = 0;
		$businesslength = 0;
		
		$vip = "";
		
		$apptype = $this->setApplicationValue($this->RequiredValues['p_apptype']);
		$fname = $this->setApplicationValue($this->RequiredValues['p_first_name'], 20);
		$midint = $this->setApplicationValue(substr($this->RequiredValues['p_middle_initial'], 0, 1));
		$lname = $this->setApplicationValue($this->RequiredValues['p_last_name'], 23);
		$suffix = $this->setApplicationValue($this->RequiredValues['p_Suffix'], 4);
		$busname = $this->setApplicationValue($this->RequiredValues['p_busname'], 40);
		
		$city = $this->setApplicationValue($this->RequiredValues['p_Service_City'], 20);
		$zip5 = $this->setApplicationValue($this->RequiredValues['p_Service_Zip5'], 5);
		$zip4 = $this->setApplicationValue($this->RequiredValues['p_Service_Zip4'], 4);
		$sphone = $this->buildDBPhoneNumber($this->RequiredValues['p_Service_phone_number_prefix'], $this->RequiredValues['p_Service_phone_number_first'], $this->RequiredValues['p_Service_phone_number_last']);
		$address = $this->buildApplicationAddressParts($this->RequiredValues['p_Service_Address']);
		$county = $this->buildApplicationCounty($countyPrefix, $this->RequiredValues['p_Service_Zip5'], $state);
		
		$email = $this->setApplicationValue($this->RequiredValues['p_email_addr'], 20);
		$account = $this->setApplicationValue($this->RequiredValues['p_Account_Number'], 20);
		$campaign = $this->setApplicationValue($this->RequiredValues['p_campaign'], 4);
		$cellcode = $this->setApplicationValue($this->RequiredValues['p_cellcode'], 2);
		$memlevel = $this->setApplicationValue($this->RequiredValues['p_dividend_miles_level'], 15);
		
		if($this->RequiredValues['p_billing'] == "no")
		{
			$bphone = $this->buildDBPhoneNumber($this->RequiredValues['p_phone_number_prefix'], $this->RequiredValues['p_phone_number_first'], $this->RequiredValues['p_phone_number_last']);
			$bphone_ext = $this->setApplicationValue(" ", 7);
			$baddress = $this->buildApplicationAddressParts($this->RequiredValues['p_Billing_Address']);
			$bcity = $this->setApplicationValue($this->RequiredValues['p_Billing_City'], 20);
			$bcounty = $this->buildApplicationCounty($countyPrefix, $this->RequiredValues['p_Billing_Zip5'], $state);
			$bstate = $this->setApplicationValue($this->RequiredValues['p_Billing_State'], 2);
			$bzip5 = $this->setApplicationValue($this->RequiredValues['p_Billing_Zip5'], 5);
			$bzip4 = $this->setApplicationValue($this->RequiredValues['p_Billing_Zip4'], 4);
		}
		else
		{
			$bphone = $sphone;
			$bphone_ext = $sphone_ext;
			$baddress = $address;
			$bcity = $city;
			$bcounty = $county;
			$bstate = $state;
			$bzip5 = $zip5;
			$bzip4 = $zip4;
		}
		
		$this->setApplicationISO($state, $zip5, $terrobj);
		$iso = $this->ApplicationISO;
		
		$billmeth = $terrobj->billmeth;
		if($state == "CT")
		{
			$billmeth = "1";
		}
		
		$this->setApplicationRateClass($this->RequiredValues['p_Local_Utility']);
		$rate_class = $this->ApplicationRateClass;
		
		$this->setApplicationPricePlan($this->RequiredValues['p_Local_Utility'], $partnercode);
		$priceplan = $this->ApplicationPricePlan;
		
		$vas = " ";
		if(substr($account, 0, 2) == "ID")
		{
			$vas = "S";
		}
		
		$partner_code = $this->RequiredValues['p_partcode'];
		if($this->RequiredValues['p_partcode'] == "")
		{
			$partner_code = "USA";
		}
		
		$auth = "0";
		if($this->RequiredValues['p_authorize'] != "")
		{
			$auth = "1";
		}
		
		$confcode = $uid = $RecordsClass->getUniqueDBID();
		$now = strftime("%H%M%S");
		
		if($this->RequiredValues['s_fname'] != "")
		{
			$fullemail = $this->RequiredValues['s_fname'].'.'.$this->RequiredValues['s_lname'].'@e-hps.com';
			$hpsemail = $fullemail;
			if(strlen($fullemail) > 50)
			{
				$hpsemail = substr($fullemail, 0, 50);
			}
		}
		
		$greenopt = "000";
		if($this->RequiredValues['s_green'] == "001" && $this->RequiredValues['p_greenopt'] == "")
		{
			$greenopt = "001";
		}
		elseif($this->RequiredValues['p_greenopt'] != "")
		{
			$greenopt = $this->RequiredValues['p_greenopt'];
		}
		
		$refid = $this->RequiredValues['p_refid'];
		if($refid == "")
		{
			$refid = $this->RequiredValues['s_refid'];
		}
		if($refid != " ")
		{
			$mid = "RFRL";
			if($campaign == "")
			{
				$campaign = $partnerinfo->defrefcamp;
			}
		}
		if($state == "TX" && $this->ApplicationRevClass != 2)
		{
			$busname = "";
		}
		
		$offercode = "";
		$offer_rates = array();
		$productcode = "";
		$fixedintro = "";
		if($this->ApplicationState != 1)
		{
			$partnerType = "cobrand";
			if($partcode == "BRD")
			{
				$partnerType = "brand";
			}
			elseif($this->ApplicationPartner->affinity == 1)
			{
				$partnerType = "affinity";
			}
			$conditions = array("partner_type" => $partnerType, "state" => $this->ApplicationState, "account_type" => $busres, "utility" => $this->RequiredValues['p_Local_Utility']);
			$offercodeObject = $RecordsClass->getRecords("default_offer_mapping", $conditions);
			$offer_rates = $UtilitiesClass->getUtilityRates($offercodeObject->offercode, $this->RequiredValues['p_Local_Utility']);
			$productcode = $offer_rates[0];
			
			if($productcode != "")
			{
				// Process VAS adder for green utilities.
				// Special case for New Jersey.
				if($this->ApplicationState == 5)
				{
					// No VAS adder for non-green utilities.
					// Special case for Starwood partner, no VAS adder eventhough partner is always green.
					if($greenopt == "000" || $partcode == "SPG")
					{
						$fixedintro = round($offer_rates[1], 3);
					}
					else
					{
						// $0.01 VAS adder for green utilities.
						$fixedintro = round(($offer_rates[1] + 0.01), 3);
					}
				}
				// All other states.
				else
				{
					// No VAS adder for non-green utilities.
					// Special case for Starwood partner, no VAS adder eventhough partner is always green.
					if($greenopt == "000" || $partcode == "SPG")
					{
						$fixedintro = $offer_rates[1];
					}
					else
					{
						// $0.01 VAS adder for green utilities.
						$fixedintro = $offer_rates[1] + 0.01;
					}
				}
			}
		}
		
		for($i = 0; $i < $this->ApplicationNumberOfAccounts; $i++)
		{
			if($i == 0)
			{
				$accvar = "Account_Number";
				$srvar = "servicereference";
			}
			else
			{
				$accvar = "account_$i";
				$srvar = "sr_$i";
			}
			
			$account = $this->setApplicationValue($postedValues[$accvar], 20);
			$sequence = str_pad($i + 1, 3, "0", STR_PAD_LEFT);
			
			$sql = "INSERT INTO register (regdate, uid, sequence, vendorid, apptype, first_name, 
				mid_init, last_name, suffix, vip, busname, revclass, servicephone, sericeext, 
				addr1, addr2, addr3, city, state, zip5, zip4, county, billphone, billext, 
				baddr1, baddr2, baddr3, bcity, bstate, bzip5, bzip4, bcounty, att, email, servicetype, 
				territory_code, marketer, iso, taxex, billmeth, entype, today, distrib, account, rateclass, 
				promocode, priceplan, bdate, edate, edate_plan, repid, vas, campaign, cellcode, saledate, 
				partnercode, partner_memnum, memlevel, auth, accept, confcode, nowtime, hpsemail, 
				origurl, greenopt, refid, sourceip, busres, budget, entby, appby, namekey, baccount, 
				introgroup, mkgroup, stateid, pfname, plname, enrollcustid, fico, paysrc, paymeth, payamt, 
				contractterm, spanishbill, notificationwaiver, dob, mothermaiden, taxid, Credit1, Credit2, 
				kWh, RentOwn, ResidenceLength, EmployeeCount, BusinessLength, CurrentSupplier, spfname, 
				splname, ProdCode, FixedIntro, noexport, years_inbiz, years_bizaddr, late_payment6, 
				busname_change, elec_supp_prevyear, years_creditbiz) 
				VALUES (:regdate, :uid, :sequence, :vendorid, :apptype, :first_name, 
				:mid_init, :last_name, :suffix, :vip, :busname, :revclass, :servicephone, :sericeext, 
				:addr1, :addr2, :addr3, :city, :state, :zip5, :zip4, :county, :billphone, :billext, 
				:baddr1, :baddr2, :baddr3, :bcity, :bstate, :bzip5, :bzip4, :bcounty, :att, :email, :servicetype, 
				:territory_code, :marketer, :iso, :taxex, :billmeth, :entype, :today, :distrib, :account, :rateclass, 
				:promocode, :priceplan, :bdate, :edate, :edate_plan, :repid, :vas, :campaign, :cellcode, :saledate, 
				:partnercode, :partner_memnum, :memlevel, :auth, :accept, :confcode, :nowtime, :hpsemail, 
				:origurl, :greenopt, :refid, :sourceip, :busres, :budget, :entby, :appby, :namekey, :baccount, 
				:introgroup, :mkgroup, :stateid, :pfname, :plname, :enrollcustid, :fico, :paysrc, :paymeth, :payamt, 
				:contractterm, :spanishbill, :notificationwaiver, :dob, :mothermaiden, :taxid, :Credit1, :Credit2, 
				:kWh, :RentOwn, :ResidenceLength, :EmployeeCount, :BusinessLength, :CurrentSupplier, :spfname, 
				:splname, :ProdCode, :FixedIntro, :noexport, :years_inbiz, :years_bizaddr, :late_payment6, 
				:busname_change, :elec_supp_prevyear, :years_creditbiz) ";
			$statement = $this->PDODB->prepare($sql);
			
			$statement->bindValue(":regdate", time());
			$statement->bindValue(":uid", "0".$uid);
			$statement->bindValue(":sequence", $sequence);
			$statement->bindValue(":vendorid", $mid);
			$statement->bindValue(":apptype", $apptype);
			$statement->bindValue(":first_name", $fname);
			$statement->bindValue(":mid_init", $midint);
			$statement->bindValue(":last_name", $lname);
			$statement->bindValue(":suffix", $suffix);
			$statement->bindValue(":vip", $vip);
			$statement->bindValue(":busname", $busname);
			$statement->bindValue(":revclass", $revclass);
			$statement->bindValue(":servicephone", trim($sphone));
			$statement->bindValue(":sericeext", trim($sphone_ext));
			$statement->bindValue(":addr1", trim($address[0]));
			$statement->bindValue(":addr2", trim($address[1]));
			$statement->bindValue(":addr3", trim($address[2]));
			$statement->bindValue(":city", trim($city));
			$statement->bindValue(":state", trim($state));
			$statement->bindValue(":zip5", trim($zip5));
			$statement->bindValue(":zip4", trim($zip4));
			$statement->bindValue(":county", trim($county));
			$statement->bindValue(":billphone", trim($bphone));
			$statement->bindValue(":billext", trim($bphone_ext));
			$statement->bindValue(":baddr1", trim($baddress[0]));
			$statement->bindValue(":baddr2", trim($baddress[1]));
			$statement->bindValue(":baddr3", trim($baddress[2]));
			$statement->bindValue(":bcity", trim($bcity));
			$statement->bindValue(":bstate", trim($bstate));
			$statement->bindValue(":bzip5", trim($bzip5));
			$statement->bindValue(":bzip4", trim($bzip4));
			$statement->bindValue(":bcounty", trim($bcounty));
			$statement->bindValue(":att", trim($attline));
			$statement->bindValue(":email", trim($email));
			$statement->bindValue(":servicetype", trim($servicetype));
			$statement->bindValue(":territory_code", trim($terr_code_out));
			$statement->bindValue(":marketer", trim($marketer));
			$statement->bindValue(":iso", trim($iso));
			$statement->bindValue(":taxex", trim($taxex));
			$statement->bindValue(":billmeth", trim($billmeth));
			$statement->bindValue(":entype", trim($entype));
			$statement->bindValue(":today", trim($today));
			$statement->bindValue(":distrib", trim($distrib));
			$statement->bindValue(":account", trim($account));
			$statement->bindValue(":rateclass", trim($rate_class));
			$statement->bindValue(":promocode", trim($promocode));
			$statement->bindValue(":priceplan", trim($priceplan));
			$statement->bindValue(":bdate", trim($bdate));
			$statement->bindValue(":edate", trim($edate));
			$statement->bindValue(":edate_plan", trim($edate_plan));
			$statement->bindValue(":repid", trim($fpover));
			$statement->bindValue(":vas", trim($vas));
			$statement->bindValue(":campaign", trim($campaign));
			$statement->bindValue(":cellcode", trim($cellcode));
			$statement->bindValue(":saledate", trim($saledate));
			$statement->bindValue(":partnercode", trim($partner_code));
			$statement->bindValue(":partner_memnum", trim($partner_memnum));
			$statement->bindValue(":memlevel", trim($memlevel));
			$statement->bindValue(":auth", trim($auth));
			$statement->bindValue(":accept", trim($accept));
			$statement->bindValue(":confcode", trim($confcode));
			$statement->bindValue(":nowtime", trim($now));
			$statement->bindValue(":hpsemail", trim($fullemail));
			$statement->bindValue(":origurl", trim($this->RequiredValues['s_urlinfo']));
			$statement->bindValue(":greenopt", $greenopt);
			$statement->bindValue(":refid", $refid);
			$statement->bindValue(":sourceip", trim($_SERVER['REMOTE_ADDR']));
			$statement->bindValue(":busres", $busres);
			$statement->bindValue(":budget", $this->RequiredValues['s_budget']);
			$statement->bindValue(":entby", $this->RequiredValues['s_op']);
			$statement->bindValue(":appby", $this->RequiredValues['s_op2']);
			$statement->bindValue(":namekey", $this->RequiredValues['p_namekey']);
			$statement->bindValue(":baccount", $postedValues[$srvar]);
			$statement->bindValue(":introgroup", $introgroup);
			$statement->bindValue(":mkgroup", $mkgroup);
			$statement->bindValue(":stateid", $this->ApplicationState);
			$statement->bindValue(":pfname", $pfname);
			$statement->bindValue(":plname", $plname);
			$statement->bindValue(":enrollcustid", $enrollcustid);
			$statement->bindValue(":fico", $fico);
			$statement->bindValue(":paysrc", $paysrc);
			$statement->bindValue(":paymeth", $paymeth);
			$statement->bindValue(":payamt", $payamt);
			$statement->bindValue(":contractterm", $contractterm);
			$statement->bindValue(":spanishbill", $spanishbill);
			$statement->bindValue(":notificationwaiver", $notificationwaiver);
			$statement->bindValue(":dob", $dob);
			$statement->bindValue(":mothermaiden", $mothersmaiden);
			$statement->bindValue(":taxid", $taxid);
			$statement->bindValue(":Credit1", $credit1);
			$statement->bindValue(":Credit2", $credit2);
			$statement->bindValue(":kWh", $kwh);
			$statement->bindValue(":RentOwn", $rentown);
			$statement->bindValue(":ResidenceLength", $residencelength);
			$statement->bindValue(":EmployeeCount", $employeecount);
			$statement->bindValue(":BusinessLength", $businesslength);
			$statement->bindValue(":CurrentSupplier", $currsupp);
			$statement->bindValue(":spfname", $spfname);
			$statement->bindValue(":splname", $splname);
			$statement->bindValue(":ProdCode", $productcode);
			$statement->bindValue(":FixedIntro", $fixedintro);
			$statement->bindValue(":noexport", "0");
			$statement->bindValue(":years_inbiz", trim($years_inbiz));
			$statement->bindValue(":years_bizaddr", trim($years_bizaddr));
			$statement->bindValue(":late_payment6", trim($late_payment6));
			$statement->bindValue(":busname_change", trim($busname_change));
			$statement->bindValue(":elec_supp_prevyear", trim($elec_supp_prevyear));
			$statement->bindValue(":years_creditbiz", trim($years_creditbiz));

			// Execute the query
			$statement->execute();			
		}
		
		$_SESSION['confcode'] = $confcode;
		
		return $confcode;
	}

}

?>

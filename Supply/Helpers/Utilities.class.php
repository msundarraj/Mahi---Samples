<?php
/**
 * Description of Utilities
 *
 * @author zaguanno
 */
require_once "$base_dir/config.php";
require_once "EP/Util/Database.php";
require_once "EP/Helpers/Records.class.php";

class Utilities {
    /**
	 * DB Connection
	 */
	private $PDODB;
	
	/**
	 * Allowed Utility Account Tables
	 */
	private $AllowedUtilityTables = array("duq", "meted", "peco", "penelec", "ppl");

	/**
	 * Utility DB Table
	 */
	private $UtilityTable = "NONE";

	/**
	 * Field Associations for account search
	 */
	private $FieldAssociations = array();

	/**
	 * Ignore Suffixes for account search
	 */
	private $IgnoreSuffixes = array();

	/**
	 * Count of Utility Accounts after Search
	 */
	public $UtilityAccountsCount = 0;

	/**
	 * __construct()
	 * Constructor of Utilities Class
	 */
    public function  __construct() {
		// Connect to the DB
		$this->PDODB = EP_Util_Database::pdo_connect();
	}

	/**
	 * setUtilityTable()
	 * Sets the Utility DB Table for later use
	 *
	 * @param string $utilityName
	 */
	public function setUtilityTable($utilityName) {
		// If it's an account lookup utility
		if(in_array($utilityName, $this->AllowedUtilityTables))
		{
			// Set table name
			$this->UtilityTable = $utilityName."_data";
		}
	}

	/**
	 * setFieldAssociations()
	 * Sets the table fields to search per form field
	 */
	private function setFieldAssociations() {
		$this->FieldAssociations['name'] = array("name1", "name2");
		$this->FieldAssociations['addr'] = array("addr1", "addr2", "baddr1", "baddr2");
		$this->FieldAssociations['city'] = array("city", "bcity");
		$this->FieldAssociations['zip'] = array("zip", "bzip");
		$this->FieldAssociations['state'] = array("state", "bstate");
		$this->FieldAssociations['acct'] = array("account");
		$this->FieldAssociations['fname'] = array("name1", "name2");
		$this->FieldAssociations['lname'] = array("name1", "name2");
		$this->FieldAssociations['saddr'] = array("addr1", "addr2", "baddr1", "baddr2");
		$this->FieldAssociations['scity'] = array("city", "bcity");
		$this->FieldAssociations['szip5'] = array("zip", "bzip");
	}

	/**
	 * setIgnoreSuffixes()
	 * Sets the array of strings to ignore in an account search
	 */
	private function setIgnoreSuffixes() {
		// Build query and prep
		$sql = "SELECT suffix FROM street_suffixes ORDER BY id";
		$statement = $this->PDODB->prepare($sql);

		// Execute the query
		$statement->execute();

		// Fetch results
		$suffixes = $statement->fetchAll(PDO::FETCH_CLASS);

		// Cycle through results and add to IgnoreSuffixes array
		foreach($suffixes AS $suffix)
		{
			$this->IgnoreSuffixes[] = ' '.$suffix->suffix;
		}
	}

	/**
	 * getUtilityAccountByID()
	 * Gets the Utility Account Data by record ID
	 *
	 * @param int $recordID
	 * @return object $UtilityAccountObject
	 */
	public function getUtilityAccountByID($recordID) {
		// Check for allowed table
		if($this->UtilityTable != "NONE")
		{
			// Build query, prep, and bind
			$sql = "SELECT * FROM $this->UtilityTable WHERE id = :recordID ";
			$statement = $this->PDODB->prepare($sql);
			$statement->bindValue(":recordID", $recordID);

			// Execute the query
			$statement->execute();

			// Fetch object
			$UtilityAccountObject = $statement->fetchObject();

			// Set Accounts Count
			if(is_object($UtilityAccountObject))
			{
				$this->UtilityAccountsCount = 1;
			}

			// Return the object
			return $UtilityAccountObject;
		}
		else
		{
			return NULL;
		}
	}

	/**
	 * getUtilityAccountByAccountNumber()
	 * Gets the Utility Account Data by Account Number
	 *
	 * @param int $accountNumber
	 * @return object $UtilityAccountObject
	 */
	public function getUtilityAccountByAccountNumber($accountNumber) {
		// Check for allowed table
		if($this->UtilityTable != "NONE")
		{
			// Build query, prep, and bind
			$sql = "SELECT * FROM $this->UtilityTable WHERE MATCH (account) AGAINST (:accountNumber) ";
			$statement = $this->PDODB->prepare($sql);
			$statement->bindValue(":accountNumber", $accountNumber);

			// Execute the query
			$statement->execute();

			// Fetch object
			$UtilityAccountObject = $statement->fetchObject();

			// Set Accounts Count
			if(is_object($UtilityAccountObject))
			{
				$this->UtilityAccountsCount = 1;
			}

			// Return the object
			return $UtilityAccountObject;
		}
		else
		{
			return NULL;
		}
	}

	/**
	 * getUtilityAccountsBySearchParameters()
	 * Gets the Utility Accounts based on search criteria
	 *
	 * @param array $searchParameters
	 * @return array $utilityAccounts
	 */
	public function getUtilityAccountsBySearchParameters($searchParameters) {
		// Check for allowed table
		if($this->UtilityTable != "NONE")
		{
			$utilityAccounts = array();
			$this->setFieldAssociations();
			$this->setIgnoreSuffixes();

			$sql = "SELECT * FROM $this->UtilityTable WHERE ";

			foreach($searchParameters AS $searchField => $searchValue)
			{
				if(array_key_exists($searchField, $this->FieldAssociations) && $searchValue != "")
				{
					$sql .= "(";
					foreach($this->FieldAssociations[$searchField] AS $tableField)
					{
						$sql .= "$tableField LIKE :$tableField OR ";
					}
					// Trim the last 'OR'
					$sql = rtrim($sql, " OR ");
					$sql .= ") AND ";
				}
			}

			// Trim the last 'AND'
			$sql = rtrim($sql, "AND ");

			$sql .= "LIMIT 1001 ";

			// Prepare the query
			$statement = $this->PDODB->prepare($sql);		

			foreach($searchParameters AS $searchField => $searchValue)
			{
				if(array_key_exists($searchField, $this->FieldAssociations) && $searchValue != "")
				{
					if($searchField == "addr")
					{
						$searchValue = str_ireplace($this->IgnoreSuffixes,'',$searchValue);
					}

					foreach($this->FieldAssociations[$searchField] AS $tableField)
					{
						$statement->bindValue(":$tableField", "%$searchValue%");
					}
				}
			}
			// Execute the query
			$statement->execute();

			// Fetch the results
			$utilityAccounts = $statement->fetchAll(PDO::FETCH_CLASS);

			// Count results
			$this->UtilityAccountsCount = count($utilityAccounts);

			// Return results
			return $utilityAccounts;
		}
		else
		{
			return array();
		}
	}

	/**
	 * getUtilitiesByState()
	 * Gets the list of utilities by state
	 *
	 * @param int $stateID State ID [optional]
	 * @param bool $includeHidden Include Hidden Utilities [optional]
	 * @param string $orderBy [optional]
	 *
	 * @return array $output
	 */
	public function getUtilitiesByState($stateID = 1, $includeHidden = false, $orderBy = "") {
		// Assume no binding needed
		$bind = false;

		// Begin building query
		$sql = "SELECT * FROM utility2 ";

		// If $stateID is a state...
		if($stateID > 0)
		{
			$sql .= "WHERE state = :stateID ";

			// If hidden utilities should remain hidden...
			if($includeHidden == false)
			{
				$sql .= "AND active != 2 ";
			}

			if($orderBy != "")
			{
				$sql .= "ORDER BY $orderBy ";
			}
			else
			{
				$sql .= "ORDER BY active DESC, utility ";
			}

			// Set binding flag
			$bind = true;
		}
		// Else, get utilities for all states
		else
		{
			// If hidden utilities should remain hidden...
			if($includeHidden == false)
			{
				$sql .= "WHERE active != 2 ";
			}

			if($orderBy != "")
			{
				$sql .= "ORDER BY $orderBy ";
			}
			else
			{
				$sql .= "ORDER BY code ";
			}
		}

		// Prepare the query
		$statement = $this->PDODB->prepare($sql);

		// Bind state if needed
		if($bind == true)
		{
			$statement->bindValue(":stateID", $stateID);
		}

		// Execute the query
		$statement->execute();

		// Fetch the results
		$output = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the output array
		return $output;
	}

	/**
	 * getUtilityByID()
	 * Gets the Utility by ID
	 *
	 * @param int $utilityID
	 * @return object $UtilityObject
	 */
	public function getUtilityByID($utilityID) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM utility2 WHERE id = :utilityID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":utilityID", $utilityID);

		// Execute the query
		$statement->execute();

		// Fetch object
		$UtilityObject = $statement->fetchObject();

		// Return the object
		return $UtilityObject;
	}

	/**
	 * getUtilityByCode()
	 * Gets the Utility by Code
	 *
	 * @param int $utilityCode
	 * @return object $UtilityObject
	 */
	public function getUtilityByCode($utilityCode) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM utility2 WHERE code = :utilityCode ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":utilityCode", $utilityCode);

		// Execute the query
		$statement->execute();

		// Fetch object
		$UtilityObject = $statement->fetchObject();

		// Return the object
		return $UtilityObject;
	}

	/**
	 * getUtilityByAbbrev()
	 * Gets the Utility by Abbrev
	 *
	 * @param int $utilityAbbrev
	 * @return object $UtilityObject
	 */
	public function getUtilityByAbbrev($utilityAbbrev) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM utility2 WHERE abbrev = :utilityAbbrev ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":utilityAbbrev", $utilityAbbrev);

		// Execute the query
		$statement->execute();

		// Fetch object
		$UtilityObject = $statement->fetchObject();

		// Return the object
		return $UtilityObject;
	}

	/**
	 * getUtilityByTdspdunsState()
	 * Gets the Utility by Tdspduns and State
	 *
	 * @param int $tdspduns
	 * @param int $state
	 * @return object $UtilityObject
	 */
	public function getUtilityByTdspdunsState($tdspduns, $state) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM utility2 WHERE tdspduns = :tdspduns AND state = :state ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":tdspduns", $tdspduns);
		$statement->bindValue(":state", $state);

		// Execute the query
		$statement->execute();

		// Fetch object
		$UtilityObject = $statement->fetchObject();

		// Return the object
		return $UtilityObject;
	}
	
	/**
	 * getUtilityRates()
	 * Gets the Utility rates based on Offercode and Utility Code
	 *
	 * @param string $offercode
	 * @param string $utilityCode
	 * @return array $rates
	 */
	public function getUtilityRates($offercode, $utilityCode) {
		$rates = array("", "");
		
		// Build query, prep, and bind
		$sql = "SELECT chargeid, rate FROM util_offers 
			WHERE code = :offercode AND util_code = :utilityCode 
			ORDER BY effdate DESC LIMIT 1 ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":offercode", $offercode);
		$statement->bindValue(":utilityCode", sprintf("%02d", $utilityCode));
		
		// Execute the query
		$statement->execute();

		// Fetch object
		$UtilityObject = $statement->fetchObject();

		if(is_object($UtilityObject))
		{
			// Set rates array
			$rates = array($UtilityObject->chargeid, $UtilityObject->rate);
		}

		// Return rates
		return $rates;
	}
	
	/**
	 * isUtilityGas()
	 * Check if Utility has gas option
	 * 
	 * @param string $utilityAbbrev
	 * @return bool $gasCheck
	 */
	public function isUtilityGas($utilityAbbrev) {
		// Build query, prep, and bind
		$sql = "SELECT has_gas_option FROM utility2 WHERE abbrev = :utilityAbbrev ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":utilityAbbrev", $utilityAbbrev);

		// Execute the query
		$statement->execute();

		// Fetch object
		$UtilityObject = $statement->fetchObject();
		
		$gasCheck = false;
		if($UtilityObject->has_gas_option == 1)
		{
			$gasCheck = true;
		}
		
		return $gasCheck;
	}
	
	/**
	 * isZipGas()
	 * Check if Zipcode has gas option
	 * 
	 * @param string $zip
	 * @return bool $gasCheck
	 */
	public function isZipGas($zip) {
		// Build query, prep, and bind
		$sql = "SELECT zip FROM gas_zips WHERE zip = :zip ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":zip", $zip);

		// Execute the query
		$statement->execute();

		// Fetch object
		$UtilityObject = $statement->fetchObject();
		
		$gasCheck = false;
		if(is_object($UtilityObject))
		{
			$gasCheck = true;
		}
		
		return $gasCheck;
	}
	
	/**
	 * editUtilityInformation()
	 * Updates the stored info for the given Utility
	 * 
	 * @param array $postedValues
	 */
	public function editUtilityInformation($postedValues) {
		$utilityID = $postedValues['uid'];
		
		// Build query, prep, and bind
		$sql = "UPDATE utility2 SET 
			utility = :utility, 
			active = :active, 
			code = :code, 
			phone = :phone, 
			acctext = :acctext, 
			acctext_ext = :acctextExt, 
			acclen = :acclen, 
			abbrev = :abbrev, 
			abbrev2 = :abbrev2, 
			rctext = :rctext, 
			extra_account_name = :extraAccountName, 
			srtext = :srtext, 
			account_logic = :accountLogic, 
			extra_account_logic = :extraAccountLogic, 
			block_por = :blockPor, 
			good_pors = :goodPors, 
			por_field = :porField, 
			secondary_por_trigger = :secondaryPorTrigger, 
			secondary_por_field = :secondaryPorField, 
			good_secondary_pors = :goodSecondaryPors, 
			addr_street1 = :addrStreet1, 
			addr_street2 = :addrStreet2, 
			addr_city = :addrCity, 
			addr_state = :addrState, 
			addr_zip5 = :addrZip5, 
			addr_zip4 = :addrZip4, 
			customer_service_hours = :customerServiceHours, 
			phone_emergency_gas = :phoneEmergencyGas, 
			phone_emergency_electric_bus = :phoneEmergencyElectricBus, 
			phone_emergency_electric_res = :phoneEmergencyElectricRes, 
			url = :url 
			WHERE id = :utilityID ";
		$statement = $this->PDODB->prepare($sql);
		
		$statement->bindValue(":utility", $postedValues['utility']);
		$statement->bindValue(":active", $postedValues['active']);
		$statement->bindValue(":code", $postedValues['code']);
		$statement->bindValue(":phone", $postedValues['phone']);
		$statement->bindValue(":acctext", $postedValues['acctext']);
		$statement->bindValue(":acctextExt", $postedValues['acctext_ext']);
		$statement->bindValue(":acclen", $postedValues['acclen']);
		$statement->bindValue(":abbrev", $postedValues['abbrev']);
		$statement->bindValue(":abbrev2", $postedValues['abbrev2']);
		$statement->bindValue(":rctext", $postedValues['rctext']);
		$statement->bindValue(":extraAccountName", $postedValues['extra_account_name']);
		$statement->bindValue(":srtext", $postedValues['srtext']);
		$statement->bindValue(":accountLogic", $postedValues['account_logic']);
		$statement->bindValue(":extraAccountLogic", $postedValues['extra_account_logic']);
		$statement->bindValue(":blockPor", $postedValues['block_por']);
		$statement->bindValue(":goodPors", $postedValues['good_pors']);
		$statement->bindValue(":porField", $postedValues['por_field']);
		$statement->bindValue(":secondaryPorTrigger", $postedValues['secondary_por_trigger']);
		$statement->bindValue(":secondaryPorField", $postedValues['secondary_por_field']);
		$statement->bindValue(":goodSecondaryPors", $postedValues['good_secondary_pors']);
		$statement->bindValue(":addrStreet1", $postedValues['addr_street1']);
		$statement->bindValue(":addrStreet2", $postedValues['addr_street2']);
		$statement->bindValue(":addrCity", $postedValues['addr_city']);
		$statement->bindValue(":addrState", $postedValues['addr_state']);
		$statement->bindValue(":addrZip5", $postedValues['addr_zip5']);
		$statement->bindValue(":addrZip4", $postedValues['addr_zip4']);
		$statement->bindValue(":customerServiceHours", $postedValues['customer_service_hours']);
		$statement->bindValue(":phoneEmergencyGas", $postedValues['phone_emergency_gas']);
		$statement->bindValue(":phoneEmergencyElectricBus", $postedValues['phone_emergency_electric_bus']);
		$statement->bindValue(":phoneEmergencyElectricRes", $postedValues['phone_emergency_electric_res']);
		$statement->bindValue(":url", $postedValues['url']);
		$statement->bindValue(":utilityID", $utilityID);

		// Execute the query
		$statement->execute();
		
		$RecordsClass = new Records();
		$RecordsClass->addDatabaseAccessRecord("utility2", "Utility ID $utilityID updated.");
	}

	/**
	 * getStationByStationCode()
	 * Gets the Station by Station Code
	 *
	 * @param int $stationCode
	 * @return object $StationObject
	 */
	public function getStationByStationCode($stationCode) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM station_codes WHERE stationcode = :stationCode ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":stationCode", $stationCode);

		// Execute the query
		$statement->execute();

		// Fetch object
		$StationObject = $statement->fetchObject();

		// Return the object
		return $StationObject;
	}
	
	/**
	 * getUtilityOfferByOffercodeUtilityCode()
	 * Gets the utility offer for a offercode and utility code
	 * 
	 * @param string $offercode
	 * @param string $utilityCode
	 * @param string $orderBy [optional]
	 * @return object $utilityOffer
	 */
	public function getUtilityOfferByOffercodeUtilityCode($offercode, $utilityCode, $orderBy = "") {
		// Build query, prep, and bind
		$sql = "SELECT * FROM util_offers WHERE code = :offercode AND util_code = :utilityCode ";
		if($orderBy != "")
		{
			$sql .= "ORDER BY $orderBy ";
		}
		
		$sql .= "LIMIT 1 ";
		
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":offercode", $offercode);
		$statement->bindValue(":utilityCode", $utilityCode);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$utilityOffer = $statement->fetchObject();

		// Return the Utility Offer
		return $utilityOffer;
	}
	
	/**
	 * addUtilityOffer()
	 * Adds a Utility Offer
	 * 
	 * @param object $offerObject
	 * @param int $utilityID
	 * @param array $TDSP
	 * @param string $effectiveDate
	 * @param array $ValueAddedService
	 */
	public function addUtilityOffer($offerObject, $utilityID, $TDSP, $effectiveDate, $ValueAddedService) {
		$tdspFixed = $tdspVariable = $tdspDFixed = $tdspDVariable = $tdspNDFixed = $tdspNDVariable = 0;
		
		$contractTerm = trim($offerObject->InitialOffer_ContractTerm);
		
		if($contractTerm == "")
		{
			$contractTerm = "NULL";
			
			$tdspFixed = $TDSP['Fixed'];
			$tdspVariable = $TDSP['Variable'];
			$tdspDFixed = $TDSP['DemandFixed'];
			$tdspDVariable = $TDSP['DemandVariable'];
			$tdspNDFixed = $TDSP['NonDemandFixed'];
			$tdspNDVariable = $TDSP['NonDemandVariable'];
		}
		
		$sql = "INSERT INTO util_offers (effdate, util_code, chargeid, code, pricinggroup, replacedbycode, duration, rate, 
			tdspfixed, tdspvariable, tdsp_dem_fixed, tdsp_dem_var, tdsp_non_dem_fixed, tdsp_non_dem_var, 
			vas_id, vas_name, vas_unit, vas_rate, vas_code, contractterm) 
			VALUES (:effectiveDate, :utilityCode, :chargeID, :code, :pricingGroup, :replacedByCode, :duration, :rate, 
			:tdspFixed, :tdspVariable, :tdspDFixed, :tdspDVariable, :tdspNDFixed, :tdspNDVariable, 
			:vasID, :vasName, :vasUnit, :vasRate, :vasCode, :contractTerm) ";
		
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":effectiveDate", $effectiveDate);
		$statement->bindValue(":utilityCode", $utilityID);
		$statement->bindValue(":chargeID", $offerObject->InitialOffer_ChargeID);
		$statement->bindValue(":code", $offerObject->InitialOffer_Code);
		$statement->bindValue(":pricingGroup", $offerObject->InitialOffer_PricingGroupCode);
		$statement->bindValue(":replacedByCode", $offerObject->InitialOffer_ReplacedByCode);
		$statement->bindValue(":duration", $offerObject->InitialOffer_Duration);
		$statement->bindValue(":rate", $offerObject->InitialOffer_Rate);
		$statement->bindValue(":tdspFixed", $tdspFixed);
		$statement->bindValue(":tdspVariable", $tdspVariable);
		$statement->bindValue(":tdspDFixed", $tdspDFixed);
		$statement->bindValue(":tdspDVariable", $tdspDVariable);
		$statement->bindValue(":tdspNDFixed", $tdspNDFixed);
		$statement->bindValue(":tdspNDVariable", $tdspNDVariable);
		$statement->bindValue(":vasID", $ValueAddedService['ID']);
		$statement->bindValue(":vasName", $ValueAddedService['Name']);
		$statement->bindValue(":vasUnit", $ValueAddedService['Unit']);
		$statement->bindValue(":vasRate", $ValueAddedService['Rate']);
		$statement->bindValue(":vasCode", $ValueAddedService['Code']);
		$statement->bindValue(":contractTerm", $contractTerm);

		// Execute the query
		$statement->execute();
	}
	
	/**
	 * getAllLookupUtilities()
	 * Gets all Utilities where a lookup is possible
	 * 
	 * @return array $utilities
	 */
	public function getAllLookupUtilities() {
		$sql = "SELECT states.has_lookup, utility2.id, utility2.utility, utility2.num_upload_files 
			FROM utility2 
			LEFT JOIN states ON utility2.state = states.id 
			WHERE states.has_lookup = 1 and utility2.active = 1 ";
		$statement = $this->PDODB->prepare($sql);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$utilities = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the utility list
		return $utilities;
	}
	
	/**
	 * removeAllUtilityAccountData()
	 * Removes all Utility Account data before a data import
	 */
	public function removeAllUtilityAccountData() {
		$sql = "DELETE FROM $this->UtilityTable ";
		$statement = $this->PDODB->prepare($sql);
		$statement->execute();
	}
	
	/**
	 * addUtilityAccountData()
	 * Adds data to the Utility Accounts Tables
	 * 
	 * @param array $dataImportRecord
	 */
	public function addUtilityAccountData($dataImportRecord) {
		$columnList = $bindingList = "";
		$fieldCount = 0;
		
		$sql = "SHOW COLUMNS FROM $this->UtilityTable ";
		$statement = $this->PDODB->prepare($sql);
		$statement->execute();
		$UtilityTableColumns = $statement->fetchAll(PDO::FETCH_CLASS);
		foreach($UtilityTableColumns AS $UtilityTableColumn)
		{
			if($UtilityTableColumn->Field != "id")
			{
				$columnList .= $UtilityTableColumn->Field.", ";
				$fieldCount++;
			}
		}
		$columnList = rtrim($columnList, ", ");
		
		if(count($dataImportRecord) == $fieldCount)
		{
			for($i = 0; $i < $fieldCount; $i++)
			{
				$bindingList .= ":dataValue$i, ";
			}
			$bindingList = rtrim($bindingList, ", ");
			
			$sql = "INSERT INTO $this->UtilityTable ($columnList) VALUES ($bindingList) ";
			$statement = $this->PDODB->prepare($sql);
			
			for($i = 0; $i < count($dataImportRecord); $i++)
			{
				$statement->bindValue(":dataValue$i", $dataImportRecord[$i]);
			}

			// Execute the query
			$statement->execute();
			echo "<span style='color: green;'>Successful Import Into $fieldCount Fields.</span><br />\n";
		}
		else
		{
			echo "<span style='color: red; font-weight: bold;'>Import List (".count($dataImportRecord)." Fields) does not match DB List ($fieldCount Fields).</span><br />\n";
		}
	}
}
?>

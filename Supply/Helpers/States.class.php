<?php
/**
 * Description of States
 *
 * @author zaguanno
 */
require_once "$base_dir/config.php";
require_once "EP/Util/Database.php";

class States {
	/**
	 * DB Connection
	 */
	private $PDODB;
	
	/**
	 * State Switch States
	 */
	public $StateSwitchStatesCount;

	/**
	 * __construct()
	 * Constructor of States Class
	 */
	public function __construct() {
		// Connect to the DB
		$this->PDODB = EP_Util_Database::pdo_connect();
	}
	
	/**
	 * getAllServiceStates()
	 * Gets the list of states where service is provided
	 * 
	 * @param string $orderBy [optional] Defaults to ID
	 * @param bool $onlyActive [optional] Defaults to all states
	 * @return array $states
	 */
	public function getAllServiceStates($orderBy = "id", $onlyActive = false) {
		// Build query and prep
		$sql = "SELECT * FROM states ";
		if($onlyActive == true)
		{
			$sql .= "WHERE active = 1 ";
		}
		$sql .= "ORDER BY $orderBy ";
			
		$statement = $this->PDODB->prepare($sql);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$states = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return results
		return $states;
	}
	
	/**
	 * getAllInboundServiceStates()
	 * Gets the list of inbound viewable states where service is provided
	 * 
	 * @param string $orderBy [optional] Defaults to ID
	 * @param bool $onlyActive [optional] Defaults to all states
	 * @return array $states
	 */
	public function getAllInboundServiceStates($onlyActive = false, $orderBy = "states.id") {
		// Build query and prep
		$sql = "SELECT states.* FROM states 
			LEFT JOIN partnercode ON states.id = partnercode.state 
			WHERE partnercode.allow_inbound = 1 ";
		if($onlyActive == true)
		{
			$sql .= "AND states.active = 1 ";
		}
		$sql .= "GROUP BY states.id ORDER BY $orderBy ";
			
		$statement = $this->PDODB->prepare($sql);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$states = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return results
		return $states;
	}
	
	/**
	 * getStateSwitchStatesByDir()
	 * Gets the list of state switch states by partnerDir
	 * 
	 * @param string $partnerDir
	 * @param string $orderBy [optional]
	 * @return mixed $states
	 */
	public function getStateSwitchStatesByDir($partnerDir, $orderBy = "") {
		$states = array();
		
		// Build query, prep and bind
		$sql = "SELECT states.* FROM states 
			LEFT JOIN partnercode ON states.id = partnercode.state 
			WHERE partnercode.stateswitch = 1 
			AND partnercode.partner_dir = :partnerDir ";
		if($orderBy != "")
		{
			$sql .= "ORDER BY $orderBy ";
		}
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnerDir", $partnerDir);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$states = $statement->fetchAll(PDO::FETCH_CLASS);
		
		$this->StateSwitchStatesCount = count($states);
		
		if($this->StateSwitchStatesCount == 1)
		{
			$states = $states[0];
		}

		// Return results
		return $states;
	}
	
	/**
	 * getAllUSStates()
	 * Gets the list of all US states
	 * 
	 * @param string $orderBy [optional] Defaults to name
	 * @return array $states
	 */
	public function getAllUSStates($orderBy = "name") {
		// Build query and prep
		$sql = "SELECT * FROM usstates ORDER BY $orderBy ";
		$statement = $this->PDODB->prepare($sql);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$states = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return results
		return $states;
	}
	
	/**
	 * getStateByAbbrev()
	 * Get State by abbreviation
	 * 
	 * @param string $abbrev
	 * @return object $state
	 */
	public function getStateByAbbrev($abbrev) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM states WHERE abbrev = :abbrev ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":abbrev", $abbrev);

		// Execute the query
		$statement->execute();

		// Fetch object
		$state = $statement->fetchObject();
		
		return $state;
	}
	
	/**
	 * getStateByName()
	 * Get State by name
	 * 
	 * @param string $name
	 * @return object $state
	 */
	public function getStateByName($name) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM states WHERE name = :name ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":name", $name);

		// Execute the query
		$statement->execute();

		// Fetch object
		$state = $statement->fetchObject();
		
		return $state;
	}
	
	/**
	 * getStateByID()
	 * Get State by ID
	 * 
	 * @param string $stateID
	 * @return object $state
	 */
	public function getStateByID($stateID) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM states WHERE id = :stateID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":stateID", $stateID);

		// Execute the query
		$statement->execute();

		// Fetch object
		$state = $statement->fetchObject();
		
		return $state;
	}
	
	/**
	 * editStateAccountLookup()
	 * Updates the lookup status of a state
	 * 
	 * @param int $lookup
	 * @param int $stateID
	 */
	public function editStateAccountLookup($lookup, $stateID) {
		// Build query, prep, and bind
		$sql = "UPDATE states SET has_lookup = :lookup WHERE id = :stateID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":lookup", $lookup);
		$statement->bindValue(":stateID", $stateID);

		// Execute the query
		$statement->execute();
	}
	
	/**
	 * getCountyNameByAbbrevZip()
	 * Gets the county name by Abbrev and Zip code
	 * 
	 * @param string $abbrev
	 * @param int $zipCode
	 * @return string $countyName
	 */
	public function getCountyNameByAbbrevZip($abbrev, $zipCode) {
		if($abbrev == "no")
		{
			$countyName = "NO";
		}
		else
		{
			$countyTable = $abbrev."_county";
			
			// Build query, prep, and bind
			$sql = "SELECT county FROM $countyTable WHERE zip = :zipCode ";
			$statement = $this->PDODB->prepare($sql);
			$statement->bindValue(":zipCode", $zipCode);

			// Execute the query
			$statement->execute();

			// Fetch object
			$county = $statement->fetchObject();
			
			$countyName = $county->county;
		}
		
		return $countyName;
	}
	
	/**
	 * getISOByAbbrevZip()
	 * Gets the county name by Abbrev and Zip code
	 * 
	 * @param string $abbrev
	 * @param int $zipCode
	 * @param string $utilityID [optional]
	 * @return string $countyName
	 */
	public function getISOByAbbrevZip($abbrev, $zipCode, $utilityID = "") {
		$iso = "";
		
		if($utilityID == "1")
		{
			$iso = "J";
			
			$sql = "SELECT code FROM iso WHERE zip = :zipCode ";
			$statement = $this->PDODB->prepare($sql);
			$statement->bindValue(":zipCode", $zipCode);

			// Execute the query
			$statement->execute();

			// Fetch object
			$isoObject = $statement->fetchObject();
			
			if(is_object($isoObject))
			{
				$iso = $isoObject->code;
			}
		}
		elseif($utilityID != "2")
		{
			$countyTable = $abbrev."_county";
			
			// Build query, prep, and bind
			$sql = "SELECT iso FROM $countyTable WHERE zip = :zipCode ";
			$statement = $this->PDODB->prepare($sql);
			$statement->bindValue(":zipCode", $zipCode);

			// Execute the query
			$statement->execute();

			// Fetch object
			$isoObject = $statement->fetchObject();
			
			$iso = $isoObject->iso;
		}
		
		return $iso;
	}

}

?>

<?php
/**
 * Description of Exports
 *
 * @author zaguanno
 */
require_once "$base_dir/config.php";
require_once "EP/Util/Database.php";

class Exports {
	/**
	 * DB Connection
	 */
	private $PDODB;

	/**
	 * __construct()
	 * Constructor of Exports Class
	 */
	public function __construct() {
		// Connect to the DB
		$this->PDODB = EP_Util_Database::pdo_connect();
	}
	
	/**
	 * getRegisterExportList()
	 * Gets a list of Register records based on supplied parameters
	 * 
	 * @param array $getValues
	 * @param int $dateStart
	 * @param int $dateEnd
	 * @param array $states
	 * @param string $partnercode
	 */
	public function getRegisterExportList($getValues, $dateStart = "", $dateEnd = "", $states = array(), $partnercode = "") {
		$registerExportList = array();
		
		$sql = "SELECT * FROM register WHERE ";
		if(isset($getValues['id']))
		{
			$sql .= "id = :id ";
		}
		else
		{
			$sql .= "noexport = 0 AND auth = 1 AND regdate >= :dateStart AND regdate <= :dateEnd ";
			
			if($partnercode != "")
			{
				$sql .= "AND partnercode = :partnercode ";
			}
			if(isset($getValues['mid']) && $getValues['mid'] != "0")
			{
				$sql .= "AND vendorid = :mid ";
			}
			if(isset($getValues['apptype']) && $getValues['apptype'] != "0")
			{
				$sql .= "AND apptype = :apptype ";
			}
			if(isset($getValues['cellcode']) && $getValues['cellcode'] != "0")
			{
				$sql .= "AND cellcode = :cellcode ";
			}
			if(isset($getValues['campaign']) && $getValues['campaign'] != "0")
			{
				$sql .= "AND campaign = :campaign ";
			}
			if(isset($getValues['promo']) && $getValues['promo'] != "0")
			{
				$sql .= "AND promocode = :promo ";
			}
			if(count($states) > 0)
			{
				$sql .= "AND stateid IN (";
				for($i = 0; $i < count($states); $i++)
				{
					$sql .= ":state_$i, ";
				}
				$sql = rtrim($sql, ", ");
				$sql .= ") ";
			}
			$sql .= "ORDER BY regdate ";
		}
		$statement = $this->PDODB->prepare($sql);
		
		if(isset($getValues['id']))
		{
			$statement->bindValue(":id", $getValues['id']);
		}
		else
		{
			$statement->bindValue(":dateStart", $dateStart);
			$statement->bindValue(":dateEnd", $dateEnd);
			
			if($partnercode != "")
			{
				$statement->bindValue(":partnercode", $partnercode);
			}
			if(isset($getValues['mid']) && $getValues['mid'] != "0")
			{
				$statement->bindValue(":mid", $getValues['mid']);
			}
			if(isset($getValues['apptype']) && $getValues['apptype'] != "0")
			{
				$statement->bindValue(":apptype", $getValues['apptype']);
			}
			if(isset($getValues['cellcode']) && $getValues['cellcode'] != "0")
			{
				$statement->bindValue(":cellcode", $getValues['cellcode']);
			}
			if(isset($getValues['campaign']) && $getValues['campaign'] != "0")
			{
				$statement->bindValue(":campaign", $getValues['campaign']);
			}
			if(isset($getValues['promo']) && $getValues['promo'] != "0")
			{
				$statement->bindValue(":promo", $getValues['promo']);
			}
			if(count($states) > 0)
			{
				for($i = 0; $i < count($states); $i++)
				{
					$statement->bindValue(":state_$i", $states[$i]);
				}
			}
		}
		
		// Execute the query
		$statement->execute();

		// Fetch the results
		$registerExportList = $statement->fetchAll(PDO::FETCH_CLASS);
		
		// Return register export list
		return $registerExportList;
	}
	
	/**
	 * getHeadingText()
	 * Gets the header text for an exported file
	 * 
	 * @param string $field
	 * @param string $language [optional]
	 * @return string $headerText
	 */
	public function getHeadingText($field, $language = "")
	{
		$sql = "SELECT * FROM headingmap WHERE fieldname = :field ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":field", $field);
		// Execute the query
		$statement->execute();

		// Fetch the results
		$headingmapObject = $statement->fetchObject();
		
		$headerText = $field;
		
		if(is_object($headingmapObject))
		{
			$headerText = $headingmapObject->headtext;
			
			if($language != "")
			{
				$columnName = $language."label";
				
				if($headingmapObject->$columnName != NULL)
				{
					$headerText = $headingmapObject->$columnName;
				}
			}
		}
		
		return $headerText;
	}
	
	/**
	 * getCleansedRecordsByPhase()
	 * Get cleansed records by processphase
	 * 
	 * @param int $processPhase
	 * @param string $orderBy [optional]
	 * @param string $wlStatus [optional]
	 * @param string $limit [optional]
	 * @return array $cleansedRecords
	 */
	public function getCleansedRecordsByPhase($processPhase, $orderBy = "", $wlStatus = "", $limit = "") {
		$cleansedRecords = array();
		
		// Build query, prep and bind
		$sql = "SELECT * FROM cleansed WHERE processphase = :processPhase ";
		if($wlStatus != "")
		{
			$sql .= "AND wlstatus = :wlStatus ";
		}
		if($orderBy != "")
		{
			$sql .= "ORDER BY $orderBy ";
		}
		if($limit != "")
		{
			$sql .= "LIMIT $limit ";
		}
		
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":processPhase", $processPhase);
		if($wlStatus != "")
		{
			$statement->bindValue(":wlStatus", $wlStatus);
		}

		// Execute the query
		$statement->execute();

		// Fetch the results
		$cleansedRecords = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the MIDs
		return $cleansedRecords;
	}
	
	/**
	 * editCleansedRecordsToSent()
	 * Set cleansed records processphase = 3
	 */
	public function editCleansedRecordsToSent() {
		// Build query and prep
		$sql = "UPDATE cleansed SET processphase = 3 ";
		$statement = $this->PDODB->prepare($sql);

		// Execute the query
		$statement->execute();
	}
	
	/**
	 * getCleansedUniqueID()
	 * Gets a unique ID not used in the cleansed table
	 * 
	 * @return string $uid
	 */
	public function getCleansedUniqueID() {
		$duplicate = true;
		
		while($duplicate)
		{
			$uid = substr(uniqid('Z'),-9,8);
			
			$sql = "SELECT COUNT(*) FROM cleansed WHERE uid = '$uid' ";
			$statement = $this->PDODB->query($sql);
			$rows = $statement->fetchColumn();

			if($rows == 0)
			{
				$duplicate = false;
			}
		}
		
		return $uid;
	}
	
	/**
	 * getCleansedUniqueStates()
	 * Gets the list of unique States from the cleansed table
	 * 
	 * @return array $states
	 */
	public function getCleansedUniqueStates() {
		$states = array();
		
		// Build query and prep
		$sql = "SELECT DISTINCT state FROM cleansed ORDER BY state ";
		$statement = $this->PDODB->prepare($sql);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$states = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the states
		return $states;
	}
	
	/**
	 * editCleansedStatusByID()
	 * Sets a given cleansed record to certain status parameters
	 * 
	 * 
	 */
	public function editCleansedStatusByID($processPhase, $wlStatus, $uid, $wlStatusDesc, $cleansedID) {
		// Build query, prep and bind
		$sql = "UPDATE cleansed SET 
			processphase = :processPhase, 
			wlstatus = :wlStatus, 
			uid = :uid, 
			wlstatusdesc = :wlStatusDesc 
			WHERE id = :cleansedID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":processPhase", $processPhase);
		$statement->bindValue(":wlStatus", $wlStatus);
		$statement->bindValue(":uid", $uid);
		$statement->bindValue(":wlStatusDesc", $wlStatusDesc);
		$statement->bindValue(":cleansedID", $cleansedID);
		
		// Execute the query
		$statement->execute();
	}

}

?>

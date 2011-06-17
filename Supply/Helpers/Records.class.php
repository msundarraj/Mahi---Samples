<?php
/**
 * Description of Records
 *
 * @author zaguanno
 */
require_once "$base_dir/config.php";
require_once "EP/Util/Database.php";

class Records {
	/**
	 * DB Connection
	 */
	private $PDODB;

	/**
	 * __construct()
	 * Constructor of Records Class
	 */
    public function  __construct() {
		// Connect to the DB
		$this->PDODB = EP_Util_Database::pdo_connect();
	}
	
	/**
	 * getRecords()
	 * Gets records from the DB
	 * 
	 * @param string $table DB Table Name
	 * @param array $conditions Query Conditions [optional]
	 * @param string $orderBy Query Order [optional]
	 * @param array $tableConnections Connections made for condition-table joining [optional]
	 *
	 * @return mixed $output (Either single object or array of objects)
	 */
	public function getRecords($table, $conditions = array(), $orderBy = '', $tableConnections = array()) {
		//Initialize variables
		$output = array();

		// Start with a basic query of the table
		$sql = "SELECT * FROM $table ";

		// If there are connections or conditions on the list, add them to the query
		if(count($tableConnections) > 0 || count($conditions) > 0)
		{
			$sql .= "WHERE ";
			foreach($tableConnections AS $field => $value)
			{
				// Adding table connections
				$sql .= "$field = $value AND ";
			}

			foreach($conditions AS $field => $value)
			{
				// If multiple tables, there may be table-field connectors to account for
				$bindingName = str_replace(".", "", $field);

				// Check for special operators
				$specialConditions = explode(",", $value);
				if(count($specialConditions) > 1)
				{
					$operator = $specialConditions[0];
					$val = $specialConditions[1];

					// Overwrite condition value for binding
					$conditions[$field] = $val;

					// Add special condition in PDO format
					$sql .= "$field $operator :$bindingName AND ";
				}
				else
				{
					// Adding conditions in PDO format
					$sql .= "$field = :$bindingName AND ";
				}
			}

			// Trim the last 'AND'
			$sql = rtrim($sql, "AND ");
		}

		// If there is an Order By, add it to the query
		if($orderBy != '')
		{
			$sql .= " ORDER BY $orderBy ";
		}

		// Prepare the query
		$statement = $this->PDODB->prepare($sql);

		// Bind the conditional parameters, if there are any
		foreach($conditions AS $field => $value)
		{
			// If multiple tables, there may be table-field connectors to account for
			$bindingName = str_replace(".", "", $field);

			$statement->bindValue(":$bindingName", $value);
		}

		// Execute the query
		$statement->execute();

		// Fetch results
		$results = $statement->fetchAll(PDO::FETCH_CLASS);

		// Count Records
		$recordCount = count($results);

		// If multiple records...
		if($recordCount > 1)
		{
			$output = $results;
		}
		// Else, get single record
		elseif($recordCount == 1)
		{
			$output = $results[0];
		}

		// Return the output
		return $output;
	}
	
	/**
	 * getCountRecords()
	 * Gets count of records from the DB
	 * 
	 * @param string $table DB Table Name
	 * @param array $conditions Query Conditions [optional]
	 * @param string $orderBy Query Order [optional]
	 * @param array $tableConnections Connections made for condition-table joining [optional]
	 *
	 * @return int $count
	 */
	public function getCountRecords($table, $conditions = array(), $orderBy = '', $tableConnections = array()) {
		$allRecords = $this->getRecords($table, $conditions, $orderBy, $tableConnections);

		$count = 0;

		if(is_object($allRecords))
		{
			$count = 1;
		}
		elseif(is_array($allRecords))
		{
			$count = count($allRecords);
		}

		return $count;
	}
	
	/**
	 * addDatabaseAccessRecord()
	 * Adds a record of Database Access to the history table
	 * 
	 * @param string $accessedTable
	 * @param string $accessMessage
	 */
	public function addDatabaseAccessRecord($accessedTable, $accessMessage) {
		// Get current time
		$currentTime = time();
		
		// Build query, prep, and bind
		$sql = "INSERT INTO history (user, auditdate, dbtable, action) 
			VALUES (:user, :currentTime, :accessedTable, :accessMessage) ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":user", $_SESSION['user_id']);
		$statement->bindValue(":currentTime", $currentTime);
		$statement->bindValue(":accessedTable", $accessedTable);
		$statement->bindValue(":accessMessage", $accessMessage);

		// Execute the query
		$statement->execute();
	}
	
	/**
	 * getUniqueDBID()
	 * Gets a unique ID not used in the DB
	 * 
	 * @param string $tableName [optional] Defaults to register
	 * @return string $uid
	 */
	public function getUniqueDBID($tableName = "register") {
		$duplicate = true;
		
		while($duplicate)
		{
			$uid = substr(uniqid('Z'),-7,7);
			
			switch($tableName)
			{
				case "cleansed":
					$sql = "SELECT COUNT(*) FROM cleansed WHERE uid = '$uid' ";
					break;
				case "register":
					$sql = "SELECT COUNT(*) FROM register WHERE confcode = '$uid' ";
					break;
				default:
					$sql = "SELECT COUNT(*) FROM register WHERE confcode = '$uid' ";
					break;
			}
			$statement = $this->PDODB->query($sql);
			$rows = $statement->fetchColumn();
			

			if($rows == 0)
			{
				$duplicate = false;
			}
		}
		
		return $uid;
	}
}
?>

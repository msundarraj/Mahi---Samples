<?php
/**
 * Description of Operators
 *
 * @author zaguanno
 */
require_once "$base_dir/config.php";
require_once "EP/Util/Database.php";

class Operators {
	/**
	 * DB Connection
	 */
	private $PDODB;

	/**
	 * __construct()
	 * Constructor of Operators Class
	 */
	public function __construct() {
		// Connect to the DB
		$this->PDODB = EP_Util_Database::pdo_connect();
	}

	/**
	 * getAllOperators()
	 * Gets all Operators
	 *
	 * @param string $orderBy [optional]
	 * @param bool $EPOnly [optional] Defaults to false
	 * @return array $operators
	 */
	public function getAllOperators($orderBy = "", $EPOnly = false) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM operators ";
		if($EPOnly == true)
		{
			$sql .= "WHERE external_id > 0 ";
		}
		if($orderBy != "")
		{
			$sql .= "ORDER BY $orderBy ";
		}
		$statement = $this->PDODB->prepare($sql);

		// Execute the query
		$statement->execute();

		// Fetch object
		$operators = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the object
		return $operators;
	}

	/**
	 * getVendorByMID()
	 * Gets an individual vendor object by MID
	 *
	 * @param string $MID
	 * @return object $VendorObject
	 */
	public function getVendorByMID($MID) {
		// Build query, prep, and bind
		$sql = "SELECT * FROM vendorids WHERE mid = :MID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":MID", $MID);

		// Execute the query
		$statement->execute();

		// Fetch object
		$VendorObject = $statement->fetchObject();

		// Return the object
		return $VendorObject;
	}
	
	/**
	 * getUniqueVendorsByPartnerID()
	 * Gets the list of MIDs for a given Partner ID
	 * 
	 * @param int $partnerID 
	 * @return object $vendors
	 */
	public function getUniqueVendorsByPartnerID($partnerID) {
		// Build query, prep, and bind
		$sql = "SELECT * 
			FROM vendorids
			LEFT JOIN partner_mid ON vendorids.id = partner_mid.mid_id 
			WHERE partner_mid.partner_id = :partnerID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":partnerID", $partnerID);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$vendors = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the vendors
		return $vendors;
	}

}

?>

<?php
/**
 * Description of Pricing
 *
 * @author zaguanno
 */
require_once "$base_dir/config.php";
require_once "EP/Util/Database.php";

class Pricing {
	/**
	 * DB Connection
	 */
	private $PDODB;

	/**
	 * __construct()
	 * Constructor of Pricing Class
	 */
	public function __construct() {
		// Connect to the DB
		$this->PDODB = EP_Util_Database::pdo_connect();
	}

	/**
	 * getRateClasses()
	 * Gets the rate classes
	 *
	 * @param int $refID
	 * @return array $rateClasses
	 */
	public function getRateClasses() {
		$rateClasses = array();
		
		// Build query, prep, and bind
		$sql = "SELECT * FROM rateclass ";
		$statement = $this->PDODB->prepare($sql);

		// Execute the query
		$statement->execute();

		// Fetch the results
		$rateClasses = $statement->fetchAll(PDO::FETCH_CLASS);

		// Return the classes
		return $rateClasses;
	}

}

?>

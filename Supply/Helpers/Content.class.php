<?php
/**
 * Description of Content
 *
 * @author zaguanno
 */
require_once "$base_dir/config.php";
require_once "EP/Util/Database.php";

class Content {
	/**
	 * DB Connection
	 */
	private $PDODB;
	
	/**
	 * Environment's Base URL
	 */
	private $baseURL;

	/**
	 * __construct()
	 * Constructor of Content Class
	 */
	public function __construct($baseURL = "") {
		// Connect to the DB
		$this->PDODB = EP_Util_Database::pdo_connect();
		
		// If baseURL is set, make it a private variable
		if($baseURL != "")
		{
			$this->baseURL = $baseURL;
		}
	}
	
	/**
	 * getReferralContent()
	 * Gets the page content for a referral partner
	 * 
	 * @param string $pageName
	 * @param int $stateID
	 * @param int $partnerID
	 * @return text $pageContent
	 */
	public function getReferralContent($pageName, $stateID, $partnerID) {
		$sql = "SELECT * FROM ref_partner_pages WHERE page_name = :pageName AND state = :stateID AND partner_id = :partnerID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":pageName", $pageName);
		$statement->bindValue(":stateID", $stateID);
		$statement->bindValue(":partnerID", $partnerID);
		
		// Execute the query
		$statement->execute();

		// Fetch the results
		$ContentObject = $statement->fetchObject();
		
		if(!is_object($ContentObject))
		{
			$ContentObject = $this->getDefaultReferralContent($pageName, $stateID);
			
			if(!is_object($ContentObject))
			{
				return "";
			}
		}
		
		$pageContent = $ContentObject->page_content;
		
		$this->replaceContentURLs($pageContent);
		
		return $pageContent;
	}
	
	/**
	 * getDefaultReferralContent()
	 * Gets the default page content for referrals
	 * 
	 * @param string $pageName
	 * @param int $stateID
	 * @return object $ContentObject
	 */
	private function getDefaultReferralContent($pageName, $stateID) {
		$sql = "SELECT * FROM ref_default_pages WHERE page_name = :pageName AND state = :stateID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":pageName", $pageName);
		$statement->bindValue(":stateID", $stateID);
		
		// Execute the query
		$statement->execute();

		// Fetch the results
		$ContentObject = $statement->fetchObject();

		// Return the Content Object
		return $ContentObject;
	}
	
	/**
	 * replaceContentURLs()
	 * Replace URLs in Page Content with Environment's Base URL
	 * 
	 * @param text $pageContent [by Reference]
	 */
	private function replaceContentURLs(&$pageContent) {
		// Replace base urls
		$pageContent = preg_replace('/\s(href|src)=["\']?\/?(?!(http?:))([^>"\'\s]+)/i', ' $1="'.$this->baseURL.'$3', $pageContent);
	}
	
	/**
	 * getDefaultDMContent()
	 * Gets the default DM page content
	 * 
	 * @param string $pageName
	 * @param int $stateID
	 * @return object $ContentObject
	 */
	public function getDefaultDMContent($pageName, $stateID) {
		$sql = "SELECT * FROM default_dm_content WHERE page_name = :pageName AND state = :stateID ";
		$statement = $this->PDODB->prepare($sql);
		$statement->bindValue(":pageName", $pageName);
		$statement->bindValue(":stateID", $stateID);
		
		// Execute the query
		$statement->execute();

		// Fetch the results
		$ContentObject = $statement->fetchObject();

		// Return the Content Object
		return $ContentObject;
	}
}

?>

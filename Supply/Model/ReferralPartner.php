<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Partner model ...
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_ReferralPartner extends EP_Model
{

	protected $id;//             | int(11)      | NO   | PRI | NULL    | auto_increment |
	protected $fname; //           | varchar(60)  | YES  |     | NULL    |                |
	protected $lname; //           | varchar(60)  | YES  |     | NULL    |                |
	protected $busname; //         | varchar(100) | YES  |     | NULL    |                |
	protected $billing_address; // | varchar(250) | YES  |     | NULL    |                |
	protected $phone1; //          | varchar(5)   | YES  |     | NULL    |                |
	protected $phone2; //          | varchar(5)   | YES  |     | NULL    |                |
	protected $phone3; //          | varchar(5)   | YES  |     | NULL    |                |
	protected $email; //           | varchar(150) | YES  |     | NULL    |                |
	protected $bcity; //          | varchar(60)  | YES  |     | NULL    |                |
	protected $billing_state; //   | varchar(3)   | YES  |     | NULL    |                |
	protected $bzip1; //           | varchar(6)   | YES  |     | NULL    |                |
	protected $bzip2; //           | varchar(4)   | YES  |     | NULL    |                |
	protected $agree_terms; //     | tinyint(4)   | YES  |     | NULL    |                |
	protected $entry_date; //      | int(11)      | YES  |     | NULL    |                |
	protected $refid; //           | varchar(6)   | YES  |     | NULL    |                |
	protected $bus_type; //        | varchar(50)  | YES  |     | NULL    |                |
	protected $tax_class; //       | varchar(50)  | YES  |     | NULL    |                |
	protected $tax_exempt; //      | tinyint(4)   | YES  |     | NULL    |                |
	protected $active; //          | tinyint(4)   | YES  |     | NULL    |                |
	protected $stateid; //         | int(11)      | YES  |     | NULL    |                |
	protected $base_partnerid; //  | int(11)      | YES  |     | NULL    |                |
	protected $partnerid; //       | int(11)      | YES  |     | NULL    |                |
	protected $password; //        | varchar(60)  | YES  |     | NULL    |                |
	protected $termsagree; //      | tinyint(4)   | YES  |     | NULL    |                |
	protected $in_list; //         | tinyint(4)   | YES  |     | 1       |                |

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return the $fname
	 */
	public function getFname() {
		return $this->fname;
	}

	/**
	 * @param field_type $fname
	 */
	public function setFname($fname) {
		$this->fname = $fname;
	}

	/**
	 * @return the $lname
	 */
	public function getLname() {
		return $this->lname;
	}

	/**
	 * @param field_type $lname
	 */
	public function setLname($lname) {
		$this->lname = $lname;
	}

	/**
	 * @return the $busname
	 */
	public function getBusname() {
		return $this->busname;
	}

	/**
	 * @param field_type $busname
	 */
	public function setBusname($busname) {
		$this->busname = $busname;
	}

	/**
	 * @return the $billing_address
	 */
	public function getBillingAddress() {
		return $this->billing_address;
	}

	/**
	 * @param field_type $billing_address
	 */
	public function setBillingAddress($billing_address) {
		$this->billing_address = $billing_address;
	}

	/**
	 * @return the $phone1
	 */
	public function getPhone1() {
		return $this->phone1;
	}

	/**
	 * @param field_type $phone1
	 */
	public function setPhone1($phone1) {
		$this->phone1 = $phone1;
	}

	/**
	 * @return the $phone2
	 */
	public function getPhone2() {
		return $this->phone2;
	}

	/**
	 * @param field_type $phone2
	 */
	public function setPhone2($phone2) {
		$this->phone2 = $phone2;
	}

	/**
	 * @return the $phone3
	 */
	public function getPhone3() {
		return $this->phone3;
	}

	/**
	 * @param field_type $phone3
	 */
	public function setPhone3($phone3) {
		$this->phone3 = $phone3;
	}

	/**
	 * @return the $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param field_type $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @return the $bcity
	 */
	public function getBcity() {
		return $this->bcity;
	}

	/**
	 * @param field_type $bcity
	 */
	public function setBcity($bcity) {
		$this->bcity = $bcity;
	}

	/**
	 * @return the $billing_state
	 */
	public function getBillingState() {
		return $this->billing_state;
	}

	/**
	 * @param field_type $billing_state
	 */
	public function setBillingState($billing_state) {
		$this->billing_state = $billing_state;
	}

	/**
	 * @return the $bzip1
	 */
	public function getBzip1() {
		return $this->bzip1;
	}

	/**
	 * @param field_type $bzip1
	 */
	public function setBzip1($bzip1) {
		$this->bzip1 = $bzip1;
	}

	/**
	 * @return the $bzip2
	 */
	public function getBzip2() {
		return $this->bzip2;
	}

	/**
	 * @param field_type $bzip2
	 */
	public function setBzip2($bzip2) {
		$this->bzip2 = $bzip2;
	}

	/**
	 * @return the $agree_terms
	 */
	public function getAgreeTerms() {
		return $this->agree_terms;
	}

	/**
	 * @param field_type $agree_terms
	 */
	public function setAgreeTerms($agree_terms) {
		$this->agree_terms = $agree_terms;
	}

	/**
	 * @return the $entry_date
	 */
	public function getEntryDate() {
		return $this->entry_date;
	}

	/**
	 * @param field_type $entry_date
	 */
	public function setEntryDate($entry_date) {
		$this->entry_date = $entry_date;
	}

	/**
	 * @return the $refid
	 */
	public function getRefid() {
		return $this->refid;
	}

	/**
	 * @param field_type $refid
	 */
	public function setRefid($refid) {
		$this->refid = $refid;
	}

	/**
	 * @return the $bus_type
	 */
	public function getBusType() {
		return $this->bus_type;
	}

	/**
	 * @param field_type $bus_type
	 */
	public function setBusType($bus_type) {
		$this->bus_type = $bus_type;
	}

	/**
	 * @return the $tax_class
	 */
	public function getTaxClass() {
		return $this->tax_class;
	}

	/**
	 * @param field_type $tax_class
	 */
	public function setTaxClass($tax_class) {
		$this->tax_class = $tax_class;
	}

	/**
	 * @return the $tax_exempt
	 */
	public function getTaxExempt() {
		return $this->tax_exempt;
	}

	/**
	 * @param field_type $tax_exempt
	 */
	public function setTaxExempt($tax_exempt) {
		$this->tax_exempt = $tax_exempt;
	}

	/**
	 * @return the $active
	 */
	public function getActive() {
		return $this->active;
	}

	/**
	 * @param field_type $active
	 */
	public function setActive($active) {
		$this->active = $active;
	}

	/**
	 * @return the $stateid
	 */
	public function getStateid() {
		return $this->stateid;
	}

	/**
	 * @param field_type $stateid
	 */
	public function setStateid($stateid) {
		$this->stateid = $stateid;
	}

	/**
	 * @return the $base_partnerid
	 */
	public function getBasePartnerid() {
		return $this->base_partnerid;
	}

	/**
	 * @param field_type $base_partnerid
	 */
	public function setBasePartnerid($base_partnerid) {
		$this->base_partnerid = $base_partnerid;
	}

	/**
	 * @return the $partnerid
	 */
	public function getPartnerid() {
		return $this->partnerid;
	}

	/**
	 * @param field_type $partnerid
	 */
	public function setPartnerid($partnerid) {
		$this->partnerid = $partnerid;
	}

	/**
	 * @return the $password
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param field_type $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @return the $termsagree
	 */
	public function getTermsagree() {
		return $this->termsagree;
	}

	/**
	 * @param field_type $termsagree
	 */
	public function setTermsagree($termsagree) {
		$this->termsagree = $termsagree;
	}

	/**
	 * @return the $in_list
	 */
	public function getInList() {
		return $this->in_list;
	}

	/**
	 * @param field_type $in_list
	 */
	public function setInList($in_list) {
		$this->in_list = $in_list;
	}


}
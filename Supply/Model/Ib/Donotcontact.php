<?php 

require_once dirname(__FILE__) . '/../../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Do not call model ...
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Ib_Donotcontact extends EP_Model
{
	protected $id;
	protected $call_id;
	protected $operator_id;
	protected $first_name;
	protected $mid_init;
	protected $last_name;
	protected $addr1;
	protected $addr2;
	protected $city;
	protected $state;
	protected $zip5;
	protected $zip4;
	protected $email;
	protected $phone;
	protected $date_created;
	
	/**
	 * 
	 * @param int $id
	 */
	public function setId( $id )
	{
		$this->id = $id;
		return $this;
	}
	
	/**
	 * 
	 * @return int 
	 */ 	 
	public function getId()
	{
		return (int)$this->id;
	}
	


	/**
	 * @return the $call_id
	 */
	public function getCallId() {
		return $this->call_id;
	}

	/**
	 * @param field_type $call_id
	 */
	public function setCallId($call_id) {
		$this->call_id = $call_id;
	}

	/**
	 * @return the $operator_id
	 */
	public function getOperatorId() {
		return $this->operator_id;
	}

	/**
	 * @param field_type $operator_id
	 */
	public function setOperatorId($operator_id) {
		$this->operator_id = $operator_id;
	}

	/**
	 * @return the $first_name
	 */
	public function getFirstName() {
		return $this->first_name;
	}

	/**
	 * @param field_type $first_name
	 */
	public function setFirstName($first_name) {
		$this->first_name = $first_name;
	}

	/**
	 * @return the $mid_init
	 */
	public function getMidInit() {
		return $this->mid_init;
	}

	/**
	 * @param field_type $mid_init
	 */
	public function setMidInit($mid_init) {
		$this->mid_init = $mid_init;
	}

	/**
	 * @return the $last_name
	 */
	public function getLastName() {
		return $this->last_name;
	}

	/**
	 * @param field_type $last_name
	 */
	public function setLastName($last_name) {
		$this->last_name = $last_name;
	}

	/**
	 * @return the $addr1
	 */
	public function getAddr1() {
		return $this->addr1;
	}

	/**
	 * @param field_type $addr1
	 */
	public function setAddr1($addr1) {
		$this->addr1 = $addr1;
	}

	/**
	 * @return the $addr2
	 */
	public function getAddr2() {
		return $this->addr2;
	}

	/**
	 * @param field_type $addr2
	 */
	public function setAddr2($addr2) {
		$this->addr2 = $addr2;
	}

	/**
	 * @return the $city
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * @param field_type $city
	 */
	public function setCity($city) {
		$this->city = $city;
	}

	/**
	 * @return the $state_id
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @param field_type $state_id
	 */
	public function setState($state) {
		$this->state = $state;
	}

	/**
	 * @return the $zip5
	 */
	public function getZip5() {
		return $this->zip5;
	}

	/**
	 * @param field_type $zip5
	 */
	public function setZip5($zip5) {
		$this->zip5 = $zip5;
	}

	/**
	 * @return the $zip4
	 */
	public function getZip4() {
		return $this->zip4;
	}

	/**
	 * @param field_type $zip4
	 */
	public function setZip4($zip4) {
		$this->zip4 = $zip4;
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
	 * @return the $service_phone
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @param field_type $service_phone
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}

	/**
	 * @return the $date_created
	 */
	public function getDateCreated() {
		return $this->date_created;
	}

	/**
	 * @param field_type $date_created
	 */
	public function setDateCreated($date_created) {
		$this->date_created = $date_created;
		return $this;
	}
	
}




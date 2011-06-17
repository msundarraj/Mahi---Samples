<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';

class EP_Model_Lead extends EP_Model 
{
	protected $id;
	protected $type_id;	// see the lead_types table
	protected $name_first;
	protected $name_last;
	protected $addr1;
	protected $addr2;
	protected $city;
	protected $state;
	protected $zip5;
	protected $zip4;
	protected $email;
	protected $phone;
	protected $partner;
	protected $utility;
	protected $date_created;
	
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
	 * @return the $type_id
	 * 	// see the lead_types table
	 */
	public function getTypeId() {
		return $this->type_id;
	}

	/**
	 * @param field_type $type_id
	 */
	public function setTypeId($type_id) {
		$this->type_id = $type_id;
	}

	/**
	 * @return the $name_first
	 */
	public function getNameFirst() {
		return $this->name_first;
	}

	/**
	 * @param field_type $name_first
	 */
	public function setNameFirst($name_first) {
		$this->name_first = $name_first;
	}

	/**
	 * @return the $name_last
	 */
	public function getNameLast() {
		return $this->name_last;
	}

	/**
	 * @param field_type $name_last
	 */
	public function setNameLast($name_last) {
		$this->name_last = $name_last;
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
	 * @return the $state
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @param field_type $state
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
	 * @return the $phone
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @param field_type $phone
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}

	/**
	 * @return the $partner
	 */
	public function getPartner() {
		return $this->partner;
	}

	/**
	 * @param field_type $partner
	 */
	public function setPartner($partner) {
		$this->partner = $partner;
	}

	/**
	 * @return the $utility
	 */
	public function getUtility() {
		return $this->utility;
	}

	/**
	 * @param field_type $utility
	 */
	public function setUtility($utility) {
		$this->utility = $utility;
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
	}

	
	
}




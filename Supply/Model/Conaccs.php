<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Conaccs model ...
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Conaccs extends EP_Model
{
	protected $memnum;
	protected $title;
	protected $fname;
	protected $mname;
	protected $lname;
	protected $add1;
	protected $add2;
	protected $aff3;
	protected $apt;
	protected $city;
	protected $state;
	protected $pcode;
	protected $country;
	
	/**
	 * @return the $memnum
	 */
	public function getMemnum() {
		return $this->memnum;
	}

	/**
	 * @param field_type $memnum
	 */
	public function setMemnum($memnum) {
		$this->memnum = $memnum;
	}

	/**
	 * @return the $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param field_type $title
	 */
	public function setTitle($title) {
		$this->title = $title;
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
	 * @return the $mname
	 */
	public function getMname() {
		return $this->mname;
	}

	/**
	 * @param field_type $mname
	 */
	public function setMname($mname) {
		$this->mname = $mname;
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
	 * @return the $add1
	 */
	public function getAdd1() {
		return $this->add1;
	}

	/**
	 * @param field_type $add1
	 */
	public function setAdd1($add1) {
		$this->add1 = $add1;
	}

	/**
	 * @return the $add2
	 */
	public function getAdd2() {
		return $this->add2;
	}

	/**
	 * @param field_type $add2
	 */
	public function setAdd2($add2) {
		$this->add2 = $add2;
	}

	/**
	 * @return the $aff3
	 */
	public function getAff3() {
		return $this->aff3;
	}

	/**
	 * @param field_type $aff3
	 */
	public function setAff3($aff3) {
		$this->aff3 = $aff3;
	}

	/**
	 * @return the $apt
	 */
	public function getApt() {
		return $this->apt;
	}

	/**
	 * @param field_type $apt
	 */
	public function setApt($apt) {
		$this->apt = $apt;
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
	 * @return the $pcode
	 */
	public function getPcode() {
		return $this->pcode;
	}

	/**
	 * @param field_type $pcode
	 */
	public function setPcode($pcode) {
		$this->pcode = $pcode;
	}

	/**
	 * @return the $country
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * @param field_type $country
	 */
	public function setCountry($country) {
		$this->country = $country;
	}



}





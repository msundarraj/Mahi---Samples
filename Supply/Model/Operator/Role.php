<?php 

require_once dirname(__FILE__) . '/../../../../config.php';
require_once 'EP/Model.php';


class EP_Model_Operator_Role extends EP_Model 
{
	protected $id;
	protected $name;
	protected $abbrev;
	protected $date_created;
	protected $date_mod;

		
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
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param field_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	
	/**
	 * @return the $abbrev
	 */
	public function getAbbrev() {
		return $this->abbrev;
	}

	/**
	 * @param field_type $abbrev
	 */
	public function setAbbrev($abbrev) {
		$this->abbrev = $abbrev;
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

	/**
	 * @return the $date_mod
	 */
	public function getDateMod() {
		return $this->date_mod;
	}

	/**
	 * @param field_type $date_mod
	 */
	public function setDateMod($date_mod) {
		$this->date_mod = $date_mod;
	}



}

<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';

class EP_Model_Supplier extends EP_Model 
{
	
	protected $id; //          | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
	protected $name; //        | varchar(250)     | NO   |     | NULL    |                |
	protected $state; //       | int(10)          | NO   |     | NULL    |                |
	protected $business; //    | tinyint(1)       | YES  |     | NULL    |                |
	protected $residential; // | tinyint(1)       | YES  |     | NULL    |                |

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
	 * @return the $business
	 */
	public function getBusiness() {
		return $this->business;
	}

	/**
	 * @param field_type $business
	 */
	public function setBusiness($business) {
		$this->business = $business;
	}

	/**
	 * @return the $residential
	 */
	public function getResidential() {
		return $this->residential;
	}

	/**
	 * @param field_type $residential
	 */
	public function setResidential($residential) {
		$this->residential = $residential;
	}

	
}
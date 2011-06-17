<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Corp Site Partner model ...
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Corpsitepartner extends EP_Model
{
	protected $id;
	protected $name;
	protected $url;
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
	 * @return the $url
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param field_type $url
	 */
	public function setUrl($url) {
		$this->url = $url;
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
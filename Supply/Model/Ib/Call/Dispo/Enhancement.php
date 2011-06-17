<?php 

require_once dirname(__FILE__) . '/../../../../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Call Dispo Enhancment model ...
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Ib_Call_Dispo_Enhancement extends EP_Model
{
	protected $id;
	protected $call_dispo_id;
	protected $code;
	protected $name;
	protected $date_created;
	protected $allow_comments;
	
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
	/**
	 * @return the $call_dispo_id
	 */
	public function getCallDispoId() {
		return $this->call_dispo_id;
	}

	/**
	 * @param field_type $call_dispo_id
	 */
	public function setCallDispoId($call_dispo_id) {
		$this->call_dispo_id = $call_dispo_id;
	}

	/**
	 * @return the $code
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @param field_type $code
	 */
	public function setCode($code) {
		$this->code = $code;
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
	 * @return the $call_dispo_id
	 */
	public function getCall_dispo_id() {
		return $this->call_dispo_id;
	}



	/**
	 * @return the $allow_comments
	 */
	public function getAllowComments() {
		return $this->allow_comments;
	}


	/**
	 * @param field_type $allow_comments
	 */
	public function setAllowComments($allow_comments) {
		$this->allow_comments = $allow_comments;
	}




}




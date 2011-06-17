<?php 

require_once dirname(__FILE__) . '/../../../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Dispo Asgn model ...
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Ib_Call_DisposAsgn extends EP_Model
{
	protected $id;
	protected $call_id;
	protected $call_dispo_id;
	protected $reason;
	protected $date_created;
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return (int)$this->id;
	}

	/**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return the $call_id
	 */
	public function getCallId() {
		return (int)$this->call_id;
	}

	/**
	 * @param field_type $call_id
	 */
	public function setCallId($call_id) {
		$this->call_id = $call_id;
	}

	/**
	 * @return the $dispo_id
	 */
	public function getCallDispoId() {
		return (int)$this->call_dispo_id;
	}

	/**
	 * @param field_type $dispo_id
	 */
	public function setCallDispoId($dispo_id) {
		$this->call_dispo_id = $dispo_id;
	}

	/**
	 * @return the $reason
	 */
	public function getReason() {
		return $this->reason;
	}

	/**
	 * @param field_type $reason
	 */
	public function setReason($reason) {
		$this->reason = $reason;
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
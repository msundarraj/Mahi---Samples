<?php 

require_once dirname(__FILE__) . '/../../../../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Call Dispo EnhancmentsAsgn model
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Ib_Call_Dispo_EnhancementsAsgn extends EP_Model
{
	protected $id;
	protected $call_dispo_asgn_id;
	protected $call_dispo_enhancement_id;
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
	 * @return the $call_dispo_asgn_id
	 */
	public function getCallDispoAsgnId() {
		return $this->call_dispo_asgn_id;
	}

	/**
	 * @param field_type $call_dispo_asgn_id
	 */
	public function setCallDispoAsgnId($call_dispo_asgn_id) {
		$this->call_dispo_asgn_id = $call_dispo_asgn_id;
	}

	/**
	 * @return the $call_dispo_enhancement_id
	 */
	public function getCallDispoEnhancementId() {
		return $this->call_dispo_enhancement_id;
	}

	/**
	 * @param field_type $call_dispo_enhancement_id
	 */
	public function setCallDispoEnhancementId($call_dispo_enhancement_id) {
		$this->call_dispo_enhancement_id = $call_dispo_enhancement_id;
	}





}




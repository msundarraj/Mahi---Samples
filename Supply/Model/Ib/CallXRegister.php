<?php 

require_once dirname(__FILE__) . '/../../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Call model ...
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Ib_CallXRegister extends EP_Model
{
	protected $id; //                        | int(10) unsigned     | NO   | PRI | NULL    | auto_increment |
	protected $call_id; //                | int(10) unsigned     | NO   | MUL | NULL    |                |
	protected $uid; //                       | varchar(32)          | NO   |     | NULL    |                |
	protected $reason; //                    | text                 | YES  |     | NULL    |                |
	protected $call_dispo_id; //             | int(10) unsigned     | YES  | MUL | NULL    |                |
	protected $call_dispo_enhancement_id; // | int(10) unsigned     | YES  | MUL | NULL    |                |
	protected $end_status_id; //             | smallint(5) unsigned | YES  | MUL | NULL    |                |
	protected $date_created; //              | datetime             | NO   |     | NULL    |                |
	protected $date_ended; //                | datetime             | YES  |     | NULL    |                |

	
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
	
	public function setCallId( $id )
	{
		$this->call_id = $id ;
		return $this;
	}
	
	public function getCallId( )
	{
		return $this->call_id;
	}

	/**
	 * @return the $uid
	 */
	public function getUid() {
		return $this->uid;
	}

	/**
	 * @param string $uid
	 */
	public function setUid( $uid ) {
		if ( strlen($uid) == 0 )
		{
			$this->setMessage( 'ERROR', 'uid must be larger then 0 chars' );
			return false;
		}
		
		$this->uid = $uid;
		return $this;
	}

	/**
	 * 
	 * return the $end_status_id
	 */
	public function getEndStatusId()
	{
		return $this->end_status_id;	
	}
	
	/**
	 * 
	 * Set the end status id
	 * @param int $id
	 */
	public function setEndStatusId( $id )
	{
		$this->end_status_id = $id;
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

	

	/**
	 * @return the $date_created
	 */
	public function getDateCreated() {
		return $this->date_created;
	}

	/**
	 * @param datetime $date_created
	 */
	public function setDateCreated($date_created) {
		$this->date_created = $date_created;
		return $this;
	}
	
	/**
	 * @return the $date_ended
	 */
	public function getDateEnded() {
		return $this->date_ended;
	}

	/**
	 * @param datetime $date_ended
	 */
	public function setDateEnded($date_ended) {
		$this->date_ended = $date_ended;
		return $this;
	}
		
}




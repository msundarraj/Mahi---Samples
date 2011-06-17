<?php 

require_once dirname(__FILE__) . '/../../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Call model ...
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Ib_Call extends EP_Model
{
	protected $id;
	protected $ext_cust_id;
	protected $vendor_id;
	protected $username;
	protected $rep_id;
	protected $cust_phone;
	protected $source_phone;
	protected $date_created;
	protected $date_ended;
	
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
	 * @return the $ext_cust_id
	 */
	public function getExtCustId() {
		return $this->ext_cust_id;
	}

	/**
	 * @param field_type $ext_cust_id
	 */
	public function setExtCustId($ext_cust_id) {
		if ( !is_numeric( $ext_cust_id ))
		{
			$this->setMessage( 'ERROR', 'ext_cust_id must be numeric' );
			return false;
		}
		$this->ext_cust_id = $ext_cust_id;
		return $this;
	}

	/**
	 * @return the $vendor_id
	 */
	public function getVendorId() {
		return $this->vendor_id;
	}

	/**
	 * @param field_type $vendor_id
	 */
	public function setVendorId($vendor_id) {
		if ( !is_numeric( $vendor_id ))
		{
			$this->setMessage( 'ERROR', 'vendor_id must be numeric' );
			return false;
		}
		$this->vendor_id = $vendor_id;
		return $this;
	}

	/**
	 * @return the $username
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @param field_type $username
	 */
	public function setUsername($username) {
		$this->username = $username;
		return $this;
	}

	/**
	 * @return the $rep_id
	 */
	public function getRepId() {
		return $this->rep_id;
	}

	/**
	 * @param field_type $rep_id
	 */
	public function setRepId($rep_id) {
		if ( !is_numeric( $rep_id ))
		{
			$this->setMessage( 'ERROR', 'rep_id must be numeric' );
			return false;
		}
		$this->rep_id = $rep_id;
		return $this;
	}

	/**
	 * @return the $cust_phone
	 */
	public function getCustPhone() {
		return $this->cust_phone;
	}

	/**
	 * @param field_type $cust_phone
	 */
	public function setCustPhone($cust_phone) {
		$this->cust_phone = $cust_phone;
		return $this;
	}

	/**
	 * @return the $source_phone
	 */
	public function getSourcePhone() {
		return $this->source_phone;
	}

	/**
	 * @param field_type $source_phone
	 */
	public function setSourcePhone($source_phone) {
		$this->source_phone = $source_phone;
		return $this;
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
		
	public function endCall(  $endMainCall = true )
	{
		if ( $endMainCall === true )
		{
			unset( $_SESSION['call'] );
		}
		
		// not sure what this is, but it needs to be removed
		unset( $_SESSION['submitaction'] );
		
		unset( $_SESSION['call_x_register'] );
		unset( $_SESSION['enroll_tel'] );
		unset( $_SESSION['first'] );
		unset( $_SESSION['register'] );
		unset( $_SESSION['st_abbrev'] );
		unset( $_SESSION['tabs'] );
		unset( $_SESSION['utility'] );
		unset( $_SESSION['web_addr'] );
		unset( $_SESSION['partner'] );
		
		unset( $_SESSION['pc'] );
		// deprecated starburst variables
		unset( $_SESSION['sb'] );

		// These are used for multiple accounts on account tab
		unset( $_SESSION['multiple_namekey'] );
		unset( $_SESSION['nk'] );
		unset( $_SESSION['sr'] );
		unset( $_SESSION['account'] );
		unset( $_SESSION['multiple_service'] );
		unset( $_SESSION['multiple_account'] );
	}
	
}




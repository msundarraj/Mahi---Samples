<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';
require_once 'EP/Util/Database.php';
require_once 'EP/Model/Operator/Role.php';

class EP_Model_Operator extends EP_Model 
{
	protected $id;
	protected $abbrev;
	protected $name_first;
	protected $name_last;
	protected $email;
	protected $password;
	protected $external_id;
	protected $vendor_id;
	protected $active;
	protected $date_created;
	protected $date_mod;
	
	
	protected $_roles;
	protected $_vendor;
	protected $_mid;
	

	
	
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

	public function getName()
	{
		return $this->name_first . ' ' . $this->name_last;	
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
	 * @return the $password
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param field_type $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @return the $external_id
	 */
	public function getExternalId() {
		return $this->external_id;
	}

	/**
	 * @param field_type $external_id
	 */
	public function setExternalId($external_id) {
		$this->external_id = $external_id;
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
		$this->vendor_id = $vendor_id;
	}

	/**
	 * @return the $active
	 */
	public function getActive() {
		return $this->active;
	}

	/**
	 * @param field_type $active
	 */
	public function setActive($active) {
		$this->active = $active;
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

	public static function isLoggedIn()
	{
		if ( isset( $_SESSION['operator']))
		{
			$operator = $_SESSION['operator'];
			if ( $operator->getEmail() && $operator->getId() )
			{
				return true;
			}
		}
		return false;
	}

	public static function hashPassword( $password )
	{
		return hash_hmac('sha256', $password, EP_SALT );
	}
	
	public static function login( $email, $password )
	{	
		$db = EP_Util_Database::pdo_connect();
		
		// $passwordHashed =  hash_hmac('sha256', $password, EP_SALT );
		$passwordHashed = EP_Model_Operator::hashPassword( $password );
		
		$sql = "SELECT * FROM operators ";
		$sql .= "WHERE email = ? AND password = ? and active = 1 ";
		
		$sth = $db->prepare( $sql );
		// print_r( $db->errorInfo() );
		// print_r ( $sth->errorInfo() );

		$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Operator' );

		$sth->execute( array( $email, $passwordHashed ));
		$result = $sth->fetch();
		
		// don't send back the password
		if ( $result )
		{
			$result->setPassword( null );

			// set the role
			$result->setRoles( $result->fetchRoles() );
			
			// set the vendor
			$result->setVendor( $result->fetchVendor() );
			
			// set up the session node
			$_SESSION['operator'] = $result;
			
		}

		return $result;
	}
	
	public function getMid()
	{
		if ( isset( $this->_vendor->mid))
		{
			return $this->_vendor->mid;
		}
		return false;	
	}
	
	protected function fetchVendor()
	{
		$db = EP_Util_Database::pdo_connect();
		$sql = "SELECT * FROM vendorids WHERE id = ? ";
		$sth = $db->prepare( $sql );
		$sth->setFetchMode( PDO::FETCH_OBJ );
		$sth->execute( array( $this->getVendorId() ));
		return $sth->fetch();	
	}
	
	public function setVendor( $obj )
	{
		if ( empty( $obj ))
		{
			$obj = $this->fetchVendor();
		}
		$this->_vendor = $obj;
		return $this;	
	}
	
	protected function fetchRoles()
	{
		require_once 'EP/Model/Operator/RoleMapper.php';
		$mapper = new EP_Model_Operator_RoleMapper();
		$arr = $mapper->fetchByOperator( $this->getId() );
		return $arr;
	}
	
	public function setRoles( $arr )
	{
		if ( empty( $arr ))
		{
			$arr = $this->fetchRoles();
		}
		$this->_roles = $arr;
		return $this;
	}
	
	public function getRoles()
	{
		return $this->_roles;
	}
	
	public function getVendor()
	{
		return $this->_vendor;
	}
	
	public function toObject()
	{
		$obj = new stdClass();
		$obj->id = (int)$this->getId();
		$obj->abbrev = $this->getAbbrev();
		$obj->name_first = $this->getNameFirst();
		$obj->name_last = $this->getNameLast();
		$obj->email = $this->getEmail();
		$obj->active = (int)$this->getActive();
		$obj->date_created = $this->getDateCreated();
		$obj->date_mod = $this->getDateMod();
		// $obj->mid = $this->getVendor()->mid;
		$obj->vendor_id = (int)$this->vendor_id;
		$obj->external_id = $this->external_id;
		$obj->password = "********";
		return $obj;
	}
	
	/**
	 * 
	 * Determines if the operator is EP personnel
	 * @param object $operator EP_Model_Operator
	 */
	public function isEPOperator( $operator )
	{
		$mid = $operator->getVendor()->mid;

		$validMids = array(
			'EPIB',
			'INSL',
			'D2D1'
		);
		
		if ( !in_array( $mid, $validMids ) )
		{
			return false;
		}
		
		return true;
	}
}



/*
 * 
     [62] => EP_Model_Operator Object
        (
            [id:protected] => 109
            [abbrev:protected] => STH
            [name_first:protected] => Stacey
            [name_last:protected] => Hoppman
            [email:protected] => shoppman@energypluscompany.com
            [password:protected] => 
            [external_id:protected] => 
            [vendor_id:protected] => 1
            [active:protected] => 1
            [date_created:protected] => 2011-02-10 07:45:01
            [date_mod:protected] => 2011-02-10 07:45:01
            [_roles:protected] => Array
                (
                    [0] => EP_Model_Operator_Role Object
                        (
                            [id:protected] => 3
                            [name:protected] => Supervisor
                            [abbrev:protected] => sup
                            [date_created:protected] => 2011-02-16 08:47:35
                            [date_mod:protected] => 2011-02-16 08:47:35
                            [_messages:private] => Array
                                (
                                )

                        )

                )

            [_vendor:protected] => stdClass Object
                (
                    [id] => 1
                    [mid] => EPIB
                    [brandonly] => 0
                    [refonly] => 0
                    [tx] => 1
                    [showref] => 0
                    [showrep] => 0
                    [replist] => 0
                    [description] => Energy Plus Inbound
                )

            [_mid:protected] => 
            [_messages:private] => Array
                (
                )

        )

 */
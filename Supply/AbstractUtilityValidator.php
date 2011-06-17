<?php

require_once dirname(__FILE__) . '/../../config.php';
require_once 'EP/Model/UtilityMapper.php';

abstract class EP_AbstractUtilityValidator
{
	private $code;

	protected $NOT_ALL_NUMBERS;
	protected $NOT_EXACT_LENGTH;
	protected $SUCCESS = array('valid'=>TRUE, 'message'=>NULL);

	protected $acclen;
	protected $acctext;
	protected $extra_account_name;

	/**
	   Constructor, used to set code

	   @param $code utility2.code value for this validator
	*/
	protected function __construct($code)
	{
		$this->code = $code;
		$mapper = new EP_Model_UtilityMapper();
		$util = $mapper->fetchByCode($this->code, array('acclen', 'acctext', 'extra_account_name'));
		if(empty($util))
		{
			throw new Exception('Utility not found');
		}
		$this->acclen = $util->acclen;
		$this->acctext = $util->acctext;
		$this->extra_account_name = $util->extra_account_name;

		$this->NOT_ALL_NUMBERS = array('valid'=>FALSE, 'message'=>"{$this->acctext} should be all numbers.");
		$this->NOT_EXACT_LENGTH = array('valid'=>FALSE, 'message'=>"{$this->acctext} should be {$this->acclen} digits long.");
	}

	/**
	   Checks utility account number for validity.  Default behavior is to require exact length match and all digits

	   Messages could be put into the db, using for example message ids.  It would be important to keep the logic and messages in sync.

	   @param $acct_num account number
	   @return associative array with two keys.  'valid' value is true if valid, false otherwise.  'message' value is message explaining why it's invalid, if applicable, or NULL.
	*/
	public function getValidity($acct_num)
	{
		if(!ctype_digit($acct_num))
		{
			return $this->NOT_ALL_NUMBERS;
		}
		if(!$this->lengthEqualsAcclen($acct_num))
		{
			return $this->NOT_EXACT_LENGTH;
		}
		return $this->SUCCESS;
	}

	/**
	   Checks whether the length of $acct_num is exactly the same as the acclen value for this utility in the database
	   
	   @param $acct_num account number
	   @return true if the length of the number exactly equals acclen
	*/
	protected function lengthEqualsAcclen($acct_num)
	{
		return strlen($acct_num) == $this->acclen;
	}
}

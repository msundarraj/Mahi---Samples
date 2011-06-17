<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// Connecticut Light & Power
class EP_UtilityValidator_clp extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('07');
	}

	public function getValidity($acct_num)
	{
		if(!ctype_digit($acct_num))
		{
			return $this->NOT_ALL_NUMBERS;
		}

		if(substr($acct_num, 6, 2) != '00')
		{
			return array('valid'=>FALSE, 'message'=>"Invalid number combination for {$this->acctext}");
		}

		if(!$this->lengthEqualsAcclen($acct_num))
		{
			return $this->NOT_EXACT_LENGTH;
		}

		return $this->SUCCESS;
	}

	/**
	   Checks extra utility account number for validity.  Default behavior is to require exact length match and all digits

	   Messages could be put into the db, using for example message ids.  It would be important to keep the logic and messages in sync.

	   @param $acct_num account number
	   @return associative array with two keys.  'valid' value is true if valid, false otherwise.  'message' value is message explaining why it's invalid, if applicable, or NULL.
	*/
	public function getExtraValidity($acct_num)
	{
		// Original JavaScript only errored for < 11, but given the message, I think that was an oversight.
		if(strlen($acct_num) != 11)
		{
			return array('valid'=>FALSE, 'message'=>"{$this->extra_account_name} should be 11 digits long.");
		}

		if(substr($acct_num, 0, 2) != '51')
		{
			return array('valid'=>FALSE, 'message'=>"{$this->extra_account_name} should begin with 51");
		}

		return $this->SUCCESS;
	}
}

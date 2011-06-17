<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// New Jersey Natural Gas
class EP_UtilityValidator_NJNG extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('49');
	}

	public function getValidity($acct_num)
	{
		if(!$this->lengthEqualsAcclen($acct_num))
		{
			return $this->NOT_EXACT_LENGTH;
		}
		
		$first_11 = substr($acct_num, 0, 11);
		$char_12 = strtoupper($acct_num[11]);
		if(!ctype_digit($first_11) || (!ctype_digit($char_12) && ($char_12 != 'Y')))
		{
			return array('valid'=>FALSE, 'message'=>"{$this->acctext} must start with 11 digits.  The final character must be either a digit or the letter Y.");
		}
		return $this->SUCCESS;
	}
}

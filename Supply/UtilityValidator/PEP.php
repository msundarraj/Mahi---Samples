<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// Pepco
class EP_UtilityValidator_PEP extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('32');
	}

	public function getValidity($acct_num)
	{
		if(!ctype_digit($acct_num))
		{
			return $this->NOT_ALL_NUMBERS;
		}

		if($acct_num[0] == '0' || $acct_num[0] == '1')
		{
			return array('valid'=>FALSE, 'message'=>'An Account Number starting with 0 or 1 indicates you are located outside of our MD service area');
		}

		if(!$this->lengthEqualsAcclen($acct_num))
		{
			return $this->NOT_EXACT_LENGTH;
		}

		return $this->SUCCESS;
	}
}

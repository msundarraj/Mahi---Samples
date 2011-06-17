<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// PSE&G
class EP_UtilityValidator_PSEG extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('29');
	}

	public function getValidity($acct_num)
	{
		if(substr($acct_num, 0, 2) != 'PE')
		{
			return array('valid'=>FALSE, 'message'=>"{$this->acctext} must start with PE and be followed by 18 numbers. Do not use PG (or the numbers after it) as this is your Gas ID.");
		}

		if(!$this->lengthEqualsAcclen($acct_num))
		{
			return $this->NOT_EXACT_LENGTH;
		}

		$rest = substr($acct_num, 2);
		$rest_all_digits = ctype_digit($rest);
		if(!$rest_all_digits)
		{
			return array('valid'=>FALSE, 'message'=>"{$this->acctext} must start with PE and be followed by 18 numbers.");
		}

		return $this->SUCCESS;
	}
}

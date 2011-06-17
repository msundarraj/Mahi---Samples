<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// NYSEG
class EP_UtilityValidator_ns extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('04');
	}

	public function getValidity($acct_num)
	{
		$rest = substr($acct_num, 3);
		if(!ctype_digit($rest))
		{
			return array('valid'=>FALSE, 'message'=>"{$this->acctext} must start with N01 and be followed by 12 numbers.");
		}

		if(substr($acct_num, 0, 3) != 'N01')
		{
			return array('valid'=>FALSE, 'message'=>"{$this->acctext} must begin N01");
		}

		if(!$this->lengthEqualsAcclen($acct_num))
		{
			return $this->NOT_EXACT_LENGTH;
		}

		return $this->SUCCESS;
	}
}

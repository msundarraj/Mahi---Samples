<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// RG&E
class EP_UtilityValidator_rg extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('05');
	}

	public function getValidity($acct_num)
	{
		$rest = substr($acct_num, 3);
		
		if(!ctype_digit($rest))
		{
			return array('valid'=>FALSE, 'message'=>"{$this->acctext} must start with R01 and be followed by 12 numbers.");
		}
		
		if(substr($acct_num, 0, 3) != 'R01')
		{
			return array('valid'=>FALSE, 'message'=>"{$this->acctext} must begin R01");
		}
		
		if(!$this->lengthEqualsAcclen($acct_num))
		{
			return $this->NOT_EXACT_LENGTH;
		}
		
		return $this->SUCCESS;
	}
}

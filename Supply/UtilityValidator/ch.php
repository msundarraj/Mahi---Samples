<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// Central Hudson
class EP_UtilityValidator_ch extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('03');
	}

	public function getValidity($acct_num)
	{
		if(!ctype_digit($acct_num))
		{
			return $this->NOT_ALL_NUMBERS;
		}

		$len = strlen($acct_num);
		// This is apparently the only utility for which acclen is a maximum, not an exact value.
		if($len != 10 && $len != 11)
		{
			return array('valid'=>FALSE, 'message'=>"{$this->acctext} should be 10 or 11 digits long.");
		}

		return $this->SUCCESS;
	}
}

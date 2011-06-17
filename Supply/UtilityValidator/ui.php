<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// United Illuminating Company
class EP_UtilityValidator_ui extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('08');
	}

	public function getValidity($acct_num)
	{
		if(!ctype_digit($acct_num))
		{
			return $this->NOT_ALL_NUMBERS;
		}

		if($acct_num[0] == '0')
		{
			return array('valid'=>FALSE, 'message'=>"{$this->acctext} cannot begin with 0");
		}

		if(!$this->lengthEqualsAcclen($acct_num))
		{
			return $this->NOT_EXACT_LENGTH;
		}

		return $this->SUCCESS;
	}
}

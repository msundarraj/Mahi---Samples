<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// Jersey Central Power & Light
class EP_UtilityValidator_JCPL extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('28');
	}

	public function getValidity($acct_num)
	{
		if(!ctype_digit($acct_num))
		{
			return $this->NOT_ALL_NUMBERS;
		}

		if(substr($acct_num, 0, 2) != '08')
		{
			return array('valid'=>FALSE, 'message'=>'Customer Number must start with 08.');
		}

		if(!$this->lengthEqualsAcclen($acct_num))
		{
			return $this->NOT_EXACT_LENGTH;
		}

		return $this->SUCCESS;
	}
}

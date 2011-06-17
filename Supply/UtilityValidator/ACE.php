<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// Atlantic City Electric
class EP_UtilityValidator_ACE extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('27');
	}
}

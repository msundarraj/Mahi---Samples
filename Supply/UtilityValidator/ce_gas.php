<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// ConEd Gas
class EP_UtilityValidator_ce_gas extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('37');
	}
}

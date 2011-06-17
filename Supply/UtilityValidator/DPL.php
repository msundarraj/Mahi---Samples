<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// Delmarva Power
class EP_UtilityValidator_DPL extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('33');
	}
}

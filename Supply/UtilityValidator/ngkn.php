<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// National Grid (Keyspan NY) Gas
class EP_UtilityValidator_ngkn extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('41');
	}
}

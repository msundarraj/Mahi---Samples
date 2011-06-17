<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// National Fuel Gas Company
class EP_UtilityValidator_nfg extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('38');
	}
}

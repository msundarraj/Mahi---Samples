<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// Rockland Electric Company (O&R)
class EP_UtilityValidator_RECO extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('30');
	}
}

<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// Orange & Rockland
class EP_UtilityValidator_or extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('06');
	}
}

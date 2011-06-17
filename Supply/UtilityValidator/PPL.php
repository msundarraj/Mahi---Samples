<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// PPL Electric Utilities
class EP_UtilityValidator_PPL extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('15');
	}
}

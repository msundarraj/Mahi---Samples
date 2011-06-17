<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

class EP_UtilityValidator_ComEd extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('36');
	}
}

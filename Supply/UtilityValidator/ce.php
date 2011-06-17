<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// ConEd
class EP_UtilityValidator_ce extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('01');
	}
}

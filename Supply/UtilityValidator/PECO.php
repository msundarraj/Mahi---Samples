<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// PECO
class EP_UtilityValidator_PECO extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('17');
	}
}

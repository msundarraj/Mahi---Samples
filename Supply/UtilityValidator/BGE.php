<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// BGE
class EP_UtilityValidator_BGE extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('31');
	}
}

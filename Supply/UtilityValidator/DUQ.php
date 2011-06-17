<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// Duquesne Light Company
class EP_UtilityValidator_DUQ extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('16');
	}
}

<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/AbstractUtilityValidator.php';

// National Grid / Niagara Mohawk
class EP_UtilityValidator_ng extends EP_AbstractUtilityValidator
{
	public function __construct()
	{
		parent::__construct('02');
	}
}

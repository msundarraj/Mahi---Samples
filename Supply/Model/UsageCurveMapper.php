<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';

class EP_Model_UsageCurveMapper extends EP_Mapper
{
	
	/**
	   @param $month month when quantity was used, 0-indexed (0 == January)
	   @param $commodity id from commodity table
	   @param $quantity quantity of commodity used in given month.  Measured in units from commodity.units_abbrev (e.g. therms)
	   @return boolean indicating whether the customer has high usage
	*/
	public function isHighUsage($month, $commodity, $quantity)
	{
		$db = $this->getDatabaseConnection();
		$sth = $db->prepare("SELECT usage_factor FROM usage_curve WHERE month = ? AND commodity = ? LIMIT 1");
		$sth->execute(array($month, $commodity));
              $factor = $sth->fetchColumn(0);
              $sth->closeCursor();
		$annual_usage = $quantity / $factor;
          	$sth= $db->prepare("SELECT annual_high_usage_threshold FROM commodity WHERE id = ? LIMIT 1 ");
          	$sth->execute(array($commodity));
		$threshold = $sth->fetchColumn(0);
    
		if($threshold == NULL)
		{
			return FALSE; // If no threshold, assume not high
		}
		return $annual_usage >= $threshold;
	}

}
<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Model/Starburstvariables.php';

/**
 *
 * Data Mapper for Starburstvariables model
 * @author Matthew Flaschen mflaschen@entech.com
 *
 */
class EP_Model_StarburstvariablesMapper extends EP_Mapper
{
    private static $names = array('Electricity', 'Gas');

    /**
       Returns commodity name, given code.
       @param code code as int
       @return name string
    */
    public static function getName($code)
    {
	return self::$names[$code];
    }

    /**
       Returns all fields for each row with given partner
       @param partner_id id of given partner
       @return all rows for given partner
    */
    function fetchAllByPartner($partner_id)
    {
	$db = EP_Util_Database::pdo_connect();
	$sql = 'SELECT * FROM starburst_variables WHERE partner_id = ?';
	$sth = $db->prepare($sql);
	if($this->getUsePublicVars())
	{
	    $sth->setFetchMode(PDO::FETCH_OBJ);
	}
	else
	{
	    $sth->setFetchMode(PDO::FETCH_CLASS, 'EP_Model_Starburstvariables');
	}
	$sth->execute(array($partner_id));
	return $sth->fetchAll();
    }

    /**
       This returns an array representing the commodities electricity, gas, both, or neither, depending on is_gas flag.
       Each element of the array is itself an array.  There are two keys.  name is a text representation, code is a numeric code.
       
       Electricity's code is 0.  Gas's is 1.

       @param partner_id id of given partner
       @return multi-dimensional array, with each row (if any) containing name and code for a possible commodity
    */
    function fetchCommoditiesByPartner($partner_id)
    {
	$db = EP_Util_Database::pdo_connect();
	$elec_sql = 'SELECT COUNT(*) > 0 FROM starburst_variables WHERE partner_id = ? AND NOT is_gas';
	$sth = $db->prepare($elec_sql);
	$sth->execute(array($partner_id));
	$commodities = array();
	if($sth->fetchColumn(0))
	{
	    $commodities[] = array('name'=>self::getName(0), 'code'=>0);
	}
	$sth->closeCursor();
	$gas_sql = 'SELECT COUNT(*) > 0 FROM starburst_variables WHERE partner_id = ? AND is_gas';
	$sth = $db->prepare($gas_sql);
	$sth->execute(array($partner_id));
	if($sth->fetchColumn(0))
	{
	    $commodities[] = array('name'=>self::getName(1), 'code'=>1);
	}
	$sth->closeCursor();
	return $commodities;
    }
    
	/**
	 * 
	 * Fetch the starburst variables from the database
	 * 
	 * @param int $partnerId
	 * @param string $promoCode
	 * @return object 
	 */
    public function fetchVariablesByPartnerAndPromo( $partnerId, $promoCode )
    {
    	$db = EP_Util_Database::pdo_connect();
 		$sql = "SELECT * FROM starburst_variables WHERE partner_id = :partner_id AND promo_trigger = :promo_code ";
		$sth = $db->prepare( $sql );
		// print_r( $db->errorInfo() );
		// print_r( $sth->errorInfo() );
		$sth->bindParam( ':partner_id', $partnerId, PDO::PARAM_INT);
		$sth->bindParam( ':promo_code', $promoCode, PDO::PARAM_STR, 5 );
		$sth->setFetchMode( PDO::FETCH_OBJ );
		$sth->execute();
		$sb = $sth->fetch();
		$sth->closeCursor();

		
    	// didn't find anything, need to use default
		// need to use the default promo code
		if ( !$sb )
		{
			$promoCode = 'DEF';
			$sql = "SELECT * FROM starburst_variables WHERE partner_id = :partner_id AND promo_trigger = 'DEF' ";
			$sth = $db->prepare( $sql );
			// print_r( $db->errorInfo() );
			// print_r( $sth->errorInfo() );
			$sth->bindParam( ':partner_id', $partnerId, PDO::PARAM_INT);
			$sth->setFetchMode( PDO::FETCH_OBJ );
			$sth->execute();
			$sb = $sth->fetch();
			$sth->closeCursor();

		}
		
		return $sb;
    }
}

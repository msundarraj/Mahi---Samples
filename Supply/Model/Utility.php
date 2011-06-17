<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';
require_once 'EP/Model/CountyMapper.php';

class EP_Model_Utility extends EP_Model
{
	protected $id;
	protected $code;
	protected $utility;
	protected $table_code;
	protected $state;
	protected $phone;
	protected $acctext;
	protected $acclen;
	protected $rctext;
	protected $srtext;
	protected $acctext_ext;
	protected $abbrev;
	protected $abbrev2;
	protected $extra_account_name;
	protected $new;
	protected $tdspduns;
	protected $tdspfixed;
	protected $tdspvariable;
	protected $unitprice_text;
	protected $unitprice_text5;
	protected $account_logic;
	protected $extra_account_logic;
	protected $position_error_msg;
	protected $extra_pos_error_msg;
	protected $active;
	protected $block_por;
	protected $good_pors;
	protected $por_field;
	protected $billmeth;
	protected $num_upload_files;
	protected $secondary_por_trigger;
	protected $secondary_por_field;
	protected $good_secondary_pors;
	protected $has_gas_option;
	protected $phone_emergency_gas;
	protected $phone_emergency_electric_bus;
	protected $phone_emergency_electric_res;
	protected $customer_service_hours;
	protected $addr_street1;
	protected $addr_street2;
	protected $addr_city;
	protected $addr_state;
	protected $addr_zip5;
	protected $addr_zip4;
	protected $url;
	protected $commodity;
	protected $ask_usage;
	protected $use_usage_curve;

	/**
	   @return the $id
	*/
	public function getId()
	{
		return $this->id;
	}

	/**
	   @param $id id
	*/
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	   @return the $code
	*/
	public function getCode()
	{
		return $this->code;
	}

	/**
	   @param $code code
	*/
	public function setCode($code)
	{
		$this->code = $code;
	}

	/**
	   @return the $utility
	*/
	public function getUtility()
	{
		return $this->utility;
	}

	/**
	   @param $utility utility name
	*/
	public function setUtility($utility)
	{
		$this->utility = $utility;
	}

	/**
	   @return the $table_code
	*/
	public function getTableCode()
	{
		return $this->table_code;
	}

	/**
	   @param $table_code table prefix code
	*/
	public function setTableCode($table_code)
	{
		$this->table_code = $table_code;
	}

	/**
	   @return the $state
	*/
	public function getState()
	{
		return $this->state;
	}

	/**
	   @param $state state id
	*/
	public function setState($state)
	{
		$this->state = $state;
	}

	/**
	   @return the $phone
	*/
	public function getPhone()
	{
		return $this->phone;
	}

	/**
	   @param $phone phone number
	*/
	public function setPhone($phone)
	{
		$this->phone = $phone;
	}

	/**
	   @return the $acctext
	*/
	public function getAcctext()
	{
		return $this->acctext;
	}

	/**
	   @param $acctext text label for account number
	*/
	public function setAcctext($acctext)
	{
		$this->acctext = $acctext;
	}

	/**
	   @return the $acclen
	*/
	public function getAcclen()
	{
		return $this->acclen;
	}

	/**
	   @param $acclen account number length
	*/
	public function setAcclen($acclen)
	{
		$this->acclen = $acclen;
	}

	/**
	   @return the $rctext
	*/
	public function getRctext()
	{
		return $this->rctext;
	}

	/**
	   @param $rctext the rctext
	*/
	public function setRctext($rctext)
	{
		$this->rctext = $rctext;
	}

	/**
	   @return the $srtext
	*/
	public function getSrtext()
	{
		return $this->srtext;
	}

	/**
	   @param $srtext the srtext
	*/
	public function setSrtext($srtext)
	{
		$this->srtext = $srtext;
	}

	/**
	   @return the $acctext_ext
	*/
	public function getAcctextExt()
	{
		return $this->acctext_ext;
	}

	/**
	   @param $acctext_ext additional account text
	*/
	public function setAcctextExt($acctext_ext)
	{
		$this->acctext_ext = $acctext_ext;
	}

	/**
	   @return the $abbrev
	*/
	public function getAbbrev()
	{
		return $this->abbrev;
	}

	/**
	   @param $abbrev utility abbreviation
	*/
	public function setAbbrev($abbrev)
	{
		$this->abbrev = $abbrev;
	}

	/**
	   @return the $abbrev2
	*/
	public function getAbbrev2()
	{
		return $this->abbrev2;
	}

	/**
	   @param $abbrev2 secondary utility abbreviation
	*/
	public function setAbbrev2($abbrev2)
	{
		$this->abbrev2 = $abbrev2;
	}

	/**
	   @return the $extra_account_name
	*/
	public function getExtraAccountName()
	{
		return $this->extra_account_name;
	}

	/**
	   @param $extra_account_name name for extra account
	*/
	public function setExtraAccountName($extra_account_name)
	{
		$this->extra_account_name = $extra_account_name;
	}

	/**
	   @return the $new
	*/
	public function getNew()
	{
		return $this->new;
	}

	/**
	   @param $new new flag
	*/
	public function setNew($new)
	{
		$this->new = $new;
	}

	/**
	   @return the $tdspduns
	*/
	public function getTdspduns()
	{
		return $this->tdspduns;
	}

	/**
	   @param $tdspduns TDSP DUNS
	*/
	public function setTdspduns($tdspduns)
	{
		$this->tdspduns = $tdspduns;
	}


	/**
	   @return the $tdspfixed
	*/
	public function getTdspfixed()
	{
		return $this->tdspfixed;
	}

	/**
	   @param $tdspfixed TDSP fixed price component
	*/
	public function setTdspfixed($tdspfixed)
	{
		$this->tdspfixed = $tdspfixed;
	}

	/**
	   @return the $tdspvariable
	*/
	public function getTdspvariable()
	{
		return $this->tdspvariable;
	}

	/**
	   @param $tdspvariable TDSP variable price component
	*/
	public function setTdspvariable()
	{
		$this->tdspvariable = $tdspvariable;
	}

	/**
	   @return the $unitprice_text
	*/
	public function getUnitpriceText()
	{
		return $this->unitprice_text;
	}

	/**
	   @param $unitprice_text unit price text
	*/
	public function setUnitpriceText($unitprice_text)
	{
		$this->unitprice_text = $unitprice_text;
	}

	/**
	   @return the $unitprice_text5
	*/
	public function getUnitpriceText5()
	{
		return $this->unitprice_text5;
	}

	/**
	   @param $unitprice_text5 unit price text (5)
	*/
	public function setUnitpriceText5($unitprice_text5)
	{
		$this->unitprice_text5 = $unitprice_text5;
	}

	/**
	   @return the $account_logic
	*/
	public function getAccountLogic()
	{
		return $this->account_logic;
	}

	/**
	   @param $account_logic logic rules for account number validation
	*/
	public function setAccountLogic($account_logic)
	{
		$this->account_logic = $account_logic;
	}

	/**
	   @return the $extra_account_logic
	*/
	public function getExtraAccountLogic()
	{
		return $this->extra_account_logic;
	}

	/**
	   @param $extra_account_logic logic rules for extra account number validation
	*/
	public function setExtraAccountLogic($extra_account_logic)
	{
		$this->extra_account_logic = $extra_account_logic;
	}

	/**
	   @return the $position_error_msg
	*/
	public function getPositionErrorMsg()
	{
		return $this->position_error_msg;
	}

	/**
	   @param $position_error_msg position error message
	*/
	public function setPositionErrorMsg()
	{
		$this->position_error_msg = $position_error_msg;
	}

	/**
	  @return the $extra_pos_error_msg 
	*/
	public function getExtraPosErrorMsg()
	{
		return $this->extra_pos_error_msg;
	}

	/**
	   @param $extra_pos_error_msg extra position error message
	*/
	public function setExtraPosErrorMsg($extra_pos_error_msg)
	{
		$this->extra_pos_error_msg = $extra_pos_error_msg;
	}

	/**
	   @return the $active flag
	*/
	public function getActive()
	{
		return $this->active;
	}

	/**
	   @param $active active flag
	*/
	public function setActive($active)
	{
		$this->active = $active;
	}

	/**
	   @return the $block_por
	*/
	public function getBlockPor()
	{
		return $this->block_por;
	}

	/**
	   @param $block_por block POR
	*/
	public function setBlockPor($block_por)
	{
		$this->block_por = $block_por;
	}

	/**
	   @return the $good_pors
	*/
	public function getGoodPors()
	{
		return $this->good_pors;
	}

	/**
	   @param $good_pors good PORs
	*/
	public function setGoodPors($good_pors)
	{
		$this->good_pors = $good_pors;
	}

	/**
	   @return the $por_field
	*/
	public function getPorField()
	{
		return $this->por_field;
	}

	/**
	   @param $por_field POR field
	*/
	public function setPorField($por_field)
	{
		$this->por_field = $por_field;
	}

	/**
	   @return the $billmeth
	*/
	public function getBillmeth()
	{
		return $this->billmeth;
	}

	/**
	   @param $billmeth bill method
	*/
	public function setBillmeth($billmeth)
	{
		$this->billmeth = $billmeth;
	}

	/**
	   @return the $num_upload_files
	*/
	public function getNumUploadFiles()
	{
		return $this->num_upload_files;
	}

	/**
	   @param $num_upload_files number of upload files
	*/
	public function setNumUploadFiles($num_upload_files)
	{
		$this->num_upload_files = $num_upload_files;
	}

	/**
	   @return the $secondary_por_trigger
	*/
	public function getSecondaryPorTrigger()
	{
		return $this->secondary_por_trigger;
	}

	/**
	   @param $secondary_por_trigger secondary POR trigger
	*/
	public function setSecondaryPorTrigger($secondary_por_trigger)
	{
		$this->secondary_por_trigger = $secondary_por_trigger;
	}

	/**
	   @return the $secondary_por_field
	*/
	public function getSecondaryPorField()
	{
		return $this->secondary_por_field;
	}

	/**
	   @param $secondary_por_field secondary POR field
	*/
	public function setSecondaryPorField($secondary_por_field)
	{
		$this->secondary_por_field = $secondary_por_field;
	}

	/**
	   @return the $good_secondary_pors
	*/
	public function getGoodSecondaryPors()
	{
		return $this->good_secondary_pors;
	}

	/**
	   @param $good_secondary_pors good secondary PORs
	*/
	public function setGoodSecondaryPors($good_secondary_pors)
	{
		$this->good_secondary_pors = $good_secondary_pors;
	}

	/**
	   @return the $has_gas_option flag
	*/
	public function getHasGasOption()
	{
		return $this->has_gas_option;
	}

	/**
	   @param $has_gas_option whether utility is both gas and electric
	*/
	public function setHasGasOption($has_gas_option)
	{
		$this->has_gas_option = $has_gas_option;
	}

	/**
	   @return $phone_emergency_gas, the emergency phone number for gas
	*/
	public function getPhoneEmergencyGas()
	{
		return $this->phone_emergency_gas;
	}

	/**
	   @param $phone_emergency_gas the emergency phone number for gas
	*/
	public function setPhoneEmergencyGas($phone_emergency_gas)
	{
		$this->phone_emergency = $phone_emergency_gas;
	}

	/**
	   @return $phone_emergency_electric_bus, the emergency phone number for electric business customers
	*/
	public function getPhoneEmergencyElectricBus()
	{
		return $this->phone_emergency_electric_bus;
	}

	/**
	   @param $phone_emergency_electric_bus the emergency phone number for electric business customers
	*/
	public function setPhoneEmergencyElectricBus($phone_emergency_electric_bus)
	{
		$this->phone_emergency_electric_bus = $phone_emergency_electric_bus;
	}

	/**
	   @return $phone_emergency_electric_res, the emergency phone number for electric residential customers
	*/
	public function getPhoneEmergencyElectricRes()
	{
		return $this->phone_emergency_electric_res;
	}

	/**
	   @param $phone_emergency_electric_res the emergency phone number for electric residential customers
	*/
	public function setPhoneEmergencyElectricRes($phone_emergency_electric_res)
	{
		$this->phone_emergency_electric_res = $phone_emergency_electric_res;
	}
	
	/**
	   @return $customer_service_hours, the hours customer service is open
	*/
	public function getCustomerServiceHours()
	{
		return $this->customer_service_hours;
	}

	/**
	   @param $customer_service_hours the hours customer service is open
	*/
	public function setCustomerServiceHours($customer_service_hours)
	{
		$this->customer_service_hours = $customer_service_hours;
	}

	/**
	   @return $addr_street1, main street address
	*/
	public function getAddrStreet1()
	{
		return $this->addr_street1;
	}

	/**
	   @param $addr_street1 main street address
	*/
	public function setAddrStreet1($addr_street1)
	{
		$this->addr_street1 = $addr_street1;
	}

	/**
	   @return $addr_street2, secondary street address
	*/
	public function getAddrStreet2()
	{
		return $this->addr_street2;
	}

	/**
	   @param $addr_street2 secondary street address
	*/
	public function setAddrStreet2($addr_street2)
	{
		$this->addr_street2 = $addr_street2;
	}

	/**
	   @return $addr_city, the address city
	*/
	public function getAddrCity()
	{
		return $this->addr_city;
	}

	/**
	   @param $addr_city the address city
	*/
	public function setAddrCity($addr_city)
	{
		$this->addr_city = $addr_city;
	}

	/**
	   @return $addr_state, the address state
	*/
	public function getAddrState()
	{
		return $this->addr_state;
	}

	/**
	   @param $addr_state the address state
	*/
	public function setAddrState($addr_state)
	{
		$this->addr_state = $addr_state;
	}

	/**
	   @return $addr_zip5, the address ZIP code
	*/
	public function getAddrZip5()
	{
		return $this->addr_zip5;
	}

	/**
	   @param $addr_zip5 the address ZIP code
	*/
	public function setAddrZip5($addr_zip5)
	{
		$this->addr_zip5 = $addr_zip5;
	}

	/**
	   @return $addr_zip4, the address ZIP4 code
	*/
	public function getAddrZip4()
	{
		return $this->addr_zip4;
	}

	/**
	   @param $addr_zip4 the address ZIP4 code
	*/
	public function setAddrZip4($addr_zip4)
	{
		$this->addr_zip4 = $addr_zip4;
	}

	/**
	   @return $url, the main customer URL
	*/
	public function getUrl()
	{
		return $this->url;
	}

	/**
	   @param $url the main customer URL
	*/
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	   @return $has_gas, boolean indicating whether utility supports gas registrations
	*/
	public function getHasGas()
	{
		return $this->has_gas;
	}

	/**
	   @param $has_gas boolean indicating whether utility supports gas registrations
	*/
	public function setHasGas($has_gas)
	{
		$this->has_gas = $has_gas;
	}

	/**
	   @return $commodity, integer keyed to id of commodity table, indicating supported commodity
	*/
	public function getCommodity()
	{
		return $this->commodity;
	}

	/**
	   @param $has_elec integer keyed to id of commodity table, indicating supported commodity
	*/
	public function setCommodity($commodity)
	{
		$this->commodity = $commodity;
	}
	
	/**
	   @return ask_usage, boolean indicating whether state asks about commodity usage
	*/
	public function getAskUsage()
	{
		return $this->ask_usage;
	}

	/**
	   @param $ask_usage boolean indicating whether state asks about commodity usage
	*/
	public function setAskUsage($ask_usage)
	{
		$this->ask_usage = $ask_usage;
	}

	/**
	   @return use_usage_curve, boolean indicating whether utility uses the commodity's usage curve (for estimating revclass)
	*/
	public function getUseUsageCurve()
	{
		return $this->use_usage_curve;
	}

	/**
	   @param $use_usage_curve boolean indicating whether utility uses the commodity's usage curve (for estimating revclass)
	*/
	public function setUseUsageCurve($use_usage_curve)
	{
		$this->use_usage_curve = $use_usage_curve;
	}

	/**
	   @return account number validator for this utility (or rather its abbrev)
	*/
	public function getValidator()
	{
		$validator_path = dirname(__FILE__) .  '/../UtilityValidator/' . $this->abbrev . '.php';
		if(!file_exists($validator_path)) // Presumably invalid abbrev
		{
			return NULL;
		}
		require_once $validator_path;
		$class_name = "EP_UtilityValidator_{$this->abbrev}";
		return new $class_name;
	}

	/**
	   TODO: This is borrowed from newcentral and still relies on hard-coding.

	   @param $zip5 Service ZIP5 for registration
	   @return iso
	*/
	public function getIso($zip5)
	{
		if($this->state == 2)
		{
			return 'CT';
		}
		// PA, NJ, MD, IL
		if($this->state == 4 || $this->state == 5 || $this->state == 6 || $this->state == 7)
		{
			// Transform from filesystem-friendly abbrev to backend-friendly iso
			return str_replace('_', ' ', $this->abbrev);
		}
		if($this->code == '01')
		{
			$sql = 'SELECT code FROM iso WHERE zip = ? LIMIT 1';
			$db = EP_Util_Database::pdo_connect();
			$sth = $db->prepare($sql);
			$sth->execute(array($zip5));
			$iso_db_lookup = $sth->fetchColumn(0);
			return $iso_db_lookup ? $iso_db_lookup : 'J';
		}
		if($this->code != '02') // not Nat Grid
		{
			$county_mapper = new EP_Model_CountyMapper();
			$county_row = $county_mapper->fetchByPrefixAndZip($this->table_code, $zip5);
			if(!empty($county_row))
			{
				return $county_row->iso;
			}
		}
		return '';
	}
}

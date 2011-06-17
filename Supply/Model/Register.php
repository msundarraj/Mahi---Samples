<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Register model ...
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Register extends EP_Model
{

	protected $id = 0; //                 | int(11)      | NO   | PRI | NULL    | auto_increment |
	protected $regdate; //           | int(11)      | YES  |     | NULL    |                |
	protected $uid; //                | varchar(32)  | YES  | MUL | NULL    |                |
	protected $sequence; //           | varchar(3)   | YES  |     | NULL    |                |
	protected $vendorid; //           | varchar(10)  | YES  |     | NULL    |                |
	protected $apptype; //            | varchar(10)  | YES  |     | NULL    |                |
	protected $first_name; //         | varchar(60)  | YES  |     | NULL    |                |
	protected $mid_init; //           | varchar(5)   | YES  |     | NULL    |                |
	protected $last_name; //          | varchar(60)  | YES  |     | NULL    |                |
	protected $suffix; //             | varchar(5)   | YES  |     | NULL    |                |
	protected $vip; //                | varchar(3)   | YES  |     | NULL    |                |
	protected $busname; //            | varchar(150) | YES  |     | NULL    |                |
	protected $revclass; //           | varchar(5)   | YES  |     | NULL    |                |
	protected $servicephone; //       | varchar(32)  | YES  |     | NULL    |                |
	protected $sericeext; //          | varchar(5)   | YES  |     | NULL    |                |
	protected $addr1; //              | varchar(64)  | YES  |     | NULL    |                |
	protected $addr2; //              | varchar(64)  | YES  |     | NULL    |                |
	protected $addr3; //              | varchar(64)  | YES  |     | NULL    |                |
	protected $city; //               | varchar(20)  | YES  |     | NULL    |                |
	protected $state; //              | varchar(20)  | YES  |     | NULL    |                |
	protected $zip5; //               | varchar(5)   | YES  |     | NULL    |                |
	protected $zip4; //               | varchar(4)   | YES  |     | NULL    |                |
	protected $county; //             | varchar(25)  | YES  |     | NULL    |                |
	protected $billphone; //          | varchar(32)  | YES  |     | NULL    |                |
	protected $billext; //            | varchar(10)  | YES  |     | NULL    |                |
	protected $baddr1; //             | varchar(64)  | YES  |     | NULL    |                |
	protected $baddr2; //             | varchar(64)  | YES  |     | NULL    |                |
	protected $baddr3; //             | varchar(64)  | YES  |     | NULL    |                |
	protected $bcity; //              | varchar(20)  | YES  |     | NULL    |                |
	protected $bstate; //             | varchar(10)  | YES  |     | NULL    |                |
	protected $bzip5; //              | varchar(5)   | YES  |     | NULL    |                |
	protected $bzip4; //              | varchar(4)   | YES  |     | NULL    |                |
	protected $bcounty; //            | varchar(25)  | YES  |     | NULL    |                |
	protected $att; //                | varchar(100) | YES  |     | NULL    |                |
	protected $email; //              | varchar(150) | YES  |     | NULL    |                |
	protected $servicetype; //        | tinyint(4)   | YES  |     | NULL    |                |
	protected $territory_code; //     | varchar(10)  | YES  |     | NULL    |                |
	protected $marketer; //           | varchar(10)  | YES  |     | NULL    |                |
	protected $iso; //                | varchar(10)  | YES  |     | NULL    |                |
	protected $taxex; //              | tinyint(4)   | YES  |     | NULL    |                |
	protected $billmeth; //           | varchar(5)   | YES  |     | NULL    |                |
	protected $entype; //             | tinyint(4)   | YES  |     | NULL    |                |
	protected $today; //              | varchar(10)  | YES  |     | NULL    |                |
	protected $distrib; //            | varchar(10)  | YES  |     | NULL    |                |
	protected $account; //            | varchar(30)  | YES  |     | NULL    |                |
	protected $rateclass; //          | varchar(10)  | YES  |     | NULL    |                |
	protected $promocode; //          | varchar(5)   | YES  |     | NULL    |                |
	protected $priceplan; //          | varchar(10)  | YES  |     | NULL    |                |
	protected $bdate; //              | varchar(10)  | YES  |     | NULL    |                |
	protected $edate; //              | varchar(10)  | YES  |     | NULL    |                |
	protected $edate_plan; //         | varchar(10)  | YES  |     | NULL    |                |
	protected $repid; //              | varchar(20)  | YES  |     | NULL    |                |
	protected $vas; //                | varchar(10)  | YES  |     | NULL    |                |
	protected $campaign; //           | varchar(5)   | YES  |     | NULL    |                |
	protected $cellcode; //           | varchar(5)   | YES  |     | NULL    |                |
	protected $saledate; //           | int(11)      | YES  |     | NULL    |                |
	protected $partnercode; //        | varchar(5)   | YES  |     | NULL    |                |
	protected $partner_memnum; //     | varchar(20)  | YES  |     | NULL    |                |
	protected $memlevel; //           | varchar(15)  | YES  |     | NULL    |                |
	protected $auth; //               | varchar(25)  | YES  |     | NULL    |                |
	protected $accept; //             | tinyint(4)   | YES  |     | NULL    |                |
	protected $confcode; //           | varchar(25)  | YES  |     | NULL    |                |
	protected $nowtime; //            | int(11)      | YES  |     | NULL    |                |
	protected $hpsemail; //           | varchar(150) | YES  |     | NULL    |                |
	protected $origurl; //            | varchar(150) | YES  |     | NULL    |                |
	protected $greenopt; //           | varchar(6)   | YES  |     | NULL    |                |
	protected $refid; //              | varchar(10)  | YES  |     | NULL    |                |
	protected $sourceip; //           | varchar(32)  | YES  |     | NULL    |                |
	protected $busres; //             | int(11)      | YES  |     | NULL    |                |
	protected $budget; //             | int(11)      | YES  |     | NULL    |                |
	protected $entby; //              | varchar(5)   | YES  |     | NULL    |                |
	protected $appby; //              | varchar(5)   | YES  |     | NULL    |                |
	protected $namekey; //            | varchar(32)  | YES  |     | NULL    |                |
	protected $baccount; //           | varchar(32)  | YES  |     | NULL    |                |
	protected $introgroup; //         | varchar(32)  | YES  |     |         |                |
	protected $mkgroup; //            | varchar(32)  | YES  |     |         |                |
	protected $stateid; //            | int(11)      | YES  |     | NULL    |                |
	protected $pfname; //             | varchar(20)  | YES  |     | NULL    |                |
	protected $plname; //             | varchar(20)  | YES  |     | NULL    |                |
	protected $enrollcustid; //       | varchar(20)  | YES  |     | NULL    |                |
	protected $fico; //               | varchar(20)  | YES  |     | NULL    |                |
	protected $paysrc; //             | varchar(20)  | YES  |     | NULL    |                |
	protected $paymeth; //            | varchar(20)  | YES  |     | NULL    |                |
	protected $payamt; //             | float        | YES  |     | NULL    |                |
	protected $contractterm; //       | varchar(50)  | YES  |     | NULL    |                |
	protected $spanishbill; //        | varchar(5)   | YES  |     | NULL    |                |
	protected $notificationwaiver; // | varchar(5)   | YES  |     | NULL    |                |
	protected $dob; //                | varchar(32)  | YES  |     | NULL    |                |
	protected $mothermaiden; //       | varchar(24)  | YES  |     | NULL    |                |
	protected $taxid; //              | varchar(10)  | YES  |     | NULL    |                |
	protected $Credit1; //          | varchar(10)  | YES  |     | NULL    |                |
	protected $Credit2; //            | varchar(30)  | YES  |     | NULL    |                |
	protected $kWh; //                | varchar(10)  | YES  |     | NULL    |                |
	protected $RentOwn; //            | varchar(2)   | YES  |     | NULL    |                |
	protected $ResidenceLength; //    | int(11)      | YES  |     | NULL    |                |
	protected $EmployeeCount; //      | int(11)      | YES  |     | NULL    |                |
	protected $BusinessLength; //     | int(11)      | YES  |     | NULL    |                |
	protected $CurrentSupplier; //    | tinyint(4)   | YES  |     | NULL    |                |
	protected $spfname; //            | varchar(50)  | YES  |     | NULL    |                |
	protected $splname; //            | varchar(50)  | YES  |     | NULL    |                |
	protected $ProdCode; //           | varchar(10)  | YES  |     | NULL    |                |
	protected $FixedIntro; //         | varchar(10)  | YES  |     | NULL    |                |
	protected $noexport; //           | tinyint(4)   | YES  |     | NULL    |                |
	protected $years_inbiz; //        | varchar(60)  | YES  |     | NULL    |                |
	protected $years_bizaddr; //      | varchar(60)  | YES  |     | NULL    |                |
	protected $late_payment6; //      | varchar(60)  | YES  |     | NULL    |                |
	protected $busname_change; //     | varchar(60)  | YES  |     | NULL    |                |
	protected $elec_supp_prevyear; // | varchar(60)  | YES  |     | NULL    |                |
	protected $years_creditbiz; //    | varchar(60)  | YES  |     | NULL    |                |

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return the $regdate
	 */
	public function getRegdate() {
		return $this->regdate;
	}

	/**
	 * @param field_type $regdate
	 */
	public function setRegdate($regdate) {
		$this->regdate = $regdate;
	}

	/**
	 * @return the $uid
	 */
	public function getUid() {
		return $this->uid;
	}

	/**
	 * @param field_type $uid
	 */
	public function setUid($uid) {
		$this->uid = $uid;
	}

	/**
	 * @return the $sequence
	 */
	public function getSequence() {
		return $this->sequence;
	}

	/**
	 * @param field_type $sequence
	 */
	public function setSequence($sequence) {
		$this->sequence = $sequence;
	}

	/**
	 * @return the $vendorid
	 */
	public function getVendorid() {
		return $this->vendorid;
	}

	/**
	 * @param field_type $vendorid
	 */
	public function setVendorid($vendorid) {
		$this->vendorid = $vendorid;
	}

	/**
	 * @return the $apptype
	 */
	public function getApptype() {
		return $this->apptype;
	}

	/**
	 * @param field_type $apptype
	 */
	public function setApptype($apptype) {
		$this->apptype = $apptype;
	}

	/**
	 * @return the $first_name
	 */
	public function getFirstName() {
		return $this->first_name;
	}

	/**
	 * @param field_type $first_name
	 */
	public function setFirstName($first_name) {
		$this->first_name = $first_name;
	}

	/**
	 * @return the $mid_init
	 */
	public function getMidInit() {
		return $this->mid_init;
	}

	/**
	 * @param field_type $mid_init
	 */
	public function setMidInit($mid_init) {
		$this->mid_init = $mid_init;
	}

	/**
	 * @return the $last_name
	 */
	public function getLastName() {
		return $this->last_name;
	}

	/**
	 * @param field_type $last_name
	 */

	public function setLastName($last_name) {
		$this->last_name = $last_name;
	}

	/**
	 * @return the $suffix
	 */
	public function getSuffix() {
		return $this->suffix;
	}

	/**
	 * @param field_type $suffix
	 */
	public function setSuffix($suffix) {
		$this->suffix = $suffix;
	}

	/**
	 * @return the $vip
	 */
	public function getVip() {
		return $this->vip;
	}

	/**
	 * @param field_type $vip
	 */
	public function setVip($vip) {
		$this->vip = $vip;
	}

	/**
	 * @return the $busname
	 */
	public function getBusname() {
		return $this->busname;
	}

	/**
	 * @param field_type $busname
	 */
	public function setBusname($busname) {
		$this->busname = $busname;
	}

	/**
	 * @return the $revclass
	 */
	public function getRevclass() {
		return $this->revclass;
	}

	/**
	 * @param field_type $revclass
	 */
	public function setRevclass($revclass) {
		$this->revclass = $revclass;
	}

	/**
	 * @return the $servicephone
	 */
	public function getServicephone() {
		return $this->servicephone;
	}

	/**
	 * @param field_type $servicephone
	 */
	public function setServicephone($servicephone) {
		$this->servicephone = $servicephone;
	}

	/**
	 * @return the $sericeext
	 */
	public function getSericeext() {
		return $this->sericeext;
	}

	/**
	 * @param field_type $sericeext
	 */
	public function setSericeext($sericeext) {
		$this->sericeext = $sericeext;
	}

	/**
	 * @return the $addr1
	 */
	public function getAddr1() {
		return $this->addr1;
	}

	/**
	 * @param field_type $addr1
	 */
	public function setAddr1($addr1) {
		$this->addr1 = $addr1;
	}

	/**
	 * @return the $addr2
	 */
	public function getAddr2() {
		return $this->addr2;
	}

	/**
	 * @param field_type $addr2
	 */
	public function setAddr2($addr2) {
		$this->addr2 = $addr2;
	}

	/**
	 * @return the $addr3
	 */
	public function getAddr3() {
		return $this->addr3;
	}

	/**
	 * @param field_type $addr3
	 */
	public function setAddr3($addr3) {
		$this->addr3 = $addr3;
	}

	/**
	 * @return the $city
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * @param field_type $city
	 */
	public function setCity($city) {
		$this->city = $city;
	}

	/**
	 * @return the $state
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @param field_type $state
	 */
	public function setState($state) {
		$this->state = $state;
	}

	/**
	 * @return the $zip5
	 */
	public function getZip5() {
		return $this->zip5;
	}

	/**
	 * @param field_type $zip5
	 */
	public function setZip5($zip5) {
		$this->zip5 = $zip5;
	}

	/**
	 * @return the $zip4
	 */
	public function getZip4() {
		return $this->zip4;
	}

	/**
	 * @param field_type $zip4
	 */
	public function setZip4($zip4) {
		$this->zip4 = $zip4;
	}

	/**
	 * @return the $county
	 */
	public function getCounty() {
		return $this->county;
	}

	/**
	 * @param field_type $county
	 */
	public function setCounty($county) {
		$this->county = $county;
	}

	/**
	 * @return the $billphone
	 */
	public function getBillphone() {
		return $this->billphone;
	}

	/**
	 * @param field_type $billphone
	 */
	public function setBillphone($billphone) {
		$this->billphone = $billphone;
	}

	/**
	 * @return the $billext
	 */
	public function getBillext() {
		return $this->billext;
	}

	/**
	 * @param field_type $billext
	 */
	public function setBillext($billext) {
		$this->billext = $billext;
	}

	/**
	 * @return the $baddr1
	 */
	public function getBaddr1() {
		return $this->baddr1;
	}

	/**
	 * @param field_type $baddr1
	 */
	public function setBaddr1($baddr1) {
		$this->baddr1 = $baddr1;
	}

	/**
	 * @return the $baddr2
	 */
	public function getBaddr2() {
		return $this->baddr2;
	}

	/**
	 * @param field_type $baddr2
	 */
	public function setBaddr2($baddr2) {
		$this->baddr2 = $baddr2;
	}

	/**
	 * @return the $baddr3
	 */
	public function getBaddr3() {
		return $this->baddr3;
	}


	/**
	 * @param field_type $baddr3
	 */
	public function setBaddr3($baddr3) {
		$this->baddr3 = $baddr3;
	}

	/**
	 * @return the $bcity
	 */
	public function getBcity() {
		return $this->bcity;
	}

	/**
	 * @param field_type $bcity
	 */
	public function setBcity($bcity) {
		$this->bcity = $bcity;
	}

	/**
	 * @return the $bstate
	 */
	public function getBstate() {
		return $this->bstate;
	}

	/**
	 * @param field_type $bstate
	 */
	public function setBstate($bstate) {
		$this->bstate = $bstate;
	}

	/**
	 * @return the $bzip5
	 */
	public function getBzip5() {
		return $this->bzip5;
	}

	/**
	 * @param field_type $bzip5
	 */
	public function setBzip5($bzip5) {
		$this->bzip5 = $bzip5;
	}

	/**
	 * @return the $bzip4
	 */
	public function getBzip4() {
		return $this->bzip4;
	}

	/**
	 * @param field_type $bzip4
	 */
	public function setBzip4($bzip4) {
		$this->bzip4 = $bzip4;
	}

	/**
	 * @return the $bcounty
	 */
	public function getBcounty() {
		return $this->bcounty;
	}

	/**
	 * @param field_type $bcounty
	 */
	public function setBcounty($bcounty) {
		$this->bcounty = $bcounty;
	}

	/**
	 * @return the $att
	 */
	public function getAtt() {
		return $this->att;
	}

	/**
	 * @param field_type $att
	 */
	public function setAtt($att) {
		$this->att = $att;
	}

	/**
	 * @return the $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param field_type $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @return the $servicetype
	 */
	public function getServicetype() {
		return $this->servicetype;
	}

	/**
	 * @param field_type $servicetype
	 */
	public function setServicetype($servicetype) {
		$this->servicetype = $servicetype;
	}

	/**
	 * @return the $territory_code
	 */
	public function getTerritoryCode() {
		return $this->territory_code;
	}

	/**
	 * @param field_type $territory_code
	 */
	public function setTerritoryCode($territory_code) {
		$this->territory_code = $territory_code;
	}

	/**
	 * @return the $marketer
	 */
	public function getMarketer() {
		return $this->marketer;
	}

	/**
	 * @param field_type $marketer
	 */
	public function setMarketer($marketer) {
		$this->marketer = $marketer;
	}

	/**
	 * @return the $iso
	 */
	public function getIso() {
		return $this->iso;
	}

	/**
	 * @param field_type $iso
	 */
	public function setIso($iso) {
		$this->iso = $iso;
	}

	/**
	 * @return the $taxex
	 */
	public function getTaxex() {
		return $this->taxex;
	}

	/**
	 * @param field_type $taxex
	 */
	public function setTaxex($taxex) {
		$this->taxex = $taxex;
	}

	/**
	 * @return the $billmeth
	 */
	public function getBillmeth() {
		return $this->billmeth;
	}

	/**
	 * @param field_type $billmeth
	 */
	public function setBillmeth($billmeth) {
		$this->billmeth = $billmeth;
	}

	/**
	 * @return the $entype
	 */
	public function getEntype() {
		return $this->entype;
	}

	/**
	 * @param field_type $entype
	 */
	public function setEntype($entype) {
		$this->entype = $entype;
	}

	/**
	 * @return the $today
	 */
	public function getToday() {
		return $this->today;
	}

	/**
	 * @param field_type $today
	 */
	public function setToday($today) {
		$this->today = $today;
	}

	/**
	 * @return the $distrib
	 */
	public function getDistrib() {
		return $this->distrib;
	}

	/**
	 * @param field_type $distrib
	 */
	public function setDistrib($distrib) {
		$this->distrib = $distrib;
	}

	/**
	 * @return the $account
	 */
	public function getAccount() {
		return $this->account;
	}

	/**
	 * @param field_type $account
	 */
	public function setAccount($account) {
		$this->account = $account;
	}

	/**
	 * @return the $rateclass
	 */
	public function getRateclass() {
		return $this->rateclass;
	}

	/**
	 * @param field_type $rateclass
	 */
	public function setRateclass($rateclass) {
		$this->rateclass = $rateclass;
	}

	/**

	 * @return the $promocode
	 */
	public function getPromocode() {
		return $this->promocode;
	}

	/**
	 * @param field_type $promocode
	 */
	public function setPromocode($promocode) {
		$this->promocode = $promocode;
	}

	/**
	 * @return the $priceplan
	 */
	public function getPriceplan() {
		return $this->priceplan;
	}

	/**
	 * @param field_type $priceplan
	 */
	public function setPriceplan($priceplan) {
		$this->priceplan = $priceplan;
	}

	/**
	 * @return the $bdate
	 */
	public function getBdate() {
		return $this->bdate;
	}

	/**
	 * @param field_type $bdate
	 */
	public function setBdate($bdate) {
		$this->bdate = $bdate;
	}

	/**
	 * @return the $edate
	 */
	public function getEdate() {
		return $this->edate;
	}

	/**
	 * @param field_type $edate
	 */
	public function setEdate($edate) {
		$this->edate = $edate;
	}

	/**
	 * @return the $edate_plan
	 */
	public function getEdatePlan() {
		return $this->edate_plan;
	}

	/**
	 * @param field_type $edate_plan
	 */
	public function setEdatePlan($edate_plan) {
		$this->edate_plan = $edate_plan;
	}

	/**
	 * @return the $repid
	 */
	public function getRepid() {
		return $this->repid;
	}

	/**
	 * @param field_type $repid
	 */
	public function setRepid($repid) {
		$this->repid = $repid;
	}

	/**
	 * @return the $vas
	 */
	public function getVas() {
		return $this->vas;
	}

	/**
	 * @param field_type $vas
	 */
	public function setVas($vas) {
		$this->vas = $vas;
	}

	/**
	 * @return the $campaign
	 */
	public function getCampaign() {
		return $this->campaign;
	}

	/**
	 * @param field_type $campaign
	 */
	public function setCampaign($campaign) {
		$this->campaign = $campaign;
	}

	/**
	 * @return the $cellcode
	 */
	public function getCellcode() {
		return $this->cellcode;
	}

	/**
	 * @param field_type $cellcode
	 */
	public function setCellcode($cellcode) {
		$this->cellcode = $cellcode;
	}

	/**
	 * @return the $saledate
	 */
	public function getSaledate() {
		return $this->saledate;
	}

	/**
	 * @param field_type $saledate
	 */
	public function setSaledate($saledate) {
		$this->saledate = $saledate;
	}

	/**
	 * @return the $partnercode
	 */
	public function getPartnercode() {
		return $this->partnercode;
	}

	/**
	 * @param field_type $partnercode
	 */
	public function setPartnercode($partnercode) {
		$this->partnercode = $partnercode;
	}

	/**
	 * @return the $partner_memnum
	 */
	public function getPartnerMemnum() {
		return $this->partner_memnum;
	}

	/**
	 * @param field_type $partner_memnum
	 */
	public function setPartnerMemnum($partner_memnum) {
		$this->partner_memnum = $partner_memnum;
	}

	/**
	 * @return the $memlevel
	 */
	public function getMemlevel() {
		return $this->memlevel;
	}

	/**
	 * @param field_type $memlevel
	 */
	public function setMemlevel($memlevel) {
		$this->memlevel = $memlevel;
	}

	/**
	 * @return the $auth
	 */
	public function getAuth() {
		return $this->auth;
	}

	/**
	 * @param field_type $auth
	 */
	public function setAuth($auth) {
		$this->auth = $auth;
	}

	/**
	 * @return the $accept
	 */
	public function getAccept() {
		return $this->accept;
	}

	/**
	 * @param field_type $accept
	 */
	public function setAccept($accept) {
		$this->accept = $accept;
	}

	/**
	 * @return the $confcode
	 */
	public function getConfcode() {
		return $this->confcode;
	}

	/**
	 * @param field_type $confcode
	 */
	public function setConfcode($confcode) {
		$this->confcode = $confcode;
	}

	/**
	 * @return the $nowtime
	 */
	public function getNowtime() {
		return $this->nowtime;
	}

	/**
	 * @param field_type $nowtime
	 */
	public function setNowtime($nowtime) {
		$this->nowtime = $nowtime;
	}

	/**
	 * @return the $hpsemail
	 */
	public function getHpsemail() {
		return $this->hpsemail;
	}

	/**
	 * @param field_type $hpsemail
	 */
	public function setHpsemail($hpsemail) {
		$this->hpsemail = $hpsemail;

	}

	/**
	 * @return the $origurl
	 */
	public function getOrigurl() {
		return $this->origurl;
	}

	/**
	 * @param field_type $origurl
	 */
	public function setOrigurl($origurl) {
		$this->origurl = $origurl;
	}

	/**
	 * @return the $greenopt
	 */
	public function getGreenopt() {
		return $this->greenopt;
	}

	/**
	 * @param field_type $greenopt
	 */
	public function setGreenopt($greenopt) {
		$this->greenopt = $greenopt;
	}

	/**
	 * @return the $refid
	 */
	public function getRefid() {
		return $this->refid;
	}

	/**
	 * @param field_type $refid
	 */
	public function setRefid($refid) {
		$this->refid = $refid;
	}

	/**
	 * @return the $sourceip
	 */
	public function getSourceip() {
		return $this->sourceip;
	}

	/**
	 * @param field_type $sourceip
	 */
	public function setSourceip($sourceip) {
		$this->sourceip = $sourceip;
	}

	/**
	 * @return the $busres
	 */
	public function getBusres() {
		return $this->busres;
	}

	/**
	 * @param field_type $busres
	 */
	public function setBusres($busres) {
		$this->busres = $busres;
	}

	/**
	 * @return the $budget
	 */
	public function getBudget() {
		return $this->budget;
	}

	/**
	 * @param field_type $budget
	 */
	public function setBudget($budget) {
		$this->budget = $budget;
	}

	/**
	 * @return the $entby
	 */
	public function getEntby() {
		return $this->entby;
	}

	/**
	 * @param field_type $entby
	 */
	public function setEntby($entby) {
		$this->entby = $entby;
	}

	/**
	 * @return the $appby
	 */
	public function getAppby() {
		return $this->appby;
	}

	/**
	 * @param field_type $appby
	 */
	public function setAppby($appby) {
		$this->appby = $appby;
	}

	/**
	 * @return the $namekey
	 */
	public function getNamekey() {
		return $this->namekey;
	}

	/**
	 * @param field_type $namekey
	 */
	public function setNamekey($namekey) {
		$this->namekey = $namekey;
	}

	/**
	 * @return the $baccount
	 */
	public function getBaccount() {
		return $this->baccount;
	}

	/**
	 * @param field_type $baccount
	 */
	public function setBaccount($baccount) {
		$this->baccount = $baccount;
	}

	/**
	 * @return the $introgroup
	 */
	public function getIntrogroup() {
		return $this->introgroup;
	}

	/**
	 * @param field_type $introgroup
	 */
	public function setIntrogroup($introgroup) {
		$this->introgroup = $introgroup;
	}

	/**
	 * @return the $mkgroup
	 */
	public function getMkgroup() {
		return $this->mkgroup;
	}

	/**
	 * @param field_type $mkgroup
	 */
	public function setMkgroup($mkgroup) {
		$this->mkgroup = $mkgroup;
	}

	/**
	 * @return the $stateid
	 */
	public function getStateid() {
		return $this->stateid;
	}

	/**
	 * @param field_type $stateid
	 */
	public function setStateid($stateid) {
		$this->stateid = $stateid;
	}

	/**
	 * @return the $pfname
	 */
	public function getPfname() {
		return $this->pfname;
	}

	/**
	 * @param field_type $pfname
	 */
	public function setPfname($pfname) {
		$this->pfname = $pfname;
	}

	/**
	 * @return the $plname
	 */
	public function getPlname() {
		return $this->plname;
	}

	/**
	 * @param field_type $plname
	 */
	public function setPlname($plname) {
		$this->plname = $plname;
	}

	/**
	 * @return the $enrollcustid
	 */
	public function getEnrollcustid() {
		return $this->enrollcustid;
	}

	/**
	 * @param field_type $enrollcustid
	 */
	public function setEnrollcustid($enrollcustid) {
		$this->enrollcustid = $enrollcustid;
	}

	/**
	 * @return the $fico
	 */
	public function getFico() {
		return $this->fico;
	}

	/**
	 * @param field_type $fico
	 */
	public function setFico($fico) {
		$this->fico = $fico;
	}

	/**
	 * @return the $paysrc
	 */
	public function getPaysrc() {
		return $this->paysrc;
	}

	/**
	 * @param field_type $paysrc
	 */
	public function setPaysrc($paysrc) {
		$this->paysrc = $paysrc;
	}

	/**
	 * @return the $paymeth
	 */
	public function getPaymeth() {
		return $this->paymeth;
	}

	/**
	 * @param field_type $paymeth
	 */
	public function setPaymeth($paymeth) {
		$this->paymeth = $paymeth;
	}

	/**
	 * @return the $payamt
	 */
	public function getPayamt() {
		return $this->payamt;
	}

	/**
	 * @param field_type $payamt
	 */
	public function setPayamt($payamt) {
		$this->payamt = $payamt;
	}

	/**
	 * @return the $contractterm
	 */
	public function getContractterm() {
		return $this->contractterm;
	}

	/**
	 * @param field_type $contractterm
	 */
	public function setContractterm($contractterm) {
		$this->contractterm = $contractterm;
	}

	/**
	 * @return the $spanishbill
	 */
	public function getSpanishbill() {
		return $this->spanishbill;
	}

	/**
	 * @param field_type $spanishbill
	 */
	public function setSpanishbill($spanishbill) {
		$this->spanishbill = $spanishbill;
	}

	/**
	 * @return the $notificationwaiver
	 */
	public function getNotificationwaiver() {
		return $this->notificationwaiver;
	}

	/**
	 * @param field_type $notificationwaiver
	 */
	public function setNotificationwaiver($notificationwaiver) {
		$this->notificationwaiver = $notificationwaiver;
	}

	/**
	 * @return the $dob
	 */
	public function getDob() {
		return $this->dob;
	}

	/**
	 * @param field_type $dob
	 */
	public function setDob($dob) {
		$this->dob = $dob;
	}

	/**
	 * @return the $mothermaiden
	 */
	public function getMothermaiden() {
		return $this->mothermaiden;
	}

	/**
	 * @param field_type $mothermaiden
	 */
	public function setMothermaiden($mothermaiden) {
		$this->mothermaiden = $mothermaiden;
	}

	/**
	 * @return the $taxid
	 */
	public function getTaxid() {
		return $this->taxid;
	}

	/**
	 * @param field_type $taxid
	 */
	public function setTaxid($taxid) {
		$this->taxid = $taxid;
	}

	/**
	 * @return the $Credit1
	 */
	public function getCredit1() {
		return $this->Credit1;
	}

	/**
	 * @param field_type $Credit1
	 */
	public function setCredit1($Credit1) {
		$this->Credit1 = $Credit1;
	}

	/**
	 * @return the $Credit2
	 */
	public function getCredit2() {
		return $this->Credit2;
	}

	/**
	 * @param field_type $Credit2
	 */
	public function setCredit2($Credit2) {
		$this->Credit2 = $Credit2;
	}

	/**
	 * @return the $kWh
	 */
	public function getKWh() {
		return $this->kWh;
	}

	/**
	 * @param field_type $kWh
	 */
	public function setKWh($kWh) {
		$this->kWh = $kWh;
	}

	/**
	 * @return the $RentOwn
	 */
	public function getRentOwn() {
		return $this->RentOwn;
	}

	/**
	 * @param field_type $RentOwn
	 */
	public function setRentOwn($RentOwn) {
		$this->RentOwn = $RentOwn;
	}

	/**
	 * @return the $ResidenceLength
	 */
	public function getResidenceLength() {
		return $this->ResidenceLength;
	}

	/**
	 * @param field_type $ResidenceLength
	 */
	public function setResidenceLength($ResidenceLength) {
		$this->ResidenceLength = $ResidenceLength;
	}

	/**
	 * @return the $EmployeeCount
	 */
	public function getEmployeeCount() {
		return $this->EmployeeCount;
	}

	/**
	 * @param field_type $EmployeeCount
	 */
	public function setEmployeeCount($EmployeeCount) {
		$this->EmployeeCount = $EmployeeCount;
	}

	/**
	 * @return the $BusinessLength
	 */
	public function getBusinessLength() {
		return $this->BusinessLength;
	}

	/**
	 * @param field_type $BusinessLength
	 */
	public function setBusinessLength($BusinessLength) {
		$this->BusinessLength = $BusinessLength;
	}

	/**
	 * @return the $CurrentSupplier
	 */
	public function getCurrentSupplier() {
		return $this->CurrentSupplier;
	}

	/**
	 * @param field_type $CurrentSupplier
	 */
	public function setCurrentSupplier($CurrentSupplier) {
		$this->CurrentSupplier = $CurrentSupplier;
	}

	/**
	 * @return the $spfname
	 */
	public function getSpfname() {
		return $this->spfname;
	}

	/**
	 * @param field_type $spfname
	 */
	public function setSpfname($spfname) {
		$this->spfname = $spfname;
	}

	/**
	 * @return the $splname
	 */
	public function getSplname() {
		return $this->splname;
	}

	/**
	 * @param field_type $splname
	 */
	public function setSplname($splname) {
		$this->splname = $splname;
	}

	/**
	 * @return the $ProdCode
	 */
	public function getProdCode() {
		return $this->ProdCode;
	}

	/**
	 * @param field_type $ProdCode
	 */
	public function setProdCode($ProdCode) {
		$this->ProdCode = $ProdCode;
	}

	/**
	 * @return the $FixedIntro
	 */
	public function getFixedIntro() {
		return $this->FixedIntro;
	}

	/**
	 * @param field_type $FixedIntro
	 */
	public function setFixedIntro($FixedIntro) {
		$this->FixedIntro = $FixedIntro;
	}

	/**
	 * @return the $noexport
	 */
	public function getNoexport() {
		return $this->noexport;
	}

	/**
	 * @param field_type $noexport
	 */
	public function setNoexport($noexport) {
		$this->noexport = $noexport;
	}

	/**
	 * @return the $years_inbiz
	 */
	public function getYearsInbiz() {
		return $this->years_inbiz;
	}

	/**
	 * @param field_type $years_inbiz
	 */
	public function setYearsInbiz($years_inbiz) {
		$this->years_inbiz = $years_inbiz;
	}

	/**
	 * @return the $years_bizaddr
	 */
	public function getYearsBizaddr() {
		return $this->years_bizaddr;
	}

	/**
	 * @param field_type $years_bizaddr
	 */
	public function setYearsBizaddr($years_bizaddr) {
		$this->years_bizaddr = $years_bizaddr;
	}

	/**
	 * @return the $late_payment6
	 */
	public function getLatePayment6() {
		return $this->late_payment6;
	}

	/**
	 * @param field_type $late_payment6
	 */
	public function setLatePayment6($late_payment6) {
		$this->late_payment6 = $late_payment6;
	}

	/**
	 * @return the $busname_change
	 */
	public function getBusnameChange() {
		return $this->busname_change;
	}

	/**
	 * @param field_type $busname_change
	 */
	public function setBusnameChange($busname_change) {
		$this->busname_change = $busname_change;
	}

	/**
	 * @return the $elec_supp_prevyear
	 */
	public function getElecSuppPrevyear() {
		return $this->elec_supp_prevyear;
	}

	/**
	 * @param field_type $elec_supp_prevyear
	 */
	public function setElecSuppPrevyear($elec_supp_prevyear) {
		$this->elec_supp_prevyear = $elec_supp_prevyear;
	}

	/**
	 * @return the $years_creditbiz
	 */
	public function getYearsCreditbiz() {
		return $this->years_creditbiz;
	}

	/**
	 * @param field_type $years_creditbiz
	 */
	public function setYearsCreditbiz($years_creditbiz) {
		$this->years_creditbiz = $years_creditbiz;
	}

	
	public function getBillingAddress( $orientation = 'horizontal' )
	{
		$output = '';
		if ( $orientation == 'horizontal' )
		{
			$output = $this->getBaddr1();
			$addr2 = $this->getBaddr2();
			if ( isset( $addr2 ))
			{
				$output .= ', ' . $addr2;
			}
			
			$city = $this->getBcity();
			if ( isset( $city ))
			{
				$output .= ', ' . $city;	
			}
			
			$state = $this->getBstate();
			if ( isset( $state ))
			{
				$output .= ', ' . $state;
			}
			
			$zip5 = $this->getBzip5();
			if ( isset( $zip5 ))
			{
				$output .= ' ' . $zip5;
			}
			
			$zip4 = $this->getBzip4();
			if ( isset( $zip4 ))
			{
				$output .= '-' . $zip4;
			}
		}
		
		return $output;
	}


	public function getServiceAddress( $orientation = 'horizontal' )
	{
		$output = '';
		if ( $orientation == 'horizontal' )
		{
			$output = $this->getAddr1();
			$addr2 = $this->getAddr2();
			if ( isset( $addr2 ))
			{
				$output .= ', ' . $addr2;
			}
			
			$city = $this->getCity();
			if ( isset( $city ))
			{
				$output .= ', ' . $city;	
			}
			
			$state = $this->getState();
			if ( isset( $state ))
			{
				$output .= ', ' . $state;
			}
			
			$zip5 = $this->getZip5();
			if ( isset( $zip5 ))
			{
				$output .= ' ' . $zip5;
			}
			
			$zip4 = $this->getZip4();
			if ( isset( $zip4 ))
			{
				$output .= '-' . $zip4;
			}
		}
		
		return $output;
	}


}


<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Partner model ...
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Partner extends EP_Model
{
	protected $id;
	protected $activation_bonus;
	protected $affinity;
	protected $allow_inbound;
	protected $allow_inbound_cellcode;
	protected $apptype;
	protected $bus_taxex;
	protected $campaign;
	protected $checksum;
	protected $cksumtxt;
	protected $default_cellcode;
	protected $default_green;
	protected $defrefcamp;
	protected $description;
	protected $earthshare;
	protected $green_content;
	protected $has_gas_option;
	protected $inbound_cellcode;
	protected $inbound_htld_cellcode;
	protected $lightbulbimage;
	protected $new;
	protected $partnercode;
	protected $partner_dir;
	protected $partner_email;
	protected $partnerlogo;
	protected $partner_name;
	protected $partner_nameprefix;
	protected $partner_new;
	protected $partner_note1;
	protected $partner_offerdetails;
	protected $partner_offerdir;
	protected $partner_offertext;
	protected $partner_shortname;
	protected $partner_url;
	protected $pfname;
	protected $points_hint;
	protected $promocode;
	protected $promocode_bus;
	protected $referral_dealer;
	protected $rewards;
	protected $sequence;
	protected $short_rewards;
	protected $status;
	protected $state;
	protected $stateswitch;
	protected $trackinglink;
	protected $use_pricecode;
	protected $use_referral;
	protected $vendorid;
	protected $description_long;
	protected $phone_number;
	protected $category;
	protected $allow_invalid_checksum;
	protected $gas_promocode;
	protected $gas_promocode_gas;
	protected $checksum_error_msg;
       protected $memnum_maxlen;

	
	protected $variables = array();

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
	 * @return the $activation_bonus
	 */
	public function getActivationBonus() {
		return $this->activation_bonus;
	}

	/**
	 * @param field_type $activation_bonus
	 */
	public function setActivationBonus($activation_bonus) {
		$this->activation_bonus = $activation_bonus;
	}

	/**
	 * @return the $affinity
	 */
	public function getAffinity() {
		return $this->affinity;
	}

	/**
	 * @param field_type $affinity
	 */
	public function setAffinity($affinity) {
		$this->affinity = $affinity;
	}

	/**
	 * @return the $allow_inbound
	 */
	public function getAllowInbound() {
		return $this->allow_inbound;
	}

	/**
	 * @param field_type $allow_inbound
	 */
	public function setAllowInbound($allow_inbound) {
		$this->allow_inbound = $allow_inbound;
	}

	/**
	 * @return the $allow_inbound_cellcode
	 */
	public function getAllowInboundCellcode() {
		return $this->allow_inbound_cellcode;
	}

	/**
	 * @param field_type $allow_inbound_cellcode
	 */
	public function setAllowInboundCellcode($allow_inbound_cellcode) {
		$this->allow_inbound_cellcode = $allow_inbound_cellcode;
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
	 * @return the $bus_taxex
	 */
	public function getBusTaxex() {
		return $this->bus_taxex;
	}

	/**
	 * @param field_type $bus_taxex
	 */
	public function setBusTaxex($bus_taxex) {
		$this->bus_taxex = $bus_taxex;
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
	 * @return the $checksum
	 */
	public function getChecksum() {
		return $this->checksum;
	}

	/**
	 * @param field_type $checksum
	 */
	public function setChecksum($checksum) {
		$this->checksum = $checksum;
	}

	/**
	 * @return the $cksumtxt
	 */
	public function getCksumtxt() {
		return $this->cksumtxt;
	}

	/**
	 * @param field_type $cksumtxt
	 */
	public function setCksumtxt($cksumtxt) {
		$this->cksumtxt = $cksumtxt;
	}

	/**
	 * @return the $default_cellcode
	 */
	public function getDefaultCellcode() {
		return $this->default_cellcode;
	}

	/**
	 * @param field_type $default_cellcode
	 */
	public function setDefaultCellcode($default_cellcode) {
		$this->default_cellcode = $default_cellcode;
	}

	/**
	 * @return the $default_green
	 */
	public function getDefaultGreen() {
		return $this->default_green;
	}

	/**
	 * @param field_type $default_green
	 */
	public function setDefaultGreen($default_green) {
		$this->default_green = $default_green;
	}

	/**
	 * @return the $defrefcamp
	 */
	public function getDefrefcamp() {
		return $this->defrefcamp;
	}

	/**
	 * @param field_type $defrefcamp
	 */
	public function setDefrefcamp($defrefcamp) {
		$this->defrefcamp = $defrefcamp;
	}

	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param field_type $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return the $earthshare
	 */
	public function getEarthshare() {
		return $this->earthshare;
	}

	/**
	 * @param field_type $earthshare
	 */
	public function setEarthshare($earthshare) {
		$this->earthshare = $earthshare;
	}

	/**
	 * @return the $green_content
	 */
	public function getGreenContent() {
		return $this->green_content;
	}

	/**
	 * @param field_type $green_content
	 */
	public function setGreenContent($green_content) {
		$this->green_content = $green_content;
	}

	/**
	 * @return the $has_gas_option
	 */
	public function getHasGasOption() {
		return $this->has_gas_option;
	}

	/**
	 * @param field_type $has_gas_option
	 */
	public function setHasGasOption($has_gas_option) {
		$this->has_gas_option = $has_gas_option;
	}

	/**
	 * @return the $inbound_cellcode
	 */
	public function getInboundCellcode() {
		return $this->inbound_cellcode;
	}

	/**
	 * @param field_type $inbound_cellcode
	 */
	public function setInboundCellcode($inbound_cellcode) {
		$this->inbound_cellcode = $inbound_cellcode;
	}

	/**
	 * @return the $inbound_htld_cellcode
	 */
	public function getInboundHtldCellcode() {
		return $this->inbound_htld_cellcode;
	}

	/**
	 * @param field_type $inbound_htld_cellcode
	 */
	public function setInboundHtldCellcode($inbound_htld_cellcode) {
		$this->inbound_htld_cellcode = $inbound_htld_cellcode;
	}

	/**
	 * @return the $lightbulbimage
	 */
	public function getLightbulbimage() {
		return $this->lightbulbimage;
	}

	/**
	 * @param field_type $lightbulbimage
	 */
	public function setLightbulbimage($lightbulbimage) {
		$this->lightbulbimage = $lightbulbimage;
	}

	/**
	 * @return the $new
	 */
	public function getNew() {
		return $this->new;
	}

	/**
	 * @param field_type $new
	 */
	public function setNew($new) {
		$this->new = $new;
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
	 * @return the $partner_dir
	 */
	public function getPartnerDir() {
		return $this->partner_dir;
	}

	/**
	 * @param field_type $partner_dir
	 */
	public function setPartnerDir($partner_dir) {
		$this->partner_dir = $partner_dir;
	}

	/**
	 * @return the $partner_email
	 */
	public function getPartnerEmail() {
		return $this->partner_email;
	}

	/**
	 * @param field_type $partner_email
	 */
	public function setPartnerEmail($partner_email) {
		$this->partner_email = $partner_email;
	}

	/**
	 * @return the $partnerlogo
	 */
	public function getPartnerLogo() {
		return $this->partnerlogo;
	}

	/**
	 * @param field_type $partnerlogo
	 */
	public function setPartnerLogo($partnerlogo) {
		$this->partnerlogo = $partnerlogo;
	}

	/**
	 * @return the $partner_name
	 */
	public function getPartnerName() {
		return $this->partner_name;
	}

	/**
	 * @param field_type $partner_name
	 */
	public function setPartnerName($partner_name) {
		$this->partner_name = $partner_name;
	}

	/**
	 * @return the $partner_nameprefix
	 */
	public function getPartnerNameprefix() {
		return $this->partner_nameprefix;
	}

	/**
	 * @param field_type $partner_nameprefix
	 */
	public function setPartnerNameprefix($partner_nameprefix) {
		$this->partner_nameprefix = $partner_nameprefix;
	}

	/**
	 * @return the $partner_new
	 */
	public function getPartnerNew() {
		return $this->partner_new;
	}

	/**
	 * @param field_type $partner_new
	 */
	public function setPartnerNew($partner_new) {
		$this->partner_new = $partner_new;
	}

	/**
	 * @return the $partner_note1
	 */
	public function getPartnerNote1() {
		return $this->partner_note1;
	}

	/**
	 * @param field_type $partner_note1
	 */
	public function setPartnerNote1($partner_note1) {
		$this->partner_note1 = $partner_note1;
	}

	/**
	 * @return the $partner_offerdetails
	 */
	public function getPartnerOfferdetails() {
		return $this->partner_offerdetails;
	}

	/**
	 * @param field_type $partner_offerdetails
	 */
	public function setPartnerOfferdetails($partner_offerdetails) {
		$this->partner_offerdetails = $partner_offerdetails;
	}

	/**
	 * @return the $partner_offerdir
	 */
	public function getPartnerOfferdir() {
		return $this->partner_offerdir;
	}

	/**
	 * @param field_type $partner_offerdir
	 */
	public function setPartnerOfferdir($partner_offerdir) {
		$this->partner_offerdir = $partner_offerdir;
	}

	/**
	 * @return the $partner_offertext
	 */
	public function getPartnerOffertext() {
		return $this->partner_offertext;
	}

	/**
	 * @param field_type $partner_offertext
	 */
	public function setPartnerOffertext($partner_offertext) {
		$this->partner_offertext = $partner_offertext;
	}

	/**
	 * @return the $partner_shortname
	 */
	public function getPartnerShortname() {
		return $this->partner_shortname;
	}

	/**
	 * @param field_type $partner_shortname
	 */
	public function setPartnerShortname($partner_shortname) {
		$this->partner_shortname = $partner_shortname;
	}

	/**
	 * @return the $partner_url
	 */
	public function getPartnerUrl() {
		return $this->partner_url;
	}

	/**
	 * @param field_type $partner_url
	 */
	public function setPartnerUrl($partner_url) {
		$this->partner_url = $partner_url;
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
	 * @return the $points_hint
	 */
	public function getPointsHint() {
		return $this->points_hint;
	}

	/**
	 * @param field_type $points_hint
	 */
	public function setPointsHint($points_hint) {
		$this->points_hint = $points_hint;
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
	 * @return the $promocode_bus
	 */
	public function getPromocodeBus() {
		return $this->promocode_bus;
	}

	/**
	 * @param field_type $promocode_bus
	 */
	public function setPromocodeBus($promocode_bus) {
		$this->promocode_bus = $promocode_bus;
	}

	/**
	 * @return the $referral_dealer
	 */
	public function getReferralDealer() {
		return $this->referral_dealer;
	}

	/**
	 * @param field_type $referral_dealer
	 */
	public function setReferralDealer($referral_dealer) {
		$this->referral_dealer = $referral_dealer;
	}

	/**
	 * @return the $rewards
	 */
	public function getRewards() {
		return $this->rewards;
	}

	/**
	 * @param field_type $rewards
	 */
	public function setRewards($rewards) {
		$this->rewards = $rewards;
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
	 * @return the $short_rewards
	 */
	public function getShortRewards() {
		return $this->short_rewards;
	}

	/**
	 * @param field_type $short_rewards
	 */
	public function setShortRewards($short_rewards) {
		$this->short_rewards = $short_rewards;
	}

	/**
	 * @return the $status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param field_type $status
	 */
	public function setStatus($status) {
		$this->status = $status;
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
	 * @return the $stateswitch
	 */
	public function getStateswitch() {
		return $this->stateswitch;
	}

	/**
	 * @param field_type $stateswitch
	 */
	public function setStateswitch($stateswitch) {
		$this->stateswitch = $stateswitch;
	}

	/**
	 * @return the $trackinglink
	 */
	public function getTrackinglink() {
		return $this->trackinglink;
	}

	/**
	 * @param field_type $trackinglink
	 */
	public function setTrackinglink($trackinglink) {
		$this->trackinglink = $trackinglink;
	}

	/**
	 * @return the $use_pricecode
	 */
	public function getUsePricecode() {
		return $this->use_pricecode;
	}

	/**
	 * @param field_type $use_pricecode
	 */
	public function setUsePricecode($use_pricecode) {
		$this->use_pricecode = $use_pricecode;
	}

	/**
	 * @return the $use_referral
	 */
	public function getUseReferral() {
		return $this->use_referral;
	}

	/**
	 * @param field_type $use_referral
	 */
	public function setUseReferral($use_referral) {
		$this->use_referral = $use_referral;
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
	   @return string long description of the partner
	*/
	public function getDescriptionLong()
	{
		return $this->description_long;
	}

	/**
	   @param string $description_long long description of the partner
	*/
	public function setDescriptionLong($description_long)
	{
		$this->description_long = $description_long;
	}
	
	/**
	   @return string phone number of partner
	*/
	public function getPhoneNumber()
	{
		return $this->phone_number;
	}

	/**
	   @param string $phone_number phone number of partner
	*/
	public function setPhoneNumber($phone_number)
	{
		$this->phone_number = $phone_number;
	}

	/**
	   @return int id of category in partner_categories table
	*/
	public function getCategory()
	{
		return $this->category;
	}

	/**
	   @param int $category id of category in partner_categories table
	*/
	public function setCategory($category)
	{
		$this->category = $category;
	}

	public function getAllowInvalidChecksum()
	{
		return $this->allow_invalid_checksum;
	}
	
	public function setAllowInvalidChecksum( $bool )
	{
		$this->allow_invalid_checksum = $bool;
		return $this;	
	}
	
	/**
	   @return gas_promocode, default promocode for gas registrations
	*/
	public function getGasPromocode()
	{
		return $this->gas_promocode;
	}

	/**
	   @param $gas_promocode default promocode for gas registrations
	*/
	public function setGasPromocode($gas_promocode)
	{
		$this->gas_promocode = $gas_promocode;
	}

	/**
	   @return gas_promocode_bus, default promocode for gas business registrations
	*/
	public function getGasPromocodeBus()
	{
		return $this->gas_promocode_bus;
	}
	
	/**
	   @param gas_promocode_bus default promocode for gas business registrations
	*/
	public function setGasPromocodeBus($gas_promocode_bus)
	{
		$this->gas_promocode_bus = $gas_promocode_bus;
	}

	/**
	   @return checksum_error_msg, error message for invalid checksums
	*/
	public function getChecksumErrorMsg()
	{
		return $this->checksum_error_msg;
	}
	
	/**
	   @param checksum_error_msg error message for invalid checksums
	*/
	public function setChecksumErrorMsg($checksum_error_msg)
	{
		$this->checksum_error_msg = $checksum_error_msg;
	}

	/**
	   @return memnum_maxlen, maximum length of partner member number
	*/
	public function getMemnumMaxlen()
	{
		return $this->memnum_maxlen;
	}

	/**
	   @param $memnum_maxlen maximum length of partner member number
	*/
	public function setMemnumMaxlen($memnum_maxlen)
	{
		$this->memnum_maxlen = $memnum_maxlen;
	}

	/**
	   @return partner type ("brand", "cobrand", or "affinity")
	*/
	public function getPartnerType()
	{
              $partner = $this->getPartnercode();
		if($partner   == 'BRD' || $partner  =='BRC' || $partner =='BRP' || $partner =='BRR')
		{
			return 'brand';
		}
		if($this->getAffinity())
		{
			return 'affinity';
		}
		return 'cobrand';
	}

	public function setVariables( $arr )
	{
		$this->variables = $arr;
		return $this;
	}
	
	public function getVariables()
	{
		return $this->variables;
	}
	
	public function getVariable( $var )
	{
		$variables = $this->getVariables();
		if ( in_array( $var, array_keys( $variables )))
		{
			return $variables["$var"];
		}
	
		return false;
	}
	
	public function getVariableValue( $var )
	{
		$variable = $this->getVariable( $var );
		if ( $variable )
		{
			return $variable->variable_value;
		}
		return false;
	}

	public function getValidator()
	{
		// XXX: Some validators require newcentral_functions.php.  If so, it will run inside this function's scope, so we must import the key globals from config.php.
		global $dbhost, $dbname, $dbuser, $dbpass;
		$validator_path = dirname(__FILE__) .  '/../../../oochecksums/validatenumber_' . strtolower($this->partnercode) . '.php';
		if(!file_exists($validator_path)) // Presumably invalid partner code
		{
			return NULL;
		}
		require_once $validator_path;
		$class_name = "Checksums_{$this->partnercode}";
		return new $class_name;
	}
}





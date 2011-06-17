<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';

class EP_Model_State extends EP_Model 
{
	protected $id;
	protected $name;
	protected $abbrev;
	protected $active;
	protected $use_namekey;
	protected $zip_min;
	protected $zip_max;
	protected $next_page;
	protected $app_page;
	protected $sections;
	protected $pdf_sections;
	protected $email_sections_sp;
	protected $pdf_sections_sp;
	protected $has_lookup;
	protected $agreename;
	protected $set_price_var;
	protected $has_gas;
	protected $tax_rate;
	protected $cs_tel;
	protected $enroll_tel;
	protected $has_elec;
       protected $version;
	
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
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param field_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return the $abbrev
	 */
	public function getAbbrev() {
		return $this->abbrev;
	}

	/**
	 * @param field_type $abbrev
	 */
	public function setAbbrev($abbrev) {
		$this->abbrev = $abbrev;
	}

	/**
	 * @return the $active
	 */
	public function getActive() {
		return $this->active;
	}

	/**
	 * @param field_type $active
	 */
	public function setActive($active) {
		$this->active = $active;
	}

	/**
	 * @return the $use_namekey
	 */
	public function getUseNamekey() {
		return $this->use_namekey;
	}

	/**
	 * @param field_type $use_namekey
	 */
	public function setUseNamekey($use_namekey) {
		$this->use_namekey = $use_namekey;
	}

	/**
	 * @return the $zip_min
	 */
	public function getZipMin() {
		return $this->zip_min;
	}

	/**
	 * @param field_type $zip_min
	 */
	public function setZipMin($zip_min) {
		$this->zip_min = $zip_min;
	}

	/**
	 * @return the $zip_max
	 */
	public function getZipMax() {
		return $this->zip_max;
	}

	/**
	 * @param field_type $zip_max
	 */
	public function setZipMax($zip_max) {
		$this->zip_max = $zip_max;
	}

	/**
	 * @return the $next_page
	 */
	public function getNextPage() {
		return $this->next_page;
	}

	/**
	 * @param field_type $next_page
	 */
	public function setNextPage($next_page) {
		$this->next_page = $next_page;
	}

	/**
	 * @return the $app_page
	 */
	public function getAppPage() {
		return $this->app_page;
	}

	/**
	 * @param field_type $app_page
	 */
	public function setAppPage($app_page) {
		$this->app_page = $app_page;
	}

	/**
	 * @return the $sections
	 */
	public function getSections() {
		return $this->sections;
	}

	/**
	 * @param field_type $sections
	 */
	public function setSections($sections) {
		$this->sections = $sections;
	}

	/**
	 * @return the $pdf_sections
	 */
	public function getPdfSections() {
		return $this->pdf_sections;
	}

	/**
	 * @param field_type $pdf_sections
	 */
	public function setPdfSections($pdf_sections) {
		$this->pdf_sections = $pdf_sections;
	}

	/**
	 * @return the $email_sections_sp
	 */
	public function getEmailSectionsSp() {
		return $this->email_sections_sp;
	}

	/**
	 * @param field_type $email_sections_sp
	 */
	public function setEmailSections_Sp($email_sections_sp) {
		$this->email_sections_sp = $email_sections_sp;
	}

	/**
	 * @return the $pdf_sections_sp
	 */
	public function getPdfSectionsSp() {
		return $this->pdf_sections_sp;
	}

	/**
	 * @param field_type $pdf_sections_sp
	 */
	public function setPdfSectionsSp($pdf_sections_sp) {
		$this->pdf_sections_sp = $pdf_sections_sp;
	}

	/**
	 * @return the $has_lookup
	 */
	public function getHasLookup() {
		return $this->has_lookup;
	}

	/**
	 * @param field_type $has_lookup
	 */
	public function setHasLookup($has_lookup) {
		$this->has_lookup = $has_lookup;
	}

	/**
	 * @return the $agreename
	 */
	public function getAgreename() {
		return $this->agreename;
	}

	/**
	 * @param field_type $agreename
	 */
	public function setAgreename($agreename) {
		$this->agreename = $agreename;
	}

	/**
	 * @return the $set_price_var
	 */
	public function getSetPriceVar() {
		return $this->set_price_var;
	}

	/**
	 * @param field_type $set_price_var
	 */
	public function setSetPriceVar($set_price_var) {
		$this->set_price_var = $set_price_var;
	}

	/**
	 * @return the $has_gas
	 */
	public function getHasGas() {
		return $this->has_gas;
	}

	/**
	 * @param field_type $has_gas
	 */
	public function setHasGas($has_gas) {
		$this->has_gas = $has_gas;
	}

	/**
	 * @return the $tax_rate
	 */
	public function getTaxRate() {
		return $this->tax_rate;
	}

	/**
	 * @param field_type $tax_rate
	 */
	public function setTaxRate($tax_rate) {
		$this->tax_rate = $tax_rate;
	}

	/**
	 * @return the $cs_tel
	 */
	public function getCsTel() {
		return $this->cs_tel;
	}

	/**
	 * @param field_type $cs_tel
	 */
	public function setCsTel($cs_tel) {
		$this->cs_tel = $cs_tel;
	}

	/**
	 * @return the $enroll_tel
	 */
	public function getEnrollTel() {
		return $this->enroll_tel;
	}

	/**
	 * @param field_type $enroll_tel
	 */
	public function setEnrollTel($enroll_tel) {
		$this->enroll_tel = $enroll_tel;
	}

	/**
	   @return has_elec, boolean indicating whether state supports electricity
	*/
	public function getHasElec()
	{
		return $this->has_elec;
	}

	/**
	   @param $has_elec boolean indicating whether state supports electricity
	*/
	public function setHasElec($has_elec)
	{
		$this->has_elec = $has_elec;
	}

       /**
	   @return an integer value which tells the system to redirect to the specific application
 	*/
	public function getVersion()
	{
		return $this->version;
	}

	/**
	   @return an integer value which tells the system to redirect to the specific application
	*/
	public function setVersion($version)
	{
		$this->version = $version;
	}


	public static function getSelectBox( $name, $id, $selected_id = null)
	{
		require_once 'EP/Model/StateMapper.php';
		
		$stateMapper = new EP_Model_StateMapper();
		$states = $stateMapper->fetchAll();
		
		$output = '';
		$output .= '<select name="' . $name . '" id="' . $id . '">';
		
		$output .= '<option value="">Select State:</option>';
		
		for ( $i = 0; $i < count( $states ); $i++ )
		{
			$selected = '';
			$state = $states[$i];
			if ( $state->getId() == $selected_id )
			{
				$selected = 'selected="selected"';
			}
			$output .= '<option value="' . $state->getId() . '" ' . $selected . '>' . $state->getName() . '</option>'; 
		}
		
		$output .= '</select>';
		return $output;
	}
}




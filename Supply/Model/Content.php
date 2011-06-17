<?php

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';
require_once 'StateMapper.php';
require_once 'PartnerMapper.php';
require_once 'EP/Util/Database.php';
require_once 'UtilityMapper.php';
require_once 'Offer.php';
error_reporting(E_ALL);

// TODO: Should there instead be a commodity_combo column in the actual content management tables (pointing to commodity_variables.id)?
class EP_Model_Content extends EP_Model
{
	// ID keys correspond to commodity_variables.id
	private $tos_names = array(1 => 'gas_tos_sa', 2 => 'tos_sa', 3 => 'both_gas_elec_tos_sa');

	// Statement handle for commodity_variables columns
	private $sth_commodity_variables_cols;

	// Statement handle for commodity_variables row
	private $sth_commodity_variables_row;

	private $state_mapper;
	private $partner_mapper;

	function __construct()
	{
		global $dbname;
		$col_sql = "SELECT column_name FROM information_schema.columns WHERE table_schema = ? AND table_name = 'commodity_variables' AND data_type = 'varchar'";
		$this->sth_commodity_variables_cols = EP_Util_Database::pdo_connect()->prepare($col_sql);
		$this->sth_commodity_variables_cols->setFetchMode(PDO::FETCH_OBJ);
		$this->sth_commodity_variables_cols->bindValue(1, $dbname);
		$val_sql = 'SELECT * FROM commodity_variables WHERE id = ?';
		$this->sth_commodity_variables_row = EP_Util_Database::pdo_connect()->prepare($val_sql);
		$this->state_mapper = new EP_Model_StateMapper();
		$this->partner_mapper = new EP_Model_PartnerMapper();
	}

	/**
	   @param $state_id state id
	   @param $partner_id partnercode.id, or NULL for non-state specific content
	   @param $page_name page name key
	   @param $language

	   @return the unsubstituted text
	*/
	private function getUnsubstitutedDeliveryContent($state_id, $partner_id, $page_name, $language)
	{
		$db = EP_Util_Database::pdo_connect();
		$field_name = 'page_content' . ($language == 'en' ? '' : $language);
		if($partner_id)
		{
			$sql = 'SELECT page_content from partner_dm_pages WHERE state=? AND page_name=? AND partner_id=? ORDER BY id DESC';
			$sth = $db->prepare($sql);
			$sth->setFetchMode(PDO::FETCH_OBJ);
			$sth->execute(array($state_id, $page_name, $partner_id));
			$result = $sth->fetch();
			if($result)
			{
				return stripslashes($result->$field_name); // stripslashes for backwards compatibility with db data, not security.
			}
		}
		if(empty($result))
		{
			$sql = 'SELECT page_content from default_dm_content WHERE state=? AND page_name=?';
			$sth = $db->prepare($sql);
			$sth->setFetchMode(PDO::FETCH_OBJ);
			$sth->execute(array($state_id, $page_name));
			$result = $sth->fetch();
			if($result)
			{
				return stripslashes($result->$field_name);
			}
		}
		return NULL;
	}

	/**
	   @param $state_id states.id
	   @param $partner_id partnercode.id, or NULL to use default
	   @param $page page name

	   @return unsubstituted page content (partner_pages or default_content
	*/
	private function getUnsubstitutedContent($state_id, $partner_id, $page)
	{
		$db = EP_Util_Database::pdo_connect();
		if($partner_id)
		{
			$sql = <<< EOF
				SELECT page_content 
				FROM partner_pages 
				WHERE state=?
				AND page_name=?
				AND partner_id=? 
				ORDER BY id DESC
				LIMIT 1
EOF;
			$sth = $db->prepare($sql);
			$sth->setFetchMode(PDO::FETCH_OBJ);
			$sth->execute(array($state_id, $page, $partner_id));
			$result = $sth->fetch();
		}
		if(empty($result))
		{
			$sql = <<< EOF
				SELECT page_content
				FROM default_content
				WHERE state=?
				AND page_name=?
				LIMIT 1
EOF;
			$sth = $db->prepare($sql);
			$sth->setFetchMode(PDO::FETCH_OBJ);
			$sth->execute(array($state_id, $page));
			$result = $sth->fetch();
		}
		if($result)
		{
			// stripslashes for backwards compatibility
			return stripslashes($result->page_content);
		}
	}
	/**
	   @param $state_id states.id
	   @param $partner_id partnercode.id
	   @param $page_name name of page
	   @param $commodity_combo_id commodity_variables.id
	   @param $media media code, such as sc (screen), pd (PDF), or em (email)
	   @param $promocode promocode
	   @param $language 'en' or 'sp'
	   @param $greenopt green option

	   @return substituted delivery method content
	*/
	public function getSubstitutedDeliveryContent($state_id, $partner_id, $page_name, $commodity_combo_id, $media, $promocode, $language, $greenopt)
	{
		$unsub_content = $this->getUnsubstitutedDeliveryContent($state_id, $partner_id, $page_name, $language);
		return $this->substituteDeliveryContent($state_id, $partner_id, $commodity_combo_id, $media, $promocode, $greenopt, $unsub_content);
	}

	/**
	   @param $state_id states.id
	   @param $partner_id partnercode.id
	   @param $commodity_combo_id commodity_variables.id
	   @param $media media code, such as sc (screen), pd (PDF), or em (email)
	   @param $promocode promocode
	   @param $greenopt green option
	   @param $content previous content

	   @return content with delivery method substitutions
	*/
	private function substituteDeliveryContent($state_id, $partner_id, $commodity_combo_id, $media, $promocode, $greenopt, $content)
	{
		$content = $this->substitutePartnerVars($state_id, $partner_id, $commodity_combo_id, $content, $media);
		$content = $this->substituteStarburstVars($partner_id, $promocode, FALSE, $content);

		$partner = $this->partner_mapper->fetch($partner_id, array('affinity'));
		$content = $this->substituteConditionalVariables($greenopt, $partner->getAffinity(), $content);
		$content = $this->substituteConfig($media, $content);
		$content = $this->substituteCommodityVariables($commodity_combo_id, $content);
		return $content;
	}

	/**
	   @param $state_id states.id
	   @param $partner_id partnercode.id
	   @param $page_name page name
	   @param $elec_promocode optional promocode.  If NULL, will use partnercode.promocode
	   @param $gas_promocode optional promocode.  If NULL, will use partnercode.gas_promocode

	   @return substituted content
	*/
	public function getSubstitutedContent($state_id, $partner_id, $page_name, $elec_promocode=NULL, $gas_promocode=NULL)
	{
		$partner = $this->partner_mapper->fetch($partner_id, array('has_gas_option', 'promocode', 'gas_promocode'));
		if(!isset($elec_promocode))
		{
			$elec_promocode = $partner->getPromocode();
		}

		if(!isset($gas_promocode))
		{
			$gas_promocode = $partner->getGasPromocode();
		}

		$state = $this->state_mapper->fetch($state_id, array('has_elec', 'has_gas'));
		
		$show_elec = $state->getHasElec();
		$show_gas = $state->getHasGas() && $partner->getHasGasOption();
		
		if($show_elec && $show_gas)
		{
			$commodity_combo_id = 3;
		}
		else if($show_elec)
		{
			$commodity_combo_id = 2;
		}
		else if($show_gas)
		{
			$commodity_combo_id = 1;
		}
		
		$content = $this->getUnsubstitutedContent($state_id, $partner_id, $page_name);
		$content = $this->substituteOfferContent($state_id, $partner_id, $show_elec, $show_gas, $elec_promocode, $gas_promocode, $content);
		
		// substitutePartnerVars is capable of substituting more than we need, but that doesn't cause problems.
		$content = $this->substitutePartnerVars($state_id, $partner_id, $commodity_combo_id, $content, 'sc');
		$content = $this->substituteStarburstVars($partner_id, $elec_promocode, FALSE, $content);
		$content = $this->substituteStarburstVars($partner_id, $gas_promocode, TRUE, $content);
		$content = $this->substituteCommodityVariables($commodity_combo_id, $content);
		return $content;
	}

	/**
	   Gets all substituted delivery method content sections, concatenated, for a particular state, partner, commodity_combo_id, and registration

	   Substitutes registration fields
	   
	   @param $state_id states.id
	   @param $partner_id partnercode.id
	   @param $commodity_combo_id commodity_variables.id
	   @param $reg registration object
	   @param $elec_promocode electric promocode
	   @param $gas_promocode gas promocode
	   @param $media media code, such as sc, pd
	   @param $language language, en or sp
	   
	   @return all applicable sections, concatenated
	*/
	public function getCombinedDeliveryContent($state_id, $partner_id, $commodity_combo_id, $reg, $elec_promocode, $gas_promocode, $media, $language)
	{
		$state = $this->state_mapper->fetch($state_id, array('email_sections_sp', 'sections'));
		$partner = $this->partner_mapper->fetch($partner_id, array('affinity'));
		if($partner->getAffinity()) // XXX: sp section set is for affinity;
		{
			$section_string = $state->getEmailSectionsSp();
		}
		else
		{
			$section_string = $state->getSections();
		}
		$sections = explode(",", $section_string);
		$content = '';
		foreach($sections as $section)
		{
			$section_content = $this->getUnsubstitutedDeliveryContent($state_id, $partner_id, $section, $language);
			if(strpos($section, 'welcome') === 0)
			{
				// Suppress green in welcome section, because it must be per-account
				$section_content = $this->substituteConditionalVariables('000', FALSE, $section_content);
			}
			$content .= $section_content;
		}
		// Only need to do substitutions once.
		$content = $this->substituteDeliveryContent($state_id, $partner_id, $commodity_combo_id, $media, $reg->getPromocode(), $reg->getGreenopt(), $content);

		$content = str_replace('{nlapp_first_name}',$reg->getFirstName(),$content);
              $content = str_replace('{nlapp_last_name}',$reg->getLastName(),$content);
              $content = str_replace('{nlapp_confcode}',$reg->getConfcode(),$content);
              if($reg->getTaxex() !='')
                 $content = str_replace('{app_taxex}',$reg->getTaxex(),$content);
              else
                 $content = str_replace('{app_taxex}','',$content);
              if($partner->getAffinity() == 0)
               $content = str_replace('{nlapp_partner_memnum}',$reg-> getPartnerMemnum(),$content);
              else
               $content = str_replace('{nlapp_partner_memnum}','',$content);
                 
              $content = str_replace('{app_partner_memnum}','',$content);
             
              if($reg->getBusname() != '')
               $content = str_replace('{app_busname}','<strong>Business Name:</strong> '.$reg->getBusname().'<br/>',$content);
              else
               $content = str_replace('{app_busname}','',$content);

              $content = str_replace('{app_addr1}','',$content);
              $content = str_replace('{app_addr2}','',$content);
              $content = str_replace('{app_city}','',$content);
              $content = str_replace('{app_state}','',$content);
              $content = str_replace('{app_zip5}','',$content);
              $content = str_replace('{app_zip4}','',$content);

        
              $content = str_replace('{app_baddr1}','',$content);
              $content = str_replace('{app_baddr2}','',$content);
              $content = str_replace('{app_bcity}','',$content);
              $content = str_replace('{app_bstate}','',$content);
              $content = str_replace('{app_bzip5}','',$content);
              $content = str_replace('{app_bzip4}','',$content);

              $content = str_replace('{PROMO}', $elec_promocode, $content);
              $content = str_replace('{gas_PROMO}', $gas_promocode, $content);

              if($state_id == 5)
                  $content = $this->substituteRate($state_id, $partner->getPartnercode(),$content);

		return $content;
	}

	// TODO: May need to modify to choose page_content or page_contentsp depending on a language parameter
	/**
	   @param $state_id state id
	   @param $partcode partner code (partnercode.partnercode)
	   @param commodity_combo_id commodity combination id, corresponding to commodity_variables id
	   @param $media media code, such as sc (screen) or pd (PDF)
	   @param $elec_promocode electric promocode
	   @param $gas_promocode gas promocode
	   @param $greenopt whether to include the green option section

	   @return the Terms of Service
	*/
	public function getTermsOfService($state_id, $partcode, $commodity_combo_id, $media, $elec_promocode, $gas_promocode, $greenopt)
	{
		if(!isset($this->tos_names[$commodity_combo_id]))
		{
			throw new Exception('Invalid commodity combination ID');
		}
		$tos_name = $this->tos_names[$commodity_combo_id];
		$partner = $this->partner_mapper->fetchByStateAndPartnercode($state_id, $partcode, array('id'));
		if(empty($partner))
		{
			throw new Exception('Partner not found');
		}
		$partner_id = $partner->getId();
		$greenopt = sprintf("%03d", $greenopt);
		$content = $this->getSubstitutedDeliveryContent($state_id, $partner_id, $tos_name, $commodity_combo_id, $media, $elec_promocode, 'en', $greenopt);
		$rewards_content = $this->getSubstitutedDeliveryContent($state_id, $partner_id, 'rewards2', $commodity_combo_id, $media, $elec_promocode, 'en', $greenopt);
		$rewards_content = str_replace('{PROMO}', $elec_promocode, $rewards_content);
		$rewards_content = str_replace('{gas_PROMO}', $gas_promocode, $rewards_content);
		$content .= $rewards_content;

		if($greenopt != '000')
		{
			$green_content = '<div id="greentxt2">';
			$green_content .= $this->getSubstitutedDeliveryContent($state_id, $partner_id, 'grendisc', $commodity_combo_id, $media, $elec_promocode, 'en', $greenopt);
			$green_content .= '</div>';
			$content .= $green_content;
		}

              if($state_id == 5)
                  $content = $this->substituteRate($state_id, $partcode,$content);
              return $content;
 	}

   
       public function substituteRate($state_id, $partcode,$contentTemplate)
	{
          $utility_mapper = new EP_Model_UtilityMapper();
          $model_offer = new EP_Model_Offer();
          $elec_nj_utils = $utility_mapper->fetchByStateID($state_id, array('*'), array( 'utility' => 'ASC' ), 1);
          $str="";
          $str.="<table  border='1' cellspacing='0' cellpading='0' style='width:300px;'><tr>"; 
          $str.="<th style='width:100px;text-align:center;font-weight:bold;font-size:small;vertical-align:bottom;'>Utility</th>";
          $str.="<th style='width:100px;text-align:center;font-weight:bold;font-size:small;'>Residential<br/> rate per<br/> kWh</th>";
          $str.="<th style='width:100px;text-align:center;font-weight:bold;font-size:small;'>Business<br/> rate per<br/> kWh</th>";
          $str.="</tr>";
          foreach ($elec_nj_utils as $util )
	   {
            $elec_util = $util->getAbbrev();
            $str.="<tr>";
            $str.="<th style='text-align:center;font-weight:normal;font-size:small;'>$elec_util</th>";
            //resident -account type 0
            $code = $util->getCode();
            $first_res_price = $model_offer->fetchFirstMonthPrice($state_id, $partcode,0,$code,0);
            $str.="<th style='text-align:center;font-size:small;font-weight:normal;'>$$first_res_price</th>";
            //business -account type 1
            $first_bus_price = $model_offer->fetchFirstMonthPrice($state_id, $partcode,1,$code,0);
            $str.="<th style='text-align:center;font-size:small;font-weight:normal;'>$$first_bus_price</th>";
            $str.="</tr>";
          }
          
          $str.="</table>";
          $contentTemplate= str_replace('{nlapp_FixedIntro}',$str,$contentTemplate);
   	   return $contentTemplate;
	}

	/**
	   @param $state_id states.id
	   @param $partner_dir partnercode.partner_dir
	   @param $elec_promocode, optional electric promocode, or NULL
	   @param $gas_promocode, optional gas promocode, or NULL

	   @return substituted content, or NULL on error
	*/
	public function getLandingPage($state_id, $partner_dir, $elec_promocode=NULL, $gas_promocode=NULL)
	{
		$partner = $this->partner_mapper->fetchByStateAndPartnerDir($state_id, $partner_dir);
		if(empty($partner))
		{
			return NULL;
		}

		$page = 'landing';
		if($partner->getUseReferral())
		{
			$page .= '_ref';
		}
		if($partner->getAffinity())
		{
			$page .= '_aff';
		}
		$content = $this->getSubstitutedContent($state_id, $partner->getId(), $page, $elec_promocode, $gas_promocode);
		return $content;
	}

    /**
        Get the referral landing page content.
        Substitute variables in content.
        
        @param $state_abbrev = 2 letter state abbreviation lowercase.
        @param $partner_dir = corresponding to table partnercode.partner_dir.
        @param $promocode = promocode cooresponding to table promocode.
        @param $refid = [optional] corresponding to table refsettings.id.
        @param $commodity_combo_id = commodity combination id, corresponding to table commodity_variables.id.
        @returns array(
            partnercode => partnercode record,
            promocode => promocode record,
            content => Page content after variable substitution,
            logo_path => Resolved relative URL to associated partner or referral logo )
    */
    public function getReferralLandingPage($state_abbrev, $partner_dir, $promocode, $refid, $commodity_combo_id)
    {
        // Get state id.
        $sth_state = EP_Util_Database::pdo_connect()->prepare(
            'select id ' .
            'from states ' .
            'where abbrev = :state_abbrev ' .
            'limit 1');
        $sth_state->bindValue(':state_abbrev', $state_abbrev);
        $sth_state->execute();
        $state_id = $sth_state->fetchColumn();
        
        // Get partner info.
        $sth_partner = EP_Util_Database::pdo_connect()->prepare(
            'select * ' .
            'from partnercode ' .
            'where partner_dir = :partner_dir ' .
            'limit 1');
        $sth_partner->bindValue(':partner_dir', $partner_dir);
        $sth_partner->execute();
        $partner = $sth_partner->fetch();
        
        // Get applicable promocode.
        if (empty($promocode))
        {
            $promocode = $this->getDefaultPartnerPromocode($partner['id']);
        }

        $sth_promocode = EP_Util_Database::pdo_connect()->prepare(
            'select * ' .
            'from promocode ' .
            'where code = :promocode ' .
            'limit 1');
        $sth_promocode->bindValue(':promocode', $promocode);
        $sth_promocode->execute();
        $promocode_row = $sth_promocode->fetch();
        
        // Get page content template.
        $sth_page_content = EP_Util_Database::pdo_connect()->prepare(
            'select rpp.page_content ' .
            'from ref_partner_pages as rpp ' .
            'where rpp.state = :state_id and ' .
            'rpp.partner_id = :partner_id and ' .
            'rpp.page_name = \'reflanding\' ' .
            'limit 1');
        $sth_page_content->bindValue(':state_id', $state_id);
        $sth_page_content->bindValue(':partner_id', $partner['id']);
        $sth_page_content->execute();
        $template = $sth_page_content->fetchColumn();

        // Get referral info, if present.
        $sth_ref = EP_Util_Database::pdo_connect()->prepare(
            'select logo_path ' .
            'from refsettings ' .
            'where id = :refid ' .
            'limit 1');
        $sth_ref->bindValue(':refid', $refid);
        $sth_ref->execute();
        $ref_logo = $sth_ref->fetchColumn();
        
        // Substitute all vars into $template.
        $content = $this->substitutePartnerVars($state_id, $partner['id'], $commodity_combo_id, $template, 'sc');
        $content = $this->substituteStarburstVars($partner['id'], $promocode, FALSE, $content);
        //$content = $this->substituteContent($state_id, $commodity_combo_id, $template);
        

        // Resolve logo path, if one is defined in table refsettings.
        $logo_path = !empty($ref_logo) ?
            'images/' . $ref_logo :
            'partnerlogos/' . $partner['partnerlogo'];
        
        return array(
            'partnercode' => $partner,
            'promocode' => $promocode_row,
            'content' => $content,
            'logo_path' => $logo_path);
    }
    
    private function getDefaultPartnerPromocode($partner_id)
    {
        // Get default promocode if one not provided.
        $sth_promocode = EP_Util_Database::pdo_connect()->prepare(
            'select promocode ' .
            'from partnercode ' .
            'where partnercode.id = :partner_id ' .
            'limit 1');
        $sth_promocode->bindValue(':partner_id', $partner_id);
        $sth_promocode->execute();
        $promocode = $sth_promocode->fetchColumn();
        
        return $promocode;
    }
    
    // Statement handles used by substitutePartnerVars().
    private $sth_get_var_type;
    private $sth_get_partner_var;
    private $sth_get_partner_commodity_var;
    private $sth_get_partner_dm_var;

    public function substitutePartnerVars($state_id, $partner_id, $commodity_combo_id, $template, $media)
    {
	if(!in_array($media, array('em', 'pd', 'sc')))
	{
		throw new Exception("Invalid media code");
	}
        // Prepare some SQL for variable substitution.
        $this->sth_get_var_type = EP_Util_Database::pdo_connect()->prepare(
            'select variable_type_id ' .
            'from variable_def ' .
            'where variable_name = :variable_name ' .
            'limit 1');
        
        $this->sth_get_partner_var = EP_Util_Database::pdo_connect()->prepare(
            'select variable_value ' .
            'from partner_variables ' .
            'where variable_name = :variable_name and ' .
            'partner_id = :partner_id ' .
            'limit 1');
        
        $this->sth_get_partner_commodity_var = EP_Util_Database::pdo_connect()->prepare(
            'select variable_value ' .
            'from partner_commodity_variables ' .
            'where variable_name = :variable_name and ' .
            'partner_id = :partner_id and ' .
            'commodity_variable_id = :commodity_variable_id ' .
            'limit 1');
        
	// media is validated above
	$partner_dm_sql = <<< EOF
		SELECT var_val_$media 
		FROM partner_dm_variables
		WHERE variable_name = :variable_name AND
		state_id = :state_id AND
		partner_id = :partner_id
EOF;
	$this->sth_get_partner_dm_var = EP_Util_Database::pdo_connect()->prepare($partner_dm_sql);
	
        $this->sth_get_partner_var->bindValue(':partner_id', $partner_id);
        $this->sth_get_partner_commodity_var->bindValue(':partner_id', $partner_id);
        $this->sth_get_partner_commodity_var->bindValue(':commodity_variable_id', $commodity_combo_id);
	$this->sth_get_partner_dm_var->bindValue(':state_id', $state_id);
	$this->sth_get_partner_dm_var->bindValue(':partner_id', $partner_id);

        // Substitute variables via regex.
        // Look for tokens like: {variable_name}
        // Call substitutePartnerVarCallback() on each match, replace with returned value.
        $content = preg_replace_callback(
            '/\\{(\w+)\\}/m',
            array(&$this, 'substitutePartnerVarCallback'),
            $template);
        
        return $content;
    }
    
    /**
       @param $partner_id partnercode.id
       @param $promocode promocode
       @param $is_gas true for gas, false otherwise
       @param $template template

       @return content after substitution
    */
    public function substituteStarburstVars($partner_id, $promocode, $is_gas, $template)
    {
        global $base_url;
     
	$prefix = $is_gas ? 'gas_' : '';
   
        // Check if starburst variables apply.
        $sth_starburst = EP_Util_Database::pdo_connect()->prepare(
            'select * ' .
            'from starburst_variables ' .
            'where partner_id = :partner_id and ' .
            'promo_trigger = :promocode ' .
            'limit 1');
        $sth_starburst->bindValue(':partner_id', $partner_id);
        $sth_starburst->bindValue(':promocode', $promocode);
        $sth_starburst->execute();
        $starburst_vars = $sth_starburst->fetch();
        
        // Is there a starburst variable for this partner/promocode?
        // If not, do no substitution.
        if (!isset($starburst_vars)) return $template;
        
        // Get partnercode.
        $sth_partnercode = EP_Util_Database::pdo_connect()->prepare(
            'select partnercode ' .
            'from partnercode ' .
            'where id = :partner_id ' .
            'limit 1');
        $sth_partnercode->bindValue(':partner_id', $partner_id);
        $sth_partnercode->execute();
        $partner_code = $sth_partnercode->fetchColumn();
        
        // Substitute variables.
        $content = $template;
        $starimage = !empty($starburst_vars['imagepath']) ?
			'<img src="' . $base_url . 'images/' . $starburst_vars['imagepath'] . '" />' :
			'<img src="' . $base_url . 'images/' . $partner_code . $starburst_vars['image_miles'] . '.jpg" />';
 
        $content = str_replace("{{$prefix}starbimage}", $starimage, $content);
	$content = str_replace("{{$prefix}rewtxt}", $starburst_vars['text1_miles'], $content);
	$content = str_replace("{{$prefix}dolltext}", $starburst_vars['text2_miles'], $content);
	$content = str_replace("{{$prefix}bullet_snippet}", $starburst_vars['bullet_snippet'], $content);
	$content = str_replace("{{$prefix}bullet_snippet2}", $starburst_vars['bullet_snippet2'], $content);
	$content = str_replace("{{$prefix}disc_sentence}", $starburst_vars['disc_sentence'], $content);
	$content = str_replace("{{$prefix}resaffin}", $starburst_vars['resaffin'], $content);
	$content = str_replace("{{$prefix}bizaffin}", $starburst_vars['bizaffin'], $content);
	$content = str_replace("{{$prefix}aff_res_ongoing}", $starburst_vars['aff_res_ongoing'], $content);
	$content = str_replace("{{$prefix}aff_biz_ongoing}", $starburst_vars['aff_biz_ongoing'], $content);
	$content = str_replace("{{$prefix}footnote}", $starburst_vars['footnote'], $content);
	$content = str_replace("{{$prefix}ib_desc}", $starburst_vars['ib_desc'], $content);
	$content = str_replace("{{$prefix}bonus_mon}", $starburst_vars['bonus_mon'], $content);
	$content = str_replace("{{$prefix}award_mons}", $starburst_vars['award_mons'], $content);
        
        return $content;
    }

    // This is used from at least one place outside this class, confirmation.inc
    /**
       @param $commodity_combo_id commodity_variables.id
       @param $template the text that needs to be substituted

       @return the substituted text
    */
    public function substituteCommodityVariables($commodity_combo_id, $template)
    {
	    $content = $template;
	    $this->sth_commodity_variables_cols->execute();
	    $this->sth_commodity_variables_row->execute(array($commodity_combo_id));
	    $commodity_var_row = $this->sth_commodity_variables_row->fetch();
	    while($commodity_col = $this->sth_commodity_variables_cols->fetch())
	    {
		    $commod_col_name = $commodity_col->column_name;
		    $commod_placeholder = '{Commodity_' . ucfirst($commod_col_name) . '}';
		    // Replacement is case-insensitive (Commodity_Type/Commodity_type/etc.)
		    $content = str_ireplace($commod_placeholder, $commodity_var_row[$commod_col_name], $content);
	    }
	    $this->sth_commodity_variables_cols->closeCursor();
	    $this->sth_commodity_variables_row->closeCursor();
	    return $content;
    }

    /**
       @param $green green option (greenopt)
       @param $affinity whether partner is affinity
       @param $content original content

       @return substituted content
    */
    private function substituteConditionalVariables($green, $affinity, $content)
    {
	    $green = sprintf("%03d", $green);
	    if($green == '000')
	    { // remove tags and content for 1 and 2  remove the tags for the 0
		    $content = preg_replace('/{IFGREEN1}(.*?){\/IFGREEN1}/is','',$content);
		    $content = preg_replace('/{IFGREEN2}(.*?){\/IFGREEN2}/is','',$content);
		    $content = str_replace('{IFGREEN0}','',$content);
		    $content = str_replace('{/IFGREEN0}','',$content);
		    $content = preg_replace('/{STATICGREEN}(.*?){\/STATICGREEN}/is','',$content);
	    }
	    else if($green == '001')
	    {
		    $content = preg_replace('/{IFGREEN0}(.*?){\/IFGREEN0}/is','',$content);
		    $content = preg_replace('/{IFGREEN2}(.*?){\/IFGREEN2}/is','',$content);
		    $content = str_replace('{IFGREEN1}','',$content);
		    $content = str_replace('{/IFGREEN1}','',$content);
		    $content = preg_replace('/{STATICGREEN}(.*?){\/STATICGREEN}/is','',$content);
	    }
	    else if($green == '002')
	    {
		    $content = preg_replace('/{IFGREEN0}(.*?){\/IFGREEN0}/is','',$content);
		    $content = preg_replace('/{IFGREEN1}(.*?){\/IFGREEN1}/is','',$content);
		    $content = str_replace('{IFGREEN2}','',$content);
		    $content = str_replace('{/IFGREEN2}','',$content);
		    $content = preg_replace('/{STATICGREEN}(.*?){\/STATICGREEN}/is','',$content);
	    }
	    else
	    {
		    $content = preg_replace('/{IFGREEN0}(.*?){\/IFGREEN0}/is','',$content);
		    $content = preg_replace('/{IFGREEN1}(.*?){\/IFGREEN1}/is','',$content);
		    $content = preg_replace('/{IFGREEN2}(.*?){\/IFGREEN2}/is','',$content);
		    $content = str_replace('{STATICGREEN}','',$content);
		    $content = str_replace('{/STATICGREEN}','',$content);
	    }

	    if(!$affinity)
	    {
		    $content = preg_replace('/{IFAFFINITY}(.*?){\/IFAFFINITY}/is','',$content);
	    }
	    else
	    {
		    $content = str_replace('{IFAFFINITY}','',$content);
		    $content = str_replace('{/IFAFFINITY}','',$content);
	    }
	    
	    return $content;
    }

    /**
       @param $state_id states.id
       @param $partner_id partnercode.id
       @param $show_elec true to include electric section, false otherwise
       @param $show_gas true to include gas section, false otherwise
       @param $elec_promocode electric promocode
       @param $gas_promocode gas promocode
       @param $template previous text, containing {electric_offer} and/or {gas_offer}
       @param $template previous text, containing {affinity_electric_offer} and/or {affinity_gas_offer}

       @return text with electric offer content, gas offer content, or both, with starburst substitutions for each section.
    */
    private function substituteOfferContent($state_id, $partner_id, $show_elec, $show_gas, $elec_promocode, $gas_promocode, $template)
    {
         // Substitute variables.
        $content = $template;

	if($show_elec)
	{
		$elec_offer = $this->getUnsubstitutedContent($state_id, $partner_id, 'electric_offer');
		$elec_offer = $this->substituteStarburstVars($partner_id, $elec_promocode, FALSE, $elec_offer);
		$content = str_replace('{electric_offer}', $elec_offer, $content);
      
              $affinity_elec_offer = $this->getUnsubstitutedContent($state_id, $partner_id, 'affinity_electric_offer');
              $affinity_elec_offer = $this->substituteStarburstVars($partner_id, $elec_promocode, FALSE, $affinity_elec_offer);
              $content = str_replace('{affinity_electric_offer}',$affinity_elec_offer, $content);
	}
	else
	{
		$content = str_replace('{electric_offer}', '', $content);
              $content = str_replace('{affinity_electric_offer}', '', $content);
	}
	
	if($show_gas)
	{
		$gas_offer = $this->getUnsubstitutedContent($state_id, $partner_id, 'gas_offer');
		$gas_offer = $this->substituteStarburstVars($partner_id, $gas_promocode, TRUE, $gas_offer);
		$content = str_replace('{gas_offer}', $gas_offer, $content);

              $affinity_gas_offer = $this->getUnsubstitutedContent($state_id, $partner_id, 'affinity_gas_offer');
              $affinity_gas_offer = $this->substituteStarburstVars($partner_id, $gas_promocode, TRUE, $affinity_gas_offer);
              $content = str_replace('{affinity_gas_offer}',$affinity_gas_offer, $content);
	}
	else
	{
		$content = str_replace('{gas_offer}', '', $content);		
              $content = str_replace('{affinity_gas_offer}', '', $content);
	}
	
        return $content;
    }

    /**
       @param media code, such as pd
       @param $content previous content
       
       @return content with config variables, such as {imagelocation}, substituted

    */
    private function substituteConfig($media, $content)
    {
	    global $base_url;
	    // Prince accesses the images locally
	    if($media == 'pd')
	    {
		    $replacement = $base_dir . '/';
	    }
	    else
	    {
		    $replacement = $base_url;
	    }
	    return str_replace('{imagelocation}', $replacement, $content);
    }
    
    /**
        Substitute variable via preg_replace_callback.
        Used by getReferralLandingPage().
        
        @param $matches - array provided from preg_replace_callback().  In form of:
                          array( <matched variable w/ brackets>, <variable name> )
        @returns variable value
    */
    private function substitutePartnerVarCallback($matches)
    {
        $variable_name = $matches[1];
        
        // Lookup variable type.
        $this->sth_get_var_type->bindValue(':variable_name', $variable_name);
        $this->sth_get_var_type->execute();
        $var_type = $this->sth_get_var_type->fetchColumn();
        
        if (empty($var_type))
        {
            // Variable name not found.
            return "{Error: Variable [$variable_name] is not defined!}";
        }
        
        // Substitute from the appropriate variable table.
        switch ($var_type)
        {
        case 1: // Partner variable.
            $this->sth_get_partner_var->bindValue(':variable_name', $variable_name);
            $this->sth_get_partner_var->execute();
            return $this->sth_get_partner_var->fetchColumn();

        case 2: // Partner commodity variable.
            $this->sth_get_partner_commodity_var->bindValue(':variable_name', $variable_name);
            $this->sth_get_partner_commodity_var->execute();
            return $this->sth_get_partner_commodity_var->fetchColumn();

        case 3: // Partner dm variable.
            $this->sth_get_partner_dm_var->bindValue(':variable_name', $variable_name);
	    $this->sth_get_partner_dm_var->execute();
	    return $this->sth_get_partner_dm_var->fetchColumn();
            
        default: // Non-partner variable
		return '{' . $variable_name . '}'; // Not all variables are partner variables, so we return defined non-partner variables untouched
        }
    }
}

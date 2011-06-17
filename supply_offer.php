<?php

//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Offer Page//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////


require_once 'includes/main.php';

//
// make sure previous tabs are completed
//
require_once 'includes/check_state.php';
require_once 'includes/check_util.php';

require_once 'EP/Model/StateMapper.php';
require_once 'EP/Model/PartnerMapper.php';
require_once 'EP/Model/PartnerCategoryMapper.php';
require_once 'EP/HTML/Form/Widget/RadioBool.php';
require_once 'EP/Model/Offer.php';

$register = '';
if ( isset( $_SESSION['register']))
{
	$register = $_SESSION['register'];
}



/*
 * If it's a POST then we save to the existing register record
 *
 */

if ( !empty( $_POST ) )
{
// echo '<pre>' . print_r( $_POST, true ) . '</pre>';
// die();

	$sflag = false;

	if (isset($_POST['hidden_partner_code']) && isset($_POST['hidden_campaign_code']) && isset($_POST['hidden_promo_code']))
	{
		$stateId = 0;
		if ( isset( $_SESSION['tabs']['state']['state']))
		{
			$stateId = $_SESSION['tabs']['state']['state'];
		}

		$partnerMapper = new EP_Model_PartnerMapper();
		$partnerMapper->setUsePublicVars( true );
		$result = $partnerMapper->fetchByStatePromoCampaignPartner(
			$_POST['hidden_partner_code'],
			$_POST['hidden_campaign_code'],
			$_POST['hidden_promo_code'],
			$stateId
		);

		// For the very rare bump up logic, we have both the old "promo_code"
		// and the bumped up "hidden_promo_code" but "hidden_promo_code"
		// will not return anything in the query above
		// so we need to try again with the original "promo_code."
		if ( ! $result && isset( $_POST['promo_code']))
		{
			$result = $partnerMapper->fetchByStatePromoCampaignPartner(
				$_POST['hidden_partner_code'],
				$_POST['hidden_campaign_code'],
				$_POST['promo_code'],
				$stateId
			);
		}

		if ( $result )
		{
			$sflag = true;
			$_SESSION['tabs']['offer']['promo_desc'] = $result[0]->promodesc;
			$_SESSION['tabs']['offer']['partner_id'] = $result[0]->id;

			$partnerMapper = new EP_Model_PartnerMapper();
			$partnerResult = $partnerMapper->fetch( $result[0]->id );
			if ( $partnerResult )
			{
				$_SESSION['partner'] = $partnerResult;
			}
		}

	}

	// set the vendor id ( we may overrride it for referrals )
	$operator = $_SESSION['operator'];
	$register->setVendorid( $operator->getMid() );

	// if refid is included, we need to validate it
	if ( isset( $_POST['hidden_refid']) && $_POST['hidden_refid'] != '' )
	{
		require_once 'EP/Model/ReferralPartnerMapper.php';

		$mapper = new EP_Model_ReferralPartnerMapper();
		$mapper->setUsePublicVars( true );
		$refResult = $mapper->fetchByStateCampaignRefid( trim( $_POST['hidden_partner_code']), trim( $_POST['hidden_campaign_code'] ), trim( $_POST['hidden_refid'] ), $stateId,  $_SESSION['tabs']['utility']['resbus']  );

		// then save refid to register model and to session
		if ( $refResult )
		{
			$register->setRefid( trim( $_POST['hidden_refid'] ) );
			$_SESSION['tabs']['offer']['refid'] = trim( $_POST['hidden_refid'] );
			// for referrals, override the vendorid (mid) with "rfrl"
			$register->setVendorid( 'RFRL' );
		}
		else
		{
			$register->setRefid( null );
			$_SESSION['tabs']['offer']['refid'] = null;

		}
	}

	// values are good, let's save everything
	if( $sflag === true )
	{
		// add to register object for saving
		$register->setPartnercode( trim( strtoupper($_POST['hidden_partner_code']) ) );
		$register->setCampaign( trim( $_POST['hidden_campaign_code'] ) );
		$register->setPromocode( trim( $_POST['hidden_promo_code'] ) );

		// save the input box values to session
		$_SESSION['tabs']['offer']['partner'] = $register->getPartnercode();
		$_SESSION['tabs']['offer']['campaign'] = $register->getCampaign();
		$_SESSION['tabs']['offer']['promo'] = $register->getPromocode();

		// set the reward type in the session, we use this in other tabs
		if ( !empty( $_POST['hidden_reward_type']) && in_array(trim($_POST['hidden_reward_type']), array('cashback', 'miles_or_points') ))
		{
			$_SESSION['tabs']['offer']['reward_type'] = trim($_POST['hidden_reward_type']);
		}


		// for backwards compatibility
		$_SESSION['tabs']['offer']['Partnercode'] = $register->getPartnercode();
		$_SESSION['tabs']['offer']['Campaign'] = $register->getCampaign();
		$_SESSION['tabs']['offer']['Promocode'] = $register->getPromocode();

		// TODO save the selectbox values to session

		// get partner type and affinity
		$partnerMapper = new EP_Model_PartnerMapper();
		$partnerMapper->setUsePublicVars(true);
		$partnerObj = $partnerMapper->fetchByStateAndPartnercode($_SESSION['tabs']['state']['state'], $register->getPartnercode() );

		$partnerCode = strtoupper( $register->getPartnercode() );

		// set the partner type
		if($partnerCode == 'BRD' || $partnerCode == 'BRC'|| $partnerCode == 'BRP'|| $partnerCode == 'BRR' )
		{
			$_SESSION['tabs']['offer']['partner_type'] = "brand";

		}
		else
		{
			$_SESSION['tabs']['offer']['partner_type'] = "cobrand";
		}

		$default_green=null;
		if(isset($partnerCode))
		{
					$green_partnercode = getRecord('partnercode','partnercode',$partnerCode);
					$default_green  = $green_partnercode->default_green;

					if($default_green==1)
					{
						$_SESSION['tabs']['account']['greenoption_choice']='002';
					}

					if($default_green==0)
					{
						$_SESSION['tabs']['account']['greenoption_choice']='000';
					}

					if($_SESSION['tabs']['offer']['Partnercode']=='SPG')
					{
						$_SESSION['tabs']['account']['greenoption_choice']='003';
					}
		}

		// set affinity
		if( $partnerObj->affinity == 1 )
		{
			$_SESSION['tabs']['offer']['affinity'] = 1;
			// we rewrite the partner_type var, we really need to get this as a separate column
			// in the database because this is pretty weird
			$_SESSION['tabs']['offer']['partner_type'] = 'affinity';
		}
		else
		{
			$_SESSION['tabs']['offer']['affinity'] = 0;
		}

		// get the offer code from our model
		$offerObj = new EP_Model_Offer();
		$default_offercode = $offerObj->fetchOfferCode(
			$_SESSION['tabs']['state']['state'],
			$_SESSION['tabs']['offer']['partner_type'],
			$_SESSION['tabs']['utility']['resbus'],
			$_SESSION['utility']['code'],
			$_SESSION['tabs']['offer']['partner'],
			$_SESSION['tabs']['offer']['campaign']
		);

		if ( $default_offercode )
		{
			$_SESSION['tabs']['offer']['default_offercode'] = $default_offercode;
			$register->setProdCode( $default_offercode );
		}

		$register->setPartnerMemnum( null );
		if ( !empty( $_POST['dividend_miles_number']))
		{
			$register->setPartnerMemnum( trim($_POST['dividend_miles_number']) );
			$_SESSION['tabs']['offer']['PartnerMemnum'] = $register->getPartnerMemnum();
		}

		// first name on rewards program
		$register->setPfname( null );
		if ( !empty( $_POST['pfname']))
		{
			$register->setPfname( trim($_POST['pfname']) );
			$_SESSION['tabs']['offer']['Pfname'] = $register->getPfname();
		}

		// lastname on rewwards program
		$register->setPlname( null );
		if ( !empty( $_POST['plname']))
		{
			$register->setPlname( trim($_POST['plname']) );
			$_SESSION['tabs']['offer']['Plname'] = $register->getPlname();
		}

		// update the session register
		$_SESSION['register'] = $register;

		// first yes/no
		$_SESSION['tabs']['offer']['receive_offer'] = $_POST['receive_offer'];
		// do you have offercode yes/no
		$_SESSION['tabs']['offer']['offer_code'] = $_POST['offer_code'];
		// please confirm this is the offer yes/no
		$_SESSION['tabs']['offer']['confirm_offer'] = $_POST['confirm_offer'];

		if ( isset( $_POST['sb_partner_cats'] ) && !empty( $_POST['sb_partner_cats']))
		{
			$_SESSION['tabs']['offer']['sb_partner_cats'] = $_POST['sb_partner_cats'];
		}



		// save the register record
		$mapper = new EP_Model_RegisterMapper();
		$result = $mapper->save( $register );

		if ( !$result )
		{
			die( 'error saving' );
		}

		if ( isset($_POST['next_url']) && $_POST['next_url'] != '' )
		{
			$header = "Location: ${base_url}myinbound/" . $_POST['next_url'];
			header( $header );
		}
		else
		{
			$_SESSION['messages'] = 'The information has been saved';
		}
	}
	else
	{
		$_SESSION['messages'] = 'Utility and account type are required.';
	}

}  // end if $_POST

$partnerCode = '';
if ( isset( $_SESSION['tabs']['offer']['partner']))
{
	$partnerCode = $_SESSION['tabs']['offer']['partner'];
}
$campaignCode = '';
if ( isset( $_SESSION['tabs']['offer']['campaign']))
{
	$campaignCode = $_SESSION['tabs']['offer']['campaign'];
}
$promoCode = '';
if ( isset( $_SESSION['tabs']['offer']['promo']))
{
	$promoCode = $_SESSION['tabs']['offer']['promo'];
}

$receiveOffer = null;
if ( isset( $_SESSION['tabs']['offer']['receive_offer']))
{
	$receiveOffer = $_SESSION['tabs']['offer']['receive_offer'];
}

$offerCode = null;
if ( isset( $_SESSION['tabs']['offer']['offer_code']))
{
	$offerCode = $_SESSION['tabs']['offer']['offer_code'];
}

$offerDetails = null;
if ( isset( $_SESSION['tabs']['offer']['offer_details']))
{
	$offerDetails = $_SESSION['tabs']['offer']['offer_details'];
}



$hear_sql = "select * from heard_about_us_items";
if($hear_sql)
{
	$hear_res = mysql_query($hear_sql,$link);
}

$offer_hear = null;
if ( isset( $_SESSION['tabs']['offer']['offer_hear'] ) )
{
	$offer_hear = $_SESSION['tabs']['offer']['offer_hear'];
}

$stid = null;
$stateId = null;
if($_SESSION['tabs']['state']['state']!='')
{
	$strec = getRecord('states','id',$_SESSION['tabs']['state']['state']);
	$stabbrev = $strec->abbrev;
	$stid = $strec->id;
	$stateId = $strec->id;
}

$accountType = 1;
if ( isset( $_SESSION['tabs']['utility']['resbus']))
{
	$accountType = $_SESSION['tabs']['utility']['resbus'];
}

// get the list of categories that are valid for this state
// we do not show categories with no partners
$pcMapper = new EP_Model_PartnerCategoryMapper();
$partner_categories = $pcMapper->fetchAllByState( $stateId );

	// start the page output
    require_once 'includes/header.php';


?>

<script type="text/javascript" src="/myinbound/scripts/select_offer.js"></script>
<script type="text/javascript">

var partner = 0;

// globals
var stateId = <?=$stateId;?>;
var accountType = <?=$accountType;?>;
var reward_desc = '';
var isValidAccountNumber = false;
var isPartnerAffinity = false;

$(document).ready(function() {

		$('#receive_offer_yes').bind('click', toggleSectionDisplay );
		$('#receive_offer_yes').bind('click', function() {
			$('#section_program_details').hide();
			hideOfferSelectBoxes();
			$('#reward_desc').hide();
			$('#section_confirm_offer').hide();
		});

		$('#receive_offer_no').bind('click', toggleSectionDisplay );
		$('#receive_offer_no').bind('click', function() {
			$('#section_offer_code_yes').hide();
			$('#membership_select').hide();
			$('#reward_desc').hide();
			$('#section_member_select_yes').hide();
			$('#section_confirm_offer').hide();
			// $('#section_program_details').show();
		});

		$('#offer_hear').bind( 'change', function() {
			if ( $('#offer_hear').val() == 8 )
			{
				$('#offer_hear_referral').show();
				$('#referral_data').show();
				$('#section_program_details').hide();
				$('#section_program_details_yes').hide();
			}
			else
			{
				$('#offer_hear_referral').hide();
				$('#referral_data').hide();
				// set text to ''
				$('#section_program_details').show();
			}
		});

		$('#sb_referral').bind( 'blur', function() {
			fetchPromoByReferral( $('#sb_referral').val(), accountType );
		});

		$('#check_sb_referral').bind( 'click', function() {
			fetchPromoByReferral( $('#sb_referral').val(), accountType );
		});

		$('#program_details_yes').bind('click', toggleSectionDisplay );
		$('#program_details_yes').bind('click', function() {

		});

		$('#program_details_no').bind('click', toggleSectionDisplay );
	  	$('#program_details_no').bind('click', function() {
			$('#membership_select').hide();
		});

	  	$('#offer_code_yes').bind('click', toggleSectionDisplay );
		$('#offer_code_yes').bind('click', function() {
			hideOfferSelectBoxes();
		});

		$('#offer_code_no').bind('click', toggleSectionDisplay );
		$('#offer_code_no').bind('click', function() {

		});

		$('#offer_details_yes').bind('click', toggleSectionDisplay );
		$('#offer_details_yes').bind('click', function() {
			$('#section_program_details').hide();
			showOfferSelectBoxes();
		});

		$('#offer_details_no').bind('click', toggleSectionDisplay );
		$('#offer_details_no').bind('click', function(){
			$('#section_program_details').show();
			hideOfferSelectBoxes();
		});

		$('#partner_code').bind('blur', function(){
			fetchPartnerInfo( fetchPartnerInfoSuccess, fetchPartnerInfoError );
		});

		$('#promo_code').bind('blur', validateOffer );
		$('#btn_check_offer').bind('click', validateOffer );

		$('#referral_code').bind('blur', validateReferral );
		$('#btn_check_referral').bind('click', validateReferral );

		$('#confirm_offer_yes').bind('click', toggleSectionDisplay );
		$('#confirm_offer_no').bind('click', toggleSectionDisplay );

		$('#confirm_offer_no_no').bind('click', function() {
			$('#eflag').val('dispo');
			$('#eflagid').val( 3 );
			logDispo( '#frm_inbound' );
		});

		// show the offersearch ( again )
		$('#confirm_offer_no_yes').bind('click', function() {
			$('#eflag').val( '' );
			$('#eflagid').val( '' );

			// choose no for receive offer
			$('#receive_offer_yes').attr('checked', false );
			$('#receive_offer_no').attr('checked', true );

			// reset radios for find partners
			$('#find_partners_yes').attr('checked', false );
			$('#find_partners_no').attr('checked', false );

			// hide text box question
			$('#section_find_partners_yes').hide();

			// hide how did you hear
			$('#section_receive_offer_no').hide();
			$('#section_receive_offer_yes').hide();
			// $('#section_program_details').hide();

			// reset select boxes
			$("#sb_partner_cats option:selected").attr('selected', false );
			// reset member number selection
			$("#membership_selection option:selected").attr('selected', false );

			// reset hidden form elements
			$('#hidden_partner_code').val('');
			$('#hidden_campaign_code').val('');
			$('#hidden_promo_code').val('');
			$('#hidden_refid').val('');

			// reset confirm radios
			$('#confirm_offer_yes').attr('checked', false );
			$('#confirm_offer_no').attr('checked', false );

			// reset "is there another offer" radio
			$('#section_confirm_offer_no').hide();
			$('#confirm_offer_no_yes').attr('checked', false );
			$('#confirm_offer_no_no').attr('checked', false );

			// hide offersearch select boxes
			$('#section_program_details_yes').hide();

			$('#section_offer_code_yes').hide();
			$('#membership_select').hide();
			$('#reward_desc').hide();
			$('#section_member_select_yes').hide();
			$('#section_confirm_offer').hide();
			$('#section_program_details').show();

		});
// hidden_allow_invalid_checksum
		$('#membership_selection').bind('change', function() {

			var invalidChecksumDiv = '#section_member_select_no_' + $('#hidden_partner_code').val().toLowerCase();

			if ( this.value == 'yes' )
			{
				// reset dispo if set
				$('#eflag').val( '' );
				$('#eflagid').val( '' );

				// hide the "no" and "not a member"
				$('#section_member_select_no').hide();
				$('#section_member_select_not_member').hide();

				// if it exists, remove the invalid checksum div
				$( invalidChecksumDiv ).hide();

				// show next sections and get variables
				$('#section_member_select_yes').show();
				$('#section_confirm_offer').show();
				fetchPartnerVariables( $('#hidden_partner_code').val(), $('#hidden_campaign_code').val(), $('#hidden_promo_code').val(), stateId, accountType, fetchPartnerVariablesSuccess, fetchPartnerVariablesError );
			}
			else if ( this.value == 'no' )
			{
				// reset dispo if set
				$('#eflag').val( '' );
				$('#eflagid').val( '' );

				// hide the "yes" and "not a member"
				$('#section_member_select_yes').hide();
				$('#section_member_select_not_member').hide();


				// if we don't allow bad checksums, then we show another div
				if ( $('#hidden_allow_invalid_checksum').val() == 0 )
				{
					$( invalidChecksumDiv ).show();
					$('#section_reward_desc').hide();
					$('#section_confirm_offer').hide();
				}
				else
				{
					$('#section_member_select_no').show();
					$('#section_confirm_offer').show();
					fetchPartnerVariables( $('#hidden_partner_code').val(), $('#hidden_campaign_code').val(), $('#hidden_promo_code').val(), stateId, accountType, fetchPartnerVariablesSuccess, fetchPartnerVariablesError );
				}
			}
			else
			{
				$('#section_member_select_not_member').show();

				// if it exists, remove the invalid checksum div
				$( invalidChecksumDiv ).hide();

				$('#section_member_select_yes').hide();
				$('#section_member_select_no').hide();
				$('#section_confirm_offer').hide();
				$('#section_reward_desc').hide();
			}
		});

		$('#default_offer_yes').bind('click', toggleSectionDisplay );
		$('#default_offer_yes').bind('click', function() {
			fetchDefaultOffer( stateId, accountType );
			$('#section_find_partners_no').show();
			// hide the instructions
			$('.hide_for_default_offer').hide();
		});

		$('#default_offer_no').bind('click', toggleSectionDisplay );
		$('#default_offer_no_yes').bind('click', toggleSectionDisplay );
		$('#default_offer_no_no').bind('click', toggleSectionDisplay );

		$('#default_offer_no_no').bind('click', function() {
			$('#eflag').val('dispo');
			$('#eflagid').val( 3 );
			logDispo( '#frm_inbound');
		});

		$('#find_partners_yes').bind('click', function() {
			$('#section_find_partners_no').hide();
			$('#membership_select').hide();
			$('.hide_for_default_offer').show();
			$('#section_find_partners_yes').show();
			showOfferSelectBoxes( {"showCats": true } );

			// need to hide the select boxes until we need them
			$('#offersearch_partners').hide();
				$('#sb_partner').remove();
			$('#offersearch_campaigns').hide();
				$('#sb_campaign').remove();
			$('#offersearch_promo').hide();
				$('#sb_promo').remove();
			$('#reward_desc').text('');
			$('#section_confirm_offer').hide();
		});

		$('#find_partners_no').bind('click', function() {
			// duplicated calls in  $('#sb_partner_cats').bind('change', function() {
			fetchDefaultOffer( stateId, accountType );
			$('#section_find_partners_no').show();
			// hide the instructions
			$('.hide_for_default_offer').hide();
			$('#section_find_partners_yes').hide();
		});

		$('#sb_partner_cats').bind('change', function() {

			var invalidChecksumDiv = '#section_member_select_no_' + $('#hidden_partner_code').val().toLowerCase();

			if ( $('#sb_partner_cats').val() == '999' )
			{
				// duplicated calls in  $('#find_partners_no').bind('click', function() {
				fetchDefaultOffer( stateId, accountType, true, true );
				$('#section_find_partners_no').show();
				// hide the instructions
				$('.hide_for_default_offer').hide();
			}
			else
			{
				// it is better to just hide the whole section
				// instead of truncating the select boxes
				$('#offersearch_partners').hide();
				$('#offersearch_campaigns').hide();
				$('#offersearch_promo').hide();
				// this is the member number part
				$('#membership_select').hide();
				$('#section_reward_desc').hide();
				$('#section_confirm_offer').hide();

				// upromise
				$( invalidChecksumDiv ).hide();

				fetchPartnerSelectBox( {"cat": this.value } )
			}
		});

		$('#dividend_miles_number').bind('blur', function() {
			// remove the table from checksum validatenumber
			$('#validate_dividend_miles_number').text('');
			validateAccountNumber( this.value, $('#hidden_partner_code').val(), $('#hidden_promo_code').val(), validateAccountNumberSuccess, validateAccountNumberError );
		});

		$('#offerbutnopartner_yes').bind('click', toggleSectionDisplay );
		$('#offerbutnopartner_no').bind('click', toggleSectionDisplay );

		$('#section_offer_details_yes_body_open').bind('click', function() {
			$('#section_offer_details_yes_body').show();
		});
		$('#section_offer_details_yes_body_close').bind('click', function() {
			$('#section_offer_details_yes_body').hide();
		});

		$('#offersearch_partners_body_open').bind('click', function() {
			$('#offersearch_partners_body').show();
		});
		$('#offersearch_partners_body_close').bind('click', function() {
			$('#offersearch_partners_body').hide();
		});

		$('#need_member_yes').bind('click', toggleSectionDisplay );
		$('#need_member_no').bind('click', toggleSectionDisplay );
		$('#need_member_no').bind('click', function() {
			$('#eflag').val( 'dispo' );
			$('#eflagid').val( 10 );
			logDispo( '#frm_inbound' );
		});


		$('#jfb_need_member_yes').bind('click', toggleSectionDisplay );
		$('#jfb_need_member_no').bind('click', toggleSectionDisplay );
		$('#jfb_need_member_no').bind('click', function() {
			$('#eflag').val( 'dispo' );
			$('#eflagid').val( 15 );   // needs to be changed to jfb dispo
			logDispo( '#frm_inbound' );
		});

		// submit form
		$('#btn_continue').bind('click', validateForm );

});  // end $(document).ready(function() {

</script>

</head>
<?php

/*
   onload="global_loadsetting('<?=$_SESSION['tabs']['state']['state'];?>','<?=$_SESSION['tabs']['offer']['Partnercode'];?>','<?=$_SESSION['tabs']['offer']['Campaign'];?>,<?=$_SESSION['tabs']['offer']['Promocode'];?>','<?=$_SESSION['tabs']['offer']['ref1'];?>','<?=$_SESSION['tabs']['offer']['ref2'];?>','<?=$_SESSION['tabs']['offer']['ref3'];?>','<?=$_SESSION['tabs']['offer']['referral'];?>')"
*/
?>

<body>
<div class="yui3-g" id="container" ><!--yui3-g-->
<?php
	$page = basename( __FILE__ );
	require_once 'includes/nav.php';
?>

<div class="yui3-u" id="main"><!--yui3-u-->

<?php
if ( isset( $_SESSION['messages']))
{
        $output = '';
        $output = '<div class="ib_messages">';
        $output .= print_r( $_SESSION['messages'], true );
        unset( $_SESSION['messages']);
        $output .= '</div>';
        print $output;
}
?>
<form id="frm_inbound" name="frm_inbound" method="post" action="select_offer.php" autocomplete="off">
<div class="whiteblock">
<div>
	<p>Did you receive an offer from Energy Plus?
<?php
	$widget = new EP_HTML_Form_Widget_RadioBool( 'receive_offer', $receiveOffer );
	echo $widget->getOutput();
?>
	</p>
</div>
	<div id="section_receive_offer_yes" style="display: none;" >
		<p>May I please have the offer code that is on the offer you received?
		It should begin with 3 letters?
<?php
	$widget = new EP_HTML_Form_Widget_RadioBool( 'offer_code', $offerCode );
	echo $widget->getOutput();
?>
		</p>

	    <div id="section_offer_code_yes" style="display: none;" class="padding_bottom" >
	    	<table border="0" cellspacing="0" cellpadding="0" >
	    		<tr>
	    			<td style="vertical-align: top;"><label for="partner_code" style="padding-right: 1em;" >Offer Code:</label></td>
	    			<td><input type="text" id="partner_code" name="partner_code" value="<?=$partnerCode;?>" size="3" maxlength="3">
    		-<input type="text" id="campaign_code" name="campaign_code" value="<?=$campaignCode;?>" size="4" maxlength="4">
    		<span id="promo_code_hyphen">-</span><input type="text" id="promo_code" name="promo_code" value="<?=$promoCode;?>" size="3" maxlength="3" >
    		<br ><span style="color: #aaa; font-style: italic">Ex. ABC-1234-123</span>
    		<br ><span class="success" id="offer_success"></span> <span class="error" id="offer_error"></span>

    				</td>
    				<td style="vertical-align: top;">
    					<input type="button" id="btn_check_offer" value="Check offer" style="margin-left: 1em; " >
    				</td>
    			</tr>
    			<tr id="section_referral_code" style="display: none; ">
    				<td style="vertical-align: top;"><label for="referral_code" style="padding-right: 1em">Referral Code:</label></td>
    				<td><input type="text" id="referral_code" name="referral_code" value="" size="5" maxlength="5">
    				<br /><span class="success referral_success" ></span><span class="error referral_error" ></span>
    				</td>
    				<td style="vertical-align: top;">
    					<input type="button" id="btn_check_referral" value="Check referral" style="margin-left: 1em; ">
    				</td>
    			</tr>
			</table>

    	</div>

     	<div id="section_offer_code_no" style="display: none;">
			<p>Can you tell me what the offer was that you received?
<?php
	$widget = new EP_HTML_Form_Widget_RadioBool( 'offer_details', $offerDetails );
	echo $widget->getOutput();
?>
			</p>

			<!--<div id="section_offer_details_yes" style="display:none; width: 400px; float: right; background-color: #eee; padding: 4px;">-->
			<div>
				<div>
					<!--<div style="float: right; padding-top: 8px;">
						<span id="section_offer_details_yes_body_open"><a class="tabtext" href="javascript: void(0);">+Open</a> / </span>
						<span id="section_offer_details_yes_body_close"><a class="tabtext" href="javascript: void(0);">-Close</a></span>
					</div>
					<div class="rep_note">NOTES TO TSR:</div>-->
				</div>
				<div id="section_offer_details_yes_body" style="display:none;">
					<p class="rep_note">IF YES AND CALLER PROVIDES A PARTNER BUT NO OFFER:
					USE SEARCH KEY TO PULL UP OFFERS FOR THAT PARTNER AND SELECT DEFAULT</p>

					<p class="rep_note">IF CALLER SAYS THEY WERE REFERRED, SELECT THAT PARTNER WITH "REFERRAL"
					PRIOR TO THE PARTNER NAME</p>

					<p class="rep_note">IF YES AND CALLER PROVIDES A PARTNER AND AN OFFER: USE SEARCH KEY TO PULL UP THAT PARTNER AND
					TRY TO LOCATE OFFER</p>

					<blockquote><p class="rep_note">IF CAN'T LOCATE OFFER:  <span class="formdata">Unfortunately that offer is no longer valid.
					However, I can give you our most recent offer.</span> SELECT DEFAULT FOR THAT PARTNER</p></blockquote>
					<blockquote><p class="rep_note">IF LOCATE OFFER: SELECT OFFER</p></blockquote>

					<p><span class="rep_note">IF YES AND CALLER PROVIDES OFFER BUT NO PARTNER:</span> Energy Plus has a number of organizations and companies that we partner
					with to provide additional value to our customers.  Was your offer associated with any specific organization or company?<br >
<?php
	$offerButNoPartner = null;
	$widget = new EP_HTML_Form_Widget_RadioBool( 'offerbutnopartner', $offerButNoPartner );
	echo $widget->getOutput();
?>
					</p>
					<div id="section_offerbutnopartner_yes" style="display: none; ">
						<p><span class="rep_note">USE SEARCH KEY TO PULL UP THAT PARTNER AND TRY TO LOCATE OFFER.
						IF STILL CAN'T FIND OFFER:</span> Unfortunately that offer is no longer valid. However, I can give you our
						most recent offer.<span class="rep_note">TODO (SELECT DEFAULT FOR THAT PARTNER) GO TO II3</span>
						</p>
					</div>
					<div id="section_offerbutnopartner_no" style="display: none; ">
						<p>Unfortunately without that information I will not be able to enroll you in a program with that
						specific offer.  However, I can give you our most recent offer.<span class="rep_note">TODO (SELECT DEFAULT) GO TO II3</span></p>
					</div>
				</div>

			</div><!--  end section offer details yes -->
		</div> <!--  end section offer code no -->


	</div><!--  end section_receive_offer_yes -->

    <div id="section_receive_offer_no" style="display: none;" class="padding_bottom" >
		<p>How did you hear about us?
			<select name="offer_hear" id="offer_hear" >
				<option value="">Select Referral Type</option>
<?php

    while($hear_rec = mysql_fetch_object($hear_res))
    {
    	if( $offer_hear == $hear_rec->id )
    	{
        	echo '<option value="'.$hear_rec->id.'" Selected>'.$hear_rec->name.'</option>'."\n";
        }
        else
        {
        	echo '<option value="'.$hear_rec->id.'">'.$hear_rec->name.'</option>'."\n";
        }
    }

?>
			</select>
		</p>

		<div id="offer_hear_referral" style="display:none">
			<label for="referral_code" class="label_table">Referral Code:</label>
    		<input type="text" id="sb_referral" name="sb_referral" value="" size="5" maxlength="5">
    		<input type="button" id="check_sb_referral" name="check_sb_referral" value="Check Referral" >
<div style="margin-left: 8.25em; ">
<span class="success" id="sb_referral_success"></span> <span class="error" id="sb_referral_error"></span>
</div>
    		<br /><span class="success referral_success"></span><span class="error referral_error" ></span>
		</div>
		<div id="referral_data" style="display: none; clear: left;">
			<div>
				<label class="label_table">Partner:</label> <input type="text" name="referral_partner_code" id="referral_partner_code" size="5" disabled >
			</div>
			<div style="clear: left;">
				<label class="label_table">Campaign:</label> <input type="text" name="referral_campaign_code" id="referral_campaign_code" size="5" disabled >
				</div>
				<div style="clear: left;">
				<label class="label_table">Promo:</label> <input type="text" name="referral_promo_desc" id="referral_promo_desc" size="20" disabled >
				</div>
		</div>
	</div>

	<div id="section_program_details" style="display:none">
		<p>We have a number of organizations and companies that we partner with to provide
		additional value to our customers. Would you like me to help you determine if you already have an
		association or membership with any of our partners?
<?php
	$findPartners = null;
	$widget = new EP_HTML_Form_Widget_RadioBool( 'find_partners', $findPartners );
	echo $widget->getOutput();
?>
		</p>

        <div id="section_find_partners_yes" style="display:none;" >
        	<p>Are you currently a member or have an association with a company or organization in one of the
        	following categories?</p>
        </div>
        <div id="section_find_partners_no" style="display:none">
           	<p>That is OK. I can still give you a great offer!!</p>
        </div>
	</div>


	<div id="nooffercode_yes" class="padding_top">
 		<div id="no_choice_cashback" style="display:none">
        	I can tell you what partnerships we have or if you have a particular one in mind i can check to
        	see if we are associated with that company or organization.
        </div>
        <div id="nochoice" style="display:none">
           	<p class="rep_note">That is OK. I can still give you a great offer!!!</p>
        </div>
	</div>


    <div id="section_program_details_yes" style="display:none" >
    	<div id="offersearch_categories" style="display:none;" class="padding_bottom" >
             <span style="width: 6em;"><label class="label_table" for="sb_partner_cats">Category: </label></span>
             <select name="sb_partner_cats" id="sb_partner_cats" >
             	<option value="">Select Category</option>
<?php
	foreach ( $partner_categories as $num => $category )
	// while($partner_categories_rec = mysql_fetch_object( $partner_categories_res ))
    {
    	//if($_SESSION['tabs']['offer']['partner_selection']==$partner_categories_rec->id)
       // {
       // 	echo '<option value="'.$partner_categories_rec->id.'" Selected>'.$partner_categories_rec->name.'</option>'."\n";
       // }
       // else
       // {
          	echo '<option value="'. $category->getId() . '">' . $category->getName() . '</option>'."\n";
       // }
	}
?>
			</select>
    	</div>

    	<div id="offersearch_partners" style="display:none; " class="padding_bottom" >
    		<!--<p class="hide_for_default_offer" style="clear:both;" >I can tell you what partnerships we have or if you have a particular one in mind I can check to see if we are
    		associated with that company or organization.</p>-->

    		<!--<div class="hide_for_default_offer" style="width: 400px; float:right; background-color: #eee; padding: 4px;">-->
    		<div >
    			<div >
					<!--<div style="float: right; padding-top: 8px;">
						<span id="offersearch_partners_body_open"><a class="tabtext" href="javascript: void(0);">+Open</a> / </span>
						<span id="offersearch_partners_body_close"><a class="tabtext" href="javascript: void(0);">-Close</a></span>
					</div>
					<div class="rep_note">NOTES TO TSR:</div>-->
				</div>
				<div id="offersearch_partners_body" style="display:none;">
	    			<p class="rep_note">IF CUSTOMER CAN NOT FIND A PARTNERSHIP:<br>
	    				<span class="formdata">That is OK I can still give you a great offer!</span>
	    				<br> SELECT BRAND DEFAULT   GO TO II4   TODO
	    			</p>
					<p class="rep_note">NOTE TO TSR: IF CUSTOMER FINDS A PARTNERSHIP:
						<span class="formdata">SELECT DEFAULT OFFER FOR THAT PARTNER GO TO II3</span> TODO
					</p>
				</div>
			</div>
			<span style="width: 6em;"><label class="label_table" for="sb_partners">Partner: </label></span><span id="partnerdiv"></span>
    	</div>

    	<div id="offersearch_campaigns" style="display: none; clear: left;" class="padding_bottom" >
    	    <span style="width: 6em;"><label class="label_table" for="sb_campaigns">Campaign: </label></span><span id="camdiv"></span>
    	</div>

    	<div id="offersearch_promo" style="display: none; clear: left; " class="padding_bottom" >
    		<label class="label_table" for="sb_promos">Promo: </label><span id="promodiv"></span>
    	</div>

        <span class="success referral_success" ></span><span class="error referral_error" ></span>

	</div>

	<div id="note_to_TSR" style="display:none">
		<p class="rep_note">NOTE TO TSR:  IF CUSTOMER PROVIDES AN OFFER CODE THAT IS NOT IN THE DROP DOWN,
		READ THE FOLLOWING AND SELECT THE DEFAULT OFFER:</p>&nbsp;Unfortunately that offer is no longer valid.
		However, I can give you our most recent offer.
	</div>


	<div id='membership_select' style="display:none; clear:both;" class="padding_top padding_bottom" >
		<div style="background-color: #efefef;">
			Can you please give me your <span class="partner_name"></span> &nbsp;number?
			<select name='membership_selection' id='membership_selection'  >
				<option value=''>Select</option>
				<option value='yes'>Yes</option>
				<option value='no'>No</option>
				<option value='not'>Not a Member</option>
			</select>
		</div>

		<div id="section_member_select_no" style="display:none" class="padding_top">
			<p>Unfortunately  in order to receive the rewards associated with this offer I will need your
			<span class="partner_name"></span> number.  I can go ahead and process your enrollment and when you have the
			membership information you can call our customer service department at <?=$_SESSION['cs_tel']; ?>.
			They will update your information and you can begin earning your rewards. Please note that until that
			time you will not earn any awards.</p>
		</div>

		<div id="section_member_select_yes" style="display:none">
			<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
	        	<tr>
	            	<td colspan="2" bgcolor="#666666" class="admintable">
	                	<font color="#FFFFFF"><strong>About Your <span class="partner_name"></span> Account:</strong></font>
	                </td>
	          	</tr>
	<?php
	$cksumtxt = 0;
	if($cksumtxt)
	{
	?>
	                            <tr>
	                                   <td colspan="2" class="formdata">
	                                          <?php echo $cksumtxt;?>
	                                   </td>
	                            </tr>
	<?php
	}
	?>
				<tr>
	            	<td bgcolor="#efefef" class="formdata" width="37%">
	                	<span class="partner_name"></span> Number:<font color="red">*</font>
	                </td>

					<td bgcolor="#efefef" class="formdata" width="63%">
	                	<div class="inp_h">
	                    	<input type="text" name="dividend_miles_number" id="dividend_miles_number" maxlength="12" value="" >
	                    	<span class="error" id="dividend_miles_number_error"></span>
	                    </div>
	                </td>
				</tr>
				<tr>
					<td colspan="2">
					<div id="validate_dividend_miles_number"></div>
					</td>
				</tr>
				<tr>
	            	<td bgcolor="#efefef" class="formdata" width="37%">
	                	First Name on <span class="partner_name"></span> Account:<font color="red">*</font>
	                </td>
	                <td bgcolor="#efefef" class="formdata" width="63%">
	                	<input type="text" name="pfname" id="pfname" value="" />
	                </td>
				</tr>
	            <tr>
	                <td bgcolor="#efefef" class="formdata" width="37%">
	            		Last Name on <span class="partner_name"></span> Account:<font color="red">*</font>
	                </td>
	                <td bgcolor="#efefef" class="formdata" width="63%">
	                	<input type="text" name="plname" id="plname" value="" />
	                </td>
				</tr>
	 		</table>


			<div id="all_nines" >
            	<p class="rep_note">NOTE TO TSR: IF CALLER DOES NOT HAVE MEMBER NUMBER AND WANTS TO
                CONTINUE ON WITH ENROLLMENT FOR THE OFFER, ENTER IN ALL 9's AND ON THE 2'nd ATTEMPT YOU WILL
                BE ABLE TO PROCEED.</p>
			</div>


		</div>

		<div id="section_member_select_not_member" style="display:none" class="padding_top">
			<p>In order for you to receive the rewards associated with the program you will need to be a member.  However we do have a
			program that you can enroll in that does not require a membership. Would you be interested?
<?php
	$defaultOffer = null;
	$widget = new EP_HTML_Form_Widget_RadioBool( 'default_offer', $defaultOffer );
	echo $widget->getOutput();
?>
			</p>
			<div id="section_default_offer_yes" style="display:none">

			</div>
			<div id="section_default_offer_no" style="display:none">
				<p class="rep_note">NOTE TO TSR: GO BACK TO OFFER SEARCH IF CALLER WANTS A DIFFERENT OFFER</p>
				<p>Is there anything else I can help you with today?
		<?php
			$defaultOfferNo = null;
			$widget = new EP_HTML_Form_Widget_RadioBool( 'default_offer_no', $defaultOfferNo );
			echo $widget->getOutput();
		?>
				<div id="section_default_offer_no_yes" style="display:none">
					<p class="rep_note">NOTE TO TSR: IF CUSTOMER HAS QUESTIONS GO TO FAQS</p>
				</div>
				<div id="section_default_offer_no_no" style="display:none">
					<p class="rep_note">NOTE TO TSR: AUTO DISPO #3: NO OFFER CHOSEN</p>
				</div>
			</div>
		</div>

	</div><!-- end membership_select -->




	<div id="section_member_select_no_jfb" style="display: none;" class="padding_top">
		<p>Unfortunately  in order to enroll in this program I will need your NJFB membership number.
		Once you have this information, please call us back at <?=$_SESSION['enroll_tel']; ?>
		</p>
		<div>
        	<p>Is there anything else I can help you with today?
<?php
	$frm_confirm_offer = null;
	$widget = new EP_HTML_Form_Widget_RadioBool( 'jfb_need_member', $frm_confirm_offer );
	echo $widget->getOutput();
?>
		</div>
		<div id="section_jfb_need_member_yes" style="display: none; ">
        	<p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p>
        </div>
		<div id="section_jfb_need_member_no" style="display: none; ">
			 <span class="rep_note">NOTE TO TSR: AUTO DISPO # 15 - Caller Refused to provide Membership #</span>
		</div>
	</div>

	<div id="section_member_select_no_upr" style="display: none;" class="padding_top">
		<p>Unfortunately  in order to enroll in this program I will need your <span class="partner_name"></span> number.
		Once you have this information, please call us back at <?=$_SESSION['enroll_tel']; ?> or you can complete your enrollment on-line at  www.upromise.com.
		</p>
		<div>
        	<p>Is there anything else I can help you with today?
<?php
	$frm_confirm_offer = null;
	$widget = new EP_HTML_Form_Widget_RadioBool( 'need_member', $frm_confirm_offer );
	echo $widget->getOutput();
?>
		</div>
		<div id="section_need_member_yes" style="display:none">
        	<p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p>
        </div>
		<div id="section_need_member_no" style="display:none">
			 <span class="rep_note">NOTE TO TSR: AUTO DISPO # 10 - Caller Refused to provide Upromise Membership #</span>
		</div>
	</div>



	<div id="Capturemember" style="display:none">
		<table width="100%" align="center" border="0" cellspacing="0" cellpadding="5" class="admintable">
			<tr>
				<td>
					<div id="Capturemember" style="display:none">
						Capture Number
					</div>


					<div id="ifnotamember"  style="display:none">
						In order for you to receive the rewards associated with the program you will need to be a member. However we do have a program that you can enroll in that does not require a membership. Would you be interested?
						<input type="radio" id="ifnotamember_yes" name="ifnotamember" value="17" <?if($_SESSION['tabs']['offer']['ifnotamember']==17){echo "Checked";}?>  onclick="global_checksetting(this,'no2member_uprval_yes','no2member_uprval_no','','')" />Yes
						<input type="radio" id="ifnotamember_no" name="ifnotamember" value="18" <?if($_SESSION['tabs']['offer']['ifnotamember']==18){echo "Checked";}?>  onclick="global_checksetting(this,'no2member_uprval_yes','no2member_uprval_no','disp','10')" />No

					</div>
					<div id="no2member_uprval_yes" style="display:none">
						<p class="rep_note">Probe the customer</p>
					</div>
					<div id="no2member_uprval_no" style="display:none">
						<p class="rep_note">End Call (NOTE TO TSR: CALL WAS AUTO DISPO'D #10: CUSTOMER REFUSED TO PROVIDE UPROMISE MEMBERSHIP #)</p>
					</div>
					<div id="ifnotamember_no"  style="display:none">
						Is there anything else i can help you with today?
						<input type="radio" id="ifnotamember_yes" name="ifnotamember_no" value="19" <?if($_SESSION['tabs']['offer']['ifnotamember_no']==19){echo "Checked";}?>  onclick="global_checksetting(this,'ifnotamember_no_yes','ifnotamember_no_no','','')" />Yes
						<input type="radio" id="ifnotamember_no" name="ifnotamember_no" value="20" <?if($_SESSION['tabs']['offer']['ifnotamember_no']==20){echo "Checked";}?>  onclick="global_checksetting(this,'ifnotamember_no_yes','ifnotamember_no_no','','')" />No

					</div>
					<div id="ifnotamember_no_yes"  style="display:none">
						<p class="rep_note">If Customer has Questions go to FAQS</p>
					</div>
					<div id="ifnotamember_no_no"  style="display:none">
						<p class="rep_note">Thank you for calling Energy Plus and have a nice day.</p>
					</div>
				</td>
			</tr>
		</table>
	</div>



	 <div id="section_reward_desc" style="display:none" >
     	<span id="reward_desc"></span>
	</div>


	<div id="section_confirm_offer" style="display:none;" class="padding_top" >
    	<div>
        	Can you please confirm that this is the offer you would like to enroll in?

<?php
	$frm_confirm_offer = null;
	$widget = new EP_HTML_Form_Widget_RadioBool( 'confirm_offer', $frm_confirm_offer );
	echo $widget->getOutput();
?>
		</div>

       	<div id="section_confirm_offer_no" style="display:none" class="padding_top">
        	<p>Is there another offer I can help you find?
<?php
	$confirmOfferNo = null;
	$widget = new EP_HTML_Form_Widget_RadioBool( 'confirm_offer_no', $confirmOfferNo );
	echo $widget->getOutput();
?>
<!-- Thank you for calling Energy Plus and have a nice day.�(END CALL)
			(AUTO DISPO #3: NO OFFER CHOSEN)
-->
			</p>
		   <p class="rep_note">NOTE TO TSR: GO BACK TO OFFER SEARCH IF CALLER WANTS A DIFFERENT OFFER</p>
        </div>

        <div id="section_confirm_offer_yes" style="display:none">
            <p class="rep_note">Click "Save and Continue" to go to Customer Information tab</p>
        </div>

        <div id="confirm_offer_no_yes" style="display:none">
        	<p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p>
        </div>

        <div id="confirm_offer_no_no" style="display:none">
        	<p class="formdata">"Thank you for calling Energy Plus and have a nice day."</p>
        	<p class="rep_note">(END CALL) (AUTO DISPO #3: NO OFFER CHOSEN)</p>
        </div>
	</div><!-- end section_confirm -->


	<div class="padding_top" style="padding-top: 3em; padding-bottom: 10em; clear: both;" >
		<input type="hidden" name="ref_value" id="ref_value">
		<input type="hidden" name="checksum" id="checksum">
		<input type="hidden" name="partnerid" id="partnerid">
      <div>
		<input type="hidden" name="hidden_partner_code" id="hidden_partner_code" >
		<input type="hidden" name="hidden_campaign_code" id="hidden_campaign_code" >
		<input type="hidden" name="hidden_promo_code" id="hidden_promo_code" >
		<input type="hidden" name="hidden_refid" id="hidden_refid" >
        <input type="hidden" name="hidden_reward_type" id="reward_type" >
        <input type="hidden" name="hidden_allow_invalid_checksum" id="hidden_allow_invalid_checksum" value="0" >
       </div>
		<input type="button" class="ib_button" name="btn_continue" id="btn_continue" value="Save and Continue"  >
		<input type="button" class="ib_button" name="btn_save" id="btn_save" value="Save" onClick="step_offerval(this)" >
		<input type="button" class="ib_button" name="btn_log_dispo" id="btn_log_dispo" value="Log Dispo" onClick="logDispo( '#frm_inbound' )" >
		<input type='hidden' name='eflag' id='eflag' >
		<input type='hidden' name='eflagid' id='eflagid'>
		<input type='hidden' name='next_url' id='next_url' value='select_customer.php'>
	</div>
</div>
</form>
</div>
<?php
        require_once 'includes/statusbar.php';
?>
</div><!--yui3-g-->
<?php
        require_once 'includes/footer.php';
?>

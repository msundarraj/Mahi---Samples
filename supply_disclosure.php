<?php

//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Disclosure//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////

require_once 'includes/main.php';

//
// make sure previous tabs are completed
//
require_once 'includes/check_state.php';
require_once 'includes/check_util.php';
require_once 'includes/check_offer.php';
require_once 'includes/check_customer.php';
require_once 'includes/check_billing.php';
require_once 'includes/check_account.php';

require_once 'EP/Model/StateMapper.php';
require_once 'EP/Model/RegisterMapper.php';
require_once 'EP/Model/Ib/CallMapper.php';
require_once 'EP/HTML/Form/Widget/RadioBool.php';
require_once 'EP/Model/Offer.php';
require_once 'EP/Model/StarburstvariablesMapper.php';


$register = $_SESSION['register'];
$regid=$register->getId();
$register_mapper = new EP_Model_RegisterMapper();

$partnerId = null;
if ( isset( $_SESSION['tabs']['offer']['partner_id']))
{
	$partnerId = $_SESSION['tabs']['offer']['partner_id'];
}

$promoCode = null;
if ( isset( $_SESSION['tabs']['offer']['promo']))
{
	$promocode = $_SESSION['tabs']['offer']['promo'];
}

$uid=null;
if(isset($_SESSION['first']['uid']))
{
	$uid=$_SESSION['first']['uid'];
}

$Session_state= null;
if(isset($_SESSION['tabs']['state']['state']))
{
	$Session_state = $_SESSION['tabs']['state']['state'];
}

$offer = new EP_Model_Offer();
$rewardDesc = null;
if ( isset( $_SESSION['tabs']['offer']['partner'] ) &&
		isset( $_SESSION['tabs']['offer']['campaign'] ) &&
		isset( $_SESSION['tabs']['offer']['promo'] ) &&
		isset( $_SESSION['tabs']['state']['state'] ) )
{
	$result = $offer->fetchDescription(  $_SESSION['tabs']['state']['state'], $_SESSION['tabs']['offer']['promo'],  $_SESSION['tabs']['offer']['campaign'],  $_SESSION['tabs']['offer']['partner'], $base_url );
	//echo '<pre>' . print_r( $result, true ) . '</pre>';
}


//print_r($_SESSION);

$reward_type = null;

$award_mons =null;
$partner = null;
if ( isset( $_SESSION['partner']))
{
      $partner = $_SESSION['partner'];
      $variables = $partner->getVariables();
      // echo '<pre>' . print_r( $variables, true ) . '</pre>';

      if ( isset( $variables['reward_type']))
      {
            $reward_type= $variables['reward_type']->variable_value;
      }



      if(isset($variables['award_mon']))
	  {
	         $award_mons= $variables['award_mon']->variable_value;
      }


	  if(isset($variables['bonus_biz']))
	  {
	  	  	 $bonus_biz= $variables['bonus_biz']->variable_value;
	  }

}

if (($Session_state ==1) || ($Session_state==7) || ($Session_state==6) || ($Session_state==5))
{
	$A_cycle='billing cycle';
	$B_cycle='billing cycles';
	$C_cycle='billing cycles';
}
else if(($Session_state ==2) || ($Session_state==3) || ($Session_state==4))
{
	$A_cycle='month of active service';
	$B_cycle='months';
	$C_cycle='months of active service';
}


$session_aff_res_ongoing=null;
$session_award_mons=null;
$session_bizaffin=null;
$session_bonus=null;
$session_bonus_biz=null;
$session_bonus_mon=null;
$session_bonus_res=null;
$session_bullet_snipet=null;
$session_full_program=null;
$session_ib_desc=null;
$session_ongoing_bullet=null;
$session_resaffin=null;
$session_rewtxt=null;
$session_reward_type=null;
$session_second_month=null;

$mapper = new EP_Model_StarburstvariablesMapper();
$sb = $mapper->fetchVariablesByPartnerAndPromo( $partnerId, $promoCode );
if ( $sb )
{
	// echo '<pre>' . print_r( $sb, true ) . '</pre>';
	$session_aff_res_ongoing = $sb->aff_res_ongoing;
	$session_award_mons =  $sb->award_mons;
	$session_bizaffin = $sb->bizaffin;
	$session_bonus = $sb->bonus;
	$session_bonus_biz = $sb->bonus_biz;
	$session_bonus_mon = $sb->bonus_mon;
	$session_bonus_res = $sb->bonus_res;
	$session_bullet_snippet = $sb->bullet_snippet;
	$session_bullet_snippet2 = $sb->bullet_snippet2;
	$session_full_program = $sb->full_program;
	$session_ib_desc = $sb->ib_desc;
	$session_ongoing_bullet = $sb->ongoing_bullet;
	$session_resaffin = $sb->resaffin;
	$session_rewtxt = $sb->text1_miles;
	$session_reward_type = $sb->reward_type;
	$session_second_month = $sb->second_month;
	      
}
/*
if (isset( $_SESSION['sb']['aff_res_ongoing']))
{
     $session_aff_res_ongoing = $_SESSION['sb']['aff_res_ongoing'];
}
if (isset($_SESSION['sb']['award_mons']))
{
    $session_award_mons =  $_SESSION['sb']['award_mons'];
}
if (isset( $_SESSION['sb']['bizaffin']))
{
     $session_bizaffin = $_SESSION['sb']['bizaffin'];
}
if (isset( $_SESSION['sb']['bonus']))
{
     $session_bonus = $_SESSION['sb']['bonus'];
}
if (isset( $_SESSION['sb']['bonus_biz']))
{
     $session_bonus_biz = $_SESSION['sb']['bonus_biz'];
}
if (isset( $_SESSION['sb']['bonus_mon']))
{
     $session_bonus_mon = $_SESSION['sb']['bonus_mon'];
}
if (isset( $_SESSION['sb']['bonus_res']))
{
     $session_bonus_res = $_SESSION['sb']['bonus_res'];
}
if (isset( $_SESSION['sb']['bullet_snipet']))
{
     $session_bullet_snipet = $_SESSION['sb']['bullet_snipet'];
}


if (isset( $_SESSION['sb']['full_program']))
{
      $session_full_program = $_SESSION['sb']['full_program'];
}
if (isset( $_SESSION['sb']['ib_desc']))
{
     $session_ib_desc = $_SESSION['sb']['ib_desc'];
}
if (isset( $_SESSION['sb']['ongoing_bullet']))
{
     $session_ongoing_bullet = $_SESSION['sb']['ongoing_bullet'];
}
if (isset( $_SESSION['sb']['resaffin']))
{
     $session_resaffin = $_SESSION['sb']['resaffin'];
}
if (isset( $_SESSION['sb']['rewtxt']))
{
      $session_rewtxt = $_SESSION['sb']['rewtxt'];
}
if (isset( $_SESSION['sb']['reward_type']))
{
     $session_reward_type = $_SESSION['sb']['reward_type'];
}
if (isset( $_SESSION['sb']['second_month']))
{
     $session_second_month = $_SESSION['sb']['second_month'];
}
*/

$Session_service_lname= null;
if(isset($_SESSION['tabs']['customer']['last_name']))
{
	$Session_service_lname=$_SESSION['tabs']['customer']['last_name'];
}

$Session_lastname= null;
if(isset($_SESSION['tabs']['state']['lname']))
{
	$Session_lastname=$_SESSION['tabs']['state']['lname'];
}

$Session_service_fname= null;
if(isset($_SESSION['tabs']['customer']['first_name']))
{
	$Session_service_fname=$_SESSION['tabs']['customer']['first_name'];
}

$Session_firstname= null;
if(isset($_SESSION['tabs']['state']['fname']))
{
	$Session_firstname=$_SESSION['tabs']['state']['fname'];
}



if($Session_lastname)
{
	$lastName  = $Session_lastname;
	$firstName = $Session_firstname;

}
else
{
	$lastName  = $Session_service_lname;
	$firstName = $Session_service_fname;
}


$spouse_var=null;
$Session_spouse_name = null;
if(isset($_SESSION['tabs']['customer']['spfname']))
{
	$Session_spouse_name=$_SESSION['tabs']['customer']['spfname'];
}

if($Session_spouse_name!='')
{
	$fname_var= $_SESSION['tabs']['customer']['spfname'];
	$lname_var= $_SESSION['tabs']['customer']['splname'];
}
else
{
	$fname_var=$firstName;
	$lname_var=$lastName;
}


//print_r($_SESSION);


$Session_current_supplier= null;
if(isset($_SESSION['tabs']['utility']['currsupp']))
{
	$Session_current_supplier = $_SESSION['tabs']['utility']['currsupp'];
}

$Session_resbus= null;
if(isset($_SESSION['tabs']['utility']['resbus']))
{
	$Session_resbus=$_SESSION['tabs']['utility']['resbus'];
}


$Session_select_utility= null;
if(isset($_SESSION['tabs']['utility']['select_utility']))
{
	$Session_select_utility=$_SESSION['tabs']['utility']['select_utility'];
}



$Session_utility_id= null;
if(isset($_SESSION['utility']['id']))
{
	$Session_utility_id=$_SESSION['utility']['id'];
}

$Session_partner_type= null;
if(isset($_SESSION['tabs']['offer']['partner_type']))
{
	$Session_partner_type=$_SESSION['tabs']['offer']['partner_type'];
}

$Session_resbus= null;
if(isset($_SESSION['tabs']['utility']['resbus']))
{
	$Session_resbus=$_SESSION['tabs']['utility']['resbus'];
}

$Session_acctext=null;
if(isset($_SESSION['utility']['acctext']))
{
$Session_acctext=$_SESSION['utility']['acctext'];
}


$Session_reward_type= null;
if(isset($_SESSION['tabs']['offer']['reward_type']))
{
	$Session_reward_type=$_SESSION['tabs']['offer']['reward_type'];
}

$Session_partner= null;
if(isset($_SESSION['tabs']['offer']['partner']))
{
	$Session_partner=$_SESSION['tabs']['offer']['partner'];
}


$Session_entype= null;

if(isset($_SESSION['tabs']['utility']['entype']))
{
	$Session_entype=$_SESSION['tabs']['utility']['entype'];
}


$Session_confcode= null;
if(isset($_SESSION['first']['confcode']))
{
	 $Session_confcode=$_SESSION['first']['confcode'];
}


$Session_utility_code= null;
if(isset($_SESSION['utility']['code']))
{
	$Session_utility_code=$_SESSION['utility']['code'];
}

$Session_utility=null;
if(isset($_SESSION['utility']['utility']))
{
 $Session_utility=$_SESSION['utility']['utility'];
}

$Session_select_utility= null;
if(isset($_SESSION['tabs']['utility']['select_utility']))
{
	$Session_select_utility=$_SESSION['tabs']['utility']['select_utility'];
}


$Session_esiid= null;
if(isset($_SESSION['tabs']['utility']['esiid']))
{
	$Session_esiid = $_SESSION['tabs']['utility']['esiid'];
}

$Session_multiple_service= null;
if(isset($_SESSION['multiple_service']))
{
	$Session_multiple_service = $_SESSION['multiple_service'];
}

$Session_servicereference= null;
if(isset($_SESSION['tabs']['account']['servicereference']))
{
	$Session_servicereference = $_SESSION['tabs']['account']['servicereference'];
}

$Session_Account_Number= null;
if(isset($_SESSION['tabs']['account']['Account_Number']))
{
	$Session_Account_Number = $_SESSION['tabs']['account']['Account_Number'];
}

$Session_multiple_account= null;
if(isset($_SESSION['multiple_account']))
{
	$Session_multiple_account = $_SESSION['multiple_account'];
}

$Session_Service_Address1= null;
if(isset($_SESSION['tabs']['customer']['Service_Address1']))
{
	$Session_Service_Address1 = $_SESSION['tabs']['customer']['Service_Address1'];
}

$Session_Service_Address2= null;
if(isset($_SESSION['tabs']['customer']['Service_Address2']))
{
	$Session_Service_Address2 = $_SESSION['tabs']['customer']['Service_Address2'];
}

$Session_City= null;
if(isset($_SESSION['tabs']['customer']['Service_City']))
{
	$Session_City = $_SESSION['tabs']['customer']['Service_City'];
}

$Session_abbrev= null;
if(isset($_SESSION['tabs']['state']['abbrev']))
{
	$Session_abbrev = $_SESSION['tabs']['state']['abbrev'];
}


$Service_Zip5= null;
if(isset($_SESSION['tabs']['customer']['Service_Zip5']))
{
	$Service_Zip5 = $_SESSION['tabs']['customer']['Service_Zip5'];
}

$Service_Zip4= null;
if(isset($_SESSION['tabs']['customer']['Service_Zip4']))
{
	$Service_Zip4 = $_SESSION['tabs']['customer']['Service_Zip4'];
}


$Session_Billing_Address1= null;
if(isset($_SESSION['tabs']['billing']['Billing_Address1']))
{
	$Session_Billing_Address1 = $_SESSION['tabs']['billing']['Billing_Address1'];
}


$Session_Billing_Address2= null;
if(isset($_SESSION['tabs']['billing']['Billing_Address2']))
{
	$Session_Billing_Address2 = $_SESSION['tabs']['billing']['Billing_Address2'];
}



$Session_Billing_City= null;
if(isset($_SESSION['tabs']['billing']['Billing_City']))
{
	$Session_Billing_City = $_SESSION['tabs']['billing']['Billing_City'];
}


$Session_Billing_State= null;
if(isset($_SESSION['tabs']['billing']['Billing_State']))
{
	$Session_Billing_State = $_SESSION['tabs']['billing']['Billing_State'];
}


$Session_Billing_Zip4= null;
if(isset($_SESSION['tabs']['billing']['Billing_Zip4']))
{
	$Session_Billing_Zip4= $_SESSION['tabs']['billing']['Billing_Zip4'];
}


$Session_Billing_Zip5= null;
if(isset($_SESSION['tabs']['billing']['Billing_Zip5']))
{
	$Session_Billing_Zip5 = $_SESSION['tabs']['billing']['Billing_Zip5'];
}



$Session_Affinity= null;
if(isset($_SESSION['tabs']['offer']['affinity']))
{
	$Session_Affinity = $_SESSION['tabs']['offer']['affinity'];
}


if($Session_current_supplier!='' && $Session_current_supplier!='CDPS')
{
	 $Session_current_supplier = "from  ".$_SESSION['tabs']['utility']['currsupp'];
}
else if($Session_current_supplier=='CDPS')
{
	 $Session_current_supplier = "from your current supplier";
}
else
{
	 $Session_current_supplier = "from  ".$Session_utility;
}


$Session_call= null;
if(isset($_SESSION['call']))
{
	$Session_call=$_SESSION['call'];
}

$Session_messages= null;
if(isset($_SESSION['messages']))
{
	$Session_messages=$_SESSION['messages'];
}

$Session_submitaction= null;
if(isset($_SESSION['submitaction']))
{
	$Session_submitaction=$_SESSION['submitaction'];
}

$green='';
if(isset($_SESSION['tabs']['account']['greenoption_choice']))
{
 $green=$_SESSION['tabs']['account']['greenoption_choice'];
}


$ssn1='';
if(isset($_SESSION['tabs']['account']['ssn1']))
{
	$ssn1=$_SESSION['tabs']['account']['ssn1'];
}

$ssn2='';
if(isset($_SESSION['tabs']['account']['ssn2']))
{
	$ssn2=$_SESSION['tabs']['account']['ssn2'];
}

$ssn3='';
if(isset($_SESSION['tabs']['account']['ssn3']))
{
	$ssn3=$_SESSION['tabs']['account']['ssn3'];
}




$Session_web_addr= null;
if(isset($_SESSION['web_addr']))
{
	$Session_web_addr=$_SESSION['web_addr'];
}


$offer = new EP_Model_Offer();


$firstMonthPrice = $offer->fetchFirstMonthPrice($Session_state, $_SESSION['tabs']['offer']['partner'], $Session_resbus, $Session_utility_code,$green);
$firstMonthPrice = sprintf("%0.3f&cent;",$firstMonthPrice*100);


	if($Session_state==3)
		{

			if(isset($_SESSION['Pfname']))
			{
				$register->setPfname(trim($_SESSION['Pfname']));
			}

			if(isset($_SESSION['Plname']))
			{
				$register->setPlname(trim($_SESSION['Plname']));
			}

			if(isset($_SESSION['enrollcustid']))
			{
				$register->setEnrollcustid(trim($_SESSION['enrollcustid']));
			}

			if(isset($_SESSION['Paysrc']))
			{
				$register->setPaysrc(trim($_SESSION['Paysrc']));
			}

			if(isset($_SESSION['Paymeth']))
			{
				$register->setPaymeth(trim($_SESSION['Paymeth']));
			}

			if(isset($_SESSION['depositamount']))
			{
				$register->setPayamt(trim($_SESSION['depositamount']));
			}

			if(isset($_SESSION['Credit1']))
			{
				$register->setCredit1(trim($_SESSION['Credit1']));
			}

			if(isset($_SESSION['Credit2']))
			{
				$register->setCredit2(trim($_SESSION['Credit2']));
			}

			$mapper = new EP_Model_RegisterMapper();

		}


$register = null;
if ( isset( $_SESSION['register']))
{
	$register = $_SESSION['register'];
}


	if ( !empty( $_POST ) && $_POST['submitflag']=='success')
	{

		// for successful enrollment
		// echo "Successful Enrollment";

		$callStatus = 1;

		$register->setAccept(trim($_POST['accept']));
		$register->setAuth('1');


		$regid=$register->getId();
		$mapper = new EP_Model_RegisterMapper();
		$result = $mapper->save( $register );

		//Update Auth Field




		$update_qry = "update register set auth='1' where uid='$uid' and id!=$regid";
		$update_res = mysql_query($update_qry,$link) or die(mysql_error()."--".$update_qry);

		if (isset($Session_call) && isset( $_SESSION['call_x_register']))
		{
			$call = $Session_call;
			$callId = $call->getId();
			$callXRegister = $_SESSION['call_x_register'];
		}
		else
		{
			die( 'cannot find call');
		}

		$date = date('Y-m-d H:i:s');

		// if the next_url is yet, we don't end the call
		// we only end the call_x_register and start a new one
		// then redirect to next_url for recording another account
		if ( isset( $_POST['next_url']) && $_POST['next_url'] == 'select_state.php')
		{
			$callXRegister->setDateEnded( $date );
			$callXRegisterMapper = new EP_Model_Ib_CallXRegisterMapper();

			$callXRegister->setEndStatusId($callStatus);
			$callXRegResult = $callXRegisterMapper->save( $callXRegister );

			if ( $callXRegResult === true )
			{
				// remove the session vars, but notthe original call
				$call->endCall( false );

				$callXRegisterNew = new EP_Model_Ib_CallXRegister();
				$callXRegisterNew->setCallId( $call->getId() );

				// save to db
				$callXRegisterNewMapper = new EP_Model_Ib_CallXRegisterMapper();
				$callXRegisterNewResult = $callXRegisterNewMapper->save( $callXRegisterNew );
				if ( $callXRegisterNewResult === true )
				{
					$_SESSION['call_x_register'] = $callXRegisterNew;
				}
				header( "Location: ${base_url}myinbound/" . $_POST['next_url'] );
			}
			else
			{
				echo "Error saving 1";
			}
		}
		else
		{
			$call->setDateEnded( $date );
			$callXRegister->setDateEnded( $date );

			$callMapper = new EP_Model_Ib_CallMapper();
			$callXRegisterMapper = new EP_Model_Ib_CallXRegisterMapper();

			$callXRegister->setEndStatusId($callStatus);

			$db = EP_Util_Database::pdo_connect();
			$db->beginTransaction();

			$callMapper->setDatabaseConnection( $db );
			$result = $callMapper->save( $call );
			$callXRegisterMapper->setDatabaseConnection( $db );
			$callXRegResult = $callXRegisterMapper->save( $callXRegister );

			if ( $result === true && $callXRegResult === true )
			{
				$db->commit();
				$call->endCall();
				header( "Location: ${base_url}myinbound/show_call_end.php?c=" . $call->getId() );
			}
			else
			{
				$db->rollback();
				echo "Error saving 2";
			}
		}
	} // end POST


	if( $Session_call )
	{
		$call = $Session_call;
		$callId = $call->getId();
	}

	if($Session_select_utility=='07')
	{
		$campaignid='30461';
		$acct_type="ACCT NUM";
	}
	else if($Session_select_utility=='08')
	{
		$campaignid='30462';
		$acct_type="POD ID";
	}

/*
	$default_price=getofferpricing($Session_utility_id,$Session_partner_type,$Session_resbus,$Session_state,$Session_utility_code);

	if($green=='001')
	{
		$default_price=$default_price+0.01;
	}

	if ($default_price!='')
	{
	$default_price="$ ".$default_price;
	}
*/
	date_default_timezone_set('Canada/Eastern');

	$central_time=date("M j, Y g:i a", strtotime('-1 hour'));

	$_POST['vendor_id']=111;



$register = $_SESSION['register'];



//echo $lastName;

$stateMapper = new EP_Model_StateMapper();
$state = $stateMapper->fetch( $_SESSION['tabs']['state']['state'] );
$stateId = $state->getId();
$csTele = $state->getCsTel();
$enrollTele = $state->getEnrollTel();

if($_SESSION['tabs']['state']['state'])
{
	$statechoice_yes='checked';
	$statechoice1_yes='checked';
}

$billing_cycle=12;

require_once 'includes/header.php';


	if($Session_Affinity==1)
	{
		if($Session_resbus == 0)
		{

			$code='32B2F9';
		}
		else
		{
			$code='6ACCCD';
		}
	}
	else
	{
		if($Session_resbus == 0)
		{
			$code='FD414E';
		}
		else
		{
			$code='2E950C';
		}
	}

//$code='6ACCCD';
//echo $_SESSION['tabs']['offer']['affinity'];

	//echo $_SESSION['tabs']['offer']['default_offercode'];


$Session_default_offercode=null;
if(isset($_SESSION['tabs']['offer']['default_offercode']))
{
	$Session_default_offercode=$_SESSION['tabs']['offer']['default_offercode'];
}


				$sql = sprintf("select * from util_offers where code='%s' and util_code='%s' order by effdate desc limit 1",$Session_default_offercode,$Session_select_utility);
				$res = mysql_query($sql,$link);
				$offerrow = mysql_fetch_object($res);
				//echo mysql_num_rows($res);

				if(mysql_num_rows($res)>0)
				{
					if($Session_resbus == 0)
					{
						$rate1000 = $offerrow->tdspfixed / 1000;
						//$offerrow->rate."<BR>";
						//$offerrow->tdspvariable."<BR>";
						$rate1 = $rate1000 + $offerrow->rate + $offerrow->tdspvariable;
						if($green=='001')
						{
							$rate1 += $offerrow->vas_rate;
						}
						$kwh='1000 kWh';
					}
					else
					{
						//echo $offerrow->tdsp_non_dem_var."<BR>";
						$rate_d2500 = $offerrow->tdsp_non_dem_fixed/ 2500;

						//echo $offerrow->rate."<BR>";
						$rate1 = $rate_d2500 + $offerrow->rate + $offerrow->tdsp_non_dem_var;
						if($green=='001')
						{
							$rate1 += $offerrow->vas_rate;
						}
						$kwh='2500 kWh';

					}
				}
				// 11.19.2010 modified by Judi to right align values for #1031





?>
<script type="text/javascript" src="../myinbound/scripts/disclosure.js"></script>

<script type="text/javascript">
var http1 = getHTTPObject();
var http2 = getHTTPObject();
var http3 = getHTTPObject();
</script>
</head>
<body onload="return load_globalsetting('<?=$Session_state;?>')" >

<div class="yui3-g" style="width:1100" >

<?php
	$page = basename( __FILE__ );
        require_once 'includes/nav.php';
?>
<div class="yui3-u" id="main">
<?

$account_flag="False";
//print_r($_SESSION);

if($ssn1==444 && $ssn2==44 && $ssn3==4444)
{
	$account_flag="True";
}

$Session_depositamount= null;
if(isset($_SESSION['depositamount']))
{
	$Session_depositamount=$_SESSION['depositamount'];
}

if($Session_depositamount==0)
{
	$account_flag="True";

}


if($account_flag=='False')
{
	if($Session_confcode=='' && $stateId==3)
	{
		 $Session_messages="Deposit Process needs to be completed before navigating to the Disclosure";
		 $output = '';
       	 $output = '<div class="ib_messages">';
	        $output .= print_r( $Session_messages, true );
       	 unset($Session_messages);
	        $output .= '</div>';
       	 print $output;
		 exit;
	}
}

$Billing_Address=$Session_Billing_Address1;

if($Session_Billing_Address2!='')
{
	$Billing_Address.=", ".$Session_Billing_Address2;
}


$Billing_Address.=", ".$Session_Billing_City.", ".$Session_Billing_State.", ".$Session_Billing_Zip5;

if($Session_Billing_Zip4!='')
{
	$Billing_Address.=" - ".$Session_Billing_Zip4."</B>";
}



?>
<form id="inbound_disclosure" name="inbound_disclosure" method="post" action="" >
<div class="whiteblock">
<?php
if(!isset( $_POST['tpv_disclosureflag']) || $_POST['tpv_disclosureflag']!='done')
{
?>
<p class="rep_note">MANDATORY DISCLOSURE MUST BE READ AND RECORDED WHEN
TAKING A NEW APPLICATION OVER THE PHONE:</p>

<p>Mr./Ms. <?=$lname_var;?>, for quality assurance purposes, the remainder of this call will be
recorded. I am going to read a short disclosure which you will need to  accept at the end. It will only take a
minute.
<?php
if( $stateId == 7 )
{
?>
May i Continue to read a brief disclosure?&nbsp;&nbsp;
<input type='radio' name='ilcontinue' id='ilcontinue_yes' value="1">&nbsp;&nbsp;Yes&nbsp;&nbsp;
<input type='radio' name='ilcontinue' id='ilcontinue_no' value="0">&nbsp;&nbsp;No
<?php
}
?>
</p>

<div id='section_ilcontinue_no' style="display:none">
	<p>
		We do need your permission to continue with your enrollment. If you do not want to move forward at this time, we'll need to suspend your enrollment.May i continue to read you a brief disclosure?
		<input type='radio' name='ildisclosure' id='ildisclosure_yes' value="1">&nbsp;&nbsp;Yes
		&nbsp;&nbsp;<input type='radio' name='ildisclosure' id='ildisclosure_no' value="0">&nbsp;&nbsp;No
	</p>
</div>

<div id='section_ildisclosure_yes' style="display:none"></div>
<div id='section_ildisclosure_no' style="display:none">
		<p>
			Should you decide you are still interested in enrolling with Energy Plus, Please call us back at <?=$enrollTele;?> <?if($Session_Affinity!=1){echo "or go on-line at ".$Session_web_addr;}?>
		</p>
		<p class='rep_note'>Auto Dispo #25: CALLER WOULD NOT ACCEPT DISCLOSURE TERMS</p>
</div>
<div id='section_ilcontinue_yes' style="display:none">

<p>First, I need to verify that your name is <b><?=$fname_var;?> <?=$lname_var;?></b>,
your billing address is <b><?=$Billing_Address;?></b></p>

<?php
/*
 * •	IF <STATE> = “PA”, “CT” OR “NY”: (insert<ACCT #>)
•	IF <STATE> = “TX”: (insert<ESI ID>)

 */
//print_r($_SESSION['account']);

if( isset( $_SESSION['tabs']['account']['Account_Number'] ) )
{
	if($stateId==2 || $stateId==1 || $stateId==4 ||  $stateId==7 || $stateId==6 || $stateId==5)
	{
		$multiple_account=rtrim($_SESSION['multiple_account'],",");

		if($multiple_account!='')
		{
			$multiple_account_arr= $_SESSION['tabs']['account']['Account_Number'].",".$multiple_account;
			$multiple_account_pieces = explode(",", $multiple_account_arr);
			echo "<table><tr><td width='50%'><span class='formdata'>and your Utility $Session_acctext(s) is</span></td><td width='50%'>&nbsp;</td></tr>";

			foreach ($multiple_account_pieces as &$multiple_account_value)
			{
				echo "<tr><td width='50%'>&nbsp;</td><td width='50%'><span class='formdata'>".$multiple_account_value."</span></td></tr>";
			}
		}
		else
		{
			echo "<table><tr><td width='50%'><span class='formdata'>and your Utility $Session_acctext(s) is ". $_SESSION['tabs']['account']['Account_Number']."</span></td></tr>";

		}

		echo "</table>";
	}
}


if( isset( $_SESSION['tabs']['account']['servicereference'] ) &&  $_SESSION['tabs']['account']['servicereference']!='')
{
	if( $stateId == 2 )
	{
		$multiple_service=rtrim($_SESSION['multiple_service'],",");

		if($multiple_service!='')
		{
			$multiple_service_arr= $_SESSION['tabs']['account']['servicereference'].",".$multiple_service;
			$multiple_service_pieces = explode(",", $multiple_service_arr);
			echo "<table><tr><td width='50%'><span class='formdata'>and your Utility Account number(s) is</span></td><td width='50%'>&nbsp;</td></tr>";

			foreach ($multiple_service_pieces as &$multiple_service_value)
			{
				echo "<tr><td width='50%'>&nbsp;</td><td width='50%'><span class='formdata'>".$multiple_service_value."</span></td></tr>";
			}
		}
		else
		{
			echo "<table><tr><td width='50%'><span class='formdata'>and your Utility Account number(s) is ".$_SESSION['tabs']['account']['servicereference']."</span></td></tr>";
		}
		echo "</table>";
	}
}


if(isset($_SESSION['tabs']['utility']['esiid']))
{
	if( $stateId == 3 )
	{
		echo "<span class='formdata'>and your ESI ID is ";
		echo $Session_esiid;
		echo "</span>";
	}
}

if( $stateId == 3 || $stateId == 7 )
{
?>
<br><br>
<p>
			and today is <?=date("M j, Y g:i a", strtotime('-1 hour'));?>
</p>
<?php
}
else
{
?>
<br><br>
<p>
			and today is <?=date("M j, Y g:i a");?>
</p>

<?php
}

if( $stateId == 7 )
{
?>
<p>
The service address where you are switching your electricity supply to Energy Plus is

<?php

	$service_Address=$Session_Service_Address1;

	if( $Session_Service_Address2 != '' )
	{
		$service_Address.=$Session_Service_Address2;
	}

	$service_Address.=", ".$Session_City.", ".$Session_abbrev.", ".$Service_Zip5;

	if( $Service_Zip4 != '' )
	{
		$service_Address .= " - " . $Service_Zip4 . "</B>";
	}

	echo "<B>" . $service_Address . "</B>";
}
?>


<p class="formdata">Is that correct?
		<input type='radio' name='date' id='date_yes' value="1">&nbsp;&nbsp;Yes
		<input type='radio' name='date' id='date_no' value="0">&nbsp;&nbsp;No
</p>
			<span class='rep_note'>(PAUSE FOR AUDIBLE AFFIRMATIVE RESPONSE.) </span>
<div id="section_date_no" style="display:none">
	<p class='rep_note'>NOTE TO TSR:  GO BACK TO APPROPRIATE TAB AND CORRECT AND SAVE THE INFORMATION,
	THEN RE-READ THE DISCLOSURE BEGINNING WITH THE CORRECTED INFORMATION. TO CONTINUE SELECT YES</p>
</div>

<?php

$output = '<div id="section_date_yes" style="display:none">';

switch ( $stateId )
{
	case 1:
		$output .= <<<EOL
<p class="formdata">Mr./ Ms. $lastName, thank you for switching your electricity supply to Energy Plus.
By accepting this offer, you are acknowledging that you are over the age of  18 and are the authorized representative of this account.
Specifically, you are authorizing Energy Plus to perform the necessary tasks to initiate service and begin the enrollment process as of today,
including obtaining from your current utility company your historic usage in order to appropriately forecast your energy needs going forward.
You may rescind this authorization at any time by calling us. Please note you can review your Consumer Bill of Rights by visiting the
Energy Plus website at www.energypluscompany.com.</p>
<p class="formdata">Mr. / Ms. $lastName, you are agreeing to accept variable rate pricing and there are no penalties or fees for termination.
By signing up with Energy Plus, you will qualify for a waiver of any sales tax charges that appear on the delivery portion of your
electricity bill. For New York City customers, the city portion of your sales tax will not be waived due to changes in the
New York City tax code. Exceptions according to specific tax laws in your area may apply. Please refer to your bill for your actual tax rate.
After you have been enrolled, Energy Plus only will be supplying your electricity. Your current utility will continue to deliver your
electricity and be available to respond to any emergencies should they occur. You will receive a sales agreement with complete terms and
conditions by mail. You may rescind this offer within 3 days of receipt of this letter by calling Energy Plus at
$csTele and providing your utility account number. Should you wish to make any changes to your account after the 3 day period,
including canceling your account, please call Energy Plus and give 30 days notice.</p>

EOL;

		break;
	case 2:
	case 4:
		$output .= <<<EOL
<p class="formdata">Mr. / Ms. $lastName, Thank you for switching your electricity supply to Energy Plus.
By accepting this offer, you are acknowledging that you are over the age of 18 and are the authorized representative of this account.
Specifically, you are authorizing Energy Plus to perform the necessary tasks to initiate service and begin the enrollment
process as of today, including obtaining from your current utility company your historic usage.</p>
<p class="formdata">Mr. / Ms. $lastName, you are agreeing to accept variable rate pricing, and there are no penalties or fees for termination.
After you have been enrolled, Energy Plus only will be supplying your electricity. Your current utility will continue to deliver your
electricity and bill you for all delivery services. You will receive a sales agreement with complete terms and conditions by mail.
You may rescind this offer within 3 days of receipt of this letter by calling Energy Plus at $csTele. Should you wish to make any changes to your account after the 3 day period,
including canceling your account, please call Energy Plus and give 30 days notice.</p>

EOL;

		break;
	case 5:
		$output .= <<<EOL
<p class="formdata">Mr. / Ms. $lastName, thank you for switching your electricity supply to Energy Plus.
By accepting this offer, you are acknowledging that you are over the age of 18 and are the authorized representative of this account.
Specifically, you are authorizing Energy Plus to perform the necessary tasks to initiate service and begin the enrollment
process as of today, including obtaining from your current utility company your historic usage. </p>
<p class="formdata">Mr. / Ms. $lastName, you are agreeing to accept variable rate pricing, and there are no penalties or fees for termination.
The first month price for this product is $firstMonthPrice.  After you have been enrolled, Energy Plus only will be supplying your electricity.
Your current utility will continue to deliver your electricity and bill you for all delivery services and will send you a confirmation letter
regarding this change. You will receive a sales agreement with complete terms and conditions by mail. You may rescind this offer within
14 days of the date of the letter from your utility by calling Energy Plus at $csTele. Should you wish to make any changes to your account after the 14 day period,
including canceling your account, please call Energy Plus and give 30 days notice.</p>

EOL;

		break;
	case 6:
		$output .= <<<EOL
<p class="formdata">Mr. / Ms. $lastName, thank you for switching your electricity supply to Energy Plus.
By accepting this offer, you are acknowledging that you are over the age of 18 and are the authorized representative of this account.
Specifically, you are authorizing Energy Plus to perform the necessary tasks to initiate service and begin the enrollment process as of today,
including obtaining from your current utility company your historic usage.</p>
<p class="formdata">Mr. / Ms. $lastName, you are agreeing to accept variable rate pricing, and there are no penalties or fees for termination.
Please note you may incur early cancellation penalties under a current supplier contract.
The first month price for this product is $firstMonthPrice. After you have been enrolled, Energy Plus only will be supplying your electricity.
Your current utility will continue to deliver your electricity and bill you for all delivery services and will send you a confirmation letter
regarding this change. You will receive a sales agreement with complete terms and conditions by mail. You may rescind this offer within
3 business days after receipt of this letter from Energy Plus by calling $csTele. Should you wish to make any changes to your account after the 3 day period,
including canceling your account, please call Energy Plus and give 30 days notice.</p>


EOL;

		break;
	case 7:
		$output .= <<<EOL

<p class="formdata">Mr. / Ms. $lastName, thank you for switching your electricity supply $Session_current_supplier to Energy Plus.
By accepting this offer, you are acknowledging that you are over the age of 18 and are the authorized representative of this account.
Specifically, you are authorizing Energy Plus to perform the necessary tasks to initiate service and begin the enrollment process as of today,
including obtaining from your current utility company your historic usage.
<p class="formdata">Mr. / Ms. $lastName, you are agreeing to accept variable rate pricing, and there are no penalties or fees for termination.
The first month price for this product is $firstMonthPrice. After you have been enrolled, Energy Plus only will be supplying your electricity.
Your current utility will continue to deliver your electricity and bill you for all delivery services and will send you a confirmation letter
regarding this change.  You will receive a sales agreement with complete terms and conditions by mail. You may rescind this offer no later than
five (5) calendar days prior to the effective date of your switch to Energy Plus by calling Energy Plus at $csTele. The effective date of your switch is your first normally scheduled meter reading date with Energy Plus. Should you wish to make changes to your account
after this period, including canceling your account, please call Energy Plus and give 30 days notice.</p>

EOL;

		break;
}

$output .= '</div>';
echo $output;


//If Texas
if( $stateId == 3 )
{
?>
<div id="tx_section_rebuttal_yes" style="display: none;">
	<?php
	if($Session_entype==2)
	{
	?>
      		<p class="formdata">Do you agree to become a customer with Energy Plus and allow Energy Plus to
      		complete the tasks required to start your electric service?
      		<input type='radio' name='entype' id='entype_yes' value="1">&nbsp;&nbsp;Yes
      		<input type='radio' name='entype' id='entype_no' value="0">&nbsp;&nbsp;No </p>
      		<p class="rep_note">NOTE TO TSR: PAUSE FOR AUDIBLE AFFIRMATIVE RESPONSE.</p>
	<?php
	}

	if($Session_entype==1)
	{
	?>
      		<p class="formdata">Do you agree to become an Energy Plus customer and allow us to complete the tasks
      		required to switch your electric service from your current REP to Energy Plus?
      		<input type='radio' name='entype' id='entype_yes' value="1">&nbsp;&nbsp;Yes
      		<input type='radio' name='entype' id='entype_no' value="0">&nbsp;&nbsp;No </p>
      		<p class="rep_note">NOTE TO TSR: PAUSE FOR AUDIBLE AFFIRMATIVE RESPONSE.</p>
	<?php
	}
	?>
</div>
<div id="section_entype_yes" style="display: none;">
	<?php
	if($Session_entype==2)
	{
	?>
		<p><span class="formdata">You are agreeing to accept variable rate pricing for the Energy Plus Variable Rate Product. The product term is month to month and there are no penalties or fees for early termination should you wish to cancel your service. The price that will be applied during your first billing cycle is <?echo sprintf("%0.1f&cent;",$rate1*100);?> per kWh based on a monthly usage of <?=$kwh;?>.</span></p>
      		<p><span class="formdata">You will receive the Terms of Service with complete terms and conditions by mail which will explain how to rescind this offer.You have the right to rescind this offer within 3 federal business days of receipt of the Terms of Service. Should you wish to make any changes to your account after the 3 day period, including canceling your account,please call Energy Plus at 877-710-5550.</span></p>
	<?php
	}

	if($Session_entype==1)
	{
	?>
		<p><span class="formdata">You are agreeing to accept variable rate pricing for the Energy Plus Variable Rate Product. The product term is month to month and there are no penalties or fees for early termination should you wish to cancel your service. The price that will be applied during your first billing cycle is <?echo sprintf("%0.1f&cent;",$rate1*100);?> per kWh based on a monthly usage of <?=$kwh;?>. You will receive the Terms of Service with complete terms and conditions by mail  which will explain how to rescind this offer.You have the right to rescind this offer within 3 federal business days of receipt of the Terms of Service. Should you wish to make any changes to your account after the 3 day period, including canceling your account,please call Energy Plus at 877-710-5550.</span></p>
	<?php
	}
	?>

</div>
	<div id="section_entype_no" style="display: none;">
			<p><span class="formdata">Unfortunately, we do need your acceptance to move forward with your enrollment.If you are not able to agree at this time, we will need to suspend your enrollment. Is there anything I can help clarify?<input type='radio' name='entype_no' id='entype_no_yes' value="5">&nbsp;&nbsp;Yes<input type='radio' name='entype_no' id='entype_no_no' value="4">&nbsp;&nbsp;No</span></p>

      		<div id="section_entype_no_yes" style="display: none;">
			<p class='rep_note'>IF CUSTOMER HAS QUESTIONS GO TO FAQS.</p>
			<p class='rep_note'>IF CUSTOMER WOULD LIKE TO PROCEED GO BACK TO POINT OF INTERRUPTION.</p>
      		</div>
      		<div id="section_entype_no_no" style="display: none;">
			<p><span class="formdata">Should you decide you are still interested in enrolling with Energy Plus, please call us back at <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or go on-line at ".$Session_web_addr;}?>.</span> <p class="rep_note"> NOTE TO TSR: CALL WAS AUTO DISPO'D #25: CALLER WOULD NOT ACCEPT DISCLOSURE TERMS</p>
      		</div>
	</div>
<?php
}
?>


<?php
if(( $stateId == 1) || ( $stateId == 3) || ( $stateId == 2) || ( $stateId == 4) || ( $stateId == 5) || ( $stateId == 6) || ( $stateId == 7))
{
?>

<?php

function getpartner_variable($variable_name,$partner_id)
{
	global $link;
	$partner_variable_qry = "select variable_value from partner_variables where variable_name='".$variable_name."' and partner_id=".$partner_id;
	$partner_variable_res = mysql_query($partner_variable_qry,$link) or die(mysql_error()."--".$partner_variable_qry);
	$partner_variable_row = mysql_fetch_row($partner_variable_res);
	$variable_value=$partner_variable_row[0];
	return $variable_value;
}

if(isset($_SESSION['tabs']['offer']['partner_id']))
{
 $reward_type=getpartner_variable('reward_type',$_SESSION['tabs']['offer']['partner_id']);
 $full_program=getpartner_variable('full_program',$_SESSION['tabs']['offer']['partner_id']);
}
 //$award_mons=getpartner_variable('full_program',$_SESSION['tabs']['offer']['partner_id']);




if($Session_Affinity==1)
{
echo "<div id='section_affinity_yes' style='display: none;'>";
	if($Session_resbus == 1)
	{
?>
      	<ul>
        <li><p class="formdata">Your activation bonus check will be processed after your second <?=$A_cycle;?> with Energy Plus
        and your cash back rebate will be processed after <?=$session_award_mons;?> <?=$B_cycle;?>.</p></li>
      	</ul>
<?php
	}
	else if($Session_resbus == 0)
	{
?>
     	<ul>
        <li><p class="formdata">Your activation bonus check will be processed after your second <?=$A_cycle;?> with
        Energy Plus and your cash back rebate will be processed after <?=$session_award_mons;?> <?=$B_cycle;?>.</p></li>
	</ul>
<?php
	}
	else if($Session_resbus==3)
	{
?>
	<ul>
        <li><p class="formdata">Your activation bonus checks for your business and residential account will be
        processed after your second <?=$A_cycle;?> with Energy Plus and your cash back rebates will be processed after
        <?=$award_mons;?> <?=$B_cycle;?>.</p></li>
    	</ul>
<?php
	}
echo "</div>";

}
else
{
	if($Session_reward_type=='miles_or_points')
	{
?>
	<div id="section_partner_type_yes" style='display: none;'>
		<p class="formdata">
			Your bonus <?=$reward_type;?> will be issued after your <?=$session_bonus_mon;?> <?=$A_cycle;?> from Energy Plus. Keep in mind, it may take 8-12 weeks from the day you start service for these <?=$reward_type;?> to appear on your <?=$full_program;?> statement.
		</p>
	</div>
<?php
	}
	else if($Session_reward_type=='cashback')
	{

		if($Session_partner=='UPR')
		{
?>
	<div id="section_partner_type_yes" style='display: none;'>
		<p class="formdata">
			Your cash back rebate will be issued monthly.
		</p>
	</div>

<?php

		}
		else
		{
?>
	<div id="section_partner_type_yes" style='display: none;'>
		<p class="formdata">
			Your cash back rebate will be issued after you complete <?=$session_award_mons;?> <?=$B_cycle;?> with Energy Plus.
		</p>
	</div>
<?php
		}
	}
}

?>
<div id="section_rebuttal_yes" style="width:100%; display: none;">
    <div id="rebuttal" style="width:100%; text-align:right; display: none;">
			<p class="rep_note" align='right'>NOTES TO TSR: If customer asks about 30 day notice:</p>
			<p  align='right'><a class="tabtext" href="javascript: void();" onclick="tsrnote('open')">+Open</a>/<a class="tabtext" href="javascript: void();" onclick="tsrnote('close')">-Close</a></p>
    </div>

    <div id="tsrnote" style="width:100%; display: none;">
	<p class="rep_note">REBUTTAL to What do you mean by 30 days notice? You said I could cancel at any time: </p>
		<span>
				The 30 days notice relates to the fact that we are not permitted by the utility company to interrupt your billing cycle,
				just as we can't start your service until the beginning of your next billing cycle. We will accept a cancellation the same day
				you call us, but it may take up to 30 days to complete the process and for your service to be completely switched to a new supplier.
		</span>
   </div>

    <div id="terms" style="display: none;">
		<p>Do you accept the terms as they have been read?
		<input  name="rebuttal_yes" id="rebuttal_yes_yes"  type="radio" value="1">&nbsp;&nbsp;Yes
		<input  name="rebuttal_yes" id="rebuttal_yes_no" type="radio" value="0">&nbsp;&nbsp;No</p>
    </div>

    <div id="section_rebuttal_yes_no" style="display: none;">
		<p>Unfortunately, we do need your acceptance to move forward with your enrollment.
		If you are not able to agree at this time, we will need to suspend your enrollment.
		Is there anything I can help clarify?
		<input  name="rebuttal_yes_no" id="rebuttal_yes_no_yes"  type="radio" value="1">&nbsp;&nbsp;Yes
		<input  name="rebuttal_yes_no" id="rebuttal_yes_no_no" type="radio" value="0" >&nbsp;&nbsp;No
		</p>

       	<div id="section_rebuttal_yes_no_yes" style="display: none;">
		 	<p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p>
      		<p class='rep_note'>IF CUSTOMER WOULD LIKE TO PROCEED GO BACK TO POINT OF INTERRUPTION</p>
		</div>

		<div id="section_rebuttal_yes_no_no" style="display: none;">
			<p>Should you decide you are still interested in enrolling with Energy Plus,
			please call us back at <?=$_SESSION['enroll_tel'];?> <?php if($Session_Affinity!=1){echo "or go on-line at ".$Session_web_addr;}?>.</p>
			<p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D :#25 CALLER WOULD NOT ACCEPT DISCLOSURE TERMS</p>
		</div>
	</div>
</div>
<?php
}
?>

</div><!---IL Div-->

<?php
if( $stateId == 2 )
	{
?>


<div id="section_closure_yes" style="display: none;">
			<p class="formdata">
				Great! Thank you. Your confirmation number is <?=$Session_confcode;?>.  You can refer to this number should you have questions about your enrollment.
			</p>
					<p>
							<span class="formdata">
							Thank you for your time and interest in Energy Plus. In order to process your enrollment, we are required by
							law to transfer you to an independent third party who will verify your intent to enroll with us as your electric supply company.
							They will verify you agreed to the terms of service we just discussed, as well as confirm your name, address, telephone number and
							electricity account information. You will need to confirm the information they request as well as reply with a clear Yes as a response
							when requested or we will be unable to process your enrollment. I will remain on the line during the verification process.
							</span>
					</p>
					<p>
							<span class="formdata">
							The Third Party Verification agent will not be able to answer any questions that you have during the process.
							If you ask any questions, they will need to turn the call back over to me and we will need to start the verification process again.
							For that reason, can I answer any questions for you now, prior to starting the process?
							&nbsp;<input type='radio' name='tpv' id='tpv_yes' value="9" >&nbsp;&nbsp;Yes&nbsp;&nbsp;<input type='radio' name='tpv' id='tpv_no' value="8" >&nbsp;&nbsp;No&nbsp;&nbsp;
							</span>
					</p>
					<div id="section_tpv_yes" style="display: none;"><p><span class="rep_note">ANSWER QUESTIONS</span></p></div>
					<div id="section_tpv_no" style="display: none;"><p>Upon completion of the verification process, your enrollment will then be submitted for processing and you will be sent a welcome letter from Energy Plus. Please hold while I transfer you to a verifications specialist.This may take a few moments.</p></div>
				<span class="rep_note" style="padding-left: 100px;">IF CUSTOMER ASKS WHO THE INDEPENDENT THIRD PARTY IS:</span><span class="formdata">We use a company called Data Exchange to perform our verification services.</span>
			<div style="width:100%;align:right;">
				<p class="rep_note" align='right'>NOTE TO TSR: If Customer says I do not have time to wait:</p>
				<p  align='right'><a class="tabtext" href="javascript: void();" onclick="tsrnote_time('open')">+Open</a>/<a class="tabtext" href="javascript: void();" onclick="tsrnote_time('close')">-Close</a></p>

			</div>
			<div id="tsrnote_time" style="display: none;">
				<p class="formdata">Unfortunately, we are required for you to confirm your intent to enroll with Energy Plus with an independent third party.
						We will be unable to process your request if you are unable to speak with our   verifications specialist.
						It will only take a few minutes of your time.
				</p>
				<p class="rep_note">NOTE TO TSR: If Customer says I still have no time:</p>
				<p class="formdata">I am sorry, but we will be unable to complete your enrollment request at this time. If you would still like to enroll, please call us back at <?=$_SESSION['enroll_tel'];?> and we would be happy to start the   enrollment process over again. </p>
				<p>May I Continue with your enrollment?
				&nbsp;<input type='radio' name='tpv_final' id='tpv_final_yes' value="9" >&nbsp;&nbsp;Yes<input type='radio' name='tpv_final' id='tpv_final_no' value="8" >&nbsp;&nbsp;No
				</p>
			</div>

			<span class='rep_note'>NOTE TO TSR: CALL WAS AUTO DISPO'D #26: CALLER REFUSED TPV</span>


	<span class="rep_note">VERIFICATIONS TRANSFER INSTRUCTIONS FOR TSR:</span>
		<ol>
			<li><span class="rep_note">PLACE CUSTOMER ON HOLD.</span></li>
			<li><span class="rep_note">Push the SUBMIT button on the screen to transfer data to Data Exchange. </span></li>
			<li><span class="rep_note">CALL DATA EXCHANGE AT 918-513-4736.</span></li>
			<li><span class="rep_note">YOU WILL BE GREETED BY A DATA EXCHANGE VERIFICATIONS SPECIALIST.</span></li>
			<li><span class="rep_note">PROVIDE THE VERIFICATIONS AGENT THE DATA THEY REQUEST. </span></li>

			<li><span class="rep_note">The Vendor Number will be displayed on your screen</span></li>
			<li><span class="rep_note">Your agent id is your 4 digit id</span></li>
			<li><span class="rep_note">The Campaign ID will be displayed on your screen</span></li>
			<li><span class="rep_note">The transaction ID will be displayed on your screen</span></li>

			<li><span class="rep_note">If there is an issue with the data being transferred to Data Exchange, the agent will also ask you for the following:</span></li>

			<li><span class="rep_note">Customer Name (Name on bill if it is different)</span></li>
			<li><span class="rep_note">Service Address</span></li>
			<li><span class="rep_note">Billing Address (if different from Service address)</span></li>
			<li><span class="rep_note">Customer Phone Number</span></li>
			<li><span class="rep_note">Utility Account Number</span></li>

			<li><span class="rep_note">Data Exchange agent will then give you a Verification Code.This does NOT mean the sale is valid.</span></li>
			<li><span class="rep_note">ONCE THE DATA IS PROVIDED, CONFERENCE THE CUSTOMER IN, THANK THEM FOR WAITING, AND INTRODUCE THEM TO THE VERIFICATIONS AGENT.</span></li>
			<li><span class="rep_note">Agent will stay on the entire call and will not exit until the TPV agent completes the call. If the customer asks a question or does not accept the terms then agent must start from the beginning or cancel the enrollment.</span></li>
		</ol>
</div>
		<div>
			<p>
			<input type="button" name"tpv_verification" id="tpv_verification" Value="Submit to TPV" onclick="stepval(this)" disabled>
			<input type="button" class="ib_button" name="btn_log_dispo" id="btn_log_dispo" value="Log Dispo" onclick="logDispo('disp','25')" disabled>
			<?php /*?><input type='hidden' name='eflag' id='eflag' >
			<input type='hidden' name='eflagid' id='eflagid' ><?php */ ?>
			</p>
		</div>
<?php
	}
else
	{
?>
<div id="section_closure_yes" style="display: none;">
	<p>
		Great! Thank you, your confirmation number is <b><?=$_SESSION['first']['confcode'];?></b>.
		You can refer to this number should you have questions about your enrollment.
	</p>

<p>Are there any other accounts you would like to enroll today?
<?php
	$widget = new EP_HTML_Form_Widget_RadioBool( 'more_accounts' );
	echo $widget->getOutput();
?>
</p>
	<div id="section_more_accounts_yes" style="display: none;" >
		<p>Great! Just give me a moment so I can complete this enrollment and then we will begin your
		next enrollment.</p>
	</div>
	<div id="section_anything_else" style="display: none;">
		<p>Is there anything else I can do for you today?
			<input type='radio' name='radio20' id='radio20_yes' value="11" >&nbsp;&nbsp;Yes
	      	<input type='radio' name='radio20' id='radio20_no' value="10" >&nbsp;&nbsp;No
		</p>
	    <div id="section_radio20_yes" style="display: none;">
	    	<p class='rep_note'>ANSWER THE CUSTOMER'S QUESTION</p>
	    </div>

	    <div id="section_radio20_no" style="display: none;">
	    	<p>Should you need to contact us for any reason, please call us at <?=$csTele;?>.</p>
	    </div>
	</div>
</div>

<div style="padding: 2em 0 5em 0;" >
	<input type="button" class="ib_button" name="btn_end_call" id="btn_end_call" value="Submit Enrollment" onclick="EndCall(this)" disabled>
	<input type="button" class="ib_button" name="btn_log_dispo" id="btn_log_dispo" value="Log Dispo" onclick="logDispo('disp','')" disabled>
	<input type="hidden" name="next_url" id="next_url" value="" >
</div>
<?php
	}
}
else
{
	$vendorNumber = null;
	$operator = $_SESSION['operator'];
	$mid = $operator->getMid();
	if ( $mid == 'EPIB' )
	{
		$vendorNumber = 50;
	}

	if ( $mid == 'RDIN' )
	{
		$vendorNumber = 40;
	}

	$register = $_SESSION['register'];

	$mapper = new EP_Model_RegisterMapper();
	$results = $mapper->fetchAllByUid( $register->getUid() );

?>
			<ol>
       		 	<li><span class="formdata">Vendor Number = <?=$vendorNumber;?></span></li>
				<li><span class="formdata">Campaign ID = <?=$campaignid;?></span></li>
				<li><span class="formdata">Transaction ID= <?=$callId;?></span></li>
      			</ol>
			<span class="formdata">In case the data transmittal fails, also display:</span>

<?php
	$output = '';
	foreach( $results as $num => $result )
	{
		$name = $result->getFirstName() . ' ' . $result->getLastName();
		$serviceAddress = $result->getServiceAddress();
		$billingAddress = $result->getBillingAddress();
		$phone = $result->getServicephone();
		$accountNumber = $result->getAccount();

		$output .= <<<EOL

		<ol>
<li>Customer Name: $name </li>
<li>Customer Service Address: $serviceAddress </li>
<li>Customer Billing Address: $billingAddress </li>
<li>Customer Phone Number: $phone </li>
<li>Utility Account Number (if CL&amp;P) or POD ID (if UI): $accountNumber </li>
	</ol>
EOL;

	}
	echo $output;
	$output = null;
?>


			<span class="rep_note">IF THE CALL WILL NOT CONNECT TO THE TPV VENDOR OR THE TPV VENDOR SAYS THAT THEY CANNOT TAKE THE CALL:</span><p class="formdata">Due to technical difficulties I will not be able to transfer you at this time. An Energy Plus representative will contact you shortly to complete the enrollment. Can I please have the best phone number to contact you?</p>
			<p class="rep_note">NOTE TO TSR: NOTIFY YOUR SUPERVISIOR OF THE TPV PROBLEM AND PROVIDE CUSTOMER NAME, ADDRESS AND CONTACT PHONE NUMBER. CLICK ON NON-SALE DISPO BUTTON TO END CALL.</p>


			<p align='left'><input type='button' name='tpvenroll' id='tpvenroll' value='Submit Enrollment' onclick="EndCall(this)">&nbsp;&nbsp;&nbsp;
			<input type='button' name='tpvdispo' id='tpvdispo' value='Non-Sale Dispo' onclick="logDispo('disp','43')"></p>

<?php
}
?>

			<input type='hidden' name='vendor_id' id='vendor_id' value='<?=$_SESSION['tabs']['state']['Vendorid'];?>'>
			<input type='hidden' name='agent_id' id='agent_id' value='<?=$_SESSION['tabs']['state']['agent_id'];?>'>
			<input type='hidden' name='Session_state' id='Session_state' value='<?=$Session_state?>'>
			<input type='hidden' name='trans_id' id='trans_id' value='<?=$callId;?>'>
			<input type='hidden' name='util_type' id='util_type' value='ELECTRIC'>
			<input type='hidden' name='campaign_id' id='campaign_id' value='<?=$campaignid;?>'>
			<input type='hidden' name='sales_state' id='sales_state' value='<?=$_SESSION['tabs']['state']['abbrev'];?>'>
			<input type='hidden' name='auth_fname' id='auth_fname' value='<?=$_SESSION['tabs']['customer']['first_name'];?>'>
			<input type='hidden' name='auth_mi' id='auth_mi' value='<?=$_SESSION['tabs']['customer']['middle_initial'];?>'>
			<input type='hidden' name='auth_lname' id='auth_lname' value='<?=$_SESSION['tabs']['customer']['last_name'];?>'>
			<input type='hidden' name='bill_fname' id='bill_fname' value='<?=$_SESSION['tabs']['customer']['first_name'];?>'>
			<input type='hidden' name='bill_mi' id='bill_mi' value='<?=$_SESSION['tabs']['customer']['middle_initial'];?>'>
			<input type='hidden' name='bill_lname' id='bill_lname' value='<?=$_SESSION['tabs']['customer']['last_name'];?>'>
			<input type='hidden' name='company_name' id='company_name' value='<?=$_SESSION['tabs']['billing']['busname'];?>'>
			<input type='hidden' name='company_title' id='company_title' value=''>
			<input type='hidden' name='btn' id='btn' value='<?=$_SESSION['tabs']['billing']['Billing_Phone'];?>'>
			<input type='hidden' name='serv_address' id='serv_address' value='<?=$_SESSION['tabs']['customer']['Service_Address1'];?>'>
			<input type='hidden' name='serv_city' id='serv_city' value='<?=$_SESSION['tabs']['customer']['Service_City'];?>'>
			<input type='hidden' name='serv_state' id='serv_state' value='<?=$_SESSION['tabs']['state']['abbrev'];?>'>
			<input type='hidden' name='serv_zip' id='serv_zip' value='<?=$_SESSION['tabs']['customer']['Service_Zip5'];?>'>
			<input type='hidden' name='serv_county' id='serv_county' value=''>
			<input type='hidden' name='bill_address' id='bill_address' value='<?=$_SESSION['tabs']['billing']['Billing_Address1'];?>'>
			<input type='hidden' name='bill_city' id='bill_city' value='<?=$_SESSION['tabs']['billing']['Billing_City'];?>'>
			<input type='hidden' name='bill_state' id='bill_state' value='<?=$_SESSION['tabs']['billing']['Billing_State'];?>'>
			<input type='hidden' name='bill_zip' id='bill_zip' value='<?=$_SESSION['tabs']['billing']['Billing_Zip5'];?>'>
			<input type='hidden' name='acct_type' id='acct_type' value='<?=$acct_type;?>'>
			<input type='hidden' name='acct_num' id='acct_num' value='<?=$_SESSION['tabs']['account']['Account_Number'];?>'>
			<input type='hidden' name='meter_num' id='meter_num' value='<?=$_SESSION['tabs']['account']['accountno_meter'];?>'>
			<input type='hidden' name='rate_class' id='rate_class' value='<?=$_SESSION['tabs']['account']['Rate_Class'];?>'>
			<input type='hidden' name='lead_type' id='lead_type' value='<?=$_SESSION['tabs']['utility']['acct_type'];?>'>
			<input type='hidden' name='tpv_disclosureflag' id='tpv_disclosureflag' >
			<input type='hidden' name='submitflag' id='submitflag' >
			<input type='hidden' name='accept' id='accept' >
			<input type='hidden' name='auth' id='auth' >
			<input type='hidden' name='eflag' id='eflag' >
			<input type='hidden' name='eflagid' id='eflagid' >
			<input type='hidden' name='endcall_id' id='endcall_id'>

</form>
          </div>
    </div>
<?php
        require_once 'includes/statusbar.php';
?>
</div>
<?php
        require_once 'includes/footer.php';




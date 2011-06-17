<?php

///////////////////

Project Name - Supply Side Project
Developer    - Mahendran
Module Name  - Setting up Billing
Date         - 04/21/2011

////////////////////////


require_once 'includes/main.php';

//
// make sure previous tabs are completed
//
require_once 'includes/check_state.php';
require_once 'includes/check_util.php';
require_once 'includes/check_offer.php';
require_once 'includes/check_customer.php';

require_once 'EP/Model/StateMapper.php';
require_once 'EP/Model/PartnerMapper.php';
require_once 'EP/HTML/Form/Widget/RadioBool.php';

//Ini_set('display_errors', 1 );

$Session_State= null;
if(isset($_SESSION['tabs']['state']['state']))
{
	$Session_State = $_SESSION['tabs']['state']['state'];
}

$copy_billing= null;
if(isset($_GET['copy_billing']))
{
	$copy_billing = $_GET['copy_billing'];
}

$Session_resbus= null;
if(isset($_SESSION['tabs']['utility']['resbus']))
{
	$Session_resbus=$_SESSION['tabs']['utility']['resbus'];
}


$Session_email_addr= null;
if(isset($_SESSION['tabs']['billing']['email_addr']))
{
	$Session_email_addr=$_SESSION['tabs']['billing']['email_addr'];
}

$Session_billing_phone_number_prefix= null;
if(isset($_SESSION['tabs']['billing']['billing_phone_number_prefix']))
{
	$Session_billing_phone_number_prefix=$_SESSION['tabs']['billing']['billing_phone_number_prefix'];
}

$Session_billing_phone_number_first= null;
if(isset($_SESSION['tabs']['state']['billing_phone_number_first']))
{
	$Session_billing_phone_number_first=$_SESSION['tabs']['billing']['billing_phone_number_first'];
}

$Session_billing_phone_number_last= null;
if(isset($_SESSION['tabs']['billing']['billing_phone_number_last']))
{
	$Session_billing_phone_number_last=$_SESSION['tabs']['billing']['billing_phone_number_last'];
}

$Session_alternate_phone_number_prefix= null;
if(isset($_SESSION['tabs']['billing']['alternate_phone_number_prefix']))
{
	$Session_alternate_phone_number_prefix=$_SESSION['tabs']['billing']['alternate_phone_number_prefix'];
}

$Session_alternate_phone_number_first= null;
if(isset($_SESSION['tabs']['billing']['alternate_phone_number_first']))
{
	$Session_alternate_phone_number_first=$_SESSION['tabs']['billing']['alternate_phone_number_first'];
}

$Session_alternate_phone_number_last= null;
if(isset($_SESSION['tabs']['billing']['alternate_phone_number_last']))
{
	$Session_alternate_phone_number_last=$_SESSION['tabs']['billing']['alternate_phone_number_last'];
}

$Session_busname= null;
if(isset($_SESSION['tabs']['billing']['busname']))
{
	$Session_busname=$_SESSION['tabs']['billing']['busname'];
}

$Session_enroll_tel= null;
if(isset($_SESSION['enroll_tel']))
{
	$Session_enroll_tel=$_SESSION['enroll_tel'];
}

$Session_web_addr= null;
if(isset($_SESSION['web_addr']))
{
	$Session_web_addr=$_SESSION['web_addr'];
}

if($Session_State=='')
{
	$header_prev = "Location: $base_url/myinbound/select_state.php";
	header($header_prev);
}

$Billing_Address1=null;
if(isset($_SESSION['tabs']['customer']['Service_Address1']))
{
	$Billing_Address1=$_SESSION['tabs']['customer']['Service_Address1'];
}

$Session_Affinity= null;
if(isset($_SESSION['tabs']['offer']['affinity']))
{
	$Session_Affinity = $_SESSION['tabs']['offer']['affinity'];
}



$register = '';
if ( isset( $_SESSION['register']))
{
	$register = $_SESSION['register'];
}


if(!empty($_POST))
{
	$billing_phone=$_POST['phone_number_prefix']."".$_POST['phone_number_first']."".$_POST['phone_number_last'];
	$register->setBaddr1(trim($_POST['Billing_Address1']));
	$register->setBaddr2(trim($_POST['Billing_Address2']));
	//$register->setBaddr3(trim($_POST['']));
	$register->setBcity(trim($_POST['Billing_City']));
	$register->setBstate(trim($_POST['Billing_State']));
	$register->setBzip5(trim($_POST['Billing_Zip5']));
	$register->setBzip4(trim($_POST['Billing_Zip4']));
	$register->setBillphone(trim($billing_phone));
	$register->setBusname(trim($_POST['busname']));
	$register->setEmail(trim($_POST['email_addr']));


	$terrobj = getUtil($_SESSION['utility']['code']);
	$terr = $terrobj->utility;
	$countyPrefix = trim($terrobj->table_code);

	if($countyPrefix == 'no')
	{
		$bcounty = $_POST['Billing_State'];
	}
	else
	{
		$bcounty = (isset($_POST['Billing_Zip5']))?getCounty($countyPrefix,$_POST['Billing_Zip5']):'No County';
	}
	$bcounty = strtoupper(PadString($bcounty,20));
	$register->setBcounty(trim($bcounty));

	$mapper = new EP_Model_RegisterMapper();
	$result = $mapper->save( $register );

	if ( !$result )
	{
		die( 'error saving' );
	}

	$_SESSION['tabs']['billing']['Billing_Address1'] = $_POST['Billing_Address1'];
	$_SESSION['tabs']['billing']['Billing_Address2'] = $_POST['Billing_Address2'];
	$_SESSION['tabs']['billing']['Billing_City'] = $_POST['Billing_City'];
	$_SESSION['tabs']['billing']['Billing_State'] = $_POST['Billing_State'];
	$_SESSION['tabs']['billing']['Billing_Phone'] = $billing_phone;
	$_SESSION['tabs']['billing']['Billing_Zip5'] = $_POST['Billing_Zip5'];
	$_SESSION['tabs']['billing']['Billing_Zip4'] = $_POST['Billing_Zip4'];
	$_SESSION['tabs']['billing']['phone_number_prefix'] = $_POST['phone_number_prefix'];
	$_SESSION['tabs']['billing']['phone_number_first'] = $_POST['phone_number_first'];
	$_SESSION['tabs']['billing']['phone_number_last'] = $_POST['phone_number_last'];
	$_SESSION['tabs']['billing']['email_addr'] = $_POST['email_addr'];
	$_SESSION['tabs']['billing']['busname'] = $_POST['busname'];





	$_SESSION['register'] = $register;


	if ( isset($_POST['next_url']) && $_POST['next_url'] != '' )
	{
		$header = "Location: $base_url/myinbound/" . $_POST['next_url'];
		header( $header );
	}
	else
	{
		$_SESSION['messages'] = 'The information has been saved';
	}

}




if($copy_billing==1)
{
	$Billing_Address1=null;
	if(isset($_SESSION['tabs']['customer']['Service_Address1']))
	{
		$Billing_Address1=$_SESSION['tabs']['customer']['Service_Address1'];
	}

	$Billing_Address2=null;
	if(isset($_SESSION['tabs']['customer']['Service_Address2']))
	{
		$Billing_Address2=$_SESSION['tabs']['customer']['Service_Address2'];
	}

	$Billing_City=null;
	if(isset($_SESSION['tabs']['customer']['Service_City']))
	{
		$Billing_City=$_SESSION['tabs']['customer']['Service_City'];
	}

	$Billing_Zip5=null;
	if(isset($_SESSION['tabs']['customer']['Service_Zip5']))
	{
		$Billing_Zip5=$_SESSION['tabs']['customer']['Service_Zip5'];
	}

	$Billing_Zip4=null;
	if(isset($_SESSION['tabs']['customer']['Service_Zip4']))
	{
		$Billing_Zip4=$_SESSION['tabs']['customer']['Service_Zip4'];
	}

	$Billing_phone_number_prefix=null;
	if(isset($_SESSION['tabs']['customer']['Service_phone_number_prefix']))
	{
		$Billing_phone_number_prefix=$_SESSION['tabs']['customer']['Service_phone_number_prefix'];
	}

	$Billing_phone_number_first=null;
	if(isset($_SESSION['tabs']['customer']['Service_phone_number_first']))
	{
		$Billing_phone_number_first=$_SESSION['tabs']['customer']['Service_phone_number_first'];
	}

	$Billing_phone_number_last=null;
	if(isset($_SESSION['tabs']['customer']['Service_phone_number_last']))
	{
		$Billing_phone_number_last=$_SESSION['tabs']['customer']['Service_phone_number_last'];
	}

	$Billing_state=null;
	if(isset($_SESSION['st_abbrev']))
	{
		$Billing_state=$_SESSION['st_abbrev'];
	}
}
else
{
	$Billing_Address1=null;
	if(isset($_SESSION['tabs']['billing']['Billing_Address1']))
	{
		$Billing_Address1=$_SESSION['tabs']['billing']['Billing_Address1'];
	}

	$Billing_Address2=null;
	if(isset($_SESSION['tabs']['billing']['Billing_Address2']))
	{
		$Billing_Address2=$_SESSION['tabs']['billing']['Billing_Address2'];
	}

	$Billing_City=null;
	if(isset($_SESSION['tabs']['billing']['Billing_City']))
	{
		$Billing_City=$_SESSION['tabs']['billing']['Billing_City'];
	}

	$Billing_Zip5=null;
	if(isset($_SESSION['tabs']['billing']['Billing_Zip5']))
	{
		$Billing_Zip5=$_SESSION['tabs']['billing']['Billing_Zip5'];
	}

	$Billing_Zip4=null;
	if(isset($_SESSION['tabs']['billing']['Billing_Zip4']))
	{
		$Billing_Zip4=$_SESSION['tabs']['billing']['Billing_Zip4'];
	}

	$Billing_phone_number_prefix=null;
	if(isset($_SESSION['tabs']['billing']['phone_number_prefix']))
	{
	$Billing_phone_number_prefix=$_SESSION['tabs']['billing']['phone_number_prefix'];
	}

	$Billing_phone_number_first=null;
	if(isset($_SESSION['tabs']['billing']['phone_number_first']))
	{
		$Billing_phone_number_first=$_SESSION['tabs']['billing']['phone_number_first'];
	}

	$Billing_phone_number_last=null;
	if(isset($_SESSION['tabs']['billing']['phone_number_last']))
	{
		$Billing_phone_number_last=$_SESSION['tabs']['billing']['phone_number_last'];
	}

	$Billing_state=null;

	if(isset($_SESSION['tabs']['billing']['Billing_State']))
	{
		$Billing_state=$_SESSION['tabs']['billing']['Billing_State'];
	}
}

    require_once 'includes/header.php';


?>
<script type="text/javascript" src="../myinbound/scripts/select_billing.js"></script>

</head>

<body onLoad="global_loadsetting('<?=$copy_billing;?>','<?=$Billing_Address1;?>','<?=$Session_email_addr;?>','<?=$Session_busname;?>','<?=$Session_resbus;?>','<?=$Session_State;?>','<?=$Session_billing_phone_number_prefix;?>','<?=$Session_alternate_phone_number_prefix;?>')">

<div class="yui3-g" id="container" >

<?php
	$page = basename( __FILE__ );
        require_once 'includes/nav.php';
?>
        <div class="yui3-u" id="main">

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

<form id="inbound_billing" name="inbound_billing" method="post" action="#" autocomplete="off">
<div class="whiteblock">
			<?
				if($copy_billing!=1)
				{
			?>
				<div id="prebillingsection1" >
					<div style="height:25px;width:100%; float: left;">
						<span class="formdata">
								May I please have your billing address?
								<input type=radio name="billing1" value="yes" id="Radio1" onClick="if(this.checked)toggleBilling('block',<?=$Session_resbus;?>);" <?if($Billing_Address1!=''){echo 'Checked';}?>>Yes
		  						<input type=radio name="billing1" value="no" id="Radio2" onClick="if(this.checked)toggleBilling('none',<?=$Session_resbus;?>);" <?if($Billing_Address1=='' && $copy_billing==''){echo '';}?>>No
						</span>
					</div>
				</div>
			<?
				}
			?>
				<div id="prebillingsection2" style="height:150px;width:100%; float: left;display:none;">
						<BR><span class="formdata">Unfortunately we need to know your billing address before we can proceed with your enrollment.  Once you are willing to provide this information, please call us back at  <?=$Session_enroll_tel;?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>.<BR><BR>Is there anything else I can do for you today?
								<input name="servicefaq" id="servicefaq1" type="radio" value="1" onClick="masterchecksetting10(this)">Yes
								<input name="servicefaq" id="servicefaq2" type="radio" value="0" onClick="masterchecksetting10(this)">No
						</span>
								<div id="section10b" style="display: none;"><span class="formdata"><p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</font></i></span></div>
								<div id="section10a" style="display: none;"><span class="formdata">Thank you for calling Energy Plus and have a nice day.<p class="rep_note">NOTE TO TSR: AUTO DISPO #9: Caller Refused to provide billing address</p></div>

				</div>
				<div id="billingsection" style="display:none;">
					<div style="height:70px;width:100%; float: left;">
						<p bgcolor="#666666" class="formheader"><font color="#FFFFFF">
						<strong><span id="addpre"></span> Billing Address:</strong></font></p>
						<span class='rep_note'>READ BACK WHAT WAS ALREADY CAPTURED AND MAKE ANY NECESSARY CHANGES</span>

					</div>
					<div style="height:35px;width:30%; float: left;"><span class="formdata">Billing Address1:<font color="red">*</font>&nbsp;&nbsp;&nbsp;</span></div>
					<div style="height:35px;width:70%; float: left;">
						<span class="formdata">
		    					<input type="text" name="Billing_Address1"  autocomplete ="off" maxlength="64"  id="Billing_Address1" value="<?=$Billing_Address1;?>"><div id="billaddrcheck1"></div>
						</span>
					</div>
					<div style="height:35px;width:30%; float: left;"><span class="formdata">Billing Address2:&nbsp;&nbsp;&nbsp;</span></div>
					<div style="height:35px;width:70%; float: left;">
						<span class="formdata">
		    					<input type="text" name="Billing_Address2" maxlength="64" id="Billing_Address2" value="<?=$Billing_Address2;?>" ><div id="billaddrcheck2"></div>
						</span>
					</div>

					<div style="height:35px;width:30%; float: left;"><span class="formdata">Billing City:<font color="red">*</font>&nbsp;&nbsp;&nbsp;</span></div>
					<div style="height:35px;width:70%; float: left;">
						<span class="formdata">
		    					<input type="text" name="Billing_City" maxlength="20"  id="Billing_City" value="<?=$Billing_City;?>" ><div id="billcitycheck"></div>
						</span>
					</div>
					<div style="height:35px;width:30%; float: left;"><span class="formdata">Billing State:<font color="red">*</font>&nbsp;&nbsp;&nbsp;</span></div>
					<div style="height:35px;width:70%; float: left;">
						<span class="formdata">
								<select name="Billing_State" id="Billing_State">
<?php
$usstates = getList('usstates');
while($usstate = mysql_fetch_object($usstates))
{
	$selected = ($usstate->abbrev == $Billing_state)?'selected="selected"':'';
?>
									<option value="<?php echo $usstate->abbrev.'" '.$selected.'>'.$usstate->name.' - '.$usstate->abbrev;?></option>
<?php
}
?>
								</select><div id="billstatecheck"></div>

						</span>
					</div>
					<div style="height:35px;width:30%; float: left;"><span class="formdata">Billing Zip:<font color="red">*</font>&nbsp;&nbsp;&nbsp;</span></div>
					<div style="height:35px;width:70%; float: left;">
						<span class="formdata">
		    					<input name="Billing_Zip5" id="Billing_Zip5"  type="text" size="8" maxlength="5" value="<?=$Billing_Zip5;?>" > -
							<input name="Billing_Zip4" id="Billing_Zip4"  type="text" size="8" value="<?=$Billing_Zip4;?>" maxlength="4" >
							<div id="billzipcheck"></div>
						</span>
					</div>
					<div style="height:35px;width:30%; float: left;"><span class="formdata">Billing Phone Number:
							<font color="red">*</font>&nbsp;&nbsp;&nbsp;</span></div>
					<div style="height:35px;width:70%; float: left;">
						<span class="formdata">
								(
		        					<input name="phone_number_prefix" type="text" id="phone_number_prefix"  size="4" maxlength="3" value="<?=$Billing_phone_number_prefix;?>" >)
								<input name="phone_number_first" type="text" id="phone_number_first"  size="4" maxlength="3" value="<?=$Billing_phone_number_first;?>" > -
								<input name="phone_number_last" type="text" id="phone_number_last"  size="6" maxlength="4" value="<?=$Billing_phone_number_last;?>" ><div id="billphonecheck"></div>
						</span>
					</div>
				</div>
				<div id="section2c" style="display: none;">
					<div style="height:35px;width:100%; float: left;">
						<span class="formdata">
							May I have your email address?&nbsp;<input name="email"  type="radio" value="0" onclick="masterchecksetting2(this)" Checked>Yes&nbsp;<input name="email" type="radio" value="1" onclick="masterchecksetting2(this)" >No
						</span>
					</div>

					<div id="section2b" style="display: none;">
							<span class="formdata">
								Energy Plus only uses your e-mail address to communicate with you about your account.  We respect your privacy and will not share your address with any other party. <BR><BR> With that in mind, may I have your e-mail address?&nbsp;<input name="email1" type="radio" value="1" onclick="masterchecksetting3(this,<?=$_SESSION['tabs']['state']['state'];?>)">&nbsp;&nbsp;Yes&nbsp;<input name="email1" type="radio" value="0" onclick="masterchecksetting3(this,<?=$_SESSION['tabs']['state']['state'];?>)">&nbsp;&nbsp;No
							</span>
						<div style="height:50px;width:100%; float: left;">
								<div id="section3b" style="height:50px;width:100%; float: left;display: none;"><span class="formdata"><p class="rep_note">CAPTURE AND CONFIRM E-MAIL BACK TO CALLER</p></span></div>
								<div id="section3a" style="height:50px;width:100%; float: left;display: none;"><span class="formdata"><BR>That is fine, we can continue without it.</span></div>
						</div>
					</div>
				</div>
				<div id="section2a" style="display: none;">
					<div style="height:35px;width:30%; float: left;"><span class="formdata">Email Address:<font color="red">*</font></span></div>
					<div style="height:35px;width:70%; float: left;">
						<span class="formdata">
									<input type="text" name="email_addr" id="email_addr"  maxlength="150" class="inp_h" value="<?=$Session_email_addr;?>" >
									<input type="hidden" name="r_email_addr" size="1" value="Email address must given">
									<input type="hidden" name="e_email_addr" size="1" value="Supplied Email address must be valid">
						</span>
					</div>
				</div>
<?
//echo $_SESSION['state'];
if($_SESSION['tabs']['state']['state']==3)
{
?>
				<div id="section_phone" style="display: none;">
						<div style="height:35px;width:50%; float: left;"><span class="formdata">May I please have the phone number of the primary account holder?:<font color="red">*</font></div>
						<div style="height:35px;width:50%; float: left;">
						<span class="formdata">
							<input  name="phonechoice" id="phonechoice"  type="radio" value="1" onclick="global_checksetting(this,'phone_section_yes','phone_section_no','','')" <?if($Session_billing_phone_number_prefix!='' && $Session_billing_phone_number_first!='' && $Session_billing_phone_number_last!=''){echo 'Checked';}?> /> Yes&nbsp;
							<input  name="phonechoice" id="phonechoice"  type="radio" value="0" onclick="global_checksetting(this,'phone_section_yes','phone_section_no','','')" <?if($Session_billing_phone_number_prefix==''){echo 'Checked';}?>/> No&nbsp;
						</span>
						</div>
				</div>

				<div id="phone_section_yes" style="display:none;">
					<div style="height:35px;width:30%; float: left;"><span class="formdata">Phone Number:<font color="red">*</font></span></div>
					<div style="height:35px;width:70%; float: left;">
						       (<input name="billing_phone_number_prefix" type="text" id="billing_phone_number_prefix"  value="<?=$Session_billing_phone_number_prefix;?>" class="inp_h" size="4" maxlength="3">)
							<input name="billing_phone_number_first" type="text" id="billing_phone_number_first"  value="<?=$Session_billing_phone_number_first;?>" class="inp_h" size="4" maxlength="3"> -
							<input name="billing_phone_number_last" type="text" id="billing_phone_number_last" value="<?=$Session_billing_phone_number_last;?>" class="inp_h" size="6" maxlength="4">
							<input type="hidden" name="c_billing_phone_number_prefix;billing_phone_number_first;billing_phone_number_last" value="3,3,4,Missing/Invalid Service Phone" />
					</div>
				</div>

				<div id="phone_section_no" style="height:200px;width:100%;display:none;float: left;">
							<div style="height:50px;width:100%;"
								<span class="formdata">
									Unfortunately we need to know your phone number before we can proceed with your enrollment. Once you are willing to provide this information, please call us back at  <?=$Session_enroll_tel;?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>.
								</span>
							</div>
							<div style="height:50px;width:100%;">
									<span class="formdata"><BR><BR>Is there anything else I can do for you today?
										<input name="busfaq" type="radio" value="1" onclick="global_checksetting(this,'bphone_section1_yes','bphone_section1_no','disp','12')">Yes
										<input name="busfaq" type="radio" value="0" onclick="global_checksetting(this,'bphone_section1_yes','bphone_section1_no','','')">No
									</span>
							</div>
					<div id="bphone_section1_yes" style="height:50px;width:100%;display: none;"><BR><BR><p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p></div>
					<div id="bphone_section1_no" style="height:50px;width:100%;display: none;"><BR><BR>Thank you for calling Energy Plus and have a nice day.<BR><BR><p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D #12: Caller Refused to provide primary phone number</p></div>
				</div>

				<div id="section8c1" style="display:none;">
					<div style="height:35px;width:40%; float: left;"><span class="formdata">May I please have the alternative phone number?:<font color="red">*</font></span></div>
					<div style="height:35px;width:60%; float: left;">(
		  					<input name="alternate_phone_number_prefix" type="text" id="alternate_phone_number_prefix"  value="<?=$Session_alternate_phone_number_prefix;?>" class="inp_h" size="4" maxlength="3">)
							<input name="alternate_phone_number_first" type="text" id="alternate_phone_number_first"  value="<?=$Session_alternate_phone_number_first;?>" class="inp_h" size="4" maxlength="3"> -
							<input name="alternate_phone_number_last" type="text" id="alternate_phone_number_last"  value="<?=$Session_alternate_phone_number_last;?>" class="inp_h" size="6" maxlength="4">
					</div>
				</div>
<?
}
?>
</div>
<?
if($Session_resbus==1 )
{
?>
	<?
		if($Session_State==3)
		{
	?>
				<div>
					<div style="width:100%; float: left;">
						<span class="formdata">
							Please confirm that you are the principal business owner or an Authorized representative of the Business?
							<input  name="businessowner"  type="radio" value="1"  onclick="global_checksetting(this,'principal_section_yes','principal_section_no','','')" /> Yes&nbsp;
							<input  name="businessowner" type="radio" value="0"  onclick="global_checksetting(this,'principal_section_yes','principal_section_no','disp',89)" /> No&nbsp;

						</span>
					</div>
					<div id="principal_section_yes" style="display:none;">
					</div>
					<div id="principal_section_no" style="height:100px;width:100%; float: left;display:none;">
						<p class="formdata">
								Unfortunately we need to know the business owner before we can proceed with your enrollment.Once you are willing to provide this information, please call us back at  <?=$Session_enroll_tel;?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>.
						</p>
						<span class="formdata">
								Is there anything else I can do for you today?
								<input name="principalbusfaq" id="principalbusfaq" type="radio" value="1" onclick="global_checksetting(this,'principal_section1_yes','principal_section1_no','cust','')">Yes
								<input name="principalbusfaq" id="principalbusfaq" type="radio" value="0" onclick="global_checksetting(this,'principal_section1_yes','principal_section1_no','disp',89)">No
						</span>
						<div id="principal_section1_yes" style="height:50px;width:100%; float: left;display: none;">
									<p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p>
						</div>
						<div id="principal_section1_no" style="height:50px;width:100%; float: left;display: none;">
									<p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D #89: TX Not-Authorized Business Owner</p>
						</div>
					</div>
				</div>
				<span>&nbsp;</span>
	<?
		}
	?>
				<div id="business" style="width:100%;float:left;display:none;">
					<div style="height:35px;width:100%; float: left;">
						<span class="formdata">
							May I please have your business name?
							<input  name="businesschoice"  type="radio" value="1"  onclick="global_checksetting(this,'business_section_yes','business_section_no','','')" Checked/> Yes&nbsp;
							<input  name="businesschoice" type="radio" value="0"  onclick="global_checksetting(this,'business_section_yes','business_section_no','disp',24)" /> No&nbsp;
						</span>
					</div>
					<div id="business_section_yes" style="display:none;">
						<div style="height:35px;width:30%; float: left;"><span class="formdata">Business Name?</span></div>
						<div style="height:35px;width:70%; float: left;"><input type="text" name="busname" id="busname" maxlength="150" value="<?=$Session_busname;?>" class="inp_h"></div><div id="busnamecheck"></div>
					</div>
					<div id="business_section_no" style="width:100%; float: left;display:none;">
						<p class="formdata">
								Unfortunately we need to know your Business name before we can proceed with your enrollment.Once you are willing to provide this information, please call us back at  <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>.
						</p>
						<span class="formdata">
								Is there anything else I can do for you today?
								<input name="busfaq" id="busfaq" type="radio" value="1" onclick="global_checksetting(this,'business_section1_yes','business_section1_no','cust','')">Yes
								<input name="busfaq" id="busfaq" type="radio" value="0" onclick="global_checksetting(this,'business_section1_yes','business_section1_no','disp',24)">No
						</span>
						<div id="business_section1_yes" style="height:50px;width:100%; float: left;display: none;">
									<p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p>
						</div>
						<div id="business_section1_no" style="height:50px;width:100%; float: left;display: none;">
									<p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D #24: Caller Refused to provide business name</p>
						</div>
					</div>
				</div>

<?
}
?>
				<div>
						<input type="button" class="ib_button" name="btn_continue" id="btn_continue" value="Save and Continue" onclick="stepval()" >
						<input type="button" class="ib_button" name="btn_save" id="btn_save" value="Save" onclick="stepval(this)" >
						<input type="button" class="ib_button" name="btn_log_dispo" id="btn_log_dispo" value="Log Dispo" onclick="logDispo(this)" disabled>
						<input type='hidden' name='eflag' id='eflag' >
						<input type='hidden' name='eflagid' id='eflagid' >
						<input type='hidden' name='next_url' id='next_url' value="select_account.php" >
						<input type='hidden' name='resbus' id='resbus' value='<?=$Session_resbus;?>'>
						<input type='hidden' name='hid_billing' id='hid_billing' value=''>
						<input type='hidden' name='hid_business' id='hid_business' value=''>
						<input type='hidden' name='hid_resbus' id='hid_resbus' value='<?=$Session_resbus;?>'>
						<input type='hidden' name='hid_state' id='hid_state' value='<?=$Session_State;?>'>
						<input type='hidden' name='hid_busowner' id='hid_busowner' value='0'>


				</div>
</form>
<SCRIPT TYPE="text/javascript">
<!--
autojump('phone_number_prefix', 'phone_number_first', 3);
autojump('phone_number_first', 'phone_number_last', 3);
//-->
</SCRIPT>
</div>
<?php
        require_once 'includes/statusbar.php';
?>
</div>
<?php
        require_once 'includes/footer.php';
?>

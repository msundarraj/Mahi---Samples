<?php


//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Customer Module//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////

require_once 'includes/main.php';

//
// make sure previous tabs are completed
//
require_once 'includes/check_state.php';
require_once 'includes/check_util.php';
require_once 'includes/check_offer.php';

require_once 'EP/Model/StateMapper.php';
require_once 'EP/Model/PartnerMapper.php';
require_once 'EP/HTML/Form/Widget/RadioBool.php';

ini_set('display_errors', 0 );


//print_r($_SESSION);


$Session_State= null;
if(isset($_SESSION['tabs']['state']['state']))
{
	$Session_State = $_SESSION['tabs']['state']['state'];
}




$Session_utility_code= null;
if(isset($_SESSION['utility']['code']))
{
	$Session_utility_code = $_SESSION['utility']['code'];
}

$session_abbrev= null;
if(isset($_SESSION['tabs']['state']['abbrev']))
{
$session_abbrev=$_SESSION['tabs']['state']['abbrev'];
}

$Session_resbus= null;
if(isset($_SESSION['tabs']['utility']['resbus']))
{
	$Session_resbus = $_SESSION['tabs']['utility']['resbus'];
}

$Session_State_fname = null;

if(isset($_SESSION['tabs']['state']['fname']))
{
	$Session_State_fname = $_SESSION['tabs']['state']['fname'];
}

$Session_State_lname = null;

if(isset($_SESSION['tabs']['state']['lname']))
{
	$Session_State_lname = $_SESSION['tabs']['state']['lname'];
}


$Session_Service_fname = $Session_State_fname;

if(isset($_SESSION['tabs']['customer']['first_name']))
{
	$Session_Service_fname = $_SESSION['tabs']['customer']['first_name'];
}

$Session_Service_middle_initial = null;
if(isset($_SESSION['tabs']['customer']['middle_initial']))
{
	$Session_Service_middle_initial = $_SESSION['tabs']['customer']['middle_initial'];
}


$Session_Service_lname = $Session_State_lname;

if(isset($_SESSION['tabs']['customer']['last_name']))
{
	$Session_Service_lname = $_SESSION['tabs']['customer']['last_name'];
}





$Session_Service_suffix=null;
if(isset($_SESSION['tabs']['customer']['Suffix']))
{
	$Session_Service_suffix = $_SESSION['tabs']['customer']['Suffix'];
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

$Session_Service_City= null;
if(isset($_SESSION['tabs']['customer']['Service_City']))
{
	$Session_Service_City = $_SESSION['tabs']['customer']['Service_City'];
}


$Session_Service_Zip5= null;
if(isset($_SESSION['tabs']['customer']['Service_Zip5']))
{
	$Session_Service_Zip5 = $_SESSION['tabs']['customer']['Service_Zip5'];
}


$Session_Service_Zip4= null;
if(isset($_SESSION['tabs']['customer']['Service_Zip4']))
{
	$Session_Service_Zip4 = $_SESSION['tabs']['customer']['Service_Zip4'];
}

$Session_Service_phone_number_prefix= null;
if(isset($_SESSION['tabs']['customer']['Service_phone_number_prefix']))
{
	$Session_Service_phone_number_prefix = $_SESSION['tabs']['customer']['Service_phone_number_prefix'];
}

$Session_Service_phone_number_first= null;
if(isset($_SESSION['tabs']['customer']['Service_phone_number_first']))
{
	$Session_Service_phone_number_first = $_SESSION['tabs']['customer']['Service_phone_number_first'];
}

$Session_Service_phone_number_last= null;
if(isset($_SESSION['tabs']['customer']['Service_phone_number_last']))
{
	$Session_Service_phone_number_last = $_SESSION['tabs']['customer']['Service_phone_number_last'];
}


$Session_spfname = $Session_State_fname;
if(isset($_SESSION['tabs']['customer']['spfname']))
{
	$Session_spfname = $_SESSION['tabs']['customer']['spfname'];
}

$Session_splname = $Session_State_lname;
if(isset($_SESSION['tabs']['customer']['splname']))
{
	$Session_splname = $_SESSION['tabs']['customer']['splname'];
}


$Session_web_addr= null;
if(isset($_SESSION['web_addr']))
{
	$Session_web_addr=$_SESSION['web_addr'];
}

$Session_Affinity= null;
if(isset($_SESSION['tabs']['offer']['affinity']))
{
	$Session_Affinity = $_SESSION['tabs']['offer']['affinity'];
}


$Session_billingsame= null;
if(isset($_SESSION['tabs']['utility']['billingsame']))
{
	$Session_billingsame= 1;
}


if($Session_State=='')
{
	$header_prev = "Location: $base_url/myinbound/select_state.php";
	header($header_prev);
}

$register = '';
if ( isset( $_SESSION['register']))
{
	$register = $_SESSION['register'];
}

//echo $_SESSION['tabs']['customer']['Suffix'];
//echo $_SESSION['tabs']['customer']['Service_Address1'];

if ( !empty( $_POST ) )
{
		$servicephone=$_POST['Service_phone_number_prefix']."".$_POST['Service_phone_number_first']."".$_POST['Service_phone_number_last'];
		$register->setFirstName(trim($_POST['first_name']));
		$register->setMidInit(trim($_POST['middle_initial']));
		$register->setLastName(trim($_POST['last_name']));
 		$register->setSuffix($_POST['Suffix']);
		$register->setAddr1(trim($_POST['Service_Address1']));
		$register->setAddr2(trim($_POST['Service_Address2']));
		$register->setState(trim($_POST['Service_State']));
		$register->setServicephone(trim($servicephone));
		$register->setCity(trim($_POST['Service_City']));
		$register->setZip5(trim($_POST['Service_Zip5']));
		$register->setZip4(trim($_POST['Service_Zip4']));

		if(isset($_SESSION['tabs']['account']['Account_Number']) && $Session_State==4)
		{
			$register->setAccount(trim($_SESSION['tabs']['account']['Account_Number']));
		}

		if($_POST['hid_spouse']==1)
		{
			$register->setSpfname(trim($_POST['spfname']));
			$register->setSplname(trim($_POST['splname']));
		}
		else
		{
			$register->setSpfname('');
			$register->setSplname('');
		}

		if($Session_State!=3)
		{
			$terr_code = $Session_utility_code;
			$terrobj = getUtil($terr_code);

			$terr = $terrobj->utility;
			$countyPrefix = trim($terrobj->table_code);
			if($countyPrefix == 'no')
			{
				$county = $session_abbrev;
			}
			else
			{
				 $county = (isset($_POST['Service_Zip5']))?getCounty($countyPrefix,$_POST['Service_Zip5']):'No County';
			}

			$county = strtoupper(PadString($county,20));
			$register->setCounty(trim($county));
			///////////////////////////////////////
			$st_abbrev = isset($session_abbrev) ? $session_abbrev : NULL;
			if($st_abbrev && $st_abbrev == 'CT')
			{
				$iso = $session_abbrev;
			}
			else if($st_abbrev && ($st_abbrev == 'PA' || $st_abbrev == 'NJ' || $st_abbrev == 'MD' || $st_abbrev == 'IL'))
			{
				$iso = $terrobj->abbrev;
			}
			else
			{
				if($terr_code == '01')  // if coned  look up iso on table5
					{
						$sql = sprintf("select code from iso where zip = '%s'",$_POST['Service_Zip5']);
						$res = mysql_query($sql,$link);
						if(mysql_num_rows($res))
						{
							$row = mysql_fetch_row($res);
							$iso = trim($row[0]);
							$iso = substr($iso,0,1);
						}
						else
						{
							$iso = 'J';
						}
				}
				else if($terr_code != '02') // else if not natgrid
				{
						$iso = getiso($countyPrefix,$_POST['Service_Zip5']);
				}
				else
				{
						$iso = '';
				}
			}

			$register->setIso(trim($iso));
		}

		$mapper = new EP_Model_RegisterMapper();
		$result = $mapper->save( $register );

		if ( !$result )
		{
			die( 'error saving' );
		}

		$_SESSION['register'] = $register;


		if($_POST['state_fname']!='')
		{
			$_SESSION['tabs']['state']['fname'] = trim($_POST['state_fname']);
		}

		if($_POST['state_lname']!='')
		{
			$_SESSION['tabs']['state']['lname'] = trim($_POST['state_lname']);
		}

		$_SESSION['tabs']['customer']['hid_spouse'] = $_POST['hid_spouse'];
		$_SESSION['tabs']['customer']['first_name'] = $_POST['first_name'];
		$_SESSION['tabs']['customer']['middle_initial'] = $_POST['middle_initial'];
		$_SESSION['tabs']['customer']['last_name'] = $_POST['last_name'];
		$_SESSION['tabs']['customer']['Suffix'] = $_POST['Suffix'];
		$_SESSION['tabs']['customer']['Service_Address1'] = $_POST['Service_Address1'];
		$_SESSION['tabs']['customer']['Service_Address2'] = $_POST['Service_Address2'];
		$_SESSION['tabs']['customer']['Service_City'] = $_POST['Service_City'];
		$_SESSION['tabs']['customer']['Service_Zip5'] = $_POST['Service_Zip5'];
		$_SESSION['tabs']['customer']['Service_Zip4'] = $_POST['Service_Zip4'];
		$_SESSION['tabs']['customer']['Service_phone_number_prefix'] = $_POST['Service_phone_number_prefix'];
		$_SESSION['tabs']['customer']['Service_phone_number_first'] = $_POST['Service_phone_number_first'];
		$_SESSION['tabs']['customer']['Service_phone_number_last'] = $_POST['Service_phone_number_last'];
		$_SESSION['tabs']['customer']['Service_phone'] = $servicephone;

		if($_POST['hid_spouse']==1)
		{
			$_SESSION['tabs']['customer']['spfname'] = $_POST['spfname'];
			$_SESSION['tabs']['customer']['splname'] = $_POST['splname'];
		}
		else
		{
			$_SESSION['tabs']['customer']['spfname'] = '';
			$_SESSION['tabs']['customer']['splname'] = '';

		}

		//echo $_SESSION['utility']['code'];




		//////////////////////////////////////////////
		// put the results into the session


		if($_POST['copy_billing']==1 || $Session_billingsame==1)
		{
			$copy_billing=1;
		}
		else
		{
			$copy_billing=0;
		}

		if ( isset($_POST['next_url']) && $_POST['next_url'] != '' )
		{
			$header = "Location: $base_url/myinbound/" . $_POST['next_url']."?copy_billing=".$copy_billing;
			header( $header );
		}
		else
		{
			$_SESSION['messages'] = 'The information has been saved';
		}
}

$cust_name1 = $Session_Service_fname;
$cust_name2 = $Session_Service_lname;
$cust_name_mid = null;
$cust_addr1 = null;
$cust_addr2 = null;
$cust_city = null;
$cust_state = null;
$cust_zip5 = null;
$cust_zip4 = null;
$cust_bname1 = null;
$cust_baddr1 = null;
$cust_baddr2 = null;
$cust_bcity = null;
$cust_bstate = null;
$cust_bzip = null;



// if its PA, we may have their account number from the PA utility search
// in the session that we can use to get their name/address info

// TODO: check that its a utility that has a lookup table, otherwise this query fails
if($Session_State == 4 && isset( $_SESSION['tabs']['utility']['account_id']) && isset( $_SESSION['utility']['abbrev']))
{
	$_SESSION['tabs']['account']['Account_Number'] =  $_SESSION['tabs']['utility']['account_id'];

	$sql = "SELECT name1, name2, addr1, addr2, city, state, zip,  baddr1, baddr2, bcity, bstate, bzip ";
	$sql .= "FROM " . strtolower( $_SESSION['utility']['abbrev']) . "_data WHERE account = ? ";

	// Fetch the utility record
	$db = EP_Util_Database::pdo_connect();
	$sth = $db->prepare( $sql );
	$sth->setFetchMode( PDO::FETCH_OBJ );
	$sth->execute( array( $_SESSION['tabs']['utility']['account_id'] ));
	$cus_rec = $sth->fetch();

	// $cus_qry = mysql_query($last_query,$link) or die(mysql_error().'--'.$sql);
	// while($cus_rec = mysql_fetch_object($cus_qry))
	if ( $cus_rec )
	{

		// the name field from the PA utility search has the whole name in name1 field
		// so we need to do some work to break it up

		$nameArr = explode( ' ', $cus_rec->name1 );
		$nameArrCount = count( $nameArr );
		switch ( $nameArrCount )
		{
			case 5:
			case 4:
			case 3:
				// check to see if middle initial
				if ( strlen($nameArr[1]) == 1 )
				{
					$cust_name1 = $nameArr[0];
					$cust_name_mid = $nameArr[1];
					$cust_name2 = null;
					for ( $i = 2; $i <= count( $nameArr ); $i++ )
					{
						$cust_name2 .= $nameArr[ $i ] . " ";
					}
				}
				else	// executive decision, let's put the rest into "last name" field
				{
					$cust_name1 = $nameArr[0];
					for ( $i = 1; $i <= count( $nameArr ); $i++ )
					{
						$cust_name2 .= $nameArr[ $i ] . " ";
					}
				}
				$cust_name2 = trim( $cust_name2 );
				break;
			default: // 2 names, hopefully first and last
				$cust_name1 = $nameArr[0];
				$cust_name2 = $nameArr[1];
				break;
		}

		$readonly="readonly";
		$cust_addr1 = $cus_rec->addr1;
		$cust_addr2 = $cus_rec->addr2;
		$cust_city = $cus_rec->city;
		$cust_state = $cus_rec->state;
		// $cust_zip5 = $cus_rec->zip;
		$cust_zip5 = substr( $cus_rec->zip, 0, 5 );
		$cust_zip4 = substr( $cus_rec->zip, 5, 9 );
		$cust_baddr1 = $cus_rec->baddr1;
		$cust_baddr2 = $cus_rec->baddr2;
		$cust_bcity = $cus_rec->bcity;
		$cust_bstate = $cus_rec->bstate;
		$cust_bzip = $cus_rec->bzip;
	}
}


if(($Session_State == 4) && ($cust_name1!=''))
{
	$Session_Service_fname='';
}

if(($Session_State == 4) && ($cust_name2!=''))
{
	$Session_Service_fname='';
}


if( $Session_State == 3 )
{
		$cust_addr1=null;
		if(isset($_SESSION['tabs']['utility']['auto_addr1']))
		{
		$cust_addr1=$_SESSION['tabs']['utility']['auto_addr1'];
		}

    		$cust_addr2=null;
		if(isset($_SESSION['tabs']['utility']['auto_addr2']))
		{
		$cust_addr2=$_SESSION['tabs']['utility']['auto_addr2'];
		}

		$cust_city=null;
		if(isset($_SESSION['tabs']['utility']['auto_city']))
		{
		$cust_city=$_SESSION['tabs']['utility']['auto_city'];
		}

 		$cust_state=null;
		if(isset($_SESSION['tabs']['utility']['auto_state']))
		{
		$cust_state=$_SESSION['tabs']['utility']['auto_state'];
		}

		$cust_zip5=null;
		if(isset($_SESSION['tabs']['utility']['auto_zip']))
		{
		$cust_zip5=$_SESSION['tabs']['utility']['auto_zip'];
		}

		$cust_zip4=null;
		if(isset($_SESSION['tabs']['utility']['auto_zip4']))
		{
		$cust_zip4=$_SESSION['tabs']['utility']['auto_zip4'];
		}
}



$strec = getRecord('states','id',$Session_State);
$_SESSION['st_abbrev'] = $strec->abbrev;
$zip_min=$strec->zip_min;
$zip_max=$strec->zip_max;

if($Session_State==3)
{
$proff_name = "Driver License";
}
else
{
$proff_name = "Utility Bill";
}

if($Session_State==3)
{
	$readonly="readonly";
}


    require_once 'includes/header.php';
?>
<script type="text/javascript" src="../js/validate.js"></script>
<script type="text/javascript" src="../myinbound/scripts/select_customer.js"></script>
</head>

<body onLoad="return load_globalsetting('<?=$cust_name1;?>','<?=$Session_Service_fname;?>','<?=$Session_Service_Address1;?>','<?=$cust_addr1;?>','<?=$Session_State;?>','<?=$Session_resbus;?>','<?=$Session_State_fname;?>','<?=$Session_State_lname;?>')">

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

<form id="inbound_customer" name="inbound_customer" method="post" action="#" autocomplete="off">

		<div id="section_firstname" >
		<?
			if($Session_State_fname =='' || $Session_State_lname=='')
			{
		?>
			<p class="formdata">May I please have your name?&nbsp;<input name="firstname" type="radio" value="1"  onclick="global_checksetting(this,'section_firstname_yes','section_firstname_no','','')" Checked>&nbsp;&nbsp;Yes
			<input name="firstname" type="radio" value="0"  onclick="global_checksetting(this,'section_firstname_yes','section_firstname_no','','')">&nbsp;&nbsp;No</p>
		<?
			}
		?>
		</div>
		<div id="section_firstname_yes" style="width:100%; float: left;display: none;">
				<div style="height:35px;width:30%; float: left;"><span class="formdata">First name of caller:<font color="red">*</font></span></div>
														<div style="height:35px;width:70%; float: left;">
																<span class="formdata">
																	<input id="state_fname" name="state_fname">
																</span>
												               </div>
												      		 <div style="height:35px;width:30%; float: left;"><span class="formdata">Last name of caller:<font color="red">*</font></span></div>
														 <div style="height:35px;width:70%; float: left;">
															<span class="formdata">
																	<input name="state_lname" id="state_lname">
															</span>
				</div>
		</div>
		<div id="section_firstname_no" style="width:100%; float: left;display: none;">
								<span class="formdata">
										Unfortunately we need to know your name before we can proceed with your enrollment.  Once you are willing to provide this information, please call us back at  <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>. </span><BR><BR> <span class="formdata">
										Is there anything else I can do for you today?
										<input name="unfortu" type="radio" value="1" onClick="global_checksetting(this,'section_firstname_no_yes','section_firstname_no_no','','')">&nbsp;&nbsp;Yes
										<input name="unfortu"  type="radio" value="0" onClick="global_checksetting(this,'section_firstname_no_yes','section_firstname_no_no','disp','4')">&nbsp;&nbsp;No
								</span>
		</div>
		<div id="section_firstname_no_yes" style="height:50px;width:100%; float: left;display: none;"><span class="formdata"><p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p></span><BR></div>
		<div id="section_firstname_no_no" style="height:50px;width:100%; float: left;display: none;"><span class="formdata"><p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D #4: CALLER REFUSED TO PROVIDE NAME</p></div>
		<?
		if($Session_State==3 && $Session_resbus==0)
		{

		?>
		<div id="section_nametx" style="width:100%;">
			<p class="formdata">Are you the person that will be named on the electricity bill (account holder)?&nbsp;<input name="person_res" type="radio" value="1" onClick="masterchecksetting_person(this)" >&nbsp;&nbsp;Yes
			<input name="person_res" type="radio" value="0"  onclick="masterchecksetting_person(this)">&nbsp;&nbsp;No</p>
			<div id="section_spouse" style="height:30px;width:100%;display: none;">
				<span class="formdata">Are you the spouse?</span>&nbsp;<input name="spouse_res" type="radio" value="1" onClick="masterchecksetting_spouse(this)" >&nbsp;&nbsp;Yes
					<input name="spouse_res" type="radio" value="0"  onclick="masterchecksetting_spouse(this)">&nbsp;&nbsp;No
			</div>
			<div id="section_spouse_yes" style="width:100%;display: none;">
				May I please have your First and Last Name? <span class="rep_note">CONFIRM AND READ BACK</span>
				<p class="rep_note">TSR NOTE: IF CALLER IS ENROLLING FOR SPOUSE ALL INFORMATION BEING CAPTURED MUST BE FOR THE SPOUSE</p>
				<div style="height:35px;width:30%; float: left;"><span class="formdata">First name of caller:<font color="red">*</font></span></div>
				<div style="height:35px;width:70%; float: left;">
						<span class="formdata">
							<input id="spfname" name="spfname"  value="<?=$Session_spfname;?>">
						</span>
		               </div>
		      		 <div style="height:35px;width:30%; float: left;"><span class="formdata">Last name of caller:<font color="red">*</font></span></div>
				 <div style="height:35px;width:70%; float: left;">
					<span class="formdata">
							<input name="splname" id="splname" value="<?=$Session_splname;?>">
					</span>
				</div>
			</div>
			<div id="section_spouse_no" style="width:100%;display: none;">
				<span class="formdata">Unfortunately only the account holder or their spouse can enroll.  If the account holder would like to enroll please have them call us back at <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or they can complete their enrollment on-line at ".$Session_web_addr;}?></span>
				<p class="formdata">Is there anything else I can do for you today?
 				<input name="spouse_yes" type="radio" value="1" onClick="masterchecksetting_spouse_no(this)">&nbsp;&nbsp;Yes
				<input name="spouse_no" type="radio" value="0"  onclick="masterchecksetting_spouse_no(this)">&nbsp;&nbsp;No</p>
				<div id="section_spouse_no_yes" style="width:100%; float: left;display: none;"><p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p><BR></div>
				<div id="section_spouse_no_no" style="width:100%; float: left;display: none;"><p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D #90: TX-NOT AUTHORIZED ACCOUNT HOLDER</p></div>

			</div>
			<div id="section_name" style="width:100%;display: none;">
			<?
				if($Session_Service_fname=='' || ($Session_State==3 && $Session_resbus==0))
				{
			?>
				<p class="formdata">May I please have your <span id='spouse_name'></span> name as it appears on <span id='your_name'></span> <?=$proff_name;?>&nbsp;<input name="personalA" type="radio" value="1" onClick="masterchecksetting1(this)" >&nbsp;&nbsp;Yes
				<input name="personalA" type="radio" value="0"  onclick="masterchecksetting1(this)">&nbsp;&nbsp;No</p>
			<?
				}
				else
				{
			?>
				<p class="formdata">May I please have the account holders name as it appears on the <?=$proff_name;?>?</p>
			<?
				}
			?>
			</div>
		</div>
		<?
		}
		else
		{
		?>
		<div id="section_name" style="width:100%;display: none;">
		<?
			if($Session_Service_fname=='')
			{
		?>
			<p class="formdata">May I please have the account holder's name as it appears on your <?=$proff_name;?>?&nbsp;<input name="personalA" type="radio" value="1"  onclick="masterchecksetting1(this)" Checked>&nbsp;&nbsp;Yes
			<input name="personalA" type="radio" value="0"  onclick="masterchecksetting1(this)">&nbsp;&nbsp;No</p>
		<?
			}
			else
			{
		?>
			<p class="formdata">May I please have the account holders name as it appears on the <?=$proff_name;?>?</p>
		<?
			}
		?>
		</div>
		<?
		}
		?>
		<div id="section0a" style="width:100%;display: none;">

		</div>
		<div id="section1b" style="width:100%; float: left;display: none;">
						<span class="formdata">
								Unfortunately we need to know your <span id='spouse_name1'></span> name before we can proceed with your enrollment.  Once you are willing to provide this information, please call us back at  <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>. </span><BR><BR> <span class="formdata">
								Is there anything else I can do for you today?
								<input name="unfortu" type="radio" value="1" onClick="masterchecksetting16(this)">&nbsp;&nbsp;Yes
								<input name="unfortu"  type="radio" value="0" onClick="masterchecksetting16(this)">&nbsp;&nbsp;No
						</span>
				</div>
				<div id="section6b" style="height:50px;width:100%; float: left;display: none;"><span class="formdata"><p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p></span><BR></div>
				<div id="section6a" style="height:50px;width:100%; float: left;display: none;"><span class="formdata"><p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D #4: CALLER REFUSED TO PROVIDE NAME</p></div>
		<div id="section1a" style="width:100%; float: left;display: none;">
				<div style="height:35px;width:30%; float: left;"><span class="formdata">First Name:<font color="red">*</font></span></div>
				<div style="height:35px;width:70%; float: left;">
						<span class="formdata">
							<input id="first_name" name="first_name"  value="<?php echo trim($cust_name1);?>" >
							<input type="hidden" name="r_first_name" value="First Name must be entered">
						</span>
		               </div>
		               <div style="height:35px;width:30%; float: left;"><span class="formdata">Middle Initial:</span></div>
				 <div style="height:35px;width:70%; float: left;"><span class="formdata"><input type="text" name="middle_initial" id="middle_initial"  value="<?php if($Session_Service_middle_initial) { echo $Session_Service_middle_initial;} else { echo $cust_name_mid; };?>" ></span> </div>
				 <div style="height:35px;width:30%; float: left;"><span class="formdata">Last Name:<font color="red">*</font></span></div>
				 <div style="height:35px;width:70%; float: left;">
					<span class="formdata">
							<input name="last_name" id="last_name" value="<?php echo trim($cust_name2);?>"  >
							<input type="hidden" name="r_last_name" value="Last Name must be entered">
					</span>
				</div>

				 <div style="height:35px;width:30%; float: left;"><span class="formdata">Suffix:</span></div>
				 <div style="height:35px;width:70%; float: left;">
					<span class="formdata">
							<input name="Suffix" type="radio" value="Sr." <?if($Session_Service_suffix=='Sr.'){echo 'Checked';}?>>&nbsp;&nbsp;Sr.
							<input name="Suffix" type="radio" value="Jr." <?if($Session_Service_suffix=='Jr.'){echo 'Checked';}?>>&nbsp;&nbsp;Jr.
					</span>
				</div>
		</div>
		<div id="section4c" style="display: none;width:100%; float: left;">
		<?
		if($Session_State!=3)
		{
		?>
					<span class="formdata">Thank you, Mr/Ms <?php echo trim($Session_Service_lname);?>,
						May I please have the address where the electric service will be used starting with </span>
						<p  class="formdata">the full street address?&nbsp;<input name="serviceinfo" id="serviceinfo" type="radio" value="1" onClick="masterchecksetting4(this)" <?if($Session_Service_Address1!='' || $cust_addr1!=''){echo 'Checked';}?>>&nbsp;&nbsp;Yes&nbsp;&nbsp;<input name="serviceinfo" id="serviceinfo" type="radio" value="0" <?if($Session_Service_Address1=='' && $cust_addr1==''){echo '';}?> onClick="masterchecksetting4(this)">&nbsp;&nbsp;No</p>
		<?
		}
		?>
		</div>


				<div id="section4a" style="display: none; ">
					<div style="height:50px;width:100%; float: left;">
						<p bgcolor="#666666" class="formheader"><font color="#FFFFFF">
						<strong><span id="addpre"></span> Service Address:</strong>
						<?
						if($Session_State!=3)
						{
						?>
							<i>&nbsp;&nbsp;REPEAT AND SPELL BACK AS YOU ENTER INFORMATION.</i>
						<?
						}
						?>
						 <span id="prim1"></span></font>
					</div>

				<div style="height:35px;width:30%; float: left;"><span class="formdata">Service Address1<font color="red">*</font>:</span></div>
				<div style="height:35px;width:70%; float: left;">
					<span class="formdata">
						<input name="Service_Address1" id="Service_Address1"  maxlength="64"  value="<?php if($Session_Service_Address1!=''){echo $Session_Service_Address1;}else{echo trim($cust_addr1);}?>" <?=$readonly;?>>
		      				<input type="hidden" name="r_Service_Address1" value="Missing Service Address1">
					</span>
		  		</div>


				<div style="height:35px;width:30%; float: left;"><span class="formdata">Service Address2:</span></div>
				<div style="height:35px;width:70%; float: left;">
					<span class="formdata">
						<input name="Service_Address2" id="Service_Address2"  maxlength="64"  value="<?php if($Session_Service_Address2!=''){echo $Session_Service_Address2;}else{echo trim($cust_addr2);}?>"  <?=$readonly;?>>
		      				<input type="hidden" name="r_Service_Address2" value="Missing Service Address2">
					</span>
		  		</div>

				<div style="height:35px;width:30%; float: left;"><span class="formdata">Service City:<font color="red">*</font></span></div>
				<div style="height:35px;width:70%; float: left;">
					<span class="formdata">
							<input name="Service_City" id="Service_City"  maxlength="20" value="<?php if($Session_Service_City!=''){echo $Session_Service_City;}else{echo trim($cust_city);}?>" <?=$readonly;?>>
							<input type="hidden" name="r_Service_City" value="Missing Service City">
					</span>
				</div>
				<div style="height:35px;width:30%; float: left;"><span class="formdata">Service State<font color="red">*</font>:</span></div>
				<div style="height:35px;width:70%; float: left;">
					<span class="formdata">
							<input name="Service_State" type="text" value="<?php if($_SESSION['st_abbrev']){echo $_SESSION['st_abbrev'];}?>" readonly >
					</span>
				</div>
				<div style="height:35px;width:30%; float: left;"><span class="formdata">Service Zip:<font color="red">*</font>:</span></div>
				<div style="height:35px;width:70%; float: left;">
					<span class="formdata">
							<input name="Service_Zip5" type="text" size="8" maxlength="5" id="Service_Zip5"  value="<?php if($Session_Service_Zip5!=''){echo $Session_Service_Zip5;}else{echo trim($cust_zip5);}?>"  <?=$readonly;?>>-
							<input name="Service_Zip4" type="text" size="8" maxlength="4" id="Service_Zip4"   value="<?php if($Session_Service_Zip4!=''){echo $Session_Service_Zip4;}else{echo $cust_zip4;}?>" <?=$readonly;?>>
							<input name="z_Service_Zip5" id="z_Service_Zip5" type="hidden" value="<?php echo $strec->zip_min;?>,<?php echo $strec->zip_max;?>">
							<input type="hidden" name="o_Service_Zip5" value="5,Invalid Service Zip">
							<input type="hidden" name="r_Service_Zip5" value="Missing Service Zip">
					</span>
				</div>
				<div>
							<p class="formdata">May I Please have the phone number of the primary account holder?
							&nbsp;<input name="phone" type="radio" value="1"  onclick="phone_checksetting(this,'phonesection_yes','phonesection_no','','')" Checked>&nbsp;&nbsp;Yes
							<input name="phone" type="radio" value="0"  onclick="phone_checksetting(this,'phonesection_yes','phonesection_no','disp','12')">&nbsp;&nbsp;No</p>
				</div>
				<div id="phonesection_yes" style="width:100%; float: left;display: none;">
					<div style="height:35px;width:30%; float: left;"><span class="formdata">Service Phone Number:<font color="red">*</font>&nbsp;&nbsp;&nbsp;</span></div>
					<div style="height:35px;width:70%; float: left;" class="inp_h">
						<span class="formdata">
				 			(<input name="Service_phone_number_prefix" type="text" id="Service_phone_number_prefix"  value="<?=$Session_Service_phone_number_prefix;?>" size="4" maxlength="3" >)
							<input name="Service_phone_number_first" type="text" id="Service_phone_number_first"  value="<?=$Session_Service_phone_number_first;?>" size="4" maxlength="3" > -
							<input name="Service_phone_number_last" type="text" id="Service_phone_number_last"  value="<?=$Session_Service_phone_number_last;?>" size="6" maxlength="4" >
							<input type="hidden" name="c_Service_phone_number_prefix;Service_phone_number_first;Service_phone_number_last" value="3,3,4,Missing/Invalid Service Phone" >
						</span>
					</div>
				</div>
				<div id="phonesection_no" style="width:100%; float: left;display: none;">
										<span class="formdata">
												Unfortunately we need to know your phone number before we can proceed with your enrollment. Once you have this information, please call us back at  <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>. </span><BR><BR> <span class="formdata">
												Is there anything else I can do for you today?
												<input name="no_phone_yes" type="radio" value="1" onClick="phone_checksetting(this,'phonesection_no_yes','phonesection_no_no','cust','')">&nbsp;&nbsp;Yes
												<input name="no_phone_no"  type="radio" value="0" onClick="phone_checksetting(this,'phonesection_no_yes','phonesection_no_no','disp','12')">&nbsp;&nbsp;No
										</span>
						<div id="phonesection_no_yes" style="height:50px;width:100%; float: left;display: none;"><span class="formdata"><p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p></span><BR></div>
						<div id="phonesection_no_no" style="height:50px;width:100%; float: left;display: none;"><span class="formdata"><p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D #12: CALLER REFUSED TO PROVIDE PRIMARY PHONE NUMBER</p></div>

				</div>



		<?
		if($Session_State!=3)
		{
		?>
				<div id="copy_address" style="height:35px;width:80%; float: left;">
					<span class="formdata">Is your billing address the same as your service address?&nbsp;<input type=radio name="copy_billing" id="copy_billing" value="1" id="copy_billing" >&nbsp;&nbsp;Yes&nbsp;&nbsp;<input type=radio name="copy_billing" id="copy_billing" value="0" id="copy_billing" >&nbsp;&nbsp;No</span>
				</div>
		<?
		}
		?>
			</div>
			<div id="section4b" style="display: none;">
					<span class="formdata">
						<BR>Unfortunately we need to know your address before we can proceed with your enrollment. <BR> Once you are willing to provide this information, please call us back at  <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>.<BR><BR>
						Is there anything else I can do for you today?
							<input name="servicefaq" id="servicefaq" type="radio" value="1" onClick="masterchecksetting5(this)">&nbsp;&nbsp;Yes
							<input name="servicefaq" id="servicefaq" type="radio" value="0" onClick="masterchecksetting5(this)">&nbsp;&nbsp;No
					</span>
					<div id="section5a" style="height:50px;width:100%; float: left;display: none;"><span class="formdata"><BR><p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D #5: Caller Refused to provide service address</p></span></div>
					<div id="section5b" style="height:50px;width:100%; float: left;display: none;"><span class="formdata"><p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p></i></span></div>
					<BR>
			</div>
			<div style="height:125px;width:100%; float: left;" >
			<table>
				<tr>
					<td>&nbsp;

					</td>
				</tr>
				<tr>
					<td>&nbsp;

					</td>
				</tr>
				<tr>
					<td>
						<input type="button" class="ib_button" name="btn_continue" id="btn_continue" value="Save and Continue" onClick="stepval(this)" >
						<input type="button" class="ib_button" name="btn_save" id="btn_save" value="Save" onClick="stepval(this)" >
						<input type="button" class="ib_button" name="btn_log_dispo" id="btn_log_dispo" value="Log Dispo" onClick="logDispo(this)" disabled>
						<input type='hidden' name='eflag' id='eflag' >
						<input type='hidden' name='eflagid' id='eflagid' >
						<input type='hidden' name='serviceinfo_val' id='serviceinfo_val' value="0" >
						<input type='hidden' name='next_url' id='next_url' value="select_billing.php">
						<input type='hidden' name='validate_address' id='validate_address' >
						<input type='hidden' name='resbus' id='resbus' value='<?=$Session_resbus;?>' >
						<input type='hidden' name='hid_spouse' id='hid_spouse'  >
						<input type='hidden' id="fname" name="fname"  value="<?php echo trim($cust_name1);?>" >
						<input type='hidden' name="lname" id="lname" value="<?php echo trim($cust_name2);?>"  >
						<input type='hidden' name='hid_person' id='hid_person' value='0'>
						<input type='hidden' name='hid_driver' id='hid_driver' value='0'>
						<input type='hidden' name='hid_state' id='hid_state' value='<?=$Session_State;?>' >
						<input type='hidden' name='hid_resbus' id='hid_resbus' value='<?=$Session_resbus;?>' >
						<input type='hidden' name='hid_state_fname' id='hid_state_fname' value='<?=$Session_State_fname;?>' >
						<input type='hidden' name='hid_state_lname' id='hid_state_lname' value='<?=$Session_State_lname;?>' >

					</td>
				</tr>
			</table>
			</div>
</form>
<SCRIPT TYPE="text/javascript">
<!--
autojump('Service_phone_number_prefix', 'Service_phone_number_first', 3);
autojump('Service_phone_number_first', 'Service_phone_number_last', 3);
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


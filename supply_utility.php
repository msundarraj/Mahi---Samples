<?php
require_once 'includes/main.php';

//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Utility//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////


//
// make sure previous tabs are completed
//
require_once 'includes/check_state.php';

require_once 'EP/Model/StateMapper.php';
require_once 'EP/Model/PartnerMapper.php';
require_once 'EP/HTML/Form/Widget/RadioBool.php';
require_once 'EP/Model/UtilityMapper.php';

// we redirect to select_utility_tx.php for Texas

$register = '';
if ( isset( $_SESSION['register']))
{
	$register = $_SESSION['register'];
}

//print_r($_SESSION);

$state = '';
$strec = null;
$stabbrev = null;
$stid = null;
if ( isset( $_SESSION['tabs']['state']['state'] ))
{
	$state = $_SESSION['tabs']['state']['state'];

	// for texas, we redirect to texas utility page
	if ( $state == 3 )
	{
		header( "Location: select_utility_tx.php" );
	}

	$stateMapper = new EP_Model_StateMapper();
	$strec = $stateMapper->fetch( $state );
	unset( $stateMapper );

	$stabbrev = $strec->getAbbrev();
	$stid = $strec->getId();
}


$currsupp = null;
if ( isset( $_SESSION['tabs']['utility']['currsupp'] ))
{
	$currsupp = $_SESSION['tabs']['utility']['currsupp'];
}


$select_utility = '';
if ( isset( $_SESSION['tabs']['utility']['select_utility']))
{
	$select_utility = $_SESSION['tabs']['utility']['select_utility'];
}




/*
 * If it's a POST then we save to the existing register record
 *
 */

if ( !empty( $_POST ) )
{
	if ( isset($_POST['busresSess']) )
	{

		if ( !isset( $_POST['select_utility'] ) || !is_int((int)$_POST['select_utility']))
		{
			die('error 4');
		}
		$util = trim($_POST['select_utility']);

		// fetch the utility
		$utilMapper = new EP_Model_UtilityMapper();
		$utilMapper->setUsePublicVars( true );
		$utilObj = $utilMapper->fetchByCode( $util );
		unset( $utilMapper );

		// take the utility data and put in a big array in session
		// we should eventually just leave this as utility object
		$props = EP_Model_Utility::getAllClassProperties( 'EP_Model_Utility' ) ;
		$util_vars = array();
		foreach ( $props as $num => $val )
		{
			if ( isset( $utilObj->$val ))
			{
				$util_vars[ $val ] = $utilObj->$val;
			}
		}

		if( $util_vars )
		{
			extract( $util_vars );
		}

		foreach ($util_vars as $key => $value)
		{
    		$_SESSION['utility'][$key] = $value;
		}

		$register->setDistrib(trim($_SESSION['utility']['code']));
		$register->setTerritoryCode(trim($_SESSION['utility']['code']));

		$_SESSION['tabs']['utility']['select_utility'] = $_POST['select_utility'];


		$tmpAccountType = (int)trim($_POST['busresSess'] );

		if ( $tmpAccountType == 1 )	// business
		{
			$_SESSION['tabs']['utility']['acct_type'] = "COMMERCIAL";
			$_SESSION['tabs']['utility']['resbus'] = 1;
			$_SESSION['tabs']['utility']['revclass'] = 2;
			$register->setRevclass( 2 );
			$register->setBusres( 1 );
		}
		else	// residential
		{
			$_SESSION['tabs']['utility']['acct_type'] = "RESIDENTIAL";
			$_SESSION['tabs']['utility']['resbus'] = 0;
			$_SESSION['tabs']['utility']['revclass'] = 1;
			$register->setRevclass( 1 );
			$register->setBusres( 0 );
		}

		$register->setEntype( 1 );


		unset( $tmpAccountType );

		$today = strftime("%Y%m%d");
		$register->setToday(trim($today));


		if ( isset( $_POST['supplier'] ))
		{
			$_SESSION['tabs']['utility']['currsupp'] = $_POST['supplier'];
		}
		else
		{
			$_SESSION['tabs']['utility']['currsupp'] = '';
		}

		$today = strftime("%Y%m%d");

		$register->setToday(trim($today));
		$_SESSION['tabs']['utility']['today'] = $today;

/*
		if($_SESSION['utility']['has_gas_option']==1)
		{
				$servicetype=2;
		}
		else
		{
				$servicetype=1;
		}
*/
		$register->setServicetype( 1 );
		$_SESSION['tabs']['utility']['servicetype'] = 1;


		if ( !empty( $_POST['account_id']))
		{
			// set the account id from PA search
			$_SESSION['tabs']['utility']['account_id'] = $_POST['account_id'];
		}

		//  update our register record
		$mapper = new EP_Model_RegisterMapper();
		$result = $mapper->save( $register );

		if ( !$result )
		{
			die( 'error saving' );
		}

		// put the results into the session

		$_SESSION['register'] = $register;

		$next_url = '';
		if ( isset( $_POST['next_url']))
		{
			$next_url = $_POST['next_url'];
		}
		if ( $next_url != '' )
		{
			header( "Location: ${base_url}myinbound/${next_url}" );
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
} // end if POST

/*
 * If we are revisiting this tab
 * let's try to populate everything
 * that we already know
 */

$session = null;
if ( isset( $_SESSION['tabs']['utility']))
{
	$session = $_SESSION['tabs']['utility'];
}

$select_utility = null;
if (isset($_SESSION['tabs']['utility']['select_utility']))
{
	$select_utility = $_SESSION['tabs']['utility']['select_utility'];
}

$account_type = null;
if (isset($_SESSION['tabs']['utility']['resbus']))
{
	$account_type = $_SESSION['tabs']['utility']['resbus'];
}

// get the utilities
$utilMapper = new EP_Model_UtilityMapper();
$utils = $utilMapper->fetchAllByState( $state );
unset( $utilMapper );

######################## Custom Validation######################################

$resbus1_selected1='';
$resbus1_selected2='';
$resbus1_selected3='';
$resbus1_selected4='checked';

if ( isset( $_SESSION['tabs']['utility']['resbus'] ))
{
	if( $_SESSION['tabs']['utility']['resbus'] == 0 )
	{
		 $resbus1_selected1='checked';
		 $resbus1_selected2='';
		 $resbus1_selected3='';
		 $resbus1_selected4='';

	}
	else if( $_SESSION['tabs']['utility']['resbus'] == 1 )
	{
		 $resbus1_selected1='';
		 $resbus1_selected2='checked';
		 $resbus1_selected3='';
		 $resbus1_selected4='';

	}
	else // if($_SESSION['tabs']['utility']['resbus']==4)
	{
		 $resbus1_selected1='';
		 $resbus1_selected2='';
		 $resbus1_selected3='';
	}
}


$_SESSION['enroll_tel']=$strec->getEnrollTel();
$_SESSION['cs_tel']     = $strec->getCsTel();


$_SESSION['web_addr']="<font color='#0000FF'>www.energypluscompany.com</font>";

$entype = '';
if ( isset( $_SESSION['tabs']['utility']['entype'] ) )
{
	$entype = $_SESSION['tabs']['utility']['entype'];
}


// START the HTML page
require_once 'includes/header.php';
?>

<script type="text/javascript">

var state = <?=$state;?>

function global_loadsetting(state,select_utility,resbus,entype)
{

		document.getElementById("busresSess").value=resbus;

		if( resbus == 0 )
		{
			if(state==7)
			{
				document.getElementById('supplier').style.display='';
			}
			document.getElementById("resbes_noanswer_Section").style.display='none';
		}
		else if( resbus == 1 )
		{
			document.getElementById("resbes_noanswer_Section").style.display='none';
			if(state==7)
			{
				document.getElementById('supplier').style.display='';
			}
		}


		if(select_utility!='CPNP' || select_utility!='SNA')
		{
			document.getElementById("accountsection").style.display='';
		}

		if(document.getElementById("busresSess").value==4)
		{
			document.getElementById('resbes_noanswer_Section').style.display='';
			if(state==7)
			{
				document.getElementById('supplier').style.display='none';
			}
			document.getElementById('busresSess').value = 4;
		}
}


function global_checksetting_IL(selelm,yes_section,no_section,log,logid)
{
	var log;

	var resbus = $('input[name=resbus]:checked').val()
	if(log=='')
	{
		var log='';
	}

	if(logid=='')
	{
		var logid='';
	}

	if(selelm.value=='1')
	{
		document.getElementById(yes_section).style.display='none';
		document.getElementById(no_section).style.display='';
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}
	else
	{
		document.getElementById(yes_section).style.display='';
		document.getElementById(no_section).style.display='none';
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;

		fetchSuppliers( state, resbus, fetchSuppliersSuccess, fetchSuppliersError );
	}

}

var fetchSuppliersSuccess = function( data ) {
	if ( data.success == true )
	{
		var utils = data.data;
		$("select").remove("#supplier");
		// create the select box
		var select = document.createElement( 'select' );
		select.id = "supplier";
		select.name = "supplier";

		var option = document.createElement('option');
		option.value = '';
		var text = document.createTextNode( 'Select Supplier' );
		option.appendChild( text );
		select.appendChild( option );

		// var fetchPromo = false;
		for ( var i = 0; i < utils.length; i++ )
		{
			var option = document.createElement('option');
			option.value = utils[i].name;
			//if ( utils[i].name === asdf )
			//{
			//	option.selected = 'selected';
			//}
			var text = document.createTextNode( utils[i].name );
			option.appendChild( text );
			select.appendChild( option );
		}


		var option = document.createElement('option');
		option.value = 'CDPS';
		var text = document.createTextNode( 'CALLER DID NOT PROVIDE SUPPLIER NAME' );

		option.appendChild( text );
		select.appendChild( option );

		// add select box to div
		$("#supplierlist").append( select );
	}
	else
	{
		alert( "There has been an error fetching the suppliers." );
	}

}

var fetchSuppliersError = function( data ) {
	alert( "There has been an error fetching the suppliers." );
}

function fetchSuppliers( state, resbus, callbackSuccess, callbackError )
{
	$.ajax({
		url: '/ajax/fetch_suppliers.php',
		data: {'state': state, 'resbus': resbus },
		dataType: 'json',
		type: 'GET',
		success: function(data) {
			callbackSuccess( data );
		},
		error: function(data) {
			callbackError( data );
		}
	});
}

function global_checksetting(selelm,yes_section,no_section,log,logid)
{
	var log;
	if(log=='')
	{
		var log='';
	}

	if(logid=='')
	{
		var logid='';
	}

	if(selelm.value=='1')
	{
		document.getElementById(yes_section).style.display='none';
		document.getElementById(no_section).style.display='';
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;

	}
	else
	{
		document.getElementById(yes_section).style.display='';
		document.getElementById(no_section).style.display='none';
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}

	if(log!='')
	{
		document.getElementById('btn_continue').disabled = true;
		document.getElementById('btn_save').disabled = true;
	}
}

function global_checksetting_utility1(selelm,yes_section,no_section)
{
	if(selelm.value=='CPNP')
	{
		document.getElementById('CPNP_Section').style.display='';
		document.getElementById('SNA_Section').style.display='none';
	}
	else
	{
		document.getElementById('SNA_Section').style.display='';
		document.getElementById('CPNP_Section').style.display='none';
	}
}

function global_checksetting_revclass(selelm,yes_section,no_section,state)
{
	if( selelm.value == '0' )
	{
		document.getElementById('resbes_noanswer_Section').style.display='none';

		if( state == 7 )
		{
			var resbus = 1;
			document.getElementById('supplier').style.display='';
		}

		document.getElementById('busresSess').value = 0;
		document.getElementById('eflag').value='';
		document.getElementById('eflagid').value='';
		document.getElementById('btn_continue').disabled = false;
		document.getElementById('btn_save').disabled = false;
	}
	else if( selelm.value == '1' )
	{
		document.getElementById('resbes_noanswer_Section').style.display='none';

		if( state == 7 )
		{
			var resbus = 2;
			document.getElementById('supplier').style.display='';
		}

		document.getElementById('busresSess').value = 1;
		document.getElementById('eflag').value='';
		document.getElementById('eflagid').value='';
		document.getElementById('btn_continue').disabled = false;
		document.getElementById('btn_save').disabled = false;
	}
	else if(selelm.value=='4')
	{
		document.getElementById('resbes_noanswer_Section').style.display='';

		if( state == 7 )
		{
			document.getElementById('supplier').style.display='none';
		}
		document.getElementById('busresSess').value = 4;
		document.getElementById('btn_continue').disabled = true;
		document.getElementById('btn_save').disabled = true;
	}
}



function showLookup()
{
	var elem = document.getElementById('select_utility');
	var util = elem.options[elem.selectedIndex].value;

	if(util=='')
	{
		alert('Please choose Utility');
		document.getElementById('select_utility').focus();
		return false;
	}
	else
	{
		var url="pasearch.php?util="+util;
		newwindow=window.open(url,'name','height=800px,width=900px,scrollbars=1');
		if (window.focus) {newwindow.focus()}
		return false;
	}
}

function hideLookup()
{
	document.getElementById('lookupdiv').style.display='none';
}



function check_setting_provider(selelm)
{
	document.getElementById("switch_service_message").style.display='';
}

function check_setting_utility1(selelm,state)
{
	if(document.getElementById('busresSess').value==4)
	{
		document.getElementById("resbes_noanswer_Section").style.display='';
	}

	if(selelm.value=="CPNP")
	{
		document.getElementById("accountsection").style.display='none';
		document.getElementById("cpnpsection").style.display='';
		document.getElementById("snasection").style.display='none';
		document.getElementById("resbes_noanswer_Section").style.display='none';
		document.getElementById("utilitymsg").style.display='none';
		if(state==7)
		{
			document.getElementById("supplier").style.display='none';
		}
	}
	else if(selelm.value=="SNA")
	{
		document.getElementById("accountsection").style.display='none';
		document.getElementById("cpnpsection").style.display='none';
		document.getElementById("snasection").style.display='';
		document.getElementById("resbes_noanswer_Section").style.display='none';
		document.getElementById("utilitymsg").style.display='none';
		if(state==7)
		{
			document.getElementById("supplier").style.display='none';
		}
	}
	else
	{
		document.getElementById("accountsection").style.display='';
		document.getElementById("cpnpsection").style.display='none';
		document.getElementById("snasection").style.display='none';
		document.getElementById("utilitymsg").style.display='';
		document.getElementById('eflag').value='';
		document.getElementById('eflagid').value='';
		if(state==7)
		{
			document.getElementById("supplier").style.display='';
		}

		if(document.getElementById('busresSess').value!=4)
		{
			document.getElementById('btn_continue').disabled = false;
			document.getElementById('btn_save').disabled = false;
		}
	}
}

function checksetting_CPNP(selelm)
{
	if(selelm.value=='1')
	{
		document.getElementById('sectioncpnp_yes').style.display='none';
		document.getElementById('sectioncpnp_no').style.display='';
	}
	else
	{
		document.getElementById('sectioncpnp_yes').style.display='';
		document.getElementById('sectioncpnp_no').style.display='none';
	}
}

function checksetting_SNA(selelm)
{
	if(selelm.value=='1')
	{
		document.getElementById('sectionsna_yes').style.display='none';
		document.getElementById('sectionsna_no').style.display='';
	}
	else
	{
		document.getElementById('sectionsna_yes').style.display='';
		document.getElementById('sectionsna_no').style.display='none';
	}
}

function validateForm()
{
	var su = $('#select_utility').val();
	if( su == '' || isNaN( parseInt( su )))
	{
		alert('Please Select the Utility');
		$('#select_utility').focus();
		return false;
	}

	if(document.getElementById('busresSess').value=='')
	{
		alert('Please choose the Account type');
		return false;
	}

	if ( this.id == 'btn_continue' )
	{
		$('#next_url').val('select_offer.php');
	}

	if(document.getElementById('inbound_utility').value=="SNA")
	{
		document.getElementById("inbound_utility").action = 'customer_service.php';
  		document.getElementById("inbound_utility").submit();
		return true;
	}
	else
	{
     	document.getElementById("inbound_utility").action = 'select_utility.php';
  		document.getElementById("inbound_utility").submit();
		return true;
	}
}

$(document).ready(function() {
	  $('#btn_continue').bind('click', validateForm );
	  $('#btn_save').bind('click', validateForm );


	$('#sna_contact_no').bind('click', function() {
		  $('#eflag').val( 'dispo' );
		  $('#eflagid').val( 31 );
		  logDispo( '#inbound_utility');
	});

	$('#cpnpsection_no').bind('click', function() {
		  $('#eflag').val( 'dispo' );
		  $('#eflagid').val( 1 );
		  logDispo( '#inbound_utility');
	});

	$('#resbes_noanswer_no').bind('click', function() {
		  $('#eflag').val( 'dispo' );
		  $('#eflagid').val( 2 );
		  logDispo( '#inbound_utility');
	});


	$('#btn_logdispo8').bind('click', function() {
		  $('#eflag').val( 'dispo' );
		  $('#eflagid').val( 8 );
		  logDispo( '#appform');
	});

	$('#btn_logdispo9').bind('click', function() {
		  $('#eflag').val( 'dispo' );
		  $('#eflagid').val( 8 );
		  logDispo( '#appform');
	});



	$('#lookup').bind('click', showLookup );

	$('#select_utility').bind('change', function() {
		var su = $('#select_utility').val();
		// hide our PA lookup button if they don't select a util
		if ( su == 'CPNP' || su == 'SNA' )
		{
			$('#lookup').hide();
		}
		else
		{
			$('#lookup').show();
		}
	});
});

</script>

<?php
	unset($_SESSION['pover']);

?>
<link rel="stylesheet" type="text/css" href="/myinbound/css/global.css">


</head>

<body onLoad="global_loadsetting('<?=$state?>','<?=$select_utility;?>','<?=$account_type;?>','<?=$entype;?>')">

<div class="yui3-g" id="container"  >

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


	<input type="hidden" name="submitted" value="1" >
	<input type="hidden" name="state" value="<?php echo $strec->getId();?>" >
	<input type="hidden" name="statea" value="<?php echo $strec->getAbbrev();?>" >
	<input type="hidden" name="accid" id="accid" value="" >
	<input type="hidden" name="gas" id="gas" value="" >

	<form id="inbound_utility" name="inbound_utility" method="post" action="select_utility.php" autocomplete="off">
		<div id="utilitymsg" style="width: 400px; float: right; background-color: #eee; padding: 4px;">
			<p class="rep_note">NOTE TO TSR: A CALLER MAY PROVIDE THEIR ELECTRICITY SUPPLIER AND NOT
			UTILITY COMPANY. WE NEED TO CAPTURE THE UTILITY.</p>
		</div>
		<div class="padding_bottom">
			<p>May I please have the name of your current electricity provider,
			or the company that sends you your electricity bills?	</p>
			<select name="select_utility" id="select_utility" onChange="check_setting_utility1(this,'<?=$state?>')" >
				<option value="">Select Utility for Search</option>
<?php
	for ( $i = 0; $i < count( $utils ); $i++ )
	{
		if( $utils[$i]->getCode() == $select_utility )
		{
			echo '<option value="' . $utils[$i]->getCode() . '" selected>' . $utils[$i]->getUtility() . '</option>' . "\n";
		}
		else
		{
			echo '<option value="' . $utils[$i]->getCode() . '">' . $utils[$i]->getUtility() . '</option>' . "\n";
		}
	}
	echo "<option value='CPNP'>CALLER PREFERS NOT TO PROVIDE</option> \n";
	echo "<option value='SNA'>UTILITY NOT AVAILABLE</option> \n";

?>
			</select>
		</div>
		<div id="cpnpsection" style="display:none; clear: both;">
			<p>Unfortunately we need to know your Electricity Service Provider before we can proceed with your enrollment.
			Once you are willing to provide this information please call us back at <?=$_SESSION['enroll_tel'];?> or
			you can complete your enrollment on-line at <?=$_SESSION['web_addr'];?>. </p>

			<p>Is there any thing else I can do for you today?
				<input name="cpnpoption" id="cpnpsection_yes" type="radio" value="0" onClick="global_checksetting(this,'sectioncpnp_yes','sectioncpnp_no','dispo','1')">Yes
				<input name="cpnpoption" id="cpnpsection_no" type="radio" value="1" onClick="global_checksetting(this,'sectioncpnp_yes','sectioncpnp_no','dispo','1')">No
			</p>

			<div id="sectioncpnp_yes" style="display: none;">
				<p class="rep_note">NOTE TO TSR: PROBE THE CUSTOMER. IF CUSTOMER DOES NOT WANT TO ENROLL, CLICK LOG DISPO AND END CALL.</p>
			</div>
			<div id="sectioncpnp_no" style="display: none;">
				<p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D #1: CALLER REFUSED TO PROVIDE UTILITY COMPANY NAME</p>
			</div>
		</div>

		<div id="snasection" style="display:none; clear:both;">
			<p>Unfortunately we cannot provide service to you at this time. I would be happy to take down
			your contact information were that to change in the future.
				<input name="snaoption" id="sna_contact_yes" type="radio" value="0" onClick="global_checksetting(this,'sectionsna_yes','sectionsna_no','cust','6')">Yes
				<input name="snaoption" id="sna_contact_no" type="radio" value="1" onClick="global_checksetting(this,'sectionsna_yes','sectionsna_no','dispo',1)">No
			</p>

			<div id="sectionsna_yes" style="display: none;">
				<p class="rep_note">NOTE TO TSR: GO TO CUSTOMER SERVICE TAB AND SELECT 6</p>
			</div>
			<div id="sectionsna_no" style="display: none;">
				<p class="rep_note">NOTE TO TSR : AUTO DISPO #31: UTILITY NOT AVAILABLE - DID NOT ADD TO ENROLLMENT LIST</p>
			</div>
		</div>
<?php
	// For mostly PA utilities, we provide an account search
	//Edited by Mahi
	if($strec->getHasLookup() )
	{
?>
		<input type="button" id="lookup" style="margin-bottom: 1em;" value="Find Account Details"  >&nbsp;&nbsp;<span id="accidshow"></span>
<?php
	}
?>
<div id="accountsection" style=" clear:both;" class="padding_bottom" >
	<p>Is the account you would like to switch to Energy Plus  Residential or Business?</p>
	<input type="radio" id="resbus1" name="resbus" value="0" onClick="global_checksetting_revclass(this,'','','<?=$_SESSION['tabs']['state']['state'];?>')" <?=$resbus1_selected1;?> >
		<label for="resbus1">Residential</label><br>
	<input type="radio" id="resbus2" name="resbus" value="1" onClick="global_checksetting_revclass(this,'','','<?=$_SESSION['tabs']['state']['state'];?>')" <?=$resbus1_selected2;?> >
		<label for="resbus2">Business</label><br>
	<input type="radio" id="resbus4" name="resbus" value="4" onClick="global_checksetting_revclass(this,'resbes_noanswer_Section','','<?=$_SESSION['tabs']['state']['state'];?>')"  >
		<label for="resbus4">No Answer</label><br>
	<input type="hidden" name="busresSess" id="busresSess" value=''>
</div>

	<div id="resbes_noanswer_Section" style="display:none;">
		<p>Unfortunately we need to know the type of account before we can proceed with your enrollment. Once you have this information, please call us back at <?=$_SESSION['enroll_tel'];?> or you can complete your enrollment on-line at <?=$_SESSION['web_addr'];?>.</p>
		<p>Is there anything else I can do for you today?
			<input name="noans" id="resbes_noanswer_yes" type="radio" value="0" onClick="global_checksetting(this,'sections_noans_yes','sections_noans_no','cust','')">Yes
			<input name="noans" id="resbes_noanswer_no" type="radio" value="1" onClick="global_checksetting(this,'sections_noans_yes','sections_noans_no','dispo','')">No
		</p>
			<div id="sections_noans_yes" style="display: none;">
				<p class="rep_note">NOTE TO TSR: PROBE THE CUSTOMER</p>
			</div>
			<div id="sections_noans_no" style="display: none;">
				<p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D</p>
			</div>
	</div>
<?php
// Illinois
if( $state == 7 )
{
?>
	<div id="supplier" style="display: none;">
		<p>Do you currently have another company supplying your electricity other than ComED?
			<input name="conmed" type="radio" value="0" onClick="global_checksetting_IL(this,'supplier_yes','supplier_no','cust','')">Yes
			<input name="conmed" type="radio" value="1" onClick="global_checksetting_IL(this,'supplier_yes','supplier_no','dispo','')">No
		</p>

		<div id="supplier_yes" style="display: none;">
			<span id='supplierlist'></span>
		</div>
		<div id="supplier_no" style="display: none;">
			<span class="rep_note">NOTE TO TSR: Continue.</span>
		</div>
		<div>
			<p class="rep_note">NOTE TO TSR: IF THE CUSTOMER IS CONFUSED:  ASK CUSTOMER TO GET COMED BILL AND ASK THEM TO LOOK UNDER THE ELECTRIC SUPPLY SERVICES. IF THERE IS ANOTHER COMPANY'S NAME  THERE ASK THEM TO PROVIDE IT TO YOU. THIS IS THE SUPPLIER.</p>
		</div>
	</div>
<?php
} // end Illinois
?>
		<div style="padding-top: 3em;">
			<input type="hidden" name="eflag" id="eflag" >
			<input type="hidden" name="eflagid" id="eflagid" >
			<input type="hidden" name="next_url" id="next_url" value="" >
			<input type="hidden" name="account_id" id="account_id" >
			<input type="button" class="ib_button" name="btn_continue" id="btn_continue" value="Save and Continue" >
			<input type="button" class="ib_button" name="btn_save" id="btn_save" value="Save" >
			<input type="button" class="ib_button" name="btn_log_dispo" id="btn_log_dispo" value="Log Dispo" onClick="logDispo('#inbound_utility')" >
		</div>
</form>
</div>

<div id="lookupdiv" style="background-color:#fff;display:none;position:absolute;top:90px;left:250px;width:650px;height:auto;border:1px solid #ccc;">
	<div style="height:22px;background-color:#006b66;">
		<div style="width:30px;float:right;color:#fff">
			<a style="color:#fff;font-weight:bold;" href="javascript:void(0)" onClick="hideLookup()">X</a>
		</div>
	</div>
	<div id="rdiv">
	</div>
</div>
<?php
        require_once 'includes/statusbar.php';
?>
</div>
<?php
        require_once 'includes/footer.php';

?>


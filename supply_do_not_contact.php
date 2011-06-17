<?php

//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Calls Module//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////

require_once 'includes/main.php';
require_once 'EP/Util/Database.php';
require_once 'EP/Model/Ib/Donotcontact.php';
require_once 'EP/Model/Ib/DonotcontactMapper.php';
require_once 'EP/Model/Ib/CallMapper.php';
require_once 'EP/HTML/Form/Widget/RadioBool.php';

if ( !empty( $_POST))
{
	// echo '<pre>' . print_r( $_POST, true ) . '</pre>';
	
	// these are ordered in a specific way
	// cuz we check for the existence of the first 5 items 
	// in the POST array
	$arr = array( 
		'name_first_', 
    	'name_last_',
		'addr1_',
    	'city_', 
    	'state_', 
    	'zip5_',
    	'addr2_', 
    	'zip4_', 
    	'email_', 
    	'Service_phone_number_prefix_',
       'Service_phone_number_first_',
       'Service_phone_number_last_'
	 );
	 
	 // loop thru each numbered form
	 for ( $i = 1; $i <= 10; $i++ )
	 {
 		$name_first = '';
 		$name_last = '';
 		$addr1 = '';
 		$addr2 = '';
 		$city = '';
 		$state = '';
 		$zip5 = '';
 		$zip4 = '';
 		$email = '';
 		$phone = '';
              $phone_prefix = '';
              $phone_first = '';
              $phone_last = '';
	 		
	 	$vars = array();
	 	// loop thru variables
	 	for( $j = 0; $j < count( $arr ); $j++ )
	 	{
	 		// $vars[] = $arr[$k] . $i;
	 		$vars[] = $arr[ $j ] . $i;
	 	}
	 	// these values are there, then we're going to try to save it
	 	if ( !empty( $_POST[$arr[0] . $i]) &&
	 		!empty( $_POST[$arr[1] . $i]) &&
	 		!empty( $_POST[$arr[2] . $i]) &&
	 		!empty( $_POST[$arr[3] . $i]) &&
	 		!empty( $_POST[$arr[4] . $i]) &&
	 		!empty( $_POST[$arr[5] . $i])
	 		)
	 	{
	 		$name_first = trim( $_POST[$arr[0] . $i] );
	 		$name_last = trim( $_POST[$arr[1] . $i] );
	 		$addr1 = trim( $_POST[$arr[2] . $i] );
	 		$city = trim( $_POST[$arr[3] . $i] );
	 		$state = trim( $_POST[$arr[4] . $i] );
	 		$zip5 = trim( $_POST[$arr[5] . $i] );

	 		
	 		$addr2 = '';
	 		if ( !empty( $_POST[$arr[6] . $i]))
	 		{
	 			$addr2 = trim( $_POST[$arr[6] . $i] );
	 		}

	 		$zip4 = '';
	 		if ( !empty( $_POST[$arr[7] . $i] ))
	 		{
	 			$zip = trim( $_POST[$arr[7] . $i] );
	 		}
	 		$email = '';
	 		if ( !empty( $_POST[$arr[8] . $i] ))
	 		{
	 			$email = trim( $_POST[$arr[8] . $i] );
	 		}

	 		$phone = '';
                     $phone_prefix = '';
                     $phone_first = '';
                     $phone_last = '';
	 		if ( !empty( $_POST[$arr[9] . $i ]))
	 		{
	 			$phone_prefix = trim( $_POST[$arr[9] . $i] );
	 		}
 
                     if ( !empty( $_POST[$arr[10] . $i ]))
	 		{
	 			$phone_first = trim( $_POST[$arr[10] . $i] );
	 		}
                     if ( !empty( $_POST[$arr[11] . $i ]))
	 		{
	 			$phone_last = trim( $_POST[$arr[11] . $i] );
	 		}
                     $phone=$phone_prefix."".$phone_first."".$phone_last;


	 		

	 		$contact = new EP_Model_Ib_Donotcontact();
	 		$contact->setFirstName( $name_first );
	 		$contact->setLastName( $name_last );
	 		$contact->setAddr1( $addr1 );
	 		$contact->setAddr2( $addr2 );
	 		$contact->setCity( $city );
	 		$contact->setState( $state );
	 		$contact->setZip5( $zip5 );
	 		$contact->setZip4( $zip4 );
	 		$contact->setEmail( $email );
	 		$contact->setPhone( $phone );
	 		
	 		$mapper = new EP_Model_Ib_DonotcontactMapper();
	 		$result = $mapper->save( $contact );
	 		
			if ( $result )
			{
				$_SESSION['messages'][] = "Record for <b>'$name_first $name_last'</b> has been saved";
			}
	 	}
		else
		{
			// we don't have enough to save this record
			//$_SESSION['messages'][] = "Not enough info to save record: $i ";
		}
	 } // end for loop
	
	// save and end call
	// see also dispositions.php for ending a call
	if ( isset( $_SESSION['call']) && isset( $_SESSION['call_x_register']))
	{
		$call = $_SESSION['call'];
		$callId = $call->getId();
		$callXRegister = $_SESSION['call_x_register'];
		
		// update the call with our new end status
		// non sale dispostion is status of 3
		$callXRegister->setEndStatusId( 2 );
		//$callXRegister->setCallDispoId( 27 );
		//$callXRegister->setCallDispoEnhancementId( 2 );
		$callXRegister->setReason( 'auto' );
		$date = date('Y-m-d H:i:s');
		$call->setDateEnded( $date );
		$callXRegister->setDateEnded( $date );
		
		// save the call
		$db = EP_Util_Database::pdo_connect();
		$db->beginTransaction();
		$callMapper = new EP_Model_Ib_CallMapper();
		$callXRegisterMapper = new EP_Model_Ib_CallXRegisterMapper();
		$callMapper->setDatabaseConnection( $db );
		$cmResult = $callMapper->save( $call );
		$callXRegisterMapper->setDatabaseConnection( $db );
		$callXRegResult = $callXRegisterMapper->save( $callXRegister );
		
		if ( $cmResult && $callXRegResult )
		{
			$db->commit();
			if ( $_POST['continue_call'] == 1)
			{
				header( "Location: ${base_url}myinbound/customer_service.php" );
			}
			else 
			{
				// header show call end ?da= $asgn->getId()
				$call->endCall();
				header( "Location: ${base_url}myinbound/show_call_end.php?c=" . $call->getId() );
			}
		}
		else
		{
			$db->rollback();
			echo 'Error saving.';
		}
	} // end if call
}

// make state select box for leads form
$db = EP_Util_Database::pdo_connect();
$sql = "SELECT name, abbrev FROM usstates order by name";
$sth = $db->prepare( $sql );
$sth->setFetchMode( PDO::FETCH_OBJ );
$sth->execute( );
$states = $sth->fetchAll();



    require_once 'includes/header.php';
?>
<script type="text/javascript">



function validateForm()
{
  
	$('#frm1').submit();	
}

$(document).ready(function() {
	$('#open_1').hide();
	$('#close_1').hide();
	$('#continue_call_yes').bind('click', function() {
		$('#closure').hide();
	});
	$('#continue_call_no').bind('click', function() {
		$('#closure').show();
		alert('Thank you for calling Energy Plus and have a nice day.')
       validateForm();
	});
	
	$('#add_1').bind('click', function() {
		$('#div_dnc_2').show();
	});
	$('#add_2').bind('click', function() {
		$('#div_dnc_3').show();
	});
	$('#add_3').bind('click', function() { 
		$('#div_dnc_4').show();
	});
	$('#add_4').bind('click', function() {
		$('#div_dnc_5').show();
	});
	$('#add_5').bind('click', function() {
		$('#div_dnc_6').show();
	});
	$('#add_6').bind('click', function() {
		$('#div_dnc_7').show();
	});
	$('#add_7').bind('click', function() {
		$('#div_dnc_8').show();
	});
	$('#add_8').bind('click', function() {
		$('#div_dnc_9').show();
	});
	$('#add_9').bind('click', function() {
		$('#div_dnc_10').show();
	});

	$('#open_2').bind('click', function() {
		$('#body_2').show();
	});
	$('#close_2').bind('click', function() {
		$('#body_2').hide();
	} );
	
	$('#open_3').bind('click', function() {
		$('#body_3').show();
	});
	$('#close_3').bind('click', function() {
		$('#body_3').hide();
	} );
	
	$('#open_4').bind('click', function() {
		$('#body_4').show();
	});
	$('#close_4').bind('click', function() {
		$('#body_4').hide();
	} );
	
	$('#open_5').bind('click', function() {
		$('#body_5').show();
	});
	$('#close_5').bind('click', function() {
		$('#body_5').hide();
	} );
	
	$('#open_6').bind('click', function() {
		$('#body_6').show();
	});
	$('#close_6').bind('click', function() {
		$('#body_6').hide();
	} );
	
	$('#open_7').bind('click', function() {
		$('#body_7').show();
	});
	$('#close_7').bind('click', function() {
		$('#body_7').hide();
	} );
	
	$('#open_8').bind('click', function() {
		$('#body_8').show();
	});
	$('#close_8').bind('click', function() {
		$('#body_8').hide();
	} );
	
	$('#open_9').bind('click', function() {
		$('#body_9').show();
	});
	$('#close_9').bind('click', function() {
		$('#body_9').hide();
	} );
	
	$('#open_10').bind('click', function() {
		$('#body_10').show();
	});
	$('#close_10').bind('click', function() {
		$('#body_10').hide();
	} );
	    
});
</script>

</head>

<body>

<div class="yui3-g" id="container" >

<?php
	$page = 'customer_service.php';
	require_once 'includes/nav.php';
	if ( isset( $_SESSION['messages']))
{
	$output = '';
	$output = '<div class="ib_messages">';
	// $output .= print_r( $_SESSION['messages'], true );
	$output .= '<ul>';
	foreach ( $_SESSION['messages'] as $k => $v )
	{
		$output .= "<li>$v</li>";
	}
	$output .= '</ul>';
	unset( $_SESSION['messages']);
	$output .= '</div>';
	print $output;
}
?>
        <div class="yui3-u" id="main">
<h1>Customer Service - Remove from mailing list</h1>
<div class="whiteblock">
<div>
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
<form action="do_not_contact.php" method="post" name="frm1" id="frm1" autocomplete="off">
<?php 

$output = '';

for ( $i = 1; $i <= 10; $i++ )
{
	$stateSelect = '<select name="state_' .$i . '" id="state_' . $i . '">';
	$stateSelect .= '  <option value="">Choose state: </option>';
	for ( $j = 0; $j < count($states); $j++ )
	{
		$state = $states[$j];
		$stateSelect .= '<option value="' . $state->abbrev . '">' . $state->name .'</option>';
	}

$stateSelect .= '</select>';

	if ( $i == 1 )
	{
		$style = ' style="width: 450px;" ';
	}
	else
	{
		$style = ' style="width: 450px; display: none;" ';
	}
	
$output .= <<<EOL
	<div id="div_dnc_{$i}"  {$style} class="padding_bottom">
		<div id="title_{$i}">
			<table cellspacing="0" class="admintable" width="100%" >
			<tr>
		        	<td bgcolor="#666666" class="formheader">
					<span style="color:#fff;font-weight:bold">{$i}) Address to remove:</span>		        	
		        	</td>
		        	<td bgcolor="#666666" style="text-align: right"><span id="open_{$i}"><a href="javascript: void(0);" class="tabtext">+Open</a> /</span> <span id="close_{$i}"><a class="tabtext" href="javascript: void(0);">-Close</a></span>
		        	</td>
			</tr>
			</table>
		</div>
		<div id="body_{$i}">
		<table cellspacing="0" class="admintable">
			<tr>
				<td><label for="name_first_{$i}">First Name: </label></td>
				<td>
					<input id="name_first_{$i}" name="name_first_{$i}" style="width:350px;" />
				</td>
			</tr>
			<tr>
				<td bgcolor="#efefef"><label for="name_last_{$i}">Last Name: </label></td>
				<td bgcolor="#efefef">
					<input name="name_last_{$i}" id="name_last_{$i}" style="width:350px;" />
				</td>
			</tr>
			<tr>
				<td><label for="addr1_{$i}">Address 1: </label></td>
				<td>
		      			<input type="text" name="addr1_{$i}" id="addr1_{$i}" style="width:350px;" >
		  		</td>
			</tr>
			<tr>
				<td bgcolor="#efefef" ><label for="addr2_{$i}">Address 2:</label> </td>
				<td bgcolor="#efefef" >
		      			<input type="text" name="addr2_{$i}" id="addr2_{$i}" style="width:350px;" >
		  		</td>
			</tr>
			<tr>
				<td><label for="city_{$i}">City:</label> </td>
				<td>
					<input name="city_{$i}" id="city_{$i}" style="width:350px;" >
				</td>
			</tr>
			<tr>
				<td bgcolor="#efefef" ><label>State:</label> </td>
				<td bgcolor="#efefef" >
					$stateSelect
				</td>
			</tr>
			<tr>
				<td><label for="zip5_{$i}">Zip 5:</label> </td>
				<td>
					<input name="zip5_{$i}" id="zip5_{$i}" type="text" size="8" maxlength="5" />   
				</td>
			</tr>
			<tr>
				<td bgcolor="#efefef"><label for="email_{$i}">Email address:</label></td>
				<td bgcolor="#efefef">
					<input type="text" id="email_{$i}" name="email_{$i}" style="width:350px;" >
				</td>
			</tr>
			<tr>
				<td ><label for="phone_{$i}">Phone</label></td>
				<td >
				<!--	<input type="text" id="phone_{$i}" name="phone_{$i}" style="width:350px;" >-->
                                 
                               (<input name="Service_phone_number_prefix_{$i}" type="text" id="Service_phone_number_prefix_{$i}"   size="4" maxlength="3" >)
					<input name="Service_phone_number_first_{$i}" type="text" id="Service_phone_number_first_{$i}"  size="4" maxlength="3" > -
					<input name="Service_phone_number_last_{$i}" type="text" id="Service_phone_number_last_{$i}"   size="6" maxlength="4" >
                                
				</td>
			</tr>
			<tr>
			<td>&nbsp;</td>
			<td><input type="checkbox" name="add_{$i}" id="add_{$i}" class="cb_to_bind" value="{$i}"><label for="add_{$i}"> Add another?</label></td>
			</tr>
		</table>
		</div>
		
	</div>	
	
EOL;

}
echo $output;

?>
<p class="rep_note">NOTE TO TSR: RETURN TO POINT OF INTERRUPTION.</p>
<?php 
	$widget = new EP_HTML_Form_Widget_RadioBool( 'continue_call', 1 );
	echo $widget->getOutput();
?><br >
	<div id="closure" class="padding_top">
		
	</div>


<input style="margin-top: 2em; margin-bottom: 3em;" type="button" onClick="validateForm();" name="frm_submit" id="frm_submit" value="Submit"  >
</form>
</div>
</div>



        </div>
<?php
        require_once 'includes/statusbar.php';
?>
</div>
<?php
        require_once 'includes/footer.php';
?>

 


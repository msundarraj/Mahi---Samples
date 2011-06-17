<?php

//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - State Module//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////

require_once 'includes/main.php';

require_once 'EP/Model/StateMapper.php';
require_once 'EP/HTML/Form/Widget/RadioBool.php';
require_once 'EP/Model/Ib/CallMapper.php';
require_once 'EP/Model/Ib/CallXRegister.php';
require_once 'EP/Model/Ib/CallXRegisterMapper.php';

if ( !isset( $_SESSION['call']))
{
	// find the matching call record
	// and add it to the session
	if ( isset( $_GET['call_id']))
	{
		$callMapper = new EP_Model_Ib_CallMapper();
		$call = $callMapper->fetch( trim( $_GET['call_id'] ));
		if ( !$call )
		{
			die( 'cannot find matching call' );
		}
		
		
		$callId = $call->getId();
		$_SESSION['call'] = $call;
	}
	else
	{
		header("Location: /myinbound/waiting_for_call.php" );
	}
}
else
{
	$call = $_SESSION['call'];
	$callId = $call->getId();
}
$operator = $_SESSION['operator'];
$call->setUsername( $operator->getEmail() );
$call->setRepId( $operator->getId() );
$_SESSION['call'] = $call;


if ( !isset( $_SESSION['call_x_register']))
{
	if ( isset( $_GET['reg_id']))
	{
		$callXRegisterMapper = new EP_Model_Ib_CallXRegisterMapper();
		$callXRegister = $callXRegisterMapper->fetch( trim( $_GET['reg_id']));

		if ( !$callXRegister )
		{
			die( 'cannot find matching call x register record');
		}
		$callXRegisterId = $callXRegister->getId();
		$_SESSION['call_x_register'] = $callXRegister;
	}
	else
	{
		header("Location: /myinbound/waiting_for_call.php" );
	}
}
else
{
	$callXRegister = $_SESSION['call_x_register'];
	$callXRegisterId = $callXRegister->getId();
}

// get the list of states, used for state selection pull down
$stateMapper = new EP_Model_StateMapper();
$states = $stateMapper->fetchAll();

/*
 * If it is a POST
 * Then we're inserting or updating the "register" record
 * For an insert, we also need to update the "calls" table
 * to link up "register" and "calls"
 * and we put the call into the session
 *
 */

if (!empty( $_POST ))
{

	if ( !isset( $_POST['call_id']) || !isset($_POST['disclose_state']) || empty( $_POST['state']))
	{
		$_SESSION['messages'] = 'Please Choose the State Selection';
	}
	else
	{
		if ( isset( $_SESSION['register']))
		{
			$registerIsNew = false;
			$register = $_SESSION['register'];
		}
		else
		{
			$registerIsNew = true;

			$callMapper = new EP_Model_Ib_CallMapper();
			$call = $callMapper->fetch( trim( $_POST['call_id'] ));
			if ( !$call )
			{
				die( 'cannot find matching call' );
			}

			$register = new EP_Model_Register();
		}

		$strec = getRecord('states','id',$_POST['state']);

		////Store the below values for one time////////////////////////////
		if( !isset( $_SESSION['first']['regdate'] )  )
		{
			$regdate=time();
			$register->setRegdate($regdate);
			$_SESSION['first']['regdate']=$register->getRegdate();
		}

		if( !isset($_SESSION['first']['confcode'])  )
		{
			$confcode=ZeroPadString(substr(uniqid('A'),-9,8),8);
			$register->setConfcode($confcode);
			$_SESSION['first']['confcode']=$register->getConfcode();
		}

		if( !isset($_SESSION['first']['sequence'])  )
		{
			$register->setSequence('001');
			$_SESSION['first']['sequence']=$register->getSequence();
		}


		if( !isset($_SESSION['first']['noexport'])  )
		{
			$register->setNoexport('0');
			$_SESSION['first']['noexport']=$register->getNoexport();
		}

		if( !isset($_SESSION['first']['budget']) )
		{
			$register->setBudget('0');
			$_SESSION['first']['budget']=$register->getBudget();
		}

		if( !isset($_SESSION['first']['auth'])  )
		{
			$register->setAuth('0');
			$_SESSION['first']['auth']=$register->getAuth();
		}


		if(!isset($_SESSION['first']['cellcode']))
		{
			$register->setCellcode('10');
			$_SESSION['first']['cellcode']=10;
		}


		if( !isset($_SESSION['first']['nowtime']) )
		{
			$Nowtime=strftime("%H%M%S");
			$register->setNowtime($Nowtime);
			$_SESSION['first']['nowtime']=$register->getNowtime();
		}

		if( !isset($_SESSION['first']['sourceip'])  )
		{
			$register->setSourceip($_SERVER['REMOTE_ADDR']);
			$_SESSION['first']['sourceip']=$register->getSourceip();
		}
/*
		if( !isset($_SESSION['first']['origurl'])  )
		{
			$register->setOrigurl($_SESSION['urlinfo']);
			$_SESSION['first']['origurl ']=$register->getOrigurl();
		}
*/

		if( !isset( $_SESSION['first']['apptype'] ) )
		{
			$register->setApptype('IB');
			$_SESSION['first']['apptype']='IB';
		}

		if( !isset( $_SESSION['first']['marketer'] ) )
		{
			$register->setMarketer('01');
			$_SESSION['first']['marketer']='01';
		}


		if( !isset( $_SESSION['first']['uid'] ) )
		{
			$Uid=ZeroPadString(substr(uniqid('A'),-9,8),8);
			$register->setUid($Uid);
			$_SESSION['first']['uid']=trim($register->getUid());
		}
		/****
		else
		{
			$register->setUid( $_SESSION['first']['uid'] );
			$_SESSION['first']['uid']=trim($register->getUid());
		}
/****/

		if( !isset( $_SESSION['first']['saledate'] ))
		{
			$Today=strftime("%Y%m%d");
			$register->setSaledate($Today);
			$_SESSION['first']['saledate']=$register->getSaledate();
		}

		if( !isset( $_SESSION['first']['entby'] ))
		{
			$_SESSION['first']['entby '] = $register->getEntby();
		}

		if( !isset( $_SESSION['first']['appby'] ))
		{
			$_SESSION['first']['appby'] = $register->getAppby();
		}


		if( !isset( $_SESSION['first']['repid']) )
		{
			$_SESSION['first']['repid'] = $register->getRepid();
		}

		if( !isset( $_SESSION['first']['Introgroup'] ))
		{
			//$register->setIntrogroup=(trim(''));
			//$_SESSION['first']['Introgroup']=$register->getIntrogroup();
		}

		if( !isset( $_SESSION['first']['mkgroup'] ) )
		{
			//$register->setMkgroup=(trim(''));
			//$_SESSION['first']['mkgroup']=$register->getMkgroup();
		}




		////////////////////////////////////////////////////////////////////

		////The Below Values are subjected to change as we traverse the tab////////////////////////////

		$register->setState(trim($strec->abbrev));
		$register->setStateid(trim($strec->id));

		if (!isset( $_SESSION['tabs']['customer']))
		{
			$register->setFirstName(trim($_POST['fname']));
			$register->setLastName(trim($_POST['lname']));
		}

		// add which operator entered this application
		$operator = $_SESSION['operator'];
		$call->setUsername( $operator->getEmail() );
		$call->setRepId( $operator->getId().'0' );
		$_SESSION['call'] = $call;
		$register->setAppby( $operator->getAbbrev() );
		$register->setEntby( $operator->getAbbrev() );
		$register->setVendorid( $operator->getMid() );
		$register->setRepid( $operator->getId() );

		$mapper = new EP_Model_RegisterMapper();
		$result = $mapper->save( $register );

		if ( !$result )
		{
			die( 'error saving' );
		}

		if ( $registerIsNew )
		{
			$callXRegister->setUid( $register->getUid() );
// echo '<pre>' . print_r( $callXRegister, true ) . '</pre>';
			$callXRegisterMapper = new EP_Model_Ib_CallXRegisterMapper();
// echo '<pre>' . print_r( $register, true ) . '</pre>';
// echo '<pre>' . print_r( $_SESSION, true ) . '</pre>';

			$result = $callXRegisterMapper->save( $callXRegister );
			if ( !$result )
			{
				die( 'could not update call x register' );
			}
			// $_SESSION['call'] = $call;
			// $_SESSION['call_register'] = $callXRegister;
		}

		// put the results into the session
		$_SESSION['tabs']['state']['enroll'] = trim( $_POST['enroll'] );
		$_SESSION['tabs']['state']['abbrev'] = $register->getState();

		$_SESSION['tabs']['state']['fname'] = trim($_POST['fname']);
		$_SESSION['tabs']['state']['lname'] = trim($_POST['lname']);

		$_SESSION['tabs']['state']['disclose_state'] = trim( $_POST['disclose_state'] );
		$_SESSION['tabs']['state']['state'] = $register->getStateid();
		$_SESSION['tabs']['state']['enroll']= trim($_POST['enroll']);
		$_SESSION['tabs']['state']['disclose_state'] =trim($_POST['disclose_state']);
		// $_SESSION['tabs']['state']['Vendorid'] =$register->getVendorid();
		// $_SESSION['tabs']['state']['agent_id'] =$operator->getAbbrev();

		$_SESSION['register'] = $register;

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
}	// end if POST


/*
 * If we are revisiting this tab
 * let's try to populate everything
 * that we already know
 */

$stateSession = null;
if ( isset( $_SESSION['tabs']['state']))
{
	$stateSession = $_SESSION['tabs']['state'];
}

$frm_enroll = null;
if ( isset( $stateSession['enroll']) && $stateSession['enroll'] == 1)
{
	$frm_enroll = 1;
}

$frm_fname = '';
if ( isset( $stateSession['fname']))
{
	 $frm_fname = $stateSession['fname'];
}

$frm_lname = '';
if ( isset( $stateSession['lname']))
{
	$frm_lname = $stateSession['lname'];
}

$frm_disclose_state = null;
if ( isset( $stateSession['disclose_state']) && $stateSession['disclose_state'] == 1 )
{
	$frm_disclose_state = 1;
}

$stateId = '';
if ( isset( $stateSession['state']))
{
	$stateId = $stateSession['state'];
}

$readonly='null';
$style='null';
if (isset( $_SESSION['tabs']['customer']) && ($frm_fname!='' || $frm_lname!=''))
{
	$readonly='readonly';
	$style="style='background-color:#eeeeee'";
}


// START the HTML page
require_once 'includes/header.php';
?>
<script type="text/javascript">

function validateForm()
{
	var state = $('#state').val();

	if ( !state || isNaN( parseInt( state ))  )
	{
		messages.push( 'State is required.' );
	}

	if ( hasMessages() )
	{
		showMessages();
		return false;
	}

	if ( this.id == 'btn_continue' )
	{
		$('#next_url').val('select_utility.php');
	}

	$('#frm_state').submit();
}


function global_loadsetting(state)
{
	if( state != '' || state != 'undefined' )
	{
		$('#section_enroll_yes').show();
		$('#section_disclose_state_yes').show();

	}
	else
	{
		$('#section_enroll_yes').hide();
		$('#section_disclose_state_yes').show();
	}
}


$(document).ready(function() {

	  global_loadsetting(<?=$stateId;?>);

	  $('#btn_continue').bind('click', validateForm );
	  $('#btn_save').bind('click', validateForm );
	  $('#btn_log_dispo').bind('click', function(){
			logDispo( '#frm_state');
	  });

	  $('#enroll_yes').bind('click', toggleSectionDisplay );
	  $('#enroll_yes').bind('click', function() {
		  $('#eflag').val('');
		  $('#eflagid').val('');
		$('#section_disclose_state_yes').show();
	  });
	  $('#enroll_no').bind('click', toggleSectionDisplay );
	  $('#enroll_no').bind('click', function() {
		  $('#section_disclose_state_yes').hide();
		  $('#section_disclose_state_no').hide();
		  $('#section_statenotlisted').hide();
		  $('#section_contact_no').hide();
		  $('#section_contact_yes').hide();
		   $('#eflag').val('');
		  $('#eflagid').val('');

	  });
	  $('#disclose_state_yes').bind('click', toggleSectionDisplay );
	  $('#disclose_state_yes').bind('click', function(){
		  $('#eflag').val('');
		  $('#eflagid').val('');

	  });

	  $('#disclose_state_no').bind('click', toggleSectionDisplay );
	  $('#disclose_state_no').bind('click', function(){
		  $('#section_statenotlisted').hide();
		  $('#eflag').val('cust');
		  $('#eflagid').val( 1 );
	  } );
	  $('#state').bind('change', function(){
		  	var val = $('#state').val();
			if ( val == 'other' || val == '--------' )
			{
				$('#section_statenotlisted').show();
			}
			else
			{
				$('#section_statenotlisted').hide();
			}
	  } );
	  $('#contact_yes').bind('click', toggleSectionDisplay );
	  $('#contact_yes').bind('click', function() {
		  $('#eflag').val('cust');
		  $('#eflagid').val( 5 );
	  });
	  $('#contact_no').bind('click', toggleSectionDisplay );
	  $('#contact_no').bind('click', function() {
		  $('#eflag').val( 'dispo' );
		  $('#eflagid').val( 29 );
		  logDispo( '#frm_state');
	  });


	});

</script>
</head>


<body>

<div class="yui3-g" id="container" >

<?php
	$page = basename( __FILE__ );
	require_once 'includes/nav.php';
?>

<div class="yui3-u" id="main">
<form id="frm_state" name="frm_state" method="post" action="select_state.php"  autocomplete="off">
<div class="whiteblock">
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

	$name = '';
        if ( isset( $_SESSION['operator'] ))
        {
            $operator = $_SESSION['operator'];
            $name = $operator->getName();
            unset( $operator );
        }
?>
	<div><p>Hello! My name is <?=$name;?>. Thank you for calling Energy Plus.
	<br >Are you calling to enroll with Energy Plus today?
<?php
	if( $frm_enroll == '' )
	{
		$frm_enroll=1;
	}
	$widget = new EP_HTML_Form_Widget_RadioBool( 'enroll', $frm_enroll );
	echo $widget->getOutput();
?>
	</div>
<div id="section_enroll_yes" >
	<div>
		<p>Great, let's get started!</p>
		<p>Can I please have your name?</p>
	</div>
	<div>
		<p>First Name: <input type="text" name="fname" id="fname" size="15"maxlength="30" value="<?=$frm_fname;?>" <?=$readonly;?> <?=$style;?>></p>
	</div>
	<div>
		<p>Last Name: <input type="text" name="lname" id="lname" size="15" maxlength="30" value="<?=$frm_lname;?>" <?=$readonly;?> <?=$style;?>></p>
	</div>
	<div>
		<p >Can you please tell me the state you are calling from?
	<?php
		if($frm_disclose_state=='')$frm_disclose_state=1;
		$widget = new EP_HTML_Form_Widget_RadioBool( 'disclose_state', $frm_disclose_state );
		echo $widget->getOutput();
	?></p>
	</div>
</div>


<div id="section_disclose_state_yes"  >
	<span class="formdata">State: </span>&nbsp;
		<select name="state" id="state"  >
			<option value="">Select</option>
<?php
	$output = '';
	foreach ( $states as $num => $stateObj )
	{
		if( $stateObj->getId() == $stateId )
		{
			echo '<option value="' . $stateObj->getId() . '" selected="selected" >' . $stateObj->getAbbrev() . "</option>\n";
		}
		else
		{
			echo '<option value="' . $stateObj->getId() . '">' . $stateObj->getAbbrev() . "</option>\n";
		}
	}

	echo $output;
	$output = '';

?>
			<option value="other">Other</option>
		</select>
</div>

<div id="section_statenotlisted" style="display: none;">
	<p class="rep_note">IF STATE IS NOT ON THE LIST</p>
	<p>Unfortunately we are not currently doing business in that state. I would be happy to take down your contact
	information were that to change in the future.
			<?php
				$widget = new EP_HTML_Form_Widget_RadioBool( 'contact' );
				echo $widget->getOutput();
			?>
		</p>
		<div id="section_contact_no" style="display: none;">
			<p class="rep_note">(NOTE TO TSR: CALL WAS AUTO DISPO'D #29 : CALLER OUT OF STATE - DID NOT ADD TO
			ENROLLMENT LIST)</p>
		</div>
		<div id="section_contact_yes" style="display: none;"><p class="rep_note">GO TO CUSTOMER SERVICE HOT KEY AND
		SELECT #5</p></div>
</div>
<div id="section_disclose_state_no" style="display: none;">
	<p>How can I help you today?</p>
	<p class="rep_note">IF CUSTOMER HAS A QUESTION ABOUT AN EXISTING ACCOUNT GO TO CUSTOMER
		SERVICE HOT KEY #1.</p>
</div>

<div id="section_enroll_no" style="display: none;">
	<p>How can I help you today?</p>

	<span class="rep_note">IF CUSTOMER HAS SPECIFIC QUESTIONS ABOUT RATES OR OFFERS:</span><span class="formdata">I would be happy to answer your questions but first I need to
	gather some information so I can provide you with accurate information.</span>
	<p class="rep_note">PROCEED WITH "NAME," "STATE," "UTILITY," AND "OFFER" CAPTURES</p>
	<p class="rep_note">IF CALLER HAS GENERIC QUESTIONS GO TO FAQS. TO CONTINUE ON, SELECT STATE</p>
	<p class="rep_note">IF CALLER IS AN EXISTING CUSTOMER OR RECENTLY ENROLLED:
	GO TO CUSTOMER SERVICE HOT KEY AND SELECT #1</p>
</div>


			<div id="section_buttons" style="padding: 3em 0 3em 0;">
				<input type="hidden" name="eflag" id="eflag" />
				<input type="hidden" name="eflagid" id="eflagid" />
				<input type="hidden" name="next_url" id="next_url" />
				<input type="hidden" name="call_id" id="call_id" value="<?=$callId;?>" />
				<input type="button" class="ib_button" name="btn_continue" id="btn_continue" value="Save and Continue" />
				<input type="button" class="ib_button" name="btn_save" id="btn_save" value="Save" />
				<input type="button" class="ib_button" name="btn_log_dispo" id="btn_log_dispo" value="Log Dispo" >
			</div>
		</div><!--  end whiteblock -->
	</form>
	</div><!--  end main -->

<?php
        require_once 'includes/statusbar.php';
?>
</div> <!--  end container -->
<?php
        require_once 'includes/footer.php';
?>


<?php


//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Customer Service//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////

require_once 'includes/main.php';
require_once 'EP/Model/State.php';
require_once 'EP/Model/StateMapper.php';
require_once 'EP/HTML/Form/Widget/RadioBool.php';
require_once 'EP/Util/Database.php';
require_once 'EP/Model/Ib/Call/Dispo/CategoryMapper.php';
require_once 'EP/Model/Ib/Call/Dispo/Category.php';
require_once 'EP/Model/Ib/Call/DispoMapper.php';
require_once 'EP/Model/Ib/Call/Dispo.php';
require_once 'EP/Model/Ib/CallMapper.php';



if ( !isset( $_SESSION['call'] ))
{
	die('No call');
}

// default customer service number
$telephone = '1-866-964-5672';
$callId = '';
$stateSelectedId = null;
$callXRegister = null;

if ( isset( $_SESSION['tabs']['state']['state']))
{
	$stateSelectedId = $_SESSION['tabs']['state']['state'];
	$stateMapper = new EP_Model_StateMapper();
	$state = $stateMapper->fetch( $stateSelectedId );
	$telephone = $state->getCsTel();
}

$eflag=null;
if ( isset( $_POST['eflag']))
{
	$eflag=$_POST['eflag'];
}

$eflagid=null;
if ( isset( $_POST['eflagid']))
{
	$eflagid=$_POST['eflagid'];
}


if ( isset ( $_SESSION['call']))
{
	$call = $_SESSION['call'];
	$callId = $call->getId();
}

if ( isset( $_SESSION['call_x_register']))
{
	$callXRegister = $_SESSION['call_x_register'];

}


//if ( !empty( $_POST))
//{
	// it's a lead
	if ( !empty( $_POST['lead_eflag']))
	{
		$name_first = '';
		$name_last = '';
		$addr1 = '';
		$addr2 = '';
		$city = '';
		$state = '';
		$zip = '';
		$phone = '';
		$email = '';



		if ( !empty( $_POST['name_first']) && !empty( $_POST['name_last']) && !empty( $_POST['addr1'])
			&& !empty($_POST['city']) && !empty( $_POST['state']) && !empty( $_POST['zip5'])
			&& !empty($_POST['lead_eflag']) && !empty($_POST['lead_eflagid'])  )
		{
		echo "test";
			require_once 'EP/Model/LeadMapper.php';
			require_once 'EP/Model/Lead.php';

			$lead = new EP_Model_Lead( $_POST );

			$dispoId = trim( $_POST['lead_eflagid']);
			$dispoReason = 'auto';

			$callStatus = 3;
			$custServId = null;

			if ( isset( $_POST['reason']))
			{
				$dispoReason = trim( $_POST['reason'] );
			}


			if ( isset( $_POST['end_call_status']))
			{
				$callStatus = (int)trim( $_POST['end_call_status']);
			}

			if ( isset( $_POST['cs_id']) && $_POST['cs_id'] != '' )
			{
				$custServId = (int)trim( $_POST['cs_id']);
			}




			// update the call with our new end status
			// non sale dispostion is status of 3
			$callXRegister->setEndStatusId( $callStatus );
			$callXRegister->setReason( $dispoReason );
			$callXRegister->setCallDispoId( $dispoId );
			$callXRegister->setCallDispoEnhancementId( $custServId );

			$date = date('Y-m-d H:i:s');
			$call->setDateEnded( $date );
			$callXRegister->setDateEnded( $date );

			// start the transaction and save the call
			$db = EP_Util_Database::pdo_connect();
			$db->beginTransaction();

			// get a call mapper
			$callMapper = new EP_Model_Ib_CallMapper();
			$callXRegMapper = new EP_Model_Ib_CallXRegisterMapper();

			// save the lead, then dispo the call
			$leadMapper = new EP_Model_LeadMapper();
			$leadMapper->setDatabaseConnection( $db );
			$lmResult = $leadMapper->save( $lead );

			$callMapper->setDatabaseConnection( $db );
			$cmResult = $callMapper->save( $call );

			$callXRegMapper->setDatabaseConnection( $db );
			$callXRegResult = $callXRegMapper->save ( $callXRegister );

			// check if everything worked
			if ( $cmResult === true && $lmResult === true && $callXRegResult === true )
			{
                // commit then redirect
				$db->commit();
				// header show call end ?da= $asgn->getId()
				$call->endCall();

				header( "Location: ${base_url}myinbound/show_call_end.php?c=" . $call->getId() );
			}
			else
			{

				// roll back and do what?
				$db->rollback();
			}
		}
		else
		{
			$_SESSION['messages'] = 'Invalid form data';
		}
	}
	else	// otherwise we just record the CS info
	{
		if ( !empty( $_POST['submit_form']) && $call )
		{
			$dispoId = trim( $_POST['eflagid']);
			$dispoReason = 'auto';
			$callStatus = 3;


			$callXRegister->setCallDispoId( $dispoId );
			$callXRegister->setReason( $dispoReason );

			// update the call with our new end status
			// non sale dispostion is status of 3
			$callXRegister->setEndStatusId( $callStatus );

			// start the transaction and save the call
			$db = EP_Util_Database::pdo_connect();
			$db->beginTransaction();

			$callMapper = new EP_Model_Ib_CallMapper();
			$callMapper->setDatabaseConnection( $db );
			$cmResult = $callMapper->save( $call );

			$callXRegMapper = new EP_Model_Ib_CallXRegisterMapper();
			$callXRegMapper->setDatabaseConnection( $db );
			$callXRegResult = $callXRegMapper->save( $callXRegister );

			// check if everything worked
			if ( $cmResult === true  && $callXRegResult === true )
			{
				$call->endCall();

				header( "Location: ${base_url}myinbound/show_call_end.php?c=" . $call->getId() );
			}
		}
		else
		{
			//$_SESSION['messages'] = 'Invalid form data';
		}
	}
//} // end if ( !empty( $_POST))

// make state select box for leads form
$db = EP_Util_Database::pdo_connect();
$sql = "SELECT name, abbrev FROM usstates order by name";
$sth = $db->prepare( $sql );
$sth->setFetchMode( PDO::FETCH_OBJ );
$sth->execute( );
$states = $sth->fetchAll();

$stateSelect = '<select name="state" id="state">';
$stateSelect .= '  <option value="">Choose state: </option>';
for ( $i = 0; $i < count($states); $i++ )
{
	$state = $states[$i];
	$stateSelect .= '<option value="' . $state->abbrev . '">' . $state->name .'</option>';
}

$stateSelect .= '</select>';

    require_once 'includes/header.php';
?>
<script type="text/javascript">
var downStrokeField = null;
function autojump(fieldName,nextFieldName,fakeMaxLength)
{
	var myForm=document.forms[document.forms.length - 1];
	var myField=myForm.elements[fieldName];
	myField.nextField=myForm.elements[nextFieldName];

	if (myField.maxLength == null)
	   myField.maxLength=fakeMaxLength;

	myField.onkeydown=autojump_keyDown;
	myField.onkeyup=autojump_keyUp;
}

function autojump_keyDown()
{
	this.beforeLength=this.value.length;
	downStrokeField=this;
}

function autojump_keyUp()
{
	if (
	   (this == downStrokeField) &&
	   (this.value.length > this.beforeLength) &&
	   (this.value.length >= this.maxLength)
	   )
   	this.nextField.focus();
	downStrokeField=null;
}

function loadSections(loadvalue)
{
	var loadvalue;

	if(loadvalue==1)
	{
		document.getElementById('custsection1a').style.display='';
		document.getElementById('custsection2a').style.display='none';
		document.getElementById('custsection3a').style.display='none';
		document.getElementById('custsection4a').style.display='none';
		document.getElementById('custsection5a').style.display='none';
		document.getElementById('custsection6a').style.display='none';
		document.getElementById('custsection7a').style.display='none';
		document.getElementById('custsection8a').style.display='none';
		document.getElementById('custsection9a').style.display='none';
		document.getElementById('custsection10a').style.display='none';
	}
	else if(loadvalue==2)
	{
		document.getElementById('custsection2a').style.display='';
		document.getElementById('custsection10a').style.display='none';
		document.getElementById('custsection1a').style.display='none';
		document.getElementById('custsection3a').style.display='none';
		document.getElementById('custsection4a').style.display='none';
		document.getElementById('custsection5a').style.display='none';
		document.getElementById('custsection6a').style.display='none';
		document.getElementById('custsection7a').style.display='none';
		document.getElementById('custsection8a').style.display='none';
		document.getElementById('custsection9a').style.display='none';
	}
	else if(loadvalue==3)
	{
		document.getElementById('custsection3a').style.display='';
		document.getElementById('type_id').value='2';
		document.getElementById('cs_id').value='3';
		document.getElementById('lead_eflagid').value='84';
		document.getElementById('custsection3a_closure').show();
		document.getElementById('custsection5a_closure').style.display='none';
		document.getElementById('custsection6a_closure').style.display='none';
		document.getElementById('custsection7a_closure').style.display='none';
		document.getElementById('custsection10a').show();
		document.getElementById('custsection1a').style.display='none';
		document.getElementById('custsection2a').style.display='none';
		document.getElementById('custsection4a').style.display='none';
		document.getElementById('custsection5a').style.display='none';
		document.getElementById('custsection6a').style.display='none';
		document.getElementById('custsection7a').style.display='none';
		document.getElementById('custsection8a').style.display='none';
		document.getElementById('custsection9a').style.display='none';
	}
	else if(loadvalue==4)
	{
		document.getElementById('custsection4a').style.display='';
		document.getElementById('custsection1a').style.display='none';
		document.getElementById('custsection2a').style.display='none';
		document.getElementById('custsection3a').style.display='none';
		document.getElementById('custsection5a').style.display='none';
		document.getElementById('custsection6a').style.display='none';
		document.getElementById('custsection7a').style.display='none';
		document.getElementById('custsection8a').style.display='none';
		document.getElementById('custsection9a').style.display='none';
		document.getElementById('custsection10a').style.display='none';
	}
	else if(loadvalue==5)
	{
		document.getElementById('custsection5a').style.display='';
		document.getElementById('custsection10a').style.display='';
		document.getElementById('type_id').value='3';
		document.getElementById('lead_eflagid').value='30';
		document.getElementById('custsection3a_closure').style.display='none';
		document.getElementById('custsection5a_closure').style.display='';
		document.getElementById('custsection6a_closure').style.display='none';
		document.getElementById('custsection7a_closure').style.display='none';
		document.getElementById('custsection1a').style.display='none';
		document.getElementById('custsection2a').style.display='none';
		document.getElementById('custsection3a').style.display='none';
		document.getElementById('custsection4a').style.display='none';
		document.getElementById('custsection6a').style.display='none';
		document.getElementById('custsection7a').style.display='none';
		document.getElementById('custsection8a').style.display='none';
		document.getElementById('custsection9a').style.display='none';
	}
	else if(loadvalue==6)
	{
		document.getElementById('custsection6a').style.display='';
		document.getElementById('custsection10a').style.display='';
		document.getElementById('type_id').value='4';
		document.getElementById('lead_eflagid').value='32';
		document.getElementById('custsection3a_closure').style.display='none';
		document.getElementById('custsection5a_closure').style.display='none';
		document.getElementById('custsection6a_closure').style.display='';
		document.getElementById('custsection7a_closure').style.display='none';
		document.getElementById('custsection1a').style.display='none';
		document.getElementById('custsection2a').style.display='none';
		document.getElementById('custsection3a').style.display='none';
		document.getElementById('custsection4a').style.display='none';
		document.getElementById('custsection5a').style.display='none';
		document.getElementById('custsection7a').style.display='none';
		document.getElementById('custsection8a').style.display='none';
		document.getElementById('custsection9a').style.display='none';
	}
	else if(loadvalue==7)
	{

		document.getElementById('custsection7a').style.display='';
		document.getElementById('custsection10a').style.display='';
		document.getElementById('type_id').value='5';
		document.getElementById('lead_eflagid').value='36';
		document.getElementById('custsection3a_closure').style.display='none';
		document.getElementById('custsection5a_closure').style.display='none';
		document.getElementById('custsection6a_closure').style.display='none';
		document.getElementById('custsection7a_closure').style.display='';
		document.getElementById('custsection1a').style.display='none';
		document.getElementById('custsection2a').style.display='none';
		document.getElementById('custsection3a').style.display='none';
		document.getElementById('custsection4a').style.display='none';
		document.getElementById('custsection5a').style.display='none';
		document.getElementById('custsection6a').style.display='none';
		document.getElementById('custsection8a').style.display='none';
		document.getElementById('custsection9a').style.display='none';
	}
	else if(loadvalue==8)
	{
		document.getElementById('custsection8a').style.display='';
		document.getElementById('custsection1a').style.display='none';
		document.getElementById('custsection2a').style.display='none';
		document.getElementById('custsection3a').style.display='none';
		document.getElementById('custsection4a').style.display='none';
		document.getElementById('custsection5a').style.display='none';
		document.getElementById('custsection6a').style.display='none';
		document.getElementById('custsection7a').style.display='none';
		document.getElementById('custsection9a').style.display='none';
		document.getElementById('custsection10a').style.display='none';
	}
	else if(loadvalue==9)
	{
		document.getElementById('custsection9a').style.display='';
		document.getElementById('custsection1a').style.display='none';
		document.getElementById('custsection2a').style.display='none';
		document.getElementById('custsection3a').style.display='none';
		document.getElementById('custsection4a').style.display='none';
		document.getElementById('custsection5a').style.display='none';
		document.getElementById('custsection6a').style.display='none';
		document.getElementById('custsection7a').style.display='none';
		document.getElementById('custsection8a').style.display='none';
		document.getElementById('custsection10a').style.display='none';
	}
	else
	{
	//	document.getElementById('custsection10a').style.display='';

	}
}

function updateSections()
{
      var reason = document.getElementById("frm_reason");
      var reason_EndCall = document.getElementById("frm_reason_EndCall");
      var sectionone_a_yes = document.getElementById("section_one_a_yes");
      var sectionone_a_no = document.getElementById("section_one_a_no");
      var sectionone_a_no_no = document.getElementById("section_one_a_no_no");
      var sectionone_a_no_yes = document.getElementById("section_one_a_no_yes");
      var custsection2a2   = document.getElementById("custsection2atwo");
      var custsection2a3Yes = document.getElementById("custsection2athreeYes");
      var custsection2a3No = document.getElementById("custsection2athreeNo");
      var sectionfour_b_yes = document.getElementById("section_four_b_yes");
      var sectionfour_b_no = document.getElementById("section_four_b_no");
      var section_four_b_no_no  = document.getElementById("section_four_b_no_no");
      var section_four_b_no_yes  = document.getElementById("section_four_b_no_yes");
      var sectioneight_a_yes = document.getElementById("section_eight_a_yes");
      var sectioneight_a_no = document.getElementById("section_eight_a_no");
      var sectioneight_a_no_no = document.getElementById("section_eight_a_no_no");
      var sectioneight_a_no_yes = document.getElementById("section_eight_a_no_yes");


	sectionone_a_yes.style.display = "none";
       sectionone_a_no.style.display = "none";
       sectionone_a_no_no.style.display = "none";
       sectionone_a_no_yes.style.display = "none";
       custsection2a2.style.display = "none";
       custsection2a3Yes.style.display = "none";
       custsection2a3No.style.display = "none";
       sectionfour_b_yes.style.display = "none";
       sectionfour_b_no.style.display = "none";
       section_four_b_no_no.style.display = "none";
       section_four_b_no_yes.style.display = "none";
 	sectioneight_a_yes.style.display = "none";
  	sectioneight_a_no.style.display = "none";
  	sectioneight_a_no_no.style.display = "none";
       sectioneight_a_no_yes.style.display = "none";


	$(':checked').attr('checked', false);


	switch ( parseInt(this.value) )
	{
		case 1:
		  	reason.style.display = "none";
                     reason_EndCall.style.display = "none";
			$('#custsection1a').show();
			$('#custsection2a').hide();
			$('#custsection3a').hide();
			$('#custsection4a').hide();
			$('#custsection5a').hide();
			$('#custsection6a').hide();
			$('#custsection7a').hide();
			$('#custsection8a').hide();
			$('#custsection9a').hide();
			$('#custsection10a').hide();
			break;
		case 2:
			reason.style.display = "none";
                     reason_EndCall.style.display = "none";
			$('#custsection2a').show();
			$('#custsection10a').hide();
			$('#custsection1a').hide();
			$('#custsection3a').hide();
			$('#custsection4a').hide();
			$('#custsection5a').hide();
			$('#custsection6a').hide();
			$('#custsection7a').hide();
			$('#custsection8a').hide();
			$('#custsection9a').hide();
			break;
		case 3:
			reason.style.display = "none";
                     reason_EndCall.style.display = "none";
			$('#custsection3a').show();
			$('#custsection3a_closure').show();
			$('#custsection5a_closure').hide();
			$('#custsection6a_closure').hide();
			$('#custsection7a_closure').hide();


			$('#cs_id').val( 3 );
			$('#lead_eflagid').val( 84 );
			$('#type_id').val( 2 );

			$('#custsection10a').show();
			$('#custsection1a').hide();
			$('#custsection2a').hide();
			$('#custsection4a').hide();
			$('#custsection5a').hide();
			$('#custsection6a').hide();
			$('#custsection7a').hide();
			$('#custsection8a').hide();
			$('#custsection9a').hide();
			break;
		case 4:
   			reason.style.display = "none";
			reason_EndCall.style.display = "none";
			$('#custsection4a').show();
			$('#custsection1a').hide();
			$('#custsection2a').hide();
			$('#custsection3a').hide();
			$('#custsection5a').hide();
			$('#custsection6a').hide();
			$('#custsection7a').hide();
			$('#custsection8a').hide();
			$('#custsection9a').hide();
			$('#custsection10a').hide();
			break;
		case 5:
			reason.style.display = "none";
                     reason_EndCall.style.display = "none";
			$('#custsection5a').show();
			$('#custsection10a').show();
			$('#custsection3a_closure').hide();
			$('#custsection5a_closure').show();
			$('#custsection6a_closure').hide();
			$('#custsection7a_closure').hide();

			//$('#cs_id').val( 5 );
			$('#lead_eflagid').val( 30 );
			$('#type_id').val( 3 );

			$('#custsection1a').hide();
			$('#custsection2a').hide();
			$('#custsection3a').hide();
			$('#custsection4a').hide();
			$('#custsection6a').hide();
			$('#custsection7a').hide();
			$('#custsection8a').hide();
			$('#custsection9a').hide();
			break;
		case 6:
  			reason.style.display = "none";
			reason_EndCall.style.display = "none";
			$('#custsection6a').show();
			$('#custsection10a').show();
			$('#custsection3a_closure').hide();
			$('#custsection5a_closure').hide();
			$('#custsection6a_closure').show();
			$('#custsection7a_closure').hide();

			//$('#cs_id').val( 6 );
			$('#lead_eflagid').val( 32 );
			$('#type_id').val( 4 );

			$('#custsection1a').hide();
			$('#custsection2a').hide();
			$('#custsection3a').hide();
			$('#custsection4a').hide();
			$('#custsection5a').hide();
			$('#custsection7a').hide();
			$('#custsection8a').hide();
			$('#custsection9a').hide();
			break;
		case 7:
                     reason.style.display = "none";
			reason_EndCall.style.display = "none";
			$('#custsection7a').show();
			$('#custsection10a').show();
			$('#custsection3a_closure').hide();
			$('#custsection5a_closure').hide();
			$('#custsection6a_closure').hide();
			$('#custsection7a_closure').show();

			//$('#cs_id').val( 7 );
			$('#lead_eflagid').val( 36 );
			$('#type_id').val( 5 );

			$('#custsection1a').hide();
			$('#custsection2a').hide();
			$('#custsection3a').hide();
			$('#custsection4a').hide();
			$('#custsection5a').hide();
			$('#custsection6a').hide();
			$('#custsection8a').hide();
			$('#custsection9a').hide();
			break;
		case 8:
                     reason.style.display = "none";
			reason_EndCall.style.display = "none";
			$('#custsection8a').show();
			$('#custsection1a').hide();
			$('#custsection2a').hide();
			$('#custsection3a').hide();
			$('#custsection4a').hide();
			$('#custsection5a').hide();
			$('#custsection6a').hide();
			$('#custsection7a').hide();
			$('#custsection9a').hide();
			$('#custsection10a').hide();



			break;
		case 9:
			$('#custsection9a').show();
                      createDispoButton( 27,10);

                     reason.style.display = "block";
			$('#custsection1a').hide();
			$('#custsection2a').hide();
			$('#custsection3a').hide();
			$('#custsection4a').hide();
			$('#custsection5a').hide();
			$('#custsection6a').hide();
			$('#custsection7a').hide();
			$('#custsection8a').hide();
			$('#custsection10a').hide();
			break;
		case 10:
			/* $('#custsection10a').show();
			$('#custsection1a').hide();
			$('#custsection2a').hide();
			$('#custsection3a').hide();
			$('#custsection4a').hide();
			$('#custsection5a').hide();
			$('#custsection6a').hide();
			$('#custsection7a').hide();
			$('#custsection8a').hide();
			$('#custsection9a').hide();
			*/
			break;
	}
}

function createDispoButton( dispoId, csId )
{
       var reason_EndCall= document.getElementById("frm_reason_EndCall");
	$('#end_call').show();
       reason_EndCall.style.display = "block";
	$('#eflag').val('dispo');
	$('#eflagid').val( dispoId );
	$('#end_cs_id').val( csId );
}




function validateForm( form )
{
	//alert('Thank you for calling Energy Plus and have a nice day.');
	$('#' + form).submit();
}

function validateLeadForm()
{
	var name_first = $('#name_first').val();
	var name_last = $('#name_last').val();
	var addr1 = $('#addr1').val();
	var addr2 = $('#addr2').val();
	var city = $('#city').val();
	var state = $('#state').val();
	var zip5 = $('#zip5').val();
	var email = $('#email').val();
       var phone_prefix =  $('#Service_phone_number_prefix').val();
       var phone_first =  $('#Service_phone_number_first').val();
       var phone_last =  $('#Service_phone_number_last').val();
	var phone = phone_prefix + "" + phone_first + "" + phone_last ;

	var form = $('#frm_leads');




	if ( !name_first )
	{
		alert('Please Enter First Name');
              $('#name_first').focus();
           	return false;
	}

       if ( !name_last  )
	{
		alert('Please Enter Last Name');
              $('#name_last').focus();
		return false;
	}

       if ( !addr1   )
	{
		alert('Please Enter Address');
              $('#addr1').focus();
		return false;
	}

       if ( !city)
	{
		alert('Please Enter City');
              $('#city').focus();
		return false;
	}

       if ( !state)
	{
		alert('Please choose State');
              $('#state').focus();
		return false;
	}

       if ( !zip5)
	{
		alert('Please Enter ZipCode');
              $('#zip5').focus();
		return false;
	}

     	if ( name_first.length < 2 || name_last.length < 2 || addr1.length < 2 || city.length < 2 || state.length < 2 )
	{
		alert('The name and address fields must be completed.');
		return false;
	}

       if(document.getElementById('Service_phone_number_prefix').value =='')
	{
               alert('Please enter valid Phone number');
		 document.getElementById('Service_phone_number_prefix').focus();
               return false;
       }

        var s =  document.getElementById('Service_phone_number_prefix').value;

         if (s.length < 3)
         {
          alert('Please enter valid Phone number');
	   document.getElementById('Service_phone_number_prefix').focus();
          return false;
         }
         for (i = 0; i < s.length; i++)
         {
           // Check that current character is number.
          var c = s.charAt(i);
          if (((c < "0") || (c > "9")))
          {
          alert('Please enter valid Phone number');
	   document.getElementById('Service_phone_number_prefix').focus();
          return false;
          }
         }




	  if(document.getElementById('Service_phone_number_first').value =='')
         {
             alert('Please enter valid Phone number');
	      document.getElementById('Service_phone_number_first').focus();
             return false;
         }

         var s = document.getElementById('Service_phone_number_first').value;
         if (s.length < 3)
         {
          alert('Please enter valid Phone number');
	   document.getElementById('Service_phone_number_first').focus();
          return false;
         }

         for (i = 0; i < s.length; i++)
         {
          // Check that current character is number.
          var c = s.charAt(i);
          if (((c < "0") || (c > "9")))
          {
          alert('Please enter valid Phone number');
	   document.getElementById('Service_phone_number_first').focus();
          return false;
          }
         }


	  if(document.getElementById('Service_phone_number_last').value =='')
         {
              alert('Please enter valid Phone number');
		document.getElementById('Service_phone_number_last').focus();
              return false;
         }


         var s = document.getElementById('Service_phone_number_last').value;
          if (s.length < 4)
         {
          alert('Please enter valid Phone number');
	   document.getElementById('Service_phone_number_last').focus();
          return false;
         }

         for (i = 0; i < s.length; i++)
         {
          // Check that current character is number.
          var c = s.charAt(i);
          if (((c < "0") || (c > "9")))
          {
          alert('Please enter valid Phone number');
	   document.getElementById('Service_phone_number_last').focus();
          return false;
         }
        }


         if(document.getElementById('email').value !='')
	  {

			var x=document.getElementById('email').value;
			var atpos=x.indexOf("@");
			var dotpos=x.lastIndexOf(".");

			if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
  			{
  				alert("Not a valid e-mail address");
				document.getElementById('email').focus();
  				return false;
  			}
	 }

	alert('Thank You for calling Energy Plus.  Have a nice day.');
	form.submit();
}

function isInteger(s)
{   var i;
     alert(s);
    for (i = 0; i < s.length; i++)
    {
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9")))
        {
          alert('Please enter a Valid Phone Number');
          return false;
        }
    }
    // All characters are numbers.
    return true;
}

$(document).ready(function() {

	 $('#btn_end_call').bind('click', function() {
		 alert('Thank You for calling Energy Plus.  Have a nice day.');
			validateForm( 'frm_end_call' );
		});
	  $('#frm_leads_submit').bind('click', function() {

				validateLeadForm( );
			});

	  $('#one_a_yes').bind('click', toggleSectionDisplay );
	  $('#one_a_yes').bind('click', function() {
			createDispoButton( 27, 1 );
	  });
	  $('#one_a_no').bind('click', toggleSectionDisplay );
	  $('#one_a_no').bind('click', function() {
		  $('#end_call').hide();
	  });
	  $('#one_a_no_yes').bind('click', toggleSectionDisplay );
	    $('#one_a_no_yes').bind('click', function() {
		createDispoButton( 27, null );
		})

	  $('#one_a_no_no').bind('click', toggleSectionDisplay );
	  $('#one_a_no_no').bind('click', function() {
			createDispoButton( 28, 1 );
	  });

	  $('#two_a_yes').bind('click', function() {
		  window.location.href = 'do_not_contact.php';
                $('#custsection2atwo').hide();

	  });
	  $('#two_a_no').bind('click', function() {
                $('#custsection2atwo').show();
	  });

        $('#two_a2_yes').bind('click', function() {
		  $('#custsection2athreeNo').hide();
                $('#custsection2athreeYes').show();
                 createDispoButton( 27, null );
	  });
	  $('#two_a2_no').bind('click', function() {
                alert('Thank You for calling Energy Plus.  Have a nice day.');
                $('#custsection2athreeNo').show();
                $('#custsection2athreeYes').hide();
               window.location.href = 'dispositions.php?eflagid=81';


	  });

	  $('#four_b_yes').bind('click', toggleSectionDisplay );
	  $('#four_b_yes').bind('click', function() {
                $('#section_four_b_no').hide();
                $('#section_four_b_yes').show();
                $('#section_four_b_no_no').hide();
                $('#section_four_b_no_yes').hide();
                $('input[id=four_b_no_no]').attr('checked', false);
                $('input[id=four_b_no_yes]').attr('checked', false);
		 createDispoButton( 27, 6 );
		});
	  $('#four_b_no').bind('click', toggleSectionDisplay );
           $('#four_b_no').bind('click', function() {
                $('#section_four_b_no').show();
                $('#section_four_b_yes').hide();
                $('#end_call').hide();

		});

	  $('#four_b_no_yes').bind('click', toggleSectionDisplay );
            $('#four_b_no_yes').bind('click', function() {
		createDispoButton( 28, 6 );
		});
	  $('#four_b_no_no').bind('click', toggleSectionDisplay );
		$('#four_b_no_no').bind('click', function() {
		createDispoButton( 28, 6 );
		alert('Thank You for calling Energy Plus.  Have a nice day.');
		validateForm( 'frm_end_call' );
		});
	  $('#eight_a_yes').bind('click', toggleSectionDisplay );
             	$('#eight_a_yes').bind('click', function() {
		createDispoButton( 27, 8 );
		});

	  $('#eight_a_no').bind('click', toggleSectionDisplay );
	  $('#eight_a_no_yes').bind('click', toggleSectionDisplay );
         	$('#eight_a_no_yes').bind('click', function() {
		createDispoButton( 27, null );
		});

	  $('#eight_a_no_no').bind('click', toggleSectionDisplay );
 	  $('#eight_a_no_no').bind('click', function() {
		alert('Thank you for calling Energy Plus and have a nice day.');
                  $('#eflag').val('dispo');
	           $('#eflagid').val( 33 );
	           $('#end_cs_id').val( 8 );
                   validateForm( 'frm_end_call' );
	 });



	  $('#status').bind('change', updateSections );

});
</script>

</head>

<body onLoad="loadSections('<?=$eflagid;?>')">

<div class="yui3-g" id="container" >

<?php
	$page = basename( __FILE__ );
	require_once 'includes/nav.php';
?>
        <div class="yui3-u" id="main">
<!--
<form id="inbound_state" name="inbound_state" method="post" action="select_utility.php">
-->
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

		<h1>Customer Service</h1>
		<div style="padding-bottom: 1em; ">
			<select name='status' id='status' >
				<option>Choose</option>
				<option value="1" <?if($eflagid=='1'){echo 'Selected';}?>>1. CUSTOMER HAS CUSTOMER SERVICE QUESTION</option>
				<option value="2" <?if($eflagid=='2'){echo 'Selected';}?>>2. CALLER REQUESTS TO BE REMOVED FROM MAILING LIST</option>
				<option value="3" <?if($eflagid=='3'){echo 'Selected';}?>>3. CALLER REQUESTS NOTIFICATION FOR NATURAL GAS</option>
				<option value="4" <?if($eflagid=='4'){echo 'Selected';}?>>4. SIGN UP FOR REFERRAL PROGRAM</option>
				<option value="5" <?if($eflagid=='5'){echo 'Selected';}?>>5. NO SERVICE DUE TO STATE</option>
				<option value="6" <?if($eflagid=='6'){echo 'Selected';}?>>6. NO SERVICE DUE TO UTILITY</option>
				<option value="7" <?if($eflagid=='7'){echo 'Selected';}?>>7. NO SERVICE DUE TO POR</option>
				<option value="8" <?if($eflagid=='8'){echo 'Selected';}?>>8. IRRATE CUSTOMER WILL NOT PROVIDE SSN #</option>
				<option value="9" <?if($eflagid=='9'){echo 'Selected';}?>>9. NEGATIVE COMMENTS BY THIRD PARTIES</option>
			</select>
		</div>
	<div id="custsection1a" style="display: none;" class="padding_bottom" >
		Unfortunately I do not have access to that information. If you would like I can transfer you to our Customer Service Department.<br >
<?php
	$widget = new EP_HTML_Form_Widget_RadioBool( 'one_a' );
	echo $widget->getOutput();
?>
		<div id="section_one_a_yes" style="display: none;" class="padding_bottom padding_top" >
			Please hold while I transfer you. If for some reason we are disconnected our Customer Service number is <?=$telephone;?>.
			<p class="rep_note">NOTE TO TSR: TRANSFER THE CALLER TO CUSTOMER SERVICE AND CLICK ON END CALL TO AUTO DISPO THE CALL DISPO #27 CALL TRANSFERRED TO CUSTOMER SERVICE/EXISTING CUSTOMER </p>
		</div>

		<div id="section_one_a_no" style="display: none;" class="padding_bottom padding_top">
			Our Customer Service phone number is <?=$telephone;?>.
			They are available Monday through Friday from 9 am to 5 pm ET.  Is there anything else I can help you with today?

<?php
	$widget = new EP_HTML_Form_Widget_RadioBool( 'one_a_no' );
	echo $widget->getOutput();
?>
				<div id="section_one_a_no_no" style="display: none;" class="padding_bottom padding_top">Thank you for calling Energy Plus and have a nice day.
					<p class="rep_note">NOTE TO TSR: CLICK ON END CALL TO AUTO DISPO CALL #28 PROVIDED CS PHONE NUMBER/EXISTING CUSTOMER</p>
				</div>
                <div id="section_one_a_no_yes" style="display: none;" class="padding_bottom">
					<p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p>
				</div>
		</div>
	</div>

	<div id="custsection2a" style="display: none;" class="padding_bottom">
		If I may obtain some information from you, I will see that your name is removed from future solicitation lists.
		Please be aware it can take up to 4 to 6 weeks to remove your name from future solicitations.
		May I have the name and address as it appears on the mailing?
<?php
	$widget = new EP_HTML_Form_Widget_RadioBool( 'two_a' );
	echo $widget->getOutput();
?>
	</div>


        <div id="custsection2atwo" style="display: none;" class="padding_bottom">
			Is there anything else I can do for you today ?
	<?php
		$widget = new EP_HTML_Form_Widget_RadioBool( 'two_a2' );
		echo $widget->getOutput();
	?>

       </div>

      <div id="custsection2athreeYes" style="display: none;" class="padding_bottom">
            <p class="rep_note">NOTE TO TSR: PROBE CUSTOMER</p>
      </div>

      <div id="custsection2athreeNo" style="display: none;" class="padding_bottom">
		 <p class="rep_note">NOTE TO TSR: GO TO DISPO TAB AND SELECT #81 DOES NOT FIT INTO ANY OTHER DISPO</p>

      </div>



	<div id="custsection3a" style="display: none;" class="padding_bottom">

	</div>

	<div id="custsection4a" style="display: none;" class="padding_bottom">

		<div id="custsection4b" class="padding_bottom">
			Unfortunately I do not have access to that information. If you would like I can transfer you to our Customer Service Department.<br >
<?php
	$widget = new EP_HTML_Form_Widget_RadioBool( 'four_b' );
	echo $widget->getOutput();
?>
		</div>

		<div id="section_four_b_yes" style="display: none;" class="padding_bottom">
			Please hold while I transfer you. If for some reason we are disconnected our Customer Service number is <?=$telephone;?>.
			<!--Thank you for calling Energy Plus and have a nice day." -->
			<p class="rep_note">NOTE TO TSR: TRANSFER THE CALLER TO CUSTOMER SERVICE AND DISPOSITION THE CALL DISPO #27 AND CHOOSE CUSTOMER SERVICE DISPO CODE #6 (REFERRAL PROGRAM ENROLLMENT) </p>
		</div>

		<div id="section_four_b_no" style="display: none;" class="padding_bottom">
			Our Customer Service phone number is <?=$telephone;?>.  They are available Monday through Friday from 9am to 5pm ET.
			Is there anything else I can help you with today?
<?php
	$widget = new EP_HTML_Form_Widget_RadioBool( 'four_b_no' );
	echo $widget->getOutput();
?>
		</div>

		<div id="section_four_b_no_no" style="display: none;" class="padding_bottom">
			<!--Thank you for calling Energy Plus and have a nice day. -->
			<p class="rep_note">NOTE TO TSR: Auto DISPO CALL #28 PROVIDED CS PHONE NUMBER</p>
		</div>

		<div id="section_four_b_no_yes" style="display: none;" class="padding_bottom">
			<p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p>
		</div>

	</div>


	<div id="custsection5a" style="display: none;" class="padding_bottom">

	</div>

	<div id="custsection6a" style="display: none;" class="padding_bottom">

	</div>

	<div id="custsection7a" style="display: none;" class="padding_bottom">

	</div>

	<div id="custsection8a" style="display: none;" class="padding_bottom">
		Unfortunately, I do not have the authorization to process your enrollment without your social security number.
		If you would like I can transfer you to our customer service department.
<?php
	$widget = new EP_HTML_Form_Widget_RadioBool( 'eight_a' );
	echo $widget->getOutput();
?>

		<div id="section_eight_a_yes" style="display: none;" class="padding_bottom padding_top">
			Please hold while I transfer you. If for some reason we are disconnected our Customer Service number is <?=$telephone;?>.
			<p class="rep_note">NOTE TO TSR: TRANSFER THE CALLER TO CUSTOMER SERVICE AND CLICK END CALL TO AUTO DISPOSITION THE CALL DISPO #27 CALL TRANSFERRED TO CS/ CALLER WON'T GIVE SS# </p>


		</div>

		<div id="section_eight_a_no" style="display: none;" class="padding_bottom padding_top">
			Is there anything else I can help you with today?
<?php
	$widget = new EP_HTML_Form_Widget_RadioBool( 'eight_a_no' );
	echo $widget->getOutput();
?>
			<div id="section_eight_a_no_no" style="display: none;" class="padding_bottom padding_top">
				<p class="rep_note">NOTE TO TSR: AUTO DISPO #33: CALLER REFUSED TO PROVIDE  SS#</p>
			  </div>
			<div id="section_eight_a_no_yes" style="display: none;" class="padding_bottom padding_top">
				<p class="rep_note">NOTE TO TSR: PROBE CUSTOMER</p>
			</div>



		</div>
	</div>




	<div id="custsection9a" style="display: none;">



	</div>

	<div id="custsection10a" style="display: none;" class="padding_bottom">
		<form action="customer_service.php" method="post" name="frm_leads" id="frm_leads" autocomplete="off">
		<input type="hidden" id="lead_eflagid" name="lead_eflagid" value="<?=$eflagid;?>" >
		<input type="hidden" id="call_id" name="call_id" value="<?=$callId;?>" >
		<input type="hidden" id="lead_eflag" name="lead_eflag" value="cust" >
		<input type="hidden" id="type_id" name="type_id" value="" >
		<input type="hidden" id="cs_id" name="cs_id" >
		<table cellspacing="0" class="admintable">
			<tr>
		        	<td colspan="2" bgcolor="#666666" class="formheader">
					<span style="color:#fff;font-weight:bold">About You:</span>
		        	</td>
			</tr>
			<tr>
				<td><label for="name_first">First Name: </label></td>
				<td>
					<input id="name_first" name="name_first" style="width:350px;" />
				</td>
			</tr>
			<tr>
				<td bgcolor="#efefef"><label for="name_last">Last Name: </label></td>
				<td bgcolor="#efefef">
					<input name="name_last" id="name_last" style="width:350px;" />
				</td>
			</tr>
			<tr>
				<td><label for="addr1">Address 1: </label></td>
				<td>
		      			<input type="text" name="addr1" id="addr1" style="width:350px;" >
		  		</td>
			</tr>
			<tr>
				<td bgcolor="#efefef" ><label for="addr2">Address 2:</label> </td>
				<td bgcolor="#efefef" >
		      			<input type="text" name="addr2" id="addr2" style="width:350px;" >
		  		</td>
			</tr>
			<tr>
				<td>City: </td>
				<td>
					<input name="city" id="city" style="width:350px;" >
				</td>
			</tr>
			<tr>
				<td bgcolor="#efefef" ><label>State:</label> </td>
				<td bgcolor="#efefef" >
					<?=$stateSelect;?>
				</td>
			</tr>
			<tr>
				<td><label for="zip5">Zip:</label> </td>
				<td>
					<input name="zip5" id="zip5" type="text" size="8" maxlength="5" />
				</td>
			</tr>
			<tr>
				<td bgcolor="#efefef"><label for="email">Email address:</label></td>
				<td bgcolor="#efefef">
					<input type="text" id="email" name="email" style="width:350px;" >
				</td>
			</tr>
			<tr>
				<td><label for="phone">Phone</label></td>
				<td>
					<!--<input type="text" id="phone" name="phone" style="width:350px;" >-->
                                	(<input name="Service_phone_number_prefix" type="text" id="Service_phone_number_prefix"   size="4" maxlength="3" >)
					<input name="Service_phone_number_first" type="text" id="Service_phone_number_first"  size="4" maxlength="3" > -
					<input name="Service_phone_number_last" type="text" id="Service_phone_number_last"   size="6" maxlength="4" >
					<input type="hidden" name="c_Service_phone_number_prefix;Service_phone_number_first;Service_phone_number_last" value="3,3,4,Missing/Invalid Service Phone" >
				</td>
			</tr>
			<tr>
				<td colspan="2">
		<div id="custsection3a_closure" style="display: none;">
			<p><span class="rep_note">NOTE TO TSR: ONCE DATA IS CAPTURED:</span> We will place your name on our contact list. We will contact you if we are able to enroll your account in the future. </p>
                     <p class="rep_note">NOTE TO TSR: Auto DISPO #84:  GAS INQUIRY ADD TO ENROLLMENT LIST</p>
		</div>
		<div id="custsection5a_closure" style="display: none;">
			<p><span class="rep_note">Once data is captured:</span> We will place your name on our contact list.  We will contact you if we are able to enroll your account in the future.</p>
			<p class="rep_note">NOTE TO TSR: NO SERVICE DUE TO STATE  AUTO DISPO #30: OUT OF STATE-ADD TO ENROLLMENT LIST</p>
		</div>
		<div id="custsection6a_closure" style="display: none;">
			<p><span class="rep_note">Standardize Once data is captured:</span> We will place your name on our contact list. We will contact you if we are able to enroll your account in the future. </p>
                     <p class="rep_note">NOTE TO TSR: AUTO DISPO #32: UTILITY NOT AVAILABLE - ADD TO ENROLLMENT LIST</p>
		</div>
		<div id="custsection7a_closure" style="display: none;">
			<p><span class="rep_note">Standardize Once data is captured:</span> We will place your name on our contact list. We will contact you if we are able to enroll your account in the future. </p>
                     <p class="rep_note">NOTE TO TSR: AUTO DISPO #36: CALLER IS NOT IN ACCEPTED RATE CLASS-ADD TO ENROLLMENT LIST</p>
		</div>


				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="button" style="margin-bottom: 3em;" name="frm_leads_submit" id="frm_leads_submit" value="Submit"  >
				</td>
			</tr>
		</table>
		</form>
<SCRIPT TYPE="text/javascript">
<!--
autojump('Service_phone_number_prefix', 'Service_phone_number_first', 3);
autojump('Service_phone_number_first', 'Service_phone_number_last', 3);
//-->
</SCRIPT>

	</div>



</div>
</div>

<div id="end_call" style="display: none;" >
<form id="frm_end_call" name="frm_end_call" method="post" action="dispositions.php" autocomplete="off">
	<div style="display:none; border: 1px dotted red; margin-bottom: 1em; padding-bottom: 1em;" >
		<h3> hidden form elements</h3>
		<input type="text" name="eflag" id="eflag" value="<?=$eflag;?>" >
		<input type="text" name="eflagid" id="eflagid" value="<?=$eflagid;?>" >
		<input type="text" name="call_id" id="call_id" value="<?=$callId;?>" >
		<input type="text" name="end_call_status" id="end_call_status" value="3" >
		<input type="text" name="en_dispos" id="end_cs_id" >
		<input type="text" name="submit_form" id="submit_form" value="Submit">
	</div>
        <div id="frm_reason" style="display: none">
			<label for="reason" style="vertical-align: top; width: 100px; align: left">Comments: </label>
			<textarea name="reason" id="reason" style="width: 700px; height:100px;"></textarea>
        </div>

		<div id="frm_reason_EndCall" style="padding-top: 1em;display: none; align: left;" >
		<input style="margin-bottom: 3em; align: left" type="button" class="ib_button" name="btn_end_call" id="btn_end_call" value="End Call" >
		</div>
	</form>
</div>

        </div>
<?php
        require_once 'includes/statusbar.php';
?>
</div>
<?php
        require_once 'includes/footer.php';
?>


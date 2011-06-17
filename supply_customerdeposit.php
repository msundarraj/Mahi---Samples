<?php

//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Deposit Module//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////

require_once 'includes/main.php';

require_once 'EP/HTML/Form/Widget/RadioBool.php';
require_once 'EP/Model/RegisterMapper.php';
require_once 'EP/Util/Database.php';


$register = $_SESSION['register'];
 $regid=$register->getId();
$register_mapper = new EP_Model_RegisterMapper();


require_once("../checkcc2.php");
require_once('../jlnusoap2/nusoapcc.php');
$client = new soapclient($ccpay_url1);
$client->soap_defencoding = 'UTF-8';
$url = $ccpay_url2;
$namespace = 'https://services.ista-billing.com/';

$err = $client->getError();
if ($err)
{
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}


$reg = getRecord('register','id',$regid);
$cfname = $_POST['accfname'];
$clname = $_POST['acclname'];
$cardnum = $_POST['accnum'];
$cardtype = $_POST['cardtype'];
$expyy = $_POST['expyy'];
$expmm = $_POST['expmm'];
$cvv = $_POST['cvv'];
$amt = $_SESSION['depositamount'];
$add1 = $reg->addr1;
$add2 = $reg->addr2;
$city = $reg->city;
$state = 'TX';
$email = $reg->email;
$zip = $reg->zip5;
unset($_POST['accfname']);
unset($_POST['acclname']);
unset($_POST['accnum']);
unset($_POST['cardtype']);
unset($_POST['expyy']);
unset($_POST['expmm']);
unset($_POST['cvv']);
unset($_POST['depamt']);
unset($_POST['regid']);
$payamt = $amt;
$paysrc = 'CC';
if($cardtype == 'American Express') $crdtype = 'AMEX';
if($cardtype == 'MasterCard') $crdtype = 'MC';
if($cardtype == 'Visa') $crdtype = 'VS';
$paymeth = $crdtype;

	$valid = checkCreditCard($cardnum,$cardtype,$errno,$errtxt);
	if(!$valid)
	{
		$msg = $errtxt;
		exit;
	}
	//echo $ccnumber;


$param = array('cfname' => $cfname,'clname' => $clname,'cardtype' => $cardtype,'cardnum' => $cardnum,'expyy' => $expmm,'expmm' => $expyy,
	'amt' => $amt, 'add1' => $add1,'add2' => $add2,'city' => $city,'state' => $state,'zip' => $zip,'email' => $email,'cvv' => $cvv);
$result = $client->call('ProcessCC', array('parameters' => $param),$namespace,$url,'');



// Check for a fault
if ($client->fault)
{
	echo '<h2>Fault</h2><pre>';
	print_r($result);
	echo '</pre>';
}
else
{
	// Check for errors
	$err = $client->getError();
	if ($err)
	{
		// Display the error
		echo '<br /><h2>Unexpected Error</h2><br />We\'re sorry, we seem to be experiencing a technical problem.  Please contact us at 1-877-710-5550 so we may assist you with your application over the phone.';
		echo '<h2>Error</h2><pre>' . $err . '</pre>';
		echo 'RESPONSE -- '.$client->response;
		echo 'REQUEST -- '.$client->request;
	}
	else
	{
		//print_r($client->request);
		//print_r($client->response);
		$myres = $client->response;
		$start = strpos($myres,'<');
		$myxml = substr($myres,$start);
		require_once('../xml/lib.xml.php');
        	$xml = new Xml;
        	$out = $xml->parse($myxml,NULL);
		//print_r($out['soap:Body']['ProcessCCResponse']['ProcessCCResult']['PaymentReturn']['ReturnCode']);
		$code = $out['soap:Body']['ProcessCCResponse']['ProcessCCResult']['PaymentReturn']['ReturnCode'];
		$paymentid = $out['soap:Body']['ProcessCCResponse']['ProcessCCResult']['PaymentReturn']['PaymentID'];
//		echo 'Got return code '.$code.'<br />';
		if(substr($code,0,3) == 'tst') $code = substr($code,3);
//		echo 'Using code '.$code.'<br />';




			if($paymentid < 1)
			{
				$sql = sprintf("select * from cccodes where code='%s'",$code);
				$res = mysql_query($sql,$link);

				if(!mysql_num_rows($res))
				{
					//echo 'No code match<br />';
					$msg = 'Please reconfirm all payment information with customer and re-submit.';
				}
				else
				{
					//echo 'Code match<br />';
					$rec = mysql_fetch_object($res);
					$confcode = $out['soap:Body']['ProcessCCResponse']['ProcessCCResult']['PaymentReturn']['PaymentID'];
					$enrollcustid = $confcode;
					$_SESSION['confcode'] = $confcode;

					if($rec->decision == 'Approved')
						$msg = 'ZERO-'.$confcode;
					else
						$msg = $rec->message;
				}
			}
			else
			{
				$confcode = $code;
				$enrollcustid = $paymentid;
				$_SESSION['confcode'] = $confcode;
				$msg = 'ZERO-'.$confcode;
			}


		if($confcode!='')
		{
			$_SESSION['enrollcustid']=$enrollcustid;
			$_SESSION['Pfname']=$cfname;
			$_SESSION['Plname']=$clname;
			$_SESSION['Paysrc']=$paysrc;
			$_SESSION['Paymeth']=$paymeth;
		}

		echo $msg;
	}
}
?>
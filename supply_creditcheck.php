<?php

//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Credit Check//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////
$register = $_SESSION['register'];
$regid=$register->getId();
$register_mapper = new EP_Model_RegisterMapper();

require_once('../nusoap/soapcli.php');
require_once('../xml/lib.xml.php');

//$regid = $_POST['regid'];
//$regid = 164748;   // TEST ONLY - PLEASE REMOVE
unset($_POST['regid']);
$ssn = $_SESSION['tabs']['account']['ssn1'].$_SESSION['tabs']['account']['ssn2'].$_SESSION['tabs']['account']['ssn3'];
$dob = $_SESSION['tabs']['account']['year'].$_SESSION['tabs']['account']['month'].$_SESSION['tabs']['account']['day'];
//$ssn = '406708805';  // TEST ONLY - PLEASE REMOVE
//$dob = '19570219';  // TEST ONLY - PLEASE REMOVE
$mothermaiden = $_SESSION['tabs']['account']['mother_maiden_name'];
unset($_POST['doby']);
unset($_POST['dobm']);
unset($_POST['dobd']);
unset($_POST['ssn1']);
unset($_POST['ssn2']);
unset($_POST['ssn3']);
$_POST['dob'] = $dob;
//$_POST['ssn'] = $ssn;

//updateRecord($regid,'register',0);
$reg = getRecord('register','id',$regid);
$url    =   'https://198.65.147.180/energypluswebservice/DataviewService.asmx';
$func   =   'ProcessApplication';
$param = '';
$param .= '<SystemFields><BillCode>'.$reg->kWh.'</BillCode><ApplicationType>1</ApplicationType><ServiceAddress>';
if(isset($reg->addr1)) $param .= '<StreetNumber>'.$reg->addr1.'</StreetNumber>';
if(isset($reg->addr2) && strlen($reg->addr2) > 2) $param .= '<StreetName>'.$reg->addr2.'</StreetName>';
if(isset($reg->city)) $param .= '<City>'.$reg->city.'</City>';
if(isset($reg->state)) $param .= '<State>'.$reg->state.'</State>';
if(isset($reg->zip5)) $param .= '<ZIP>'.$reg->zip5.'</ZIP>';
$param .= '</ServiceAddress><BillingAddress>';
if(isset($reg->baddr1)) $param .= '<StreetNumber>'.$reg->baddr1.'</StreetNumber>';
if(isset($reg->baddr2) && strlen($reg->addr2) > 2) $param .= '<StreetName>'.$reg->baddr2.'</StreetName>';
if(isset($reg->bcity)) $param .= '<City>'.$reg->bcity.'</City>';
if(isset($reg->bstate)) $param .= '<State>'.$reg->bstate.'</State>';
if(isset($reg->bzip5)) $param .= '<ZIP>'.$reg->bzip5.'</ZIP>';
$param .= '</BillingAddress><AccountHolderInfo>';
if(isset($reg->first_name)) $param .= '<FirstName>'.$reg->first_name.'</FirstName>';
if(isset($reg->mid_init)) $param .= '<MiddleInit>'.$reg->mid_init.'</MiddleInit>';
if(isset($reg->last_name)) $param .= '<Surname>'.$reg->last_name.'</Surname>';
if(isset($reg->servicephone)) $param .= '<HomePhone>'.$reg->servicephone.'</HomePhone>';
if(isset($reg->billphone)) $param .= '<AlternativePhone>'.$reg->billphone.'</AlternativePhone>';
if(isset($reg->email)) $param .= '<emailAddress>'.$reg->email.'</emailAddress>';
if(isset($reg->dob)) $param .= '<DateofBirth>'.$reg->dob.'</DateofBirth>';
$param .= '<SSN>'.$ssn.'</SSN>';
$param .= '</AccountHolderInfo>';
$param .= '<UtilityCode>'.$reg->distrib.'</UtilityCode>';
$param .= '<State>'.$reg->state.'</State>';
$param .= '<Campaign>'.$reg->campaign.'</Campaign>';
$param .= '<Cell>'.$reg->cellcode.'</Cell>';
$param .= '<PartnerCode>'.$reg->partnercode.'</PartnerCode>';
$param .= '<ISORegion>'.$reg->iso.'</ISORegion>';
$param .= '</SystemFields>';
//echo $param;
$paramarr = array('XmlRequest' => $param);
//print_r($paramarr);
$localsoap = & new SoapCli();
$data = $localsoap->client($url,$func,$paramarr, "http://dvtransaction.com/", false);
if(!$data)
{
	echo 'Error';
	print_r("{$localsoap->error}\n");
	print_r("{$localsoap->dbgstr}\n");
	print_r("{$localsoap->rawRequest}\n");
	print_r("{$localsoap->rawResponse}\n");
}
else
{
//	echo $data;
	$xml = new Xml;
	$out = $xml->parse($data,NULL);
//	print_r($out['DecisionInformation']);
//	print_r($out['ErrorBlock']);
//	echo $out['UniqueID']."\n";
	extract($out['DecisionInformation']);
//	echo $ReasonCode1."\n";
//	echo $ReasonDesc1."\n";
//	echo $ApplicationStatus."\n";

	if(!$DepositAmount) $DepositAmount = 0;
	if($DepositAmount == 4) $DepositAmount = 0;
	//echo $DepositAmount;
	$_SESSION['depositamount'] = $DepositAmount;
	$_SESSION['Credit1'] = ($DepositAmount)?'1':'0';
	$_SESSION['Credit2'] = $out['UniqueID'];
	//updateRecord($regid,'register',0);
}

?>

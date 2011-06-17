<?php


//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Select Account//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////

require_once 'includes/main.php';

require_once 'includes/check_state.php';
require_once 'includes/check_util.php';
require_once 'includes/check_offer.php';
require_once 'includes/check_customer.php';
require_once 'includes/check_billing.php';

require_once 'EP/Model/StateMapper.php';
require_once 'EP/Model/PartnerMapper.php';
require_once 'EP/HTML/Form/Widget/RadioBool.php';
require_once 'EP/Model/RegisterMapper.php';
require_once 'EP/Util/Database.php';
require_once 'EP/Model/Offer.php';


$register = $_SESSION['register'];
$regid=$register->getId();
$register_mapper = new EP_Model_RegisterMapper();

//echo $_SESSION['tabs']['offer']['Partnercode'];

$state = null;

if ( isset( $_SESSION['tabs']['state']['state'] ) )
{
	require_once 'EP/Model/StateMapper.php';
	$mapper = new EP_Model_StateMapper();
	$stateObj = $mapper->fetch( $_SESSION['tabs']['state']['state'] );
	if ( $stateObj )
	{
		$state = $stateObj->getAbbrev();
	}
}


$uid=null;
if(isset($_SESSION['first']['uid']))
{
	$uid=$_SESSION['first']['uid'];
}

$Session_State= null;
if(isset($_SESSION['tabs']['state']['state']))
{
	$Session_State = $_SESSION['tabs']['state']['state'];
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

$Session_Account_Number= null;
if(isset($_SESSION['tabs']['account']['Account_Number']))
{
	 $Session_Account_Number=$_SESSION['tabs']['account']['Account_Number'];
}

$Session_taxexempt_choice= null;
if(isset($_SESSION['tabs']['account']['taxexempt_choice']))
{
	$Session_taxexempt_choice=$_SESSION['tabs']['account']['taxexempt_choice'];
}

$namekey= null;
if(isset($_SESSION['tabs']['account']['namekey']))
{
$namekey=$_SESSION['tabs']['account']['namekey'];
}


$Session_lang_option= null;
if(isset($_SESSION['tabs']['account']['lang_option']))
{
	$Session_lang_option=$_SESSION['tabs']['account']['lang_option'];
}


$Session_greenoption_choice= '000';
if(isset($_SESSION['tabs']['account']['greenoption_choice']))
{
	$Session_greenoption_choice=$_SESSION['tabs']['account']['greenoption_choice'];
}

$Session_Customer_key= null;
if(isset($_SESSION['tabs']['account']['Customer_key']))
{
	$Session_Customer_key=$_SESSION['tabs']['account']['Customer_key'];
}

$Session_Rate_Class= null;
if(isset($_SESSION['tabs']['account']['Rate_Class']))
{
	$Session_Rate_Class=$_SESSION['tabs']['account']['Rate_Class'];
}

$Session_years_inbiz= null;
if(isset($_SESSION['tabs']['account']['years_inbiz']))
{
	$Session_years_inbiz=$_SESSION['tabs']['account']['years_inbiz'];
}

$Session_years_bizaddr= null;
if(isset($_SESSION['tabs']['account']['years_bizaddr']))
{
	$Session_years_bizaddr=$_SESSION['tabs']['account']['years_bizaddr'];
}

$Session_late_payment6= null;
if(isset($_SESSION['tabs']['account']['late_payment6']))
{
	$Session_late_payment6=$_SESSION['tabs']['account']['late_payment6'];
}


$Session_busname_change= null;
if(isset($_SESSION['tabs']['account']['busname_change']))
{
	$Session_busname_change=$_SESSION['tabs']['account']['busname_change'];
}

$Session_elec_supp_prevyear= null;
if(isset($_SESSION['tabs']['account']['elec_supp_prevyear']))
{
	$Session_elec_supp_prevyear=$_SESSION['tabs']['account']['elec_supp_prevyear'];
}

$Session_years_creditbiz= null;
if(isset($_SESSION['tabs']['account']['years_creditbiz']))
{
	$Session_years_creditbiz=$_SESSION['tabs']['account']['years_creditbiz'];
}

$Session_dob= null;
if(isset($_SESSION['tabs']['account']['dob']))
{
	$Session_dob=$_SESSION['tabs']['account']['dob'];
}

$Session_mother_maiden_name= null;
if(isset($_SESSION['tabs']['account']['mother_maiden_name']))
{
	$Session_mother_maiden_name=$_SESSION['tabs']['account']['mother_maiden_name'];
}


$Session_servicereference= null;
if(isset($_SESSION['tabs']['account']['servicereference']))
{
	$Session_servicereference=$_SESSION['tabs']['account']['servicereference'];
}


$Session_spanishbill= null;
if(isset($_SESSION['tabs']['account']['spanishbill']))
{
	$Session_spanishbill=$_SESSION['tabs']['account']['spanishbill'];
}

$Session_meterno= null;
if(isset($_SESSION['tabs']['account']['meterno']))
{
	$Session_meterno=$_SESSION['tabs']['account']['meterno'];
}

$Session_rctext=null;
if(isset($_SESSION['utility']['rctext']))
{
 $Session_rctext=$_SESSION['utility']['rctext'];
}


$Session_utility=null;
if(isset($_SESSION['utility']['utility']))
{
$Session_utility=$_SESSION['utility']['utility'];
}

$utility_abbrev = '';
if ( isset( $_SESSION['utility']['abbrev'] ))
{
  $utility_abbrev = $_SESSION['utility']['abbrev'];
}

$Session_srtext=null;
if(isset($_SESSION['utility']['srtext']))
{
$Session_srtext=$_SESSION['utility']['srtext'];
}


$Session_utility=null;
if(isset($_SESSION['utility']['utility']))
{
$Session_utility=$_SESSION['utility']['utility'];
}

$Session_acctext=null;
if(isset($_SESSION['utility']['acctext']))
{
$Session_acctext=$_SESSION['utility']['acctext'];
}

$Session_acctext_ext=null;
//echo $_SESSION['utility']['acctext_ext'];
if(isset($_SESSION['utility']['acctext_ext']))
{
$Session_acctext_ext=stripslashes($_SESSION['utility']['acctext_ext']);
}

$Session_month='';
if(isset($_SESSION['tabs']['account']['month']))
{
$Session_month=$_SESSION['tabs']['account']['month'];
}

$Session_day='';
if(isset($_SESSION['tabs']['account']['day']))
{
$Session_day=$_SESSION['tabs']['account']['day'];
}

$Session_year='';
if(isset($_SESSION['tabs']['account']['year']))
{
	$Session_year=$_SESSION['tabs']['account']['year'];
}

$spouse_var=null;
if(isset($_SESSION['tabs']['customer']['spfname']))
{
	$Session_spouse_name=$_SESSION['tabs']['customer']['spfname'];
}


$Session_billmeth=null;
if(isset($_SESSION['utility']['billmeth']))
{
	 $Session_billmeth=$_SESSION['utility']['billmeth'];
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

if($Session_spouse_name!='')
{
	$spouse_var="spouse's";
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

$var_bus_taxex=1;
$default_green=null;
if(isset($_SESSION['tabs']['offer']['partner']))
{
	$bus_partnercode = getRecord('partnercode','partnercode',$_SESSION['tabs']['offer']['partner']);
	$bus_taxex 	     = $bus_partnercode->bus_taxex;

	$green_partnercode = getRecord('partnercode','partnercode',$_SESSION['tabs']['offer']['partner']);
	$default_green  = $green_partnercode->default_green;

	if($_SESSION['tabs']['offer']['Partnercode']=='SPG')
	{
		$default_green=3;
	}

}




if(!empty($_POST))
{


		if($Session_State==3)
		{
			if($_POST['year']!='' && $_POST['month']!='' && $_POST['day']!='')
			{
				$dob=$_POST['year']."".$_POST['month']."".$_POST['day'];
			}
		}

		$register->setTaxex(trim($_POST['taxexempt_choice']));


		if(isset($_POST['lang_option']))
		{
			$register->setSpanishbill(trim($_POST['lang_option']));
		}


		if($_POST['hid_default_green']==3)
		{
			$register->setGreenopt('003');
		}
		else if($_POST['hid_default_green']==1)
		{
			$register->setGreenopt('002');
		}
		else
		{
			$register->setGreenopt(trim($_POST['greenoption_choice']));
		}




		if(isset($_POST['namekey']))
		{
			$register->setNamekey(trim($_POST['namekey']));
		}

		if(isset($_POST['Rate_Class']))
		{
			$register->setRateclass(trim($_POST['Rate_Class']));
		}
		else
		{
			$register->setRateclass(trim('000'));
		}


		if($Session_State==3)
		{
			$register->setYearsInbiz(trim($_POST['years_inbiz']));
			$register->setYearsBizaddr(trim($_POST['years_bizaddr']));
			$register->setLatePayment6(trim($_POST['late_payment6']));
			$register->setBusnameChange(trim($_POST['busname_change']));
			$register->setElecSuppPrevyear(trim($_POST['elec_supp_prevyear']));
			$register->setYearsCreditbiz(trim($_POST['years_creditbiz']));
			$register->setDob(trim($dob));
		}
		//These Values are Hard Coded. Mahendran Sundarraj 04/06/2011
		$budget = '0';
		$accept = '1';
		$noexport = '0';

		if($Session_State!=3)
		{
			$register->setResidenceLength('0');
			$register->setEmployeeCount('0');
			$register->setBusinessLength('0');
			$register->setCurrentSupplier('0');
			$register->setPayamt('0');
			$register->setKwh('');

			if($_POST['Account_Number']!='')
			{
				$register->setAccount(trim($_POST['Account_Number']));
			}

			$register->setBaccount(trim($_POST['servicereference']));

			$_SESSION['tabs']['account']['Account_Number']     	 = $register->getAccount();

			$_SESSION['tabs']['account']['servicereference']     = $register->getBaccount();
		}

		$register->setBudget(trim($budget));
		$register->setAccept(trim($accept));
		$register->setNoexport(trim($noexport));

		if($Session_State==3)
		{
			$register->setBillmeth('4');
		}
		else
		{
			$register->setBillmeth(trim($Session_billmeth));
		}

		if($Session_State==3)
		{
			if($_POST['mothermaiden']!='')
			{
				$register->setMothermaiden(trim($_POST['mothermaiden']));
			}
			else if($_POST['mothermaiden']=='')
			{
				$register->setMothermaiden('Energy Plus');
			}
		}

		$_SESSION['tabs']['account']['taxexempt_choice']   = $register->getTaxex();
		$_SESSION['tabs']['account']['lang_option'] 	  = $register->getSpanishbill();

		if($_POST['hid_default_green']==1)
		{
			$_SESSION['tabs']['account']['greenoption_choice'] = '002';
		}
		else
		{
			$_SESSION['tabs']['account']['greenoption_choice'] = $_POST['greenoption_choice'];
		}


		$_SESSION['tabs']['account']['namekey']       	   = $register->getNamekey();

		$_SESSION['tabs']['account']['Rate_Class']         = $register->getRateclass();

		if($Session_State==3)
		{
			$_SESSION['tabs']['account']['years_inbiz']	  	   = $register->getYearsInbiz();
			$_SESSION['tabs']['account']['years_bizaddr']	   = $register->getYearsBizaddr();
			$_SESSION['tabs']['account']['late_payment6']	   = $register->getLatePayment6();
			$_SESSION['tabs']['account']['busname_change']	   = $register->getBusnameChange();
			$_SESSION['tabs']['account']['elec_supp_prevyear'] = $register->getElecSuppPrevyear();
			$_SESSION['tabs']['account']['years_creditbiz']    = $register->getYearsCreditbiz();
			$_SESSION['tabs']['account']['dob']		  		   = $register->getDob();
			$_SESSION['tabs']['account']['month']		  	   = trim($_POST['month']);
			$_SESSION['tabs']['account']['day']		  		   = trim($_POST['day']);
			$_SESSION['tabs']['account']['year']		       = trim($_POST['year']);
			$_SESSION['tabs']['account']['ssn1']		       = trim($_POST['ssn1']);
			$_SESSION['tabs']['account']['ssn2']		       = trim($_POST['ssn2']);
			$_SESSION['tabs']['account']['ssn3']		       = trim($_POST['ssn3']);
			$_SESSION['tabs']['account']['mother_maiden_name'] = $register->getMothermaiden();
		}



		$_SESSION['register'] = $register;

		//$_SESSION['multiple_account']=rtrim($_POST[$account].",");
		//$_SESSION['multiple_service']=rtrim($_POST[$sr].",");


		if($_SESSION['tabs']['offer']['Partnercode']!='')
		{
			$partnerid=$_SESSION['tabs']['offer']['Partnercode'];

			$sql ="select * from partnercode where state=".$Session_State." AND partnercode='$partnerid'";
			if($sql)
			{
				$res = mysql_query($sql,$link);
				$vars = mysql_fetch_assoc($res) or die(mysql_error().'--'.$sql);
				//print_r($vars);
				extract($vars);
			}

			//echo $partnercode;

			if(isset($partnercode))
			{
				$partnerinfo = getRecord('partnercode','id',$partnerid);
				$def_taxex 		= $partnerinfo->bus_taxex;
				$affinity 		= $partnerinfo->affinity;
			}




			$partcode = '';
			if ( isset( $partnercode))
			{
				$partcode = $partnercode;
			}


			$refparts = array('BRD','OHA','IPA','RLT');
			if(!in_array($partcode,$refparts))
			{
				if(isset($_SESSION['tabs']['offer']['Referral']) && strlen($_SESSION['tabs']['offer']['Referral']) > 2)
				{
					$errcode = 'REFERR '.$_SESSION['tabs']['offer']['Referral'].' REMOVED! - AFF='.$affinity;
					$_POST['refid'] = '';
				}
			}
			else
			{
				$errcode = '';
			}

			//print_r($_SESSION);

			$utility = null;
			if ( isset( $_SESSION['utility']))
			{
				$utility = $_SESSION['utility'];
			}

			$utilityAccountType = null;
			if ( isset( $_SESSION['tabs']['utility'] ))
			{
				$utilityAccountType = $_SESSION['tabs']['utility']['acct_type'];
			}

			$partnerType = null;

			if ( isset( $_SESSION['tabs']['offer']['partner_type'] ))
			{
				$partnerType = $_SESSION['tabs']['offer']['partner_type'];
			}

			$firstMonthPrice = null;

			if ( !empty ( $partnerType ) && !empty( $utilityAccountType ) && !empty( $utility ) )
			{
				$utilityCode = $utility['code'];
				if (strtolower( $utilityAccountType ) == 'residential' )
				{
					$accountType = 0;
				}
				else
				{
					$accountType = 1;
				}
				$offer = new EP_Model_Offer();

				if($_SESSION['tabs']['state']['state']==3)
				{
							$sql = sprintf("select * from util_offers where code='%s' and util_code='%s' order by effdate desc limit 1",$_SESSION['tabs']['offer']['default_offercode'],$utilityCode);
							$res = mysql_query($sql,$link);
							$offerrow = mysql_fetch_object($res);
							//echo mysql_num_rows($res);

							if(mysql_num_rows($res)>0)
							{
								if($accountType == 0)
								{
									$rate1000 = $offerrow->tdspfixed / 1000;
									//echo $offerrow->rate."<BR>";
									//echo $offerrow->tdspvariable."<BR>";
									//echo $rate1 = $rate1000 + $offerrow->rate + $offerrow->tdspvariable;

									$rate1 = $offerrow->rate;

									if($_POST['greenoption_choice']=='001')
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
									//$rate1 = $rate_d2500 + $offerrow->rate + $offerrow->tdsp_non_dem_var;

									$rate1 = $offerrow->rate;

									if($_POST['greenoption_choice']=='001')
									{
										$rate1 += $offerrow->vas_rate;
									}

									$kwh='2500 kWh';
								}
							}

							$firstMonthPrice=$rate1;

				}
				else
				{
							//echo $partnerType;
							//echo $accountType;
							//echo $utilityCode;

							$firstMonthPrice = $offer->fetchFirstMonthPrice( $stateObj->getId(), $_SESSION['tabs']['offer']['partner'], $accountType,$utilityCode,$_POST['greenoption_choice'],'','','',1);
				}

				$fixedintro=$firstMonthPrice;

			}


			if($Session_State !=1)
			{

                  $offer_rates = getUtilRate($_SESSION['tabs']['offer']['default_offercode'],$_POST['greenoption_choice'],$_SESSION['utility']['code']);  // distrib is utility code
				//print_r($offer_rates);
			}

			//echo "Fixed Intro---------".$fixedintro;

			$productcode = $offer_rates[0];
		}


		$register->setProdCode(trim($productcode));

		if($Session_State !=1)
		{
			$register->setFixedIntro(trim($fixedintro));
		}
		else
		{
			$register->setFixedIntro('');
		}

		$mapper = new EP_Model_RegisterMapper();
		$result = $mapper->save( $register );

		if ( !$result )
		{
			die( 'error saving' );
		}


		$_SESSION['tabs']['account']['meterno']=$_POST['meterno'];
		$_SESSION['multiple_account']='';
		$_SESSION['multiple_service']='';
		$sequence='1';
		$seq_prefix="00";

		if($uid!='')
		{
			$delete_qry = "delete from register where uid='$uid' and id!=$regid";
			$delete_res = mysql_query($delete_qry,$link) or die(mysql_error()."--".$rate_qry);
		}

		$ind=001;


		// reset session vars
		unset( $_SESSION['account'] );
		unset( $_SESSION['sr'] );
		unset( $_SESSION['nk'] );
		unset( $_SESSION['multiple_namekey'] );
		unset( $_SESSION['multiple_account'] );
		unset( $_SESSION['multiple_service'] );


		for ($i = 1; $i <= $_POST['meterno']; $i++)
		{
			$j = $i + 1;
			$sequence_val = $seq_prefix . $j;
			$account = "account_" . $i;
			$sr = "sr_" . $i;
			$nk = "nk_" . $i;


			if ( strlen($_POST[$account] ) == 0 &&
				strlen($_POST[$sr]) == 0 &&
				strlen($_POST[$nk]) == 0 )
				{
					continue;
				}


			$_SESSION['account'][$i] = $_POST[$account];
			$_SESSION['sr'][$i] = $_POST[$sr];
			$_SESSION['nk'][$i] = $_POST[$nk];

			if($_POST[$account]!='')
			{
				$post_account=$_POST[$account];
			}
			else
			{
				$post_account='';
			}

			if($_POST[$sr]!='')
			{
				$post_sr=$_POST[$sr];
			}
			else
			{
				$post_sr='';
			}

			if($_POST[$nk]!='')
			{
				$post_nk=$_POST[$nk];
			}
			else
			{
				$post_nk='';
			}


			//////////////////////////////

			$registration = new EP_Model_Register();
			$registration->setSequence($sequence_val);
			$registration->setUid(trim($register->getUid()));
			$registration->setRegdate(trim($register->getRegdate()));

			$registration->setApptype(trim($register->getApptype()));
			$registration->setVendorid(trim($register->getVendorid()));
			$registration->setApptype(trim($register->getApptype()));
			$registration->setFirstName(trim($register->getFirstName()));
			$registration->setMidInit(trim($register->getMidInit()));
			$registration->setLastName(trim($register->getLastName()));

			$registration->setSuffix(trim($register->getSuffix()));
			$registration->setVip(trim($register->getVip()));
			$registration->setBusname(trim($register->getBusname()));
			$registration->setRevclass(trim($register->getRevclass()));
			$registration->setServicephone(trim($register->getServicephone()));
			$registration->setSericeext(trim($register->getSericeext()));
			$registration->setAddr1(trim($register->getAddr1()));
			$registration->setAddr2(trim($register->getAddr2()));
			$registration->setAddr3(trim($register->getAddr3()));
			$registration->setCity(trim($register->getCity()));
			$registration->setState(trim($register->getState()));

			$registration->setZip5(trim($register->getZip5()));
			$registration->setZip4(trim($register->getZip4()));
			$registration->setCounty(trim($register->getCounty()));
			$registration->setBillphone(trim($register->getBillphone()));
			$registration->setBillext(trim($register->getBillext()));
			$registration->setBaddr1(trim($register->getBaddr1()));
			$registration->setBaddr2(trim($register->getBaddr2()));
			$registration->setBaddr3(trim($register->getBaddr3()));
			$registration->setBcity(trim($register->getBcity()));
			$registration->setBstate(trim($register->getBstate()));
			$registration->setBzip5(trim($register->getBzip5()));
			$registration->setBzip4(trim($register->getBzip4()));
			$registration->setBcounty(trim($register->getBcounty()));

			$registration->setAtt(trim($register->getAtt()));
			$registration->setEmail(trim($register->getEmail()));
			$registration->setServicetype(trim($register->getServicetype()));
			$registration->setTerritoryCode(trim($register->getTerritoryCode()));
			$registration->setMarketer(trim($register->getMarketer()));
			$registration->setIso(trim($register->getIso()));
			$registration->setTaxex(trim($register->getTaxex()));
			$registration->setBillmeth(trim($register->getBillmeth()));
			$registration->setEntype(trim($register->getEntype()));
			$registration->setToday(trim($register->getToday()));
			$registration->setDistrib(trim($register->getDistrib()));
			$registration->setAccount(trim($register->getAccount()));
			$registration->setRateclass(trim($register->getRateclass()));
			$registration->setPromocode(trim($register->getPromocode()));
			$registration->setPriceplan(trim($register->getPriceplan()));
			$registration->setBdate(trim($register->getBdate()));
			$registration->setEdate(trim($register->getEdate()));
			$registration->setEdatePlan(trim($register->getEdatePlan()));
			$registration->setRepid(trim($register->getRepid()));
			$registration->setVas(trim($register->getVas()));


			$registration->setCampaign(trim($register->getCampaign()));
			$registration->setCellcode(trim($register->getCellcode()));
			$registration->setSaledate(trim($register->getSaledate()));
			$registration->setPartnercode(trim($register->getPartnercode()));
			$registration->setPartnerMemnum(trim($register->getPartnerMemnum()));
			$registration->setMemlevel(trim($register->getMemlevel()));
			$registration->setAuth(trim($register->getAuth()));
			$registration->setAccept(trim($register->getAccept()));
			$registration->setConfcode(trim($register->getConfcode()));
			$registration->setNowtime(trim($register->getNowtime()));
			$registration->setHpsemail(trim($register->getHpsemail()));
			$registration->setOrigurl(trim($register->getOrigurl()));
			$registration->setGreenopt(trim($register->getGreenopt()));
			$registration->setRefid(trim($register->getRefid()));
			$registration->setSourceip(trim($register->getSourceip()));
			$registration->setBusres(trim($register->getBusres()));
			$registration->setBudget(trim($register->getBudget()));
			$registration->setEntby(trim($register->getEntby()));
			$registration->setAppby(trim($register->getAppby()));
			$registration->setIntrogroup(trim($register->getIntrogroup()));
			$registration->setMkgroup(trim($register->getMkgroup()));
			$registration->setStateid(trim($register->getStateid()));
			$registration->setPfname(trim($register->getPfname()));
			$registration->setPlname(trim($register->getPlname()));
			$registration->setEnrollcustid(trim($register->getEnrollcustid()));
			$registration->setFico(trim($register->getFico()));
			$registration->setPaysrc(trim($register->getPaysrc()));
			$registration->setPaymeth(trim($register->getPaymeth()));
			$registration->setPayamt(trim($register->getPayamt()));
			$registration->setContractterm(trim($register->getContractterm()));
			$registration->setSpanishbill(trim($register->getSpanishbill()));
			$registration->setNotificationwaiver(trim($register->getNotificationwaiver()));
			$registration->setDob(trim($register->getDob()));
			$registration->setMothermaiden(trim($register->getMothermaiden()));
			$registration->setTaxid(trim($register->getTaxid()));
			//$registration->setCredit1(trim($register->getCredit1()));
			//$registration->setCredit2(trim($register->geCredit2()));
			$registration->setKwh(trim($register->getKwh()));
			$registration->setRentown(trim($register->getRentown()));
			$registration->setResidencelength(trim($register->getResidencelength()));
			$registration->setEmployeecount(trim($register->getEmployeecount()));
			$registration->setBusinesslength(trim($register->getBusinesslength()));
			$registration->setCurrentsupplier(trim($register->getCurrentsupplier()));
			$registration->setSpfname(trim($register->getSpfname()));
			$registration->setSplname(trim($register->getSplname()));
			$registration->setProdcode(trim($productcode));


			if($Session_State !=1)
			{
				$registration->setFixedIntro(trim($fixedintro));
			}
			else
			{
				$registration->setFixedIntro('');
			}

			$registration->setNoexport(trim($register->getNoexport()));
			$registration->setYearsInbiz(trim($register->getYearsInbiz()));
			$registration->setYearsBizaddr(trim($register->getYearsBizaddr()));
			$registration->setLatePayment6(trim($register->getLatePayment6()));
			$registration->setBusnameChange(trim($register->getBusnameChange()));
			$registration->setElecSuppPrevyear(trim($register->getElecSuppPrevyear()));
			$registration->setYearsCreditbiz(trim($register->getYearsCreditbiz()));


			$registration->setAccount($_POST[$account]);
			$registration->setBaccount($_POST[$sr]);
			$registration->setNamekey($_POST[$nk]);

			$register_mapper->save($registration);

			///////////////////////////////

			$_SESSION['multiple_account'] .= $_POST[$account].",";
			$_SESSION['multiple_service'] .= $_POST[$sr].",";
			$_SESSION['multiple_namekey'] .= $_POST[$nk].",";


			$account='';
			$sr='';
			$insert_qry='';
			$post_account='';
			$post_sr='';
			$post_nk='';


		} // end for ($i = 1; $i <= $_POST['meterno']; $i++)



		// put the results into the session


		if(isset($_POST['next_url'])!= '' && $_POST['next_url'] != '' )
		{
			if(($_POST['ssn1']=='444' && $_POST['ssn2']=='44' && $_POST['ssn3']=='4444') || $Session_State!=3)
			{
				$header = "Location: $base_url/myinbound/" . $_POST['next_url1'];
				header( $header );
			}
			else
			{
				$header = "Location: $base_url/myinbound/" . $_POST['next_url'];
				header( $header );
			}
		}
		else
		{
			$_SESSION['messages'] = 'The information has been saved';
		}

}


$strec = getRecord('states','id',$Session_State);
$stabbrev = $strec->abbrev;
$stid = $strec->id;

include_once("getutilinfo.php");
$classes = getRateClasses();

//echo $util_info;
//echo "<BR>";
//echo $util_info1;
//echo "<BR>";
//echo $util_info2;
//echo "<BR>";
//echo $util_info3;
//echo "<BR>";
//echo $util_info4;

require_once 'includes/header.php';

$year = strftime("%Y");


//print_r($_SESSION['utility']);


?>
<script type="text/javascript" src="/js/validate.js"></script>
<script type="text/javascript" src="/js/modal_popup.js"></script>
<script type="text/javascript">

var utilityAbbrev = "<?=$utility_abbrev;?>";

<!--
var downStrokeField;
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
//-->


var TimeToFade = 900.0;
<?
if($_SESSION['utility']['acclen'])
{
?>
	var reqlen=<?=$_SESSION['utility']['acclen'];?>;
<?
}
?>
var numb = '0123456789';
var account = 2;
<?
if($Session_acctext)
{
?>
var acc_text = '<?=$Session_acctext;?>';
<?
}
?>
<?
if(isset($_SESSION['utility']['acctext_ext']))
{
?>
var acc2_text = '<?=$_SESSION['utility']['acctext_ext'];?>';
<?
}
?>
var state = <?php echo $Session_State; ?>;
<?

$set_price_var = '';
if ( isset($set_price_var))
{
	$set_price_var = $strec->set_price_var;
}


if($set_price_var)
{
?>
var set_price_var = <?php echo $set_price_var;?>;
<?
}

$partcode = '';
if ( isset( $partcode))
{
	$partcode = $partcode;
}


if($partcode)
{
?>
var partcode = '<?php echo $partcode;?>';
<?
}
else
{
?>
var partcode = '';
<?
}
?>
var g_has_gas = 0;
var goventbar = 0;

function boolean_checksetting(selelm,yes_section,no_section,log,logid)
{
	if(selelm.value=='1' || selelm.value=='001')
	{
		document.getElementById(yes_section).style.display='';
		document.getElementById(no_section).style.display='none';
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}
	else if(selelm.value=='0'  || selelm.value=='000')
	{
		document.getElementById(yes_section).style.display='none';
		document.getElementById(no_section).style.display='';
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}

	if(logid!='')
	{
		logDispo();
	}

}

function global_checksetting(selelm,yes_section,no_section,log,logid)
{
	//alert(selelm.value);

	var log;
	var rctext_val=document.getElementById('rctext_val').value='';
	var resbus=document.getElementById('resbus').value;

	if(log!='')
	{
		document.getElementById('btn_log_dispo').disabled = false;
		document.getElementById('btn_continue').disabled = true;
		document.getElementById('btn_save').disabled = true;
	}
	else
	{
		var log='';
		var logid='';

		document.getElementById('btn_log_dispo').disabled = true;
		document.getElementById('btn_continue').disabled = false;
		document.getElementById('btn_save').disabled = false;
	}

	if(selelm.value=='1')
	{
		document.getElementById(yes_section).style.display='';
		document.getElementById(no_section).style.display='none';
		document.getElementById('multiple_meters').style.display='';
		document.getElementById('greendiv').style.display='';
		document.getElementById('hid_accountchoice').value=1;

		if( resbus == 1 )
		{
			document.getElementById('taxexempt').style.display='';
		}
		if(document.getElementById('session_state').value==2)
		{
			document.getElementById('customerkey').style.display='';
		}
		if(document.getElementById('srtext_val').value==1)
		{
			document.getElementById('srtext').style.display='';
		}
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}
	else if(selelm.value=='0')
	{
		document.getElementById(yes_section).style.display='none';
		document.getElementById(no_section).style.display='';
		document.getElementById('multiple_meters').style.display='none';
              document.getElementById('greendiv').style.display='none';
		document.getElementById('taxexempt').style.display='none';
		document.getElementById('hid_accountchoice').value=0;

		if(document.getElementById('session_state').value==2)
		{
			document.getElementById('customerkey').style.display='none';
			document.getElementById('customerkey_section_yes').style.display='none';
              	document.getElementById('customerkey_section_no').style.display='none';
		}

		if(rctext_val==1)
		{
              	document.getElementById('utility_rate_section_yes').style.display='none';
              	document.getElementById('utility_rate_section_no').style.display='none';
			document.getElementById('rctext').style.display='none';
		}

              document.getElementById('taxexempt_section_yes').style.display='none';
		if(document.getElementById('srtext_val').value==1)
		{
			document.getElementById('srtext').style.display='none';
			document.getElementById('serviceref_section_yes').style.display='none';
              	document.getElementById('serviceref_section_no').style.display='none';
		}
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;

	}
	else if(selelm.value=='3')
	{

		if(yes_section)
		{
			document.getElementById(yes_section).style.display='none';
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='';
			document.getElementById('srtext').style.display='none';
			document.getElementById('greendiv').style.display='none';
			document.getElementById('taxexempt').style.display='none';
			document.getElementById('multiple_meters').style.display='none';
			document.getElementById('hid_businesschoice').value=0;
		}

		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}
	else if(selelm.value=='2')
	{

		if(yes_section)
		{
			document.getElementById(yes_section).style.display='';
			document.getElementById('srtext').style.display='';
			document.getElementById('greendiv').style.display='';
			document.getElementById('taxexempt').style.display='';
			document.getElementById('multiple_meters').style.display='';
			document.getElementById('hid_businesschoice').value=1;
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='none';
		}

		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}
	else if(selelm.value=='4' || selelm.value=='6' || selelm.value=='8' || selelm.value=='10' || selelm.value=='14' || selelm.value=='16' || selelm.value=='18' || selelm.value=='20' || selelm.value=='25')
	{
		if(yes_section)
		{
			document.getElementById(yes_section).style.display='';
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='none';
		}
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}
	else if(selelm.value==5)
	{

		if(yes_section)
		{
			document.getElementById(yes_section).style.display='none';
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='';
		}

		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;

		logDispo();
	}
	else if(selelm.value=='12')
	{

		if(yes_section)
		{
			document.getElementById(yes_section).style.display='';
			document.getElementById('greendiv').style.display='';
			document.getElementById('taxexempt').style.display='';
			document.getElementById('multiple_meters').style.display='';
			document.getElementById('hid_servicerefchoice').value=1
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='none';
		}

		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}
	else if(selelm.value=='13')
	{

		if(yes_section)
		{
			document.getElementById(yes_section).style.display='none';
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='';
			document.getElementById('greendiv').style.display='none';
			document.getElementById('taxexempt').style.display='none';
			document.getElementById('multiple_meters').style.display='none';
			document.getElementById('hid_servicerefchoice').value=0;
		}

		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;


	}
	else if(selelm.value=='27')
	{
		if(yes_section)
		{
			document.getElementById(yes_section).style.display='';
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='none';
		}
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}
	else if(selelm.value=='33')
	{
		if(yes_section)
		{
			document.getElementById(yes_section).style.display='';
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='none';
		}
		document.getElementById('hid_dob').value=1;
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}
	else if(selelm.value=='9')
	{
		if(yes_section)
		{
			document.getElementById(yes_section).style.display='none';
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='';
		}
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;

		logDispo();
	}
	else if(selelm.value=='15')
	{
		if(yes_section)
		{
			document.getElementById(yes_section).style.display='none';
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='';
		}
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;

		logDispo();

	}
	else if(selelm.value=='19')
		{
			if(yes_section)
			{
				document.getElementById(yes_section).style.display='none';
			}
			if(no_section)
			{
				document.getElementById(no_section).style.display='';
			}
			document.getElementById('eflag').value=log;
			document.getElementById('eflagid').value=logid;
			g_entype = selelm.value;
			logDispo();

	}
	else if(selelm.value=='7' || selelm.value=='11' || selelm.value=='17' ||  selelm.value=='21' || selelm.value=='26')
	{
		if(yes_section)
		{
			document.getElementById(yes_section).style.display='none';
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='';
		}
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}
	else if(selelm.value=='29')
		{
			if(yes_section)
			{
				document.getElementById(yes_section).style.display='';
			}
			if(no_section)
			{
				document.getElementById(no_section).style.display='none';
			}
			document.getElementById('eflag').value=log;
			document.getElementById('eflagid').value=logid;
			g_entype = selelm.value;
	}
	else if(selelm.value=='31' || selelm.value=='35' || selelm.value=='37' || selelm.value=='39')
	{
		if(yes_section)
		{
			document.getElementById(yes_section).style.display='';
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='none';
		}
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}
	else if(selelm.value=='32')
	{
		if(yes_section)
		{
			document.getElementById(yes_section).style.display='none';
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='';
		}

		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;

		logDispo();

	}
	else if(selelm.value=='30' || selelm.value=='38')
	{
		if(yes_section)
		{
			document.getElementById(yes_section).style.display='none';
		}
		if(no_section)
		{
			if(selelm.value=='38')
			{
				document.getElementById('mothermaiden').value='ENERGY PLUS';
				document.getElementById(yes_section).style.display='';
			}

			document.getElementById(no_section).style.display='';

		}

		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}
	else if(selelm.value=='40')
		{
			if(yes_section)
			{
				document.getElementById(yes_section).style.display='none';
			}
			if(no_section)
			{
				document.getElementById(no_section).style.display='';
				if(selelm.value=='38')
				{
					document.getElementById('mothermaiden').value='ENERGY PLUS';
				}
			}

			document.getElementById('eflag').value=log;
			document.getElementById('eflagid').value=logid;
			g_entype = selelm.value;
			logDispo();

	}
	else if(selelm.value=='36')
	{
		if(yes_section)
		{
			document.getElementById(yes_section).style.display='none';
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='';
		}

		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;

		logDispo();
	}
	else if(selelm.value=='34')
	{
		if(yes_section)
		{
			document.getElementById(yes_section).style.display='none';
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='';
		}

		document.getElementById('hid_dob').value=0;
		document.getElementById('mothermaidenname_no').style.display='none';
		document.getElementById('mothermaidenname_yes').style.display='none';
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;


		g_entype = selelm.value;
	}
	else if(selelm.value=='28')
	{
		if(yes_section)
		{
			document.getElementById(yes_section).style.display='none';
		}
		if(no_section)
		{
			document.getElementById(no_section).style.display='';
		}
		document.getElementById('dob_yes').style.display='none';
		document.getElementById('dob_no').style.display='none';
		document.getElementById('mothermaidenname_no').style.display='none';
		document.getElementById('mothermaidenname_yes').style.display='none';
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}
	else if(selelm.value=='22')
	{
		document.getElementById(yes_section).style.display='';
		document.getElementById(no_section).style.display='none';
		document.getElementById('greendiv').style.display='';
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}
	else if(selelm.value=='23')
	{
		document.getElementById(yes_section).style.display='none';
		document.getElementById(no_section).style.display='';
		document.getElementById('eflag').value=log;
		document.getElementById('eflagid').value=logid;
		g_entype = selelm.value;
	}
	else
	{}






}
function addAccountSlot(elem)
{
	if(account > 10) return false;
	var prefix = elem.id.substr(0,2);
	if(prefix == 'ac' && !xchkAcct(elem.id))
		return false;
	if(prefix == 'sr' && !xchkAcct2(elem.id))
		return false;
	account++;
}

function chkAcct2()
{ // servicereference is really bacct
	var thisForm=window.document.registration;
	document.getElementById('srerror').innerHTML='';
	var acno = document.getElementById('servicereference').value;
	var aclen = acno.length;
	if(aclen < 11)
	{
		document.getElementById('srerror').innerHTML='Account Number should be 11 digits long.';
		document.getElementById('servicereference').value = '';
	}
	checkDigits2(acno,'servicereference');
}

function handlemultiplemeter()
{
	if(http10.readyState == 4)
       {

		if(http10.responseText!='')
		{
			$("#account_slots").html(http10.responseText);
		}
		else
		{
			$("#account_slots").html('');
		}

	}
}

function add_accountSlot(selelm,load)
{
	if(load==2)
	{
		var multiple_meter=selelm;
	}
	else
	{
		var multiple_meter=selelm.value;
	}


	if(multiple_meter!='')
	{
		var url1 = 'ajax/add_accountslot.php?load=' + load + '&mulmeter=' + multiple_meter;
		http10.open("GET",url1, true);
	       http10.onreadystatechange = handlemultiplemeter;
	       http10.send(null);
	}
	else
	{
			$("#account_slots").html('');
	}


}




jQuery(function($){

$("#inbound_account").submit(dobval);


});

function dobval(){

	var day = $("#day").val();
	var month = $("#month").val();
	var year = $("#year").val();

	if(day =='' || month =='' || year =='')
	{
	      alert('Your Date of Birth is required');
		  submitflag='false';
	      document.getElementById('month').focus();
		  return false;
    }

	var age = 18;

	var mydate = new Date();
	mydate.setFullYear(year, month-1, day);

	var currdate = new Date();
	currdate.setFullYear(currdate.getFullYear() - age);

	if ((currdate - mydate) < 0)
	{
		$('#dob_under18').show();
		$('#mothermaidenname').hide();
		$('#mothermaidenname_yes').hide();
		$('#btn_log_dispo').attr('disabled', '' );
		$('#btn_continue').attr('disabled', '' );
 		$('#btn_save').attr('disabled', '');
		//alert("Sorry, only persons over the age of " + age + " may enter this site");
		return false;
	}
	else
	{
		$('#dob_under18').hide();
		$('#dob_under18_yes').hide();
		$('#dob_under18_no').hide();
		$('#mothermaidenname').show();
		$('#mothermaidenname_yes').show();
		$('#btn_log_dispo').attr('enabled', '' );
		$('#btn_continue').attr('disabled', '' );
 		$('#btn_save').attr('disabled', '');
 		return false;
	}

}

function stepval()
{

	var submitflag='true';

	if(document.getElementById('session_state').value=='3')
	{

			var ssn1=document.getElementById('ssn1').value;
			var ssn2=document.getElementById('ssn2').value;
			var ssn3=document.getElementById('ssn3').value;

	      	if(document.getElementById('ssn1').value =='')
	        {
       	              alert('Please enter Social Security Number');
				submitflag='false';
                     	document.getElementById('ssn1').focus();
	                     return false;
       	    }

	      	if(document.getElementById('ssn2').value =='')
	              {
       	              alert('Please enter Social Security Number');
				submitflag='false';
                     	document.getElementById('ssn2').focus();
	                     return false;
       	       }

		      if(document.getElementById('ssn3').value =='')
       	       {
              	       alert('Please enter Social Security Number');
				submitflag='false';
	                     document.getElementById('ssn3').focus();
       	              return false;
              	}
			if((ssn1.length!=3) || (ssn2.length!=2) || (ssn3.length!=4) || (!isNum(ssn1)) || (!isNum(ssn2)) || (!isNum(ssn3)))
			{
				alert('Social Security Number is Invalid');
				submitflag='false';
       	              return false;

			}

		if(document.getElementById('hid_dob').value !=1)
		{
			alert('Please enter Date of Birth');
			submitflag='false';
	        return false;
		}

		if(document.getElementById('month').value =='' || document.getElementById('day').value =='' || document.getElementById('year').value =='')
	     {
       	        alert('Your Date of Birth is required');
				submitflag='false';
                document.getElementById('month').focus();
	            return false;
       	 }

			var day = $("#day").val();
			var month = $("#month").val();
			var year = $("#year").val();

			if(day =='' || month =='' || year =='')
			{
			      alert('Your Date of Birth is required');
				  submitflag='false';
			      document.getElementById('month').focus();
				  return false;
		    }

			var age = 18;

			var mydate = new Date();
			mydate.setFullYear(year, month-1, day);

			var currdate = new Date();
			currdate.setFullYear(currdate.getFullYear() - age);

			if ((currdate - mydate) < 0)
			{
				$('#dob_under18').show();
				$('#mothermaidenname').hide();
				$('#mothermaidenname_yes').hide();
				$('#btn_log_dispo').attr('disabled', '' );
				$('#btn_continue').attr('disabled', '' );
		 		$('#btn_save').attr('disabled', '');
				submitflag='false';
				return false;
			}


		if(document.getElementById('mothermaiden').value=='')
		{
			document.getElementById('mothermaidenname_yes').style.display='';
			alert("Mother's Maiden Name is required");
			submitflag='false';
	        return false;
		}

	}
	else
	{
		//accountchoice=1
		//alert(document.getElementById('accountchoice').checked);
		//businesschoice=2
		//alert(document.getElementById('businesschoice').checked);
		//servicerefchoice=12
		//alert(document.getElementById('servicerefchoice').checked);

		var acctext= document.getElementById('acctext').value;
		if(document.getElementById('hid_accountchoice').value =='1' && document.getElementById('Account_Number').value =='')
              {
                     alert('Please enter '+acctext);
			submitflag='false';
                     document.getElementById('Account_Number').focus();
                     return false;
              }

		if(document.getElementById('hid_businesschoice').value =='1' && document.getElementById('namekey').value =='')
              {
                     alert('Please enter Customer Key');
			submitflag='false';
                     document.getElementById('namekey').focus();
                     return false;
              }

		if(document.getElementById('hid_servicerefchoice').value =='1' && document.getElementById('servicereference').value =='')
              {
                     alert('Please enter Account Number');
			submitflag='false';
                     document.getElementById('servicereference').focus();
                     return false;
              }


	}

	if(submitflag=='true')
	{
		document.inbound_account.action="select_account.php";
		document.inbound_account.submit();
     		return true;
	}
}
function isNum(parm)
{
	for (i=0; i<parm.length; i++)
	{
		if (numb.indexOf(parm.charAt(i),0) == -1) return false;
	}
	return true;
}

function chkAcct(state,display)
{
	//alert(val);
	var thisForm=window.document.inbound_account;

	document.getElementById('accerr').innerHTML='';
	var acno = document.getElementById('Account_Number').value;

	var acc_text=display;

	var aclen = acno.length;
	//alert(thisForm.Local_Utility.value);

	if(thisForm.Local_Utility.value!="04" && thisForm.Local_Utility.value!="05" && thisForm.Local_Utility.value!="29" && !isNum(acno))
	{

		document.getElementById('accerr').innerHTML=acc_text + ' should be all numbers.';
		document.getElementById('accnum_invalid').style.display='';
		document.getElementById('Account_Number').value = '';
		document.getElementById('multiple_meters').value = '';
		return false;
	}
	if(thisForm.Local_Utility.value=="29")
	{
		var acno_str =acno.substr(2);
             if(!isNum(acno_str))
              {
		      document.getElementById('accerr').innerHTML='Electric PoD ID must start with PE and be followed by 18 numbers.';
		      document.getElementById('accnum_invalid').style.display='';
                    document.getElementById('Account_Number').value = '';
		      document.getElementById('multiple_meters').value = '';
              }
		else
		{
			document.getElementById('accnum_invalid').style.display='none';

		}

	}

	if(thisForm.Local_Utility.value=="03")
	{
		if(aclen < (reqlen - 1))
		{
			document.getElementById('accerr').innerHTML='Account Number should be 10 or ' + reqlen + ' digits long.';
			document.getElementById('accnum_invalid').style.display='';
			document.getElementById('Account_Number').value = '';
			document.getElementById('multiple_meters').value = '';
		}
		else
		{
			document.getElementById('accnum_invalid').style.display='none';

		}

	}
	else
	{
		if(aclen != reqlen)
		{
			document.getElementById('accerr').innerHTML=acc_text + ' should be ' + reqlen + ' digits long.';
			document.getElementById('accnum_invalid').style.display='';
			//if(thisForm.Local_Utility.value=="04")
			//	document.getElementById('Account_Number').value = 'N01';
			//else if(thisForm.Local_Utility.value=="05")
			//	document.getElementById('Account_Number').value = 'R01';
			//else
				document.getElementById('Account_Number').value = '';
		}
		else
		{
			document.getElementById('accnum_invalid').style.display='none';

		}
		// extra validation for POD Accounts
		//if(thisForm.Local_Utility.value=="04")
	}


	checkDigits(acno,'Account_Number');

}
function xchkAcct(myid)
{
	//alert(myid);
	var thisForm=window.document.inbound_account;
	document.getElementById('xaccerr').innerHTML='';
	var acno = document.getElementById(myid).value;
	var aclen = acno.length;
	if(thisForm.Local_Utility.value!="04" && thisForm.Local_Utility.value!="05" && thisForm.Local_Utility.value!="29" && !isNum(acno))
	{
		document.getElementById('xaccerr').innerHTML=acc_text+' should be all numbers.';
		document.getElementById('multiple_meters').value = '';
		document.getElementById(myid).value = '';
		return false;
	}
	if(thisForm.Local_Utility.value=="03")
	{
		if(aclen < (reqlen - 1))
		{
			document.getElementById('xaccerr').innerHTML='Account Number should be 10 or ' + reqlen + ' digits long.';
			document.getElementById('multiple_meters').value = '';
			document.getElementById(myid).value = '';
		}
	}
	else
	{
		if(aclen != reqlen)
		{
			document.getElementById('xaccerr').innerHTML=acc_text + ' should be ' + reqlen + ' digits long.';
			document.getElementById('multiple_meters').value = '';
			document.getElementById(myid).value = '';
			return false;
		}
	}
//Mahi
	checkDigits(acno,myid);
	return true;
}
function xchkAcct2(myid)
{ // servicereference is really bacct
	var thisForm=window.document.inbound_account;
	document.getElementById('xaccerr').innerHTML='';
	var acno = document.getElementById(myid).value;
	var aclen = acno.length;
	if(aclen < 11)
	{
		document.getElementById('xaccerr').innerHTML='Account Number should be 11 digits long.';
		document.getElementById('multiple_meters').value = '';
		document.getElementById(myid).value = '';
		return false;
	}
	checkDigits2(acno,myid);
	return true;
}

function setMaxLength(callback)
{
	//alert(cashback);
	var thisForm=window.document.inbound_account;
	var util = document.getElementById('Local_Utility').value;

	var url = 'getutilinfo.php?util=' + util;

	//document.getElementById("account_1").value = '';
	//document.getElementById("ssndiv").style.display='none';
	//document.inbound_account.Multiple_Accounts.disabled = false;
	//document.getElementById("ssncheck").innerHTML = '';
	//disableAcctChk = 0;
	//document.inbound_account.Account_Number.readOnly=false;
	if(util == 0) return false;
	//alert(util);
	http.open("GET",url, true);
       http.onreadystatechange = function()
                                  {
				    handleLengthResponse1(callback);
				  };
	http.send(null);
}
function handleLengthResponse1(callback)
{
	var cashback;
        if(http.readyState == 4)
        {
		//alert(http.responseText);
		acc2_text = '';
                newtext = http.responseText.replace( new RegExp( "\\n", "g" ), "" );
	//	alert(newtext);
              //  eval(newtext);
             // alert(http.responseText);
		//if(acc2_text)
		//{
		//	document.getElementById('ac1').style.display = '';
		//	document.getElementById('ac2').style.display = '';
		//}
		//else
		//{
		//	document.getElementById('ac1').style.display = 'none';
		//	document.getElementById('ac2').style.display = 'none';
		//}
        }

	 alert(callback);

//document.getElementById("acctno").innerHTML="Customer Number (20 digits starting with \"08\")";
        //if(callback && typeof callback == "function")
        //{
	    callback();
	//}
}

function setMaxLengthEx()
{
	document.getElementById('lookupdiv').style.top='330px';
	document.getElementById('lookupdiv').style.left='150px';
	setMaxLength(function()
	  {
		var elem = document.getElementById('Local_Utility').value;
		 xmlhttp=GetXmlHttpObject();
		       if (xmlhttp==null)
			 {
			   alert ("Your browser does not support the current functionality");
			   return;
			 }

	  }
       );
}
function load_globalsetting(state,dob,maiden,account_number,Customer_key,meter_no,resbus)
{

	if(account_number!='' && state!=4)
	{
		document.getElementById('accnum_section_yes').style.display='';
	}

	if(Customer_key!='')
	{
		$("#customerkey").show();
		$("#customerkey_section_yes").show();
	}

	if(meter_no!='')
	{
		document.getElementById('multiple_meters').style.display=''
		document.getElementById('multiplemeters_section_yes').style.display=''

	}

	if( resbus == 1 )
	{
		document.getElementById('taxexempt').style.display='';
	}

	if(state==3)
	{
		document.getElementById('ssn_yes').style.display='';
		document.getElementById('dob_yes').style.display='';
		document.getElementById('mothermaidenname').style.display='';
		document.getElementById('mothermaidenname_yes').style.display=''

		document.getElementById('hid_dob').value=1;

		if(maiden)
		{
			document.getElementById('mothermaidenname_yes').style.display=''
		}
	}
	add_accountSlot(meter_no,2)
}
function GetXmlHttpObject()
{
	if (window.XMLHttpRequest)
	  {
	  // code for IE7+, Firefox, Chrome, Opera, Safari
	  return new XMLHttpRequest();
	  }
	if (window.ActiveXObject)
	  {
	  // code for IE6, IE5
	  return new ActiveXObject("Microsoft.XMLHTTP");
	  }
	return null;
}

function logDispo()
{
	alert("THANK YOU FOR CALLING ENERGY PLUS. HAVE A NICE DAY!!");

	if (document.getElementById('eflag').value == 'cust')
	{
		document.getElementById("inbound_account").action = 'customer_service.php';
		document.getElementById("inbound_account").submit();
		return true;
	}
	else if (document.getElementById('eflag').value == 'disp')
	{
		document.getElementById("inbound_account").action = 'dispositions.php';
		document.getElementById("inbound_account").submit();
		return true;
	}

}

function checkDigits(accno,fieldid)
{
	var errmsg = "";
	var ucode=document.getElementById("Local_Utility").value;

	if(fieldid == 'Account_Number')
	{
		var error_field = 'accerr';
	}
	else
	{
		var error_field = 'xaccerr';
	}

	if(ucode=='04')
	{
		var left  = 0;
		var right = 'N';

		var pos = left+1;
		var chkchar = accno.charAt(left);

		if(chkchar != right)
		{
			errmsg = 'POD ID must begin with N01'+"\n";
		}


		var left  = 1;
		var right = 0;

		var pos = left+1;
		var chkchar = accno.charAt(left);

		if(chkchar != right)
		{
			errmsg = 'POD ID must begin with N01'+"\n";
		}


		var left  = 2;
		var right = 1;

		var pos = left+1;
		var chkchar = accno.charAt(left);

		if(chkchar != right)
		{
			errmsg = 'POD ID must begin with N01'+"\n";
		}

	}
	else if(ucode=='05')
	{
		var left  = 0;
		var right = 'R';

		var pos = left+1;
		var chkchar = accno.charAt(left);

		if(chkchar != right)
		{
			errmsg = 'POD ID must begin with R01'+"\n";
		}


		var left  = 1;
		var right = 0;

		var pos = left+1;
		var chkchar = accno.charAt(left);

		if(chkchar != right)
		{
			errmsg = 'POD ID must begin with R01'+"\n";
		}


		var left  = 2;
		var right = 1;

		var pos = left+1;
		var chkchar = accno.charAt(left);

		if(chkchar != right)
		{
			errmsg = 'POD ID must begin with R01'+"\n";
		}

	}
	else if(ucode==07)
	{
		var left = 6;
		var right = 0;

		var pos = left+1;
		var chkchar = accno.charAt(left);

		if(chkchar != right)
		{
			errmsg = 'Invalid number combination for Service Reference Number. The two digits before the last of the 9 Digits in the service ref number are always 00'+"\n";
		}

		var left = 7;
		var right = 0;

		var pos = left+1;
		var chkchar = accno.charAt(left);

		if(chkchar != right)
		{
			errmsg = 'Invalid number combination for Service Reference Number. The two digits before the last of the 9 Digits in the service ref number are always 00'+"\n";
		}

	}
	else if(ucode=='08')
	{
		var left = 1;
		var right = 0;

		var pos = left+1;
		var chkchar = accno.charAt(left);
		//alert(chkchar);
		//alert(right);
		if(chkchar==right)
		{
			errmsg = 'POD ID cannot begin with 0'+"\n";
		}

	}
	else if(ucode=='28')
	{
		var left = 0;
		var right = 0;

		var pos = left+1;
		var chkchar = accno.charAt(left);

		if(chkchar != right)
		{
			errmsg = 'Customer Number start with 08'+"\n";
		}

		var left = 1;
		var right = 8;

		var pos = left+1;
		var chkchar = accno.charAt(left);

		if(chkchar != right)
		{
			errmsg = 'Customer Number start with 08'+"\n";
		}

	}
	else if(ucode=='29')
	{
		var left = 0;
		var right = 'P';
		//alert(left);
		//alert(right);
		var pos = left+1;
		var chkchar = accno.charAt(left);

		if(chkchar != right)
		{
			errmsg = 'Electric PoD ID must start with PE and be followed by 18 numbers. Do not use PG (or the numbers after it) as this is your Gas ID.'+"\n";
		}

		var left = 1;
		var right = 'E';
		//alert(left);
		//alert(right);

		var pos = left+1;
		var chkchar = accno.charAt(left);

		if(chkchar != right)
		{
			errmsg = 'Electric PoD ID must start with PE and be followed by 18 numbers. Do not use PG (or the numbers after it) as this is your Gas ID.'+"\n";
		}

	}
	else if(ucode=='32')
	{
		var left = 0;
		var right = 0;

		var pos = left+1;
		var chkchar = accno.charAt(left);
		//alert(chkchar);
		//alert(right);

		if(chkchar==right)
		{
			errmsg = 'An Account Number starting with 0 or 1 indicates you are located outside of our MD service area'+"\n";
		}

		var left = 0;
		var right = 1;

		var pos = left+1;
		var chkchar = accno.charAt(left);

		if(chkchar==right)
		{
			errmsg = 'An Account Number starting with 0 or 1 indicates you are located outside of our MD service area'+"\n";
		}

	}

	if(errmsg)
	{
		document.getElementById(error_field).innerHTML = errmsg;
		//document.getElementById(accnum_invalid).style.display="";
		//document.getElementById(multiple_meters).style.display="";
		document.getElementById(fieldid).value="";
	}
	else
	{
		//document.getElementById(accnum_invalid).style.display="none";
		//document.getElementById(multiple_meters).style.display="none";
	}


}



function checkDigits2(accno,fieldid)
{

	var errmsg = "";

	if(fieldid == 'servicereference')
	{
		var error_field = 'srerror';
	}
	else
	{
		var error_field = 'xaccerr';
	}

	var left = 0;
	var right = 5;
	var pos = left+1;
	var chkchar = accno.charAt(left);


	if(chkchar != right)
	{
		errmsg = 'Account Number should begin with 51'+"\n";
	}

	var left = 1;
	var right = 1;
	var pos = left+1;
	var chkchar = accno.charAt(left);

	if(chkchar != right)
	{
		errmsg = 'Account Number should begin with 51'+"\n";
	}

	if(errmsg)
	{
		document.getElementById(error_field).innerHTML = errmsg;
		//document.getElementById(accnum_invalid).style.display="";
		document.getElementById(fieldid).value="";
	}
	else
	{
		//document.getElementById(accnum_invalid).style.display="none";
	}

}

function chknk()
{
	document.getElementById('nkerror').innerHTML = '';
	var nk = document.getElementById('namekey').value;
	if(nk.length != 4)
	{
		document.getElementById('nkerror').innerHTML = 'Name Key must be 4 characters long.';
		document.getElementById('namekey').value = '';
	}
	document.getElementById('namekey').value = nk.toUpperCase();
	//document.registration.nkradio[0].checked = true;
}

function fetchUtilAccounts( account )
{
    $('#searcherrmsg').html('<img style="vertical-align: bottom;" src="images/txloading.gif" /><span >Please wait...</span>');
	$('#account_msg').html('');

	var url = '/ajax/fetch_util_accounts.php';
	$.ajax({
		url: url,
		data: {
			'account': account,
			'util': utilityAbbrev
		},
		dataType: 'json',
		type: 'POST',
		success: function(data) {
			// callbackSuccess( data, number, partnercode );
    		$('#searcherrmsg').html('');
			if ( data.success == true && data.totalCount == 1 )
			{
				$('#account_msg').html( '<span class="success">Valid account number!</span>' );
			}
			else
			{
				$('#account_msg').html( '<span class="error">Invalid account number.</span>' );
				alert("We could not locate an Account Number based on the information you provided. Please try again, or refer to your utility bill to enter your Account Number.");
			}
		},
		error: function(data) {
			// callbackError( data, number, partnercode );
    		$('#searcherrmsg').html('');
			alert("There has been an error checking the account number.  Please try again.");
		}
	});
}




</script>
<script type="text/javascript">

var http = getHTTPObject();
var http10 = getHTTPObject();

	function cSess()
	{
		document.location.replace('index.php?cs=1');
	}

</script>


</head>

<body onLoad="return load_globalsetting('<?=$Session_State;?>','<?=$Session_dob;?>','<?=$Session_mother_maiden_name;?>','<?=$Session_Account_Number?>','<?=$Session_Customer_key;?>','<?=$Session_meterno;?>','<?=$Session_resbus;?>')">

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
<div id="govtxt" style="border:1px solid #ccc;background-color:#fff;width:700px;padding:25px;text-align:left;display:none;">
	<div style="float:right">
		<a href="javascript:void(0)" onClick="Popup.hide('govtxt')">
			<img src="../images/close.gif" alt="Close" border="0">
		</a>
	</div>
	<p class="text1" style="font-family:tahoma;font-size:12px;">
		A government entity is an entity formed or sponsored by a government (federal, state, local, municipality).  This does not include privately owned businesses that have
		received government grants.
	</p>
</div>
<div id="billimg" style="border:1px solid #ccc; background-color:#fff; width:700px; padding:25px; text-align:left; display:none;">
        <div style="float:right;">
		<a href="javascript:void(0)" onClick="Popup.hide('billimg')">
			<img src="../images/close.gif" alt="Close" border="0">
		</a>
	</div>

	<p><img id="bimg" src="../images/billimages/<?=strtolower($_SESSION['utility']['abbrev']);?>/account.jpg" alt=""></p>
</div>
<div id="keyimg" style="border:1px solid #ccc; background-color:#fff; width:700px; padding:25px; text-align:left; display:none;">
        <div style="float:right;">
		<a href="javascript:void(0)" onClick="Popup.hide('keyimg')">
			<img src="../images/close.gif" alt="Close" border="0">
		</a>
	</div>
	<p><img id="kimg" src="../images/billimages/<?=strtolower($_SESSION['utility']['abbrev']);?>/namekey.jpg" alt=""></p>
</div>
<div id="green" style="border:1px solid #ccc; background-color:#fff; width:700px; padding:25px; text-align:left; display:none;">
<?php include ("../globals/greenpopup.html"); ?>
</div>
<div id="rbkinfo" style="position:absolute;top:200px;left:150px;display:none; background-color: #fff; padding: 20px; width: 280px; border: 1px solid #ccc;">
	<div style="width: 100%; height: 35px;">
		<a href="javascript:void(0)" onClick="Popup.hide('rbkinfo')">
			<img style="float:right;" src="../images/close.gif" alt="Close" border="0">
		</a>
	</div>
	<strong>How to retrieve your RecycleBank Account Number:</strong>
	<ol>
		<li>Go to <a href="https://www.recyclebank.com/login" target="blank">https://www.recyclebank.com/login</a></li>
		<li>Enter your E-Mail address and RecycleBank password</li>
		<li>Click on "MY ACCOUNT" and you will see your Account Number listed on the left-side of the page as pictured below</li>
	</ol>
	<div style="text-align:center;"><img src="/images/rbkpopup.jpg" alt="" ></div>
</div>
<div id="lookupdiv" style="background-color: #fff;display:none;position:absolute;top:330px;left:150px;width:450px;height:auto;border:1px solid #ccc;">
        <div style="height:22px;background-color:#006b66;">
                <div style="width:30px;float:right;color:#fff">
                        <a style="color:#fff;font-weight:bold;" href="javascript:void(0)" onClick="hideLookup()">X</a>
                </div>
        </div>
        <div id="rdiv" > </div>
</div>
<script type="text/javascript">
http = getHTTPObject();
http1 = getHTTPObject();
</script>
<form id="inbound_account" action="" name="inbound_account" method="post" autocomplete="off">
<div id="lookupdiv" style="background-color: #fff;display:none;position:absolute;top:330px;left:150px;width:450px;height:auto;border:1px solid #ccc;">
        <div style="height:22px;background-color:#006b66;">
                <div style="width:30px;float:right;color:#fff">
                        <a style="color:#fff;font-weight:bold;" href="javascript:void(0)" onClick="hideLookup()">X</a>
                </div>
        </div>
        <div id="rdiv" > </div>
</div>
	<?php
				if($Session_State!=3 && ($Session_State!=4 || $Session_Account_Number==''))
				{
				?>
					<table width="100%" border="0" cellpadding="0" cellspacing="0" class="admintable">
						<tr>
							<td colspan="2" >
								May I please have your <?=$Session_acctext;?>?
								<input  name="accountchoice"  type="radio" value="1" onClick="global_checksetting(this,'accnum_section_yes','accnum_section_no','','')" <?php if($Session_Account_Number!=''){echo 'Checked';}?>> Yes&nbsp;
								<input  name="accountchoice" type="radio" value="0" onClick="global_checksetting(this,'accnum_section_yes','accnum_section_no','disp','9')" > No&nbsp;
							</td>
						</tr>
					</table>
				<?php
				}
				?>
				<div id="accnum_section_yes" style="display:none;">
				<?php
					if($Session_State!=3 && ($Session_State!=4 || $Session_Account_Number==''))
					{
				?>
					<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
						<tr>
							<td bgcolor="#efefef" class="formdata" width="230"><?=$Session_acctext."-".$Session_acctext_ext;?><font color="red">*</font>&nbsp;&nbsp;&nbsp;<BR></td>
							<td bgcolor="#efefef" class="formdata">
								<div class="inp_h">
								<input onBlur="chkAcct('<?=$Session_State;?>','<?=$Session_acctext;?>')" id="Account_Number" name="Account_Number" value="<?=$Session_Account_Number;?>" maxlength="<?=$_SESSION['utility']['acclen'];?>">
								<span style="font-weight: bold;" id="searcherrmsg"></span>
								<span style="padding-left: 2em; font-weight: bold;" id="account_msg"></span>
								<div id="accerr" style="color:#ff0000;"></div>
									<span style="font-size:8pt;font-weight:bold;">(No spaces or dashes)</span>
									<input type="hidden" name="r_Account_Number" id="racct" value="Missing Account Number">
								<?php
								if($strec->has_lookup)
								{
									?>
										<br/><input type="button" onClick="fetchUtilAccounts( document.getElementById('Account_Number').value )" style="margin-top:10px;" value="Search" />
									<?php
								}
								?>
								<a href="javascript:void(0)" onClick="Popup.showModal('billimg');return false;"><font color="green">Where is this?</font></a>
								</div>
							</td>
						</tr>
					</table>
				<?php
					}
				?>
				</div>
				<div id="accnum_invalid" style="display:none;">
					<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
						<tr>
							<td>
								The <?=$Session_acctext;?> is not valid. Can you please check your utility bill again and provide me the number?
								<input name="accnum_valid" type="radio" value="25" onClick="global_checksetting(this,'accnum_section_yes','accnum_section_no','cust','')">Yes
								<input name="accnum_valid" type="radio" value="26" onClick="global_checksetting(this,'accnum_section_yes','accnum_section_no','disp','13')">No
							</td>
						</tr>
					</table>
				</div>

				<div id="accnum_section_no" style="display:none;">
					<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
						<tr>
							<td>
								Unfortunately we need to know your <?=$Session_acctext;?> before we can proceed with your enrollment. Once you are willing to provide this information, please call us back at <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>.
							</td>
						</tr>
						<tr>
							<td>
								Is there anything else I can do for you today?
								<input name="accnum_no" type="radio" value="4" onClick="global_checksetting(this,'account_section_yes','account_section_no','cust','')">Yes
								<input name="accnum_no" type="radio" value="5" onClick="global_checksetting(this,'account_section_yes','account_section_no','disp','13')">No
							</td>
						</tr>
						<tr>
							<td colspan="2" >
								<div id="account_section_yes" style="display: none;"><p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p><BR></div>
								<div id="account_section_no" style="display: none;">
										<p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO #13: CALLER REFUSED TO PROVIDE UTILITY <?=$Session_acctext;?></p><BR></div>
							</td>
						</tr>

					</table>
				</div>
<?
if($Session_State==2)
{
?>
		<div id="customerkey">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="admintable">
						<tr>
							<td colspan="2" >
								May I please have your Customer Name Key?
								<input  name="businesschoice"  type="radio" value="2" onClick="global_checksetting(this,'customerkey_section_yes','customerkey_section_no','','')" > Yes&nbsp;
								<input  name="businesschoice" type="radio" value="3" onClick="global_checksetting(this,'customerkey_section_yes','customerkey_section_no','disp','14')" > No&nbsp;
							</td>
						</tr>
					</table>

					<div id="customerkey_section_yes" style="display:none;">
						<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
							<tr>
								<td bgcolor="#efefef" class="formdata" width="230">Customer Key?<font color="red">*</font>&nbsp;&nbsp;&nbsp;</td>
								<td bgcolor="#efefef" class="formdata">
									<div class="inp_h">
										<input type="text" name="namekey" id="namekey" maxlength="4" id="namekey" onBlur="chknk()" value="<?=$namekey;?>" autocomplete="off" >&nbsp;
										&nbsp;<a href="javascript:void(0)" onClick="Popup.showModal('keyimg');return false;"><font color="green">Where is this?</font>
									</div>
									<div id="nkquestion2" style="display:none">
										<br />
											<input type="radio" name="nkradio" value="1" onClick="chkNKRadio()" />Yes
											<input type="radio" name="nkradio" value="0" onClick="chkNKRadio()" />No
										<div id="nkrerror"></div>
									</div>
									<input type="hidden" name="r_namekey" value="Missing Name Key">
									<div id="nkerror" style="color:#f00;"></div>
								</td>
							</tr>
						</table>
					</div>
					<div id="customerkey_section_no" style="display:none;">
						<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
							<tr>
								<td>
									Unfortunately we need to know your Customer Key before we can proceed with your enrollment.Once you are willing to provide this information, please call us back at  <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>.<BR>Is there anything else I can do for you today?
									<input name="busfaq" type="radio" value="8" onClick="global_checksetting(this,'ckey_section_yes','ckey_section_no','cust','')">Yes
									<input name="busfaq" type="radio" value="9" onClick="global_checksetting(this,'ckey_section_yes','ckey_section_no','disp','14')">No
								</td>
							</tr>
							<tr>
								<td colspan="2" >
									<div id="ckey_section_yes" style="display: none;"><p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p><BR></div>
									<div id="ckey_section_no" style="display: none;">Thank you for calling Energy Plus and have a nice day.<p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO #14: Caller Refused to provide utility customer name key</i></font><BR></div>
								</td>
							</tr>
						</table>
					</div>
	</div>
<?
}
?>
<?
if($Session_srtext!='')
{
$srtext_val=1;
?>
		<div id="srtext">
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="admintable">
							<tr>
								<td colspan="2" bgcolor="#efefef" class="formdata" >
									May I please have your <?=$Session_utility;?> Account Number?
									<input  name="servicerefchoice"  type="radio" value="12" onClick="global_checksetting(this,'serviceref_section_yes','serviceref_section_no','','')" > Yes&nbsp;
									<input  name="servicerefchoice" type="radio" value="13" onClick="global_checksetting(this,'serviceref_section_yes','serviceref_section_no','disp','13')" > No&nbsp;
								</td>
							</tr>
						</table>
					<div id="serviceref_section_yes">
						<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
							<tr>
								<td bgcolor="#efefef" class="formdata" width="230">Account Number<font color="red">*</font>&nbsp;&nbsp;&nbsp;</td>
								<td bgcolor="#efefef" class="formdata">
									<div class="inp_h">
										<input type="text" name="servicereference" id="servicereference"  maxlength="11" onBlur="chkAcct2()" AUTOCOMPLETE=OFF value="<?=$Session_servicereference;?>" >&nbsp;<a href="javascript:void(0)" onClick="Popup.showModal('billimg');return false;"><font color="green">Where is this?</font>
									</div>
									<div id="srerror" style="color:#f00;"></div>
									<div id="srerror2"></div>
								</td>
							</tr>
						</table>

					</div>
					<div id="serviceref_section_no" style="display:none;">
						<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
							<tr>
								<td>
									Unfortunately we need to know your <?=$_SESSION['utility']['utility'];?>  Account Number before we can proceed with your enrollment. Once you are willing to provide this information, please call us back at <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>.<BR>Is there anything else I can do for you today?
									<input name="serviceref" type="radio" value="14" onClick="global_checksetting(this,'serviceref_section_yes','serviceref_section_no','cust','')">Yes
									<input name="serviceref" type="radio" value="15" onClick="global_checksetting(this,'serviceref_section_yes','serviceref_section_no','disp','13')">No
								</td>
							</tr>
							<tr>
								<td colspan="2" >
									<div id="serviceref_section_yes" style="display: none;"><p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p><BR></div>
									<div id="serviceref_section_no" style="display: none;"><p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO #13: CALLER REFUSED TO PROVIDE UTILITY ACCT #</p><BR></div>
								</td>
							</tr>
						</table>
					</div>
		</div>
				<?
}
if($Session_rctext!='')
{
$rctext_val=1;

?>
		<div id="rctext">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" class="admintable">
							<tr>
								<td colspan="2">
									May I please have your <?=$Session_utility;?> Rate Class
									<input  name="rateclasschoice"  type="radio" value="16" onClick="global_checksetting(this,'utility_rate_section_yes','utility_rate_section_no','cust','')" > Yes&nbsp;
									<input  name="rateclasschoice" type="radio" value="17" onClick="global_checksetting(this,'utility_rate_section_yes','utility_rate_section_no','disp','17')" > No&nbsp;
								</td>
							</tr>
						</table>
					<div id="utility_rate_section_yes" style="display:none;">
							<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
								<tr>
									<td bgcolor="#efefef" class="formdata" width="230">Utility Rate Class<font color="red">*</font>&nbsp;&nbsp;&nbsp;</td>
									<td bgcolor="#efefef" class="formdata" class="inp_h">
										<div class="inp_h">
											<select name="Rate_Class" id="Rate_Class" >
												<option value="">Please Select</option>
													<?php
													while($class=mysql_fetch_object($classes))
													{
														if($class->group==$Session_Rate_Class)
														{
													?>
														<option value="<?php echo $class->group;?>" SELECTED><?php echo $class->description;?></option>
													<?
														}
														else
														{
													?>
														<option value="<?php echo $class->group;?>"><?php echo $class->description;?></option>

													<?
														}
													}
													?>
											</select>
												<a href="javascript:void(0)" onClick="Popup.showModal('billimg');return false;">
												<font color="green">Where is this?</font></a>

											<div id="rcerror"></div>
										</div>
									</td>
								</tr>
							</table>
						</div>
						<div id="utility_rate_section_no" style="display:none;">
							<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
								<tr>
									<td colspan="2" >
										Unfortunately we need to know your <?=$Session_utility;?> Rate Class before we can proceed with your enrollment.Once you are willing to provide this information, please call us back at  <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>.<BR>Is there anything else I can do for you today?
										<input name="utilityrate" type="radio" value="18" onClick="global_checksetting(this,'utilityrate1_section_yes','utilityrate1_section_no','cust','')">Yes
										<input name="utilityrate" type="radio" value="19" onClick="global_checksetting(this,'utilityrate1_section_yes','utilityrate1_section_no','disp','17')">No
									</td>
								</tr>
								<tr>
									<td colspan="2" >
										<div id="utilityrate1_section_yes" style="display: none;"><p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</i></font><BR></div>
										<div id="utilityrate1_section_no" style="display: none;"><p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D #17: CALLER REFUSED TO PROVIDE Rate Class</i></font><BR></div>
									</td>
								</tr>
							</table>
						</div>
			</div>
			<?
				}
			?>



				<div id="taxexempt" style="display:none;">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" class="admintable">
						<tr>
									<td colspan="2" >
									<?
										if($bus_taxex==1)
										{
									?>
												Does your business classify as a government entity?
									<?
										}
										else
										{
									?>
												Are you tax Exempt?
									<?
										}
									?>
										<input  name="taxexempt_choice" id="taxexempt_choice" type="radio" value="1" onClick="boolean_checksetting(this,'taxexempt_section_yes','taxexempt_section_no','','')" > Yes&nbsp;
										<input  name="taxexempt_choice" id="taxexempt_choice" type="radio" value="0" onClick="boolean_checksetting(this,'taxexempt_section_yes','taxexempt_section_no','','')" Checked > No&nbsp;
									</td>
						</tr>
					</table>
					</div>
					<div id="taxexempt_section_yes" style="display:none;">
						<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
							<tr>
								<td class="formdata" colspan="2">
									<?
										if($bus_taxex==1)
										{
									?>
										We apologize, but government entity accounts cannot be enrolled at this time. <BR>  Is there anything else I can do for you today?
										<input  name="govtentity_choice" id="govtentity_choice" type="radio" value="1" onClick="boolean_checksetting(this,'govtentity_section_yes','govtentity_section_no','','')" > Yes&nbsp;
										<input  name="govtentity_choice" id="govtentity_choice" type="radio" value="0" onClick="boolean_checksetting(this,'govtentity_section_yes','govtentity_section_no','disp','91')" > No&nbsp;</p>
										<div id="govtentity_section_yes" style="display:none;"><p class='rep_note'>IF CUSTOMER HAS QUESTIONS GO TO FAQS.</p></div>
										<div id="govtentity_section_no" style="display:none;"><p class='rep_note'>NOTE TO TSR: CALL WAS AUTO DISPO'D #91: Business Classified as a Government Entity</p></div>
									<?
										}
										else
										{

									?>
											You will need to send us proof of your tax exemption eligibility either by fax or mail to:
											<ul>
													<li>Fax: 866-857-8014 Attn: Enrollment Department
													<li>Mail: Energy Plus, c/o Enrollment Department, 3711 Market Street, 10th Floor, Philadelphia, PA 19104
											</ul>
									<?
										}
									?>
								</td>
							</tr>
						</table>
					</div>
					<div id="taxexempt_section_no" style="display:none;">

					</div>

				<div id="multiple_meters" style="display:none;">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" class="admintable">
						<tr>
							<td colspan="2" >
								Do you have any additional meters at the same service address?
								<input  name="multiplemeters_choice"  type="radio" value="22" onClick="global_checksetting(this,'multiplemeters_section_yes','multiplemeters_section_no','','')" > Yes&nbsp;
								<input  name="multiplemeters_choice" type="radio" value="23" onClick="global_checksetting(this,'multiplemeters_section_yes','multiplemeters_section_no','','')" > No&nbsp;
							</td>
						</tr>
					</table>
					<div id="multiplemeters_section_no" style="display:none;">
					</div>
					<div id="multiplemeters_section_yes" style="display:none;">
						<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
							<tr>
								<td colspan="2">
									How many additional meters would you like to switch over to Energy Plus?
								<select name='meterno' id='meterno' onChange="add_accountSlot(this,1);">
									<option value=''>Select</option>
								<?php
									$maxmeters=10;

									$meterrange = range (1,10);

									foreach($meterrange as $mtno)
									{
										if($Session_meterno==$mtno)
										{
											echo '<option value="'.$mtno.'" selected>'.$mtno.'</option>';
										}
										else
										{
											echo '<option value="'.$mtno.'">'.$mtno.'</option>';
										}
									}

								?>
								</select>
								</td>

							</tr>
							<tr>
								<td colspan="2">
									<span id='account_slots'></span>
									<div id="xaccerr" style="color:#ff0000;"></div>
								</td>
							</tr>
						</table>

				</div>
			</div>
<?php
if($default_green!=1 && $default_green!=3)
{
?>
			<div id="greendiv" >
					<table width="100%" border="0" cellpadding="0" cellspacing="0" class="admintable">
						<tr>
							<td colspan="2" >
								Would you like to add the Energy Plus green option to your account?
								<input  name="greenoption_choice" id="greenoption_choice" type="radio" value="001" onClick="boolean_checksetting(this,'greenoption_section_yes','greenoption_section_no','','')" <?if($Session_greenoption_choice=='001'){echo 'checked';}?>> Yes&nbsp;
								<input  name="greenoption_choice" id="greenoption_choice" type="radio" value="000" onClick="boolean_checksetting(this,'greenoption_section_yes','greenoption_section_no','','')" <?if($Session_greenoption_choice=='000'){echo 'checked';}?>> No&nbsp;
								<a href="javascript:void(0);" onClick="Popup.showModal('green');return false;"><font color="green">What's this?</font></a>
							</td>
						</tr>
					</table>
			</div>
			<div id="greenoption_section_yes" style="display:none;">
					<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
						<tr>
							<td  class="formdata" colspan="2">
								By selecting the green option a penny will be added to your per Kwh price.
							</td>
						</tr>
					</table>
			</div>
			<div id="greenoption_section_no" style="display:none;">
			</div>
<?
}

if($default_green==3)
{
echo "<div>
	Energy Plus will automatically add the Green product to your account. This benefit, which supports 100% wind power, is available at no additional cost exclusively for SPG Members.
</div>";
}
?>
<?

if($Session_State==3)
{
	$sp='';
	$en='';
	if($Session_spanishbill==1)
	{
		$sp="Selected";
	}
	else
	{
		$en="Selected";
	}
?>

				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="admintable">
						<tr>
							<td colspan="2" >
								Texas law requires that I ask which language would  you prefer to receive future correspondence from Energy Plus, including your bill?
								<select name='lang_option' id='lang_option'>
									<option value='0' <?=$en;?>>English</option>
									<option value='1' <?=$sp;?>>Spanish</option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2"><span id="milesnumbervalid"></span></td>
						</tr>
				</table>

<?
	if(($Session_resbus == 1)||($Session_resbus==3))
	{
?>
	    <div id="bizinfo" >
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="admintable">
				<tr>
					<td>How many years have you been in business?</td>
					<td>
						<select name="years_inbiz" id="years_inbiz" >
							<option value=''>Please Select</option>
							<option value='None'>None</option>
							<option value='Less than 2 years' <?if($Session_years_inbiz=='Less than 2 years'){echo 'Selected';}?>>Less than 2 years</option>
							<option  value='2 to 5 years' <?if($Session_years_inbiz=='2 to 5 years'){echo 'Selected';}?>>2 to 5 years</option>
							<option  value='More than 5 years' <?if($Session_years_inbiz=='More than 5 years'){echo 'Selected';}?>>More than 5 years</option>
							<option value='No Answer'>No Answer</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2"><span id="milesnumbervalid"></span></td>
				</tr>
				<tr>
					<td>How many years has your business been at its current address?</td>
					<td>
						<select name="years_bizaddr" id="years_bizaddr" >
						<option value=''>Please Select</option>
						<option value='None'>None</option>
						<option value='Less than 2 years' <?if($Session_years_bizaddr=='Less than 2 years'){echo 'Selected';}?>>Less than 2 years</option>
						<option value='2 to 5 years' <?if($Session_years_bizaddr=='2 to 5 years'){echo 'Selected';}?>>2 to 5 years</option>
						<option value='More than 5 years' <?if($Session_years_bizaddr=='More than 5 years'){echo 'Selected';}?>>More than 5 years</option>
						<option value='No Answer'>No Answer</option>
					</td>
				</tr>
				<tr>
					<td colspan="2"><span id="milesnumbervalid"></span></td>
				</tr>
				<tr>
					<td>How many times have you been late paying your electric bill in the past 6 months?</td>
					<td>
						<select name="late_payment6" id="late_payment6" >
							<option value=''>Please Select</option>
							<option value='Never' <?if($Session_late_payment6=='Never'){echo 'Selected';}?>>Never</option>
							<option value='Once' <?if($Session_late_payment6=='Once'){echo 'Selected';}?>>Once</option>
							<option value='More than once' <?if($Session_late_payment6=='More than once'){echo 'Selected';}?>>More than once</option>
							<option value='No Answer'>No Answer</option>
					</td>
				</tr>
				<tr>
					<td colspan="2"><span id="milesnumbervalid"></span></td>
				</tr>
				<tr>
					<td>Has the name of your business ever changed?</td>
					<td>
						<select name="busname_change" id="busname_change" >
							<option value=''>Please Select</option>
							<option value='Never' <?if($Session_busname_change=='Never'){echo 'Selected';}?>>Never</option>
							<option value='Once' <?if($Session_busname_change=='Once'){echo 'Selected';}?>>Once</option>
							<option value='More than once' <?if($Session_busname_change=='More than once'){echo 'Selected';}?>>More than once</option>
							<option value='No Answer'>No Answer</option>
					</td>
				</tr>

				<tr>
					<td colspan="2"><span id="milesnumbervalid"></span></td>
				</tr>

				<tr>
					<td>How many electricity suppliers have you been with in the previous year?</td>
					<td>
						<select name="elec_supp_prevyear" id="elec_supp_prevyear" >
							<option value=''>Please Select</option>
							<option value='None'>None</option>
							<option value='1' <?if($Session_elec_supp_prevyear=='1'){echo 'Selected';}?>>1</option>
							<option value='2' <?if($Session_elec_supp_prevyear=='2'){echo 'Selected';}?>>2</option>
							<option value='More than 2' <?if($Session_elec_supp_prevyear=='More than 2'){echo 'Selected';}?>>More than 2</option>
							<option value='No Answer'>No Answer</option>
					</td>
				</tr>
				<tr>
					<td colspan="2"><span id="milesnumbervalid"></span></td>
				</tr>
				<tr>
					<td>When is the last time you opened a line of credit for your business?</td>
					<td>
						<select name="years_creditbiz" id="years_creditbiz" >
						<option value=''>Please Select</option>
						<option value='Never' <?if($Session_years_creditbiz=='Never'){echo 'Selected';}?>>Never</option>
						<option value='Less than 2 years ago' <?if($Session_years_creditbiz=='Less than 2 years ago'){echo 'Selected';}?>>Less than 2 years ago</option>
						<option value='2 - 5 years ago' <?if($Session_years_creditbiz=='2 - 5 years ago'){echo 'Selected';}?>>2 - 5 years ago</option>
						<option value='More than 5 years ago' <?if($Session_years_creditbiz=='More than 5 years ago'){echo 'Selected';}?>>More than 5 years ago</option>
						<option value='No Answer'>No Answer</option>
					</td>
				</tr>
				<tr>
					<td colspan="2"><span id="milesnumbervalid"></span></td>
				</tr>
				<tr>
					<td colspan="2"><p class="rep_note">NOTE TO TSR: Business questions are not required if customer does not prefer to answer a single question or all questions. Click on Save & Continue to complete the enrollment.</p></td>
				</tr>
				</table>
			</div>
<?
	}
?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="admintable">
				<tr>
					<td colspan="2">
						May I please have your <?=$spouse_var;?> Social Security Number
						<input  name="ssn" type="radio" value="27" onClick="global_checksetting(this,'ssn_yes','ssn_no','','')" checked> Yes&nbsp;
						<input  name="ssn" type="radio" value="28" onClick="global_checksetting(this,'ssn_yes','ssn_no','disp','33')" > No&nbsp;
					</td>
				</tr>
			</table>

			<div id="ssn_no" style="display:none;">
				<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
					<tr>
						<td colspan="2">
							Your social security number is required as a way for us to verify your identity.  It will also be used to perform a credit check.  Please note the check is a soft inquiry and will not impact your credit score.  Would you like to reconsider and provide your Social Security Number?
							<input name="ssnconf" type="radio" value="29" onClick="global_checksetting(this,'ssn_yes','ssnconf_no','','')">Yes
							<input name="ssnconf" type="radio" value="30" onClick="global_checksetting(this,'ssn_yes','ssnconf_no','disp','33')">No
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div id="ssnconf_no" style="display: none;">Unfortunately we need to know your social security number before we can proceed with your enrollment.  Once you are willing to provide this information, please call us back at  <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>.
								 <p>Is there anything else I can do for you today?
								<input name="ssnconf1" type="radio" value="31" onClick="global_checksetting(this,'ssnssnconf_no_yes','ssnssnconf_no_no','cust','8')">&nbsp;&nbsp;Yes&nbsp;&nbsp;
								<input name="ssnconf1" type="radio" value="32" onClick="global_checksetting(this,'ssnssnconf_no_yes','ssnssnconf_no_no','disp','33')">&nbsp;&nbsp;No</p>
							</div>
					</tr>
					<tr>
						<td colspan="2">
							<div id="ssnssnconf_no_yes" style="display: none;"><p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p><BR></div>
							<div id="ssnssnconf_no_no" style="display: none;"><p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D #33: CALLER REFUSED TO PROVIDE  SS#</p><BR></div>
							<div><p class="rep_note">NOTE TO TSR: ONLY IF CALLER IS IRRATE:  IF CALL STILL DOES NOT WANT TO PROVIDE SS GO TO THE CUSTOMER SERVICE HOT KEY #8.</p></div>
						</td>
					</tr>
				</table>
			</div>
			<div id="ssn_yes" style="display:none;">
				<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
					<tr>
						<td bgcolor="#efefef" class="formdata" width="230">Social Security Number<font color="red">*</font>&nbsp;&nbsp;&nbsp;</td>
						<td bgcolor="#efefef" class="formdata">
							<div class="inp_h">
								<input type="text" name="ssn1" id="ssn1"  AUTOCOMPLETE=OFF value="" maxlength="3" size="5">&nbsp;-&nbsp;
								<input type="text" name="ssn2" id="ssn2"  AUTOCOMPLETE=OFF value="" maxlength="2" size="5">&nbsp;-&nbsp;
								<input type="text" name="ssn3" id="ssn3"  AUTOCOMPLETE=OFF value="" maxlength="4" size="5">

							</div>
						</td>
					</tr>
				</table>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="admintable">
					<tr>
						<td colspan="2">
							May I please have your <?=$spouse_var;?> date of birth?
							<input  name="dob" type="radio" value="33" onClick="global_checksetting(this,'dob_yes','dob_no','','')" Checked> Yes&nbsp;
							<input  name="dob" type="radio" value="34" onClick="global_checksetting(this,'dob_yes','dob_no','disp','21')" > No&nbsp;
						</td>
					</tr>
				</table>
			</div>
			<div id="dob_yes" style="display:none;">
				<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
					<tr>
						<td bgcolor="#efefef" class="formdata" width="230">Date of Birth<font color="red">*</font>&nbsp;&nbsp;&nbsp;</td>
						<td bgcolor="#efefef" class="formdata">
								<select name="month" id="month" >
									<option value="">Month</option>
										<?php
											for($m=1;$m<13;$m++)
											{
												$dm = ($m<10)?'0'.$m:$m;
												if($Session_month==$dm)
												{
													echo '<option value="'.$dm.'" Selected>'.$dm.'</option>';
												}
												else
												{
													echo '<option value="'.$dm.'">'.$dm.'</option>';
												}

											}
										?>
								</select>
								<select name="day" id="day" >
									<option value="">Day</option>
										<?php
											for($m=1;$m<32;$m++)
											{
												$dm = ($m<10)?'0'.$m:$m;
												if($Session_day==$dm)
												{
													echo '<option value="'.$dm.'" Selected>'.$dm.'</option>';
												}
												else
												{
													echo '<option value="'.$dm.'">'.$dm.'</option>';
												}

											}
										?>
								</select>
								<select name="year" id="year" >
									<option value="">Year</option>
										<?php
											for($m=$year-18;$m>1900;$m--)
											{
												if($Session_year==$m)
												{
													echo '<option value="'.$m.'" Selected>'.$m.'</option>';
												}
												else
												{
													echo '<option value="'.$m.'">'.$m.'</option>';
												}

											}
										?>
								</select>
								<input type='submit' name='validate_dob' id='validate_dob' value='Validate' >

						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td colspan="2">
							<div id="dob_under18" style="display:none;">
								<BR>
								I am sorry but you must be 18 years of age to enroll with Energy Plus.We apologize, but we cannot complete your enrollment at this time. Is there anything else I can do for you today?
								<input  name="dobunder18" type="radio" value="39" onClick="global_checksetting(this,'dob_under18_yes','dob_under18_no','cust','')">&nbsp;&nbsp;Yes
								<input  name="dobunder18" type="radio" value="40" onClick="global_checksetting(this,'dob_under18_yes','dob_under18_no','disp','11')">&nbsp;&nbsp;No
								<BR>
							</div>
						</td>
					<tr>
					<tr>
						<td colspan="2">
							<div id="dob_under18_yes" style="display:none;"><span class='rep_note'>IF CUSTOMER HAS QUESTIONS GO TO FAQS</span></div>
							<div id="dob_under18_no" style="display:none;"><span class='rep_note'>NOTE TO TSR : CALL WAS AUTO DISPO'D #11: CALLER  IS UNDER 18</span></div>
						</td>

					</tr>
				</table>

			</div>
			<div id="dob_no" style="display:none;">
				<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
					<tr>
						<td colspan="2" class="formdata" >
							Unfortunately we need to know your date of birth before we can proceed with your enrollment.  Once you are willing to provide this information, please call us back at  <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>.  Is there anything else I can do for you today?
							<input  name="dob_no" type="radio" value="35" onClick="global_checksetting(this,'dob_no_yes','dob_no_no','cust','')" > Yes&nbsp;
							<input  name="dob_no" type="radio" value="36" onClick="global_checksetting(this,'dob_no_yes','dob_no_no','disp','21')" > No&nbsp;
							<div id="dob_no_yes" style="display:none;"><p class="rep_note">NOTE TO TSR: IF CUSTOMER HAS QUESTIONS GO TO FAQS</div>
							<div id="dob_no_no"  style="display:none;">Thank you for calling Energy Plus and have a nice day."<p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D #21: CALLER REFUSED TO PROVIDE DATE OF BIRTH</p></div>
						</td>
					</tr>
				</table>
			</div>
			<div id="mothermaidenname" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="admintable">
								<tr>
									<td colspan="2">
										May I have your <?=$spouse_var;?> Mother's Maiden Name?
										<input  name="mothermaidenname" type="radio" value="37" onClick="global_checksetting(this,'mothermaidenname_yes','mothermaidenname_no','','')" Checked> Yes&nbsp;
										<input  name="mothermaidenname" type="radio" value="38" onClick="global_checksetting(this,'mothermaidenname_yes','mothermaidenname_no','','')" > No&nbsp;
									</td>
								</tr>

				</table>
			</div>

			<div id="mothermaidenname_no" style="display:none;">
				<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
					<tr>
						<td colspan="2" class="formdata" >
							You do not have to use your mother's maiden name as a password, you can create your own password
							<BR><span class='rep_note'>NOTE TO TSR: IF MOTHER'S MAIDEN NAME IS NOT ENTERED, IT WILL DEFAULT TO ENERGY PLUS. CLICK SAVE AND CONTINUE</span>
						</td>
					</tr>
				</table>
			</div>
			<div id="mothermaidenname_yes" style="display:none;">
				<table width="100%" cellpadding="0" cellspacing="0" class="admintable">
					<tr>
						<td bgcolor="#efefef" class="formdata" width="230">Mother's Maiden Name<font color="red">*</font>&nbsp;&nbsp;&nbsp;</td>
						<td bgcolor="#efefef" class="formdata">
							<div class="inp_h">
								<input type="text" name="mothermaiden" id="mothermaiden"  AUTOCOMPLETE=OFF value="<?=$Session_mother_maiden_name;?>" >
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							Please be advised that at this time you are authorizing Energy Plus to obtain business and consumer credit bureau reports in connection with your request for an account.
						</td>
					</tr>
				</table>
			</div>
<?php
	}
?>
	<div style="padding-top: 3em; padding-bottom: 3em; ">
			<input type="button" class="ib_button" name="btn_continue" id="btn_continue" value="Save and Continue" onClick="stepval(this)" >
			<input type='hidden' name='regid' id='regid' value="<?=$regid;?>">
			<input type="button" class="ib_button" name="btn_save" id="btn_save" value="Save" onClick="stepval(this)" >
			<input type="button" class="ib_button" name="btn_log_dispo" id="btn_log_dispo" value="Log Dispo" onClick="logDispo(this)" disabled>
			<input type='hidden' name='g_deposit' id='g_deposit'>
			<input type='hidden' name='eflag' id='eflag' >
			<input type='hidden' name='eflagid' id='eflagid' >
			<input type='hidden' name='next_url1' id='next_url1' value="disclosure.php" >
			<input type='hidden' name='next_url' id='next_url' value="select_deposit.php" >
			<input type='hidden' name='session_state' id='session_state' value="<?=$Session_State;?>">
			<input type='hidden' name='Local_Utility' id='Local_Utility' value="<?=$Session_select_utility;?>">
			<input type='hidden' name='acctext' id='acctext' value="<?=$Session_acctext;?>">
			<input type='hidden' name='rctext_val' id='rctext_val' value="<?=$rctext_val;?>">
			<input type='hidden' name='srtext_val' id='srtext_val' value="<?=$srtext_val;?>">
			<input type='hidden' name='resbus' id='resbus' value="<?=$Session_resbus;?>">
			<input type='hidden' name='hid_accountchoice' id='hid_accountchoice'>
			<input type='hidden' name='hid_businesschoice' id='hid_businesschoice'>
			<input type='hidden' name='hid_servicerefchoice' id='hid_servicerefchoice'>
			<input type='hidden' name='hid_dob' id='hid_dob'>
			<input type='hidden' name='hid_default_green' id='hid_default_green' value="<?=$default_green?>">
			<input type='hidden' name='first_name' id='first_name' value="<?=$_SESSION['tabs']['customer']['first_name'];?>">
			<input type='hidden' name='last_name' id='last_name' value="<?=$_SESSION['tabs']['customer']['last_name'];?>">
			<input type='hidden' name='Service_Address' id='Service_Address' value="<?=$_SESSION['tabs']['customer']['Service_Address1'];?>">
			<input type='hidden' name='Service_City' id='Service_City' value="<?=$_SESSION['tabs']['customer']['Service_City'];?>">
			<input type='hidden' name='Service_Zip5' id='Service_Zip5' value="<?=$_SESSION['tabs']['customer']['Service_Zip5'];?>">
		</div>
</form>
<?
	if($Session_State==3)
	{
?>
<SCRIPT TYPE="text/javascript">
<!--
autojump('ssn1', 'ssn2', 3);
autojump('ssn2', 'ssn3', 2);
//-->
</SCRIPT>
<?
	}
?>

        </div>
<?php
        require_once 'includes/statusbar.php';
?>
</div>
<?php
        require_once 'includes/footer.php';
?>


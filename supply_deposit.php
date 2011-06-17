<?php


//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Deposit Module//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////

require_once 'includes/main.php';
require_once 'includes/check_state.php';
require_once 'includes/check_util.php';

require_once 'EP/Model/StateMapper.php';
require_once 'EP/Model/PartnerMapper.php';
require_once 'EP/HTML/Form/Widget/RadioBool.php';


$register = $_SESSION['register'];
$regid=$register->getId();

$Session_State= null;
if(isset($_SESSION['tabs']['state']['state']))
{
	$Session_State = $_SESSION['tabs']['state']['state'];
}

if($Session_State=='')
{
	$header_prev = "Location: $base_url/myinbound/select_state.php";
	header($header_prev);
}


if($Session_State==3)
{
	include_once 'creditcheck.php';
}

$Session_select_utility= null;
if(isset($_SESSION['tabs']['utility']['select_utility']))
{
	$Session_select_utility=$_SESSION['tabs']['utility']['select_utility'];
}

$Session_Service_fname = null;
if(isset($_SESSION['tabs']['customer']['first_name']))
{
	$Session_Service_fname = $_SESSION['tabs']['customer']['first_name'];
}


$Session_Service_lname = null;
if(isset($_SESSION['tabs']['customer']['last_name']))
{
	$Session_Service_lname = $_SESSION['tabs']['customer']['last_name'];
}


$session_name = '';


if (($Session_Service_fname!='') && ($Session_Service_lname!='') )
{
	 $session_name = $Session_Service_fname. ' ' .$Session_Service_lname;
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


$Session_depositamount= null;
if(isset($_SESSION['depositamount']))
{
	$Session_depositamount=$_SESSION['depositamount'];
}

if($Session_depositamount==0 && $Session_State==3)
{
	$header = "Location: $base_url/myinbound/disclosure.php";
	header( $header );
}


if($Session_State)
{
	$statechoice_yes='checked';
}
else
{
	$statechoice_no='checked';
}

    require_once 'includes/header.php';
?>
<script type="text/javascript">
var http = getHTTPObject();
var http2 = getHTTPObject();
var http3 = getHTTPObject();
</script>
<script type="text/javascript" src="../myinbound/scripts/select_deposit.js"></script>
<script type="text/javascript" src="../myinbound/scripts/select_utility.js"></script>
<script type="text/javascript" src="/js/validate.js"></script>
<script type="text/javascript" src="/js/admintooltip.js"></script>
<script type="text/javascript" src="/js/modal_popup.js"></script>

</head>
<body>
<div id="checkpopup" style="border:1px solid #ccc; background-color:#fff; width:700px; padding:25px; text-align:left; display:none;">
        <div style="float:right;">
		<a href="javascript:void(0)" onClick="Popup.hide('checkpopup')">
			<img src="../images/close.gif" alt="Close" border="0">
		</a>
	</div>

	<p><img id="bimg" src="../myinbound/images/checkpopup.jpg" alt=""></p>
</div>
<div id="cardccv" style="border:1px solid #ccc; background-color:#fff; width:700px; padding:25px; text-align:left; display:none;">
        <div style="float:right;">
		<a href="javascript:void(0)" onClick="Popup.hide('cardccv')">
			<img src="../images/close.gif" alt="Close" border="0">
		</a>
	</div>
	<p><img id="bimg" src="../myinbound/images/cardccv.jpg" alt=""></p>
	<p class="formdata">Your credit card security code is a 3- or 4-digit number located on the front or back of the card as shown</p>
</div>
<div class="yui3-g" id="container" >
<?php
	$page = basename( __FILE__ );
	require_once 'includes/nav.php';
?>
<div class="yui3-u" id="main">
<form id="inbound_deposit" name="inbound_deposit" method="post" action="select_deposit.php"  autocomplete="off">
<?
if($Session_State==3)
{
?>
<input type='hidden' name='eflag' id='eflag' >
<input type='hidden' name='eflagid' id='eflagid' >
<div id="depositerrmsg" style="clear:both"></div>
<div class="box" id="depositconf"></div>
<div class="whiteblock">
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="5" class="admintable">
	<tr>
      		<td colspan="2" class="formdata">
			In order to complete your enrollment, Energy Plus requires a deposit of <span id="showamtach">$ <?=$Session_depositamount;?></span>. You may be eligible to have this deposit waived if you are 65 years of age or older or have been a victim of family violence. We will accept an electronic transfer from your checking account or a payment via credit card (American Express, MasterCard or Visa).  Which would you like to use to make payment?
			<select name='dtype' id='dtype'  onchange="return call_deposit(this)">
				<option value="">Select</option>
				<option value="ACH">Checking Account(ACH)</option>
				<option value="CC">Credit Card</option>
				<option value="CWP">Customer will not Provide</option>
			</select>
		</td>
	</tr>
</table>
</div>
<div>
	<div style="float: right; padding-top: 8px;">
		<a class="tabtext" href="javascript: void();" onclick="tsrnote('open')">+Open</a> / </span>
		<a class="tabtext" href="javascript: void();" onclick="tsrnote('close')">-Close</a></span>
	</div>
	<div style="float: right; padding-top: 8px;" class="rep_note">NOTES TO TSR:</div>
</div>
<BR>
<BR>
<div id="tsrnote" style="display: none;">
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="5" class="admintable">
	<tr>
		<td class="formdata"><span class="rep_note">TSR NOTE: IF AT ANYTIME THE CUSTOMER SAYS THEY ARE A LITE UP CUSTOMER:</span><span> In order for us to facilitate the fee waiver we'll need you to provide us proof that you are eligible for Lite Up.  This can be a copy of a previous bill that shows the discount or documentations from a Texas Agency.  A copy can either be faxed to 866-857-8014 or mailed to: Energy Plus, C/O Enrollment Department, 3711 Market Street, 10th Floor Philadelphia, PA 19104. Once we receive the documentation, a customer service representative will contact you to complete the enrollment  process.  Please include your name and a contact number on the documentation.</span></td>
	</tr>
	<tr>
		<td class="formdata"><span class="rep_note">TSR NOTE: IF AT ANY TIME THE CUSTOMER ASKS OR SAYS ANYTHING ABOUT A FEE WAIVER:</span><span class="formdata"> If you are required to pay a deposit you may be eligible to have this requirement waived if you are at least 65 years of age and you are not currently delinquent in payment of any electric service account or if you have been determined to be a victim of family violence as defined in the Texas Family Code.</span></td>
	</tr>
	<tr>
		<td class="formdata">
			<span class="rep_note">IF THE CALLER SAYS HE IS 65 OR OLDER: </span><span class="formdata">In order for us to facilitate the fee waiver we will need you to provide us with either a driver's license, passport, or state issued ID.</span>
			<span class="formdata">You will need to send us proof of your tax exemption eligibility either by fax or mail to:
										<ul>
											<li><span class="formdata">Fax: 866-857-8014 Attn: Enrollment Department</span></li>
											<li><span class="formdata">Mail: Energy Plus, c/o Enrollment Department, 3711 Market Street, 10th Floor, Philadelphia, PA 19104</span></li>
										</ul>
							</span>
			<span class="formdata">
					Once we receive the documentation, a customer service representative will contact you to complete the enrollment process.  Please include your name and a contact number on the documentation.
			</span>
		</td>
	</tr>
	<tr>
		<td class="formdata">
			<span class="rep_note">IF THE CALLER SAYS HE/SHE IS A VICTIM OF FAMILY VIOLENCE: </span><span class="formdata">In order for us to facilitate the fee waiver we will need you to provide us with a  copy of the letter received from the appropriate agency. </span>
			<span class="formdata">You will need to send us proof of your tax exemption eligibility either by fax or mail to:
										<ul>
											<li><span class="formdata">Fax: 866-857-8014 Attn: Enrollment Department</span></li>
											<li><span class="formdata">Mail: Energy Plus, c/o Enrollment Department, 3711 Market Street, 10th Floor, Philadelphia, PA 19104</span></li>
										</ul>
							</span>
			<span class="formdata">
			Once we receive the documentation, a customer service representative will contact you to complete the enrollment process.  Please include your name and a contact number on the documentation
			</span>

	</tr>
	<tr>
		<td class="formdata"><span class="rep_note">IF CALL END DUE TO FEE WAIVER DISPOSITION CALL # 88 AND INCLUDE CUSTOMER NAME, ADDRESS AND PHONE NUMBER.</span></td>
	</tr>
</table>
</div>

<div id="depositcwp" style="display:none;">
		<div>
					<div style="float: right; padding-top: 8px;">
						<a class="tabtext" href="javascript: void();" onclick="tsrnote_reb1('open')">+Open</a> / </span>
						<a class="tabtext" href="javascript: void();" onclick="tsrnote_reb1('close')">-Close</a></span>
					</div>
					<div style="float: right; padding-top: 8px;" class="rep_note">REBUTTALS - IF CUSTOMER WILL NOT PROVIDE DEPOSIT INFORMATION:
					</div>
		</div>
		<div id='tsrnote_reb1' style="display:none;">
						<table width="100%" align="left" border="0" cellspacing="0" cellpadding="5" class="admintable">
							<tr>
								<td colspan="2">
									Asking for deposits before the start of service is a common practice in the Texas electricity market.
	          							<BR><BR>You will receive a receipt of your deposit payment on your first invoice. Your deposit plus earned interest of 0.38% will be returned to you after 12 consecutive months of on-time payments or at anytime prior to that you choose to leave Energy Plus. The deposit will be applied to your final bill and any remaining amount will be sent to you in the form of a check.
									<BR><BR><p class="rep_note">TSR NOTE: If a customer is extremely upset or irate about the deposit and you have used the rebuttals above without success, please tell the customer you will have a manger look into this and get back to them. Capture name, address, and phone number and Escalate to your Supervisor. Disposition the call #39.</p>
								</td>
							</tr>
				<tr>
					<td colspan="2">
						<p class="rep_note">IF CUSTOMER STILL WILL NOT PROVIDE DEPOSIT INFORMATION.</p>
						<p class="formdata">Unfortunately we need to have credit card number or ACH details to proceed with  your enrollment.  Once you have this information, please call us back at  <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>.  Is there anything else I can do for you today?</span>
						&nbsp;<input name="ccachquestions" type="radio" value="1" onclick="global_checksetting(this,'ccachquestions_yes','ccachquestions_no','cust','')">Yes
						<input name="ccachquestions" type="radio" value="2" onclick="global_checksetting(this,'ccachquestions_yes','ccachquestions_no','disp','38')">No
						</p>
					</td>
				</tr>
				<tr>
	          			<td colspan="2">
						<div id='ccachquestions_yes' style="display:none;"><p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p></div>
	          				<div id='ccachquestions_no' style="display:none;"><p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D #38: CALLER REFUSED TO PROVIDE Deposit Account Info</p></div>
					</td>
				</tr>
		  		<tr>
					<td colspan="2">
						<input type="button" class="ib_button" name="btn_continue2" id="btn_continue2" value="Save and Continue" onclick="sendDeposit()" disabled>
						<input type="button" class="ib_button" name="btn_save2" id="btn_save2" value="Save" onclick="sendDeposit(this)" disabled>
						<input type="button" class="ib_button" name="btn_log_dispo2" id="btn_log_dispo2" value="Log Dispo" onclick="logDispo(this)" disabled>
						<input type='hidden' name='submitaction' id='submitaction'>
					</td>
				</tr>
			</table>
		</div>
	</div>
<div id="depositach" style="display:none;">
		<table class="admintable" style="clear:both" border="0" cellspacing="0" cellpadding="0" with="100%">
			<tr>
				<td colspan="2">May I please have the Deposit Account Information?
				<input name="depositach" type="radio" value="1" onclick="global_checksetting(this,'depositach_yes','depositach_no','','')">Yes
				<input name="depositach" type="radio" value="2" onclick="global_checksetting(this,'depositach_yes','depositach_no','disp','23')">No</td>
			</tr>
		</table>
		<div id="depositach_yes" style="display:none;">
			<table class="admintable" style="clear:both" border="0" cellspacing="0" cellpadding="0" with="100%">
				<tr>
					<td class="formdata" colspan="2">Please provide me with the first name that is on the checking account you will be using?</td>
				</tr>
				<tr>
					<td bgcolor="#efefef" class="formdata" width="30%">First Name<font color="red">*</font>: </td><td bgcolor="#efefef" class="formdata" width="70%"><input type="text" id="achfname" name="achfname" maxlength="51" /></td></tr>
				<tr>
					<td class="formdata" colspan="2">Please provide me with the last name that is on the checking account you will be using?</td></tr>
				<tr>
					<td bgcolor="#efefef" class="formdata" width="30%" >Last Name<font color="red">*</font>: </td><td bgcolor="#efefef" class="formdata" width="70%"><input type="text" id="achlname" name="achlname" maxlength="51" /></td></tr>
				<tr>
					<td class="formdata" colspan="2">Now on the bottom left hand side of your check is the Bank Routing number can you please read it to me?</td>
				</tr>
				<tr>
					<td colspan="2">
							<div id="verificationach" style="display:none;">
													<div style="width:100%;">
														<p class='rep_note'>NOTE TO TSR: The name provided does not match the person you are speaking with. Please Read the following:<p>
														<span class='red_note'>Can I please speak to the account holder?
															<input name="errverificationach" type="radio" value="1" onclick="global_checksetting(this,'verificationach_yes','verificationach_no','','')">Yes
															<input name="errverificationach" type="radio" value="2" onclick="global_checksetting(this,'verificationach_yes','verificationach_no','','')">No
														</span>
													</div>
													<div id="verificationach_yes" style="display:none;">
																<div style="width:100%;">
																			<p class='red_note'>Can you please confirm that I am speaking with <span id='achname1'></span>?
																				<input name="errverificationach1_yes1" type="radio" value="1" onclick="global_checksetting(this,'verificationach1_yes','verificationach1_no','','')">Yes
																				<input name="errverificationach1_yes1" type="radio" value="2" onclick="global_checksetting(this,'verificationach1_yes','verificationach1_no','','')">No
																			</p>
																</div>
															<div id="verificationach1_yes" style="display:none;">
																<p class='red_note'>Do you authorize the use of your checking account <span id='account_no'></span> to pay the $<?=$Session_depositamount;?> deposit on the Energy Plus Account?
																	<input name="errverificationcardach2" type="radio" value="1" onclick="global_checksetting(this,'verificationcardach2_yes','verificationcardach2_no','','')">Yes
																	<input name="errverificationcardach2" type="radio" value="2" onclick="global_checksetting(this,'verificationcardach2_yes','verificationcardach2_no','','')">No
																</p>
																<div id="verificationcardach2_yes" style="width:100%;display:none;">
																	<p class='red_note'>Thank you. Please put <?=$session_name;?> back on the phone so we can complete the enrollment.<p> <span class='rep_note'>NOTE TO TSR: CLICK SAVE AND CONTINUE</span>
																</div>
																<div id="verificationcardach2_no" style="display:none;">
																	 <div style="width:100%;">
																			<p class="rep_note">
																					NOTE TO TSR: PLEASE ASK FOR <?=$session_name;?> TO COME BACK ON THE PHONE THEN READ THE FOLLOWING:
																			</p>
																			<span class="red_note">
																					Unfortunately I will not be able to process the payment or complete the enrollment. Is there any thing else I can help you with today
																					<input name="errverificationach4_no" type="radio" value="1" onclick="global_checksetting(this,'verificationach4_no_yes','verificationach4_no_no','','')">Yes
																					<input name="errverificationach4_no" type="radio" value="2" onclick="global_checksetting(this,'verificationach4_no_yes','verificationach4_no_no','disp','35')">No
																			</span>
																	 </div>
																	 <div id="verificationach4_no_yes" style="display:none;">
																	 			<p class="rep_note">If Customer has Questions GO TO FAQS</p>
																	 </div>
																	 <div id="verificationach4_no_no" style="display:none;">
																	 			<p class="rep_note">Payment Info was Decline</p>
																	</div>




																</div>
															</div>
															<div id="verificationach1_no" style="display:none;">
																<p class="red_note">Unfortunately I will not be able to process the payment or complete the enrollment. Is there anything else I can help you with today?
																<input name="errverificationach_no" type="radio" value="1" onclick="global_checksetting(this,'verificationach_no_yes','verificationach_no_no','','')">Yes
																<input name="errverificationach_no" type="radio" value="2" onclick="global_checksetting(this,'verificationach_no_yes','verificationach_no_no','disp','35')">No</p>

																<div id="verificationach_no_yes" style="display:none;">
																		<p class="rep_note">If Customer has Questions GO TO FAQS</p>
																</div>
																<div id="verificationach_no_no" style="display:none;">
																		<p class="rep_note">Payment Info was Decline</p>
																</div>
															</div>
													</div>
													<div id="verificationach_no" style="display:none;">
															<p class="red_note">Unfortunately I will not be able to process the payment or complete the enrollment. Is there anything else I can help you with the today?
																<input name="errverificationach3_no" type="radio" value="1" onclick="global_checksetting(this,'verificationach3_no_yes','verificationach3_no_no','','')">Yes
																<input name="errverificationach3_no" type="radio" value="2" onclick="global_checksetting(this,'verificationach3_no_yes','verificationach3_no_no','disp','35')">No</p>
																<div id="verificationach3_no_yes" style="height:30px;display:none;">
																		<p class="rep_note">If Customer has Questions GO TO FAQS</p>
																</div>
																<div id="verificationach3_no_no" style="display:none;">
																		<p class="rep_note">Payment Info was Decline</p>
																</div>
													</div>
							</div>
					</td>
				</tr>
				<tr>
					<td bgcolor="#efefef" class="formdata" width="30%" >Bank Routing Number<font color="red">*</font>: </td><td bgcolor="#efefef" class="formdata" width="70%"><input type="text" id="achroute" name="achroute" maxlength='9' onblur="chkRouting(this)"/>
						<a href="javascript:void(0)" onClick="Popup.showModal('checkpopup');return false;"><img src="../images/icon-question.gif" alt="?" border="0" /></a><font color="red"><div id="routingerror" style="color:#ff0000;"></div></font>
					</td>
				</tr>
				<tr>
					<td  class="formdata" colspan="2">On the bottom right side of the check is your checking account number can you please tell me what it is?</td></tr>
				<tr>
					<td bgcolor="#efefef" class="formdata" width="30%">Checking Account Number<font color="red">*</font>: </td><td bgcolor="#efefef" class="formdata" width="70%"><input type="text" id="achaccount" name="achaccount" maxlength="16" />
						<a href="javascript:void(0)" onClick="Popup.showModal('checkpopup');return false;"><img src="../images/icon-question.gif" alt="?" border="0" /></a>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr><td class="formdata">Total Payment Amount: </td><td class="formdata"><span id="showamtach">$ <?=$Session_depositamount;?></span></td></tr>
				<!--<tr><td colspan="2" style="text-align:right;"><img src="../images/button-back.jpg" alt="Back" onclick="backToPT()" style="border: none; cursor:pointer; margin-top: 5px;" /><img src="../myinbound/images/button-continueonly.jpg" onclick="sendACH()" alt="go" style="cursor:pointer; margin-top: 5px;" /></td></tr>-->
			</table>
		</div>
		<div id="depositach_no" style="display:none;">
			<table>
							<tr>
									<td colspan="2">
																							<div style="float: right; padding-top: 8px;">
																									<a class="tabtext" href="javascript: void();" onclick="tsrnote_reb('open','ach')">+Open</a> /
																									<a class="tabtext" href="javascript: void();" onclick="tsrnote_reb('close','ach')">-Close</a>
																							</div>
																							<div style="float: right; padding-top: 8px;" class="rep_note">REBUTTALS - IF CUSTOMER WILL NOT PROVIDE PAYMENT INFORMATION.</div>
																							<div id='tsrnote_achreb' style="display:none;">
																			          							<BR><BR>
																			          							<p>
																			          								Asking for deposits before the start of service is a common practice in the Texas electricity market.
																			          								You will receive a receipt of your deposit payment on your first invoice. Your deposit plus earned interest of 0.38% will be returned to you after 12 consecutive months of on-time payments or at anytime prior to that you choose to leave Energy Plus. The deposit will be applied to your final bill and any remaining amount will be sent to you in the form of a check.
																			          							</p>
																											<BR><p class="rep_note">TSR NOTE: If a customer is extremely upset or irate about the deposit and you have used the rebuttals above without success, please tell the customer you will have a manger look into this and get back to them. Capture name, address, and phone number and Escalate to your Supervisor. Disposition the call #39.</p>
																							</div>
																							<div id='tsrnote_achreb1' style="display:none;" style="float: right; padding-top: 8px;" class="rep_note">IF CUSTOMER STILL WILL NOT PROVIDE PAYMENT INFORMATION.</div>

									</td>
							</tr>
							<tr>
									<td colspan="2">

													Unfortunately we need to know your  checking account information before we can complete your enrollment.  Once you have this information, please call us back at  <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>.  Is there anything else I can do for you today?
													<input name="depositach_no" type="radio" value="1" onclick="global_checksetting(this,'achquestions_yes','achquestions_no','cust','')">Yes
													<input name="depositach_no" type="radio" value="2" onclick="global_checksetting(this,'achquestions_yes','achquestions_no','disp','23')">No
									</td>
							</tr>
							<tr>
									<td colspan="2">
						          					<div id='achquestions_yes' style="display:none;"><p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p></div>
						          					<div id='achquestions_no' style="display:none;"><p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D #23-Caller refused to provide checking Account Information)</p></div>
									</td>
							</tr>
			</table>
		</div>
		<div id="paymenterror" style="display:none;">
																			<p class="red_note">We are unable to process your deposit, can you please provide an alternative method of payment.
																				<input name="verificationcc" type="radio" value="1" onclick="global_checksetting(this,'paymenterror_yes','paymenterror_no','','')">Yes
																				<input name="verificationcc" type="radio" value="2" onclick="global_checksetting(this,'paymenterror_yes','paymenterror_no','','')">No
																			</p>

																				<div id="paymenterror_yes" style="display:none;">
																					<p class="rep_note">NOTE TO TSR: Enter in new payment information above and click on Save and Continue button</p>
																				</div>

																				<div id="paymenterror_no" style="display:none;">
																					<p class="red_note">Unfortunately without another form of payment I will not be able to process your enrollment. Is there anything else I can help you with today?
																					<input name="verificationach_no" type="radio" value="1" onclick="global_checksetting(this,'paymenterror_no_yes','paymenterror_no_no','','')">Yes
																					<input name="verificationach_no" type="radio" value="2" onclick="global_checksetting(this,'paymenterror_no_yes','paymenterror_no_no','disp','35')">No</p>

																					<div id="paymenterror_no_yes" style="display:none;">
																						<p class="rep_note">If Customer has Questions GO TO FAQS</p>
																					</div>

																					<div id="paymenterror_no_no" style="display:none;">
																						<p class="rep_note">Payment Info was Decline</p>
																					</div>

																				</div>
		</div>
		<div>
		<table width="100%" align="left" border="0" cellspacing="0" cellpadding="5" class="admintable">
	  		<tr>
				<td>

					<input type="button" class="ib_button" name="btn_continue" id="btn_continue" value="Save and Continue" onclick="sendACH()" >
					<input type="button" class="ib_button" name="btn_save" id="btn_save" value="Save" onclick="sendACH()" >
					<input type="button" class="ib_button" name="btn_log_dispo" id="btn_log_dispo" value="Log Dispo" onclick="logDispo(this)" disabled>
				</td>
			</tr>
		</table>
		</div>

</div>
<div id="depositcc" style="display:none;">

<table class="admintable" style="clear:both" border="0" cellspacing="0" cellpadding="0" with="100%">
			<tr>
				<td colspan="2">May I please have the Credit Card Account Information?
				<input name="depositcc" type="radio" value="1" onclick="global_checksetting(this,'depositcc_yes','depositcc_no','','')">Yes
				<input name="depositcc" type="radio" value="2" onclick="global_checksetting(this,'depositcc_yes','depositcc_no','disp','22')">No</td>
			</tr>
</table>
<div id="depositcc_yes" style="display:none;">
			<table class="admintable" style="clear:both" border="0" cellspacing="0" cellpadding="0" style="width:100%">
				<tr>
					<td class="formdata" colspan="2">What type of credit card will you be using?<font color="red">*</font>PLEASE NOTE THAT THE CARD MUST BE IN YOUR NAME OTHERWISE WE WILL NEED TO OBTAIN AUTHORIZATION FROM THE CARD HOLDER.
					</td>
				</tr>
				<tr>
					<td bgcolor="#efefef" class="formdata" colspan="2">
					<select name="cardtype" id="cardtype" onchange="setCVV(this)" >
						<option value="">Select Card Type</option>
					<?php
						$cards = getList('ccards',' where active=1');
						while($card = mysql_fetch_object($cards))
						{
							echo '<option value="'.$card->name.'">'.$card->name.'</option>';
						}
					?>
					</select>
					</td>
				</tr>
				<tr>
					<td class="formdata" colspan="2">Mr/Ms. <?=$Session_Service_lname;?> Please provide me with the first name as it appears on the card?</td>
				</tr>
				<tr>
					<td bgcolor="#efefef" class="formdata" width="30%">First Name:<font color="red">*</font> </td><td bgcolor="#efefef"  width="70%">&nbsp;<input type="text" id="ccfname" name="ccfname" />&nbsp;&nbsp;<span class="rep_note">CONFIRM BACK TO THE CALLER</span></td>
				</tr>
				<tr>
					<td class="formdata" colspan="2">Please provide me with the Last Name on Card?</td>
				</tr>
				<tr>
					<td bgcolor="#efefef" class="formdata" width="30%">Last Name:<font color="red">*</font> </td><td bgcolor="#efefef" width="70%">&nbsp;<input type="text" id="cclname" name="cclname" />&nbsp;&nbsp;<span  class="rep_note">CONFIRM BACK TO THE CALLER</span></td>
				</tr>
				<tr>
									<td colspan="2">
											<div id="verificationcc_error" style="display:none;">
																					<div>
																						<p class='rep_note'>
																										NOTE TO TSR: The name provided does not match the person you are speaking with. Please Read the following:
																						<p>
																						<span class='red_note'>Can I please speak to the card holder?
																								<input name="verificationccerr1" type="radio" value="1" onclick="global_checksetting(this,'verificationcc_yes','verificationcc_no','','')">Yes
																								<input name="verificationccerr1" type="radio" value="2" onclick="global_checksetting(this,'verificationcc_yes','verificationcc_no','','')">No</span>

																								<div id="verificationcc_yes" style="display:none;">
																											<p class="red_note">Can you please confirm that I am speaking to &nbsp;<span id='ccname1'></span>?
																														<input name="verificationach1_yes" type="radio" value="1" onclick="global_checksetting(this,'verificationcc1_yes','verificationcc1_no','','')">Yes
																														<input name="verificationach1_yes" type="radio" value="2" onclick="global_checksetting(this,'verificationcc1_yes','verificationcc1_no','','')">No
																											</p>
																										<div id="verificationcc1_yes" style="display:none;">
																											<p class="red_note">Do you authorize the use of your <span id='card_type'></span> &nbsp;&nbsp; <span id='card_number'></span>  &nbsp; to pay the $<?=$Session_depositamount;?> deposit on the Energy Plus Account?
																												<input name="verificationcardcc2" type="radio" value="1" onclick="global_checksetting(this,'verificationcardcc2_yes','verificationcardcc2_no','','')">Yes
																												<input name="verificationcardcc2" type="radio" value="2" onclick="global_checksetting(this,'verificationcardcc2_yes','verificationcardcc2_no','','')">No
																											</p>
																											<div id="verificationcardcc2_yes" style="display:none;">
																												<span class="red_note">
																													Thank you. Please put <?=$session_name;?> back on the phone so we can complete the enrollment.
																												</span>
																												<span class="rep_note"> NOTE TO TSR: CLICK SAVE AND CONTINUE</span>
																											</div>
																											<div id="verificationcardcc2_no" style="display:none;">
																												<p class="rep_note">NOTE TO TSR: PLEASE ASK FOR <?=$session_name;?> TO COME BACK ON THE PHONE THEN READ THE FOLLOWING:<p><span class="red_note"> Unfortunately I will not be able to process the payment or complete the enrollment. Is there any thing else I can help you with today
																													<input name="verificationcc4_no" type="radio" value="1" onclick="global_checksetting(this,'verificationcc4_no_yes','verificationcc4_no_no','','')">Yes
																													<input name="verificationcc4_no" type="radio" value="2" onclick="global_checksetting(this,'verificationcc4_no_yes','verificationcc4_no_no','disp','35')">No</span>
																												 <div id="verificationcc4_no_yes" style="display:none;">
																												 			<p class="rep_note">If Customer has Questions GO TO FAQS</p>
																												 </div>
																												 <div id="verificationcc4_no_no" style="display:none;">
																												 			<p class="rep_note">Payment Info was Decline</p>
																												</div>
																											</div>
																										</div>
																										<div id="verificationcc1_no" style="display:none;">
																											<p  class="red_note">Unfortunately I will not be able to process the payment or complete the enrollment. Is there anything else I can help you with today?
																											<input name="verificationccerr_no" type="radio" value="1" onclick="global_checksetting(this,'verificationcc_no_yes','verificationcc_no_no','','')">Yes
																											<input name="verificationccerr_no" type="radio" value="2" onclick="global_checksetting(this,'verificationcc_no_yes','verificationcc_no_no','disp','35')">No</p>

																											<div id="verificationcc_no_yes" style="display:none;">
																													<p class="rep_note">If Customer has Questions GO TO FAQS</p>
																											</div>
																											<div id="verificationcc_no_no" style="display:none;">
																													<p class="rep_note">Payment Info was Decline</p>
																											</div>
																										</div>
																								</div>
																								<div id="verificationcc_no" style="display:none;">
																										<p class="red_note">Unfortunately I will not be able to process the payment or complete the enrollment. Is there anything else I can help you with the today?
																											<input name="verificationcc3_no" type="radio" value="1" onclick="global_checksetting(this,'verificationcc3_no_yes','verificationcc3_no_no','','')">Yes
																											<input name="verificationcc3_no" type="radio" value="2" onclick="global_checksetting(this,'verificationcc3_no_yes','verificationcc3_no_no','disp','35')">No</p>
																											<div id="verificationcc3_no_yes" style="height:30px;display:none;">
																													<p class="rep_note">If Customer has Questions GO TO FAQS</p>
																											</div>
																											<div id="verificationcc3_no_no" style="display:none;">
																													<p class="rep_note">Payment Info was Decline</p>
																											</div>
																								</div>
																	</div>
									</td>
				</tr>
				<tr>
									<td class="formdata" colspan="2">Now may I please have the card number?</td>
				</tr>
				<tr>
					<td bgcolor="#efefef" class="formdata" width="30%">Card Number:<font color="red">*</font> </td><td bgcolor="#efefef"  width="70%">&nbsp;<input type="text" id="ccnumber" name="ccnumber" maxlength="16" />&nbsp;&nbsp;<span  class="rep_note">CONFIRM BACK TO THE CALLER</span></td>
				</tr>
				<tr>
					<td class="formdata" colspan="2">On the back of the card is a Security code, could you please read the 3 or 4 digit code to me?</td>
				</tr>
				<tr>
					<td bgcolor="#efefef" class="formdata" width="30%">Security Code:<font color="red">*</font> </td><td bgcolor="#efefef" width="70%">&nbsp;<input name="cvv" id="cvv" type="text" size="6"/><a href="javascript:void(0)" onClick="Popup.showModal('cardccv');return false;"><img src="../images/icon-question.gif" alt="?" border="0" /></a></td>
				</tr>
				<tr>
					<td class="formdata" colspan="2">Card Expiration Month and the Expiration Date?<font color="red">*</font></td>
				</tr>
				<tr>
					<td bgcolor="#efefef"  colspan="2">
						<select name="expmm" id="expmm" >
							<option value="" selected="selected">Choose Month...</option>
							<option value="1">January</option>
							<option value="2">February</option>
							<option value="3">March</option>
							<option value="4">April</option>
							<option value="5">May</option>
							<option value="6">June</option>
							<option value="7">July</option>
							<option value="8">August</option>
							<option value="9">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
						</select>
						<?php
							$yr = strftime("%Y");
						?>

					<select name="expyy" id="expyy" >
						<option value="" selected="selected">Choose Year...</option>
						<?php
							for($yr=strftime("%Y");$yr<strftime("%Y")+10;$yr++)
							{
								echo '<option value="'.$yr.'">'.$yr.'</option>';
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="formdata" colspan="2">The last thing I need is the credit card mailing address starting with the street address?</td>
			</tr>
			<tr>
				<td bgcolor="#efefef" class="formdata" width="30%">Street Address:<font color="red">*</font> </td><td bgcolor="#efefef" class="formdata" width="70%"><input type="text" id="ccmailadd1"  name="ccmailadd1" /></td>
			</tr>
			<tr>
				<td bgcolor="#efefef" class="formdata" width="30%">APT #/Suite #:  </td><td bgcolor="#efefef" class="formdata" width="70%"><input type="text" id="ccmailadd2" name="ccmailadd2" /></td>
			</tr>
			<tr>
				<td bgcolor="#efefef" class="formdata" width="30%">City:<font color="red">*</font></td><td bgcolor="#efefef" class="formdata" width="70%"><input type="text" id="ccmailaddcity" name="ccmailaddcity" />&nbsp;&nbsp;State:<font color="red">*</font>&nbsp;&nbsp;<input name="ccmailaddstate" type="text" id="ccmailaddstate"  size="4" maxlength="2" />&nbsp;&nbsp;Zip:<font color="red">*</font>&nbsp;&nbsp;<input name="ccmailaddzip" type="text" id="ccmailaddzip" size="6" /></td>
			</tr>
			<tr>
				<td bgcolor="#efefef" class="formdata" colspan="2"><p class="rep_note">CONFIRM BACK TO THE CALLER</p></td>
			</tr>
			<tr>
				<td bgcolor="#efefef"  class="formdata" width="30%">Total Payment Amount:</td><td bgcolor="#efefef" class="formdata" width="70%"><span id="showamtcc">$ <?=$Session_depositamount;?></span></td>
			</tr>
		</table>
</div>

<div id="depositcc_no" style="display:none;">
		<table>
			<tr>
				<td colspan="2">
									<div style="float: right; padding-top: 8px;">
												<a class="tabtext" href="javascript: void();" onclick="tsrnote_reb('open','cc')">+Open</a> /
												<a class="tabtext" href="javascript: void();" onclick="tsrnote_reb('close','cc')">-Close</a>
									</div>
									<div style="float: right; padding-top: 8px;" class="rep_note">REBUTTALS - IF CUSTOMER  WILL NOT PROVIDE DEPOSIT INFORMATION.
									</div>
									<div id='tsrnote_ccreb' style="display:none;">
											<BR><p class="rep_note">TSR NOTE: If a customer is extremely upset or irate about the deposit and you have used the rebuttals above without success, please tell the customer you will have a manger look into this and get back to them. Capture name, address, and phone number and Escalate to your Supervisor. Disposition the call #39.</p>
									</div>
									<div id='tsrnote_ccreb1' style="display:none;" style="float: right; padding-top: 8px;" class="rep_note">IF CUSTOMER STILL WILL NOT PROVIDE DEPOSIT INFORMATION.</div>

				</td>
			</tr>
			<tr>
				<td colspan="2">

					Unfortunately we need to have credit card number to proceed with  your enrollment.  Once you have this information, please call us back at  <?=$_SESSION['enroll_tel'];?> <?if($Session_Affinity!=1){echo "or you can complete your enrollment on-line at ".$Session_web_addr;}?>.  Is there anything else I can do for you today?
					&nbsp;<input name="ccquestions" id="ccquestions"  type="radio" value="1" onclick="global_checksetting(this,'ccquestions_yes','ccquestions_no','cust','')">Yes
					<input name="ccquestions" id="ccquestions" type="radio" value="2" onclick="global_checksetting(this,'ccquestions_yes','ccquestions_no','disp','22')">No
				</td>
			</tr>
			<tr>
          			<td colspan="2">
						<div id='ccquestions_yes' style="display:none;"><p class="rep_note">IF CUSTOMER HAS QUESTIONS GO TO FAQS</p></div>
          				<div id='ccquestions_no' style="display:none;"><p class="rep_note">NOTE TO TSR: CALL WAS AUTO DISPO'D #22: CALLER REFUSED TO PROVIDE CREDIT CARD ACCT INFO</p></div>
				</td>
			</tr>
		</table>
</div>
<div id="paymenterrorcc" style="display:none;">
					<p class="red_note">
						We are unable to process your deposit, can you please provide an alternative method of payment.
						<input name="verificationcc" type="radio" value="1" onclick="global_checksetting(this,'paymenterrorcc_yes','paymenterrorcc_no','','')">Yes
						<input name="verificationcc" type="radio" value="2" onclick="global_checksetting(this,'paymenterrorcc_yes','paymenterrorcc_no','','')">No
					</p>
					<div id="paymenterrorcc_yes" style="display:none;">
						<p class="rep_note">NOTE TO TSR: Enter in new payment information above and click on Save and Continue button</p>
					</div>

					<div id="paymenterrorcc_no" style="display:none;">
						 <p class="red_note">
							Unfortunately without another form of payment I will not be able to process your enrollment. Is there anything else I can help you with today?
							<input name="verificationcc_no" type="radio" value="1" onclick="global_checksetting(this,'paymenterrorcc_no_yes','paymenterrorcc_no_no','','')">Yes
							<input name="verificationcc_no" type="radio" value="2" onclick="global_checksetting(this,'paymenterrorcc_no_yes','paymenterrorcc_no_no','disp','35')">No
						</p>
						<div id="paymenterrorcc_no_yes" style="display:none;">
							<p class="rep_note">If Customer has Questions GO TO FAQS</p>
						</div>

						<div id="paymenterrorcc_no_no" style="display:none;">
							<p class="rep_note">Payment Info was Decline</p>
						</div>
					</div>
</div>
<div>
		<table width="100%" align="left" border="0" cellspacing="0" cellpadding="5" class="admintable">
			  		<tr>
						<td>
							<input type="button" class="ib_button" name="btn_continue1" id="btn_continue1" value="Save and Continue" onclick="sendDeposit()" >
							<input type="button" class="ib_button" name="btn_save1" id="btn_save1" value="Save" onclick="sendDeposit(this)" >
							<input type="button" class="ib_button" name="btn_log_dispo1" id="btn_log_dispo1" value="Log Dispo" onclick="logDispo(this)" disabled>
						</td>
					</tr>
					<tr>
						<td>
							<input type='hidden' name='submitaction' id='submitaction'>

													<input type='hidden' name='depamt' id='depamt' value='<?=$Session_depositamount;?>'>
													<input type='hidden' name='regid' id='regid' value="<?=$regid;?>">
													<input type='hidden' name='next_url' id='next_url' value="select_offer.php" >
													<input type='hidden' name='submitaction' id='submitaction'>
													<input type='hidden' name='paycount' id='paycount' >
													<input type='hidden' name='authach' id='authach'>
													<input type='hidden' name='authcc' id='authcc'>
													<input type='hidden' name='customer_fname' id='customer_fname' value="<?=$Session_Service_fname;?>">
													<input type='hidden' name='customer_lname' id='customer_lname' value="<?=$Session_Service_lname;?>">
						</td>
					</tr>
		</table>
</div>


</div>
<?
}
else
{
	echo "This Functionality is avaiable for only Texas";
}
?>
</form>
</div><!--  end main -->
<?php
        require_once 'includes/statusbar.php';
?>
</div> <!--  end container -->
<?php
        require_once 'includes/footer.php';
?>

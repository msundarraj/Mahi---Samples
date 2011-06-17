<?php


//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Look Up Module//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////

session_start();
$newlink = 'select_utility_tx.php';
require_once("../newcentral_functions.php");
require_once('../jlnusoap2/nusoapadd.php');
$client = new soapclient($lookup_url3);
$client->soap_defencoding = 'UTF-8';
$url = $lookup_url4;
$namespace = 'https://services.ista-billing.com/';
$busres = (int)$_GET['busres'];

//echo $busres;
$err = $client->getError();
if ($err)
{
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}
//else echo 'CONNECTION OK'."\n";
$add1 = $_GET['add1'];
$add2 = $_GET['add2'];
$city = $_GET['city'];
$state = 'TX';
$zip5 = $_GET['zip'];
$zip4 = '';
$param = array('add1' => $add1,'add2' => $add2,'city' => $city,'state' => $state,'zip5' => $zip5,'zip4' => $zip4);
$result = $client->call('LookupAddress', array('parameters' => $param),$namespace,$url,'10204049645659090');
// Check for a fault
if ($client->fault)
{
	echo "<table><tr><td style='width:500px; border: 1px solid #900;'><span class='rep_note'>We apologize but we are currently experiencing technical difficulties. Please try again later or call 1-877-770-3373, Monday - Friday, 8:00 a.m. - 4:00 p.m. CT for assistance.</span></td></tr></table>";
	echo '<pre><h2>Fault</h2></pre>';
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
		//echo 'RESPONSE -- '.$client->response;
		//echo 'REQUEST -- '.$client->request;
	}
	else
	{
		//print_r($result['ESIIDInfoArray']);
		$resultcount =  count($result['ESIIDInfoArray']);
		if( $resultcount == 0 )
		{
?>
<div style="padding:10px;border:1px solid #900">
<p>
			<span class="head1">Match Not Found</span><br />
		</p>
		<p class="rep_note">NOTE TO TSR: Please try entering your address or ESI ID again.</p></div>
		<input type="button" name="Back" id="Back" value=" Back " onclick="window.location = '<?=$newlink;?>';">

<?php
		}
		else if( $resultcount == 1 )
		{
			extract($result['ESIIDInfoArray']['ESIIDInfo']);
			$utilrec = getRecord('utility2','tdspduns',$TDSPDuns);
			if( $utilrec )
			{
				if( $PremiseType == 'Residential' )
				{
					$revclass = '1';
					$amt1 = 'Less than $250 per month';
					$amt2 = 'Between $250 - $750 per month';
					$amt3 = 'Greater than $750 per month';
				}
				else if( $PremiseType == 'Small Non-Residential' )
				{
					$revclass = '2';
					$amt1 = 'Less than $250';
					$amt2 = 'Between $250 and $750';
					$amt3 = 'Between $750 and $1000';
					$amt4 = 'Greater than $1000';
				}
				else if( $PremiseType == 'Large Non-Residential' )
				{
					$revclass = '3';
					$amt1 = 'Less than $250';
					$amt2 = 'Between $250 and $750';
					$amt3 = 'Between $750 and $1000';
					$amt4 = 'Greater than $1000';
				}
				else
				{
					$revclass = '1';
					$amt1 = 'Less than $250 per month';
					$amt2 = 'Between $250 - $750 per month';
					$amt3 = 'Greater than $750 per month';
				}
// if($revclass==$busres)
if ( (($revclass == 2 || $revclass == 3) && $busres == 1 ) || ( $revclass == 1 && $busres == 0 )  )
{

?>
<table class="esoresultdiv" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
		<p>
			<span class="head1">Match Found</span><br />We have found a service address that matches the information you provided.
		</p>
		<strong><?php echo $Address1.' '.$Address2;?><br />
		<?php echo $City.', '.$State.' '.$Zip5.' '.$Zip4;?><br />
		ESI ID: <?php echo $ESIID;?></strong></p>

		<input type="hidden" name="h_esiid" id="h_esiid" value="<?php echo $ESIID;?>" />
		<input type="hidden" name="revclass" id="revclass" value="<?php echo $revclass;?>" />
		<input type="hidden" name="tdspduns" id="tdspduns" value="<?php echo $TDSPDuns;?>" />
		<input type="hidden" name="station" id="station" value="<?php echo $StationCode;?>" />
		<input type="hidden" name="auto_addr1" id="auto_addr1" value="<?php echo $Address1;?>" />
		<input type="hidden" name="auto_addr2" id="auto_addr2" value="<?php echo $Address2;?>" />
		<input type="hidden" name="auto_city" id="auto_city" value="<?php echo $City;?>" />
		<input type="hidden" name="auto_state" id="auto_state" value="<?php echo $State;?>" />
		<input type="hidden" name="auto_zip" id="auto_zip" value="<?php echo $Zip5;?>" />
		<input type="hidden" name="auto_zip4" id="auto_zip4" value="<?php echo $Zip4;?>" />
		<span id="errmsg2" style="color:#990000;"></span>
		<br clear="right" />
			This address is also my billing address:<span class="style1">*</span>
			<input style="vertical-align:text-bottom;" type="radio" name="billingsame" id="billingsame" value="Yes" onclick="checkSettingBS(this)" Checked /> Yes
			<input style="vertical-align:text-bottom;" type="radio" name="billingsame" id="billingsame" value="No" onclick="checkSettingBS(this)" /> No
			<br /><br />
			My average electricity bill is:<span class="style1">*</span>
			<select name="kWh" id="kWh">
				<option value="0">Please Select...</option>
				<option value="1"><?php echo $amt1;?></option>
				<option value="2"><?php echo $amt2;?></option>
				<option value="3"><?php echo $amt3;?></option>
				<?php if($revclass > 1) echo '<option value="4">'.$amt4.'</option>';?>
			</select>
<div id="hide1" style="display:none">
			A resident at this address requires life support:<span class="style1">*</span>
			<input style="vertical-align:text-bottom;" type="radio" name="lifesupport1" id="lifesupport1" value="Yes" onclick="checkSetting1(this)" /> Yes
			<input style="vertical-align:text-bottom;" type="radio" name="lifesupport1" id="lifesupport1" value="No" onclick="checkSetting1(this)" /> No
			&nbsp;
			<a href="javascript:void(0)" onmouseover="ddrivetip('If an interruption or suspension of your electric service will create a dangerous or life-threatening condition, please select &quot;Yes&quot;.  To qualify, you must complete the Critical Care Eligibility Determination Form annually and return it to your TDSP. Qualification as a critical care customer does not relieve you of your obligation to pay for the electricity service that you receive.')"; onmouseout="hideddrivetip()">
				<img src="images/icon-question.gif" border="0" />
			</a>
</div>
		</p>
		<div id="lsa" style="display:none;border:3px solid #c00;padding:10px;color:#c00;width:400px;">
			Please send proof to 888-###-####.
		</div>
	</td>
</tr>
</table>
		<p style="text-align: left;">
			<input type="button" name="Continue" id="Continue" value=" Continue " onclick="saveFirst1()">&nbsp;&nbsp;
			<input type="button" name="Back" id="Back" value=" Back " onclick="window.location = '<?=$newlink;?>'; ">
		</p>

<?
}
else
{
?>
<table class="esoresultdiv" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td><span class="head1"></span><br />
		<input type="hidden" name="revclass" id="revclass" value="<?php echo $revclass;?>" />
<div id="notresidential" style="display:none;margin-top:20px;margin-bottom:30px;border:none;padding:0px;width:500px;">
                <table>
                <tr>
			<td style="width: 74px;">
					<input type="button" name="btn_back" id="btn_back" value=" Back test2 " onclick="window.location = '<?=$newlink;?>'; ">

			</td>
			<td style="width:500px; border: 1px solid #900; color:#900000;">
				<p class="rep_note">NOTE TO TSR: The Selected ESI ID is Small Non-Residential. Please go back and try to enter your service address or ESI ID again. </p>
			</td>
		</tr>
		</table>
	      </div>
		<div id="notbusiness" style="display:none;margin-top:20px;margin-bottom:30px;border:none;padding:0px;width:500px;">
                <table>
                <tr>
			<td style="width: 74px;">
					<input type="button" name="btn_back" id="btn_back" value=" Back " onclick="window.location = '<?=$newlink;?>'; ">
			</td>
			<!-- <td style="width:500px; border: 1px solid #900; color:#900000;"><FONT COLOR='#900000'> -->
			<td style="width:500px; border: 1px solid #900; color:#900000;">
				<p class="rep_note">NOTE TO TSR: The Selected ESI ID is Residential. Please go back and try to enter your service address or ESI ID again.</p>
			</td>
		</tr>
		</table>
	      </div>
	      <div id="largebusiness" style="display:none;margin-top:20px;margin-bottom:30px;border:none;padding:0px;color:#333;width:500px;">
                <table>
                <tr>
			<td style="width: 74px;">
					<a href="<?php echo $newlink;?>"><input type="button" name="btn_back" id="btn_back" value=" Back "></a>
			</td>
			<td style="width:500px; border: 1px solid #900;">
				<p class="rep_note">NOTE TO TSR: We apologize, but we are not able to service Large Commercial accounts.</p>
			</td>
		</tr>
		</table>
	      </div>
 	  </td>
    </tr>
</table>
<?php
}
       //                 echo 'success';
			}
			else
			{   // OUT OF AREA
 				echo '<div style="padding:10px;border:1px solid #900"><p><span class="rep_note">Address Not in Our Service Area</span><br /></p> <p class="rep_note">We apologize but unfortunately it appears your address is located outside of our service area. If you would like to discuss with one of our representatives to confirm, please don\'t hesitate to contact us at 1-877-710-5550.</p></div>';
			}
		}
		else
		{
				// multi
//			echo 'Multiple Results<br />';
//			print_r($result['ESIIDInfoArray']);
?>
<input type="hidden" name="auto_addr1" id="auto_addr1" value="" />
<input type="hidden" name="auto_addr2" id="auto_addr2" value="" />
<input type="hidden" name="auto_city" id="auto_city" value="" />
<input type="hidden" name="auto_state" id="auto_state" value="" />
<input type="hidden" name="auto_zip" id="auto_zip" value="" />
<input type="hidden" name="auto_zip4" id="auto_zip4" value="" />
<input type="hidden" name="tdspduns" id="tdspduns" value="" />
<input type="hidden" name="station" id="station" value="" />
<div class="txnoticebox" id="addrchoice">
		<!--<td style="width: 80px;"><img src="images/icon-caution.jpg" alt="Help" /></td>-->
			<div id="choices">
				<p>
				<br /><span class="head1">Multiple Matches Found</span><br />
				Please select your correct service address from the listings below:&nbsp;
	<!--<a href="javascript:void(0)" onmouseover="ddrivetip('Please select your correct service address from the listings below:')"; onmouseout="hideddrivetip()">
					<img src="images/icon-question.gif" border="0" />
				</a>-->
				</p>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
<?php
			$j = 0;
			for( $i = 0; $i < $resultcount; $i++ )
			{
				extract($result['ESIIDInfoArray'][$i]);
				if( $PremiseType == 'Residential' ) $revclass = 1;
				if( $PremiseType == 'Small Non-Residential' ) $revclass = 2;
				if( $PremiseType == 'Large Non-Residential' ) $revclass = 3;

?>
					<td width="20" style="padding: 5px;">
						<input type="radio" name="addopt" id="r<?php echo $i;?>" value="radio" onclick="checkSetting3(this,<?=$ESIID.",".$revclass.",".$busres;?>)"/>
					</td>
					<td class="disclaimer" style="padding: 5px;">
						<input type="hidden" name="ar<?=$i;?>" id="ar<?=$i;?>" value="<?=$Address1;?>" />
						<input type="hidden" name="cr<?=$i;?>" id="cr<?=$i;?>" value="<?=$City;?>" />
						<input type="hidden" name="sr<?=$i;?>" id="sr<?=$i;?>" value="<?=$State;?>" />
						<input type="hidden" name="zr<?=$i;?>" id="zr<?=$i;?>" value="<?=$Zip5;?>" />
						<input type="hidden" name="yr<?=$i;?>" id="yr<?=$i;?>" value="<?=$Zip4;?>" />
						<input type="hidden" name="er<?=$i;?>" id="er<?=$i;?>" value="<?=$ESIID;?>" />
						<input type="hidden" name="dr<?=$i;?>" id="dr<?=$i;?>" value="<?=$TDSPDuns;?>" />
						<input type="hidden" name="tr<?=$i;?>" id="tr<?=$i;?>" value="<?=$StationCode;?>" />
						<input type="hidden" name="rr<?=$i;?>" id="rr<?=$i;?>" value="<?=$revclass;?>" />
						<?php echo $Address1;?><br />
             					<?php echo $City.', '.$State.' '.$Zip5.' '.$Zip4;?><br />
							<span style="font-weight: bold;">ESI ID:</span> <?php echo $ESIID;?>
					</td>
<?php
				if($j % 2) echo '</tr><tr>';
				$j++;
			}
?>
				</tr>
				<tr>
				  <td colspan="4" class="disclaimer" style="padding: 5px;">
					<p class="rep_note">NOTE TO TSR:  IF YOU CAN NOT LOCATE THE ESI ID/ADDRESS ON THE FIRST ATTEMPT PLEASE GO BACK TO THE UTILITY TAB AND RETRY.  IF YOU CAN'T FIND THE ADDRESS/ESI ID SELECT "My address/ESID is not listed below"</p>&nbsp;</td>
				 </tr>
				<tr>
					<td style="padding: 5px; vertical-align:text-bottom;"><input type="radio" name="nf" id="nf" value="nf" onclick="checkSetting_nf(this)" />	</td>
					<td colspan="3" class="disclaimer" style="padding: 5px; vertical-align:text-bottom;"> My address/ESI ID is not listed above.	</td>
				</tr>
				</table>
		  </div>
		<div id="notresidential" style="display:none;margin-top:20px;margin-bottom:30px;border:none;padding:0px;width:500px;">
                <table>
                <tr>
				<td style="width:500px; border: 1px solid #900;">
					<p class="rep_note">
						NOTE TO TSR: The Selected ESI ID is Small Non-Residential. Please go back and try to enter 
						your service address or ESI ID again.
					</p>
				</td>
			</tr>
			<tr>
				<td align="left">
					<input type="button" name="btn_back" id="btn_back" value=" Back " onclick="window.location = '<?=$newlink;?>'; ">
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_logdispo8" id="btn_logdispo8" value=" Log Dispo " onclick="appform_logDispo('disp',8)">
				</td>
			</tr>

		</table>
	      </div>
		<!-- <div id="notbusiness" style="display:none;margin-top:20px;margin-bottom:30px;border:none;padding:0px;color:#333;width:500px;"> -->
		<div id="notbusiness" style="display:none;margin-top:20px;margin-bottom:30px;border:none;padding:0px;width:800px;">
                <table>
                	<tr>
				<td>
					<p class="rep_note">NOTE TO TSR : The Selected ESI ID is Residential. Please go back and try to 
					enter your service address or ESI ID again.</p>
				</td>
			</tr>
			<tr>
				<td align="left">
					<input type="button" name="btn_back" id="btn_back" value=" Back " onclick="window.location = '<?=$newlink;?>'; ">
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_logdispo8" id="btn_logdispo8" value=" Log Dispo " onclick="appform_logDispo(this)">
				</td>
			</tr>
		</table>
	      </div>
	      <div id="largebusiness" style="display:none;margin-top:20px;margin-bottom:30px;border:none;padding:0px;width:800px;">
                <table>
                	<tr>
				<td align="left">
					<p class="rep_note">NOTE TO TSR: We apologize, but we are not able to service Large Commercial accounts.</p>
				</td>
			</tr>
			<tr>
				<td align="left">
					<input type="button" name="btn_back" id="btn_back" value=" Back " onclick="window.location = '<?=$newlink;?>'; ">
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_logdispo8" id="btn_logdispo8" value=" Log Dispo " onclick="appform_logDispo('disp',8)">
				</td>
			</tr>
		</table>
	      </div>
<!-- choices -->
		<div id="notlisted" style="display:none;margin-top:20px;margin-bottom:30px;border:none;padding:0px;width:800px;">
              <table width="100%" border="0">
               	<tr>
				<td colspan="2">
					<p class="rep_note">
						NOTE TO TSR: Unfortunately we cannot locate your account information and therefore I will not be able to enroll 
						you with Energyplus at this time. You can locate your ESI ID on your electric bill and then give us a call back 
						at <?=$_SESSION['enroll_tel'];?> or you can complete your enrollment on-line at <?=$_SESSION['web_addr'];?>. 
						Auto Dispo #8: Account # DID NOT MATCH DATA BASE.
					</p>
				</td>
			</tr>
			<tr>
				<td align="left">
					<input type="button" name="btn_back" id="btn_back" value=" Back " onclick="window.location = '<?=$newlink;?>'; ">
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_logdispo8" id="btn_logdispo8" value=" Log Dispo " onclick="appform_logDispo('disp',8)">
				</td>
			</tr>
		</table>
	</div> <!-- notlisted -->
	<div id="approveaddress" style="display: none;margin-top:10px; width: 500px;">
		<strong>You have selected:</strong><br /><br />
		<span id="seladd1"></span><br />
		<span id="selcity"></span>,<span id="selstate"></span> <span id="selzip"></span> <span id="selzip4"></span><br />
		ESI ID: <span id="selesi"></span><br /><br />
		<strong><span style="color: #000;">Is this correct?</span></strong>
		<input type="hidden" name="h_esiid" id="h_esiid" value="" />
		<input type="hidden" name="revclass" id="revclass" value="" />
		<input type="radio" name="addressok" id="radio7" value="Yes" onclick="checkSetting4(this)" /> Yes
		<input type="radio" name="addressok" id="radio7" value="No" onclick="checkSetting4(this)" /> No
			<div id="addresscontinue" style="display: none;">

				<span id="errmsg2" style="color:#990000;"></span><br style="clear: right;" />
				This address is also my billing address:<span class="style1">*</span>
				<input type="radio" name="billingsame" id="billingsame" value="Yes" onclick="checkSettingBS(this)" Checked />Yes
				<input type="radio" name="billingsame" id="billingsame" value="No" onclick="checkSettingBS(this)" />No
				<br />
				<BR>

				My average electricity bill is:<span class="style1">*</span>
				<select name="kWh" id="kWh">
					<option value="0">Please Select...</option>
					<option value="1">Less than $250 per month</option>
					<option value="2">Between $250 - $750 per month</option>
					<option value="3">Greater than $750 per month</option>
				</select><br />
				<div id="hide3" style="display:none">
					A resident at this address requires life support:
					<input type="radio" name="lifesupport1" id="radio7" value="Yes" onclick="checkSetting2(this)" /> Yes
					<input type="radio" name="lifesupport1" id="radio8" value="No" onclick="checkSetting2(this)" /> No&nbsp;
					<a href="javascript:void(0)" onmouseover="ddrivetip('If an interruption or suspension of your electric service will create a dangerous or life-threatening condition, please select &quot;Yes&quot;.  To qualify, you must complete the Critical Care Eligibility Determination Form annually and return it to your TDSP. Qualification as a critical care customer does not relieve you of your obligation to pay for the electricity service that you receive.')"; onmouseout="hideddrivetip()">
						<img src="images/icon-question.gif" border="0" />
					</a>
				</div>
				<div id="lifesupportalert2" style="display: none; border: 3px solid #c00; padding: 10px; color: #c00; width: 400px;">
					Please send proof to 888-###-####.
				</div> <!-- lifesupportalert2 -->
				<BR>
				<BR>
				<p style="text-align:left;">
					<input type="button" name="Continue" id="Continue" value=" Continue " onclick="saveFirst1()">&nbsp;&nbsp;
					<input type="button" name="Back" id="Back" value=" Back " onclick="window.location = '<?=$newlink;?>'; ">
					
				</p>
			</div>  <!-- addresscontinue -->
		</div> <!-- approveaddress -->
	</div>
<?php
		}
	}
}
?>

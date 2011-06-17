<?php


//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - PA Search//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////

require_once 'includes/main.php';
require_once 'EP/Model/StateMapper.php';
require_once 'EP/Model/PartnerMapper.php';
require_once 'EP/HTML/Form/Widget/RadioBool.php';
require_once 'includes/header.php';


$register = '';
if ( isset( $_SESSION['register']))
{
	$register = $_SESSION['register'];
}


if(isset($_SESSION['tabs']['state']['fname']))
{
	$Session_Service_fname = $_SESSION['tabs']['state']['fname'];
}
else
{
	$Session_Service_fname = $Session_first_name;
}


$Session_Service_lname = null;
if(isset($_SESSION['tabs']['state']['lname']))
{
	$Session_Service_lname = $_SESSION['tabs']['state']['lname'];
}


//print_r($_POST);
//echo $_SESSION['tabs']['customer']['Service_Address1'];


$strec = getRecord('states','id',$_SESSION['tabs']['state']['state']);
$_SESSION['st_abbrev'] = $strec->abbrev;

if($_GET['util']!='')
{
	$utility_rec = getRecord('utility2','code',$_GET['util']);
	$util = $utility_rec->abbrev;
}
else
{
	echo "<span class='rep_note'>NOTE TO TSR: Please Choose the Utility</span>";
	exit;
}

?>
   <link rel="stylesheet" type="text/css"  href="/js/ext/resources/css/ext-all.css" />
   <script src="/js/ext/adapter/ext/ext-base.js"></script>
   <script src="/js/ext/ext-all-debug.js" ></script>
<script type="text/javascript">
function load_globalsetting(selelm)
{
		document.getElementById('section1a').style.display='';
		document.getElementById('section1b').style.display='none';
		document.getElementById('section4c').style.display='';
}

Ext.onReady(function() {
	$('#results').hide();
	$('#results_tbl_txt').hide();

	Ext.ns('Application');

	var proxy = new Ext.data.HttpProxy({
    	url: '/ajax/fetch_util_accounts.php',
		method: 'post'
	});

	var field = Ext.data.Record.create([
		{name: 'id'},
		{name: 'name1'},
		{name: 'addr1' },
		{name: 'addr2'},
		{name: 'city'},
		{name: 'state' },
		{name: 'zip'},
		{name: 'account'},
		{name: 'rate_class' },
		{name: 'blocked' }
	]);  // end field

	var reader = new Ext.data.JsonReader({idProperty: 'id'}, field );
	Application.store = new Ext.data.JsonStore({
		autoLoad: true,
		// autoSave: true,
		fields: field,
		// data: data,
		proxy: proxy,
		reader: reader,
		root: 'rows',
		storeId: 'myStore',
		id: 'id',
		totalProperty: 'totalCount',
		listeners: {
			exception: function ( proxy, type, action, options, response, args  )
			{
				var msg = 'There has been a problem, please try your search again.';

				// if there's a msg returned in the JSON, use that
				if ( response.raw.msg )
				{
					msg = response.raw.msg;
				}
				alert( msg );
				$('#btn_continue').attr('disabled', '');
			},
			beforeload: function( store, options){
				store.baseParams = {
					'util' : $('#util').val(),
					'name1': $('#first_name').val(),
					'name2': $('#last_name').val(),
					'addr1': $('#Service_Address1').val(),
					'addr2': $('#Service_Address2').val(),
					'city' : $('#Service_City').val(),
					'state': $('#Service_State').val(),
					'zip5': $('#Service_Zip5').val(),
					'account': $('#frm_account').val()
				};
			},

			load: function( st, records, opts) {

				if ( records.length == 0 )
				{
					// Application.grid.hide();
					$('#results').hide();
					$('#results_tbl_txt').hide();
				}
				else
				{
					// Application.grid.show();
					$('#results').show();
					$('#results_tbl_txt').show();
					$('#results_tbl_total_count').text( st.reader.jsonData.totalCount );
				}
				$('#btn_continue').attr('disabled', '');
			}
		}	// end load listener
	}); // end store

	Application.grid = new Ext.grid.GridPanel({
		// renderTo: document.body,
		renderTo: Ext.get("results_tbl"),
		id: 'util_accounts',
		frame: true,
		title: 'UTILITY ACCOUNTS',
		// height: 200,
		stripeRows: true,
		autoHeight: true,
		width: 864,
        viewConfig: {
            forceFit:true
        },

		store: Application.store,
		loadMask: new Ext.LoadMask(Ext.getBody(), {msg:"Loading..."}),
		sm: new Ext.grid.RowSelectionModel({
            singleSelect: true,
            listeners: {
                 rowselect: function(smObj, rowIndex, record) {

					if ( record.data.blocked == true )
					{
						alert( 'This account cannot be saved due to lack of service.');
						return;
					}

					var output = '';
					output += "Are you sure you want to select this account? \n";
					output += "name:         " + record.data.name1 + "\n";
					output += "address:      " + record.data.addr1 + "\n";
					output += "city/st/zip:  " + record.data.city + ',' + record.data.state + ' ' + record.data.zip + "\n";
					output += "account:      " + record.data.account + "\n";
					var req = confirm( output );
					if ( req == true)
						{
						saveAcct( record.data.account );
						}
					else
						{
							smObj.clearSelections();
						}
                }
           }
        }),

		columns: [
			{ header: "NAME",
				dataIndex: 'name1',
				sortable: true,
				width: 200
			},
			{ header: "ADDRESS 1",
				dataIndex: 'addr1',
				sortable: true,
				width: 120
			},
			{ header: "CITY",
				dataIndex: 'city',
				sortable: true
			},
			{ header: "ST",
				dataIndex: 'state',
				sortable: true,
				width: 50
			},
			{ header: "ZIP",
				dataIndex: 'zip',
				sortable: true
			},
			{ header: "ACCOUNT #",
				dataIndex: 'account',
				width: 130,
				sortable: true
			},
			{ header: "RATE CLASS",
				dataIndex: 'rate_class',
				width: 100,
				sortable: true
			}

		],

		tbar: [
		{
			// text: "Button"
		}
		], // end tbar

    // paging bar on the bottom
    bbar: new Ext.PagingToolbar({
        pageSize: 10,
        store: Application.store,
        displayInfo: true,
        displayMsg: 'Displaying records {0} - {1} of {2}',
        emptyMsg: "No records to display",
        listeners: {
        	change :  function ( toolbar, pageData ) {
				//console.log( pageData );
				//console.log( toolbar );
				//pages = pageData.pages;
				//total = pageData.total;
				//activePage = pageData.activePage;
        	}
        } //end listeners
    })

	});  // end grid

	// Application.grid.hide();
});



function fetchAccounts( )
{
	if ( $('#first_name').val() == '' &&  $('#last_name').val() == '' &&  $('#Service_Address1').val() == ''
		&&  $('#Service_Address2').val() == '' &&  $('#Service_City').val() == ''
		&& $('#Service_Zip5').val() == '' &&  $('#frm_account').val() == '' )
	{
		alert('Please choose at least one field to perform the search');
		$('#first_name').focus();
	}

	$('#btn_continue').attr('disabled', 'disabled');

	Application.store.load();
}

function saveAcct(accid)
{
	window.opener.$('#account_id').val( accid );
	alert('This account will be saved after clicking "Save and Continue" on the utility tab.');
	self.close()
}



function masterchecksetting1(selelm)
{
	if(selelm.value=='0')
	{
		document.getElementById('section1a').style.display='none';
		document.getElementById('section1b').style.display='';
		document.getElementById('section4c').style.display='none';
		document.getElementById('section4a').style.display='none';
		document.getElementById('section4b').style.display='none';
		document.getElementById('section0a').style.display='none';
		document.getElementById('eflag').value='';
		document.getElementById('eflagid').value='';
		// document.getElementById('btn_log_dispo').disabled = true;
		document.getElementById('btn_continue').disabled = false;
	}
	else
	{
		document.getElementById('section1a').style.display='';
		document.getElementById('section1b').style.display='none';
		document.getElementById('section4c').style.display='';
		document.getElementById('section0a').style.display='';
		document.getElementById('eflag').value='';
		document.getElementById('eflagid').value='';
		document.getElementById('section6b').style.display='none';
		document.getElementById('section6a').style.display='none';
		// document.getElementById('btn_log_dispo').disabled = true;
		document.getElementById('btn_continue').disabled = false;
	}
}
function masterchecksetting3(selelm)
{
	if(selelm.value=='1')
	{
		document.registration.email_addr.value='';
		document.getElementById('section3a').style.display='none';
		document.getElementById('section3b').style.display='';
	}
	else
	{
		document.registration.email_addr.value='none@none.com';
		document.getElementById('section3a').style.display='';
		document.getElementById('section3b').style.display='none';
	}
}
function PArestoreForm(selelm)
{
	var selelm;
	document.getElementById('rdiv').innerHTML = 'none';
	document.getElementById('rdiv1').innerHTML = '';

	document.getElementById('frm_name').value = savedName;
	document.getElementById('frm_addr').value = savedAddr;
	document.getElementById('frm_city').value = savedCity;
	document.getElementById('frm_state').value = savedState;
	document.getElementById('frm_zip').value = savedZip;
	document.getElementById('frm_acct').value = savedAcct;
}

function masterchecksetting4(selelm)
{
	if(selelm.value=='0')
	{
		document.getElementById('section4a').style.display='none';
		document.getElementById('section4b').style.display='';
		document.getElementById('serviceinfo_val').value = 0;
		document.getElementById('eflag').value='';
		document.getElementById('eflagid').value='';
		// document.getElementById('btn_log_dispo').disabled = true;
		document.getElementById('btn_continue').disabled = false;
	}
	else
	{
		document.getElementById('section4a').style.display='';
		document.getElementById('section4b').style.display='none';
		document.getElementById('serviceinfo_val').value = 1;
		document.getElementById('eflag').value='';
		document.getElementById('eflagid').value='';
		//document.getElementById('btn_log_dispo').disabled = true;
		document.getElementById('btn_continue').disabled = false;
	}
}

function masterchecksetting5(selelm)
{
	if(selelm.value=='1')
	{
		document.getElementById('section5a').style.display='none';
		document.getElementById('section5b').style.display='';
		document.getElementById('eflag').value='';
		document.getElementById('eflagid').value='';
		//document.getElementById('btn_log_dispo').disabled = true;
		document.getElementById('btn_continue').disabled = false;
	}
	else
	{
		document.getElementById('section5a').style.display='';
		document.getElementById('section5b').style.display='none';
		document.getElementById('eflag').value='dispo';
		document.getElementById('eflagid').value='7';
		logDispo('7')
		//document.getElementById('btn_log_dispo').disabled = false;
		document.getElementById('btn_continue').disabled = true;
	}
}

function masterchecksetting16(selelm)
{
	if(selelm.value=='1')
	{
		document.getElementById('section6a').style.display='none';
		document.getElementById('section6b').style.display='';
		document.getElementById('eflag').value='';
		document.getElementById('eflagid').value='';
		//document.getElementById('btn_log_dispo').disabled = true;
		document.getElementById('btn_continue').disabled = false;

	}
	else
	{
		document.getElementById('section6a').style.display='';
		document.getElementById('section6b').style.display='none';
		document.getElementById('eflag').value='dispo';
		document.getElementById('eflagid').value='4';
		logDispo('4');
		//document.getElementById('btn_log_dispo').disabled = false;
		document.getElementById('btn_continue').disabled = true;
	}
}
function logDispo(eflagid)
{

	window.opener.document.inbound_utility.eflag.value='disp';
	window.opener.document.inbound_utility.eflagid.value=eflagid;


	alert("THANK YOU FOR CALLING ENERGY PLUS. HAVE A NICE DAY!!");

	window.opener.document.inbound_utility.action = 'dispositions.php';
	window.opener.document.inbound_utility.submit();
	self.close();
	return true;
}





</script>
</head>
<body>

<div class="yui3-g" >
<div class="yui3-u" id="main">



<form id="inbound_pasearch" name="inbound_pasearch" method="post" action="#" autocomplete="off">
<div id="rdiv1">
<?
if (empty($_POST))
{
		if($Session_Service_fname=='')
		{
		?>
			<BR><BR>
			<span class="formdata">May I please have your first name, middle initial and last name on your utility account?&nbsp;&nbsp;<input name="personalA" type="radio" value="1" onClick="masterchecksetting1(this)">&nbsp;&nbsp;Yes&nbsp;&nbsp;
			<input name="personalA" type="radio" value="0"  onclick="masterchecksetting1(this)">&nbsp;&nbsp;No</span>
		<?
		}
		else
		{
		?>
			<span class="formdata">Is this the full name present on your utility bill?&nbsp;&nbsp;<input name="personalA" type="radio" value="1" onClick="masterchecksetting1(this)">&nbsp;&nbsp;Yes&nbsp;&nbsp;
			<input name="personalA" type="radio" value="0"  onclick="masterchecksetting1(this)">&nbsp;&nbsp;No</span>
		<?
		}
		?>
			<div  style="height:20px;width:100%;">
					<span class='rep_note'>&nbsp;</span>

			</div>
			<div id="section0a" style="height:40px;width:100%;display: none;">
					<span class='rep_note'>REPEAT AND SPELL BACK AS YOU ENTER INFORMATION.</span>

			</div>
			<div id="section1b" style="height:100px;width:100%; float: left;display: none;">
						<span class="formdata">
								Unfortunately we need to know your name before we can proceed with your enrollment.  Once you are willing to provide this information, please call us back at  <?=$_SESSION['enroll_tel'];?> or you can complete your enrollment on-line at  <?=$_SESSION['web_addr'];?>. </span><BR><BR> <span class="formdata">
								Is there anything else I can do for you today?
								<input name="unfortu" type="radio" value="1" onClick="masterchecksetting16(this)">&nbsp;&nbsp;Yes
								<input name="unfortu"  type="radio" value="0" onClick="masterchecksetting16(this)">&nbsp;&nbsp;No
						</span>
				</div>
				<div id="section6b" style="height:50px;width:100%; float: left;display: none;"><span class="formdata"><p class='rep_note'>IF CUSTOMER HAS QUESTIONS GO TO FAQS</p></span><BR></div>
				<div id="section6a" style="height:50px;width:100%; float: left;display: none;"><span class="formdata"><span class='rep_note'>(END CALL) (NOTE TO TSR: CALL WAS AUTO DISPO'D #4: CALLER REFUSED TO PROVIDE NAME)</span></div>
				<div id="section1a" style="width:100%; float: left;display: none;">
					<div style="height:35px;width:30%; float: left;"><span class="formdata">First Name:</span></div>
					<div style="height:35px;width:70%; float: left;">
						<span class="formdata">
							<input id="first_name" name="first_name"  value="<?=$Session_Service_fname;?>" >
							<input type="hidden" name="r_first_name" value="First Name must be entered">
						</span>
		               </div>
		               <div style="height:35px;width:30%; float: left;"><span class="formdata">Middle Initial:</span></div>
				 <div style="height:35px;width:70%; float: left;"><span class="formdata"><input type="text" name="middle_initial" id="middle_initial"   ></span> </div>
				 <div style="height:35px;width:30%; float: left;"><span class="formdata">Last Name:</div>
				 <div style="height:35px;width:70%; float: left;">
					<span class="formdata">

							<input name="last_name" id="last_name"  value="<?php echo trim($Session_Service_lname);?>"  >
							<input type="hidden" name="r_last_name" value="Last Name must be entered">
					</span>
				</div>

				 <div style="height:35px;width:30%; float: left;"><span class="formdata">Suffix:</span></div>
				 <div style="height:35px;width:70%; float: left;"><span class="formdata">
							<input name="Suffix" type="radio" value="Sr.">&nbsp;&nbsp;Sr.
							<input name="Suffix" type="radio" value="Jr.">&nbsp;&nbsp;Jr.
							<input name="Suffix" type="radio" value="Other">&nbsp;&nbsp;Other.</span>
				</div>
		</div>
		<div id="section4c" style="display: none; height:50px;width:100%; float: left;">
					<span class="formdata">Thank you.
						May I please have the account number or the address where the electric service <BR>
						will be used starting with the full street address?&nbsp;&nbsp;<input name="serviceinfo" id="serviceinfo" type="radio" value="1" onClick="masterchecksetting4(this)">&nbsp;&nbsp;Yes&nbsp;&nbsp;<input name="serviceinfo" id="serviceinfo" type="radio" value="0" onClick="masterchecksetting4(this)">&nbsp;&nbsp;No
				   	</span>
		</div>

		<div id="section4a" style="display: none; ">
				<div style="height:50px;width:100%; float: left;">
					<p bgcolor="#666666" class="formheader"><font color="#FFFFFF">
						<strong><span id="addpre"></span> Service Address:</strong><i>&nbsp;&nbsp;REPEAT AND SPELL BACK AS YOU ENTER INFORMATION.</i>
						 <span id="prim1"></span></font>
				</div>
				<div style="height:35px;width:30%; float: left;"><span class="formdata">Service Address:</span></div>
				<div style="height:35px;width:70%; float: left;">
					<span class="formdata">
						<input name="Service_Address1" id="Service_Address1"  maxlength="64"  value="" >
		      				<input type="hidden" name="r_Service_Address1" value="Missing Service Address1">
					</span>
		  		</div>
				<div style="height:35px;width:30%; float: left;"><span class="formdata">Service Apt#/Suite#:</span></div>
				<div style="height:35px;width:70%; float: left;">
					<span class="formdata">
						<input name="Service_Address2" id="Service_Address2"  maxlength="64"  value="" >
		      				<input type="hidden" name="r_Service_Address2" value="Missing Service Address2">
					</span>
		  		</div>

				<div style="height:35px;width:30%; float: left;"><span class="formdata">City:</span></div>
				<div style="height:35px;width:70%; float: left;">
					<span class="formdata">
							<input name="Service_City" id="Service_City"  maxlength="20" value="" >
							<input type="hidden" name="r_Service_City" value="Missing Service City">
					</span>
					<span class="formdata">
							<input name="Service_State" id="Service_State" type="text" value="<?php if($_SESSION['st_abbrev']){echo $_SESSION['st_abbrev'];}?>" readonly  >
					</span>
					<span class="formdata">
							<input name="Service_Zip5" type="text" size="8" maxlength="5" id="Service_Zip5"  value="" >-
							<input name="Service_Zip4" type="text" size="8" maxlength="4" id="Service_Zip4"  value="" >
							<input name="z_Service_Zip5" type="hidden" value="<?php echo $strec->zip_min;?>,<?php echo $strec->zip_max;?>">
							<input type="hidden" name="o_Service_Zip5" value="5,Invalid Service Zip">
							<input type="hidden" name="r_Service_Zip5" value="Missing Service Zip">
					</span>
				</div>
				<div style="height:35px;width:30%; float: left;"><span class="formdata">Account:</span></div>
				<div style="height:35px;width:70%; float: left;">
					<span class="formdata">
						<input name="frm_account" id="frm_account"  maxlength="64"  value="" >
		      				<input type="hidden" name="r_Service_Address2" value="Missing Service Address2">
					</span>
		  		</div>
				<!-- <div style="height:35px;width:30%; float: left;"><span class="formdata">POR:</span></div>
				<div style="height:35px;width:70%; float: left;">
					<span class="formdata">
						<input name="frm_por" id="frm_por"  maxlength="64"  value="" >
		      				<input type="hidden" name="r_Service_Address2" value="Missing Service Address2">
					</span>
		  		</div>
		-->
			</div>
			<div id="section4b" style="display: none;">
					<span class="formdata">
						Unfortunately we need to know your address before we can proceed with your enrollment. <BR> Once you are willing to provide this information, please call us back at  <?=$_SESSION['enroll_tel'];?> or you can complete your enrollment on-line at <?=$_SESSION['web_addr'];?>.<BR><BR>
						Is there anything else I can do for you today?
							<input name="servicefaq" id="servicefaq" type="radio" value="1" onClick="masterchecksetting5(this)">Yes
							<input name="servicefaq" id="servicefaq" type="radio" value="0" onClick="masterchecksetting5(this)">No
					</span>
					<div id="section5a" style="height:25px;width:100%; float: left;display: none;"><span class="formdata"><span class='rep_note'>NOTE TO TSR: CALL WAS AUTO DISPO'D</span></div>
					<div id="section5b" style="height:25px;width:100%; float: left;display: none;"><span class='rep_note'>IF CUSTOMER HAS QUESTIONS GO TO FAQS</span></div>
					<BR>
			</div>
			<BR><BR>

			<div style="clear: both; padding-bottom: 3em;" >
			<table>
				<tr>
					<td>
						<input type="button" class="ib_button" name="btn_continue" id="btn_continue" value="Search" onClick="fetchAccounts(  );" >
						<input type="button" class="ib_button" name="btn_log_dispo" id="btn_log_dispo" value="Log Dispo" onClick="logDispo(this)" >
						<input type='hidden' name='eflag' id='eflag' >
						<input type='hidden' name='eflagid' id='eflagid' >
						<input type='hidden' name='serviceinfo_val' id='serviceinfo_val' value="0" >
						<input type='hidden' name='util' id='util' value="<?=$util;?>">
					</td>
				</tr>
			</table>
			</div>
</div>
</div>
</div>

<?php
	}
?>
   <div id="results_tbl_txt" style="padding-bottom: 3em;">
        <p>There were <span id="results_tbl_total_count"></span> &nbsp;accounts returned.  Please click on the row to select account to save.</p>
    </div>
<div id="results" style="padding-bottom: 5em;">
	<div id="results_tbl" ></div>
</div>
<div id="rdiv"> </div>
<span id="accidshow"></span>
<input type="hidden" name="accid" id="accid" value="" >
</form>





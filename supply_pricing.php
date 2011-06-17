<?php

//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Historical Pricing Page//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////



require_once 'includes/main.php';
require_once 'EP/Util/Database.php';
require_once 'EP/Model/Utility.php';
require_once 'EP/Model/UtilityMapper.php';
require_once 'EP/Model/StateMapper.php';
require_once 'EP/Util/Json.php';

// fetch all the states
$stateMapper = new EP_Model_StateMapper();
$states = $stateMapper->fetchAll( array( 'id', 'name') );

// fetch all the utilities
$utilMapper = new EP_Model_UtilityMapper();
$utils = $utilMapper->fetchAll( array('id', 'utility', 'state' ), array( 'state', 'id') );

// create an array for later json use that lists the utilities by state
$utilsByState = array();
foreach ( $states as $state )
{
	$utilsByState[ $state->getId() ]['name'] = $state->getName();
	$tmpUtils = array();
	foreach ( $utils as $util )
	{
		if ( $state->getId() == $util->getState() )
		{
			$counter = 0;
			if ( isset( $utilsByState[ $state->getId() ] ))
			{
				$counter = count( $utilsByState[ $state->getId() ] );
			}
			$tmpUtils[] = array( 'id' => $util->getId(), 'name' => $util->getUtility() );
		}
	}
	$utilsByState[ $state->getId() ]['utils'] = $tmpUtils;
}

$json = new EP_Util_Json();
$jsonUtilsByState = $json->encode( $utilsByState );

//Defaults

$Post_State= '';
if(isset($_POST['state']))
{
	$Post_State = $_POST['state'];
}

$Post_utility= '';
if(isset($_POST['utility']))
{
	$Post_utility = $_POST['utility'];
}

$Session_State= null;
if(isset($_SESSION['tabs']['state']['state']))
{
	$Session_State = $_SESSION['tabs']['state']['state'];
}

$Session_Utility= null;
if(isset($_SESSION['tabs']['utility']['select_utility']))
{
	$Session_Utility = $_SESSION['tabs']['utility']['select_utility'];
}

if($Post_State!='')
{
	$state=$Post_State;
}
else
{
	$state=$_POST['state']=$Session_State;
}

if($Post_utility!='')
{
	$utility=$Post_utility;

}
else
{
	$utility=$Session_Utility;
}




require_once 'includes/header.php';
?>
<script type="text/javascript">
var utilsByState = <?=$jsonUtilsByState;?>;

var http1 = getHTTPObject();

var partner=0;

function searchval()
{

	var state=document.getElementById('state').value;

	if(document.getElementById('hid_utility').value=='')
	{
		alert('Please choose the State');
		document.getElementById('state').focus;
		return false;
	}

	var utility5=document.getElementById('utility5').value;
	if(state=='')
	{
		alert('Please choose State');
		document.getElementById('state').focus;
		return false;
	}
	else if(utility5=='')
	{
		alert('Please choose Utility');
		document.getElementById('utility5').focus;
		return false;
	}
	else
	{
		var url = 'ajax/get_historical_pricing.php?state=' + state + '&utility=' + utility5;
	       http1.open("GET",url, true);
       	http1.onreadystatechange = handlesection;
	       http1.send(null);
	}
}

function handlesection()
{
 	if(http1.readyState == 4)
        {
		  document.getElementById("section_historicalPrice").innerHTML=http1.responseText;
        }
}





function populateUtil( sel )
{
	var state = sel.value;

	if ( utilsByState[ state ] === undefined )
	{
		return;
	}
	
	var utils = utilsByState[ state ].utils;
	var output = '';
	output = '<select name="utility5" id="utility5" class="dropdown" >';
	output += '<option value="">Select Utility</option>';

	for ( var i = 0; i < utils.length; i++ )
	{
		var id = utils[ i ].id;
		var name = utils[ i ].name;
		output += '<option value="' + id + '">';
		output += name;
		output += '</option>';
	}
	output += '</select>';
	document.getElementById("utilitylist").innerHTML = output;
	// not sure what this is
	document.getElementById('hid_utility').value=1;
}

</script>
</head>
<link href="../css/global.css" rel="stylesheet" type="text/css">
<body>

<div class="yui3-g" id="container" >

<?php
	$page = basename( __FILE__ );
	require_once 'includes/nav.php';
?>
        <div class="yui3-u" id="main">
	<div style="padding: 1em;" >

<form action="historical_pricing.php"  name="pricingdata" id="pricingdata" method="post">
	<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="chartdata">
		<?php
			echo '<select name="state" id="state" class="dropdown" onchange="populateUtil( this );">';
			echo '<option value="">Select State</option>';
			foreach ( $states as $state )
			{
				$selected = '';
				$id = $state->getId();
				$name = $state->getName();
				if( $_POST['state'] == $state->getId() )
				{
					$selected = 'selected="selected"';
				}
				echo "<option value='$id' $selected >$name</option>";					
			}
			echo '</select>';			
		?>
			</td>
			<td class="chartdata">

			<span id='utilitylist'></span>

			</td>

			<td class="chartdata">
				<select name="commodity" id="commodity" class="dropdown" >
					<option value='E' Selected>Electric</option>
					<!--<option value='G' <?if($_POST['commodity']=='G'){echo "Selected";}?>>Gas</option>-->
				</select>
			</td>
			<td align="left">
				<input type="button" id="search_btn" name="search_btn" value="Search" onclick="searchval(this)">
			</td>
		</tr>

	</table>
	</form>
	<BR><BR>




	<table width="700" border="0">
		<tr>
			<td>
				<span id='section_historicalPrice'></span>
			</td>
		</tr>
	</table>

	<BR>
	<BR>

	<table width="700" border="0">
			<tr>
				<td>
					<p span='rep_note'>NOTE TO TSR: IF NO HISTORICAL PRICING IS FOUND "I am Sorry, But we have not been servicing your area long enough to provide you with historical data. IF CUSTOMER PERSISTS FOR ANSWER, Agents should go to Pricing FAQ and provide appropriate Rate</p>
				</td>
			</tr>
	</table>


	<input type='hidden' name='actionval' id='actionval'>
	<input type='hidden' name='hid_utility' id='hid_utility'>



	</div>
		</div>
<?php
        require_once 'includes/statusbar.php';
?>

</div>
<?php
        require_once 'includes/footer.php';
?>
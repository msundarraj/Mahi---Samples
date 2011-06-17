<?php

//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Calls Module//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////

require_once 'includes/main.php';
require_once 'EP/Util/Database.php';
require_once 'EP/Model/Ib/CallMapper.php';
require_once 'EP/Model/Ib/CallXRegisterMapper.php';
require_once 'EP/Model/Ib/Call/DispoMapper.php';
require_once 'EP/Model/Ib/Call/EndstatusMapper.php';
require_once 'EP/Model/Ib/Call/Dispo/EnhancementMapper.php';
require_once 'includes/header.php';

$operator = null;
$mid = null;
if ( !isset( $_SESSION['operator']))
{
	return;
}

$callStart = null;

$operator = $_SESSION['operator'];	

// In order to train the call reps, RDI needs to be able to go to QA and start calls with a link
// instead of using the auto-call pop from the webservice
if ( APPLICATION_ENV == 'rz.devepc.com' || APPLICATION_ENV == 'epcqa.com' )
{
	$callStart = '<h1><a href="/myinbound/call_start.php">Start a new call</a></h1>';
}
elseif ( $operator->isEPOperator( $operator ))
{
	$callStart = '<h1><a href="/myinbound/call_start.php">Start a new call</a></h1>';
}


?>
<meta http-equiv="refresh" content="300">
</head>

<body>

<div class="yui3-g" id="container">

<?php
	$page = basename( __FILE__ );
	require_once 'includes/nav.php';
?>
        <div class="yui3-u" id="main">

<?php 
 
// c = call
if ( isset( $_REQUEST['c']))
{
	$output = <<<EOL
<style type="text/css">
td.label {
	background-color: #eee;
	text-align: right;
	font-weight: bold;
}
</style>
	<div>
			$callStart
	</div>
<h1>Call Ended</h1>
EOL;

	echo $output;
	$output = '';

	$c = trim((int)$_REQUEST['c']);
	
	// init our main variables
	
	$extCustId = null;
	$callId = null;
	$callEndStatusId = null;
	$callEndStatus = null;
	$uid = null;
	$dispoId = null;
	$dispoEnhancementId = null;
	$extUsername = null;
	$dateCreated = null;
	$dateEnded = null;
	$vendorId = null;
	
	// get the call record
	$mapper = new EP_Model_Ib_CallMapper();
	$call = $mapper->fetch( $c );
	
// echo '<pre>' . print_r( $call, true ) . '</pre>';
	if ( !$call )
	{
		die ( 'No call by that id');
	}
	

	$extCustId = $call->getExtCustId();
	$callId = $c;
	$vendorId = $call->getVendorId();
	$extUsername = $call->getUsername();
	$dateCreated = $call->getDateCreated();
	$dateEnded = $call->getDateEnded();
		
	$callXRegisterMapper = new EP_Model_Ib_CallXRegisterMapper();
	$regs = $callXRegisterMapper->fetchByCallId( $call->getId() );
	
	$output = '';
	
	for ( $i = 0; $i < count( $regs ); $i++ )
	{
		$callEndStatusId = $regs[$i]->getEndStatusId();
		$uid = $regs[$i]->getUid();
		$dispoId = $regs[$i]->getCallDispoId();
		$dispoEnhancementId = $regs[$i]->getCallDispoEnhancementId();
		$dispoReason = $regs[$i]->getReason();
		
		$statusMapper = new EP_Model_Ib_Call_EndstatusMapper();
		$statusResult = $statusMapper->fetch( $callEndStatusId );
		if ( $statusResult )
		{
			$callEndStatus = $statusResult->getName();
		}

		$dispoHTML = '';
		$dispoEnhancedHTML = '';

		
		// if there's a dispo, we need to do more work to get that info
		if ( $dispoId )
		{
			$dispoName = null;
			
			$dispoMapper = new EP_Model_Ib_Call_DispoMapper();
			$dispoResult = $dispoMapper->fetch( $dispoId );

			if ( $dispoResult )
			{
				$dispoName = $dispoResult->getName();
			
			$dispoHTML = <<<EOL
	<tr>
		<td class="label" width="50%">Disposition: </td>
		<td>$dispoId - $dispoName</td>
	</tr>
	<tr>
		<td class="label">Dispo comments: </td>
		<td>$dispoReason</td>
	</tr>
EOL;
			
			} // end if dispoResult
		} // end if dispo
		
		if ( $dispoEnhancementId )
		{
			$dispoEnhancementName = null;
			$dispoEnhancementMapper = new EP_Model_Ib_Call_Dispo_EnhancementMapper();
			$dispoEnhancementResult = $dispoEnhancementMapper->fetch( $dispoEnhancementId );
			if ( $dispoEnhancementResult )
			{
				$dispoEnhancementName = $dispoEnhancementResult->getName();
				$dispoEnhancedHTML = <<<EOL
				
	<tr>
		<td class="label">Enhanced Dispo reason:</td>
		<td>$dispoEnhancementId - $dispoEnhancementName </td>
	</tr>	
EOL;

			} // end dispoEnhancementResult
		} // end if dispoEnhancementId
	$output .= <<<EOL


<table width="75%" cellspacing="0" border="1" class="admintable" >
	<tr>
		<td class="label" width="50%" >RDI Cust Id: </td>
		<td>$extCustId</td>
	</tr>
	<tr>
		<td class="label">EPWeb Call Id: </td>
		<td>$callId</td>
	</tr>
	<tr>
		<td class="label">Call end status: </td>
		<td>$callEndStatusId - $callEndStatus</td>
	</tr>
$dispoHTML
$dispoEnhancedHTML

EOL;


$output .= <<<EOL
	<tr>
		<td class="label">Time started:</td>
		<td>$dateCreated</td>
	</tr>
	<tr>
		<td class="label">Time ended:</td>
		<td>$dateEnded</td>
	</tr>
	<tr>
		<td class="label">RDI username:</td>
		<td>$extUsername</td>
	</tr>
</table><p>&nbsp;</p>
			
EOL;
		
	}	// end for regs loop
		

	echo $output;

}

?>
	</div>
</div>
<?php
        require_once 'includes/footer.php';
        
  
        
?>

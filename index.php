<?php 


//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Index Page//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////
	require_once 'includes/main.php';
	
	$operator = $_SESSION['operator'];
	
	// In order to train the call reps, RDI needs to be able to go to QA and start calls with a link
	// instead of using the auto-call pop from the webservice
	if ( APPLICATION_ENV == 'rz.devepc.com' || APPLICATION_ENV == 'epcqa.com' )
	{
		
	}
	elseif ( $operator->getMid() == 'RDIN' )
	{
		header( 'Location: /myinbound/waiting_for_call.php' );
	}
	require_once 'includes/header.php';
?>
<meta http-equiv="refresh" content="300">
</head>
<body>

<div class="yui3-g" id="container" >

<?php 
	require_once 'includes/nav.php';
?>



        <div class="yui3-u" id="main">


<h1><a href="/myinbound/call_start.php">Start a new call</a></h1>

        </div>
        
        
        
        
<?php 
	require_once 'includes/statusbar.php';
?>    
</div>
<?php 
	require_once 'includes/footer.php';
?>

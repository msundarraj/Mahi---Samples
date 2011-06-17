<?php

//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Disposition Page//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////

require_once 'includes/main.php';
require_once 'EP/Model/Ib/Call/Dispo/CategoryMapper.php';
require_once 'EP/Model/Ib/Call/Dispo/Category.php';
require_once 'EP/Model/Ib/Call/DispoMapper.php';
require_once 'EP/Model/Ib/Call/Dispo.php';
require_once 'EP/Model/Ib/CallMapper.php';
require_once 'EP/Model/Ib/CallXRegisterMapper.php';
require_once 'EP/Util/Json.php';



if ( !empty( $_POST ) )
{
	$callId = null;
	$call = null;

	if ( isset( $_POST['call_id']))
	{
		$callId = trim( $_POST['call_id']);
	}
	else if ( isset( $_SESSION['call']))
	{
		$call = $_SESSION['call'];
		$callId = $call->getId();
	}


	if ( isset( $_POST['eflagid']) && !is_null( $callId )  )
	{


		$dispoId = trim( $_POST['eflagid']);
		$dispoReason = 'auto';
		$callStatus = 3;
		$dispoEnhancementId = null;

		if ( isset( $_POST['reason']) && $_POST['reason'] != '' )
		{
			$dispoReason = trim( $_POST['reason'] );
		}

		if ( isset( $_POST['end_call_status']))
		{
			$callStatus = (int)trim( $_POST['end_call_status']);
		}

		if ( isset( $_POST['en_dispos']) && $_POST['en_dispos'] != '')
		{
			$dispoEnhancementId = (int)trim( $_POST['en_dispos']);
		}

		// fetch the call from the db
		$callMapper = new EP_Model_Ib_CallMapper();
		$call = $callMapper->fetch( $callId );
		if ( !$call ) die("cannot find matching call");

		$callXRegisterId = null;
		$callXRegisterMapper = new EP_Model_Ib_CallXRegisterMapper();
		$callXRegister = $callXRegisterMapper->fetch( $_SESSION['call_x_register']->getId() );

		if ( !$callXRegister ) die("cannot find matching call x register ");

		// update the call with our new end status
		// non sale dispostion is status of 3
		$callXRegister->setEndStatusId( $callStatus );

		$callXRegister->setCallDispoId( $dispoId );
		$callXRegister->setCallDispoEnhancementId( $dispoEnhancementId );
		$callXRegister->setReason( $dispoReason );
		$date = date('Y-m-d H:i:s');
		$call->setDateEnded( $date );
		$callXRegister->setDateEnded( $date );

		// start the transaction and save the call
		$db = EP_Util_Database::pdo_connect();
		$db->beginTransaction();

		$callMapper->setDatabaseConnection( $db );
		$cmResult = $callMapper->save( $call );

		$callXRegisterMapper->setDatabaseConnection( $db );
		$callXRegisterResult = $callXRegisterMapper->save( $callXRegister );


		if ( $cmResult && $callXRegisterResult )
		{
			$db->commit();
			// header show call end
			$call->endCall();

			header( "Location: ${base_url}myinbound/show_call_end.php?c=" . $call->getId() );
		}
		else
		{
			$db->rollback();
		}
	}
	else
	{
		// error
		 $_SESSION['messages'] = 'Missing call id or dispo codes';
	}
}

$dispoCatsMapper = new EP_Model_Ib_Call_Dispo_CategoryMapper();
$dispoCatsMapper->setUsePublicVars( true );
$dispoCats = $dispoCatsMapper->fetchAll();

$disposMapper = new EP_Model_Ib_Call_DispoMapper();
$disposMapper->setUsePublicVars( true );
// we don't want the auto dispo options to show here
//$dispos = $disposMapper->fetchAllNotAutoDispo();
 $dispos = $disposMapper->fetchAll();

// build an array for each category
// that has all the items for that category
// we'll use this in our javascript
$catArrs = array();

foreach ( $dispoCats as $num => $dispoCat )
{
	$tmpArr = array();
	$catId = $dispoCat->id;

	$tmpArr['category_id'] = (int)$catId;
	$tmpArr['items'] = array();
	foreach ( $dispos as $num2 => $dispo )
	{
		if ( $catId == $dispo->category_id )
		{
			$tmpArr['items'][] = $dispo;
		}
	}

	$arrName = 'cat_' . $catId;
	$$arrName = $tmpArr;
	$catArrs[] = $$arrName;
}

$jsonObj = new EP_Util_Json();
$jsonDispos = $jsonObj->encode( $catArrs );

// START the HTML page
require_once 'includes/header.php';
?>


<script type="text/javascript">

	var dispos = <?=$jsonDispos; ?>;

<?php
	$dispoId = "null";
	if ( isset( $_REQUEST['eflagid'] ) && !empty( $_REQUEST['eflagid']))
	{
		$dispoId = $_REQUEST['eflagid'];
	}

	echo "    var dispoId = $dispoId ;";
?>

	function fetchCategory( catId )
	{
		for ( var i = 0; i < dispos.length; i++ )
		{
			if ( dispos[i].category_id == catId )
			{
				return dispos[i];
			}
		}
		return false;
	}

	function fetchCategoryByDispo( dispoId )
	{
		// loop thru cats
		for ( var i = 0; i < dispos.length; i++ )
		{
			// look in items in each cat
			for ( var j = 0; j < dispos[i].items.length; j++ )
			{
				if ( dispos[i].items[j].id == dispoId )
				{
					return dispos[i].items[j].category_id;
				}
			}
		}
		return false;
	}

	function populateCatsAndDispos()
	{
		if ( dispoId == null )
		{
			populateDispos();
			return;
		}

		var catId = fetchCategoryByDispo( dispoId );
		var elem = document.getElementById( 'dispo_cats' );
		for ( var i = 0; i < elem.options.length; i++ )
		{
			if (elem.options[i].value == catId )
			{
				elem.options[i].selected == true;
				elem.value = catId;
				break;
			}
		}

		populateDispos( catId );

	}

	function populate_Enhanced_dispos(en_dispoid)
	{
		if((en_dispoid=='10')||(en_dispoid=='18'))
		{
		  document.getElementById("div_reason").style.display='';
		}
		else
		{
		  document.getElementById("div_reason").style.display='none';
		}
	}

	function populateDispos( catId )
	{
/*              $('#div_reason').hide();

		elem = document.getElementById('dispos');
	//	cats = document.getElementById( 'dispo_cats' );
	//	catsId = cats.value;

		if (isNaN( catsId))
		{
			elem.options.length = 1;
			elem.options[0].text = "No available dispositions";
			elem.options[0].value="no_available_dispositions";
			return;
		}
		else
		{
			var cat = fetchCategory( catId );
		}
		var items = cat.items;

		elem.options.length = 0;
		elem.options.length = items.length + 1;
		elem.options[0].text = "Choose a disposition";
		elem.options[0].value = "choose_a_disposition";
		for( var i = 0; i < items.length; i++ )
		{
			if ( dispoId != null && items[i].id == dispoId )
			{
				elem.options[i + 1].selected = true;
			}
			elem.options[i + 1].text = items[i].id+'. '+items[i].name;
			elem.options[i + 1].value = items[i].id;
		}*/
	}

	function save()
	{
		//var catId = document.getElementById( 'dispo_cats' ).value;
		var dispoId = document.getElementById( 'dispos' ).value;
		if (  isNaN( dispoId ))
		{
			alert('Please select a category and disposition');
			return false;
		}
		var form = document.getElementById('form_dispo');
		form.submit();

	}

	$(document).ready(function() {
		$(window).load( populateCatsAndDispos );
	});
</script>
<?php
// =$_SESSION['select_state']
?>
<style type="text/css">
table.forms td {
	padding: 8px;
}
table.forms td.labels {
	font-weight: bold;
	text-align: right;
}
</style>
</head>
<body>

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
<form id="form_dispo" name="form_dispo" action="" method="post" autocomplete="off">
<input type="hidden" name="submitted" value="1" >



<div class="whiteblock">
<div style="text-align: left; padding-top: 20px;">
	<p class="head1">DISPOSITIONS</p>
	<table cellspacing="0" border="0" class="forms" >
		<tr>

			<td>

<?php /*  ?><select name="dispo_cats" id="dispo_cats" onChange="populateDispos( this.value );" >
<?php
	$output = '';
	$output .= '<option value="choose_a_category">Choose a category</option>';
	foreach ($dispoCats as $num => $dispoCat )
	{
		$output .= '<option value="' . $dispoCat->id . '">' . $dispoCat->name . '</option>';
	}
	echo $output;
	$output = '';
?>
</select> <?php */ ?>
			</td>
		</tr>
		<tr>

			<td>
				<select id="dispos" name="eflagid" class="dispo">
					<?php foreach ($dispos as $currDispo) {?>
					<option value="<?php echo $currDispo->id; ?>" <?php if ($dispoId == $currDispo->id) { ?>selected="selected"<?php } ?>><?php echo $currDispo->id;?> : <?php echo $currDispo->name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
              <tr>
                     <td>
<script type="text/javascript">
var http5 = getHTTPObject();

 $('.dispo').change(function() {
  var dispoId = $(this).val();

  $.getJSON("ajax/get_dispo_count.php", {dispoId: dispoId}, function(resp)
  {
   if (resp.data == 1)
   {
	if((dispoId=='27')||(dispoId=='28'))
	{
     		$('#div_enhanced_dispos').show();
		var call_dispo_id = dispoId;
		var url = 'ajax/get_enhanced_dispos.php?call_dispo_id=' + call_dispo_id;
       	http5.open("GET",url, true);
	       http5.onreadystatechange = handledispo;
       	http5.send(null);
	}
	else
	{
		$('#div_enhanced_dispos').show();
		document.getElementById("div_reason").style.display='';
	}
   }
   else
   {
        $('#div_enhanced_dispos').hide();
        $('#reason').val('');
   }


  });
});

function handledispo()
{
 	if(http5.readyState == 4)
        {
		  document.getElementById("div_enhanced_dispos").style.display='';
		  document.getElementById("en_dispolist").innerHTML=http5.responseText;
        }
}


</script>


      		  <div id="div_enhanced_dispos" style="display: none;" >
					<span id='en_dispolist'></span>
                </div>
		 <BR>
               <div id="div_reason" style="display: none;" >
                    	<label for="reason" style="vertical-align: top; width: 100px; align: left">Comments: </label>
						<textarea name="reason" id="reason" style="width: 700px; height:100px;"></textarea>
              </div>
                     </td>
              </tr>
		<tr>

			<td><input type="button" name="button" id="disp_button" value="Log DISPO and Start Over" onClick="save()"></td>
		</tr>
</table>
</div>
</div>
</form>

        </div>
<?php
        require_once 'includes/statusbar.php';
?>
</div>
<?php
        require_once 'includes/footer.php';
?>


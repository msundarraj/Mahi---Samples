<?php


//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - FAQ Module//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////


require_once 'includes/main.php';
require_once 'EP/Util/Database.php';
require_once 'EP/Model/Offer.php';
require_once 'EP/Model/StarburstvariablesMapper.php';


$state = null;

$partnerId = null;
if ( isset( $_SESSION['tabs']['offer']['partner_id']))
{
	$partnerId = $_SESSION['tabs']['offer']['partner_id'];
}

$promoCode = null;
if ( isset( $_SESSION['tabs']['offer']['promo']))
{
	$promocode = $_SESSION['tabs']['offer']['promo'];
}

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


$Session_resbus= null;
if(isset($_SESSION['tabs']['utility']['resbus']))
{
	$Session_resbus=$_SESSION['tabs']['utility']['resbus'];
}


$partnerType = null;
if ( isset( $_SESSION['tabs']['offer']['partner_type'] ) )
{
	$partnerType = $_SESSION['tabs']['offer']['partner_type'];
}

$partnerName = null;
$partnerFullProgram = null;

if ( isset( $_SESSION['partner']))
{
	$partner = $_SESSION['partner'];
	$partnerName = $partner->getVariableValue( 'partner' );
	$partnerFullProgram = $partner->getVariableValue( 'full_program' );
}

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

$rewardType = null;
$partnerType = null;

if ( isset( $_SESSION['tabs']['offer']['reward_type'] ) )
{
	$rewardType = $_SESSION['tabs']['offer']['reward_type'];
}

if ( isset( $_SESSION['tabs']['offer']['partner_type'] ))
{
	$partnerType = $_SESSION['tabs']['offer']['partner_type'];
}

$Session_greenoption_choice= null;
if(isset($_SESSION['tabs']['account']['greenoption_choice']))
{
	$Session_greenoption_choice=$_SESSION['tabs']['account']['greenoption_choice'];
}


$default_offer_code= null;
if(isset($_SESSION['tabs']['offer']['default_offercode']))
{
	$default_offer_code=$_SESSION['tabs']['offer']['default_offercode'];
}


$session_state= null;
if(isset($_SESSION['tabs']['state']['state']))
{
	$session_state=$_SESSION['tabs']['state']['state'];
}


//echo $Session_greenoption_choice;
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


	if($session_state==3)
	{//If the state is Texas
				$sql = sprintf("select * from util_offers where code='%s' and util_code='%s' order by effdate desc limit 1",$default_offer_code,$utilityCode);
				$res = mysql_query($sql,$link);
				$offerrow = mysql_fetch_object($res);
				//echo mysql_num_rows($res);
				//echo $Session_greenoption_choice."<BR>";
				if(mysql_num_rows($res)>0)
				{
					if($accountType == 0)
					{
						$rate1000 = $offerrow->tdspfixed / 1000;
						//$offerrow->rate."<BR>";
						//$offerrow->tdspvariable."<BR>";
						$rate1 = $rate1000 + $offerrow->rate + $offerrow->tdspvariable;
						if($Session_greenoption_choice=='001')
						{
							$rate1 += $offerrow->vas_rate;
						}
						$kwh='1000 kWh';
					}
					else
					{
						//echo $offerrow->tdsp_non_dem_var."<BR>";
						$rate_d2500 = $offerrow->tdsp_non_dem_fixed/ 2500;
						//echo $rate_d2500."<BR>";
						//echo $offerrow->rate."<BR>";
						//echo $offerrow->tdsp_non_dem_var;
						$rate1 = $rate_d2500 + $offerrow->rate + $offerrow->tdsp_non_dem_var;
						if($Session_greenoption_choice=='001')
						{
							//echo $offerrow->vas_rate;
							$rate1 += $offerrow->vas_rate;
						}
						$kwh='2500 kWh';

					}
					$firstMonthPrice=sprintf("%0.1f&cent;",$rate1*100);
				}


	}//If the state is Texas
	else
	{//If the state is not Texas
				//echo $partnerType;
				//echo $accountType;
				//echo $utilityCode;

				if($Session_utility_code=='30')
				{
					$firstMonthPrice = $offer->fetchFirstMonthPrice( $stateObj->getId(), $_SESSION['tabs']['offer']['partner'], $accountType, $utilityCode, $Session_greenoption_choice,'','','',1);
				}
				else
				{
					$firstMonthPrice = $offer->fetchFirstMonthPrice( $stateObj->getId(), $_SESSION['tabs']['offer']['partner'], $accountType, $utilityCode, $Session_greenoption_choice);
				}

				$firstMonthPrice = sprintf("%0.3f&cent;",$firstMonthPrice*100);
				//echo $firstMonthPrice;

	}//If the state is not Texas



}


$rewardDesc = null;
if ( isset( $_SESSION['tabs']['offer']['partner'] ) &&
		isset( $_SESSION['tabs']['offer']['campaign'] ) &&
		isset( $_SESSION['tabs']['offer']['promo'] ) &&
		isset( $_SESSION['tabs']['state']['state'] ) )
{
	$offer = new EP_Model_Offer();
	$result = $offer->fetchDescription(  $_SESSION['tabs']['state']['state'], $_SESSION['tabs']['offer']['promo'],  $_SESSION['tabs']['offer']['campaign'],  $_SESSION['tabs']['offer']['partner'], $base_url,$utilityAccountType);
	 //echo '<pre>' . print_r( $result, true ) . '</pre>';
	$rewardDesc = $result['rewardDesc'];

	// hard-coding first month price for this one new york campaign/promo
	if ( $_SESSION['tabs']['offer']['campaign'] == '5571'
		&& $_SESSION['tabs']['offer']['promo'] == '089'
		&& $_SESSION['tabs']['state']['state'] == 1 )
	{
		if ($Session_greenoption_choice)
			$firstMonthPrice = sprintf("%0.3f&cent;", 10.9 );
		else
			$firstMonthPrice = sprintf("%0.3f&cent;", 9.9 );

	}

}



//echo "partnerType: $partnerType <br >";
//echo "utilityAccountType: $utilityAccountType <br >";
//echo "utility: $utility <br >";
//echo "firstmonthprice: $firstMonthPrice <br >";
//print_r( $stateObj );

//print_r($_SESSION['sb']);

$session_aff_res_ongoing=null;
$session_award_mons=null;
$session_bizaffin=null;
$session_bonus=null;
$session_bonus_biz=null;
$session_bonus_mon=null;
$session_bonus_res=null;
$session_bullet_snippet=null;
$session_bullet_snippet2=null;
$session_full_program=null;
$session_ib_desc=null;
$session_ongoing_bullet=null;
$session_resaffin=null;
$session_rewtxt=null;
$session_reward_type=null;
$session_second_month=null;


$mapper = new EP_Model_StarburstvariablesMapper();
$sb = $mapper->fetchVariablesByPartnerAndPromo( $partnerId, $promoCode );
if ( $sb )
{
	// echo '<pre>' . print_r( $sb, true ) . '</pre>';
	$session_aff_res_ongoing = $sb->aff_res_ongoing;
	$session_award_mons =  $sb->award_mons;
	$session_bizaffin = $sb->bizaffin;
	$session_bonus = $sb->bonus;
	$session_bonus_biz = $sb->bonus_biz;
	$session_bonus_mon = $sb->bonus_mon;
	$session_bonus_res = $sb->bonus_res;
	$session_bullet_snippet = $sb->bullet_snippet;
	$session_bullet_snippet2 = $sb->bullet_snippet2;
	$session_full_program = $sb->full_program;
	$session_ib_desc = $sb->ib_desc;
	$session_ongoing_bullet = $sb->ongoing_bullet;
	$session_resaffin = $sb->resaffin;
	$session_rewtxt = $sb->text1_miles;
	$session_reward_type = $sb->reward_type;
	$session_second_month = $sb->second_month;
	      
}
/*
if (isset( $_SESSION['sb']['aff_res_ongoing']))
{
     $session_aff_res_ongoing = $_SESSION['sb']['aff_res_ongoing'];
}
if (isset($_SESSION['sb']['award_mons']))
{
    $session_award_mons =  $_SESSION['sb']['award_mons'];
}
if (isset( $_SESSION['sb']['bizaffin']))
{
     $session_bizaffin = $_SESSION['sb']['bizaffin'];
}
if (isset( $_SESSION['sb']['bonus']))
{
     $session_bonus = $_SESSION['sb']['bonus'];
}
if (isset( $_SESSION['sb']['bonus_biz']))
{
     $session_bonus_biz = $_SESSION['sb']['bonus_biz'];
}
if (isset( $_SESSION['sb']['bonus_mon']))
{
     $session_bonus_mon = $_SESSION['sb']['bonus_mon'];
}
if (isset( $_SESSION['sb']['bonus_res']))
{
     $session_bonus_res = $_SESSION['sb']['bonus_res'];
}
if (isset( $_SESSION['sb']['bullet_snippet']))
{
     $session_bullet_snippet = $_SESSION['sb']['bullet_snippet'];
}

if (isset( $_SESSION['sb']['bullet_snipet2']))
{
     $session_bullet_snippet2 = $_SESSION['sb']['bullet_snippet2'];
}

if (isset( $_SESSION['sb']['full_program']))
{
      $session_full_program = $_SESSION['sb']['full_program'];
}
if (isset( $_SESSION['sb']['ib_desc']))
{
     $session_ib_desc = $_SESSION['sb']['ib_desc'];
}
if (isset( $_SESSION['sb']['ongoing_bullet']))
{
     $session_ongoing_bullet = $_SESSION['sb']['ongoing_bullet'];
}
if (isset( $_SESSION['sb']['resaffin']))
{
     $session_resaffin = $_SESSION['sb']['resaffin'];
}
if (isset( $_SESSION['sb']['rewtxt']))
{
       $session_rewtxt = $_SESSION['sb']['text1_miles'];
}
if (isset( $_SESSION['sb']['reward_type']))
{
     $session_reward_type = $_SESSION['sb']['reward_type'];
}
if (isset( $_SESSION['sb']['second_month']))
{
     $session_second_month = $_SESSION['sb']['second_month'];
}
*/





$rewtxt = null;
$bonus_mon= null;
$partner = null;
$rewtype=null;
if ( isset( $_SESSION['partner']))
{
      $partner = $_SESSION['partner'];
      $variables = $partner->getVariables();

 //echo '<pre>' . print_r( $variables, true ) . '</pre>';
      if ( isset( $variables['rewtxt']))
      {
             $rewtxt = $variables['rewtxt']->variable_value;
      }

     if (isset($variables['bonus_mon']))
      {
             $bonus_mon = $variables['bonus_mon']->variable_value;
      }

     if ( isset( $variables['reward_type']))
      {
             $rewtype = $variables['reward_type']->variable_value;
      }

      if(isset($variables['bonus_biz']))
	  	  {
	  	  	  	  $bonus_biz= $variables['bonus_biz']->variable_value;
	  }


}



require_once 'includes/header.php';
?>
<script type="text/javascript" src="faq.js"></script>

</head>

<body>

<div class="yui3-g" id="container" >

<?php
	$page = basename( __FILE__ );
	require_once 'includes/nav.php';
?>
        <div class="yui3-u" id="main">
	<div style="padding: 1em;" >
	<h1>FAQ</h1>


<?php
	require_once 'faq/section_one.php';
	require_once 'faq/section_two.php';
	require_once 'faq/section_three.php';
	require_once 'faq/section_four.php';
    require_once 'faq/section_five.php';
    require_once 'faq/section_six.php';
	require_once 'faq/section_seven.php';
	require_once 'faq/section_eight.php';
	require_once 'faq/section_nine.php';
	require_once 'faq/section_ten.php';
	require_once 'faq/section_eleven.php';
    require_once 'faq/section_twelve.php';
    require_once 'faq/section_thirteen.php';
	require_once 'faq/section_fourteen.php';
    require_once 'faq/section_fifteen.php';
	require_once 'faq/section_sixteen.php';
	require_once 'faq/section_seventeen.php';
	require_once 'faq/section_eighteen.php';

?>
</div>
		</div>
<?php
        require_once 'includes/statusbar.php';
?>

</div>
<?php
        require_once 'includes/footer.php';
?>

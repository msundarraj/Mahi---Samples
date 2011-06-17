<?php

//////////////////////////////////////////////
//Project Name - Supply Side Project/////////
//Developer    - Mahendran////////////////////
//Module Name  - Login Module//////
//Date         - 04/21/2011///////////////////
/////////////////////////////////////////////

require_once '../config.php';
require_once 'EP/Model/Operator.php';
session_start();

if ( isset( $_GET['logout']))
{
	$_SESSION['messages'] = "You have been logged out.";
}
if(isset($_POST['submitted']) && $_POST['submitted'] == 1 && isset($_POST['password']) && isset( $_POST['email'] ))
{
	session_destroy();
	session_start();
	$result = EP_Model_Operator::login( trim( $_POST['email']), trim( $_POST['password']));
	if ( $result )
	{
		header( 'Location: /myinbound/' );
	}
	else
	{
		$_SESSION['messages'] = 'Login failed.  Please try again';
	}

/*
		$_SESSION['user_name'] = $u->name;
		$_SESSION['email'] = $u->email;
		$_SESSION['id'] = $u->id;
		$_SESSION['venid'] = $u->venid;
		$_SESSION['ok_ib'] = 1;
*/
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Energy Plus</title>
<link href="/inbound/partnerstyles.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.ib_messages {
	color: #ff0000;
	font-weight: bold;
}
</style>
</head>
<body>

<div class="whiteblock">
<form id="login" name="login" method="post" action="login.php" autocomplete="off">
<input type="hidden" name="submitted" value="1" />
	<table width="500" align="center" border="0" cellspacing="0" cellpadding="5">

	<tr>
	  <td colspan="2"><p>&nbsp;</p><p class="partner_head">
				<img src="/myinbound/images/energypluslogo.gif" alt="Energy Plus Logo" width="281" height="47" hspace="0" />
			<p class="partner_head">In-Bound Login</p></td>
	  </tr>
	<tr>
		<td colspan="2">
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
		</td>
	</tr>
	<tr>
		<td class="chartdata" style="width:150px;">Email:</td>
		<td class="chartdata" style="width:280px;">
			<input type="email" name="email">
		</td>
	</tr>

	<tr>
		<td class="chartdata" style="width:150px;">Password:</td>
		<td class="chartdata" style="width:280px;">
			<input type="password" name="password">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="chartdata">
			<input type="submit" name="button" id="button" value=" Login " />
		</td>
	</tr>
	</table>
</form>
<p>&nbsp;</p>
</div>
<?php 
if ( isset( $_GET['logout']))
{
	session_destroy();
}
// echo '<pre>' . print_r( $_SESSION, true ) . '</pre>';
?>
</body>
</html>

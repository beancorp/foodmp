<?php

ob_start();
include_once ("include/session.php");
include_once ('include/config.php');
include_once ('include/smartyconfig.php');
include_once ("maininc.php");
include_once ('class/common.php');
include_once ('class.emailClass.php');
include_once ('class.upload.php');
include_once SOC_INCLUDE_PATH . '/functions.php';

$StoreID = $_POST['StoreID'];
$email = $_POST['email'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$quantity = (int)$_POST['quantity'];
$reservation_date = $_POST['reservation_date'];
if (empty($StoreID)) {
	$msg = '-  Please come from the currect url.<br/>';
} 
if (empty($email)) {
	$msg .= '-  Your Email is required.<br/>';
}
if (empty($name)) {
	$msg .= '-  Your Name is required.<br/>';
}
if (empty($phone)) {
	$msg .= '-  Your Contact Phone is required.<br/>';
}
if (empty($quantity)) {
	$msg .= '-  No. of People is required.<br/>';
}
if (!is_int($quantity) || $quantity < 1) {
	$msg .= '-  No. of People must be numberal.<br/>';
}
if (empty($reservation_date)) {
	$msg .= '-  Reservation Date is required.<br/>';
}

$date_info = explode('/', $reservation_date);
$date_str = $date_info[2].'-'.$date_info[1].'-'.$date_info[0].' '.$_POST['start_hour'].':'.$_POST['start_minute'].':00';
$reservation_date = strtotime($date_str);

if (empty($msg)){
	$arrSetting['StoreID'] 			= 	$StoreID;
	$arrSetting['booker_id'] 		= 	$_POST['booker_id'] ? $_POST['booker_id'] : 0;
	$arrSetting['email'] 			= 	$email;
	$arrSetting['name'] 			= 	$name;
	$arrSetting['phone'] 			= 	$phone;
	$arrSetting['quantity'] 		= 	$quantity;
	$arrSetting['reservation_date'] = 	$reservation_date;
	$arrSetting['comments'] 		= 	$_POST['comments'];
	$arrSetting['book_date'] 		= 	time();
	
	if (!$dbcon-> insert_record($GLOBALS['table']."book", $arrSetting)) {
		$msg = $dbcon -> _errorstr;
	} else {
		$msg = 'Book Successful.';
	}
}
?>



<html>
<head>
<title>Book Online</title>
<LINK href="/skin/red/css/global.css" type=text/css rel=stylesheet> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
</head>

<body>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3">&nbsp;<br /><br /></td>
    </tr>

    <tr> 
      <td valign="top" colspan="3" align="center"> 
      <p class="txt"><font color="#FF0000">
        <?=$msg ; $msg=""?>
      </font></p>
    </tr>

  <tr> 
      <td colspan="3" align="center"><a href="#" onClick="window.close()">Close window</a>&nbsp;</td>
  </tr>
  </table>
</body>
</html>
<?php 
include_once "include/session.php" ;
include_once "include/config.php" ;
include_once "include/maininc.php" ;
include_once "include/functions.php" ;

if($_SERVER['HTTP_HOST']=="usa.buyblitz.com"){
	$path	=	"http://".$_SERVER['HTTP_HOST'];
}else if ($_SERVER['HTTP_HOST']=="mercury.myserverhosts.com"){
	$path	=	"http://".$_SERVER['HTTP_HOST']."/~buyblitz/";
}else if ($_SERVER['HTTP_HOST']=="dev.infinitytech.cn"){
	$path	=	"http://".$_SERVER['HTTP_HOST']."/php/buyblitz/";
}else{
	$path	=	"http://".$_SERVER['HTTP_HOST']."/buyblitz_usa";
}

// form item submit to paypal
/*
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="info@buyblitz.com">
<input type="hidden" name="item_name" value="Deposit money in your account">                                                   
<input type="hidden" name="amount" value="365"> 
<input type="hidden" name="currency_code" value="AUD"> 
<input type="hidden" name="item_number" value="<?=$_SESSION['StoreID']?>">
<input type="hidden" name="StoreID" value="<?=$_SESSION['StoreID']?>"> 
<input type="hidden" name="return" value="http://usa.buyblitz.com/activate.php">
<input type="hidden" name="cancel_return" value="http://usa.buyblitz.com/pay_reports.php">
<input type="hidden" name="notify_url" value="http://usa.buyblitz.com/activate.php">
<input name="submit" type="submit" class="greenButt" value="Pay Now" />
*/

extract($_REQUEST);
if ($cmd == '_notify-validate'){
	echo 'VERIFIED';
	exit;
}

?>
<html>
<head>
<title><?=$item_name?></title>
</head>
<body>
<table>
	<form method=post action="<?=$_REQUEST['return']?>">
<input type="hidden" name="business" value="<?=$_REQUEST['business']?>">
<input type="hidden" name="item_name" value="<?=$_REQUEST['item_name']?>">                                                   
<input type="hidden" name="amount" value="<?=$_REQUEST['amount']?>"> 
<input type="hidden" name="mc_gross" value="<?=$_REQUEST['amount']?>"> 
<input type="hidden" name="currency_code" value="AUD"> 
<input type="hidden" name="item_number" value="<?=$_REQUEST['item_number']?>">
<input type="hidden" name="StoreID" value="<?=$_SESSION['StoreID']?>"> 
<input type="hidden" name="return" value="<?=$_REQUEST['return']?>">
<input type="hidden" name="cancel_return" value="<?=$_REQUEST['cancel_return']?>">
<input type="hidden" name="notify_url" value="<?=$_REQUEST['notify_url']?>">
	<tr>
		<td><?=$item_name?></td>
	</tr>
	<tr>
		<td>Confirm to pay amount: <?=$amount.$currency_code?></td>
	</tr>
	<tr>
		<td><input type=submit name=submit value="Process"> &nbsp; <input type=button name=return value=return onClick="location.href='<?=$cancel_return?>'"></td>
	</tr>
	</form>
</table>
</body>
</html>

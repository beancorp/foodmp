<?php
@session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified:   " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");     //   HTTP/1.1
header ("Pragma: no-cache");
include_once ('include/config.php');
include_once ('include/smartyconfig.php');
include_once ('maininc.php');
include_once ('class.soc.php');
include_once ('class/pagerclass.php');
include_once ('functions.php');
$StoreID = $_SESSION['ShopID'];
$orderID = $_REQUEST['orderid'];
if(!$StoreID || !$orderID){
	header('Location: /soc.php?cp=home');
}
$socObj = new socClass();
$req = $socObj->invoicePrint($StoreID,$orderID);
if (!$req){
	echo "<script> alert('Failed to get invoice information.');window.close();</script>";
	exit;
}
$req['print'] = 1;
$smarty -> assign('req',$req);
$smarty -> display('invoice_print.tpl');
?>
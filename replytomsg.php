<?php

ob_start();
include_once "include/session.php" ;
include_once ('include/config.php');
include_once ('include/smartyconfig.php');
include_once "maininc.php" ;
include_once ('class.emailClass.php');
include_once ('functions.php');

$StoreID = $_SESSION['StoreID'];

$rsLQ = mysql_query("select * from ".$GLOBALS['table']."bu_detail where StoreID = $StoreID ") ;
$rslBU = mysql_fetch_object($rsLQ) ;


$businessName		=	$_SESSION['UserName'];
$to			=	$_REQUEST['emailaddress'];
$thebodyofmessage	=	$_REQUEST['body'];
$thesubject 		=	$_REQUEST['subject'];
$date			=	time();
$thestoreid 		=	$_REQUEST['StoreID'];
$fromtoname			=	$_REQUEST['fromtoname'];
$pid 				=   $_REQUEST['pid'];

$query = "SELECT * FROM  {$GLOBALS['table']}bu_detail WHERE StoreID='{$thestoreid}'";
$dbcon->execute_query($query);
$result = $dbcon->fetch_records(true);
if($result){
	echo '<pre>';
	print_r($result);
	echo '</pre>';
	$isonlyemail = false;
	if($result[0]['attribute']==4){
		$isonlyemail = true;
	}elseif($result[0]['attribute']==3&&$result[0]['subAttrib']==3){
		$isonlyemail = true;
	}else{
		$isonlyemail = false;
	}
	
	if($isonlyemail){
			$arrParams	=	array(
			'display'			=>	'reply',
			'To'				=>	$to,
			'Subject'			=>	"SOCExchange.com.au (Email from $rslBU->bu_name)",
			'buyer_nickname'	=>	get_magic_quotes_gpc()?stripslashes($fromtoname):$fromtoname,
			'thebodyofmessage'	=>	get_magic_quotes_gpc()?stripslashes($thebodyofmessage):$thebodyofmessage,
			'seller_nickname'	=>	$rslBU->bu_name,
			'webside_link'		=>	'http://'.$_SERVER['HTTP_HOST']."/".getStoreByURL($StoreID)
			);
			
			$objEmail	=	new emailClass();
			if($objEmail -> send($arrParams,'email_contact_seller.tpl')){
				$msg = "Your reply has now been sent.";
			}else{
				$msg = $objEmail -> msg;
			}
			unset($objEmail);
	}else{
		$query		=	"insert into ".$GLOBALS['table']."message_out(subject,message,StoreID,date,emailaddress,fromtoname,pid) values('Re:$thesubject','$thebodyofmessage','$thestoreid','$date','$to','$fromtoname','$pid')";
	
		if($dbcon->execute_query($query)) {
			/* additional headers */
			//$headers .= "From: mail@TheSOCExchange.com\r\n";
			if($result[0]['outerEmail']){
				$arrParams	=	array(
				'display'			=>	'reply',
				'To'				=>	$to,
				'Subject'			=>	"SOCExchange.com.au (Email from $rslBU->bu_name)",
				'buyer_nickname'	=>	get_magic_quotes_gpc()?stripslashes($fromtoname):$fromtoname,
				'thebodyofmessage'	=>	get_magic_quotes_gpc()?stripslashes($thebodyofmessage):$thebodyofmessage,
				'seller_nickname'	=>	$rslBU->bu_name,
				'webside_link'		=>	'http://'.$_SERVER['HTTP_HOST']."/".getStoreByURL($StoreID)
				);
				
				$objEmail	=	new emailClass();
				if($objEmail -> send($arrParams,'email_contact_seller.tpl')){
					$msg = "Your reply has now been sent.";
				}else{
					$msg = $objEmail -> msg;
				}
				unset($objEmail);
			}
		} else { 
			$msg = "error on sending"  ;
		
		}
	}
}else{
	$msg = "error on sending"  ;
}

header("Location:replytomsgconfirm.php?msg=$msg");

?> 
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
$to					=	$_REQUEST['emailaddress'];
$thebodyofmessage	=	$_REQUEST['body'];
$thesubject 		=	$_REQUEST['subject'];
$fromPhone 			=	$_REQUEST['fromPhone'];
$fromName 			=	$_REQUEST['fromName'];
$date				=	time();
$thestoreid 		=	$_REQUEST['StoreID'];
$fromEmail			=	$_REQUEST['fromEmail'];
$fromtoname			=	$_REQUEST['fromtoname'];
$pid 				=   $_REQUEST['pid'];

$query = "SELECT * FROM  {$GLOBALS['table']}bu_detail WHERE StoreID='{$thestoreid}'";
$dbcon->execute_query($query);
$result = $dbcon->fetch_records(true);
if($result){
	$isonlyemail = false;
	if($result[0]['attribute']==4 || $_REQUEST['GuestID']){
		$isonlyemail = true;
	}elseif($result[0]['attribute']==3&&$result[0]['subAttrib']==3){
		$isonlyemail = true;
	}else{
		$isonlyemail = true;
	}
	
	if($isonlyemail){
			$arrParams	=	array(
				'display'			=>	'contbuyer',
				'To'				=>	$to,
				'Subject'			=>	str_replace("''","'",$thesubject),
				'buyer_nickname'	=>	get_magic_quotes_gpc()?stripslashes($fromtoname):$fromtoname,
				'fromPhone'			=>	str_replace("''","'",$fromPhone),
				'fromName'			=>	Input::StripString(str_replace("''","'",$fromName)),
				'fromEmail'			=>	str_replace("''","'",$fromEmail),
				'message'			=>	str_replace("''","'",$thebodyofmessage),
				'seller_nickname'	=>	$rslBU->bu_name,
				'webside_link'		=>	'http://'.$_SERVER['HTTP_HOST']."/".getStoreByURL($StoreID),
				'cusType'			=>	'buyer',
				'email_regards'		=>  'SOC exchange Australia'
			);
			
			$objEmail	=	new emailClass();
			if($objEmail -> send($arrParams,'email_contact_seller.tpl')){
				$msg = "Your email has now been sent.";
			}else{
				$msg = $objEmail -> msg;
			}
			unset($objEmail);
	}else{		
		$arrSetting	=	array(
			"subject"		=>	$thesubject,
			"message"		=>	$thebodyofmessage,
			"phone"			=>	$fromPhone,
			"StoreID"		=>	$thestoreid,
			"date"			=>	time(),
			"emailaddress"	=>	$fromEmail,
			"fromtoname"	=>	$fromName,
			"pid"			=>  0
		);
	
		if($dbcon->insert_record($GLOBALS['table']."message", $arrSetting)) {
			/* additional headers */
			//$headers .= "From: mail@TheSOCExchange.com\r\n";
			$arrParams	=	array(
				'display'			=>	'conseller',
				'To'				=>	$to,
				'Subject'			=>	'Message Alert From SOC Exchange',
				'seller_nickname'	=>	$result[0]['bu_name'],
				'webside_link'		=>	'http://'.$_SERVER['HTTP_HOST']
			);
			$objEmail	=	new emailClass();
			$objEmail -> send($arrParams,'email_contact_seller.tpl');
				
			if($result[0]['outerEmail']){
				$arrParams	=	array(
					'display'			=>	'consubscriber_detail',
					'To'				=>	$to,
					'Subject'			=>	str_replace("''","'",$thesubject),
					'fromPhone'			=>	str_replace("''","'",$fromPhone),
					'fromName'			=>	Input::StripString(str_replace("''","'",$fromName)),
					'fromEmail'			=>	str_replace("''","'",$fromEmail),
					'buyer_nickname'	=>	get_magic_quotes_gpc()?stripslashes($fromtoname):$fromtoname,
					'message'			=>	str_replace("''","'",$thebodyofmessage),
					'seller_nickname'	=>	$rslBU->bu_name,
					'webside_link'		=>	'http://'.$_SERVER['HTTP_HOST']."/".getStoreByURL($StoreID),
					'cusType'			=>	'buyer'
				);
				
				$objEmail	=	new emailClass();
				if($objEmail -> send($arrParams,'email_contact_seller.tpl')){
					$msg = "Your email has now been sent.";
				}else{
					$msg = $objEmail -> msg ;
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
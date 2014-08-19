<?php

ob_start();
include_once "include/session.php";
include_once "include/config.php" ;

include_once "include/maininc.php" ;

include_once "include/functions.php" ;

include_once "include/smartyconfig.php";

include_once "include/class.guestEmailSubscriber.php";

$StoreID = $_SESSION['StoreID'];

$rsLQ = mysql_query("select * from ".$GLOBALS['table']."bu_detail where StoreID = $StoreID ") ;
$rslBU = mysql_fetch_object($rsLQ) ;

$goods = 'Items';

$businessName	=	$rslBU->bu_name;
$bu_urlstring 	= $rslBU->bu_urlstring;

//query for email alert user
$QUERY	=	"SELECT * FROM  ".$GLOBALS['table']."emailalert WHERE  StoreID = '".$_SESSION['StoreID']."'";
$result	=	$dbcon->execute_query($QUERY) ;
$grid 	= $dbcon->fetch_records() ;

//query for guest email user
$guestSub = new guestEmailSubscriber();
$subscribersGuests = $guestSub->getGuestSubscriberListByStore($_SESSION['StoreID']);

//query for products of seller
$strtable = "";
switch ($rslBU->attribute){
	case 0:
		$QUERY2	=	"SELECT p.*,t.* FROM  ".$GLOBALS['table']."product p left join ".$GLOBALS['table']."image t on p.StoreID = t.StoreID and  p.pid=t.pid and t.attrib=0 and t.sort=0 left join {$GLOBALS['table']}product_auction au on au.pid=p.pid WHERE p.StoreID = '".$_SESSION['StoreID']."' AND IF(p.is_auction='yes',au.end_stamp>".time().",'1=1')  AND p.deleted<>'YES'";
		$smarty->assign('type','store');
		break;
	case 1:
		$QUERY2	=	"SELECT p.*,t.* FROM  ".$GLOBALS['table']."product_realestate p left join ".$GLOBALS['table']."image t on p.StoreID = t.StoreID and  p.pid=t.pid and t.attrib=0 and t.sort=0 WHERE p.StoreID = '".$_SESSION['StoreID']."' AND p.deleted<>1";
		$smarty->assign('type','estate');
		//estate
		break;
	case 2:
		$QUERY2	=	"SELECT p.*,t.* FROM  ".$GLOBALS['table']."product_automotive p left join ".$GLOBALS['table']."image t on p.StoreID = t.StoreID and  p.pid=t.pid and t.attrib=0 and t.sort=0 WHERE p.StoreID = '".$_SESSION['StoreID']."' AND p.deleted<>'1'";
		$smarty->assign('type','auto');
		$smarty -> loadLangFile('auto/index');
		//auto
		break;
	case 3:
		$QUERY2	=	"SELECT * FROM  ".$GLOBALS['table']."product_job WHERE StoreID = '".$_SESSION['StoreID']."' AND deleted<>'1' AND ((datePosted <= '$current_date' or datePosted='0000-00-00') AND (closingDate >= '$current_date' or closingDate='0000-00-00'))";
		$smarty->assign('type','job');
		$smarty -> loadLangFile('job/index');
		//job
		break;
	case 5:		
		header("Location:/foodwine/?act=emailalerts");
		//food&wine
		exit();
		break;
}

$result2	=	$dbcon->execute_query($QUERY2) ;
$grid2 = $dbcon->fetch_records() ;

$customname = "";
//common use
if($grid){
	$size1 = sizeof($grid);
}else{
	$size1 = 0;
}

if($subscribersGuests)
{
    $size1= $size1+sizeof($subscribersGuests);
}

/** MAIL GUEST SUBSCRIBERS **/
for($i=0;$i<sizeof($subscribersGuests);$i++)
{
    	$to 		= $subscribersGuests[0]['email'];  // note the comma
	$customname = $subscribersGuests[0]['nickname'];
	
	$smarty->assign('goods',$goods);
	$smarty->assign('home_link',PAYPAL_SITEURL);
	$smarty->assign('bu_urlstring',$bu_urlstring);
	$smarty->assign('customer',$customname);
	$smarty->assign('business',$businessName);
	$smarty->assign('y',1);
	$smarty->assign('k',1);
	if(is_array($subscribersGuests)){
		$smarty->assign('procunsts',count($subscribersGuests));
		$smarty->assign('products',$subscribersGuests);
	}else{
		$smarty->assign('procunsts',0);
		$smarty->assign('products',array());
	}
	$smarty->assign('email_regards',$email_regards);
	$message = $smarty->fetch('emailalerts.tpl');
	$message = getEmailTemplate($message);
	
	/* subject */
	$subject = "Items alert from $businessName";

	/* To send HTML mail, you can set the Content-type header. */
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	/* additional headers */
	//$headers .= "To: ".$to." \r\n";
	$headers .= "From: mail@TheSOCExchange.com\r\n";

	/* and now mail it */
	mail($to, $subject, $message, FixEOL($headers));
}


for($i=0;$i<sizeof($grid);$i++){

	$_query 	= "SELECT l.user,b.bu_nickname,b.bu_urlstring FROM  ".$GLOBALS['table']."login l, ".$GLOBALS['table']."bu_detail b WHERE l.StoreID = b.StoreID and l.id=".$grid[$i]['userid'];
	$userinfo 	= $dbcon->execute_query($_query);
	$user		= $dbcon->fetch_records(true);
	$to 		= $user[0]['user'];  // note the comma
	$customname = $user[0]['bu_nickname'];
	
	
	$smarty->assign('goods',$goods);
	$smarty->assign('home_link',PAYPAL_SITEURL);
	$smarty->assign('bu_urlstring',$bu_urlstring);
	$smarty->assign('customer',$customname);
	$smarty->assign('business',$businessName);
	$smarty->assign('y',1);
	$smarty->assign('k',1);
	if(is_array($grid2)){
		$smarty->assign('procunsts',count($grid2));
		$smarty->assign('products',$grid2);
	}else{
		$smarty->assign('procunsts',0);
		$smarty->assign('products',array());
	}
	$smarty->assign('email_regards',$email_regards);
	$message = $smarty->fetch('emailalerts.tpl');
	$message = getEmailTemplate($message);
	
	/* subject */
	$subject = "Items alert from $businessName";

	/* To send HTML mail, you can set the Content-type header. */
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	/* additional headers */
	//$headers .= "To: ".$to." \r\n";
	$headers .= "From: mail@TheSOCExchange.com\r\n";

	/* and now mail it */
	mail($to, $subject, $message, FixEOL($headers));

}

$msg  =	"You have sent your " . strtolower($goods) . " to " . $size1 ." subscribers.";
return_home($msg);

//return when success or faile
function return_home($msgs){
	header("Location:soc.php?cp=inbox&msg=$msgs");
	exit;
}
?> 
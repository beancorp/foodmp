<?php
@session_start();
include_once ('include/config.php');
include_once ('include/smartyconfig.php');
include_once ('maininc.php');
include_once ('class.emailClass.php');
include_once ('class.soc.php');
include_once ('class/pagerclass.php');
include_once ('functions.php');
include_once ('class.invitations.php');
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 1)) {
	header('Location:soc.php?cp=home');
	exit();
}
if ($_SESSION['attribute']==3&&$_SESSION['subAttrib']==3) {
	header('Location:soc.php?cp=home');
	exit();
}

$invation = new invitations();
$socObj = new socClass();

$smarty -> assign('pageTitle','Wish List - Invitations @ SOC Exchange');
switch ($_REQUEST['cp']):
	case 'list':
		include_once ('xajax/xajax_core/xajax.inc.php');
		function invationlist($page,$sid){
			$objResponse 	= new xajaxResponse();
			$invation = new invitations();
			$invation -> smarty -> assign('PBDateFormat',DATAFORMAT_DB);
			$req = $invation->getinvationsByStore($sid,$page);
			$invation -> smarty -> assign('req',	$req);
			$content = $invation -> smarty -> fetch('invitations/invitations_list.tpl');
			$objResponse -> assign("refcontent",'innerHTML',$content);
			return $objResponse;
		}
		$xajax 		= new xajax();
		$xajax -> registerFunction('invationlist');
		$xajax -> processRequest();
		$req = $invation->getinvationsByStore($_SESSION['ShopID'],1);//get invations
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB);
		$smarty -> assign('req',$req);
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Invites History', 8,'','/soc.php?act=invitations'));
		$content = $smarty -> fetch('invitations/invitations.tpl');
	break;
	case 'view':
		$textInfo = $invation->getInvationById($_SESSION['ShopID'],$_REQUEST['id']);
		$sellerInfo = $invation->get_SellerInfo($_SESSION['ShopID']);
		if(!$textInfo){
			echo "Invalid Invites History.";
			exit();
		}
		$textInfo = $textInfo[0];
		$tempid = $textInfo['email_template'];
		if($tempid==1||$tempid==2){
			$usertmp = $invation->getInvationUserTPL($_SESSION['ShopID']);
			$_REQUEST['usertpl_img'] = $usertmp['template'];
			$_REQUEST['template_type'] = 'user';
		}
		$TempInfo = $invation->getTemplate($tempid);
		echo $invation->previewHTML(array_merge($textInfo,$sellerInfo),$TempInfo['Images'],$TempInfo['Info']);
		exit();
	break;
	default:
	break;
endswitch;
$smarty -> assign('content', $content);
$smarty -> assign('sidebar', 0);

$smarty -> assign('sidebar', 0);
$smarty -> assign('search_type',$search_type);
$smarty->assign("is_content",1);
include('leftmenu.php');
include_once('soc/seo.php');
$smarty -> assign('act', $_REQUEST['act']);
$smarty->assign('modcp','wishlist');
$smarty -> display('index.tpl');
?>
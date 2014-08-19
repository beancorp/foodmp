<?php
@session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');
include_once ('class.adminpromotion.php');
require_once ('Pager/Pager.php');
include_once ('xajax/xajax_core/xajax.inc.php');

//check login
$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}
$smarty -> loadLangFile('soc');

$objAdminPromotion = new adminPromotion();
$xajax 			= new xajax();

//control
switch($_REQUEST["cp"]){
	default:
		$req['header']	=	"Promotion Code";
		$xajax -> registerFunction('getPromotionList');
		$xajax -> registerFunction('deletePromotion');
		$xajax -> registerFunction('getPromotionById');
		$xajax -> registerFunction('createPromot');
		$xajax -> registerFunction('editPromot');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['promotlist'] = $objAdminPromotion -> getPromotionList();
		$smarty	-> assign('req',$req);
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB);
		$content = $smarty -> fetch('admin_promotion.tpl');
		$smarty -> assign('content', $content);
		break;
}
$req['Menu']["promotion"] = "style='color:#FF0000;font-weight:bold;'";
$smarty -> assign('req',	$req);
unset($objAdminPromotion,$req,$xajax);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
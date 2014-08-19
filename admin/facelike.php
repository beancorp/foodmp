<?php
@session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');
include_once ('class.soc.php');
include_once ('class.adminfacelike.php');
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

$objAdminFacelike 	= new adminFacelike();
$xajax 			= new xajax();

//control
switch($_REQUEST["cp"]){
	case 'records':
		$socObj = new socClass();
		$store_info = $socObj->getStoreInfo($_REQUEST["StoreID"]);
		$req['header']	=	"{$store_info['bu_name']}'s Facebook Sprints Records";
		$xajax -> registerFunction('getFacelikeRecords');
		$xajax -> registerFunction('getFacelikeRecordsSearch');
		$xajax -> registerFunction('getSuburbList');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		
		$req['list'] 	=	$objAdminFacelike -> getFacelikeRecords();
		$req['state'] 	=	$objAdminFacelike -> getStateList();
		$req['order'] = $_REQUEST['order'];
		$req['field'] = $_REQUEST['field'];
		$req['StoreID'] = $_REQUEST['StoreID'];
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_facelike_records.tpl');
		$smarty -> assign('content', $content);
		break;
	default:
		$req['header']	=	"Report for Facebook Sprints";
		$xajax -> registerFunction('getFacelikeList');
		$xajax -> registerFunction('getFacelikeListSearch');
		$xajax -> registerFunction('getSuburbList');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		
		$req['list'] 	=	$objAdminFacelike -> getFacelikeList();
		$req['state'] 	=	$objAdminFacelike -> getStateList();
		$req['order'] = $_REQUEST['order'];
		$req['field'] = $_REQUEST['field'];
		$smarty -> assign('req',	$req);
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB." %H:%M");
		$content = $smarty -> fetch('admin_facelike.tpl');
		$smarty -> assign('content', $content);
		break;
}
$req['Menu']["facelike"] = "style='color:#FF0000;font-weight:bold;'";
$smarty -> assign('req',	$req);
unset($objAdminReport,$req,$xajax);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
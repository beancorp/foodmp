<?php
/**
 * Sun Oct 12 09:13:34 GMT+08:00 2008 09:13:34
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * review control
 * ------------------------------------------------------------
 * admin\review.php
 */


@session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');
include_once ('class.adminreview.php');
require_once 'Pager/Pager.php';
include_once ('xajax/xajax_core/xajax.inc.php');


//check login
$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}

$objAdminReview	= new adminReview();
$xajax 			= new xajax();

//control
switch($_REQUEST["cp"]){
	case 'expset':
		$req['header']	=	$objAdminReview->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('saveReviewsExpriy');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['list'] =	$objAdminReview -> getReviewsExpriy();
		$req['display']	= $_REQUEST["cp"];
		$smarty -> assign('req',	$req);
		
		$content = $smarty -> fetch('admin_review.tpl');
		$smarty -> assign('content', $content);
		break;
		
	case 'details':
		$req['header']	=	$objAdminReview->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('getReviewsList');
		$xajax -> registerFunction('reviewsSearch');
		$xajax -> registerFunction('reviewsUpdate');
		$xajax -> registerFunction('reviewsDelete');
		$xajax -> registerFunction('reviewsUpdateOption');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['list'] =	$objAdminReview -> getReviewsList();
		$req['display']	= $_REQUEST["cp"];

		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_review.tpl');
		$smarty -> assign('content', $content);
		break;
	
}
$req['Menu']["{$_REQUEST["cp"]}"] = "style='color:#FF0000;font-weight:bold;'";
$smarty -> assign('req',	$req);
unset($objAdminReview,$req,$xajax,$objLogin);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
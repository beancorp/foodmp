<?php

session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');
include_once ('class.adminfanfrenzy.php');

require_once 'Pager/Pager.php';
include_once ('xajax/xajax_core/xajax.inc.php');

$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}
$dbcon = $GLOBALS['dbcon'];
$objAdminMain 	= new adminfanfrenzy();
$xajax 			= new xajax();

$xajax -> registerFunction('displayCMSItem');
$xajax -> registerFunction('saveCMSItem');
$xajax -> processRequest();
$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');

$req['cmslist'] =	$objAdminMain -> getCMSItemName();

$req['input']	=	$objAdminMain -> getCMSItemEdit();
$smarty -> assign('req',	$req);
$content = $smarty -> fetch('admin_fanfrenzy.tpl');
$smarty -> assign('content', $content);

$smarty -> assign('req',	$req);

unset($objAdminMain,$req,$xajax);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
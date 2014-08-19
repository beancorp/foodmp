<?php

/**
 * Wed Oct 08 14:07:36 GMT+08:00 2008 14:07:36
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * admin login
 * ------------------------------------------------------------
 * \admin\login.php
 */
//ini_set('display_errors', '1');
//error_reporting(E_ALL);
@session_start();
include_once ('../include/smartyconfig.php');
include_once ('maininc.php');
include_once ('class.login.php');
include_once ('xajax/xajax_core/xajax.inc.php');

$objLogin = new login();
if (isset($_REQUEST["cp"]) && $_REQUEST["cp"] == 'logout') {
	$objLogin->logout();
	unset($objLogin,$smarty);
	header('Location: ./');
	exit;
} else {
	if ($objLogin->checkLogin()) {
		header('Location: ./?act=main');
	}else{
		$xajax = new xajax();
		$xajax -> registerFunction('userLogin');
		$xajax -> processRequest();
		
		$req	=	$objLogin -> userLoginPage();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');

		$smarty -> assign('req',	$req);
		$smarty -> display('login.tpl');
	}

	unset($req,$smarty,$objLogin);
	exit;
}
unset($req,$smarty,$objLogin);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
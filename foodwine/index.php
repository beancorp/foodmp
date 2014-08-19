<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//@session_start();
require_once(dirname(dirname(__FILE__)) . '/include/maininc.php');
require_once(SOC_INCLUDE_PATH . '/session.php');
require_once(SOC_INCLUDE_PATH . '/smartyconfig.php');
require_once(SOC_INCLUDE_PATH . '/class.soc.php');
require_once(SOC_INCLUDE_PATH . '/class.uploadImages.php');
require_once (SOC_INCLUDE_PATH . '/class.FoodWine.php');
$foodWine = new FoodWine();
$socObj = new socClass();

if (isset($_REQUEST['referr']) && (!empty($_REQUEST['referr']))) {
	setcookie('cookieRefer', $_REQUEST['referr'], time()+604800);
}

if (isset($_GET['suburb'])) {
	$smarty->assign('preselect_suburb',strip_tags($_GET['suburb']));
}

$smarty -> assign('securt_url',SOC_HTTPS_HOST);
$smarty -> assign('normal_url',SOC_HTTP_HOST);
$smarty->assign('noShowGalleryBanner', true);
$smarty->assign('noShowTvBanner', true);


$indexTemplate	=	'../'.$template_tpl;
		
$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
$act = $_REQUEST['act'] ? $_REQUEST['act'] : 'search';
require(dirname(__FILE__) . '/' . $act . '.php');


$smarty->assign('is_content',1);
$search_type	=	'foodwine';
$partBottom 	=	true;
include('../indexPart.php');
exit;
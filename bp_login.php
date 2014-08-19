<?php
include_once ('include/config.php');
@session_start();
include_once "include/session.php" ;
include_once ('include/smartyconfig.php');
include_once ('include/maininc.php');
include_once "include/functions.php" ;
include_once ('include/class.soc.php');
include_once ('include/class.point.php');
$smarty -> loadLangFile('threeSeller');
$smarty -> loadLangFile('soc');

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - SOC Exchange');
$smarty -> assign('keywords',$keywordsList);
$smarty -> assign('description', 'The SOC Exchange involves selling online all your products in an online trading post. No matter how much you sell, we use flat rate selling.');
$socObj = new socClass();
$states = $socObj->getStatesList();
$smarty -> assign('statesList',	$states);

if ($_REQUEST['from']) {
	$refer = urldecode($_REQUEST['from']); 
} else {
	//Get the partner website domain
	$refer=$_SERVER['HTTP_REFERER'];
}

$str   = str_replace("http://","",$refer);  
$strdomain = explode("/",$str);          
$domain    = $strdomain[0]; 	

$_SESSION['bp_domain'] = $domain;
//$_SESSION['bp_domain'] = 'www.test2.com';

if (!empty($_SESSION)) {
$_SESSION['website'] = $_SESSION['urlstring'];
$smarty -> assign('session', $_SESSION);
}
if ($_SESSION['attribute'] == 4 || ($_SESSION['attribute'] == 3 && $_SESSION['subAttrib'] == 3)) {
	$msg = "The Ultimate SOC Race is open to those who list items on 'SOC exchange'. It's FREE to list, so join the race for the cash today!";
	header("Location:/showmessage.php?msg=".urlencode($msg));
    exit;
}

$objPoint = new Point();

if (empty($domain)) {
	$msg = "Please come from the partner's website.";
	header("Location:/showmessage.php?msg=".urlencode($msg));
    exit;
}

$req['site_info'] = $objPoint->getSiteInfo($domain);
if (!$req['site_info']['status']) {
	$msg = "There is not question for this partner's website.";
	header("Location:/showmessage.php?msg=".urlencode($msg));
    exit;
}

if(!empty($_SESSION['bp_login'])){
	header("Location: /bp_question.php");
	exit();
}

$req['from'] = urlencode($refer);

$pageTitle = 'SOC Race Bonus Point Question';
$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - SOC Race Bonus Point Question');
$smarty -> assign('keywords','SOC Race Bonus Point Question');
$smarty->assign('req', $req);
$smarty->assign('is_home',1);
$smarty -> assign('sidebar_bg', '0');

/**
 * added by Kevin, 2011-08-5
 * mark of top menu type
 */
$smarty->assign('hideTopTypeMenu', true);
$smarty->assign('footer',  footer());
$smarty -> assign('main_page',1);
$smarty->display('bp_login.tpl');

exit;

?>

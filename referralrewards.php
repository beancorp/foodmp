<?php
//ini_set('display_errors', '1');
//error_reporting(E_ALL);
include_once ('include/config.php');
if (!(SHOW_REWARDS_BANNER===true)) { header('location: '.SOC_HTTP_HOST); exit; }
@session_start();
include_once('include/smartyconfig.php');
include_once('class.team.php');
include_once('class.point.php');

include_once('maininc.php');
include_once('class.soc.php');
include_once('functions.php');
require_once('class.socstore.php');

$socObj = new socClass();
$socstoreObj = new socstoreClass();

$referral_information_page = $socObj->displayPageFromCMS(126);
$smarty->assign('referral_information_page', $referral_information_page['aboutPage']);

$referral_tutorial_page = $socObj->displayPageFromCMS(128);
$smarty->assign('referral_tutorial_page', $referral_tutorial_page['aboutPage']);

$commission_text = $socObj->displayPageFromCMS(124);
$smarty->assign('commission_text', $commission_text['aboutPage']);

if (isset($_GET['terms'])) {
	$smarty->assign('show_terms', true);
	$terms_page = $socObj->displayPageFromCMS(127);
	$smarty->assign('terms_page', $terms_page['aboutPage']);
}

function signup_commission($amount, $percentage) {
	$value = floatval($amount*$percentage/100);
	return (ceil($value/5) * 5);
}

$ref_configs = $socObj->getRefconfig();
$commission_percentage = $ref_configs['percent'];

$signup_retailer = signup_commission(365, $commission_percentage);
$signup_link = signup_commission(250, $commission_percentage);

$smarty->assign('commission_percentage', $commission_percentage);
$smarty->assign('signup_retailer', $signup_retailer);
$smarty->assign('signup_link', $signup_link);

$query	= "SELECT id, stateName, description FROM aus_soc_state ORDER BY description";
$result	= $dbcon->execute_query($query);
$state_list = $dbcon->fetch_records();
$suburb_data = array();

foreach($state_list as $state) {
	$query	= "SELECT suburb_id, suburb FROM aus_soc_suburb WHERE state = '".$state['stateName']."' ORDER BY suburb ASC";
	$result	= $dbcon->execute_query($query);
	$suburbs = $dbcon->fetch_records();
	$output = '';
	foreach($suburbs as $suburb) {
		$output .= '<option value="'.addslashes($suburb['suburb']).'">'.addslashes($suburb['suburb']).'</option>';
	}
	$suburb_data[$state['stateName']] = $output;
}

$smarty->assign('state_list', $state_list);
$smarty->assign('suburb_data', $suburb_data);

$smarty->assign('pageTitle','Referral Rewards');
$smarty->assign('contentStyle', 'float: left;width: 930px;padding: 0px; margin: 0px;');
$content = $smarty->fetch('referralrewards/referralrewards.tpl');
$smarty->assign('sidebar', 0);
$smarty->assign('hideLeftMenu', 1);
$smarty->assign('show_left_cms', 0);
$smarty -> assign('content', $content);
$smarty->assign('is_content',1);
$smarty->assign('session', $_SESSION);
$smarty->assign('ocp', 'signup');
$smarty -> display($template_tpl);
unset($smarty);
?>
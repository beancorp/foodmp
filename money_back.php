<?php
die('no longer available');
exit;
//ini_set('display_errors', '1');
//error_reporting(E_ALL);
include_once ('include/config.php');
@session_start();
include_once('include/smartyconfig.php');
include_once('maininc.php');
include_once ('class.soc.php');

include_once(dirname(__FILE__) . '/languages/'.LANGCODE.'/soc.php');
include_once(dirname(__FILE__) . '/languages/'.LANGCODE.'/foodwine/index.php');

$lang = $_LANG;

$state_query = "SELECT id, stateName as state, description FROM aus_soc_state ORDER BY description";
$dbcon->execute_query($state_query);
$state_list = $dbcon->fetch_records();

$suburb_query = "SELECT suburb_id, suburb as bu_suburb, zip FROM aus_soc_suburb ORDER BY suburb ASC";
$dbcon->execute_query($suburb_query);
$suburb_list = $dbcon->fetch_records();

$search = array();
$search['search_states'] = $state_list;
$search['cities'] = $suburb_list;
$smarty->assign('search', $search);

$smarty->assign('lang', $lang);
$smarty->assign('cuisine', $cuisine);

$socObj = new socClass();

$money_back_content = $socObj->displayPageFromCMS(130);
$smarty->assign('money_back_content', $money_back_content['aboutPage']);

$smarty->assign('pageTitle','Money Back');
$sidebarContent = '
<div style="float:left; padding-left:5px;">
	<a href="'.SOC_HTTPS_HOST.'/soc.php?cp=foodwine">
		<img width="180" height="250" src="/skin/red/images/foodmp_about.jpg">
	</a>
</div>
<div style="float:left;">
	<a href="'.SOC_HTTPS_HOST.'/registration.php">
		<img width="200" height="360" alt="Retailers Join Today" src="/skin/red/images/banner/retailers_join_today.jpg">
	</a>
</div>';
$smarty->assign('sidebarContent', $sidebarContent);
$content = $smarty->fetch('money_back_page.tpl');

$smarty -> assign('content', $content);
$smarty->assign('is_content',1);
$smarty->assign('session', $_SESSION);
$smarty -> display($template_tpl);
unset($smarty);
?>
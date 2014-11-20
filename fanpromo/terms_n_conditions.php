<?php
@session_start();

include_once ('../include/config.php');
include_once ('../include/smartyconfig.php');
include_once ('functions.inc.php');
include_once ('maininc.php');
include_once ('class.soc.php');

include_once('../languages/'.LANGCODE.'/soc.php');
include_once('../languages/'.LANGCODE.'/foodwine/index.php');

$smarty->assign('terms_page', tab_content($dbcon, 5));
$smarty->assign('page_group', "fanpromo");
$smarty->assign('hide_responsive', true);
display_page($dbcon, $smarty, 'terms_n_conditions.tpl', 'Terms and Conditions', $_LANG);
unset($smarty);
?>
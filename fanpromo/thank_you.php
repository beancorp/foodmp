<?php
@session_start();

include_once ('../include/config.php');
include_once ('../include/smartyconfig.php');
include_once ('functions.inc.php');
include_once ('maininc.php');
include_once ('class.soc.php');

include_once('../languages/'.LANGCODE.'/soc.php');
include_once('../languages/'.LANGCODE.'/foodwine/index.php');

$smarty->assign('thank_you_message', "Thank You!");

$smarty->assign('about_text', tab_content($dbcon, 1));
$smarty->assign('how_to_enter', tab_content($dbcon, 2));
$smarty->assign('how_it_works', tab_content($dbcon, 3));
$smarty->assign('thank_you', tab_content_by_key_name($dbcon, "thank-you"));

$smarty->loadLangFile('/index'); 

$smarty->assign('frenzy_url', "/fanfrenzy");


display_page($dbcon, $smarty, 'thank_you.tpl', 'Thank You', $_LANG);
unset($smarty);
?>
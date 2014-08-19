<?php

if (!$partBottom) {
	include_once ('class.soc.php');
	include_once ('class.socbid.php');
	include_once ('class.socstore.php');

	$socObj = new socClass();
	$socbidObj = new socbidClass();

	$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');

	//display logo or not
	unset($_SESSION['logo_old']);
	unset($_SESSION['logo_new']);

	$is_logo = 'true';
	$menu_bgcolor = ' bgcolor="#65BFF3"';  //show the new left banner logo, set background color.
	$menu_bottom =  ' bgcolor="#65BFF3"';
	$keywordsList = 'flat rate selling, how to sell online, online trading post, sell goods online, sell items online, sell products online, sell stuff online, sell things online, selling online, simple selling online';

	$_SESSION['logo_new'] = true;



}
else
{

	//active the menu of top navigation
	$smarty->assign('cp', $_REQUEST["cp"]);

	//left menu and public functions
	empty($indexTemplate) ? include_once('/leftmenu.php') :  include_once('../leftmenu.php');
	empty($indexTemplate) ? include_once('/soc/seo.php') :  include_once('../soc/seo.php');
	
	//$smarty -> assign('sidebar', 0);

	$smarty -> assign('menu_bgcolor', $menu_bgcolor);
	$smarty -> assign('menu_bottom', $menu_bottom);
	$smarty -> assign('is_logo', $is_logo);
	$smarty -> assign('notRoot', $indexTemplate);
	//end of display logo or not

	unset($socObj);
	$smarty -> assign('menu_bgcolor', $menu_bgcolor);
	$smarty -> assign('pageTitle', 'Local Food & Wine Retailers: Find Specials on Fruit, Vegetables & Wine Near You - Food Marketplace');
	$smarty -> display(empty($indexTemplate) ? 'index.tpl' : $indexTemplate);
	unset($smarty,$socObj,$socbidObj);

}
?>
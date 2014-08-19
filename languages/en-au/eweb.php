<?php
include ('include/smartyconfig.php');
include ('include/maininc.php');
include ('include/class.soc.php');
include ('include/class.socbid.php');
include ('include/functions.php');
include ('include/class.page.php');

function catTitles($id) {
	$socObj2 = new socClass();
	$tmp = $socObj2 -> getNameOfCat($id);
	//unset($socObj);
	return $tmp;
}

function makeTitle() {
	$seoTitle;
	$cat;
	switch($_REQUEST["cp"]){
	case 'category':
		$seoTitle = "Sell Goods Online - Sell Items Online - Selling Online";
		break;
	case 'prolist':
		$cat = catTitles($_REQUEST["id"]);
		$seoTitle = "Sell ".$cat." Online - Sell ".$cat." Items - Selling ".$cat." Products";
		break;
	case 'home':
		$seoTitle = "How to Sell Online - Sell Goods Online - Selling Online";
		break;
	default:
		$seoTitle = "Simple Selling Online - Online Trading Post - Flat Rate Selling";
		break;
	}
	return $seoTitle;
}

function makeDesc() {
	$seoDesc;
	switch($_REQUEST["cp"]){
	case 'category':
		$seoDesc = "Find items and goods online that many businesses are selling online. These businesses are selling their products online.";
		break;
	case 'prolist':
		$cat = catTitles($_REQUEST["id"]);
		$seoDesc = "We sell all types of ".$cat." online. We have flat rate selling and sell ". $cat." products online.";
		break;
	case 'home':
		$seoDesc = "The SOC Exchange is businesses selling items online. If you are a business we can show you how to sell online using simple flat rate selling.";
		break;
	default:
		$seoDesc = "SOC Exchange lets you sell items online simply using flat rate selling. It is an online trading post.";
		break;
	}
	return $seoDesc;
}

function makeKWs() {
	$seoKWs;
	switch($_REQUEST["cp"]){
	case 'category':
		$seoKWs = "sell goods online, sell items online, selling online, flat rate selling, online trading post, sell products online, sell stuff online, sell things online, selling online, simple selling online";
		break;
	case 'prolist':
		$cat = catTitles($_REQUEST["id"]);
		$seoKWs = "sell ".$cat." online, sell ".$cat ." online, selling online, flat rate selling, online trading post, sell stuff online, sell ".$cat .", selling online, simple selling online";
		break;
	case 'home':
		$seoKWs = "sell goods online, sell items online, selling online, flat rate selling, online trading post, sell products online, sell stuff online, sell things online, selling online, simple selling onlinee";
		break;
	default:
		$seoKWs = "Simple Selling Online - Online Trading Post - Flat Rate Selling";
		break;
	}
	return $seoKWs;
}
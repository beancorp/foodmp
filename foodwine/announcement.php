<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include('../indexPart.php');
$indexTemplate	=	'../index.tpl';

//Check Login
if($_SESSION['UserID'] == '' && $_SESSION['level'] != 1){
	header("Location:/soc.php?cp=login");
}

if ($_REQUEST["cp"]) {
	
	$aid = $_REQUEST['aid'];
	$title = $_REQUEST['title'];
	$content = $_REQUEST['content'];
	
	$foodWine = new FoodWine();
	$res = $foodWine->saveAnnouncement($StoreID, $title, $content, $aid);
		
    $msg = $res ? 'Save successfully.' : 'Save failed.';
	
    header('Location: ' . SOC_HTTPS_HOST . 'foodwine/?act=announcement&msg=' . $msg);
	exit();
}

$req = $socObj->getStoreInfo($StoreID);
$req['info'] = $foodWine->getAnnouncementInfo($StoreID);
$req = array_merge($req, $_REQUEST);
//ckeditor
$comm = new common();
$info_content = empty($req['info']['content']) ? "" : $req['info']['content'];
$req['input']['content'] = $comm->initEditor('content', $info_content, "SOCAlerts",$size=array('100%',300));
$smarty->assign('req',$req);
$smarty->assign('sidebar',0);
$pageTitle = 'Announcements';
$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Announcements');
$smarty -> assign('keywords','Announcements');
$content = $smarty->fetch('announcement.tpl');
$smarty -> assign('content', $content);
unset($comm);
?>

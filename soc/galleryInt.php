<?php
@session_start();
include_once ('include/config.php');
include_once ('include/smartyconfig_url.php');
include_once ('maininc.php');
include_once ('class/common.php');
include_once ('class/pagerclass.php');
include_once ('class.uploadImages.php');
include_once ('class.soc.php');
include_once ('class.socstore.php');
include_once ('functions.php');
include_once ('class.page.php');
include_once ('class.wishlist.php');
include_once ('class.gallery.php');
include_once('leftmenu.php');
include_once('soc/seo.php');
if ($_SESSION['attribute']==3&&$_SESSION['subAttrib']==3) {
	header('Location:soc.php?cp=home');
	exit();
}

$socObj = new socClass();
$socstoreObj = new socstoreClass();
$wishlist = new wishlist();
$gallery = new gallery();


$cid = base64_decode($_REQUEST['cid']);
$StoreID = base64_decode($_REQUEST['StoreID']);
$pageid = (isset($_REQUEST['p'])&&$_REQUEST['p'])?$_REQUEST['p']:1;
$firstimg = $gallery->getFirstImages($StoreID,$cid,$pageid);
if(!isset($_REQUEST['l'])||$_REQUEST['l']==0){
	if(strpos($_SERVER["HTTP_USER_AGENT"],"Chrome")){
		header("Location: /soc.php?act=galleryInt&StoreID={$_REQUEST['StoreID']}&cid={$_REQUEST['cid']}&l=1&p={$pageid}".$firstimg);
		exit();
	}
	
}
$galleryInfo = $gallery->getGalleryCategoryInfo($StoreID,$cid);

$req['info']['bu_name'] = getStoreByName($StoreID);

$smarty -> assign('sidebar', 0);
$smarty->assign('is_website',1);
$smarty->assign('cp', $_REQUEST["cp"]);
$_REQUEST['StoreID'] = $StoreID;
$req	=	$socObj -> displayStoreProduct();
$req['info']['bu_name'] = getStoreByName($StoreID);
$smarty->assign('headerInfo', $req['info']);
$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
$smarty->assign('tmp_header', $tmp_header);
$templateInfo = $socObj -> getTemplateInfo();
$smarty -> assign('templateInfo', $templateInfo);
$smarty -> assign('req',$req);
$smarty -> assign('pageTitle',$req['info']['bu_name'].'  - Gallery @ SOC Exchange');

$gallerybanner = $gallery->getGalleryTemplate($galleryInfo['template']);

$smarty->assign('wishinfo',$gallerybanner);
$smarty->assign('gallerydetail',$galleryInfo);
$ispass = true;
if($galleryInfo['gallery_category_password']&&strtolower($galleryInfo['gallery_category_password'])!=""){
	if($_SESSION['StoreGallery'][$StoreID]){
		$ispass = true;
	}else{
		$ispass = false;
		$content = $smarty->fetch('gallery/tmplate-protected.tpl');
	}
}

if($ispass){
	$pageid = isset($_REQUEST['p'])?$_REQUEST['p']:1;
	$gallerlist = $gallery->gallerylist($StoreID,$cid,$pageid);
	$pagelist = $gallery->gallerylistPages($StoreID,$cid,$pageid);
	$smarty->assign('gallerlist',$gallerlist);
	$smarty->assign('pagelist',$pagelist);
	$content = $smarty->fetch('gallery/template.tpl');
}


$smarty->assign('modcp','wishlist');
$smarty->assign('content',$content);

$smarty -> display('index.tpl');
unset($smarty);
?>
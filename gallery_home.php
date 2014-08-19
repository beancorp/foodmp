<?php
	include_once ('class.gallery.php');
	
	$socObj = new socClass();
	$socstoreObj = new socstoreClass();
	$wishlist = new wishlist();
	$gallery = new gallery();
	
	$site = $_REQUEST['site'];
	$item = $_REQUEST['item'];
	$gallery_name = $_REQUEST['gallery'];
	$smarty -> assign('securt_url',$securt_url);
	$smarty -> assign('normal_url',$normal_url);
	if (!empty($_SESSION)) {
		$_SESSION['website'] = $_SESSION['urlstring'];
		$smarty -> assign('session', $_SESSION);
	}
	$StoreID = $socstoreObj->getStoreIDbyName($site);
	if (!$StoreID){
		echo "<script>alert('Website name does not exist.');location.href='/soc.php?cp=home';</script>";
		exit;
	}


	$req['info']['bu_name'] = getStoreByName($StoreID);
	$smarty -> assign('sidebar', 0);
	$smarty->assign('is_website',1);
	$smarty->assign('site',$site);
	$smarty -> assign('itemTitle', $socObj->getTextItemTitle($req['info']['bu_name']." - Gallery",2));
	$req	=	$socObj -> displayStoreProduct(true);
	$req['info']['bu_name'] = getStoreByName($StoreID);	
	$headerInfo = $socObj -> displayStoreWebside(true);
    $req['template'] = $headerInfo['template'];
	$smarty->assign('headerInfo', $req['info']);
	$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
	$smarty->assign('tmp_header', $tmp_header);
	$templateInfo = $socObj -> getTemplateInfo();
	$smarty -> assign('templateInfo', $templateInfo);
	$smarty -> assign('pageTitle',$req['info']['bu_name'].'  - Gallery @ SOC Exchange');
	
	$galleryList = $gallery->getGalleryCategory($StoreID);
	$smarty -> assign('req',$req);
	$smarty	-> assign('galleryList',$galleryList);
	$content = $smarty->fetch('gallery/gallery_home.tpl');
	$smarty->assign('content',$content);
	$smarty->assign('isstorepage',1);
	
	$smarty -> display($template_tpl);
	unset($smarty);
	
?>
<?php
if ($_SESSION['attribute']==3&&$_SESSION['subAttrib']==3) {
	header('Location:soc.php?cp=home');
	exit();
}
$smarty -> assign('itemTitle', '<div id="content_cmslong"><span id="titles" style="width:580px;"><h1 style="font-size:14px;font-weight:400;color:#FFFFFF;margin:0px;">Send gallery to my friends history&nbsp;</h1> </span>&nbsp;<a href="/soc.php?act=gallery">Gallery Home</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/soc.php?act=galleryinfo&cid='.$_REQUEST['cid'].'&cp=send">&lt;&lt; Back</a></div><br />');
include_once ('xajax/xajax_core/xajax.inc.php');

function galleryEmailList($page,$StoreID,$cid){
	$objResponse 	= new xajaxResponse();
	$gallery = new gallery();
	$gallery -> smarty -> assign('PBDateFormat',DATAFORMAT_DB);
	$req = $gallery->getGalleryEmailLog($StoreID,$cid,$page);
	$gallery -> smarty -> assign('req',	$req);
	$content = $gallery -> smarty -> fetch('gallery/galleryEmailLog.tpl');
	$objResponse -> assign("refcontent",'innerHTML',$content);
	return $objResponse;
}
$xajax 		= new xajax();
$xajax -> registerFunction('galleryEmailList');
$xajax -> processRequest();
$req = $gallery->getGalleryEmailLog($StoreID,$_REQUEST['cid'],1);
$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');

$req['galleryinfo'] = $gallery->getGalleryCategoryInfo($StoreID,$_REQUEST['cid']);
$req['fullhtml'] = 1;
$smarty->assign('gallerylink',$hostgallery);
$smarty->assign('req',$req);
$content = $smarty -> fetch('gallery/galleryEmailLog.tpl');
$smarty -> assign('content', $content);
$smarty -> assign('sidebar', 0);

$smarty -> assign('sidebar', 0);
$smarty -> assign('search_type',$search_type);
$smarty->assign("is_content",1);
include('leftmenu.php');
include_once('soc/seo.php');
$smarty -> assign('act', $_REQUEST['act']);
$smarty->assign('modcp','wishlist');
$smarty -> display('index.tpl');
?>
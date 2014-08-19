<?php
@session_start();
include_once ('include/config.php');
include_once ('include/smartyconfig.php');
include_once ('maininc.php');
include_once ('class.emailClass.php');
include_once ('class.soc.php');
include_once ('class/pagerclass.php');
include_once ('functions.php');
include_once ('class.gallery.php');
include_once ('class.wishlist.php');
$wishlist = new wishlist();
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 1)) {
	header('Location:soc.php?cp=home');
	exit();
}
if ($_SESSION['attribute']==3&&$_SESSION['subAttrib']==3) {
	header('Location:soc.php?cp=home');
	exit();
}
$socObj = new socClass();
$gallery = new gallery();
$StoreID = $_SESSION['ShopID'];

if($_REQUEST['banner']=='user_template'){
	$banner = $gallery->saveUserTempl($StoreID,$_REQUEST['user_banner'],$_REQUEST['usertpl_img']);
}else{
	$banner = $_REQUEST['banner'];
}
$req['urlstring'] = getStoreURLNameById($StoreID);
switch ($_REQUEST['cp']){
	case 'edit':
		if(isset($_POST['saveopt'])&&$_POST['saveopt']=='savegallery'){
			if(!$gallery->checkgalleryURL($_POST['gallery_category'],$StoreID,$_POST['id'])){
				$req['msg'] = "The Gallery Name is invalid or exists.";
			}else{
				$aryResult['id']	=	$_POST['id'];
				$aryResult['gallery_category'] = $_POST['gallery_category'];
				$aryResult['gallery_url'] = clean_url_name($_POST['gallery_category']);
				$aryResult['gallery_category_desc'] = $_POST['gallery_category_desc'];
				$aryResult['gallery_category_password'] = $_POST['gallery_category_password'];
				$aryResult['StoreID'] = $StoreID;
				$aryResult['gallery_category_order'] = 1;
				$aryResult['gallery_category_thumbs'] = $_POST['gallery_category_thumbs'];
				$aryResult['gallery_category_images'] = $_POST['gallery_category_images'];
				$aryResult['music'] = $_POST['music'];
				$aryResult['music_name'] = $_POST['music_name'];
				$aryResult['template'] = $banner;
				$aryResult['lastupdate'] = time();
				if(!get_magic_quotes_gpc()){
					$aryResult = striaddslashes_deep($aryResult);
				}
				$gallery->updateGalleryCategory($aryResult);
				header('Location: /soc.php?act=galleryinfo&cid='.$aryResult['id']);exit();
			}
		}
		$req['galleryinfo'] = $gallery->getGalleryCategoryInfo($StoreID,$_REQUEST['id']);
		break;
	case 'del':
		if($gallery->DeleteGalleryCategory($StoreID,$_REQUEST['id'])){
			header('Location: /soc.php?act=gallery');exit();
		}
		break;
	default:
		if(isset($_POST['saveopt'])&&$_POST['saveopt']=='savegallery'){
			if(!$gallery->checkgalleryURL($_POST['gallery_category'],$StoreID,0)){
				$req['msg'] = "The Gallery Name is invalid or exists.";
				$req['galleryinfo'] = $_POST;
			}else{
				$aryResult['gallery_category'] = $_POST['gallery_category'];
				$aryResult['gallery_url'] = clean_url_name($_POST['gallery_category']);
				$aryResult['gallery_category_desc'] = $_POST['gallery_category_desc'];
				$aryResult['gallery_category_password'] = $_POST['gallery_category_password'];
				$aryResult['StoreID'] = $StoreID;
				$aryResult['gallery_category_order'] = 1;
				$aryResult['gallery_category_thumbs'] = $_POST['gallery_category_thumbs'];
				$aryResult['gallery_category_images'] = $_POST['gallery_category_images'];
				$aryResult['music'] = $_POST['music'];
				$aryResult['music_name'] = $_POST['music_name'];
				$aryResult['template'] = $banner;
				$aryResult['addtime'] = time();
				$aryResult['lastupdate'] = time();
				if(!get_magic_quotes_gpc()){
					$aryResult = striaddslashes_deep($aryResult);
				}
				$cid = $gallery->SaveGalleryCategory($aryResult);
				header('Location: /soc.php?act=galleryinfo&cid='.$cid);exit();
			}
		}
		break;
}

$req['wishinfo'] = $wishlist->getwishlistInfo($StoreID);
$req['banner'] = $gallery->getusertempl($StoreID);
$req['userTemp'] = $wishlist->getUserTempList();
$req['gallerylist'] = $gallery->getGalleryCategory($StoreID);

$itemtitle = $socObj->getTextItemTitle('Gallery Home',3);
$smarty -> assign('itemTitle', 	$itemtitle);
$smarty->assign('req',$req);
$content = $smarty -> fetch('gallery/gallery.tpl');
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
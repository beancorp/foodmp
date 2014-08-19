<?php
@session_start();
include_once ('include/config.php');
include_once ('include/smartyconfig.php');
include_once ('maininc.php');
include_once ('class.soc.php');
include_once ('functions.php');
include_once ('class.page.php');
include_once ('class/pagerclass.php');
include_once ('class.uploadImages.php');
include_once ('class/ajax.php');
include_once ('class.wishlist.php');

$sctp = $_REQUEST['cp'];
$StoreID = isset($_REQUEST['StoreID'])?$_REQUEST['StoreID']:0;
$wishlist = new wishlist();
$socObj = new socClass();
switch ($sctp){
	case 'dispro':
		if(isset($_SESSION['wishpage'][$StoreID])){
			$_GET['p'] = $_SESSION['wishpage'][$StoreID];
		}else{
			$_SESSION['wishpage'][$StoreID] = 1;
			$_GET['p'] = $_SESSION['wishpage'][$StoreID];
		}
		$template = array('TemplateName'=>'product-display');
		$req['info']	=	$socObj -> _displayStoreInfo($template,$StoreID);

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$req['info']['bu_name'] = getStoreByName($StoreID);
		$smarty -> assign('pageTitle',$req['info']['bu_name'].' - Wish List @ SOC Exchange Australia');

		$smarty->assign('headerInfo', $req['info']);
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		
		
		$req['product'] = $wishlist->getWishlistProlist($StoreID,'featured',true);
		$wishproInfo = $wishlist->getwishlistInfo($StoreID);
		$wishproInfo['youtubevideo']	=	getobjurl($wishproInfo['youtubevideo'])?getobjurl($wishproInfo['youtubevideo']):"";
		$smarty -> assign('wishproinfo',$wishproInfo);
		$wishlistbanner = $wishlist->getWishlistTemplate($wishproInfo['banner']);
		$smarty->assign('wishinfo',$wishlistbanner);

                /*
                 *  @Author : Yang Ball 2010-07-29
                 *  Bug #5955
                 *  get Banner Width and Height
                 */
                $banner_img_info=getimagesize('.'.$wishlistbanner['banner']);
                $banner_width=$banner_img_info[0];
                $banner_height=$banner_img_info[1];
                $smarty->assign('banner_img',array('width'=>$banner_width,'height'=>$banner_height));
                /*
                 *  END
                 */

		$smarty->assign('wishinfodetail',$wishproInfo);
		$ispass = true;
		if($wishproInfo['isprotected']&&strtolower($wishproInfo['password'])!=""){
			if($_SESSION['StoreWishlist'][$StoreID]){
				$ispass = true;
			}else{
				$ispass = false;
				$content = $smarty->fetch('wishlist/tmplate-protected.tpl');
			}
		}
		if($ispass){	
			$smarty -> assign('req',	$req);
			$wishpro = count($wishlist->getWishlistProlist($StoreID));
			$smarty -> assign('procount',$wishpro);
			$smarty->assign('regetStoreID',$_REQUEST['StoreID']);
			$content = $smarty -> fetch('wishlist/display_wishlist.tpl');
		}
		
		$smarty -> assign('content', $content);
		$smarty->assign('div','rightCol');
		$smarty->assign('sidebar',0);
		$smarty->assign('is_website',1);
		break;
	default:
		break;
}


$requesturi = $_SERVER['REQUEST_URI'];
$smarty->assign('requesturi', $requesturi);

if (!empty($_SESSION)) {
	$userData = $_SESSION;
	$storeName = getStoreURLNameById($userData['StoreID']);
	if (!empty($storeName)) {
		$userData['website'] = clean_url_name($storeName);
	}
	$smarty -> assign('session', $userData);
}
$smarty->assign('modcp','wishlist');
//left menu
include_once('leftmenu.php');

$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
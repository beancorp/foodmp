<?php
/**
 * store seting
 * Wed Feb 13 08:58:45 CST 2008 08:58:45
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * store_set.php
 */
@session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified:   " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");     //   HTTP/1.1
header ("Pragma: no-cache");

include_once ('include/smartyconfig.php');
include_once ('include/class.soc.php');
include_once ('include/class.socstore.php');
include_once ('include/class.emailClass.php');
include_once ('include/maininc.php');
include_once ('include/functions.php');
include_once ('class.upload.php');
include_once ('class.uploadImages.php');
include_once ('class.wishlist.php');

//if($_SESSION['UserID']=='' AND $_SESSION['level']!=1){header("Location:index.php");}
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 1)) {
	header('Location:soc.php?cp=home');
	exit();
}
if ($_SESSION['attribute']==3&&$_SESSION['subAttrib']==3) {
	header('Location:soc.php?cp=home');
	exit();
}
$socObj = new socClass();
$objUI	=	new uploadImages();
$wishlist = new wishlist();
$smarty -> assign('securt_url',$securt_url);
$smarty -> assign('normal_url',$normal_url);

$itemtitle = $socObj->getTextItemTitle('Wishlist',3);
$strStep = isset($_REQUEST['step'])?$_REQUEST['step']:1;
$menubar = 	$wishlist->getwishlistMenu($_REQUEST['step'],$_SESSION['ShopID']);
if ($_REQUEST['cp']=='exit') {
	$socObj -> destroyFormInputVar();
	header('Location:soc.php?cp=sellerhome');
}
if(!$wishlist->checkInitwishlist($_SESSION['ShopID'])&&$strStep!=1){
	header('Location:soc.php?act=wishlist&step=1');
}elseif ($wishlist->checkInitwishlist($_SESSION['ShopID'])&&!$wishlist->checkEnablewishlist($_SESSION['ShopID'])&&($strStep!=1&&$strStep!=2)){
	header('Location:soc.php?act=wishlist&step=2');
}
$smarty -> assign('pageTitle','Wish List @ SOC Exchange');
switch ($strStep){
	case '1':
		if(isset($_POST['cp'])&&$_POST['cp']!=""){
			if($_REQUEST['banner']=='user_template'){
				$banner = $wishlist->saveUserTemplate($_SESSION['ShopID'],$_REQUEST['user_banner'],$_REQUEST['usertpl_img']);
			}else{
				$banner = $_REQUEST['banner'];
			}
			if($wishlist->initwishlist($_SESSION['ShopID'],$_REQUEST['template'],$banner,$_REQUEST['color'])){
				if($_REQUEST['cp']=='save'){
				 	header('Location:soc.php?cp=sellerhome');
				}elseif($_REQUEST['cp']=='next'){
					header('Location:soc.php?act=wishlist&step=2');
				}
			}
		}
		$req['wishinfo'] = $wishlist->getwishlistInfo($_SESSION['ShopID']);
		$req['banner'] = $wishlist->getBannerList($_SESSION['ShopID']);
		$req['userTemp'] = $wishlist->getUserTempList();
		$req['bulid'] = $wishlist->checkInitwishlist($_SESSION['ShopID']);
		$req['enable'] = $wishlist->checkEnablewishlist($_SESSION['ShopID']);
		$smarty -> assign('req',$req);
		break;
	case '2':
		if(isset($_POST['cp'])&&$_POST['cp']!=""){
			$req['msg'] = $wishlist->saveWishlistInfo($_SESSION['ShopID']);
			if($req['msg']==""){
				if($_POST['cp']=='save'){
					header('Location:soc.php?cp=sellerhome');
				}elseif($_POST['cp']=='next'){
					header('Location:soc.php?act=wishlist&step=3');
				}
			}
		}
		$req['wishinfo'] = $wishlist->getwishlistInfo($_SESSION['ShopID']);
		$req['wishNumber'] = $wishlist->getWishListNumber($_SESSION['ShopID']);
		$smarty -> assign('req', 		$req);
		break;
	case '3':
		if ($_REQUEST['cp']=='next') {
			$socObj -> destroyFormInputVar();
            header('Location:soc.php?act=wishlist&step='.($strStep+1));
		}elseif ($_REQUEST['cp']=='edit'||$_REQUEST['cp']=='del'){
			$wishlist->addoreditwishlist();
			$arrTemp = $wishlist -> getFormInputVar();
			$wishlist -> destroyFormInputVar();
			header('Location:soc.php?act=wishlist&step=3'."&msg=" . $arrTemp['msg']);
			exit;
		}
		if($_REQUEST['multcp']){
			switch ($_REQUEST['multcp']){
				case 'delete':
					if(count($_REQUEST['ckpid'])){
						if($wishlist->multidel($_REQUEST['ckpid'])){
							$multmsg = "Deleted successfully.";
						}else{
							$multmsg = "Failed to delete.";
						}
					}else{
						$multmsg = "Failed to delete.";
					}
				break;
			}
			header('Location:soc.php?act=wishlist&step=3&msg='.$multmsg);
			exit();
		}
		
		$req =   $wishlist->getWishlistPro($_REQUEST['pid'],$_SESSION['ShopID']);
		$req['msg'] = $_REQUEST['msg'];
		$smarty -> assign('req', 		$req);
		break;
	case '4':
		if(!$wishlist->checkInitwishlist($_SESSION['ShopID'])){
			header('Location:soc.php?act=wishlist&step=1');
		}
		$storeName = getStoreURLNameById($_SESSION['StoreID']);
		if (!empty($storeName)) {
			$storeName = clean_url_name($storeName);
		}

		$smarty -> assign('StoreID', $_SESSION['StoreID']);
		$smarty -> assign('storeName', $storeName);
		break;
    case 'preview':
        $_SESSION['wishlistPreview'] = TRUE;
        header("location:/{$_SESSION['urlstring']}/wishlist");
        exit;
//
//        include_once ('class.page.php');
//        $smarty->assign('is_website',1);
//        $StoreID = $_SESSION['StoreID'];
//
//        $req	=	$socObj -> displayStoreProduct();
//        $req['info']['bu_name'] = getStoreByName($StoreID);
//
//        $wishlistinfo  = $wishlist->getwishlistInfo($StoreID);
//        $wishlistinfo['youtubevideo']	=	getobjurl($wishlistinfo['youtubevideo'])?getobjurl($wishlistinfo['youtubevideo']):"";
//
//        $wishlistbanner = $wishlist->getWishlistTemplate($wishlistinfo['banner']);
//
//        $smarty->assign('req', $req);
//        $smarty->assign('wishinfo',$wishlistbanner);
//        $smarty->assign('wishinfodetail',$wishlistinfo);
//
//        if(isset($_SESSION['wishpage'][$StoreID])){
//            $_GET['p'] = $_SESSION['wishpage'][$StoreID];
//            $_REQUEST['p'] = $_SESSION['wishpage'][$StoreID];
//        }else{
//            $_SESSION['wishpage'][$StoreID] = 1;
//            $_GET['p'] = $_SESSION['wishpage'][$StoreID];
//            $_REQUEST['p'] = $_SESSION['wishpage'][$StoreID];
//        }
//        $smarty->assign('weburl',$securt_url);
//        setCounter($StoreID,0,'WISHLIST');
//        if($wishlistinfo['template']=='a'){
//            $wishproList = $wishlist->wishlistItemsProduct($StoreID,0,true);
//            if($wishproList[0]['protype']){
//                $wishproList[0]['fotgive'] = $wishproList[0]['price'];
//            }else{
//                $wishproList[0]['fotgive'] = round($wishproList[0]['price'],2)-round($wishproList[0]['gifted'],2);
//            }
//        }else{
//            $wishproList = $wishlist->getWishlistProlist($StoreID,'featured',true);
//        }
//        $smarty -> assign('prolist',$wishproList);
//        $wishpro = count($wishlist->getWishlistProlist($StoreID));
//        $smarty -> assign('procount',$wishpro);
//        $content = $smarty->fetch('wishlist/tmplate-'.$wishlistinfo['template'].'.tpl');
//
//        $req['bulid'] = $wishlist->checkInitwishlist($_SESSION['ShopID']);
//        $req['enable'] = $wishlist->checkEnablewishlist($_SESSION['ShopID']);
//        $req['content'] = $content;
//        $smarty->assign('req', $req);
//        $smarty->assign('modcp','wishlist');
        break;
}

$smarty -> assign('content', $smarty -> fetch('wishlist_step'.$strStep.'.tpl'));

$smarty -> assign('itemTitle', 	$itemtitle.$menubar);
$smarty -> assign('sidebar', 0);
$smarty -> assign('search_type',$search_type);
$smarty->assign("is_content",1);
include('leftmenu.php');
include_once('soc/seo.php');
$smarty -> assign('act', $_REQUEST['act']);
$smarty -> display($template_tpl);
?>
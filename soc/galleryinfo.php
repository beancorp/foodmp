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
include_once ('class.upload.php');
include_once ('class.uploadImages.php');

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
if(!$gallery->getGalleryCategoryInfo($StoreID,$_REQUEST['cid'])){
	header('Location:/soc.php?act=gallery');
	exit();
}
$smarty -> assign('itemTitle','<div id="content_cmslong"><span id="titles" style="width:580px;"><h1 style="font-size:14px;font-weight:400;color:#FFFFFF;margin:0px;">Gallery images&nbsp;</h1> </span>&nbsp;<a href="/soc.php?act=gallery">Gallery Home</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/soc.php?act=gallery">&lt;&lt; Back</a></div><br />');
switch ($_REQUEST['cp']) {
    case 'edit':
        if(isset($_POST['saveopt'])&&$_POST['saveopt']=='savegallery') {
            $aryResult['id']	=	$_POST['id'];
            $aryResult['gallery_category'] = $_POST['gallery_category'];
            $aryResult['gallery_thumbs'] = $_POST['gallery_thumbs'];
            $aryResult['gallery_images'] = $_POST['gallery_images'];
            $aryResult['gallery_desc'] = substr($_POST['gallery_desc'],0,180);

            $aryResult['StoreID'] = $StoreID;
            if (!$_POST['gallery_order']||!is_numeric($_POST['gallery_order']))$_POST['gallery_order'] = 1;
            $_POST['gallery_order'] = ceil($_POST['gallery_order']);
            $aryResult['gallery_order'] = $gallery->generateOrder($_POST['gallery_category'],$_POST['gallery_order'],$_POST['id']);
            $aryResult['gallery_lastupdate'] = time();
            $gallery->updateGallery($aryResult);
            header('Location: /soc.php?act=galleryinfo&cid='.$_REQUEST['cid']);
            exit();
        }
        $req['galleryDetails'] = $gallery->galleryInfo($StoreID,$_REQUEST['pid']);
        $req['lastOrder'] = $req['galleryDetails']['gallery_order'];
        break;
    case 'del':
        if($gallery->DeleteGalleryInfo($StoreID,$_REQUEST['pid'])) {
            header('Location: /soc.php?act=galleryinfo&cid='.$_REQUEST['cid']);
            exit();
        }
        break;
    case 'send':
        include('gallerysend.php');
        exit();
        break;
    case 'emaillist':
        include('galleryemailLog.php');
        exit();
    default:
        if(isset($_POST['saveopt'])&&$_POST['saveopt']=='savegallery') {
            if($_POST['uploadtype'] == 'bluk') {
                $imgfile = $_REQUEST['bulkimg'];
                $_FILES['image']['size'] = filesize($imgfile);
                
                if($imgfile=="") {
                    $msg = $GLOBALS['multi_msg'][0];
                }elseif($_FILES['image']['size']>83400320) {
                    $msg = $GLOBALS['multi_msg'][11];
                }else {
                    $_FILES['image']['tmp_name'] = $imgfile;
                    $_FILES['image']['name'] = "upload.zip";
                    if ($_FILES['image']['size']==0) {
                        $msg = $GLOBALS['multi_msg'][0];
                    }else {
                        $result = $gallery->importGallery($_FILES['image'], $_POST['gallery_order']);
                        $boolResult = true;
                        if($fail==='all') {
                            $msg = "The titles in the csv don&#039;t match the standardized titles completely. Please check the csv.";
                        }elseif ($fail>0) {
                            $this->setFormInuptVar(array('error_more'=>"&nbsp;<a href='#' onclick='javascript:window.open(\"/multi_msg.php\",\"multerr\",\"width=600,height=400,scrollbars=yes,status=yes\");location.href=\"/soc.php?act=signon&step=4\";'>Click here show more!</a>"));
                        }
                    }
                    @unlink($imgfile);
                }
            }else {
                $aryResult['gallery_category'] = $_POST['gallery_category'];
                $aryResult['gallery_thumbs'] = $_POST['gallery_thumbs'];
                $aryResult['gallery_images'] = $_POST['gallery_images'];
                $aryResult['gallery_desc'] = substr($_POST['gallery_desc'],0,180);

                $aryResult['StoreID'] = $StoreID;
                if (!$_POST['gallery_order']||!is_numeric($_POST['gallery_order']))$_POST['gallery_order'] = 1;
                $_POST['gallery_order'] = ceil($_POST['gallery_order']);
                $aryResult['gallery_order'] = $gallery->generateOrder($_POST['gallery_category'],$_POST['gallery_order'],0);
                $aryResult['gallery_addtime'] = time();
                $aryResult['gallery_lastupdate'] = time();
                $gallery->SaveGallery($aryResult);
            }
            header('Location: /soc.php?act=galleryinfo&cid='.$_REQUEST['cid']);
            exit();
        }
        $req['lastOrder'] = $gallery->getLastOrder($StoreID, $_REQUEST['cid']) + 1;
		break;
}

$req['urlstring'] = getStoreURLNameById($StoreID);
$req['galleryinfo'] = $gallery->getGalleryCategoryInfo($StoreID,$_REQUEST['cid']);
$req['gallerylist'] = $gallery->gallerylist($StoreID,$_REQUEST['cid']);

if($_REQUEST['cp'] == 'edit'){
    $req['isEdit'] = TRUE;
}
$smarty->assign('req',$req);
$content = $smarty -> fetch('gallery/galleryInfo.tpl');
$smarty -> assign('content', $content);
$smarty -> assign('sidebar', 0);

$smarty -> assign('sidebar', 0);
$smarty -> assign('search_type',$search_type);
$smarty->assign("is_content",1);
include('leftmenu.php');
include_once('soc/seo.php');
$smarty -> assign('act', $_REQUEST['act']);
$smarty->assign('modcp','wishlist');
$smarty -> assign('securt_url',$securt_url);
$smarty -> assign('normal_url',$normal_url);
$smarty -> display($template_tpl);
?>
<?php
	include_once ('include/config.php');
	@session_start();
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
	
	$socObj = new socClass();
	$socstoreObj = new socstoreClass();
	$wishlist = new wishlist();
	$gallery = new gallery();

	$site = $_REQUEST['site'];
	$item = $_REQUEST['item'];
	$gallery_name = $_REQUEST['gallery'];
	if (!empty($_SESSION)) {
	$_SESSION['website'] = $_SESSION['urlstring'];
	$smarty -> assign('session', $_SESSION);
	}

        $sql = "select * from ".$table."login as t1 left join ".$table."bu_detail as t2 on t1.StoreID=t2.StoreID where t1.store_name='".$site."' and t2.renewalDate>".time();
        $dbcon->execute_query($sql);
        $result = $dbcon->fetch_records();
        $StoreID=$result[0]["StoreID"];



	//$StoreID = $socstoreObj->getStoreIDbyName($site);
	if (!$StoreID){
		//echo "<script>alert('The website is expired or does not exist.');location.href='/soc.php?cp=home';</script>";
		header('Location: /soc.php?cp=error404');
		exit;
	}
	$galleryInfo = $gallery->getGalleryByName($gallery_name,$StoreID);
	if(!$galleryInfo){
		//echo "<script>alert('Gallery does not exist.');location.href='/soc.php?cp=home';</script>";
		header('Location: /soc.php?cp=error404');
	}
	$galleryInfo = $galleryInfo[0];
	$cid = $galleryInfo['id'];
	$pageid = (isset($_REQUEST['p'])&&$_REQUEST['p'])?$_REQUEST['p']:1;
	$firstimg = $gallery->getFirstImages($StoreID,$cid,$pageid);
	if(!isset($_REQUEST['l'])||$_REQUEST['l']==0){
		if(strpos($_SERVER["HTTP_USER_AGENT"],"Chrome")){
			header("Location: /$site/gallery/$gallery_name/{$pageid}/1".$firstimg);
			exit();
		}
	}
	$req['info']['bu_name'] = getStoreByName($StoreID);

	$smarty -> assign('sidebar', 0);
	$smarty->assign('is_website',1);
	$_REQUEST['StoreID'] = $StoreID;
	$req	=	$socObj -> displayStoreProduct();
	$req['info']['bu_name'] = getStoreByName($StoreID);
	$headerInfo = $socObj -> displayStoreWebside(true);
    $req['template'] = $headerInfo['template'];
	$smarty->assign('headerInfo', $req['info']);
	$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
	$smarty->assign('tmp_header', $tmp_header);
	$templateInfo = $socObj -> getTemplateInfo();
	$smarty -> assign('templateInfo', $templateInfo);
	$smarty -> assign('req',$req);
	$smarty -> assign('pageTitle',$req['info']['bu_name'].'  - Gallery @ SOC Exchange');
	
	$gallerybanner = $gallery->getGalleryTemplate($galleryInfo['template']);

       /*
        *  @Author : Yang Ball 2010-07-29
        *  Bug #5955
        *  get Banner Width and Height
        */
	$smarty->assign('wishinfo',$gallerybanner);
        $banner_img_info=getimagesize('.'.$gallerybanner['banner']);
        $banner_width=$banner_img_info[0];
        $banner_height=$banner_img_info[1];
        $smarty->assign('banner_img',array('width'=>$banner_width,'height'=>$banner_height));
        
        /*
         *  END
         */
	$smarty->assign('gallerydetail',$galleryInfo);
	$ispass = true;
	if($galleryInfo['gallery_category_password']&&strtolower($galleryInfo['gallery_category_password'])!=""){
		if($_SESSION['StoreGallery'][$StoreID]&&$_SESSION['StoreGallery'][$gallery_name]){
			$ispass = true;
		}else{
			$ispass = false;
			$content = $smarty->fetch('gallery/tmplate-protected.tpl');
		}
	}
	
	if($ispass){
		$gallerlist = $gallery->gallerylist($StoreID,$cid,$pageid);
		$pagelist = $gallery->gallerylistPages($StoreID,$cid,$pageid);
		$smarty->assign('gallery_count',count($gallerlist));
		$smarty->assign('gallerlist',$gallerlist);
		$smarty->assign('pagelist',$pagelist);
		$content = $smarty->fetch('gallery/template.tpl');
	}
	
	$smarty->assign('soc_https_host',SOC_HTTPS_HOST);
	$smarty->assign('isstorepage',1);
	$smarty->assign('modcp','wishlist');
	$smarty->assign('content',$content);
	include_once('leftmenu.php');
	$smarty -> display('index.tpl');
	unset($smarty);
	
?>
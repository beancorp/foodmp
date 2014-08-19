<?php
@session_start();
include_once ('include/config.php');
include_once ('include/smartyconfig_url.php');
include_once ('maininc.php');
include_once ('class/common.php');
include_once ('class/pagerclass.php');
include_once ('class.uploadImages.php');
include_once ('class.soc.php');
include_once ('class.socbid.php');
include_once ('class.socstore.php');
include_once ('functions.php');
include_once ('class.page.php');
include_once ('class.wishlist.php');
include_once ('class.productcertified.php');

$socObj = new socClass();
$socstoreObj = new socstoreClass();

$site = $_REQUEST['site'];
$item = $_REQUEST['item'];


$count = 0;
$result = null;
if (!empty($_SESSION['StoreID'])) {
	$query_own_store = "SELECT * FROM aus_soc_bu_detail detail WHERE detail.bu_urlstring = '".$site."' AND detail.StoreID = '".$_SESSION['StoreID']."'";
	$dbcon->execute_query($query_own_store);
	$count = $dbcon->count_records();
	if ($count > 0) {
		$result = $dbcon->fetch_records();
	}
}

if ($count == 0) {
	$query = "SELECT * FROM aus_soc_bu_detail detail WHERE detail.bu_urlstring = '".$site."' AND detail.status = 1 AND detail.CustomerType = 'seller'";
	$dbcon->execute_query($query);
	$count = $dbcon->count_records();
	if ($count > 0) {
		$result = $dbcon->fetch_records();
	}
}

$StoreID = (isset($result) ? $result[0]["StoreID"] : 0);

if ($StoreID == 0) {
	header('Location: /soc.php?cp=error404');
	exit;
}

// $query = "SELECT * FROM aus_soc_bu_detail WHERE bu_urlstring = '".$site."'";

// $dbcon->execute_query($query);
// $result = $dbcon->fetch_records();
// $StoreID = $result[0]["StoreID"];
// if (!$StoreID) {
	// header('Location: /soc.php?cp=error404');
	// exit;
// }
$smarty->assign('isitempage',1);
$smarty -> assign('securt_url',$securt_url);
$smarty -> assign('normal_url',$normal_url);
$smarty->assign('soc_https_host',SOC_HTTPS_HOST);
if(strtolower($item)!="wishlist"&&strtolower($item)!="gallery") {
    $itemID = $socstoreObj->getItemIDbyName($StoreID, $item);
    if (!$itemID) {
		header('Location: /soc.php?cp=error404');
    } else {
        setCounter($StoreID,$itemID);
        $_REQUEST['StoreID'] = $StoreID;
        $_REQUEST['proid'] = $itemID;
        $req	=	$socObj -> displayStoreProduct();
		$headerInfo = $socObj -> displayStoreWebside(true);
        $req['template'] = $headerInfo['template'];
        if ($req['info']['attribute'] == 3 && count($req['items']['product']) == 0) {
        	echo "<script>alert('Sorry. You don\'t have permission to access this page.');location.href='/soc.php?cp=home';</script>";
            exit;
        }
        switch ($req['info']['attribute']) {
         	case 0:
        		$image = $req['items']['product'][0]['image_name']['name'];
         		$req['info']['facelike_desc'] = $req['items']['product'][0]['description'];
         		break;
         	case 1:
        		$image = $req['items']['product'][0]['images']['mainImage'][0]['sname']['text'];
         		$req['info']['facelike_desc'] = $req['items']['product'][0]['content'];
         		break;
         	case 2:
        		$image = $req['items']['product'][0]['images']['mainImage'][0]['sname']['text'];
         		$req['info']['facelike_desc'] = $req['items']['product'][0]['content'];
         		break;
         	case 3:
         		$req['info']['facelike_desc'] = $req['items']['product'][0]['content1'];
         		break;
         	default:
        		$image = $req['items']['product'][0]['images']['mainImage'][0]['sname']['text'];
         		$req['info']['facelike_desc'] = $req['items']['product'][0]['description'];
         		break;
        }
        if($req['info']['attribute'] == 5) {
                $smarty->assign('show_join_banner', 1);
                $smarty->assign('show_season_banner', 1);
        }
        if (substr($image, 0, 1) == '/') {
        	$image = substr($image, 1);
        }
         
        if($req['items']['product'][0]['is_auction']=='yes') {
            $socbidObj = new socbidClass();
            $req	=	$socbidObj -> displayAuction();
            $req['audio'] = $socbidObj->getAudioList();

            if($req['items']['product'][0]['is_certified']) {
                $certifiedObj = new ProductCertified();
                $req['certifiedAuthorise'] = $certifiedObj->checkCertified($itemID);
                $state=$certifiedObj->getApplyState($itemID);
                if(is_null($state) || $state == 2) {
                    $req['cancertified']=true;
                }else {
                    $req['cancertified']=false;
                }
            }
        }
		
		$free_signup_query = "SELECT status, free_signup FROM aus_soc_bu_detail WHERE StoreID = '".$_REQUEST['StoreID']."'";
		$dbcon->execute_query($free_signup_query);
		$free_signup_result = $dbcon->fetch_records();
		$free_signup = false;
		$account_enabled = false;
		if (isset($free_signup_result[0])) {
			$free_signup_data = $free_signup_result[0];
			if ($free_signup_data['free_signup'] == 1) {
				$free_signup = true;
			}
			if ($free_signup_data['status'] == 1) {
				$account_enabled = true;
			}
		}
		
		
		$smarty->assign('account_enabled', $account_enabled);
		$smarty->assign('free_signup', $free_signup);
		if ($free_signup) {
			$select_detail = "SELECT * FROM aus_soc_bu_detail WHERE StoreID = '".$_REQUEST['StoreID']."'";
			$dbcon->execute_query($select_detail);
			$detail_result = $dbcon->fetch_records();
			if (isset($detail_result[0])) {
				$detail_data = $detail_result[0];
				$delivery_items = unserialize($detail_data['bu_delivery_text']);
				$delivery_values = unserialize($detail_data['bu_delivery']);
				$smarty->assign('delivery_items', $delivery_items);
				$smarty->assign('delivery_values', $delivery_values);
				$smarty->assign('mobile_hide', $detail_data['mobile_hide']);
				$smarty->assign('mobile', $detail_data['mobile']);
				$smarty->assign('fax_hide', $detail_data['fax_hide']);
				$smarty->assign('bu_fax', $detail_data['bu_fax']);
				
				$payment_options_query = "SELECT * FROM aus_soc_payment_options op INNER JOIN aus_soc_payment_store_options sop ON sop.payment_option = op.option_id AND store_id = '".$_REQUEST['StoreID']."'";
				$dbcon->execute_query($payment_options_query);
				$payment_option_result = $dbcon->fetch_records();
				$payment_options = array();
				
				if (!empty($detail_data['bu_paypal'])) {
					$payment_options[] = array('name' => 'Paypal', 'image' => IMAGES_URL.'skin/red/payment/paypal.png');
				}

				foreach($payment_option_result as $option_data) {
					$payment_options[] = array('name' => $option_data['option_name'], 'image' => $option_data['option_image']);
				}
				
				$smarty->assign('payment_options', $payment_options);
				
				$fetch_user = "SELECT * FROM aus_soc_login WHERE StoreID = '".$_REQUEST['StoreID']."'";
				$dbcon->execute_query($fetch_user);
				$user_result = $dbcon->fetch_records();
				if (isset($user_result[0])) {
					$user_data = $user_result[0];
					if ($user_data['status'] == 2) {
						$smarty->assign('verified_image','<img src="/verified.png" height="30px" style="position:absolute;" />');
					}
				}
				
			}
		}

        $req['info']['facelike_title'] = $req['items']['product'][0]['item_name'];
        $req['info']['facelike_image'] = $image ? SOC_HTTP_HOST.$image : SOC_HTTP_HOST.'skin/red/images/logo-main.png';
        $req['info']['facelike_desc'] = str_replace(array("\"", "\'"), array('', ''), strip_tags($req['info']['facelike_desc']));
        $req['info']['like_type'] = 'item';
        $req['info']['like_itemid'] = $itemID;
        
		$headerInfo = $socObj -> displayStoreWebside(true);
        $req['template'] = $headerInfo['template'];
        $templateInfo = $socObj -> getTemplateInfo();
        $smarty -> assign('templateInfo', $templateInfo);
        $req['info']['bu_name'] = getStoreByName($StoreID);
        $smarty -> assign('is_website', 1);

        $aveRating	=	$socObj->getAveRating($_REQUEST['StoreID']);
        $req['aveRating'] = $aveRating;

        $prourl ="{$req['info']['bu_urlstring']}/{$req['items']['product'][0]['url_item_name']}";
        $imgulr =$req['items']['product'][0]['images']['mainImage'][0]['sname']['text'];
        $wg_price = "$ ".$req['items']['product'][0]['price'];
        if($req['info']['attribute']==3) {
            $imgulr=IMAGES_URL."skin/red/logo-main.png";

            $languagePath	=	LANGPATH . '/job/index.php';
            if (file_exists($languagePath)) {
                require_once($languagePath);
            }
            $wg_price = $_LANG['val']['min_salary'][$req['items']['product'][0]['salaryMin']]. " - ";
            $wg_price .= $_LANG['val']['max_salary'][$req['items']['product'][0]['salaryMax']];
        }
        $rooturl = $normal_url;
        $homeurl = "$rooturl{$prourl}";
        $req['widgetHTML']="<div align=\"center\" style=\"width:230px;padding:10px;border:1px solid #ccc;background:#FFF;\"><a href=\"$homeurl\" target=\"_blank\" style=\"text-decoration:none\"><img src=\"$rooturl{$imgulr}\" width=\"100%\" border=\"0\"/></a>";
        $req['widgetHTML'].="<div align=\"left\" style=\"padding:5px;\" ><a href=\"$homeurl\" target=\"_blank\" style=\"text-decoration:none\"><strong style=\"color:#fcab26;font:12px arial, sans-serif;\">{$req['items']['product'][0]['item_name']}</strong></a></div>";
        $req['widgetHTML'].="<div align=\"left\" style=\"padding:5px;\"><strong style=\"font:12px arial, sans-serif;color:#777777;\">Price:</strong> <strong style=\"font:12px arial, sans-serif;color:#fe0002;\">".$wg_price."</strong></div></div>";

        $smarty -> assign('req',	$req);
        $smarty->assign('weburl',$securt_url);
        $smarty -> assign('titlesName', $socObj->getItemTitle('Items','txt',true));
        $smarty->assign('HostName',$_SERVER['HTTP_HOST']);
        $smarty->assign('UserID', $_SESSION['ShopID']);
        $search_type	=	$req['info']['sellerType'];

        $smarty->assign('headerInfo', $req['info']);
        $tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
        $smarty->assign('tmp_header', $tmp_header);

        if ($req['info']['attribute'] >= 1) {
            $smarty -> loadLangFile($search_type. '/index');
            if(in_array($_REQUEST['site'],getSamplesitebyNames($samplesiteid))) {
                $smarty -> assign('is_samplestie',1);
            }
            $product_content = $smarty -> fetch($search_type . '/template/'.$search_type.'-display-product.tpl');
            $smarty -> assign('content', $product_content);
            if ($req['info']['attribute']==1) {
                if($req['items']['product'][0]['suburbName']!="") {
                    $smarty -> assign('pageTitle',"{$req['items']['product'][0]['suburbName']},{$req['items']['product'][0]['stateName']},{$req['items']['product'][0]['location']}");
                }
            }else {
                if($req['items']['product'][0]['item_name']!="") {
                    $smarty -> assign('pageTitle',$req['items']['product'][0]['item_name']);
                }
            }
        }else {
            if(in_array($_REQUEST['site'],getSamplesitebyNames($samplesiteid))) {
                $smarty -> assign('is_samplestie',1);
            }
            if($req['items']['product'][0]['isattachment']==1) {
                if(isuserbuy($req['items']['product'][0]['pid'],$_SESSION['ShopID'])) {
                    $req['isuserbuy'] = 'yes';
                    $smarty -> assign('req',$req);
                }
            }

            if($req['items']['product'][0]['is_auction']=='yes') {
                $product_content = $smarty -> fetch('template/display_auction.tpl');
                $smarty -> assign('product_content', $product_content);

                $shop_summary = $smarty->fetch('template/auction_summary.tpl');
                $smarty->assign('content',$shop_summary);
            }else {
                $product_content = $smarty -> fetch('template/display_product.tpl');
                $smarty -> assign('product_content', $product_content);

                $shop_summary = $smarty->fetch('template/tmp-shop-summary.tpl');
                $smarty->assign('content',$shop_summary);
                if($req['items']['product'][0]['item_name']!="") {
                    $smarty -> assign('pageTitle',$req['items']['product'][0]['item_name']);
                }
            }
        }
        $smarty -> assign('search_type', $req['info']['sellerType']);
        $smarty -> assign('hasProDis', true);
        $smarty -> assign('sidebar', 0);
        $smarty->assign('is_website',1);
		$smarty->assign('isstorepage',1);
        $smarty->assign('cp', $_REQUEST["cp"]);
        $smarty->assign('nottv',1);
        $smarty->assign('gallery_banner_display_status', 'show');
        include_once('leftmenu.php');
        include_once('soc/seo.php');


        $smarty -> assign('menu_bgcolor', $menu_bgcolor);
        $smarty -> assign('menu_bottom', $menu_bottom);
        $smarty -> assign('is_logo', $is_logo);
        if($req['info']['attribute']!='0') {
            $smarty -> assign('is_content',1);
        }
        $smarty->assign('base',true);
        
        $protocol = 'http';
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on'){
            $protocol = 'https';
        }
        
        switch ($req['info']['attribute']) {
        	case 1:
        		$address = $req['items']['product'][0]['suburbName'] .' '. $req['items']['product'][0]['stateName'] .' '. $req['items']['product'][0]['location'];
        		$page_title = $req['items']['product'][0]['bedroom'] . ' Bedroom '.$req['items']['product'][0]['property'].' ' . $address . ': Real Estate: SOC Exchange Australia';
        		$desc = $address.' is a '.$req['items']['product'][0]['bedroom'].' bedrooms '.$req['items']['product'][0]['property'].' with '.$req['items']['product'][0]['bathroom'].' bathrooms available for sale now. For more informationc all '.$req['info']['bu_nickname'].' on '.$req['info']['bu_phone'].'.';
        		
        		break;
        	case 2:
        		$page_title = 'Buy '. $req['items']['product'][0]['year'] .' '. $req['items']['product'][0]['make'] .' '. $req['items']['product'][0]['model'] .' '. $req['items']['product'][0]['kms'] .' KMs For '. $req['items']['product'][0]['price'] .': Cars & Autos: SOC Exchange Australia';
        		$desc = $req['items']['product'][0]['content'];
        		
        		break;
        	case 3:
        		$address = $req['items']['product'][0]['suburbName'] .' '. $req['items']['product'][0]['stateName'] .' '. $req['items']['product'][0]['location'];
        		$page_title = $req['items']['product'][0]['item_name'].' '.$req['items']['product'][0]['sectorName'].' Job In '.$address.': Jobs & Careers: SOC Exchange Australia';
        		$desc = $req['items']['product'][0]['content1'].' '.$req['items']['product'][0]['content2'].' '.$req['items']['product'][0]['content3'];
        		
        		break;
        
        	default:
        		$page_title = 'Buy '.$req['items']['product'][0]['item_name'].' Online: FoodMarketplace';
        		$desc = $req['items']['product'][0]['description'];
        		break;
        }
        $desc = strip_tags($desc);
        $desc = str_replace(array("\"", "\'"), array('', ''), $desc);
		$smarty -> assign('pageTitle', $page_title);
		$smarty -> assign('keywords', 'flat rate selling, how to sell online, online trading post, sell goods online, sell items online, sell products online, sell stuff online, sell things online, selling online, simple selling online');
		$smarty -> assign('description', $desc);
		$is_set_desc = true;
        $smarty->assign('Protocol', $protocol);
        $smarty->assign('HostName',$_SERVER['HTTP_HOST']);
        $smarty->assign('noShowGalleryBanner', true);
        unset($socObj);
        $smarty -> assign('menu_bgcolor', $menu_bgcolor);
        $smarty -> display($template_tpl);
        unset($smarty);
    }

}elseif (strtolower($item)=="wishlist") {
    include_once('leftmenu.php');
    include_once('soc/seo.php');
    $smarty -> assign('sidebar', 0);
    $smarty->assign('is_website',1);
    $smarty->assign('cp', $_REQUEST["cp"]);
    $_REQUEST['StoreID'] = $StoreID;
    $req	=	$socObj -> displayStoreProduct();
	$headerInfo = $socObj -> displayStoreWebside(true);
    $req['template'] = $headerInfo['template'];
    $req['info']['bu_name'] = getStoreByName($StoreID);
    $smarty->assign('headerInfo', $req['info']);
    $tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
    $smarty->assign('tmp_header', $tmp_header);
    $templateInfo = $socObj -> getTemplateInfo();
    $smarty -> assign('templateInfo', $templateInfo);
    $smarty -> assign('req',$req);
    $smarty -> assign('pageTitle',$req['info']['bu_name'].'  - Wish List @ SOC Exchange');
    $wishlist = new wishlist();
    if(!$wishlist->checkEnablewishlist($StoreID)) {
        echo "<script>alert('The seller hasn\'t enabled the wishlist. Please contact him/her if you need.');location.href='/$site';</script>";
        exit;
    }
    $wishlistinfo  = $wishlist->getwishlistInfo($StoreID);
    $wishlistinfo['youtubevideo']	=	getobjurl($wishlistinfo['youtubevideo'])?getobjurl($wishlistinfo['youtubevideo']):"";

    $wishlistbanner = $wishlist->getWishlistTemplate($wishlistinfo['banner']);
    $smarty->assign('wishinfo',$wishlistbanner);
    $banner_img_info=getimagesize('.'.$wishlistbanner['banner']);
    $banner_width=$banner_img_info[0];
    $banner_height=$banner_img_info[1];
    $smarty->assign('banner_img',array('width'=>$banner_width,'height'=>$banner_height));

    $smarty->assign('wishinfodetail',$wishlistinfo);
    $ispass = true;
    if($wishlistinfo['isprotected']&&strtolower($wishlistinfo['password'])!="") {
        if($_SESSION['StoreWishlist'][$StoreID]) {
            $ispass = true;
        }else {
            if($_SESSION['wishlistPreview']){
                $ispass = TRUE;
                unset($_SESSION['wishlistPreview']);
                $_SESSION['StoreWishlist'][$StoreID] = TRUE;
            }else{
                $ispass = false;
                $content = $smarty->fetch('wishlist/tmplate-protected.tpl');
            }
        }
    }
    $smarty->assign('weburl',$securt_url);
    if($ispass) {
        if(isset($_SESSION['wishpage'][$StoreID])) {
            $_GET['p'] = $_SESSION['wishpage'][$StoreID];
            $_REQUEST['p'] = $_SESSION['wishpage'][$StoreID];
        }else {
            $_SESSION['wishpage'][$StoreID] = 1;
            $_GET['p'] = $_SESSION['wishpage'][$StoreID];
            $_REQUEST['p'] = $_SESSION['wishpage'][$StoreID];
        }

        if(isset($_GET['wish'])&&$_GET['wish']!="") {
            $wishproInfo = $wishlist->getwishProByName($_GET['wish'],$StoreID);
            if(!$wishproInfo) {
                echo "<script>alert('Wishlist product does not exist.');location.href='/$site/wishlist';</script>";
                exit;
            }
            setCounter($StoreID,$wishproInfo['pid'],'WISHLIST');
            $wishproList = $wishlist->wishlistItemsProduct($StoreID,$wishproInfo['pid'],true);
            if($wishproList[0]['protype']) {
                $wishproList[0]['fotgive'] = round($wishproList[0]['price'],2);
            }else {
                $wishproList[0]['fotgive'] = round($wishproList[0]['price'],2)-round($wishproList[0]['gifted'],2);
            }
            $smarty -> assign('prolist',$wishproList);
            $wishpro = count($wishlist->getWishlistProlist($StoreID));
            $smarty -> assign('procount',$wishpro);
            $smarty -> assign('detalpage',"1");
            $content = $smarty->fetch('wishlist/tmplate-a.tpl');
        }else {
            setCounter($StoreID,0,'WISHLIST');
            if($wishlistinfo['template']=='a') {
                $wishproList = $wishlist->wishlistItemsProduct($StoreID,0,true);
                if($wishproList[0]['protype']) {
                    $wishproList[0]['fotgive'] = $wishproList[0]['price'];
                }else {
                    $wishproList[0]['fotgive'] = round($wishproList[0]['price'],2)-round($wishproList[0]['gifted'],2);
                }
            }else {
                $wishproList = $wishlist->getWishlistProlist($StoreID,'featured',true);
            }
            $smarty -> assign('prolist',$wishproList);
            $wishpro = count($wishlist->getWishlistProlist($StoreID));
            $smarty -> assign('procount',$wishpro);
            $content = $smarty->fetch('wishlist/tmplate-'.$wishlistinfo['template'].'.tpl');
        }
    }
    $smarty->assign('modcp','wishlist');
    $smarty->assign('content',$content);
	$smarty->assign('hide_race_banner',1);
	$smarty->assign('isstorepage',1);

    $smarty -> display($template_tpl);
    unset($smarty);
}elseif(strtolower($item)=="gallery") {
    include("gallery_home.php");
}

exit;

?>
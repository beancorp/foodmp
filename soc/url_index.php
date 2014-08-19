<?php
/**
 * soc contrl file
 * Tue Feb 5 15:52:12 CST 2008 15:52:12
 * @author  : Ping.Hu <hh_ping@163.com>
 * @version : V1.0
 * ------------------------------------------------------------
 * index.php
 */
//@session_start();
include_once ('include/smartyconfig_url.php');
include_once ('session.php');
include_once ('class.soc.php');
include_once ('class.page.php');
include_once ('class.uploadImages.php');
include_once ('class/pagerclass.php');
include_once ('class.wishlist.php');
include_once ('class.socbid.php');
include_once ('class.FoodWine.php');

$smarty -> assign('pageTitle',$_LANG['pageTitle']);
$smarty -> assign('keywords',$_LANG['keywords']);
$smarty -> assign('description',$_LANG['description']);
$socObj = new socClass();

$template_colours = array(
	1 => 48, // Restaurents
	2 => 45, // Liquor 
	3 => 39, // Bakery 
	4 => 42, // Seafood
	5 => 46, // Meat
	6 => 43, // Fruitveg
	7 => 47, // Bar, pubs
	8 => 41, // Fast food
	9 => 40, // Cafe
	10 => 44 // Juice
);

//display logo or not
unset($_SESSION['logo_old']);
unset($_SESSION['logo_new']);
$is_logo = '';
$menu_bgcolor = '';
$menu_bottom = '';

if ( ($_REQUEST['cp'] == 'home')
        || ($_REQUEST['cp'] == 'category')
        || ($_REQUEST['cp'] == 'features')
        || ($_REQUEST['act'] == 'signon' && empty($_REQUEST['step']))
        || ($_REQUEST['cp'] == 'cgeton')
        || ($_REQUEST['cp'] == 'faq')
        || ($_REQUEST['cp'] == 'payments')
        || ($_REQUEST['cp'] == 'prolist')
        || ($_REQUEST['cp'] == 'sample') ) {

    $_SESSION['logo_new'] = true;

}elseif ( ($_REQUEST['cp'] == 'contact')
        || ($_REQUEST['cp'] == 'protection')
        || ($_REQUEST['cp'] == 'disprolist')
        || ($_REQUEST['cp'] == 'about') ) {

    $_SESSION['logo_old'] = true;
}

//end of display logo or not
$smarty -> assign('securt_url',$securt_url);
$smarty -> assign('normal_url',$normal_url);
$smarty->assign('soc_https_host',SOC_HTTPS_HOST);

switch($_REQUEST["cp"]) {

    //display store
    default:
        //auto, estate, career and Single paid default Template A
        $socObj->resetTemplate();
        
        $req	=	$socObj -> displayStoreWebside(false,true);
		
		$promo_store_codes_query = "SELECT code FROM promo_store_codes WHERE store_id = '".$_REQUEST['StoreID']."'";
		$promo_store_result = $dbcon->getOne($promo_store_codes_query);
		
		if (is_array($promo_store_result)) {
			$smarty->assign('promo_store_code', $promo_store_result['code']);
		}
		
		// $free_signup = false;
		// $account_enabled = false;

		// if (isset($free_signup_result[0])) {
			// $free_signup_data = $free_signup_result[0];
			// if ($free_signup_data['free_signup'] == 1) {
				// $free_signup = true;
			// }
			// if ($free_signup_data['status'] == 1) {
				// $account_enabled = true;
			// }
		// }
		
        if (($req['info']['attribute'] == 1 || $req['info']['attribute'] == 2 || $req['info']['attribute'] == 3) && count($req['items']['product']) == 0 && $req['info']['StoreID'] != $_SESSION['ShopID']) {
        	echo "<script>alert('Sorry. You don\'t have permission to access this page.');location.href='/soc.php?cp=home';</script>";
            exit;
        }
        
        /*if ($req['info']['attribute'] == 5) {
        	$image = $req['images']['mainImage'][2]['bname']['text'];
        } else {
        	$image = $req['images']['mainImage'][0]['bname']['text'];
        }        
        if (substr($image, 0, 1) == '/') {
        	$image = substr($image, 1);
        }*/
		

        if($req['info']['attribute'] == 5 && !$req['info']['is_popularize_store']) {
            $is_foodwine_home = true;
            $smarty->assign('show_join_banner', 1);
            $smarty->assign('show_season_banner', 1);
            $smarty->assign('is_foodwine_home', $is_foodwine_home);
        }

        $image = 'skin/red/images/logo-main.png';
                
        $req['info']['bu_email'] = $_SESSION['email'];
        $req['info']['facelike_image'] = $image ? SOC_HTTP_HOST.$image : SOC_HTTP_HOST.'skin/red/images/logo-main.png';
        $req['info']['facelike_title'] = $req['info']['bu_name'];
        $req['info']['facelike_desc'] = addslashes(strip_tags($req['info']['description']));
        $req['info']['like_type'] = 'store';

        $rooturl = "http://{$_SERVER['HTTP_HOST']}";
        $homeurl = "$rooturl/{$req['info']['bu_urlstring']}";
        $req['widgetHTML']="<div align=\"center\" style=\"width:208px\"><a target=\"_blank\" href=\"$homeurl\"><img border=0 src=\"$rooturl/skin/red/images/soc-link-logo.jpg\" width=\"208\"/></a></div>";
        $templateInfo = $socObj -> getTemplateInfo();
        $smarty -> assign('templateInfo', $templateInfo);
        $smarty -> assign('sellerhome', 1);
        $smarty -> assign('req',	$req);
        $smarty -> assign('pageTitle',$req['info']['bu_name']." ".$req['info']['bu_suburb']." : ".$req['info']['subAttribName'].' : Food Market Place');
        $custom_seo_keywords = TRUE;
        $smarty -> assign('keywords',$req['info']['subAttribName'].",".$req['info']['bu_suburb'].",".$req['info']['bu_state']);
        $is_set_desc = TRUE;
        $smarty -> assign('description','Learn more about this '.$req['info']['subAttribName'].' local retailer in '.$req['info']['bu_suburb']);
        $smarty->assign('HostName',$_SERVER['HTTP_HOST']);
        $smarty->assign('UserID', $_SESSION['ShopID']);
        $search_type = $req['info']['sellerType'];
		//$smarty->assign('account_enabled', $account_enabled);
		
		
		// $smarty->assign('free_signup', $free_signup);
		// if ($free_signup) {
			// $select_detail = "SELECT * FROM aus_soc_bu_detail WHERE StoreID = '".$_REQUEST['StoreID']."'";
			// $dbcon->execute_query($select_detail);
			// $detail_result = $dbcon->fetch_records();
			// if (isset($detail_result[0])) {
				// $detail_data = $detail_result[0];
				// $delivery_items = unserialize($detail_data['bu_delivery_text']);
				// $delivery_values = unserialize($detail_data['bu_delivery']);
				// $smarty->assign('delivery_items', $delivery_items);
				// $smarty->assign('delivery_values', $delivery_values);
				// $smarty->assign('mobile_hide', $detail_data['mobile_hide']);
				// $smarty->assign('mobile', $detail_data['mobile']);
				// $smarty->assign('fax_hide', $detail_data['fax_hide']);
				// $smarty->assign('bu_fax', $detail_data['bu_fax']);
				
				// $payment_options_query = "SELECT * FROM aus_soc_payment_options op INNER JOIN aus_soc_payment_store_options sop ON sop.payment_option = op.option_id AND store_id = '".$_REQUEST['StoreID']."'";
				// $dbcon->execute_query($payment_options_query);
				// $payment_option_result = $dbcon->fetch_records();
				// $payment_options = array();
				
				// if (!empty($detail_data['bu_paypal'])) {
					// $payment_options[] = array('name' => 'Paypal', 'image' => '/skin/red/images/payment/paypal.png');
				// }
				
				// //if (!empty($detail_data['bu_eway'])) {
				// //	$payment_options[] = array('name' => 'Eway', 'image' => '/skin/red/images/eway.jpg');
				// //}
				
				// foreach($payment_option_result as $option_data) {
					// $payment_options[] = array('name' => $option_data['option_name'], 'image' => $option_data['option_image']);
				// }
				
				// $smarty->assign('payment_options', $payment_options);
			// }
		// }
		
        //popularize store
        if ($req['info']['is_popularize_store']) {	
			$template_color = $socObj->getTemplateColor($template_colours[$req['info']['subAttrib']]);
			$req['template'] = array('bgcolor' => $template_color);
			$smarty->assign('req', $req);
		    $smarty->assign('itemTitle', $socObj->getTextItemTitle($req['info']['subAttribName'], 4, $template_color));
		    $content = $smarty->fetch('foodwine/template/foodwine-freelisting.tpl');
		    $smarty->assign('content', $content);
        } else {
	        $smarty->assign('headerInfo', $req['info']);
	        $tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
	        $smarty->assign('tmp_header', $tmp_header);
	        if ($req['template']['tpl_type'] > 1) {
	            $smarty -> loadLangFile($search_type.'/index');
	            $strTplPath	=	$search_type.'/template/';
	            if(in_array($_REQUEST['name'],getSamplesitebyNames($samplesiteid))) {
	                $smarty -> assign('is_samplestie',1);
	            }
	            $product_content	=	$smarty -> fetch($strTplPath. $req['template']['TemplateName'] . '.tpl');
	            $smarty -> assign('content', $product_content);
	            if(in_array($req['template']['TemplateName'],array('job-c','auto-c'))) {
	                if($req['items']['product'][0]['item_name']!="") {
	                    $smarty -> assign('pageTitle',$req['items']['product'][0]['item_name']);
	                }
	            }elseif ($req['template']['TemplateName']=='estate-c') {
	                if($req['items']['product'][0]['suburbName']!="") {
	                    $smarty -> assign('pageTitle',"{$req['items']['product'][0]['suburbName']},{$req['items']['product'][0]['stateName']},{$req['items']['product'][0]['location']}");
	                }
	            }
	        } else {
				// $fetch_user = "SELECT * FROM aus_soc_login WHERE StoreID = '".$_REQUEST['StoreID']."'";
				// $dbcon->execute_query($fetch_user);
				// $user_result = $dbcon->fetch_records();
				// if (isset($user_result[0])) {
					// $user_data = $user_result[0];
					// if ($user_data['status'] == 2) {
						// $smarty->assign('verified_image','<div style="float: right;"><img src="'.SOC_HTTP_HOST.'verified.png" height="30px"  /></div>');
					// }
				// }
				
	            $strTplPath	=	'template/';
	            if(in_array($_REQUEST['name'],getSamplesitebyNames($samplesiteid))) {
	                $smarty -> assign('is_samplestie',1);
	            }
	            
				$product_content	=	$smarty -> fetch($strTplPath. $req['template']['TemplateName'] . '.tpl');
				$smarty -> assign('product_content', $product_content);
				$shop_summary = $smarty->fetch($strTplPath.'tmp-shop-summary.tpl');
				$smarty->assign('content',$shop_summary);
	
	            if($req['template']['TemplateName']=='tmp-n-e') {
	                if($req['items']['product'][0]['item_name']!="") {
	                    $smarty -> assign('pageTitle',$req['items']['product'][0]['item_name']);
	                }
	            }
	
	        }
		}
        
        $smarty->assign('noShowGalleryBanner', true);
        $smarty -> assign('sidebar', 0);
        $smarty->assign('is_website',1);
        $smarty->assign('isstorepage',1);
        $_SESSION['logo_old'] = true;
        break;
}

//display logo or not

if ( $_SESSION['logo_new'] === true ) {

    $is_logo = 'true';
    $menu_bgcolor = ' bgcolor="#65BFF3"';  //show the new left banner logo, set background color.
    $menu_bottom =  ' bgcolor="#65BFF3"';

}elseif ($_SESSION['logo_old'] === true) {

    $menu_bottom = ' class="ltpanel_bot_slogo"';  //show old logo
    $menu_bgcolor = ' background="images/ltpanel_bgrep.jpg"';  //show old logo
    $is_logo = 'true';

}else {
    $menu_bgcolor = ' class="ltpanel_bot"';	//other set
    $menu_bottom = ' class="ltpanel_bot"';	//other set
}
$smarty->assign('nottv',1);

$smarty -> assign('menu_bgcolor', $menu_bgcolor);
$smarty -> assign('menu_bottom', $menu_bottom);
$smarty -> assign('is_logo', $is_logo);
//end of display logo or not
if($req['info']['attribute']!='0') {
    $smarty -> assign('is_content',1);
}

include_once('leftmenu.php');
unset($socObj);
include_once('soc/seo.php');
$smarty -> display($template_tpl);
unset($smarty);
exit;
?>
<?php
/**
 * Tue Nov 04 14:43:26 GMT 2008 14:43:26
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * offer control
 * ------------------------------------------------------------
 * \soc\offer.php
 */
 
@session_start();
include_once ('include/smartyconfig.php');
include_once ('maininc.php');
require_once 'Pager/Pager.php';
include_once ('xajax/xajax_core/xajax.inc.php');
include_once ('class.soc.php');
include_once ('class.socbid.php');
include_once ('class.socreview.php');
include_once ('functions.php');
require_once ('class.offerClass.php');
include_once ('class.emailClass.php');

$objOfferClass = new offerClass();

$socObj = new socClass();

$socbidObj = new socbidClass();
$socreviewObj = new socReviewClass();

$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');

//display logo or not
unset($_SESSION['logo_old']);
unset($_SESSION['logo_new']);

$is_logo = 'true';
$menu_bgcolor = ' bgcolor="#65BFF3"';  //show the new left banner logo, set background color.
$menu_bottom =  ' bgcolor="#65BFF3"';
$keywordsList = 'flat rate selling, how to sell online, online trading post, sell goods online, sell items online, sell products online, sell stuff online, sell things online, selling online, simple selling online';

$_SESSION['logo_new'] = true;


switch($_REQUEST["cp"]){
	case 'offerlist':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Offer List');
		if($_SESSION['UserID']=='' AND $_SESSION['level']!=1){
			header("Location:index.php");
		}
		
		$xajax 			= new xajax();
		$xajax -> registerFunction('offerList');
		$xajax -> registerFunction('offerAccept');
		$xajax -> registerFunction('offerViewReview');
		$xajax -> registerFunction('offerViewEmail');
		$xajax -> registerFunction('offerDelete');
		$xajax -> registerFunction('resendEmail');
		$xajax -> registerFunction('activeCoupon');
		$xajax -> processRequest();
		$req['xajax_Javascript']  = $xajax -> getJavascript('/include/xajax');
		
		$smarty -> assign('xajax_Javascript', $req['xajax_Javascript']);
		
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Offer List',3));
		
		$req['offer'] = $objOfferClass -> offerList();
		$smarty -> assign('req', $req);
		$content	.=	$smarty -> fetch('obo_offer_list.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		unset($xajax);
		break;
	
	case 'review':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Offer List');
		$is_logo	=	false;
		$xajax 		= new xajax();
		$xajax -> registerFunction('offerList');
		$xajax -> registerFunction('offerReview');
		$xajax -> processRequest();
		$req['xajax_Javascript']  = $xajax -> getJavascript('/include/xajax');
		
		$smarty -> assign('xajax_Javascript', $req['xajax_Javascript']);
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Contact the Seller',3));
		$req['offer'] = $objOfferClass -> offerReview();
		$smarty -> assign('req', $req);
		$content	.=	$smarty -> fetch('obo_offer_review.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		unset($xajax);
		break;
		
	default:
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Offer List');
		$objOfferClass = new offerClass();
		if (!empty($_POST)) {
			if ($objOfferClass->offerSave()){
				$req	=	 $objOfferClass -> offerSet();
				$req['msg']	=	urldecode($_SESSION["pageParam"]["msg"]);
				$req['display']	=	'msg';
				$objOfferClass->destroyFormInputVar();
			}
		}else{
			$req	=	 $objOfferClass -> offerSet();
		}
		$req['LOGIN']	=	$_SESSION['LOGIN'];
		$req['buyerNickname']	=	$_SESSION['UserName'];
		$req['email']	=	$_SESSION['email'];
		$req['level']	=	$_SESSION['level'];
		$smarty -> assign('req', $req);
		$smarty -> display('obo_offer.tpl');
		unset($objOfferClass);
		exit;
		break;
}


$smarty-> assign('is_content',1);

//active the menu of top navigation
$smarty->assign('cp', $_REQUEST["cp"]);
$smarty->assign('requesturi', $_SERVER['REQUEST_URI']);

if (!empty($_SESSION)) {
	$userData = $_SESSION;
	$storeName = getStoreURLNameById($userData['StoreID']);
	if (!empty($storeName)) {
		$userData['website'] = clean_url_name($storeName);
	}
	$smarty -> assign('session', $userData);
}

$states = $socObj->getStatesList();
$smarty -> assign('states', $states);
$smarty -> assign('locations', $states);

if ($_REQUEST['statename'] && $_REQUEST['collegeid']) {
	$smarty -> assign('stateOnLoad', 	"selectCollegebyName('collegeobj', '". $statename ."&collegeid=".$_REQUEST['collegeid']."');");
}

$smarty -> assign('menu_bgcolor', $menu_bgcolor);
$smarty -> assign('menu_bottom', $menu_bottom);
$smarty -> assign('is_logo', $is_logo);
//end of display logo or not

unset($socObj);
include_once('soc/seo.php');
$smarty -> assign('menu_bgcolor', $menu_bgcolor);
$smarty -> display('index.tpl');
unset($smarty,$objOfferClass);
exit;
?>
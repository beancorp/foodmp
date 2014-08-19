<?php
@session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');
include_once ('class.adminproduct.php');
require_once 'Pager/Pager.php';
include_once ('xajax/xajax_core/xajax.inc.php');

//check login
$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}

$objAdminPro 	= new adminProduct();
$xajax 			= new xajax();

//control
switch($_REQUEST["cp"]){
	case 'cat':
		$req['header']	=	$objAdminPro->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('getProductCategory');
		$xajax -> registerFunction('categoryAdd');
		$xajax -> registerFunction('categoryDelete');
		$xajax -> registerFunction('categoryUpdate');
		$xajax -> registerFunction('categoryUpdateOperate');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['cateParent']	=	$objAdminPro -> getProductCategory();
		$req['categoryList']=	$objAdminPro -> getProductCategory(0);
		
		$smarty -> assign('req',	$req);
		$content	.=	$smarty -> fetch('admin_product_category.tpl');
		$smarty -> assign('content', $content);
		
		break;
		
	case 'catadset':
		$req	= $objAdminPro -> getProductCategory();
		$req['header']	=	$objAdminPro->lang['header'][$_REQUEST["cp"]];
		$req['ad_left'] = $objAdminPro->initEditor('ad_left', "",'adminDefault',array('100%',200));
		$req['ad_right'] = $objAdminPro->initEditor('ad_right', "",'adminDefault',array('100%',200));
		$req['ad_bottom'] = $objAdminPro->initEditor('ad_bottom', "",'adminDefault',array('100%',200));

		$xajax -> registerFunction('displayCategoryItem');
		$xajax -> registerFunction('saveCategoryAds');
		$xajax -> processRequest();
		
		$req['xajax_Javascript']   .= $xajax -> getJavascript('/include/xajax');
		
		$smarty -> assign('req',	$req);
		$content	.=	$smarty -> fetch('admin_category_ad_set.tpl');
		$smarty -> assign('content', $content);
		break;
		
	case 'catartset':
		$req['header']	=	$objAdminPro->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('saveCategoryArticle');
		$xajax -> registerFunction('deleteCategoryArticle');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$cgid = isset($_REQUEST['cgid']) ? $_REQUEST['cgid'] :1 ;
		if (isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit') {
			if (empty($_POST)) {
				$req['article'] = $objAdminPro -> getCategoryArticle();
				$smarty -> assign('req',	$req);
				$content	.=	$smarty -> fetch('admin_category_article_set_input.tpl');
			}
		}else{
			$req['catlist']  = $objAdminPro -> getlistProductCategory();
			$req['list']  = $objAdminPro->getCategoryArticleList($cgid,$_REQUEST['p']);
			$smarty -> assign('req',	$req);
			$content	.=	$smarty -> fetch('admin_category_article_set.tpl');
		}
		
		$smarty -> assign('content', $content);
		break;
		
	case 'catfeat':
		$req['header']	=	$objAdminPro->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('saveFeaturedCategories');
		$xajax -> registerFunction('getfeathProCat');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['categoryList']	=	$objAdminPro -> getProductCategory(0,false);
		if (!empty($_POST)) {
			$req['msg'] = 'Featured Categoires have been saved.';
		}
		$smarty -> assign('req',	$req);
		$content	.=	$smarty -> fetch('admin_featured_category.tpl');
		$smarty -> assign('content', $content);
		break;
	case 'season':
		$req['header']	=	$objAdminPro->lang['header']['season'];
		$xajax -> registerFunction('getSeasonProductList');
		$xajax -> registerFunction('getSeasonProductListSearch');
		$xajax -> registerFunction('deletePartnerSiteList');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['list'] 	=	$objAdminPro -> getSeasonProductList();
		$req['order'] = $_REQUEST['order'];
		$req['field'] = $_REQUEST['field'];
		$req['seasons'] = getSeasonArray();
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_product_season.tpl');
		$smarty -> assign('content', $content);
		$req['Menu']["season"] = "style='color:#FF0000;font-weight:bold;'";
		
		break;
		
	case 'editseason':
		
		$req['header']	=	$objAdminPro->lang['header']['editseason'];
		if(isset($_REQUEST['pid'])&&$_REQUEST['pid']!=""){
			$req['info'] = $objAdminPro->getSeasonProductInfo($_REQUEST['pid']);
		}
		if(isset($_POST)&&!empty($_POST)){
			$arrSetting = array(
				'desc' => $_REQUEST['desc'],
				'varities' => $_REQUEST['varities']
			);
                        $season_arr = $_REQUEST['season'];
                        $type_arr = $_REQUEST['type'];
                        $arrSetting['season_ids'] = implode(',', $season_arr);
                        $arrSetting['typeids'] = implode(',', $type_arr);
			if($_REQUEST['pid']!=""){
				$pid = $_REQUEST['pid'];
				$strCondition ="where pid='$pid'";
				if ($dbcon-> update_record($table."season_product", $arrSetting, $strCondition)) {
					$msg = "Edit season product successfully.";
					$req['msg'] = $msg;
					$req['isok'] = 'yes';
				}else{
					$msg = "Faild to edit season product.";
				}
			}elseif (false){
				if ($dbcon-> insert_record($table."season_product", $arrSetting)) {
					$msg = "Create season product successfully.";
					$req['msg'] = $msg;
					$req['isok'] = 'yes';
				}else{
					$msg = "Faild to create season product.";
					$req['info'] = $arrSetting;
				}
			}
		}
		$req['seasons'] = getSeasonArray();
		$smarty->assign('req',$req);
		$content = $smarty->fetch('admin_productseason_form.tpl');
		$smarty->assign('content',$content);
		$_REQUEST["cp"] = "editseason";
		$req['Menu']["season"] = "style='color:#FF0000;font-weight:bold;'";
		break;
}
$req['Menu']["{$_REQUEST["cp"]}"] = "style='color:#FF0000;font-weight:bold;'";
$smarty -> assign('req',	$req);

unset($objAdminPro,$req,$xajax);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
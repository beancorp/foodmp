<?php
/**
 * Fri Oct 09 08:18:48 GMT+08:00 2008 08:18:48
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * main function control
 * ------------------------------------------------------------
 * \admin\main.php
 */
 
session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');
include_once ('class.adminmain.php');
include_once ('class.adminhelp.php');
include_once ('class.adminjokes.php');
include_once ('class.adminrace.php');
require_once 'Pager/Pager.php';
include_once ('xajax/xajax_core/xajax.inc.php');

//check login

//echo session_id();
$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}
$dbcon = $GLOBALS['dbcon'];
$objAdminMain 	= new adminMain();
$objAdminHelp 	= new adminHelp();
$objAdminJokes  = new adminJokes();
$xajax 			= new xajax();

//control
switch($_REQUEST["cp"]){
	case 'paypal':
		$req['header']	= "Paypal Information";
		$xajax -> registerFunction('savePaypalInfo');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$req['info'] = $objAdminMain->getPaypalInfo();
		
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_paypal.tpl');
		$smarty -> assign('content', $content);
		break;
		
	case 'eway':
		$req['header']	= "Eway Information";
		$xajax -> registerFunction('saveEwayInfo');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$req['info'] = $objAdminMain->getEwayInfo();
		
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_eway.tpl');
		$smarty -> assign('content', $content);
		break;
		
	case 'facelikerace':
		
		
		$req['header']	= "Facebook Sprints Information";
		$xajax -> registerFunction('saveFacelikeRaceInfo');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$req['info'] = $objAdminMain->getFacelikeInfo();
		
		$objAdminRace = new adminRace();
		$smarty -> assign('hour',$objAdminRace -> get_Hour());
		$smarty -> assign('req',	$req);
		$smarty->assign('soc_http_host',SOC_HTTP_HOST);
		$smarty->assign('PBDateFormat',DATAFORMAT_DB);
		$content = $smarty -> fetch('admin_facelikerace.tpl');
		$smarty -> assign('content', $content);
		break;
		
	case 'cms':
		$req['header']	=	$objAdminMain->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('displayCMSItem');
		$xajax -> registerFunction('saveCMSItem');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['cmslist'] =	$objAdminMain -> getCMSItemName();
		$req['input']	=	$objAdminMain -> getCMSItemEdit();
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_cms.tpl');
		$smarty -> assign('content', $content);

		break;
		
	case 'freshreport':
		if (isset($_POST['submitButton'])) {
			if (isset($_POST['content'])) {
				$data = array(
							'body' => addslashes($_POST['content'])
						);
				
				if ($dbcon->update_query("aus_soc_cms" , $data, " WHERE id='119'")) {
					$data['updated'] = time();
					$objAdminMain->setFreshProduceReport($data);
					$smarty->assign('saved', 'Food report saved');
				}
				
				if ($_POST['sendreminder']) {
					header("Location: /admin/?act=msg&cp=sendreminder");
				}
			}
		}
	
		$req['header']	=	$objAdminMain->lang['header'][$_REQUEST["cp"]];
		//$xajax -> registerFunction('displayCMSItem');
		//$xajax -> registerFunction('saveCMSItem');
		//$xajax -> processRequest();
		//$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['input']	=	$objAdminMain -> getCMSItemEdit(119);
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_freshreport.tpl');
		$smarty -> assign('content', $content);

		break;
		
	case 'freshreportrecords':
		$req['header']	=	$objAdminMain->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('displayFreshProduceReportCMSItem');
		$xajax -> registerFunction('delFreshProduceReportCMSItem');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['cmslist'] =	$objAdminMain -> getFreshProduceReportList();
		$req['input']	=	$objAdminMain -> getCMSItemEdit();
		$smarty -> assign('req',	$req);
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB.' %H:%M:%S');
		$content = $smarty -> fetch('admin_freshreportrecords.tpl');
		$smarty -> assign('content', $content);

		break;

	case 'help':
		$req['header']	= "Help page";
		$xajax -> registerFunction('displayHelpItem');
		$xajax -> registerFunction('saveHelpItem');
		$xajax -> registerFunction('deleteItem');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['cmslist'] =	$objAdminHelp -> getHelpItemName();
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_help.tpl');
		$smarty -> assign('content', $content);
		break;
	case 'customer':
		if ($_REQUEST["user_id"] > 0){
			$smarty			= &$GLOBALS['smarty'];
			$objAdminMain 	= &$GLOBALS['objAdminMain'];
			$req['list'] =	$objAdminMain -> profileUserView($_REQUEST["user_id"]);
			$smarty -> assign('req',	$req);
			$smarty-> assign('view_profile_from_url',1);
			
			$content = $smarty -> fetch('admin_customer_view.tpl');
			$smarty -> assign('content', $content);
			
		}else{
			$req['header']	=	$objAdminMain->lang['header'][$_REQUEST["cp"]];
			$xajax -> registerFunction('customerGetList');
			$xajax -> registerFunction('customerSearch');
			$xajax -> registerFunction('customerView');
			$xajax -> registerFunction('customerDelete');
			$xajax -> processRequest();
			$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
			
			$req['list'] =	$objAdminMain -> customerGetList();
			$smarty -> assign('req',	$req);
			$content = $smarty -> fetch('admin_customer.tpl');
			$smarty -> assign('content', $content);
		}

		break;
	
	case 'pass':
		$req['header']	=	$objAdminMain->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('savePassword');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_password.tpl');
		$smarty -> assign('content', $content);
		break;
		
	case 'jokes':
		$req['header']	= "SOC Stars";
		$xajax -> registerFunction('displayJokesItem');
		$xajax -> registerFunction('saveJokesItem');
		$xajax -> registerFunction('deleteJokesItem');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['cmslist'] =	$objAdminJokes -> getJokesItemName();
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_jokes.tpl');
		$smarty -> assign('content', $content);
		break;
	default:
		$smarty -> assign('req',	$req);
		$smarty -> assign('content', $content);
		break;

}
$req['Menu']["{$_REQUEST["cp"]}"] = "style='color:#FF0000;font-weight:bold;'";
$smarty -> assign('req',	$req);

unset($objAdminMain,$req,$xajax);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
<?php
/**
 * Fri Oct 10 16:09:36 GMT+08:00 2008 16:09:36
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * payment control
 * ------------------------------------------------------------
 * \admin\payment.php
 */
 
@session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');
include_once ('class.adminpayment.php');
require_once 'Pager/Pager.php';
include_once ('xajax/xajax_core/xajax.inc.php');

$path = ROOT_PATH.'include/excel';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
include_once 'excel/Spreadsheet/Excel/Writer.php';

//check login
$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}

$smarty -> loadLangFile('soc');

$objAdminPayment 	= new adminPayment();
$xajax 			= new xajax();

//control
switch($_REQUEST["cp"]){
	case 'daterep':
		$req['header']	=	$objAdminPayment->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('paymentDetailsDateWiseReports');
		$xajax -> registerFunction('paymentDetailsDateWiseReportsSearch');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['list'] =	$objAdminPayment -> paymentDetailsDateWiseReports();
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_payment.tpl');
		$smarty -> assign('content', $content);
		break;

	case 'storerep':
		$req['header']	=	$objAdminPayment->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('paymentDetailsDateWiseReportsStore');
		$xajax -> registerFunction('paymentDetailsDateWiseReportsSearchStore');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['list'] =	$objAdminPayment -> paymentDetailsDateWiseReportsStore();
		$req['list']['store']	=	$objAdminPayment -> getOrderStoreName();
		$req['display']	= $_REQUEST["cp"];
		
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_payment.tpl');
		$smarty -> assign('content', $content);
		break;
		
	case 'commission':
		$req['header']	=	$objAdminPayment->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('saveCommission');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$req['info'] = $objAdminPayment->getCommission();
		
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_commission.tpl');
		$smarty -> assign('content', $content);
		break;
		
	case 'purchase':
		if(isset($_POST['act_export'])&&trim($_POST['act_export'])=="Export"){
			
			$arySet['fromDate'] =$_REQUEST['fromDate'];
			$arySet['toDate'] =$_REQUEST['toDate'];
			$arySet['attribute'] =$_REQUEST['attribute'];
			$arySet['p_status'] =$_REQUEST['p_status'];
			$arySet['commission_type'] =$_REQUEST['commission_type'];
			$data = $objAdminPayment->exportpurchaseRecords($arySet);
			$name = "Purchase_Records.xls";
			$workbook = new Spreadsheet_Excel_Writer(); 
			$workbook->send($name); 
			
			$worksheet =& $workbook->addWorksheet('sheet-1');
			for ($row = 0; $row < count($data); $row ++) {
				$coltotol = count($data[0]);
				$col = 0;
				foreach ($data[0] as $key=>$val){
					if($col<$coltotol){
						$worksheet->writeString($row, $col, $data[$row][$key]); 
					}
					$col++;
				}
			}
			$workbook->close();
			exit();
		}
		if(isset($_POST['act_export'])&&trim($_POST['act_export'])=="Masspay"){
			
			$arySet['fromDate'] =$_REQUEST['fromDate'];
			$arySet['toDate'] =$_REQUEST['toDate'];
			$arySet['attribute'] =$_REQUEST['attribute'];
			$arySet['p_status'] =$_REQUEST['p_status'];
			$arySet['commission_type'] =$_REQUEST['commission_type'];
			$data = $objAdminPayment->exportpurchaseRecordsPaymentInfo($arySet);
			$name = "Purchase_Records.xls";
			$workbook = new Spreadsheet_Excel_Writer(); 
			$workbook->send($name); 
			
			$worksheet =& $workbook->addWorksheet('sheet-1');
			for ($row = 0; $row < count($data); $row ++) {
				$coltotol = count($data[0]);
				$col = 0;
				foreach ($data[0] as $key=>$val){
					if($col<$coltotol){
						$worksheet->writeString($row, $col, $data[$row][$key]); 
					}
					$col++;
				}
			}
			$workbook->close();
			exit();
		}
		$req['header']	=	'Purchase Records';
		$xajax -> registerFunction('getpurchaseRecords');
		$xajaxViewMessage = $xajax->registerFunction('viewPurchase');
		
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$req['list'] = $objAdminPayment->purchaseRecords();
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB);
		$smarty -> assign('req',$req);
		$content = $smarty -> fetch('admin_purchase.tpl');
		$smarty -> assign('content',$content);
		break;
	case 'catrep':

		break;
	
	case 'adrep':

		break;
	
	case 'giftrep':

		break;
	
	case 'refrep':

		break;
	
	default:

		break;

}
$req['Menu']["{$_REQUEST["cp"]}"] = "style='color:#FF0000;font-weight:bold;'";
$smarty -> assign('req',	$req);

unset($objAdminEmail,$req,$xajax,$objLogin);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
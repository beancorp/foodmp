<?php
/**
 * Fri Oct 10 08:18:03 GMT+08:00 2008 08:18:03
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * email function control
 * ------------------------------------------------------------
 * \admin\email.php
 */
 
@session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');
include_once ('class.adminemail.php');
require_once 'Pager/Pager.php';
include_once ('xajax/xajax_core/xajax.inc.php');

//check login
$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}

$objAdminEmail 	= new adminEmail();
$xajax 			= new xajax();

$path = ROOT_PATH.'include/excel';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
include_once 'excel/Spreadsheet/Excel/Writer.php';

if(isset($_POST['act_report'])&&$_POST['act_report']=='Export'){
	if(in_array($_REQUEST["cp"],array('reportsubscribe'))){
		$data = $objAdminEmail->exportSubscriberlist();	
	}
	switch ($_REQUEST['cp']){
		case 'reportsubscribe':
			$name = "Fresh_Produce_Report_Subscribers.xls";
			break;
		default:
			$name = "export.xls";
			break;
	}
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

//control
switch($_REQUEST["cp"]){
	case 'storemailreport':
		$req['header']	=	$objAdminEmail->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('StoreWiseEmailReportList');
		$xajax -> registerFunction('StoreWiseEmailReportListShow');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['list'] =	$objAdminEmail -> StoreWiseEmailReportList();
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_email.tpl');
		$smarty -> assign('content', $content);
		break;
	case 'reportsubscribe':
		$req['header']	=	$objAdminEmail->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('ReportSubscriberList');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['list'] =	$objAdminEmail -> ReportSubscriberList();
		$smarty -> assign('req',	$req);
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB);
		$content = $smarty -> fetch('admin_report_subscribe.tpl');
		$smarty -> assign('content', $content);
		break;

	case 'cusmailreport':
		$req['header']	=	$objAdminEmail->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('CustomerWiseEmailReportList');
		$xajax -> registerFunction('CustomerWiseEmailReportListShow');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['list'] =	$objAdminEmail -> CustomerWiseEmailReportList();

		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_email_cus.tpl');
		$smarty -> assign('content', $content);
		break;
	
	case 'export':
		$req['header']	=	$objAdminEmail->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('CustomerWiseEmailReportList');
		$xajax -> registerFunction('CustomerWiseEmailReportListShow');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['list'] =	$objAdminEmail -> StoreWiseEmailReportExport();

		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_email_export.tpl');
		$smarty -> assign('content', $content);
		break;
		
	default:
		$smarty -> assign('req',	$req);
		$smarty -> assign('content', $content);
		break;

}
$req['Menu']["{$_REQUEST["cp"]}"] = "style='color:#FF0000;font-weight:bold;'";
$smarty -> assign('req',	$req);

unset($objAdminEmail,$req,$xajax);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
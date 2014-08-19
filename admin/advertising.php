<?php
/**
 * Wed Oct 15 13:54:54 GMT+08:00 2008 13:54:54
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * advertising function control
 * ------------------------------------------------------------
 * E:\hhp\web\soc\admin\advertising.php
 */

@session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');
include_once ('class.adminadvertising.php');
require_once ('Pager/Pager.php');
include_once ('xajax/xajax_core/xajax.inc.php');
require_once('class/pagerclass.php');

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

$objAdminAdv 	= new adminAdv();
$xajax 			= new xajax();

//control
switch($_REQUEST["cp"]){
	case 'allbanner':
		//$req['header']	=	$objAdminAdv->lang['header'][$_REQUEST["cp"]];
		$req['header']	=	"All States/ States/ Default Banner" ;
		$xajax -> registerFunction('getBannerAllAndDefaultList');
		$xajax -> registerFunction('getBannerAllAndDefaultListSearch');
		$xajax -> registerFunction('getBannerAllAndDefaultListSearch2');
		$xajax -> registerFunction('addBannerAllAndDefault');
		$xajax -> registerFunction('editBannerAllAndDefault');
		$xajax -> registerFunction('saveBannerAllAndDefault');
		$xajax -> registerFunction('deleteBannerAllAndDefault');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$req['input']['title'] = $objAdminAdv -> saveBannerAllAndDefault2($_POST);
//                        $_SESSION['ADMIN_BANNER_JUMP'] = $_POST['searchparam'];
                        $url = '/admin/?act=adv&cp=allbanner&title=' . $req['input']['title'];
                        header('Location: ' . $url);
//			$req['list'] =	$objAdminAdv -> getBannerAllAndDefaultList();
		}else {

			$req['list'] =	$objAdminAdv -> getBannerAllAndDefaultList();
		}
                $req['input']['title'] = $_REQUEST['title'];
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB);
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_banner_all.tpl');
		$smarty -> assign('content', $content);
		break;

	case 'state':
		$req['header']	=	$objAdminAdv->lang['header'][$_REQUEST["cp"]];
		$xajax -> registerFunction('getBannerStateList');
		$xajax -> registerFunction('getBannerStateSearch');
		$xajax -> registerFunction('addBannerState');
		$xajax -> registerFunction('editBannerState');
		$xajax -> registerFunction('saveBannerState');
		$xajax -> registerFunction('deleteBannerState');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$req['input']['title'] = $objAdminAdv -> saveBannerAllAndDefault($_POST);
			$req['list'] =	$objAdminAdv -> getBannerStateList($_POST['pageno'],$_POST['searchparam']);
		}else {
			$req['list'] =	$objAdminAdv -> getBannerStateList();
		}
		
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_banner_state.tpl');
		$smarty -> assign('content', $content);
		break;
	case 'export':
		$arrParam['start_date'] = 0; 
		$arrParam['end_date'] = 0; 
		
		if(DATAFORMAT_DB=="%m/%d/%Y"){
			if($_POST['s_start_date']!=""){
				list($month,$day,$year) = split('/',$_POST['s_start_date']);
				$arrParam['start_date'] = mktime(0,0,0,$month,$day,$year);
			}
			if($_POST['s_end_date']!=""){
				list($month,$day,$year) = split('/',$_POST['s_end_date']);
				$arrParam['end_date'] 	= mktime(0,0,0,$month,$day,$year);
			}
		}else{
			if($_POST['s_start_date']!=""){
				list($day,$month,$year) = split('/',$_POST['s_start_date']);
				$arrParam['start_date'] = mktime(0,0,0,$month,$day,$year);
			}
			if($_POST['s_end_date']!=""){
				list($day,$month,$year) = split('/',$_POST['s_end_date']);
				$arrParam['end_date'] 	= mktime(0,0,0,$month,$day,$year);
			}
		}
		$arrParam['sid'] = $_POST['sid'];
		$arrParam['state_id'] = $_POST['state_id'];
		$arrParam['po'] = $_POST['po'];
                $arrParam['search_markets'] = $_POST['search_markets'];
		$data = $objAdminAdv->exportBannerAllAndDefaultList(serialize($arrParam));
		$name = "banner.xls";
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
		break;
}

$req['Menu']["{$_REQUEST["cp"]}"] = "style='color:#FF0000;font-weight:bold;'";
$smarty -> assign('req',	$req);

unset($objAdminAdv,$req,$xajax);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
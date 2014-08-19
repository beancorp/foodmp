<?php
@session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');
include_once ('class.adminreport.php');
require_once ('Pager/Pager.php');
include_once ('xajax/xajax_core/xajax.inc.php');

//check login
$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}
$smarty -> loadLangFile('soc');

$objAdminReport = new adminReport();
$xajax 			= new xajax();

//control
switch($_REQUEST["cp"]){
	case 'college':
			$colleges = $objAdminReport->getColleges($_REQUEST['stateid']);
			$str = "<select name='college' class='seletcs'>";
			$str .= "<option value=''>All</option>";
			foreach ($colleges as $key=>$val){
				$str .= "<option value='$key'>$val</option>";
			}
			$str .="</select>";
			echo $str;
			exit();
		break;
	default:
		$req['header']	=	"Report for Competition";
		$xajax -> registerFunction('getReportList');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$opt = isset($_REQUEST['opt'])?$_REQUEST['opt']:'';
		$req['selected']['selletype'] 	   = "-1";
		if($opt!=""){
			if($_REQUEST['start_date']!=""){
				if(DATAFORMAT_DB=="%m/%d/%Y"){
					list($month,$day,$year) = split('/',$_REQUEST['start_date']);
					if($_REQUEST['end_date']!=""){
						list($e_month,$e_day,$e_year) = split('/',$_REQUEST['end_date']);
					}
				}else{
					list($day,$month,$year) = split('/',$_REQUEST['start_date']);
					if($_REQUEST['end_date']!=""){
						list($e_day,$e_month,$e_year) = split('/',$_REQUEST['end_date']);
					}
				}
				$startDate = mktime($_REQUEST['s_hour'],($_REQUEST['s_min']!="")?intval($_REQUEST['s_min']):0,0,$month,$day,$year);
				if($_REQUEST['end_date']!=""){
					$enddate = mktime($_REQUEST['e_hour'],($_REQUEST['e_min']!="")?intval($_REQUEST['e_min']):0,59,$e_month,$e_day,$e_year);
				}else{
					$enddate = time();
				}
			}
			$req['selected']['start_date'] = $_REQUEST['start_date'];
			$req['selected']['end_date']   = $_REQUEST['end_date'];
			$req['selected']['s_hour'] 	   = $_REQUEST['s_hour'];
			$req['selected']['s_min'] 	   = ($_REQUEST['s_min']!=""&&intval($_REQUEST['s_min'])==0)?"00":$_REQUEST['s_min'];
			$req['selected']['e_hour'] 	   = $_REQUEST['e_hour'];
			$req['selected']['e_min'] 	   = ($_REQUEST['e_min']!=""&&intval($_REQUEST['e_min'])==0)?"00":$_REQUEST['e_min'];
			$req['selected']['state'] 	   = $_REQUEST['state'];
			$req['selected']['selletype']  = $_REQUEST['selletype'];
			$req['selected']['mebship']  = $_REQUEST['mebship'];
			
			$req['selected']['gender']  = $_REQUEST['gender'];
			$req['selected']['college']  = $_REQUEST['college'];
			$req['selected']['inrenew']  = $_REQUEST['inrenew'];
			
			$_SESSION['reportSearch']['selletype'] = $req['selected']['selletype'];
			$_SESSION['reportSearch']['startDate'] = $startDate;
			$_SESSION['reportSearch']['enddate'] = $enddate;
			$_SESSION['reportSearch']['state'] = $req['selected']['state'];
			$_SESSION['reportSearch']['mebship'] = $req['selected']['mebship'];
			$_SESSION['reportSearch']['college'] = $req['selected']['college'];
			$_SESSION['reportSearch']['gender'] = $req['selected']['gender'];
			$_SESSION['reportSearch']['inrenew'] = $req['selected']['inrenew'];
			
			$req['reportlist'] = $objAdminReport -> getReportList($startDate,$enddate,$_REQUEST['selletype'],$_REQUEST['state'],$_REQUEST['gender'],$_REQUEST['college'],$curpage,$_REQUEST['mebship'],$_REQUEST['inrenew']);
			if($_REQUEST['act_report']=='Export'){
				$data = $objAdminReport -> getExcelList($startDate,$enddate,$_REQUEST['selletype'],$_REQUEST['state'],$_REQUEST['gender'],$_REQUEST['college'],$_REQUEST['mebship'],$_REQUEST['inrenew']);
				$path = ROOT_PATH.'include/excel';
				set_include_path(get_include_path() . PATH_SEPARATOR . $path);
				include_once 'excel/Spreadsheet/Excel/Writer.php';
				$workbook = new Spreadsheet_Excel_Writer(); 
				$workbook->send('signupreport.xls'); 

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
		}
		if($req['selected']['state']!=""){
			$stateid = $req['selected']['state'];
		}else{
			$stateid = "0";
		}
		$smarty -> assign('college',$objAdminReport ->getColleges($stateid));
		$smarty -> assign('hour',$objAdminReport -> getHour());
		$smarty -> assign('sellertype',$objAdminReport -> getSellerType());
		$smarty -> assign('state',$objAdminReport -> getStateList());
		$smarty	-> assign('req',$req);
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB." %H:%M");
		$content = $smarty -> fetch('admin_report.tpl');
		$smarty -> assign('content', $content);
		break;
}
$req['Menu']["report"] = "style='color:#FF0000;font-weight:bold;'";
$smarty -> assign('req',	$req);
unset($objAdminReport,$req,$xajax);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
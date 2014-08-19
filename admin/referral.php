<?php
@session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');
include_once ('class.emailClass.php');
include_once ('class.adminrefer.php');
include_once ('functions.php');
include_once ('class.soc.php');
require_once ('Pager/Pager.php');
include_once ('class.adminreport.php');
include_once ('xajax/xajax_core/xajax.inc.php');

$path = ROOT_PATH.'include/excel';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
include_once 'excel/Spreadsheet/Excel/Writer.php';

unset($_SESSION['refSearch']['state']);
unset($_SESSION['refSearch']['college']);
//check login
$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}
$smarty -> loadLangFile('soc');

$objAdminRefer 	= new adminRefer();
$objAdminReport = new adminReport();
$xajax 			= new xajax();
$socObj 		= new socClass();

/**search date**/
$startDate = 0;
$enddate = 0;
if(DATAFORMAT_DB=="%m/%d/%Y"){
	if($_REQUEST['start_date']!=""){
		list($month,$day,$year) = split('/',$_REQUEST['start_date']);
		$startDate = mktime($_REQUEST['s_hour'],($_REQUEST['s_min']!="")?intval($_REQUEST['s_min']):0,0,$month,$day,$year);
	}
	if($_REQUEST['end_date']!=""){
		list($e_month,$e_day,$e_year) = split('/',$_REQUEST['end_date']);
		$enddate = mktime($_REQUEST['e_hour'],($_REQUEST['e_min']!="")?intval($_REQUEST['e_min']):0,59,$e_month,$e_day,$e_year);
	}else{
		$enddate = time();
	}
}else{
	if($_REQUEST['start_date']!=""){
		list($day,$month,$year) = split('/',$_REQUEST['start_date']);
		$startDate = mktime($_REQUEST['s_hour'],($_REQUEST['s_min']!="")?intval($_REQUEST['s_min']):0,0,$month,$day,$year);
	}
	if($_REQUEST['end_date']!=""){
		list($e_day,$e_month,$e_year) = split('/',$_REQUEST['end_date']);
		$enddate = mktime($_REQUEST['e_hour'],($_REQUEST['e_min']!="")?intval($_REQUEST['e_min']):0,59,$e_month,$e_day,$e_year);
	}else{
		$enddate = time();
	}		
}
if(is_array($_REQUEST['state'])){
	$stateparam = implode(',',$_REQUEST['state']);
}else{
	$stateparam = $_REQUEST['state'];
}
if(isset($_POST['act_report'])&&$_POST['act_report']=='Export'){
	if(in_array($_REQUEST["cp"],array('ref_list','ref_payment','ref_payReport'))){
		$data = $objAdminRefer->exportReferlist($startDate,$enddate,$_REQUEST['nickname'],$_REQUEST['usertype'],$_REQUEST['paymethod'],$stateparam,$_REQUEST['college'],$_REQUEST['status'],$_SESSION['refSearch']['ispayment']);	
	}
	switch ($_REQUEST['cp']){
		case 'ref_list':
			$name = "Referral_Report_Search.xls";
			break;
		case 'ref_payment':
			$name = "Payment_Requested_Report.xls";
			break;
		case 'ref_payReport':
			$name = "Payment_Report.xls";
			break;
		case 'ref_report':
			$name = "Referrer_ID_Report.xls";
			$referID = isset($_REQUEST['referID'])?$_REQUEST['referID']:"";
			if(get_magic_quotes_gpc()){
				$referID = stripslashes($referID);
			}
			$data = $objAdminRefer ->exportRefID($referID);
			break;
		case 'ref_config':
			$name = "Special_Referral_Rates.xls";
			$data = $objAdminRefer ->exportconfig();
			break;
		case 'ref_user':
			$name = "Special_Referral_Report.xls";
			$data = $objAdminRefer ->exportRefreport($_REQUEST['StoreID']);
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
	case "ref_list":
		$req['disp'] = 'referrer';
		$req['header']	=	"Referral Report/Search";
		$xajax -> registerFunction('getReferList');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');

		if(is_array($_REQUEST['state'])){
			$stateparam = implode(',',$_REQUEST['state']);
		}else{
			$stateparam = $_REQUEST['state'];
		}
		$opt = isset($_REQUEST['opt'])?$_REQUEST['opt']:'';
		if($opt!=""){
			
			if(DATAFORMAT_DB=="%m/%d/%Y"){
				if($_REQUEST['start_date']!=""){
					list($month,$day,$year) = split('/',$_REQUEST['start_date']);
					$startDate = mktime($_REQUEST['s_hour'],($_REQUEST['s_min']!="")?intval($_REQUEST['s_min']):0,0,$month,$day,$year);
				}
				if($_REQUEST['end_date']!=""){
					list($e_month,$e_day,$e_year) = split('/',$_REQUEST['end_date']);
					$enddate = mktime($_REQUEST['e_hour'],($_REQUEST['e_min']!="")?intval($_REQUEST['e_min']):0,59,$e_month,$e_day,$e_year);
				}else{
					$enddate = time();
				}
			}else{
				if($_REQUEST['start_date']!=""){
					list($day,$month,$year) = split('/',$_REQUEST['start_date']);
					$startDate = mktime($_REQUEST['s_hour'],($_REQUEST['s_min']!="")?intval($_REQUEST['s_min']):0,0,$month,$day,$year);
				}
				if($_REQUEST['end_date']!=""){
					list($e_day,$e_month,$e_year) = split('/',$_REQUEST['end_date']);
					$enddate = mktime($_REQUEST['e_hour'],($_REQUEST['e_min']!="")?intval($_REQUEST['e_min']):0,59,$e_month,$e_day,$e_year);
				}else{
					$enddate = time();
				}
				
			}
			$req['selected']['college']    = $_REQUEST['college'];
			$req['selected']['start_date'] = $_REQUEST['start_date'];
			$req['selected']['end_date']   = $_REQUEST['end_date'];
			$req['selected']['s_hour'] 	   = $_REQUEST['s_hour'];
			$req['selected']['e_hour'] 	   = $_REQUEST['e_hour'];
			$req['selected']['state'] 	   = explode(',',$stateparam);
			$req['selected']['status'] 	   = $_REQUEST['status'];
			if(get_magic_quotes_gpc()){
				$nickname = stripslashes($_REQUEST['nickname']);
			}else{
				$nickname = $_REQUEST['nickname'];
			}
			$req['selected']['nickname']  = htmlspecialchars($nickname);
			$req['selected']['usertype']  = $_REQUEST['usertype'];
			$req['selected']['paymethod'] = $_REQUEST['paymethod'];
			
			$_SESSION['refSearch']['startDate']= $startDate;
			$_SESSION['refSearch']['enddate']  = $enddate;
			$_SESSION['refSearch']['nickname'] = $_REQUEST['nickname'];
			$_SESSION['refSearch']['usertype'] = $_REQUEST['usertype'];
			$_SESSION['refSearch']['paymethod']= $_REQUEST['paymethod'];
			$_SESSION['refSearch']['order']= $_REQUEST['order'];
			$_SESSION['refSearch']['state'] = $stateparam;
			$_SESSION['refSearch']['status'] = $_REQUEST['status'];
			$_SESSION['refSearch']['college']    = $_REQUEST['college'];	
			$url = "&opt=send&nickname=".urlencode($nickname)."&usertype={$_REQUEST['usertype']}&paymethod={$_REQUEST['paymethod']}&start_date={$_REQUEST['start_date']}&end_date={$_REQUEST['end_date']}&s_hour={$_REQUEST['s_hour']}&e_hour={$_REQUEST['e_hour']}&state={$stateparam}&college={$_REQUEST['college']}";
		}else{
			$req['selected']['state'] = array('');
		}
		$_SESSION['refSearch']['ispayment']= false;	
		$_SESSION['refSearch']['payreport']	= false;
		$req['order'] = $_REQUEST['order'];
		$req['field'] = $_REQUEST['field'];
		$req['sorturl'] = $url;
		$req['reflist'] = $objAdminRefer -> getReferList($startDate,$enddate,$_REQUEST['nickname'],$_REQUEST['usertype'],$_REQUEST['paymethod'],$stateparam,$_REQUEST['college'],$_REQUEST['status'],$_REQUEST['field'],$_REQUEST['order']);
		$smarty -> assign('hour',$objAdminRefer -> get_Hour());
		$req['disp_from']  = 'yes';
		$smarty	-> assign('req',$req);
		$statelist = array();
		$statelist['']='Select State';
		foreach ($objAdminReport->getStateList() as $pass){
			$statelist[$pass['id']] = $pass['description']."(".$pass['stateName'].")";
		}
		$smarty -> assign('state',$statelist);
		if(count(explode(',',$stateparam))=="1"){
			$stateid = $stateparam;
		}else{
			$stateid = "0";
		}
		$smarty -> assign('college',$objAdminReport ->getColleges($stateid));
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB);
		$content = $smarty -> fetch('admin_refer.tpl');
		$smarty -> assign('content', $content);
		break;
	case "ref_payment":
		$req['disp'] = 'payment';
		$req['header']	=	"Payment  Requested Report";
		$xajax -> registerFunction('getReferList');
		$xajax -> registerFunction('getexportnum');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$startDate = 0;
		$enddate = 0;
		$opt = isset($_REQUEST['opt'])?$_REQUEST['opt']:'';
		if(isset($_REQUEST['optval'])&&$_REQUEST['optval']=="saveform"){
			$startDate		=	$_SESSION['refSearch']['startDate'];
			$enddate 		=	$_SESSION['refSearch']['enddate'] ;
			$nickname		=	$_SESSION['refSearch']['nickname'];
			$_REQUEST['usertype']=$_SESSION['refSearch']['usertype'];
			$_REQUEST['paymethod']=$_SESSION['refSearch']['paymethod'];
			$_REQUEST['order'] =	$_SESSION['refSearch']['order'];
			$_REQUEST['field'] =	$_SESSION['refSearch']['field'];
			
			$_REQUEST['start_dat'] = $req['selected']['start_date'] = $_SESSION['refSearch']['start_date'];
			$_REQUEST['end_date'] = $req['selected']['end_date']   =$_SESSION['refSearch']['end_date'];
			$_REQUEST['s_hour'] = $req['selected']['s_hour'] 	   = $_SESSION['refSearch']['s_hour'];
			$_REQUEST['e_hour'] = $req['selected']['e_hour'] 	   = $_SESSION['refSearch']['e_hour'];
			$_REQUEST['nickname'] = $nickname;
			$req['selected']['nickname']  = htmlspecialchars($nickname);
			$_REQUEST['usertype']=$req['selected']['usertype']  = $_SESSION['refSearch']['usertype'];
			$_REQUEST['paymethod']=$req['selected']['paymethod'] = $_SESSION['refSearch']['paymethod'];
			$j=0;
			if($_POST['sendmoney']){
				foreach ($_POST['sendmoney'] as $key=>$pass){
					if(floatval($pass)>0){
						if(eregi('^Sent ',$socObj->sendcheque($key,$pass))){
							$j++;
						}
					}
				}
			}
			if($j>1){
				$req['msg'] = "$j users have been sent.";
			}else{
				$req['msg'] = "$j user have  been sent.";
			}
		}else{
		if($opt!=""){
			if(DATAFORMAT_DB=="%m/%d/%Y"){
				if($_REQUEST['start_date']!=""){
					list($month,$day,$year) = split('/',$_REQUEST['start_date']);
					$startDate = mktime($_REQUEST['s_hour'],($_REQUEST['s_min']!="")?intval($_REQUEST['s_min']):0,0,$month,$day,$year);
				}
				if($_REQUEST['end_date']!=""){
					list($e_month,$e_day,$e_year) = split('/',$_REQUEST['end_date']);
					$enddate = mktime($_REQUEST['e_hour'],($_REQUEST['e_min']!="")?intval($_REQUEST['e_min']):0,59,$e_month,$e_day,$e_year);
				}else{
					$enddate = time();
				}
			}else{
				if($_REQUEST['start_date']!=""){
					list($day,$month,$year) = split('/',$_REQUEST['start_date']);
					$startDate = mktime($_REQUEST['s_hour'],($_REQUEST['s_min']!="")?intval($_REQUEST['s_min']):0,0,$month,$day,$year);
				}
				if($_REQUEST['end_date']!=""){
					list($e_day,$e_month,$e_year) = split('/',$_REQUEST['end_date']);
					$enddate = mktime($_REQUEST['e_hour'],($_REQUEST['e_min']!="")?intval($_REQUEST['e_min']):0,59,$e_month,$e_day,$e_year);
				}else{
					$enddate = time();
				}
				
			}
				$req['selected']['start_date'] = $_REQUEST['start_date'];
				$req['selected']['end_date']   = $_REQUEST['end_date'];
				$req['selected']['s_hour'] 	   = $_REQUEST['s_hour'];
				$req['selected']['e_hour'] 	   = $_REQUEST['e_hour'];
				if(get_magic_quotes_gpc()){
					$nickname = stripslashes($_REQUEST['nickname']);
				}else{
					$nickname = $_REQUEST['nickname'];
				}
				$req['selected']['nickname']  = htmlspecialchars($nickname);
				$req['selected']['usertype']  = $_REQUEST['usertype'];
				$req['selected']['paymethod'] = $_REQUEST['paymethod'];
				
				$_SESSION['refSearch']['start_date'] = $_REQUEST['start_date'];
				$_SESSION['refSearch']['end_date']   = $_REQUEST['end_date'];
				$_SESSION['refSearch']['s_hour'] 	   = $_REQUEST['s_hour'];
				$_SESSION['refSearch']['e_hour'] 	   = $_REQUEST['e_hour'];
				$_SESSION['refSearch']['startDate']= $startDate;
				$_SESSION['refSearch']['enddate']  = $enddate;
				$_SESSION['refSearch']['nickname'] = $_REQUEST['nickname'];
				$_SESSION['refSearch']['usertype'] = $_REQUEST['usertype'];
				$_SESSION['refSearch']['paymethod']= $_REQUEST['paymethod'];
				$_SESSION['refSearch']['order']= $_REQUEST['order'];	
				$_SESSION['refSearch']['field']= $_REQUEST['field'];
			}
		}
		if(get_magic_quotes_gpc()){
			$nickname = stripslashes($_REQUEST['nickname']);
		}else{
			$nickname = $_REQUEST['nickname'];
		}
		$url = "&opt=send&nickname=".urlencode($nickname)."&usertype={$_REQUEST['usertype']}&paymethod={$_REQUEST['paymethod']}&start_date={$_REQUEST['start_date']}&end_date={$_REQUEST['end_date']}&s_hour={$_REQUEST['s_hour']}&e_hour={$_REQUEST['e_hour']}&state={$_REQUEST['state']}";
		$_SESSION['refSearch']['ispayment']= true;
		$_SESSION['refSearch']['payreport']	= false;	
		$req['order'] = $_REQUEST['order'];
		$req['field'] = $_REQUEST['field'];
		$req['sorturl'] = $url;
		$req['reflist'] = $objAdminRefer -> getReferList($startDate,$enddate,$_REQUEST['nickname'],$_REQUEST['usertype'],$_REQUEST['paymethod'],"","","",$_REQUEST['field'],$_REQUEST['order'],true);
		$smarty -> assign('hour',$objAdminRefer -> get_Hour());
		$smarty	-> assign('req',$req);
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB);
		$content = $smarty -> fetch('admin_refer.tpl');
		$smarty -> assign('content', $content);
		break;
	case 'ref_user':
		
		$xajax -> registerFunction('Referpage');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB);
		if(isset($_REQUEST['request_send'])&&$_REQUEST['request_send']=="send"){
			$msg = $socObj->sendcheque($_REQUEST['StoreID'],floatval($_REQUEST['amount']));
		}
		$req['order'] = $_REQUEST['order'];
		$req['field'] = $_REQUEST['field'];
		$req['StoreID'] = $_REQUEST['StoreID'];
		$req['page'] = $_REQUEST['page']?$_REQUEST['page']:1;
		$req['ref'] = $socObj->getBuyerRefer($_REQUEST['StoreID'],$_REQUEST['field'],$_REQUEST['order'],$_REQUEST['page']?$_REQUEST['page']:1,1);
		$req['statu'] = $socObj->getRefUserStatus($_REQUEST['StoreID']);
		$req['msg'] = $msg;
		$smarty->assign('req',$req);
		$content = $smarty -> fetch('admin_refer_user.tpl');
		$smarty -> assign('content', $content);
		break;
	case 'ref_payReport':
		$req['disp'] = 'payreport';
		$req['header']	=	"Payment Report";
		$xajax -> registerFunction('getReferList');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$startDate = 0;
		$enddate = 0;
		$opt = isset($_REQUEST['opt'])?$_REQUEST['opt']:'';
		if($opt!=""){
			if(DATAFORMAT_DB=="%m/%d/%Y"){
				if($_REQUEST['start_date']!=""){
					list($month,$day,$year) = split('/',$_REQUEST['start_date']);
					$startDate = mktime($_REQUEST['s_hour'],($_REQUEST['s_min']!="")?intval($_REQUEST['s_min']):0,0,$month,$day,$year);
				}
				if($_REQUEST['end_date']!=""){
					list($e_month,$e_day,$e_year) = split('/',$_REQUEST['end_date']);
					$enddate = mktime($_REQUEST['e_hour'],($_REQUEST['e_min']!="")?intval($_REQUEST['e_min']):0,59,$e_month,$e_day,$e_year);
				}else{
					$enddate = time();
				}
			}else{
				if($_REQUEST['start_date']!=""){
					list($day,$month,$year) = split('/',$_REQUEST['start_date']);
					$startDate = mktime($_REQUEST['s_hour'],($_REQUEST['s_min']!="")?intval($_REQUEST['s_min']):0,0,$month,$day,$year);
				}
				if($_REQUEST['end_date']!=""){
					list($e_day,$e_month,$e_year) = split('/',$_REQUEST['end_date']);
					$enddate = mktime($_REQUEST['e_hour'],($_REQUEST['e_min']!="")?intval($_REQUEST['e_min']):0,59,$e_month,$e_day,$e_year);
				}else{
					$enddate = time();
				}
				
			}
			$req['selected']['start_date'] = $_REQUEST['start_date'];
			$req['selected']['end_date']   = $_REQUEST['end_date'];
			$req['selected']['s_hour'] 	   = $_REQUEST['s_hour'];
			$req['selected']['e_hour'] 	   = $_REQUEST['e_hour'];
			$req['selected']['state'] 	   = $_REQUEST['state'];
			if(get_magic_quotes_gpc()){
				$nickname = stripslashes($_REQUEST['nickname']);
			}else{
				$nickname = $_REQUEST['nickname'];
			}
			$req['selected']['nickname']  = htmlspecialchars($nickname);
			$req['selected']['usertype']  = $_REQUEST['usertype'];
			$req['selected']['paymethod'] = $_REQUEST['paymethod'];
		}
		$_SESSION['refSearch']['startDate']= $startDate;
		$_SESSION['refSearch']['enddate']  = $enddate;
		$_SESSION['refSearch']['nickname'] = $_REQUEST['nickname'];
		$_SESSION['refSearch']['usertype'] = $_REQUEST['usertype'];
		$_SESSION['refSearch']['paymethod']= $_REQUEST['paymethod'];
		$_SESSION['refSearch']['order']= $_REQUEST['order'];	
		$_SESSION['refSearch']['field']= $_REQUEST['field'];
		$url = "&opt=send&nickname=".urlencode($nickname)."&usertype={$_REQUEST['usertype']}&paymethod={$_REQUEST['paymethod']}&start_date={$_REQUEST['start_date']}&end_date={$_REQUEST['end_date']}&s_hour={$_REQUEST['s_hour']}&e_hour={$_REQUEST['e_hour']}&state={$_REQUEST['state']}";

		$_SESSION['refSearch']['ispayment'] = true;
		$_SESSION['refSearch']['payreport']	= true;
		$req['order'] = $_REQUEST['order'];
		$req['field'] = $_REQUEST['field'];
		$req['sorturl'] = $url;
		$req['reflist'] = $objAdminRefer -> getReferList($startDate,$enddate,$_REQUEST['nickname'],$_REQUEST['usertype'],$_REQUEST['paymethod'],"","","",$_REQUEST['field'],$_REQUEST['order'],true);
		$smarty -> assign('hour',$objAdminRefer -> get_Hour());
		$smarty	-> assign('req',$req);
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB);
		$content = $smarty -> fetch('admin_refer.tpl');
		$smarty -> assign('content', $content);
		break;
	case 'downlist':
		$xajax -> registerFunction('getdownlist');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$req['header']	=	"Export Report";
		$req['order'] = $_REQUEST['order'];
		$req['field'] = $_REQUEST['field'];
		$req['reflist'] = $objAdminRefer->getexportlist($_REQUEST['field'],$_REQUEST['order']);
		$smarty	-> assign('req',$req);
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB);
		$content = $smarty -> fetch('admin_ref_download.tpl');
		$smarty -> assign('content', $content);
		break;
	case 'download':
		$file = $_REQUEST['file'];
		header( "Content-type: text/plain");
		header( "Content-Disposition: attachment; filename=\"paypalexport.txt\"" );
		header( "Expires: 0" ); // set expiration time
		header( "Cache-Component: must-revalidate, post-check=0, pre-check=0" );
		header( "Pragma: public" );
		readfile(ROOT_PATH.'/upload/download/'.$file);
		exit();
		break;
	case 'ref_report':
		$xajax -> registerFunction('getReferById');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$req['header'] = "Referrer ID Report";
		$referID = isset($_REQUEST['referID'])?$_REQUEST['referID']:"";
		if(get_magic_quotes_gpc()){
			$referID = stripslashes($referID);
		}
		$pageid = isset($_REQUEST['pageid'])&&intval($_REQUEST['pageid'])>0?intval($_REQUEST['pageid']):"1";
		$url = "&referID=$referID&pageid=$pageid";
		$req['selected']['referID'] = $referID;
		$req['reflist'] = $objAdminRefer ->getreferrerbyRefID($referID,$pageid,$_REQUEST['field'],$_REQUEST['order']);
		$req['order'] = $_REQUEST['order'];
		$req['field'] = $_REQUEST['field'];
		$req['sorturl'] = $url;
		$smarty->assign('req',$req);
		$smarty->assign('PBDateFormat',DATAFORMAT_DB);
		$content=$smarty -> fetch('admin_refer_report.tpl');
		$smarty->assign('content', $content);
		break;
	default:
		$xajax -> registerFunction('getconfiglist');
		$xajax -> registerFunction('saveReferConfig');
		$xajax -> registerFunction('getReferConfig');
		$xajax -> registerFunction('delReferConfig');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$req['header']	=	"Standard Referral Configuration";
		if(isset($_REQUEST['opt'])&&$_REQUEST['opt']=="save"){
			$confary['percent'] =  intval($_REQUEST['percent']);
			$confary['min_commission'] =  floatval($_REQUEST['min_commission']);
			$confary['min_refer'] =  floatval($_REQUEST['min_refer']);
			if($socObj -> saveRefconfig($confary)){
				$req['msg'] = "Saved successfully.";
			}else{
				$req['msg'] = "Faild to save.";
			}
		}
		$field = isset($_REQUEST['field'])?$_REQUEST['field']:"";
		$order = isset($_REQUEST['order'])?$_REQUEST['order']:"asc";
		$page = isset($_REQUEST['page'])?$_REQUEST['page']:'1';
		$req['field']=$field;
		$req['order']=$order;
		$req['page']=$page;
		$req['config'] = $socObj -> getRefconfig();
		$req['configlist'] = $objAdminRefer ->getconfiglist($page,$field,$order);
		$smarty	-> assign('req',$req);
		$content = $smarty -> fetch('admin_refer_conf.tpl');
		$smarty -> assign('content', $content);
		break;
}
$req['Menu'][$_REQUEST["cp"]] = "style='color:#FF0000;font-weight:bold;'";
$smarty -> assign('req',	$req);
unset($objAdminPromotion,$req,$xajax);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
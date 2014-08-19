<?php
@session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');
include_once ('class.emailClass.php');
include_once ('class.adminrace.php');
include_once ('functions.php');
include_once ('class.soc.php');
require_once ('Pager/Pager.php');
include_once ('class.adminreport.php');
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

$objAdminRace 	= new adminRace();
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

//control
switch($_REQUEST["cp"]){
	case "race_list":
		$req['disp'] = 'Race';
		$req['header']	= "Race Report";
		$xajax -> registerFunction('getRaceList');
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
					$enddate = '';
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
					$enddate = '';
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
			$_SESSION['refSearch']['s_hour']= $_REQUEST['s_hour'];
			$_SESSION['refSearch']['enddate']  = $enddate;
			$_SESSION['refSearch']['e_hour']= $_REQUEST['e_hour'];
			$_SESSION['refSearch']['nickname'] = $_REQUEST['nickname'];
			$_SESSION['refSearch']['usertype'] = $_REQUEST['usertype'];
			$_SESSION['refSearch']['order']= $_REQUEST['order'];
			$_SESSION['refSearch']['state'] = $stateparam;
			$url = "&opt=send&nickname=".urlencode($nickname)."&usertype={$_REQUEST['usertype']}&paymethod={$_REQUEST['paymethod']}&start_date={$_REQUEST['start_date']}&end_date={$_REQUEST['end_date']}&s_hour={$_REQUEST['s_hour']}&e_hour={$_REQUEST['e_hour']}&state={$stateparam}&college={$_REQUEST['college']}";
		}else{
			$req['selected']['state'] = array('');
			unset($_SESSION['refSearch']);
		}
		$_SESSION['refSearch']['ispayment']= false;	
		$_SESSION['refSearch']['payreport']	= false;
		$_SESSION['refSearch']['field']	= $_REQUEST['field'];
		$_SESSION['refSearch']['order']= $_REQUEST['order'];
		$req['order'] = $_REQUEST['order'];
		$req['field'] = $_REQUEST['field'];
		$req['sorturl'] = $url;
		$req['reflist'] = $objAdminRace -> getRaceList($startDate,$enddate,$_REQUEST['nickname'],$_REQUEST['usertype'],$_REQUEST['paymethod'],$stateparam,$_REQUEST['college'],$_REQUEST['status'],$_REQUEST['field'],$_REQUEST['order']);
		$smarty -> assign('hour',$objAdminRace -> get_Hour());
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
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB);
		$content = $smarty -> fetch('admin_race.tpl');
		$smarty -> assign('content', $content);
		break;
	
	case 'record_list':
		include_once ('class.point.php');
		$objPoint = new Point();
		
		$StoreID = $_REQUEST['StoreID'];
		$req['pointinfo'] = $objPoint->getPointInfo($StoreID);
		$req['order'] = $_REQUEST['order'];
		$req['field'] = $_REQUEST['field'];
		$req['StoreID'] = $_REQUEST['StoreID'];
		//$req['page'] = $_REQUEST['page']?$_REQUEST['page']:1;
		//$req['ref'] = $socObj->getBuyerRefer($_REQUEST['StoreID'],$_REQUEST['field'],$_REQUEST['order'],$_REQUEST['page']?$_REQUEST['page']:1,1);
		//$req['statu'] = $socObj->getRefUserStatus($_REQUEST['StoreID']);
		$req['msg'] = $msg;
		$smarty->assign('req',$req);
		$content = $smarty -> fetch('admin_race_record_list.tpl');
		$smarty -> assign('content', $content);
		break;
		
	case 'partner_site':
		$req['header']	=	$objAdminRace->lang['header']['partner_site'];
		$xajax -> registerFunction('getPartnerSiteList');
		$xajax -> registerFunction('getPartnerSiteListSearch');
		$xajax -> registerFunction('deletePartnerSiteList');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['list'] 	=	$objAdminRace -> getPartnerSiteList();
		$req['order'] = $_REQUEST['order'];
		$req['field'] = $_REQUEST['field'];
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_partner_site.tpl');
		$smarty -> assign('content', $content);
		$req['Menu']["partner_site"] = "style='color:#FF0000;font-weight:bold;'";
		break;
		
	case 'add_partner_site':
		$req['header']	=	$objAdminRace->lang['header']['add_partner_site'];
		if(isset($_REQUEST['sid'])&&$_REQUEST['sid']!=""){
			$req['info'] = $objAdminRace->getPartnerSiteInfo($_REQUEST['sid']);
		}
		if(isset($_POST)&&!empty($_POST)){
			$arrSetting = array(
				'site_name' => $_REQUEST['site_name'],
				'domain' => $_REQUEST['domain'],
				'point' => $_REQUEST['point'],
				'max_time' => $_REQUEST['max_time'],
				'deleted' => $_REQUEST['deleted'],
			);
			$arrSetting['domain'] = str_replace('http://', '', $arrSetting['domain']);
			if($_REQUEST['sid']!=""){
				$sid = $_REQUEST['sid'];
				$strCondition ="where id='$sid'";
				if ($dbcon-> update_record($table."point_site", $arrSetting, $strCondition)) {
					$msg = "Edit partner site successfully.";
					$req['msg'] = $msg;
					$req['isok'] = 'yes';
				}else{
					$msg = "Faild to edit partner site.";
				}
			}else{
				$arrSetting['add_time'] = time();
				if ($dbcon-> insert_record($table."point_site", $arrSetting)) {
					$msg = "Create partner site successfully.";
					$req['msg'] = $msg;
					$req['isok'] = 'yes';
				}else{
					$msg = "Faild to create partner site.";
					$req['info'] = $arrSetting;
				}
			}
		}
		$smarty->assign('req',$req);
		$content = $smarty->fetch('admin_partnersite_form.tpl');
		$smarty->assign('content',$content);
		$_REQUEST["cp"] = "add_partner_site";
		$req['Menu']["partner_site"] = "style='color:#FF0000;font-weight:bold;'";
		break;
		
	case 'question':
		$req['header']	=	$objAdminRace->lang['header']['question'];
		$xajax -> registerFunction('getQuestionList');
		$xajax -> registerFunction('getQuestionListSearch');
		$xajax -> registerFunction('deleteQuestionList');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['site_list'] = $objAdminRace->getSelectPartnerSite();
		$req['list'] 	=	$objAdminRace -> getQuestionList();
		$req['order']	= 	$_REQUEST['order'];
		$req['field'] 	= 	$_REQUEST['field'];
		$req['back'] 	= 	$_REQUEST['back'];
		$req['sid'] 	= 	$_REQUEST['sid'];
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_question.tpl');
		$smarty -> assign('content', $content);
		$req['Menu']["question"] = "style='color:#FF0000;font-weight:bold;'";
		break;
		
	case 'add_question':
		$req['header']	=	$objAdminRace->lang['header']['add_question'];
		$req['sid']		=	$_REQUEST['sid'];
		$req['back']	=	$_REQUEST['back'];
		if(isset($_REQUEST['qid'])&&$_REQUEST['qid']!=""){
			$req['info'] = $objAdminRace->getQuestionInfo($_REQUEST['qid']);
		}
		$req['info']['site_id'] = $req['info']['site_id'] ? $req['info']['site_id'] : $_REQUEST['sid'];
		$req['site_list'] = $objAdminRace->getSelectPartnerSite();
		if(isset($_POST)&&!empty($_POST)){
			$arrSetting = array(
				'site_id' 		=> 	$_REQUEST['site_id'],
				'question' 		=> 	$_REQUEST['question'],
				'type' 			=> 	$_REQUEST['type'],
				'deleted' 		=> 	$_REQUEST['deleted'],
			);
			if($_REQUEST['qid']!=""){
				$qid = $_REQUEST['qid'];
				$strCondition ="where id='$qid'";
				if ($dbcon-> update_record($table."point_question", $arrSetting, $strCondition)) {
					$msg = "Edit question successfully.";
					$req['msg'] = $msg;
					$req['isok'] = 'yes';
					$req['info'] = $_REQUEST;
				}else{
					$msg = "Faild to edit question.";
				}
			}else{
				if ($dbcon-> insert_record($table."point_question", $arrSetting)) {
					$msg = "Create question successfully.";
					$req['msg'] = $msg;
					$req['isok'] = 'yes';
					$req['info'] = $_REQUEST;
				}else{
					$msg = "Faild to create question.";
					$req['info'] = $arrSetting;
				}
			}
		}
		$smarty->assign('req',$req);
		$content = $smarty->fetch('admin_question_form.tpl');
		$smarty->assign('content',$content);
		$_REQUEST["cp"] = "add_question";
		$req['Menu']["question"] = "style='color:#FF0000;font-weight:bold;'";
		break;
		
	case 'answer':
		$req['header']	=	$objAdminRace->lang['header']['answer'];
		$xajax -> registerFunction('getAnswerList');
		$xajax -> registerFunction('getAnswerListSearch');
		$xajax -> registerFunction('deleteAnswerList');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['site_list'] = $objAdminRace->getSelectPartnerSite();
		if ($_REQUEST['sid']) {
			$req['question_list'] = $objAdminRace->getQuestionsBySid($_REQUEST['sid']);
		}
		
		$req['list'] 	=	$objAdminRace -> getAnswerList();
		$req['order'] 	= 	$_REQUEST['order'];
		$req['field'] 	= 	$_REQUEST['field'];
		$req['sid'] 	= 	$_REQUEST['sid'];
		$req['qid'] 	= 	$_REQUEST['qid'];
		$req['back'] 	= 	$_REQUEST['back'];
		$req['f'] 		= 	$_REQUEST['f'];
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_answer.tpl');
		$smarty -> assign('content', $content);
		$req['Menu']["answer"] = "style='color:#FF0000;font-weight:bold;'";
		break;
		
	case 'add_answer':
		$req['header']	=	$objAdminRace->lang['header']['add_answer'];
		$req['sid']		=	$_REQUEST['sid'];
		$req['qid']		=	$_REQUEST['qid'];
		$req['back']	=	$_REQUEST['back'];
		if(isset($_REQUEST['aid'])&&$_REQUEST['aid']!=""){
			$req['site_list'] = $objAdminRace->getSelectPartnerSite();
			$req['info'] = $objAdminRace->getAnswerInfo($_REQUEST['aid']);
			$req['question_list'] = $objAdminRace->getQuestionsBySid($req['info']['site_id']);
		} else {
			$req['site_list'] = $objAdminRace->getSelectPartnerSite();
			$req['question_list'] = $objAdminRace->getQuestionsBySid($_REQUEST['sid']);
		}
		$req['info']['site_id'] = $req['info']['site_id'] ? $req['info']['site_id'] : $_REQUEST['sid'];
		$req['info']['question_id'] = $req['info']['question_id'] ? $req['info']['question_id'] : $_REQUEST['qid'];
		if(isset($_POST)&&!empty($_POST)){
			$arrSetting = array(
				'question_id' 		=> 	$_REQUEST['question_id'],
				'pre_index' 		=> 	$_REQUEST['pre_index'],
				'answer' 			=> 	$_REQUEST['answer'],
				'order' 			=> 	$_REQUEST['order'],
				'status' 			=> 	$_REQUEST['status'],
				'deleted' 			=> 	$_REQUEST['deleted'],
			);
			if($_REQUEST['aid']!=""){
				$aid = $_REQUEST['aid'];
				$strCondition ="where id='$aid'";
				if ($dbcon-> update_record($table."point_answer", $arrSetting, $strCondition)) {
					$msg = "Edit answer successfully.";
					$req['msg'] = $msg;
					$req['isok'] = 'yes';
					$req['info'] = $_REQUEST;
				}else{
					$msg = "Faild to edit answer.";
				}
			}else{
				if ($dbcon-> insert_record($table."point_answer", $arrSetting)) {
					$msg = "Create answer successfully.";
					$req['msg'] = $msg;
					$req['isok'] = 'yes';
					$req['info'] = $_REQUEST;
				}else{
					$msg = "Faild to create answer.";
					$req['info'] = $arrSetting;
				}
			}
		}
		$smarty->assign('req',$req);
		$content = $smarty->fetch('admin_answer_form.tpl');
		$smarty->assign('content',$content);
		$_REQUEST["cp"] = "add_answer";
		$req['Menu']["answer"] = "style='color:#FF0000;font-weight:bold;'";
		break;
		
	case 'getsitelist':
		$site = $_REQUEST['site'];
		$questions = $objAdminRace->getQuestionsBySid($site);
		echo "<option value='' selected='selected'>Select Question</option>";
		if ($questions) {
			foreach ($questions as $pass){
				echo "<option value='{$pass['id']}'>{$pass['question']}</option>"; 
			}	
		}
		
		exit();
		break;
		
	case 'checkparnersiteform':
		$domain = $_REQUEST['domain'];
		$sid = $_REQUEST['sid']?$_REQUEST['sid']:"";
		if(!get_magic_quotes_gpc()){
			$domain = addslashes($domain);
		}
		echo $objAdminRace->checkPartnerSitefrom($domain,$sid);
		exit();
		break;
}
$req['Menu'][$_REQUEST["cp"]] = "style='color:#FF0000;font-weight:bold;'";
$smarty -> assign('req',	$req);
unset($objAdminPromotion,$req,$xajax);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
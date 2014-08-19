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
include_once ('xajax/xajax_core/xajax.inc.php');

//check login
$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}
$smarty -> loadLangFile('soc');


$objAdminRefer 	= new adminRefer();
$socObj 		= new socClass();
$startDate  = 0;
$enddate    = 0;
if(isset($_REQUEST['start_date'])&&$_REQUEST['start_date']!=""){
	if(DATAFORMAT_DB=="%m/%d/%Y"){
		list($month,$day,$year) = split('/',$_REQUEST['start_date']);
		if($_REQUEST['end_date']!=""){
			list($e_month,$e_day,$e_year) = split('/',$_REQUEST['end_date']);
		}
	}else{
		list($day,$month,$year) = split('/',$_REQUEST['start_date']);
		if($_REQUEST['end_date']!=""){
			list($e_month,$e_day,$e_year) = split('/',$_REQUEST['end_date']);
		}
	}
	$startDate = mktime($_REQUEST['s_hour'],($_REQUEST['s_min']!="")?intval($_REQUEST['s_min']):0,0,$month,$day,$year);
	if($_REQUEST['end_date']!=""){
		$enddate = mktime($_REQUEST['e_hour'],($_REQUEST['e_min']!="")?intval($_REQUEST['e_min']):0,59,$e_month,$e_day,$e_year);
	}else{
		$enddate = time();
	}
	
}
$list = $objAdminRefer -> getpaypallist($startDate,$enddate,$_REQUEST['nickname'],$_REQUEST['usertype']);
$str = "";
if(is_array($list)){
	foreach ($list as $pass){
		@$socObj ->sendcheque($pass['StoreID'],$pass['second']);
		$str .=$pass['first']."\t".$pass['second']."\t".$pass['third']."\t".$pass['forth']."\t".$pass['fifth']."\r\n";
	}
}
if($str!=""){
	$output = time().'_'.randStr(6).".txt";
	if(!file_exists(ROOT_PATH.'/upload/download/')){
		mkdir(ROOT_PATH.'/upload/download/');
	}
	$FH = fopen(ROOT_PATH.'/upload/download/'.$output, 'w') or die("can't open file");
	fwrite($FH,$str);
	fclose($FH);	
	$arySeting = array('filename'=>$output,'export_num'=>count($list),'start_date'=>$startDate,'end_date'=>$enddate,'create_time'=>time());
	$dbcon->insert_query($table."ref_download",$arySeting);
}
header( "Content-type: text/plain");
header( "Content-Disposition: attachment; filename=\"paypalexport.txt\"" );
header( "Expires: 0" ); // set expiration time
header( "Cache-Component: must-revalidate, post-check=0, pre-check=0" );
header( "Pragma: public" );
echo $str;
?>
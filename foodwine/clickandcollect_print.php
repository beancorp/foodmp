<?php
@session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified:   " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");     //   HTTP/1.1
header ("Pragma: no-cache");
include_once ('../include/config.php');
include_once ('../include/smartyconfig.php');
include_once ('maininc.php');
include_once ('../include/class.uploadImages.php');
include_once ('../include/class.soc.php');
include_once ('../include/class/pagerclass.php');
include_once ('../include/functions.php');

$StoreID = $_SESSION['StoreID'];
if (isset($_GET['store'])) {
	$StoreID = $_GET['store'];
}

$socObj = new socClass();
$req = $socObj -> displayStoreWebside(false,true,$StoreID);	
$req['print'] = isset($_GET['print']) ? $_GET['print'] : 1;



if (isset($_GET['subAttrib'])) {
	$req['info']['subAttrib'] = $_GET['subAttrib'];
}

if ($req['print'] == 0) {
	$position_logo = '';
	$position_url = '';
	$logo_width = '170px';
	switch ($req['info']['subAttrib']) {
		case 1:
			$position_logo = 'right: 20px; top: 20px;';
			$position_url = 'top: 230px; left: 70px;';
			break;
		case 2:
			$position_logo = 'left: 20px; bottom: 140px;';
			$position_url = 'top: 200px; left: 120px;';
			break;
		case 3:
			$position_logo = 'right: 20px; top: 20px;';
			$position_url = 'top: 230px; left: 120px;';
			break;
		case 4:
			$position_logo = 'right: 20px; bottom: 140px;';
			$position_url = 'top: 305px; left: 55px;';
			break;
		case 5:
			$position_logo = 'right: 20px; bottom: 140px;';
			$position_url = 'top: 305px; left: 55px;';
			break;
		case 6:
			$position_logo = 'right: 20px; top: 20px;';
			//$position_url = 'top: 160px; left: 305px;';
			$position_url = 'left: 85px; top: 265px; width: 550px; text-align: center;';
			break;
		case 7:
			$position_logo = 'right: 10px; bottom: 150px;';
			//$position_url = 'top: 120px; left: 305px;';
			$position_url = 'top: 225px; left: 85px; width: 550px; text-align: center;';
			$logo_width = '125px';
			break;
		case 8:
			$position_logo = 'left: 275px; bottom: 150px;';
			$position_url = 'top: 185px; left: 215px;';
			break;
		case 9:
			$position_logo = 'right: 20px; top: 20px;';
			$position_url = 'top: 255px; left: 170px;';
			break;
		case 10:
			$position_logo = 'left: 20px; bottom: 150px;';
			$position_url = 'top: 210px; left: 55px;';
			break;
	}
	
	$smarty->assign('logo_width', $logo_width);
	$smarty->assign('position_logo', $position_logo);
	$smarty->assign('position_url', $position_url);
}

$req['store_url'] = str_replace('http://', '', SOC_HTTP_HOST).$req['info']['bu_urlstring'];
$smarty -> assign('req',$req);

if ($req['print']) {
	$smarty -> display('clickandcollect_print.tpl');
} else {
	$smarty -> display('clickandcollect_pdf.tpl');
}

?>
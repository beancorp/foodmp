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


$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}

$smarty -> loadLangFile('soc');

$fetch_referrals = "SELECT ref.StoreID, detail.bu_name 
					FROM aus_soc_referrer ref 
					INNER JOIN aus_soc_bu_detail detail ON detail.StoreID = ref.StoreID 
					GROUP BY ref.StoreID";
$dbcon->execute_query($fetch_referrals);
$referrals_result = $dbcon->fetch_records();
$smarty->assign('referrals', $referrals_result);

$content = $smarty->fetch('admin_referrals.tpl');
$smarty->assign('content', $content);

$req['Menu'][$_REQUEST["cp"]] = "style='color:#FF0000;font-weight:bold;'";
$smarty -> assign('req',	$req);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
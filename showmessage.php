<?php
include_once ('include/config.php');
@session_start();
include_once ('include/smartyconfig.php');
include_once ('include/maininc.php');
include_once ('include/class.soc.php');
$smarty -> loadLangFile('threeSeller');

$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - SOC Exchange');
$smarty -> assign('keywords',$keywordsList);
$smarty -> assign('description', 'The SOC Exchange involves selling online all your products in an online trading post. No matter how much you sell, we use flat rate selling.');

if (!empty($_SESSION)) {
$smarty -> assign('session', $_SESSION);
}

$req['msg'] = stripcslashes(urldecode($_REQUEST['msg']));
$smarty -> assign('req', $req);
$smarty->display('showmessage.tpl');

exit;

?>

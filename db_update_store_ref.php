<?php 
include_once ('include/config.php');
@session_start();
include_once ('include/smartyconfig.php');
include_once ('include/maininc.php');
include_once ('include/class.soc.php');
include_once "include/class.socstore.php" ;

/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/

$psw = $_GET['psw'];
if ($psw != '1314') {
	echo 'Incorrect Passwod.';
	exit();
}

$sql = "SELECT * FROM {$table}bu_detail WHERE is_popularize_store='0' AND CustomerType='seller' AND ref_name!=''";
$dbcon->execute_query($sql);
$res = $dbcon->fetch_records(true);

echo 'START <br />';

$stostoreObj = new socstoreClass();
foreach ($res as $key => $val) {
	$is_exists = $dbcon->checkRecordExist($table.'bu_detail', "WHERE referrer='{$val['ref_name']}' AND is_popularize_store='0' AND CustomerType='seller'");
	if ($is_exists) {
		echo $val['StoreID'].'<br />';
		$stostoreObj->updateStoreRef($val['StoreID'], $val['ref_name']);
	}
}

echo 'END';

?>

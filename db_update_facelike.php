<?php
/**
 * Mon Feb 13 15:33:19 GMT 2012
 * @author  : Kevin.liu<kevin.liu@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * FaceLike Action
 * ------------------------------------------------------------
 * facelikeajax.php
 */
include_once ('include/session.php');
include_once ('include/config.php');
include_once ('include/maininc.php');
include_once ('include/functions.php');
include_once ('/include/class.socstore.php');

$psw = $_GET['psw'];
if ($psw != '1314') {
	echo 'Incorrect Passwod.';
	exit();
}


$sql = "SELECT * FROM {$table}facelike_records WHERE pid='0'";
$dbcon -> execute_query($sql);
$res = $dbcon -> fetch_records(true);
foreach ($res as $val) {
	$tmp_url = str_replace('http://', '', $val['url']);
	$url_ary = explode('/', $tmp_url);
	$count = count($url_ary);	
	$like_type = 'store';
	if ($count == 3 && !strpos($tmp_url, '?')) {
		$like_type = 'item';
	}
	if (strpos($tmp_url, '?')) {
		$like_type = 'other';
	}
	if ($like_type == 'item') {
		$product_table_ary = array(
        	'0'		=> 		$table.'product',
        	'1'		=> 		$table.'product_realestate',
        	'2'		=> 		$table.'product_automotive',
        	'3'		=> 		$table.'product_job',
        	'5'		=> 		$table.'product_foodwine',
        );
        $url_item_name = $url_ary[2];
        $sql = "SELECT * FROM {$table}bu_detail WHERE StoreID='{$val['StoreID']}'";
		$store_info = $dbcon->getOne($sql);
		$product_table = $product_table_ary[$store_info['attribute']];
        $sql = "SELECT * FROM $product_table WHERE url_item_name='{$url_item_name}'";
		$res = $dbcon->getOne($sql);
		$pid = $res['pid'];
	}
	
	$pid = $like_type == 'item' ? $pid : 0;
	
	$arrSetting = array(
		'type'		=> $like_type,
		'pid'		=> $pid,
	);
	
	$dbcon->update_record($table.'facelike_records', $arrSetting, "WHERE id='{$val['id']}'");	
}
?>
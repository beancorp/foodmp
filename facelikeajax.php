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
if(isset($_REQUEST['opt'])&&$_REQUEST['opt']=='facelike'){
	include_once ('include/session.php');
	include_once ('include/config.php');
	include_once ('include/maininc.php');
	include_once ('include/functions.php');
	
	extract($_REQUEST);
	
	$arrSetting = array(
		'StoreID' 	=> 	$StoreID,
		'uid' 		=> 	$_SESSION['ShopID'] ? $_SESSION['ShopID'] : '0',
		'url'		=> 	$url,
		'num'		=> $type == 'like' ? 1 : -1,
		'type'		=> $like_type,
		'pid'		=> $pid,
		'timestamp' => time()
	);
	
	$dbcon->insert_record($table.'facelike_records', $arrSetting);
	
	echo $_REQUEST['opt'].','.$_REQUEST['type'].','.$_REQUEST['StoreID'].','.$_REQUEST['url'].','.$_SESSION['ShopID'];
	exit();
}
?>
<?php
@session_start();
ini_set("max_execution_time", 3600);
include_once ('include/config.php');
include_once ('include/smartyconfig.php');
include_once ('maininc.php');
include_once "include/class.processcsv.php";
if(isset($_FILES['Filedata'])&&$_FILES['Filedata']['size']>0){
	$procsv = new processcsv();
	$StoreID = $_REQUEST['StoreID'];
	$type = $_REQUEST['type'];
	$procsv->deleteCSVEmailbyStoreType($StoreID,$type);
	$arymsg = $procsv->uploadSaveCsv($StoreID,$_FILES['Filedata'],$type);
	echo $arymsg['num']."|".$arymsg['msg'];
}
?>
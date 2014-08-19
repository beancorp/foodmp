<?php
@session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');
include_once ('functions.php');


$sql = "select * from {$table}bu_detail where ref_name is null or ref_name=''";
$dbcon->execute_query($sql);
$result  = $dbcon->fetch_records(true);
if($result){
	foreach ($result as $pass){
		$str = getrefname();
		echo $pass['StoreID'].":".$pass['bu_nickname'].":$str <br/>";
		$sql = "update {$table}bu_detail set ref_name='{$str}' where StoreID = {$pass['StoreID']}";
		$dbcon->execute_query($sql);
	}
}
?>
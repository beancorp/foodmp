<?php
include_once("../include/config.php");
include_once("../include/maininc.php");
include_once("../include/functions.php");
include_once("../include/class.soc.php");
include_once("../include/class.socbid.php");

$socbidObj = new socbidClass();

// need set the table name and field name
$date = date("Y-m-d",time());
$stamp = time();
$sql = "select StoreID,pid from ".$GLOBALS['table']."product as product ";
$sql.= "left join ".$GLOBALS['table']."product_auction as auction ";
$sql.= "where auction.end_stamp<'$stamp' and product.status='1'";
$dbcon->execute_query($sql);
$rows = $dbcon->fetch_records();

$count = 0;
foreach($rows as $val){
	//$reviewKey = $socbidObj->getReviewKey($val['pid']);
	$socbidObj->socSendMail($val['StoreID'],$val['pid']);
	$sql = "update ".$GLOBALS['table']."product_auction set status='2' where end_stamp < '$stamp' and pid=".$val['pid'];
	$dbcon->execute_query($sql);
	$count+=1;
}


echo "$date: $count record(s) is updated.";
?>
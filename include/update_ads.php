<?php
include_once "../include/config.php" ;
include_once "../include/maininc.php" ;
include_once "../include/functions.php" ;

// need set the table name and field name
$date = date("Y-m-d",time());
//$sql = "insert into usa_buyblitz_ads_log select * from usa_buyblitz_ads_soc where date!='$date'";
$sql = "insert into ".$GLOBALS['table']."ads_log select * from ".$GLOBALS['table']."ads";
$dbcon->execute_query($sql);

//$sql = "delete from usa_buyblitz_ads_soc where date!='$date'";
$sql = "delete from ".$GLOBALS['table']."ads";
$dbcon->execute_query($sql);
$count = mysql_affected_rows();
echo "$date: remove the $count old ads records.";
?>
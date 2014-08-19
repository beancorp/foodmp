<?php
ini_set('display_errors', 0);
@session_start();

include_once ('../include/config.php');
include_once ('../include/smartyconfig.php');
include_once ('functions.inc.php');
include_once ('maininc.php');
include_once ('class.soc.php');

include_once('../languages/'.LANGCODE.'/soc.php');
include_once('../languages/'.LANGCODE.'/foodwine/index.php');


$sql = "SELECT promo_store_codes.*, 
				 consumer.bu_name As consumer, consumer.bu_email AS consumer_email
				
				FROM promo_store_codes
				LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = promo_store_codes.store_id";

$dbcon->execute_query($sql);
			$res = $dbcon->fetch_records(true);

foreach ($res as $row){
	echo "Code : {$row["code"]} ---------  Store:  {$row["consumer"]}  ----- Store email:  {$row["consumer_email"]}  -----  <br>";
}


?>
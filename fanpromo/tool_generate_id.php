<?php
//generate unique_id for photo

ini_set('display_errors', 1);
@session_start();

include_once ('../include/config.php');
include_once ('../include/smartyconfig.php');
include_once ('functions.inc.php');
include_once ('maininc.php');
include_once ('class.soc.php');

include_once('../languages/'.LANGCODE.'/soc.php');
include_once('../languages/'.LANGCODE.'/foodwine/index.php');


$sql = "SELECT * FROM photo_promo";

$dbcon->execute_query($sql);
			$res = $dbcon->fetch_records(true);

foreach ($res as $row){
	$sql = "";
	if (!$row["unique_id"]){
		$sql = "UPDATE photo_promo SET unique_id = '" . uniqid() . "' WHERE photo_id = {$row["photo_id"]}";
		$dbcon->execute_query($sql);
	}
}
echo "done";


?>
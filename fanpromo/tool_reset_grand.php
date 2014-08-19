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


echo "Reset  Grand Final = 0 <br> "; 	
	
$sql = "update photo_promo set grand_final = 0";
$dbcon->execute_query($sql);
echo "reset done";


echo "Reset All Vote = 0 <br> ";

$sql = "TRUNCATE TABLE photo_promo_fan";
$dbcon->execute_query($sql);


echo "Stop Sending EMail  <br> ";
$sql = "TRUNCATE TABLE queue_mail";
$dbcon->execute_query($sql);

echo "Remove List Grand Nominee <br>";
$sql = "TRUNCATE TABLE promo_grand_list";
$dbcon->execute_query($sql);

echo "Reset trigger <br>";
$sql = "UPDATE photo_promo_text SET text_content = 0 WHERE key_name ='grand-trigger'";
$dbcon->execute_query($sql);

?>
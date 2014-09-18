<?php
/**
 * This Cron will run once per month on the last day of each month. 
 * It will : 
 * 	Select Top 300 to move to Grand
 * 	Select Top  3 to move to send email 
 * 	Select Top 97 to inform.   
 * 
 */
ini_set('display_errors', 0);
error_reporting(E_ALL);


// change this when go live
//$_SERVER['HTTP_HOST'] = 'www.thesocexchange.com';
/*
$cronPath=str_replace('\\','/',dirname(__FILE__)).'/';

require_once $cronPath.'../include/maininc.php';
require_once $cronPath.'../include/class/common.php';
require_once $cronPath.'../include/class.soc.php';
require_once $cronPath.'../include/class.socbid.php';
require_once $cronPath.'../include/functions.php';
*/

/*
 *  USA Live

require_once '/var/www/thesocexchange.com/include/maininc.php';
require_once '/var/www/thesocexchange.com/include/class/common.php';
require_once '/var/www/thesocexchange.com/include/class.soc.php';
require_once '/var/www/thesocexchange.com/include/class.socbid.php';
require_once '/var/www/thesocexchange.com/include/functions.php';
 *
 * 
 */
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
/**
 * new soc test site
 */
//$new_test_site_usa_path='/infinity/sites/soc/soc_au';     //new test site path
//$new_test_site_usa_path='/home/soc/promote.socexchange.com.au';     //new live site path
$new_test_site_usa_path=dirname(dirname(__FILE__));     //new live site path
include_once($new_test_site_usa_path.'/include/smartyconfig.php');
include_once($new_test_site_usa_path.'/include/maininc.php');
include_once($new_test_site_usa_path.'/include/class/common.php');
include_once($new_test_site_usa_path.'/include/class.soc.php');
include_once($new_test_site_usa_path.'/include/class.socbid.php');
include_once($new_test_site_usa_path.'/include/class.emailClass.php');
include_once($new_test_site_usa_path.'/include/functions.php');
include_once ($new_test_site_usa_path.'/fanpromo/functions.inc.php');

/*
 * END SOC NEW TEST SITE
 */

global $table;

if(DATAFORMAT_DB=="%m/%d/%Y"){
	date_default_timezone_set('America/New_York');
}else{
	date_default_timezone_set('Australia/Sydney');
}

$current_time = time();
$advance_time = $current_time + 7 * 24 * 3600;

//Auto

/*****  Begin Auto  *****/
$grand_trigger = tab_content_by_key_name($dbcon, "grand-trigger");
if ($grand_trigger === 1) 
	exit();

//get Grand Final Date
$grand_final_date = tab_content_by_key_name($dbcon, "grand-final-date");
$grand_final_time = strtotime($grand_date_open);
$today = mktime(0,0,0);

if ($today == $grand_final_time){
	//get Retailer
	$sql = "SELECT COUNT(detail.StoreID) As retailer_count FROM aus_soc_bu_detail AS detail 
			INNER JOIN aus_soc_login as lg on lg.StoreID = detail.StoreID 
			WHERE detail.CustomerType = 'seller' AND detail.attribute = '5' 
			AND NOT(detail.bu_name IS NULL) 
			AND detail.renewalDate >= UNIX_TIMESTAMP(NOW())
			AND detail.status = 1;";
	$res = $dbcon->getOne($sql);
	$retailer_count = $res['retailer_count'];
	$retailer_goal = tab_content_by_key_name($dbcon, "retailer-number");
	//$retailer_goal = 3000;
	
	if ($retailer_count >= $retailer_goal){
		//update trigger
		$text_sql = "UPDATE photo_promo_text SET text_content = 1 WHERE key_name = 'grand-trigger' LIMIT 1";
		$dbcon->execute_query($text_sql);
		$sql = "TRUNCATE TABLE photo_promo_fan";
		$dbcon->execute_query($sql);
	}
}















$sql = "SELECT COUNT(fans.fan_id) As fan_count, (SELECT MAX(fan_id) FROM photo_promo_fan WHERE photo_id = photo.photo_id) AS last_fan_id ,
				photo.*, consumer.bu_name As consumer, consumer.bu_email AS consumer_email, 
				retailer.bu_urlstring As retailer_url, retailer.bu_name As retailer
				
					FROM photo_promo photo
					LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id 
					LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
					LEFT JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
					WHERE 1
					AND photo.approved = 1
					AND photo.grand_final <>1
					AND photo.store_id <> 0
					
					GROUP BY photo.photo_id ORDER BY fan_count DESC, last_fan_id ASC,  photo.timestamp ASC LIMIT 0, 10";
			$dbcon->execute_query($sql);
			$res = $dbcon->fetch_records(true);
$i = 1;


foreach ($res as $row){
		//insert in GRAND Final Table
		$insert_array = array("photo_id" => $row["photo_id"],
							"time_winner" => time(), 
	  						"is_sent_email" => 0,
							"count_vote" => $row["fan_count"],
							"sort_number" => $i,
							);	
		insert_grand_table($dbcon, $insert_array);
		//update grand final
		$arr_update["grand_final"] = 1; 
		update_photo($dbcon, $arr_update, $row["photo_id"]);
		
		
		if ($i<=3){
			$top3_list[]= array("name" => $row["consumer"], "email" => $row["consumer_email"]);	
		}else{
			$top97_list[]= array("name" => $row["consumer"], "email" => $row["consumer_email"]);
		}	
		
		
		$i++;
}
$mail_param = array();

$email_admin = tab_content($dbcon, 19); 

$mail_param["mail_to"] = $email_admin;
$mail_param["subject"] = "List of winner";

$message = 'Hi Admin, <br> Here are list of winners <br> Top 3: <br>';


foreach ($top3_list as $top){
	$message .= "<br>No{$top["number"]}  username: {$top["name"]} ------ email: {$top["email"]} <br>";
}
$message .= "<br><br><br> Here are list of top 97: <br>"; 

foreach ($top97_list as $top){
	$message .= "<br> username: {$top["name"]} ------ email: {$top["email"]} <br>";
}
$message .='<br /><br /> Kind regards, <br /> FoodMarketplace.';

$mail_param["content"] = $message;



echo "<pre>";
var_dump($mail_param);

echo "</pre>";
insert_queue_mail($dbcon, $mail_param);







<?php
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
ini_set('display_errors', 1);
error_reporting(E_ALL);




/*****  Begin Auto  *****/

/*
$mail_param["mail_to"] = "sirothoi2005@gmail.com";
$mail_param["subject"] = "Confirm competion";
$mail_param["content"] = 'Hi '.ucwords($_SESSION["NickName"]).' <br /><br /> This email is\' sent to confirm that you enter our competition Fanfrenzy. <br />
													<br /><br /> Kind regards, <br /> FoodMarketplace.';
					
insert_queue_mail($dbcon, $mail_param);

die;
					*/




//Send email to the store whose product will expired after 3 days.
$sql =  "SELECT * FROM queue_mail LIMIT 0, 3";
$dbcon->execute_query($sql);
$result = $dbcon->fetch_records();


if ($result && is_array($result)) {
	foreach ($result as $row){
		$delete_sql = "";
		$objEmail	=	new emailClass();
			
		$arrParams['To']				=	$row["mail_to"];
		$arrParams['Subject']			=	$row["subject"];
		//$arrParams["message"] 			= 	wordwrap(htmlspecialchars_decode($row["content"]), 70, "\r\n");

		$message = file_get_contents(getcwd()."/mail_send/content_mail_{$row["id"]}.txt");
		$arrParams["message"] = $message;
		
		
		
		$bool = $objEmail -> send($arrParams,$new_test_site_usa_path.'/skin/red/email_simple.tpl');
//		if ($bool === true){
			$delete_sql = "DELETE FROM queue_mail WHERE id = {$row["id"]}";
			$dbcon->execute_query($delete_sql);
			unlink(getcwd()."/mail_send/content_mail_{$row["id"]}.txt"); //delete messsage file
//		}
		unset($objEmail);
	}	
}
echo "sent mail done";


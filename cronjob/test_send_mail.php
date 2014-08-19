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

$email_template =  get_email_template_by_key_name($dbcon, "email_to_top1");

$username = "hieuluuchi"; 

$message = str_replace("##username##", nl2br($username), $email_template["content"]); //Replace 





$objEmail	=	new emailClass();
			
$arrParams['To']				=	"hieu.luu@opendigital.com.au";
$arrParams['Subject']			=	"test";
$arrParams["message"] 			= 	htmlspecialchars_decode($message); 
$bool = $objEmail -> send($arrParams,$new_test_site_usa_path.'/skin/red/email_simple.tpl');
var_dump($bool);
echo "sent mail done";


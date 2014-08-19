<?php
/**
 * This Cron will run once per month on the last day of each month. 
 * It will : 
 *     Select Top 300 to move to Grand
 *     Select Top  3 to move to send email 
 *     Select Top 97 to inform.   
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
include_once ($new_test_site_usa_path.'/fanpromo/functions.inc.php');

/*
 * END SOC NEW TEST SITE
 */

global $table;

$sql = "Select retailer.StoreID from ".$table."bu_detail as retailer
        JOIN ".$table."login as login on retailer.StoreID=login.StoreID  
        JOIN promo_store_codes as codes on retailer.StoreID!=codes.store_id
        WHERE login.attribute=5 AND login.level=1";
$dbcon->execute_query($sql);
$res = $dbcon->fetch_records(true);

foreach ($res as $row){
    generate_fanpromo_code($dbcon, $row['StoreID']);
}

die('Complete generated'); 





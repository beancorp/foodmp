#!/usr/bin/php
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
$new_test_site_usa_path=dirname(dirname(__FILE__));     //new live site path
include_once($new_test_site_usa_path.'/include/smartyconfig.php');
include_once($new_test_site_usa_path.'/include/maininc.php');
include_once($new_test_site_usa_path.'/include/class/common.php');
include_once($new_test_site_usa_path.'/include/class.soc.php');
include_once($new_test_site_usa_path.'/include/class.socbid.php');
include_once($new_test_site_usa_path.'/include/class.emailClass.php');
include_once($new_test_site_usa_path.'/include/functions.php');
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
$advance_time = $current_time - 7 * 24 * 3600;

$samplesiteid_str = implode(',', $samplesiteid);
$samplesiteid_str = empty($samplesiteid_str) ? 0 : $samplesiteid_str;

/*****  Begin Auto  *****/

$sql = "UPDATE {$table}product_automotive SET deleted=1 WHERE pay_status = '0' AND datec  < '$current_time' AND StoreID NOT IN ($samplesiteid_str)";
$dbcon->execute_query($sql);
/*****  End Auto  *****/

/*****  Begin Estate  *****/

$sql = "UPDATE {$table}product_realestate SET deleted=1 WHERE pay_status = '0' AND datec  < '$current_time' AND StoreID NOT IN ($samplesiteid_str)";
$dbcon->execute_query($sql);

/*****  End Estate  *****/

/*****  Begin Job  *****/

$sql = "UPDATE {$table}product_job product, {$table}bu_detail detail SET product.deleted=1 WHERE product.StoreID=detail.StoreID AND product.pay_status = '0' AND product.datec  < '$current_time' AND product.StoreID NOT IN ($samplesiteid_str) AND detail.subAttrib!=3";
$dbcon->execute_query($sql);

/*****  End Job  *****/

?>

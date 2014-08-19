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
//$new_test_site_usa_path='/home/soc/promote.socexchange.com.au';     //new live site path
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

//Send email to the store which will expired after 3 days. 

if(DATAFORMAT_DB=="%m/%d/%Y"){
	date_default_timezone_set('America/New_York');
}else{
	date_default_timezone_set('Australia/Sydney');
}

$current_time = time();
$date_7 = date('Y-m-d', $current_time + 7 * 24 * 3600);
$date_14 = date('Y-m-d', $current_time + 14 * 24 * 3600);

$startstamp_7 = strtotime($date_7. ' 00:00:00');
$endstamp_7 = strtotime($date_7. ' 23:59:59');
$startstamp_14 = strtotime($date_14. ' 00:00:00');
$endstamp_14 = strtotime($date_14. ' 23:59:59');

$sql =  "SELECT StoreID, attribute, bu_email, bu_name AS seller_name, product_renewal_date AS expiring_date \n".
		"FROM ".$table."bu_detail \n".
		"WHERE attribute IN (1,2,3) \n".
		"AND is_popularize_store='0' \n".
		"AND CustomerType = 'seller' \n".
		"AND (product_feetype='month' OR product_feetype='year') \n".
		"AND ((product_renewal_date >= '$startstamp_7' AND product_renewal_date <= '$endstamp_7') \n".
		"OR (product_renewal_date >= '$startstamp_14' AND product_renewal_date <= '$endstamp_14')) \n".
		"UNION \n".
		"SELECT StoreID, attribute, bu_email, bu_name AS seller_name, renewalDate AS expiring_date \n".
		"FROM ".$table."bu_detail \n".
		"WHERE attribute='5' \n".
		"AND is_popularize_store='0' \n".
		"AND CustomerType = 'seller' \n".
		"AND ((renewalDate >= '$startstamp_7' AND renewalDate <= '$endstamp_7') \n".
		"OR (renewalDate >= '$startstamp_14' AND renewalDate <= '$endstamp_14')) \n";
		
$dbcon->execute_query($sql);
$result = $dbcon->fetch_records();

if ($result && is_array($result)) {
	foreach ($result as $arrParams){
		$objEmail	=	new emailClass();
		$arrParams['display']			=	'expired_store';
		$arrParams['To']				=	$arrParams['bu_email'];
		$arrParams['Subject']			=	($arrParams['expiring_date'] >= $startstamp_7 && $arrParams['expiring_date'] <= $endstamp_7) ? 'Notification for website that will expire in 7 days' : 'Notification for website that will expire in 14 days';
		if ($arrParams['attribute'] == 5) {
			$arrParams['reurl'] 			= 	urlencode(SOC_HTTPS_HOST.'soc.php?cp=sellerhome');
		} else {
			$arrParams['reurl'] 			= 	urlencode(SOC_HTTPS_HOST.'soc.php?act=signon&step=4');
		}
		$arrParams['expiring_date']		=	date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$arrParams['expiring_date']);
		
		$objEmail -> send($arrParams,$new_test_site_usa_path.'/skin/red/email_product_fee.tpl');
		unset($objEmail);
	}	
}


//Send email to the store expired today. 

$current_date = date('Y-m-d', time());
$startstamp = strtotime($current_date. ' 00:00:00');
$endstamp = strtotime($current_date. ' 23:59:59');

$sql =  "SELECT StoreID, attribute, bu_email, bu_name AS seller_name, product_renewal_date AS expiring_date \n".
		"FROM ".$table."bu_detail \n".
		"WHERE attribute IN (1,2,3) \n".
		"AND is_popularize_store='0' \n".
		"AND CustomerType = 'seller' \n".
		"AND (product_feetype='month' OR product_feetype='year') \n".
		"AND product_renewal_date >= '$startstamp' \n".
		"AND product_renewal_date <= '$endstamp' \n".
		"UNION \n".
		"SELECT StoreID, attribute, bu_email, bu_name AS seller_name, renewalDate AS expiring_date \n".
		"FROM ".$table."bu_detail \n".
		"WHERE attribute='5' \n".
		"AND is_popularize_store='0' \n".
		"AND CustomerType = 'seller' \n".
		"AND renewalDate >= '$startstamp' \n".
		"AND renewalDate <= '$endstamp' \n";
	
$dbcon->execute_query($sql);
$result = $dbcon->fetch_records();

if ($result && is_array($result)) {
	foreach ($result as $arrParams){
		$objEmail	=	new emailClass();
		$arrParams['display']			=	'expired_store_today';
		$arrParams['To']				=	$arrParams['bu_email'];
		$arrParams['Subject']			=	'Notification for website that has expired today';
		if ($arrParams['attribute'] == 5) {
			$arrParams['reurl'] 			= 	urlencode(SOC_HTTPS_HOST.'soc.php?cp=sellerhome');
		} else {
			$arrParams['reurl'] 			= 	urlencode(SOC_HTTPS_HOST.'soc.php?act=signon&step=4');
		}
		$arrParams['expiring_date']		=	date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$arrParams['expiring_date']);
		
		$objEmail -> send($arrParams,$new_test_site_usa_path.'/skin/red/email_product_fee.tpl');
		unset($objEmail);
	}	
}

?>

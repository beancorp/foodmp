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

if(DATAFORMAT_DB=="%m/%d/%Y"){
	date_default_timezone_set('America/New_York');
}else{
	date_default_timezone_set('Australia/Sydney');
}

$current_time = time();
$advance_time = $current_time + 7 * 24 * 3600;

//Auto

/*****  Begin Auto  *****/
//Send email to the store whose product will expired after 3 days.
$sql =  "SELECT product.StoreID AS StoreID, detail.bu_email, detail.bu_name AS seller_name, detail.bu_urlstring \n".
		"FROM {$table}product_automotive product,{$table}bu_detail detail \n".
		"WHERE product.StoreID=detail.StoreID \n".
		"AND detail.attribute='2' \n".
		"AND detail.CustomerType = 'seller' \n".
		"AND product.pay_status = '1' \n".
		"AND product.renewal_date >= '$current_time' \n".
		"AND product.renewal_date <= '$advance_time' \n".
		"GROUP BY product.StoreID";
	
$dbcon->execute_query($sql);
$result = $dbcon->fetch_records();

if ($result && is_array($result)) {
	foreach ($result as $arrParams){
		$sql = "SELECT * FROM {$table}product_automotive WHERE StoreID='$arrParams[StoreID]'AND pay_status = '1' AND renewal_date >= '$current_time' AND renewal_date <= '$advance_time' AND deleted=0";
		$dbcon->execute_query($sql);
		$product = $dbcon->fetch_records();
		
		if ($product && is_array($product)) {
			foreach ($product as $key => $item) {
				$item['expiring_date'] = date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$item['renewal_date']);
				$product[$key] = $item;
			}
			
			$objEmail	=	new emailClass();
			$arrParams['display']			=	'expired_items';
			$arrParams['To']				=	$arrParams['bu_email'];
			$arrParams['Subject']			=	'Notification for listing(s) that will expire in 7 days';
			$arrParams['list']				=	$product;
			
			$objEmail -> send($arrParams,$new_test_site_usa_path.'/skin/red/email_product_fee.tpl');	
		}
		
		unset($objEmail);
	}	
}

//Send email to the store whose product expired today.
 
$current_date = date('Ymd', time());
$sql =  "SELECT product.StoreID AS StoreID, detail.bu_email, detail.bu_name AS seller_name, detail.bu_urlstring \n".
		"FROM {$table}product_automotive product,{$table}bu_detail detail \n".
		"WHERE product.StoreID=detail.StoreID \n".
		"AND detail.attribute='2' \n".
		"AND detail.CustomerType = 'seller' \n".
		"AND FROM_UNIXTIME( product.renewal_date , '%Y%m%d' ) = '$current_date' \n".
		"GROUP BY product.StoreID";
		
$dbcon->execute_query($sql);
$result = $dbcon->fetch_records();

if ($result && is_array($result)) {
	foreach ($result as $arrParams){
		$sql = "SELECT * FROM {$table}product_automotive WHERE StoreID='$arrParams[StoreID]' AND FROM_UNIXTIME( renewal_date , '%Y%m%d' ) = '$current_date' AND deleted=0";
		$dbcon->execute_query($sql);
		$product = $dbcon->fetch_records();
		
		if ($product && is_array($product)) {
			foreach ($product as $key => $item) {
				$item['expiring_date'] = date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$item['renewal_date']);
				$product[$key] = $item;
			}
			
			$objEmail	=	new emailClass();
			$arrParams['display']			=	'expired_items_today';
			$arrParams['To']				=	$arrParams['bu_email'];
			$arrParams['Subject']			=	'Notification that the items have been expired';
			$arrParams['expiring_date']		=	date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),time());
			$arrParams['reurl'] 			= 	urlencode(SOC_HTTPS_HOST.'soc.php?act=signon&step=4');
			$arrParams['list']				=	$product;
			
			$objEmail -> send($arrParams,$new_test_site_usa_path.'/skin/red/email_product_fee.tpl');	
		}
		
		unset($objEmail);
	}	
}

/*****  End Auto  *****/

//Estate

/*****  Begin Estate  *****/
//Send email to the store whose product will expired after 7 days.
$sql =  "SELECT product.StoreID AS StoreID, detail.bu_email, detail.bu_name AS seller_name, detail.bu_urlstring \n".
		"FROM {$table}product_realestate product,{$table}bu_detail detail \n".
		"WHERE product.StoreID=detail.StoreID \n".
		"AND detail.attribute='1' \n".
		"AND detail.CustomerType = 'seller' \n".
		"AND product.renewal_date >= '$current_time' \n".
		"AND product.renewal_date <= '$advance_time' \n".
		"GROUP BY product.StoreID";
		
$dbcon->execute_query($sql);
$result = $dbcon->fetch_records();

if ($result && is_array($result)) {
	foreach ($result as $arrParams){
		$sql = "SELECT * FROM {$table}product_realestate WHERE StoreID='$arrParams[StoreID]' AND renewal_date >= '$current_time' AND renewal_date <= '$advance_time' AND deleted=0";
		$dbcon->execute_query($sql);
		$product = $dbcon->fetch_records();
		if ($product && is_array($product)) {
			foreach ($product as $key => $item) {
				$item['reurl'] = urlencode(SOC_HTTPS_HOST.'soc.php?act=signon&step=4&pid='.$item['pid']);
				$item['expiring_date'] = date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$item['renewal_date']);
				$product[$key] = $item;
			}
			
			$objEmail	=	new emailClass();
			$arrParams['display']			=	'expired_items';
			$arrParams['To']				=	$arrParams['bu_email'];
			$arrParams['Subject']			=	'Notification for listing(s) that will expire in 7 days';
			$arrParams['list']				=	$product;
			
			$objEmail -> send($arrParams,$new_test_site_usa_path.'/skin/red/email_product_fee.tpl');	
		}
		
		unset($objEmail);
	}	
}

//Send email to the store whose product expired today.
 
$current_date = date('Y-m-d', time());
$startstamp = strtotime($current_date. ' 00:00:00');
$endstamp = strtotime($current_date. ' 23:59:59');

$sql =  "SELECT product.StoreID AS StoreID, detail.bu_email, detail.bu_name AS seller_name, detail.bu_urlstring \n".
		"FROM {$table}product_realestate product,{$table}bu_detail detail \n".
		"WHERE product.StoreID=detail.StoreID \n".
		"AND detail.attribute='1' \n".
		"AND detail.CustomerType = 'seller' \n".
		"AND product.renewal_date >= '$startstamp' \n".
		"AND product.renewal_date <= '$endstamp' \n".
		"GROUP BY product.StoreID";
		
$dbcon->execute_query($sql);
$result = $dbcon->fetch_records();

if ($result && is_array($result)) {
	foreach ($result as $arrParams){
		$sql = "SELECT * FROM {$table}product_realestate WHERE StoreID='$arrParams[StoreID]' AND renewal_date >= '$startstamp' AND renewal_date <= '$endstamp' AND deleted=0";
		$dbcon->execute_query($sql);
		$product = $dbcon->fetch_records();
		
		if ($product && is_array($product)) {
			foreach ($product as $key => $item) {
				$item['reurl'] = urlencode(SOC_HTTPS_HOST.'soc.php?act=signon&step=4&pid='.$item['pid']);
				$item['expiring_date'] = date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$item['renewal_date']);
				$product[$key] = $item;
			}
			
			$objEmail	=	new emailClass();
			$arrParams['display']			=	'expired_items_today';
			$arrParams['To']				=	$arrParams['bu_email'];
			$arrParams['Subject']			=	'Notification that the items have been expired';
			$arrParams['expiring_date']		=	date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),time());
			$arrParams['reurl'] 			= 	urlencode(SOC_HTTPS_HOST.'soc.php?act=signon&step=4');
			$arrParams['list']				=	$product;
			
			$objEmail -> send($arrParams,$new_test_site_usa_path.'/skin/red/email_product_fee.tpl');	
		}
		
		unset($objEmail);
	}	
}

/*****  End Estate  *****/

//Job

/*****  Begin Job  *****/
//Send email to the store whose product will expired after 3 days.
$sql =  "SELECT product.StoreID AS StoreID, detail.bu_email, detail.bu_name AS seller_name, detail.bu_urlstring \n".
		"FROM {$table}product_job product,{$table}bu_detail detail \n".
		"WHERE product.StoreID=detail.StoreID \n".
		"AND detail.attribute='3' \n".
		"AND detail.CustomerType = 'seller' \n".
		"AND product.renewal_date >= '$current_time' \n".
		"AND product.renewal_date <= '$advance_time' \n".
		"GROUP BY product.StoreID";
		
$dbcon->execute_query($sql);
$result = $dbcon->fetch_records();

if ($result && is_array($result)) {
	foreach ($result as $arrParams){
		$sql = "SELECT * FROM {$table}product_job WHERE StoreID='$arrParams[StoreID]' AND renewal_date >= '$current_time' AND renewal_date <= '$advance_time' AND deleted=0";
		$dbcon->execute_query($sql);
		$product = $dbcon->fetch_records();
		
		if ($product && is_array($product)) {
			foreach ($product as $key => $item) {
				$item['reurl'] = urlencode(SOC_HTTPS_HOST.'soc.php?act=signon&step=4&pid='.$item['pid']);
				$item['expiring_date'] = date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$item['renewal_date']);
				$product[$key] = $item;
			}
			
			$objEmail	=	new emailClass();
			$arrParams['display']			=	'expired_items';
			$arrParams['To']				=	$arrParams['bu_email'];
			$arrParams['Subject']			=	'Notification for listing(s) that will expire in 7 days';
			$arrParams['list']				=	$product;
			
			$objEmail -> send($arrParams,$new_test_site_usa_path.'/skin/red/email_product_fee.tpl');	
		}
		
		unset($objEmail);
	}	
}

//Send email to the store whose product expired today.
 
$current_date = date('Ymd', time());
$sql =  "SELECT product.StoreID AS StoreID, detail.bu_email, detail.bu_name AS seller_name, detail.bu_urlstring \n".
		"FROM {$table}product_job product,{$table}bu_detail detail \n".
		"WHERE product.StoreID=detail.StoreID \n".
		"AND detail.attribute='3' \n".
		"AND detail.CustomerType = 'seller' \n".
		"AND product.renewal_date >= '$startstamp' \n".
		"AND product.renewal_date <= '$endstamp' \n".
		"GROUP BY product.StoreID";
		
$dbcon->execute_query($sql);
$result = $dbcon->fetch_records();

if ($result && is_array($result)) {
	foreach ($result as $arrParams){
		$sql = "SELECT * FROM {$table}product_job WHERE StoreID='$arrParams[StoreID]' AND renewal_date >= '$startstamp' AND renewal_date <= '$endstamp' AND deleted=0";
		$dbcon->execute_query($sql);
		$product = $dbcon->fetch_records();
		
		if ($product && is_array($product)) {
			foreach ($product as $key => $item) {
				$item['reurl'] = urlencode(SOC_HTTPS_HOST.'soc.php?act=signon&step=4&pid='.$item['pid']);
				$item['expiring_date'] = date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$item['renewal_date']);
				$product[$key] = $item;
			}
			
			$objEmail	=	new emailClass();
			$arrParams['display']			=	'expired_items_today';
			$arrParams['To']				=	$arrParams['bu_email'];
			$arrParams['Subject']			=	'Notification that the items have been expired';
			$arrParams['expiring_date']		=	date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),time());
			$arrParams['reurl'] 			= 	urlencode(SOC_HTTPS_HOST.'soc.php?act=signon&step=4');
			$arrParams['list']				=	$product;
			
			$objEmail -> send($arrParams,$new_test_site_usa_path.'/skin/red/email_product_fee.tpl');	
		}
		
		unset($objEmail);
	}	
}

/*****  End Job  *****/
?>

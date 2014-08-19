<?php

/* add by Haydn.H at 20111009 ========Begin========= */
// domain jump

$_host = $_SERVER['HTTP_HOST'];
$_url = $_SERVER['SCRIPT_URI'];
if($_host=='www.thesocexchange.com.au' || $_host=='www.socexchange.com.au' || $_host=='socexchange.com.au'){
   header("location: " .str_replace('/www.socexchange.com.au','/thesocexchange.com.au', str_replace('/socexchange.com.au','/thesocexchange.com.au', str_replace('/www.thesocexchange.com.au','/thesocexchange.com.au', $_url))));
   exit;
}
/* add by Haydn.H at 20111009 ========End========= */

require_once 'subsiteconfig.php';

$host = "localhost" ;
$user = "soc_dber" ;
$pass = "Fr122@3538ed" ;
$database = "soc_db_aus" ;
$table = "aus_soc_";

define(DATAFORMAT_DB,"%d/%m/%Y");
define(TEMPLATEDIR,"");
define(UPLOADDIR,"/");
define(CURRENCYCODE,"AUD");
define(PAGESIZE, 25);
$countryID = (CURRENCYCODE == 'AUD')?'13':'223';
if(DATAFORMAT_DB=="%m/%d/%Y"){
	date_default_timezone_set('America/New_York');
}else{
	date_default_timezone_set('Australia/Sydney');
}
define(TIME_MAP,date("P"));

// Transaction test switcher
// added by jessee 20081121
define(TRANSACTION_DEBUG, 0);

define(FREE_REGISTER, false);

//paypal payment gateway setting
//is testing payment? 1=test/0=live
define(PAYPAL_DEBUG, '0');
//your paypal business account
if(PAYPAL_DEBUG==1){
define(PAYPAL_EMAIL, 'sienpo_1208048918_biz@gmail.com');
}else{
define(PAYPAL_EMAIL, 'info@thesocexchange.com.au');
}
//your site url, it must be a url that could be vistited through internet, and must end with a '/'
define(PAYPAL_SITEURL, 'http://thesocexchange.com.au/');

//google checkout server type
define(GOOGLE_CHECKOUT_SERVER_TYPE, 'sandbox');

//payment default method  added by ping.hu 20081123
//define(SIGNON_PAYMENT,'ipg');
/**
 * nr => NetRegister
 * ipg => ipg
 * other:paypal
 */
define(SIGNON_PAYMENT,'nr');
/**
 * NR payment
 */
define(NR_PAYMENT_DEBUG_MODE, false);    //debug
define(NR_PAYMENT_USER_ID, '1852');
define(NR_PAYMENT_PWD, 'txL082vcAIe7143O');

//END
//These constants may need to be modified for your system
define(THE_CLIENT_ID, "10004842");
define(THE_CERT_PATH, str_replace('/include/config.php', '', str_replace('\\', '/', __FILE__))."/include/ipg/cert.cert");
define(THE_CERT_PASSWORD, "reep19f2");
//is testing payment? 3007=test/3006=live  added by ping.hu 20081123
define(THE_PORT, "3006");
define(THE_SERVER, "www.gwipg.stgeorge.com.au");


define(FLYERS_HOST, "www.socexchange.com.au");

//$time_zone_offset = -50400;
$time_zone_offset = -14400;

$large_icon_description = "my shop tagline my shop ";

$samplesiteid = array(1=>'854238',2=>'854235',3=>'854246',4=>'854241',5=>'854240',6=>'854247',7=>'854243',8=>'854242',9=>'854248',10=>'853610',11=>'853495',12=>'853490');

//payment check time of soc_tmpcustom , added by ping.hu 20091117
define(DISABLED_TIME, 30*60);

//session domain set
@ini_set("session.cookie_domain","thesocexchange.com.au");
define('RDURL','/soc.php?cp=home');

/*
 * Config Https Host
 */
define(SOC_HTTPS_HOST, 'https://thesocexchange.com.au/');
define(SOC_HTTP_HOST,'http://thesocexchange.com.au/');

/* modify by Haydn.H at 20111009 ========Begin========= */
/*if(str_replace('https://', '', $_url) == $_url){
   define(SOC_HTTP_HOST,'http://thesocexchange.com.au/');
}else{
   define(SOC_HTTP_HOST,'https://thesocexchange.com.au/');
}*/
/* modify by Haydn.H at 20111009 ========End========= */

include_once('sellerconfig.php');
?>

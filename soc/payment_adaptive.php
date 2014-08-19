<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set("display_errors", "1"); 
error_reporting(E_ALL);
@session_start();
include_once ('/include/config.php');
include_once ('include/smartyconfig.php');
include_once ('class/ajax.php');
include_once ('include/maininc.php');
include_once ('class.soc.php');
include_once ('class.socbid.php');
include_once ('class.socstore.php');
include_once ('class.emailClass.php');
include_once ('class.paymentadaptive.php');
include_once ('class.paymentNR.php');
include_once ('class.paymentipg.php');
include_once ('functions.php');

$adaptive = new PaymentAdaptive();
echo 'test';
echo $adaptive->submit();
exit;
?>

<?php

ini_set('display_errors', 1);
include_once "include/session.php";
include_once "include/config.php";
include_once ('include/smartyconfig.php');
include_once "include/maininc.php";
include_once "include/functions.php";
include_once "include/class/common.php";
include_once "include/class.soc.php";
include_once('include/class.order.php');


// read the post from PayPal system and add 'cmd'

$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
    $value = urlencode(stripslashes($value));
    //echo "post-key: $key; val: $value<br>";
    $req .= "&$key=$value";
}

// post back to PayPal system to validate
/* Live site code */
//$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
//$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
//$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

$header.="POST /cgi-bin/webscr HTTP/1.0\r\n";
if ($paypal_info['paypal_mode'] == 0) {
    $header .= "Host: www.sandbox.paypal.com\r\n";
} else {
    $header .= "Host: www.paypal.com\r\n";
}
$header.="Content-Type:application/x-www-form-urlencoded\r\n";
$header.="Content-Length:" . strlen($req) . "\r\n\r\n";

$paypal_info = getPaypalInfo();
if ($paypal_info['paypal_mode'] == 0) {
    $fp = fsockopen('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
} else {
    $fp = fsockopen('ssl://www.paypal.com', 443, $errno, $errstr, 30);
}
/* temporary code for test
  echo "<pre>";
  echo $header;
  echo $req;
 */

// post to test program for pay test
/* Test code for local develop
  $header .= "POST /paypal_test.php HTTP/1.0\r\n";
  $header .= "Host: www.testa.com\r\n";
  $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
  $fp = fsockopen ('', 80, $errno, $errstr, 30);
 */

/* Test code for test develop
  $header .= "POST /~buyblitz/paypal_test.php HTTP/1.0\r\n";
  $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
  $fp = fsockopen ('mercury.myserverhosts.com', 80, $errno, $errstr, 30);
 */

if (!$fp) {
    // HTTP ERROR
    //echo "socket error!<br>";
} else {
    if (isset($_REQUEST['payment_status']) && $_REQUEST['payment_status'] == 'Completed') {
        // check the payment_status is Completed
        // check that txn_id has not been previously processed
        // check that receiver_email is your Primary PayPal email
        // check that payment_amount/payment_currency are correct
        // process payment
        // echo the response

        /* $QUERY		=	"select * from ".$table."product where pid=".$_REQUEST['item_number']."";
          $result		=	$dbcon->execute_query($QUERY) ;
          $grid 		=	$dbcon->fetch_records() ; */

        // get the payment gross as month value for soc user
        $total = $_REQUEST['mc_gross'];
        $order = new Order();
        /**
         * notify_url & return , it will create tow same record in order_detail table
         */
        list($StoreID, $OrderID, $buyid, $paymentdate) = split(',', $_REQUEST['custom']);
        $OrderID = $OrderID ? $OrderID : $_REQUEST['item_number'];
        
//        echo "before orderSendMail : " . $OrderID . "<br/>\n";
        $order->orderSendMail($OrderID);
//        echo "after orderSendMail : " . "<br/>\n";
        
        $query = "select count(*) as num from {$table}order_foodwine where OrderID='$OrderID' and p_status='paid'";
        $result = $dbcon->execute_query($query);
        $res = $dbcon->fetch_records(true);
        if ($res[0]['num'] < 1) {
            $arrSetting = array(
                'p_status' => 'paid',
                'paid_date' => time()
            );
            $dbcon->update_record($table . 'order_foodwine', $arrSetting, "where OrderID='$OrderID'");
            $dbcon->update_record($table . 'order_reviewref', $arrSetting, "where OrderID_foodwine='$OrderID'");
        }

        $flag = "Payment is successful.";
        header("Location:soc.php?cp=message&payment=1&StoreID=" . $StoreID . "&msg=" . $flag . $downurl);
    } 
    else{

        // log for manual investigation
        // echo the response
        $total = $_REQUEST['mc_gross'];
        /* $QUERY		=	"select * from ".$table."product where pid=".$_REQUEST['item_number']."";
          $result		=	$dbcon->execute_query($QUERY) ;
          $grid 		=	$dbcon->fetch_records() ; */
        list($StoreID, $OrderID, $buyid, $paymentdate) = split(',', $_REQUEST['custom']);
        $OrderID = $OrderID ? $OrderID : $_REQUEST['item_number'];
        $arrSetting = array(
            'p_status' => 'faild',
            'paid_date' => time()
        );
        $dbcon->update_record($table . 'order_foodwine', $arrSetting, "where OrderID='$OrderID'");

        $flag = "Invalid payment at Paypal. Please try again.";

//        print_r($_REQUEST);
//        echo "<br/><br/>";
//        echo "res: ".$res."\n";
//        exit();

        header("Location:soc.php?cp=message&msg=" . $flag);
    }
    fclose($fp);
}

exit;
?>


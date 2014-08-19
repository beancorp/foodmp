<?php
include_once "include/session.php" ;
include_once "include/config.php" ;
include_once ('include/smartyconfig.php');
include_once "include/maininc.php" ;
include_once "include/functions.php";
include_once "include/class/common.php";
include_once "include/class.soc.php";
include_once "include/class.socstore.php";
include_once "include/class.emailClass.php";

// read the post from PayPal system and add 'cmd'

//ini_set('display_errors', 1);
//error_reporting(E_ALL);
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	//echo "post-key: $key; val: $value<br>";
	$req .= "&$key=$value";
}

// post back to PayPal system to validate
/* Live site code*/
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
//$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

$paypal_info = getPaypalInfo();
if ($paypal_info['paypal_mode'] == 0) {
	$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
} else {
	$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
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
}else{
	fputs ($fp, $header . $req);
	while (!feof($fp)) {
		$res = fgets ($fp, 1024);
		//echo $res;
		//echo "res: ".htmlspecialchars($res)."\n";
		if (strcmp ($res, "VERIFIED") == 0) {
			// check the payment_status is Completed
			// check that txn_id has not been previously processed
			// check that receiver_email is your Primary PayPal email
			// check that payment_amount/payment_currency are correct
			// process payment
			// echo the response
			
			/**
			 * notify_url & return , it will create tow same record in order_detail table
			 */
			$flag = "Payment is successful.";
			
			list($StoreID,$ref_id,$attribute,$paymentdate) = split(',',$_REQUEST['custom']);			
			$query = "select count(*) as num from {$table}order_reviewref where ref_id='$ref_id' and p_status='paid'";
			$result = $dbcon->execute_query($query);
			$res = $dbcon->fetch_records(true);
			if($res[0]['num']<1){				
				$arrSetting = array(
					'p_status'	=> 'paid',
					'paid_date' => time() 
				);
				$dbcon->update_record($table.'order_reviewref',$arrSetting,"where ref_id='$ref_id'");
				
				$sql = "SELECT * FROM {$table}order_reviewref WHERE ref_id='$ref_id' AND p_status='paid'";
				$info = $dbcon->getOne($sql);
				
				$pid_ary = $info['pids'] ? explode(',', $info['pids']) : array();
				$socstoreObj = new socstoreClass();
				$res = $socstoreObj->productActive($StoreID, $info['product_feetype'], $pid_ary, $info['month']);
				$flag	=	$res ? "Payment is successful." : "Payment is failed.";
			}

			header("Location:soc.php?act=signon&step=4&msg=".$flag);

			//loop through the $_POST array and print all vars to the screen.
			//foreach($_POST as $key => $value){
			//echo $key." = ". $value."<br>";
			//}
		}else if (strcmp ($res, "INVALID") == 0) {

			// log for manual investigation
			// echo the response
			list($StoreID,$ref_id,$attribute,$paymentdate) = split(',',$_REQUEST['custom']);
			$arrSetting = array(
				'p_status'	=> 'faild',
				'paid_date' => time() 
			);
			
			$dbcon->update_record($table.'order_reviewref',$arrSetting,"where ref_id='$ref_id'");
			
			$flag="Invalid payment at Paypal. Please try again.";

			header("Location:soc.php?act=signon&step=4&msg=".$flag);

		}
	}
	fclose ($fp);
}

exit;
?>


<?php
include_once "include/session.php" ;
include_once "include/config.php" ;
include_once 'include/smartyconfig.php';
include_once "include/maininc.php" ;
include_once "include/functions.php";
include_once "include/class/common.php";
include_once "include/class.soc.php";
include_once "include/class.paymentadaptive.php";

//ini_set("display_errors", "1"); 
//error_reporting(E_ALL);

list($StoreID,$OrderID,$buyid,$paymentdate) = split(',',$_REQUEST['custom']);
$OrderID = $OrderID ? $OrderID : $_REQUEST['item_number'];

$pay_key = $_POST['pay_key'];
if (empty($pay_key)) {
	$QUERY = "select * from ".$table."order_foodwine where OrderID='$OrderID'";
	$result	= $dbcon->getOne($QUERY);
	
	$pay_key = $result['adaptive_pay_key'];
	
	/*$fp = fopen('adaptive_notify.txt', 'w+');
	fwrite($fp, serialize($result));
	fclose($fp);*/
}

$adaptive = new PaymentAdaptive();
$paymentInfo = $adaptive->checkPayment($pay_key);

if ($paymentInfo && $paymentInfo['status'] == 'COMPLETED') {
	// check the payment_status is Completed
	// check that txn_id has not been previously processed
	// check that receiver_email is your Primary PayPal email
	// check that payment_amount/payment_currency are correct
	// process payment
	// echo the response
	
	//trackingId is the ref_id
	//$ref_id = $paymentInfo['trackingId'];
	// get the payment total money
	$total = $paymentInfo['paymentInfoList.paymentInfo(0).receiver.amount'];
	
	$QUERY		=	"select * from ".$table."product where pid=".$_REQUEST['item_number']."";
	$result		=	$dbcon->execute_query($QUERY) ;
	$grid 		=	$dbcon->fetch_records() ;
	
	
	/**
	 * notify_url & return , it will create tow same record in order_detail table
	 */
	$query = "select count(*) as num from {$table}order_foodwine where OrderID='$OrderID' and p_status='paid'";
	$result = $dbcon->execute_query($query);
	$res = $dbcon->fetch_records(true);
	if($res[0]['num']<1){				
		$arrSetting = array(
			'p_status'	=> 'paid',
			'paid_date' => time(),
			'adaptive_pay_response' => serialize($paymentInfo)
		);
		$dbcon->update_record($table.'order_foodwine',$arrSetting,"where OrderID='$OrderID'");
	}

	$flag	=	"Payment is successful.";

	header("Location:soc.php?cp=message&StoreID=".$StoreID."&msg=".$flag.$downurl);

	//loop through the $_POST array and print all vars to the screen.
	//foreach($_POST as $key => $value){
	//echo $key." = ". $value."<br>";
	//}
} else {

	// log for manual investigation
	// echo the response
	$total = $paymentInfo['paymentInfoList.paymentInfo(0).receiver.amount'];
	/*$QUERY		=	"select * from ".$table."product where pid=".$_REQUEST['item_number']."";
	$result		=	$dbcon->execute_query($QUERY) ;
	$grid 		=	$dbcon->fetch_records() ;*/
	$arrSetting = array(
		'p_status'	=> 'faild',
		'paid_date' => time(),
		'adaptive_pay_response' => serialize($paymentInfo)
	);
	$dbcon->update_record($table.'order_foodwine',$arrSetting,"where OrderID='$OrderID'");
	$dbcon->update_record($table.'order_reviewref',$arrSetting,"where OrderID_foodwine='$OrderID'");
	
	$flag="Invalid payment at Paypal. Please try again.";

	header("Location:soc.php?cp=message&msg=".$flag);

}
		
exit;
?>


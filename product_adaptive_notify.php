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


list($StoreID,$ref_id,$buyid,$paymentdate) = split(',',$_REQUEST['custom']);

$pay_key = $_POST['pay_key'];
if (empty($pay_key)) {
	$QUERY = "select * from ".$table."order_reviewref where ref_id='$ref_id'";
	$result	= $dbcon->getOne($QUERY);
	
	$pay_key = $result['adaptive_pay_key'];
}

$adaptive = new PaymentAdaptive();
$paymentInfo = $adaptive->checkPayment($pay_key);
	
/*$fp = fopen('adaptive_notify.txt', 'w+');
fwrite($fp, serialize($paymentInfo));
fclose($fp);*/

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
	
	$query		=	"select * from ".$table."product where pid=".$_REQUEST['item_number']."";
	$result		=	$dbcon->execute_query($query) ;
	$grid 		=	$dbcon->fetch_records() ;
	
	
	/**
	 * notify_url & return , it will create tow same record in order_detail table
	 */
	$query = "select count(*) as num from {$table}order_reviewref where ref_id='$ref_id' and p_status='paid'";
	$result = $dbcon->execute_query($query);
	$res = $dbcon->fetch_records(true);
	if($res[0]['num']<1 && $pay_key){
		$QUERY = "insert into ".$table."order_detail (buyer_id,pid,delivery,quantity,status,amount) values('"
				.$buyid."','".$grid[0]['pid']."','".$grid[0]['postage']."','"
				.$_REQUEST['quantity']."','paid','".$total."')";
		$result	= mysql_query($QUERY);
		$orderID = mysql_insert_id();
		
		$arrSetting = array(
			'OrderID'	=> $orderID,
			'p_status'	=> 'paid',
			'paid_date' => time(),
			'adaptive_pay_response' => serialize($paymentInfo)
		);
		$dbcon->update_record($table.'order_reviewref',$arrSetting,"where ref_id='$ref_id'");
	}
	/*
	$reviewKey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
	$socObj->socSendMail($orderID);
		'pid'		=> $grid[0]['pid'],
		'buyer_id'	=> $_SESSION['ShopID'],
		'StoreID'	=> $grid[0]['StoreID'],
		'reviewkey'	=> $reviewKey
	*/
	if($grid[0]['isattachment']==1){
		$query = "SELECT * FROM {$table}product_download where pid='{$grid[0]['pid']}' and bid='{$buyid}' and paymentdate='{$paymentdate}'";
		$dbcon->execute_query($query);
		$downresult = $dbcon->fetch_records(true);
		if(is_array($downresult)&&count($downresult)>0){
		}else{
			$query  = "SELECT downkey FROM {$table}product_download";
			$dbcon -> execute_query($query);
			$downkeyresult = $dbcon->fetch_records(true);
			$downkey_ary = array();
			foreach ($downkeyresult as $pass){
				$downkey_ary[] = $pass['downkey'];
			}
			for(;;){
				$downkey = randStr(10);
				if(!in_array($downkey,$downkey_ary)){
					break;
				}
			}

			$query = "insert into ".$table."product_download (`pid`,`bid`,`paydate`,`lastdowndate`,`paymentdate`,`downkey`) values('{$grid[0]['pid']}','{$buyid}','".time()."','".(time()+86400)."','$paymentdate','$downkey')";
			if($dbcon->execute_query($query)){
				$socObj = new socClass();
				$socObj->downSendMail($grid[0]['pid'],$buyid,$StoreID,$downkey,$total,'paypal');
			}
		}
		$downurl = "&downable=".urlencode("/soc.php?cp=dispro&StoreID=$StoreID&proid={$grid[0]['pid']}");
	}

	$flag	=	"Payment is successful.";

	header("Location:soc.php?cp=message&StoreID=".$grid[0]['StoreID']."&msg=".$flag.$downurl);

	//loop through the $_POST array and print all vars to the screen.
	//foreach($_POST as $key => $value){
	//echo $key." = ". $value."<br>";
	//}
} else {

	// log for manual investigation
	// echo the response
	$total = $paymentInfo['paymentInfoList.paymentInfo(0).receiver.amount'];
	$QUERY		=	"select * from ".$table."product where pid=".$_REQUEST['item_number']."";
	$result		=	$dbcon->execute_query($QUERY) ;
	$grid 		=	$dbcon->fetch_records() ;
	$QUERY = "insert into ".$table."order_detail (buyer_id,pid,delivery,quantity,status,amount) values('"
			.$buyid."','".$grid[0]['pid']."','".$grid[0]['postage']."','"
			.$_REQUEST['quantity']."','faild','".$total."')";
	$result		=	mysql_query($QUERY);
	$orderID = mysql_insert_id();
	$arrSetting = array(
		'OrderID'	=> $orderID,
		'p_status'	=> 'faild',
		'paid_date' => time(),
		'adaptive_pay_response' => serialize($paymentInfo)
	);
	$dbcon->update_record($table.'order_reviewref',$arrSetting,"where ref_id='$ref_id'");
	
	$flag="Invalid payment at Paypal. Please try again.";

	header("Location:soc.php?cp=message&msg=".$flag);

}
		
exit;
?>


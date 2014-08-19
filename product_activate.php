<?php
include_once "include/session.php" ;
include_once "include/config.php" ;
include_once ('include/smartyconfig.php');
include_once "include/maininc.php" ;
include_once "include/functions.php";
include_once "include/class/common.php";
include_once "include/class.soc.php";

// read the post from PayPal system and add 'cmd'

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

			$QUERY		=	"select * from ".$table."product where pid=".$_REQUEST['item_number']."";
			$result		=	$dbcon->execute_query($QUERY) ;
			$grid 		=	$dbcon->fetch_records() ;
			
			// get the payment gross as month value for soc user
			$total = $_REQUEST['mc_gross'];
			
			/**
			 * notify_url & return , it will create tow same record in order_detail table
			 */
			list($StoreID,$ref_id,$buyid,$paymentdate) = split(',',$_REQUEST['custom']);
			$query = "select count(*) as num from {$table}order_reviewref where ref_id='$ref_id' and p_status='paid'";
			$result = $dbcon->execute_query($query);
			$res = $dbcon->fetch_records(true);
			if($res[0]['num']<1){
				$QUERY = "insert into ".$table."order_detail (buyer_id,pid,delivery,quantity,status,amount) values('"
						.$buyid."','".$grid[0]['pid']."','".$grid[0]['postage']."','"
						.$_REQUEST['quantity']."','paid','".$total."')";
				$result		=	mysql_query($QUERY);
				$orderID = mysql_insert_id();
				
				$arrSetting = array(
					'OrderID'	=> $orderID,
					'p_status'	=> 'paid',
					'paid_date' => time() 
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
		}else if (strcmp ($res, "INVALID") == 0) {

			// log for manual investigation
			// echo the response
			$total = $_REQUEST['mc_gross'];
			$QUERY		=	"select * from ".$table."product where pid=".$_REQUEST['item_number']."";
			$result		=	$dbcon->execute_query($QUERY) ;
			$grid 		=	$dbcon->fetch_records() ;
			list($StoreID,$ref_id,$buyid,$paymentdate) = split(',',$_REQUEST['custom']);
			$QUERY = "insert into ".$table."order_detail (buyer_id,pid,delivery,quantity,status,amount) values('"
					.$buyid."','".$grid[0]['pid']."','".$grid[0]['postage']."','"
					.$_REQUEST['quantity']."','faild','".$total."')";
			$result		=	mysql_query($QUERY);
			$orderID = mysql_insert_id();
			$arrSetting = array(
				'OrderID'	=> $orderID,
				'p_status'	=> 'faild',
				'paid_date' => time() 
			);
			$dbcon->update_record($table.'order_reviewref',$arrSetting,"where ref_id='$ref_id'");
			
			$flag="Invalid payment at Paypal. Please try again.";

			header("Location:soc.php?cp=message&msg=".$flag);

		}
	}
	fclose ($fp);
}

exit;
?>


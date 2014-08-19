<?php
include_once "include/session.php" ;
include_once "include/config.php" ;
include_once "include/smartyconfig.php";
include_once "include/maininc.php" ;
include_once "include/functions.php";
include_once "include/class/common.php";
include_once "include/class.soc.php";
include_once ('class.emailClass.php');
include_once "include/class.wishlist.php";
include_once "ecardsend.php";

// read the post from PayPal system and add 'cmd'

$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}

// post back to PayPal system to validate
/* Live site code*/
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
if (PAYPAL_DEBUG == 1) {
	$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
} else {
	$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
}
if (!$fp) {
	// HTTP ERROR
}else{
	fputs ($fp, $header . $req);
	while (!feof($fp)) {
		$res = fgets ($fp, 1024);
		//echo $res;
		//echo "res: ".htmlspecialchars($res)."\n";
		if (strcmp ($res, "VERIFIED") == 0) {
			// process payment

			$orderID = $_REQUEST['custom'];
			$query = "SELECT p_status FROM {$table}wishlist_order where OrderID='$orderID'";
			$dbcon->execute_query($query);
			$result = $dbcon->fetch_records(true);
			$firstorder = true;
			if($result[0]['p_status']=='paid'){
				$firstorder = false;
			}
			$arrSetting = array('p_status'=>'paid',
								'paid_date'=>time());
			$dbcon->update_record($table.'wishlist_order',$arrSetting,"where OrderID='$orderID'");
			$ecard = new ecardClass();
			if($firstorder){
				$query = "SELECT * FROM {$table}wishlist_order WHERE OrderID='$orderID'";
				$dbcon->execute_query($query);
				$result = $dbcon->fetch_records(true);
				$orderInfo = $result[0];
				
				$query = "SELECT * FROM {$table}bu_detail WHERE StoreID='{$orderInfo['StoreID']}'";
				$dbcon->execute_query($query);
				$result = $dbcon->fetch_records(true);
				$sellerInfo = $result[0];
				
				$query = "SELECT * FROM {$table}wishlist a left join {$table}wishlist_image b on a.pid=b.pid and a.StoreID=b.StoreID and b.attrib=0 and b.sort=0 where a.pid={$orderInfo['pid']} ";
				$dbcon->execute_query($query);
				$result = $dbcon->fetch_records(true);
				$proinfo = $result[0];
				$profile = "/images/243x212.jpg";
				if($proinfo['smallPicture']){
					if(file_exists(ROOT_PATH.$proinfo['smallPicture'])){
						$profile = $proinfo['smallPicture'];
					}
				}
				
				$wishlist = new wishlist();
				$hosturl="http://{$_SERVER['HTTP_HOST']}";
				$productlink = $hosturl.$wishlist->getProURL($orderInfo['StoreID'],$orderInfo['pid']);
				$arrParams = array('accept'=>'seller',
								   'Subject'=>'Item gifted on SOC Exchange Australia',
								   'buyer_nickname'=>$orderInfo['name'],
								   'buyer_email'=>$orderInfo['email'],
								   'message'=>$orderInfo['message'],
								   'amount'=>$orderInfo['amount'],
								   'total_amount'=>$orderInfo['total_amount'],
								   'item_name'=>$orderInfo['item_name'],
								   'productLink'=>$productlink,
								   'wishlistLink'=>$hosturl.$wishlist->getProURL($orderInfo['StoreID']),
								   'seller_name'=>$sellerInfo['bu_name'],
								   'To'=>$sellerInfo['bu_email'],);
					
				if(($arrParams['total_amount']*100-$arrParams['amount']*100)>0){
					$arrParams['payInculde']='Paypal charges included';
				}
				ob_start();
				$objEmail	=	new emailClass();
				$objEmail -> send($arrParams,'wishlist/wishlist_email.tpl',true);
				unset($objEmail);
				$arrParams['accept'] = 'buyer';
				$arrParams['To']	= $orderInfo['email'];
				$objEmail	=	new emailClass();
				$objEmail -> send($arrParams,'wishlist/wishlist_email.tpl',true);
				unset($objEmail);
							
				$products = array('nickname'=>$sellerInfo['bu_nickname'],'message'=>substr(strip_tags($orderInfo['message']),0,250).(strlen(strip_tags($orderInfo['message']))>250?"...":""),'buyerName'=>$orderInfo['name'],'proPrice'=>number_format($orderInfo['total_amount'],2),'proName'=>substr($proinfo['item_name'],0,30).(strlen($proinfo['item_name'])>30?"...":""),'proDesc'=>substr(strip_tags($proinfo['description']),0,60).(strlen(strip_tags($proinfo['description']))>60?"...":""),'profile'=>$profile);
				if(($orderInfo['total_amount']*100-$orderInfo['amount']*100)>0){
					$products['payInculde']='Paypal charges included';
				}
				$subject = "Wishlist Ecard";
				$message .= $ecard->bulidheader($subject,$orderInfo['name'].' <noreply@thesocexchange.com>',$sellerInfo['bu_nickname'].' <'.$sellerInfo['bu_email'].'>');
				$message .= $ecard->bulidmessagecontent('0016e6496c3e0aad8d0475778edd',$products,$orderInfo['ecardtpl']);
				@mail($sellerInfo['bu_email'],$subject,'',$message);
				@mail($orderInfo['email'],$subject,'',$message);
			}
			ob_end_clean();
			$flag	=	"Payment is successful.";
			ob_end_clean();
			header("Location:soc.php?cp=message&msg=".$flag.$downurl);
		}else if (strcmp ($res, "INVALID") == 0) {
			// echo the response
			$orderID = $_REQUEST['custom'];
			$arrSetting = array(
				'p_status'	=> 'cancel',
				'paid_date' => time() 
			);
			$dbcon->update_record($table.'wishlist_order',$arrSetting,"where OrderID='$orderID'");
			$query = "SELECT * FROM {$table}wishlist_order WHERE OrderID='$orderID'";
			$dbcon->execute_query($query);
			$result = $dbcon->fetch_records(true);
			$query = "update {$table}wishlist set gifted=gifted-{$result[0]['amount']} where pid='{$result[0]['pid']}'";
			$dbcon->execute_query($query);
			$flag="Invalid payment at Paypal. Please try again.";

			header("Location:soc.php?cp=message&msg=".$flag);

		}
	}
	fclose ($fp);
}

exit;
?>
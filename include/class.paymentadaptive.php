<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class PaymentAdaptive extends common
{

    private $lang;
    private $msg = '';
    private $dbcon = null;
    private $table	= '';
	private $API_AppID = '';
	private $API_RequestFormat = "NV";
	private $API_ResponseFormat = "NV";
	private $paypal_info = array();
	public $commission_rate = 0.4;
	public $commission_max_money = 10;

    /**
     * construct
     */
    public function  __construct()
    {
        $this->lang	= &$GLOBALS['_LANG'];
        $this->dbcon  = &$GLOBALS['dbcon'];
        $this->table	= &$GLOBALS['table'];
    	$this->paypal_info = getPaypalInfo();
        $this->API_AppID = $this->paypal_info['paypal_mode'] == 0 ? 'APP-80W284485P519543T' : $this->paypal_info['paypal_app_id'];
        
        $commission_info = $this->getCommissionInfo();
        $this->commission_rate = $commission_info['commission_rate'] ? $commission_info['commission_rate'] : $this->commission_rate;
        $this->commission_max_money = $commission_info['commission_max'] ? $commission_info['commission_max'] : $this->commission_max_money;
    }
    
	public function getCommissionInfo()
	{
		$sql = "SELECT * FROM {$this->table}commission WHERE 1 ORDER BY id DESC, modify_date DESC LIMIT 1";
		return $this->dbcon->getOne($sql);
	}

    /**
     * get payment infomation
     */
    public function getPaypalInfo($type='Pay')
    {
        $res = array(
            'paypal_url' => $this->paypal_info['paypal_mode'] == 0 ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr',
            'adaptivepayments_url' => $this->paypal_info['paypal_mode'] == 0 ? 'https://svcs.sandbox.paypal.com/AdaptivePayments/'.$type : 'https://svcs.paypal.com/AdaptivePayments/'.$type,
            'paypal_email' => $this->paypal_info['paypal_email'],
            'paypal_api_username' => $this->paypal_info['paypal_api_username'],
            'paypal_api_password' => $this->paypal_info['paypal_api_password'],
            'paypal_api_signature' => $this->paypal_info['paypal_api_signature'],
            'paypal_siteurl' => PAYPAL_SITEURL
        );
        
        return $res;
    }

    /**
     * ChainedPay Submit
     */
    public function submit($paymentInfo, $attribute=0, $OrderID='')
    {
    	global $socObj;
    	
    	$amount = $attribute == 0 ? $paymentInfo['price'] : $paymentInfo['amount'];
    	$primary_money = round($amount * $paymentInfo['quantity'], 2);
    	
    	$commission_money = round($primary_money * $this->commission_rate, 2);
    	$commission_money = $commission_money > $this->commission_max_money ? $this->commission_max_money : $commission_money;
    	$primary_money += round($paymentInfo['quantity'] * $paymentInfo['postage'], 2);
    	$seller_money = $primary_money - $commission_money;
    	
    	$paypalInfo = $this->getPaypalInfo();
    	
    	if ($attribute == 0) {
    		$custom = $paymentInfo['StoreID'].','.$paymentInfo['ref_id'].','.$_SESSION['ShopID'].','.time();
    		$ipnNotificationUrl = $paypalInfo['paypal_siteurl'].'product_adaptive_notify.php?custom='.$custom.'&item_number='.$paymentInfo['item_number'].'&quantity='.$paymentInfo['quantity'];
    	} elseif ($attribute == 5) {
    		$custom = $paymentInfo['StoreID'].','.$OrderID.','.$_SESSION['ShopID'].','.time();
    		$ipnNotificationUrl = $paypalInfo['paypal_siteurl'].'order_adaptive_notify.php?custom='.$custom.'&item_number='.$paymentInfo['item_number'].'&quantity='.$paymentInfo['quantity'];
    	}
    	
    	//get seller's info
		$_query = "SELECT t1.bu_nickname,t1.*, t1.bu_name, t2.user FROM ".$this->table."bu_detail t1, ".$this->table."login t2 WHERE t1.StoreID=".$paymentInfo['StoreID']." AND t1.StoreID=t2.StoreID";
		$this->dbcon->execute_query($_query);
		$seller = $this->dbcon->fetch_records(true);
		$seller_nickname = $seller[0]['bu_nickname'];
		$seller_name = $seller[0]['bu_name'];
		$seller_email	= $seller[0]['user'];
		$seller_phone = $seller[0]['bu_phone'];
				
		//get buyer's info
		$_query = "SELECT t1.bu_nickname,t1.bu_phone, t2.user FROM ".$this->table."bu_detail t1, ".$this->table."login t2 WHERE t1.StoreID=".$_SESSION['ShopID']." AND t1.StoreID=t2.StoreID";
		$this->dbcon->execute_query($_query);
		$buyer = $this->dbcon->fetch_records(true);
		$buyer_nickname = $buyer[0]['bu_nickname'];
		$buyer_email	= $buyer[0]['user'];
		$buyer_phone	= $buyer[0]['bu_phone'];
		
		//get product info
		$shipping_method_ary = $socObj->getStorePayment($paymentInfo['StoreID']);
		$sql = "select * from ".$this->table."product where pid={$paymentInfo['pid']}";
		$this->dbcon->execute_query($sql);
		$productInfo = $this->dbcon->fetch_records();
		$product_name = $productInfo[0]['item_name'];
		$quantity = $paymentInfo['quantity'];
		$amount = $paymentInfo['amount'];  
		$shipping_method = $shipping_method_ary[$paymentInfo['deliveryMethod']];
		$shipping_cost = $paymentInfo['shipping'];
		$total = $amount;
    	
    	$memo = "[Product Name: $product_name][Cost: $amount][Quantity: $quantity][Shipping Method: $shipping_method][Shipping Cost: $shipping_cost][Payment Method: PayPal][Total: $total][Buyer's Nickname: $buyer_nickname][Buyer's Email: $buyer_email][Buyer's Phone: $buyer_phone]";
    	
       	//Create request payload with minimum required parameters
		$bodyparams = array (	"requestEnvelope.errorLanguage" => "en_US",
								"actionType" => "PAY",
								"currencyCode" => CURRENCYCODE,
								//"feesPayer" => "SECONDARYONLY",
								"feesPayer" => "PRIMARYRECEIVER",
								//"feesPayer" => "EACHRECEIVER",
								"memo" => $memo,
								"cancelUrl" => $ipnNotificationUrl,
								"returnUrl" => $ipnNotificationUrl,
								"ipnNotificationUrl" => $ipnNotificationUrl,
								"reverseAllParallelPaymentsOnError" => true,
								"receiverList.receiver(0).email" => $paymentInfo['business'], //TODO
								"receiverList.receiver(0).amount" => $primary_money, //TODO
								"receiverList.receiver(0).primary" => "true", //TODO
								"receiverList.receiver(1).email" => $paypalInfo['paypal_email'], //TODO
								"receiverList.receiver(1).amount" => $commission_money, //TODO
								"receiverList.receiver(1).primary" => "false", //TODO
								
								"trackingId" => $paymentInfo['ref_id'],
								"StoreID" => $paymentInfo['StoreID'],
								"shipping" => $paymentInfo['quantity']*$paymentInfo['postage'],
								"custom" => $paymentInfo['StoreID'].','.$paymentInfo['ref_id'].','.$_SESSION['ShopID'].','.time()
							);
							
		// convert payload array into url encoded query string
		$body_data = http_build_query($bodyparams, "", chr(38));
		
		try
		{
		
		    //create request and add headers
		    $params = array("http" => array( 
									"method" => "POST",
              						"content" => $body_data,
              						"header" =>  "X-PAYPAL-SECURITY-USERID: " . $paypalInfo['paypal_api_username'] . "\r\n" .
                           			"X-PAYPAL-SECURITY-SIGNATURE: " . $paypalInfo['paypal_api_signature'] . "\r\n" .
             						"X-PAYPAL-SECURITY-PASSWORD: " . $paypalInfo['paypal_api_password'] . "\r\n" .
									"X-PAYPAL-APPLICATION-ID: " . $this->API_AppID . "\r\n" .
									"X-PAYPAL-REQUEST-DATA-FORMAT: " . $this->API_RequestFormat . "\r\n" .
									"X-PAYPAL-RESPONSE-DATA-FORMAT: " . $this->API_ResponseFormat . "\r\n" 
		              )
		    );
		
		    //create stream context
		     $ctx = stream_context_create($params);    
		
		    //open the stream and send request
		     $fp = @fopen($paypalInfo['adaptivepayments_url'], "r", false, $ctx);
		
		     if (!$fp) {
				exit('Invalid payment at Paypal. Please try again.');
		     }
		    //get response
		  	 $response = stream_get_contents($fp);
		
		  	//check to see if stream is open
		     if ($response === false) {
		        throw new Exception("php error message = " . "$php_errormsg");
		     }
		           
		    //close the stream
		     fclose($fp);
		
		    //parse the ap key from the response
		    $keyArray = explode("&", $response);
		        
		    foreach ($keyArray as $rVal){
		    	list($qKey, $qVal) = explode ("=", $rVal);
					$kArray[$qKey] = $qVal;
		    }
		    
		    //
		    $arrSetting = array(
				'adaptive_pay_key' => $kArray["payKey"],
				'commission_type' => '1',
				'commission' => $commission_money
			);
			
			if ($attribute == 0) {
				$this->dbcon->update_record($this->table.'order_reviewref',$arrSetting,"where ref_id='{$paymentInfo['ref_id']}'");
			} elseif ($attribute == 5) {
				$this->dbcon->update_record($this->table.'order_foodwine',$arrSetting,"where OrderID='{$OrderID}'");
				$this->dbcon->update_record($this->table.'order_reviewref',$arrSetting,"where OrderID_foodwine='{$OrderID}'");
			}
			
		       
		    //set url to approve the transaction
		    $payPalURL = $paypalInfo['paypal_url']."?cmd=_ap-payment&paykey=" . $kArray["payKey"];
			
		    $fp = fopen(ROOT_PATH.'/log/adaptive_log.txt', 'a+');
		    $content = "[".date('D M d H:i:s Y', time())."] Response: " . $response . " \r\n";
		    fwrite($fp, $content);
		    fclose($fp);
		    
		    //print the url to screen for testing purposes
		    /*if ( $kArray["responseEnvelope.ack"] == "Success") {
		    	echo '<p><a href="' . $payPalURL . '" target="_blank">' . $payPalURL . '</a></p>';
		     } else {
		    	echo 'ERROR Code: ' .  $kArray["error(0).errorId"] . " <br/>";
		      	echo 'ERROR Message: ' .  urldecode($kArray["error(0).message"]) . " <br/>";
		    }*/
		   
		   	//optional code to redirect to PP URL to approve payment
		    if ( $kArray["responseEnvelope.ack"] == "Success") {   
			  	header("Location:".  $payPalURL);
			    exit;
		     } else {		     	
				$flag="Invalid payment at Paypal. Please try again.";
				header("Location:soc.php?cp=message&msg=".$flag);
		     	//echo 'ERROR Code: ' .  $kArray["error(0).errorId"] . " <br/>";
		        //echo 'ERROR Message: ' .  urldecode($kArray["error(0).message"]) . " <br/>";
		     }
		} catch (Exception $e) {
			$flag="Invalid payment at Paypal. Please try again.";
			header("Location:soc.php?cp=message&msg=".$flag);
		  	//echo "Message: ||" .$e->getMessage()."||";
		}
    }

    /**
     * Check Payments
     */
    public function checkPayment($payKey='')
    {
    	if (empty($payKey)) {
    		return false;
    	}
    	
    	$paypalInfo = $this->getPaypalInfo('PaymentDetails');
    	
    	//Create request payload with minimum required parameters
		$bodyparams = array (
							"requestEnvelope.errorLanguage" => "en_US",
							"payKey" => $payKey
					);
													
		// convert payload array into url encoded query string
		$body_data = http_build_query($bodyparams, "", chr(38));
		
		try
		{
		
		    //create request and add headers
		    $params = array("http" => array( 
									"method" => "POST",
              						"content" => $body_data,
              						"header" =>  "X-PAYPAL-SECURITY-USERID: " . $paypalInfo['paypal_api_username'] . "\r\n" .
                           			"X-PAYPAL-SECURITY-SIGNATURE: " . $paypalInfo['paypal_api_signature'] . "\r\n" .
             						"X-PAYPAL-SECURITY-PASSWORD: " . $paypalInfo['paypal_api_password'] . "\r\n" .
									"X-PAYPAL-APPLICATION-ID: " . $this->API_AppID . "\r\n" .
									"X-PAYPAL-REQUEST-DATA-FORMAT: " . $this->API_RequestFormat . "\r\n" .
									"X-PAYPAL-RESPONSE-DATA-FORMAT: " . $this->API_ResponseFormat . "\r\n" 
		              )
		    );
		
		
		    //create stream context
		     $ctx = stream_context_create($params);
		    
		
		    //open the stream and send request
		     $fp = @fopen($paypalInfo['adaptivepayments_url'], "r", false, $ctx);
		
		    //get response
		  	 $response = stream_get_contents($fp);
		
		  	//check to see if stream is open
		     if ($response === false) {
		        throw new Exception("php error message = " . "$php_errormsg");
		     }
		           
		    //close the stream
		     fclose($fp);
		
		    //parse the ap key from the response
		    $keyArray = explode("&", $response);
		        
		    foreach ($keyArray as $rVal){
		    	list($qKey, $qVal) = explode ("=", $rVal);
					$kArray[$qKey] = $qVal;
		    }
		
		    //print the url to screen for testing purposes
		    If ( $kArray["responseEnvelope.ack"] == "Success") {
		    	
		    	 /*foreach ($kArray as $key =>$value){
			    	echo $key . ": " .$value . "<br/>";
			     }*/
		      	
		      	return $kArray;
		     } else {
		    	echo 'ERROR Code: ' .  $kArray["error(0).errorId"] . " <br/>";
		      	echo 'ERROR Message: ' .  urldecode($kArray["error(0).message"]) . " <br/>";
		     }
		   
		   } catch(Exception $e) {
		  		echo "Message: ||" .$e->getMessage()."||";
		   }
		   
		   return false;
    }
    
    /**
     * ChainedPay get commission
     */
    public function getCommission($paymentInfo, $attribute=0, $OrderID='')
    {
    	$amount = $attribute == 0 ? $paymentInfo['price'] : $paymentInfo['amount'];
    	$primary_money = round($amount * $paymentInfo['quantity'], 2);
    	
    	$commission_money = round($primary_money * $this->commission_rate, 2);
    	$commission_money = $commission_money > $this->commission_max_money ? $this->commission_max_money : $commission_money;
    	//$primary_money += round($paymentInfo['quantity'] * $paymentInfo['postage'], 2);
    	//$seller_money = $primary_money - $commission_money;
    	
    	return $commission_money;
    }
}
?>

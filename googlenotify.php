<?php

/**
 * Copyright (C) 2007 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/* This is the response handler code that will be invoked every time
* a notification or request is sent by the Google Server
*
* To allow this code to receive responses, the url for this file
* must be set on the seller page under Settings->Integration as the
* "API Callback URL'
* Order processing commands can be sent automatically by placing these
* commands appropriately
*
* To use this code for merchant-calculated feedback, this url must be
* set also as the merchant-calculations-url when the cart is posted
* Depending on your calculations for shipping, taxes, coupons and gift
* certificates update parts of the code as required
*
*/

include_once "include/session.php" ;
include_once "include/config.php" ;
include_once "include/maininc.php" ;
include_once "include/functions.php";
include_once "include/class/common.php";
include_once "include/class.soc.php";

require_once('./include/googlecheckout/library/googleresponse.php');
require_once('./include/googlecheckout/library/googlemerchantcalculations.php');
require_once('./include/googlecheckout/library/googleresult.php');
require_once('./include/googlecheckout/library/googlerequest.php');

define('RESPONSE_HANDLER_ERROR_LOG_FILE', 'googleerror.log');
define('RESPONSE_HANDLER_LOG_FILE', 'googlemessage.log');

$merchant_id = "";  // Your Merchant ID
$merchant_key = "";  // Your Merchant Key
$server_type = GOOGLE_CHECKOUT_SERVER_TYPE;  // change this to go live
$currency = 'AUD';  // set to GBP if in the UK

$Gresponse = new GoogleResponse($merchant_id, $merchant_key);

$Grequest = new GoogleRequest($merchant_id, $merchant_key, $server_type, $currency);

//Setup the log file
$Gresponse->SetLogFiles(RESPONSE_HANDLER_ERROR_LOG_FILE, RESPONSE_HANDLER_LOG_FILE, L_ALL);

// Retrieve the XML sent in the HTTP POST request to the ResponseHandler
$xml_response = isset($HTTP_RAW_POST_DATA)?$HTTP_RAW_POST_DATA:file_get_contents("php://input");
if (get_magic_quotes_gpc()) {
	$xml_response = stripslashes($xml_response);
}
list($root, $data) = $Gresponse->GetParsedXML($xml_response);
//  $Gresponse->SetMerchantAuthentication($merchant_id, $merchant_key);
/*
$status = $Gresponse->HttpAuthentication();
if(! $status) {
	die('authentication failed');
}
*/
/* Commands to send the various order processing APIs
* Send charge order : $Grequest->SendChargeOrder($data[$root]
*    ['google-order-number']['VALUE'], <amount>);
* Send process order : $Grequest->SendProcessOrder($data[$root]
*    ['google-order-number']['VALUE']);
* Send deliver order: $Grequest->SendDeliverOrder($data[$root]
*    ['google-order-number']['VALUE'], <carrier>, <tracking-number>,
*    <send_mail>);
* Send archive order: $Grequest->SendArchiveOrder($data[$root]
*    ['google-order-number']['VALUE']);
*
*/

switch ($root) {
	case "request-received":
		break;
	case "error":
		break;
	case "diagnosis":
		break;
	case "checkout-redirect":
		break;
	case "merchant-calculation-callback":
		break;
	case "new-order-notification":
		$orderInfo = get_arr_result($data[$root]);
		$orders = get_arr_result($data[$root]['shopping-cart']['items']['item']);
		
		foreach ($orders as $order) {
			$itemName		= $order['item_name']['VALUE'];
			$quantity		= $order['quantity']['VALUE'];
			$itemInfo		= $order['merchant-private-item-data']['VALUE'];
			$order_total	= $order['order-total']['VALUE'];
		}
		
		$sellerInfo = explode(',',$itemInfo);
		foreach($sellerInfo as $val){
			$value = explode('=',$val);
			${$value[0]} = $value[1];
		}
//		$buyerId 	= $order['buyerId'];
//		$pid		= $order['pid'];
//		$deliveryMethod = $order['deliveryMethod'];
//		$ref_id		= $order['ref_id'];
		
		$_query		=	"select * from ".$table."product where pid=".$pid."";
		$result		=	$dbcon->execute_query($_query) ;
		$grid 		=	$dbcon->fetch_records() ;
//
		$_query = "insert into ".$table."order_detail (buyer_id,pid,delivery,quantity,status,amount) values('"
		.$buyerId."','".$grid[0]['pid']."','".$grid[0]['postage']."','"
		.$quantity."','paid','".$order_total."')";
//		$_query = "insert into ".$table."order_detail (buyer_id,pid,delivery,quantity,status,amount) values('1111','2222','3333','4444','paid','5555')";
		$result		=	mysql_query($_query);
		$orderID = mysql_insert_id();

		$arrSetting = array(
			'OrderID'	=> $orderID,
			'p_status'	=> 'paid',
			'paid_date' => time() 
		);
		$dbcon->update_record($table.'order_reviewref',$arrSetting,"where ref_id=$ref_id");
			
		if($grid[0]['isattachment']==1){
			$query = "SELECT * FROM {$table}product_download where pid='{$grid[0]['pid']}' and bid='{$buyerId}' and isdownload=0' and lastdowndate>='".time()."'";
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

				$query = "insert into ".$table."product_download (`pid`,`bid`,`paydate`,`lastdowndate`,`paymentdate`,`downkey`) values('{$grid[0]['pid']}','{$buyerId}','".time()."','".(time()+86400)."','".time()."','$downkey')";
				if($dbcon->execute_query($query)){
					$socObj = new socClass();
					$socObj->downSendMail($grid[0]['pid'],$buyerId,$grid[0]['StoreID'],$downkey,$order_total,'googlecheckout');
				}
			}
		}
		
		
		break;
	case "order-state-change-notification":
		break;
	case "charge-amount-notification":
		break;
	case "chargeback-amount-notification":
		break;
	case "refund-amount-notification":
		break;
	case "risk-information-notification":
		break;
	default:
		$Gresponse->SendBadRequestStatus("Invalid or not supported Message");
		break;
}

/* In case the XML API contains multiple open tags
with the same value, then invoke this function and
perform a foreach on the resultant array.
This takes care of cases when there is only one unique tag
or multiple tags.
Examples of this are "anonymous-address", "merchant-code-string"
from the merchant-calculations-callback API
*/
function get_arr_result($child_node) {
	$result = array();
	if(isset($child_node)) {
		if(is_associative_array($child_node)) {
			$result[] = $child_node;
		}
		else {
			foreach($child_node as $curr_node){
				$result[] = $curr_node;
			}
		}
	}
	return $result;
}

/* Returns true if a given variable represents an associative array */
function is_associative_array( $var ) {
	return is_array( $var ) && !is_numeric( implode( '', array_keys( $var ) ) );
}
?>
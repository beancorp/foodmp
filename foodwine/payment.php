<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include('../indexPart.php');
$indexTemplate	=	'../index.tpl';
include_once('../include/class.order.php');
include_once('../include/class.cart.php');
include_once('../include/class.paymentadaptive.php');

$OrderID = $_REQUEST['OrderID'];		
if (empty($OrderID)) {
	echo "<script>alert('Please come from the currect url.');location.href='../soc.php?cp=home'</script>";
	exit;
}

$order = new Order();
$cart = new Cart();
$socstoreObj = new socstoreClass();
$adaptive = new PaymentAdaptive();

switch($_REQUEST["cp"]){
	case 'paypal':
		require_once (SOC_INCLUDE_PATH . '/class.FoodWine.php');
		require_once (SOC_INCLUDE_PATH . '/class.cart.php');
		
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		
		$cart = new Cart();
		$cart_info = $cart->getGoods();
		$foodWine = new FoodWine();
		
		$req = $foodWine->selectPayment();
		$req = array_merge($req, $cart_info);
		
		if (isset($_SESSION['couponInfo'])) {
			$smarty->assign('couponInfo',$_SESSION['couponInfo']);
			$req['price'] = $_SESSION['couponInfo']['offer'];
			$req['postage'] = $_SESSION['couponInfo']['postage'];
			$req['total'] = $_SESSION['couponInfo']['quantity']*$_SESSION['couponInfo']['postage']+$_SESSION['couponInfo']['quantity']*$_SESSION['couponInfo']['offer'];
		}
		
		$req['price'] = number_format($req['total_money'],2,'.','');
		$req['product_money'] = number_format($req['total_money'],2,'.','');
		$req['total_money'] = number_format(($req['total_money'] + $req['postage']),2,'.','');		
    	
		$arrSetting = array(
			'description'=>'PayPal',
			'total_money'	=> number_format($req['total_money'],2,'.',''),	
			'status' 		=> 1	
		);
		
		$commission_money = round($req['product_money'] * $adaptive->commission_rate, 2);
		$commission_money = $commission_money > $adaptive->commission_max_money ? $adaptive->commission_max_money : $commission_money;
		$arrOrderRefSetting = array(
			'description'	=>	'PayPal',
			'amount'		=> 	number_format($req['total_money'],2,'.',''),	
			//'commission' 	=> 	$commission_money
		);
		
		if (isset($_REQUEST['dest'])) {
			if($_REQUEST['overcountry']==1) {
				/***/
				$shipping_method = $_LANG['Delivery'][$_REQUEST['deliveryMethod']]['text']."(Overseas)";
			} else {
				$shipping_method = $_LANG['Delivery'][$_REQUEST['deliveryMethod']]['text'];
			}
			$shipping_cost = $_REQUEST['shipping'];
		} else {
			$shipping_method = $_LANG['Delivery'][$_REQUEST['deliveryMethod']]['text'];
			$shipping_cost = $_REQUEST['shipping'];
		}
		
		//echo $req['total_money'];
		
		$arrSetting['shipping_method'] = $shipping_method;
		$arrOrderRefSetting['shipping_method'] = $shipping_method;
		$arrSetting['shipping_cost'] = number_format($shipping_cost,2,'.','');
		$arrOrderRefSetting['shipping_cost'] = number_format($shipping_cost,2,'.','');
		
		$socObj->dbcon->update_record($socObj->table.'order_foodwine', $arrSetting, " WHERE OrderID='$OrderID'");
		$socObj->dbcon->update_record($socObj->table.'order_reviewref', $arrOrderRefSetting, " WHERE OrderID_foodwine='$OrderID'");
		$ref_id = $socObj->dbcon->lastInsertId();
//		$order->orderSendMail($OrderID);  // task 6764
		$socstoreObj->updateCouponCode($_SESSION['couponInfo']['UserID'],$_SESSION['couponInfo']['StoreID'],$_SESSION['couponInfo']['pid'],$_SESSION['couponInfo']['coupon_code']);
		unset($_SESSION['couponInfo']);
		
		$socObj->paypalFormOrder($OrderID, $req);
		$cart->delCart();
		
		//echo "alert($pid,".$_SESSION['ShopID'].",$StoreID,$reviewKey,".$productInfo['item_name'].','.$_REQUEST['price'].','.$_REQUEST['quantity'].','.$_REQUEST['postage'].")";
		exit;
		break;
		
	case 'googlecheckout':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Payment');
		$smarty -> assign('keywords','Payment');
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		if (!$StoreID){
			echo "<script>alert('Please login first.');location.href='soc.php?cp=home'</script>";
			exit;
		}
		$arrSetting = array(
			'description'=>'GoogleCheckout',
			'total_money'	=> number_format($_REQUEST['total_money'],2,'.',''),	
			'status' 		=> 1	
		);
		
		$commission_money = round($_REQUEST['product_money'] * $adaptive->commission_rate, 2);
		$commission_money = $commission_money > $adaptive->commission_max_money ? $adaptive->commission_max_money : $commission_money;
		$arrOrderRefSetting = array(
			'description'	=>	'GoogleCheckout',
			'amount'		=> 	number_format($_REQUEST['total_money'],2,'.',''),	
			//'commission' 	=> 	$commission_money	
		);
		if(isset($_REQUEST['dest'])){
			if($_REQUEST['overcountry']==1){
				/***/
				$shipping_method = $_LANG['Delivery'][$_REQUEST['deliveryMethod']]['text']."(Overseas)";
			}else{
				$shipping_method = $_LANG['Delivery'][$_REQUEST['deliveryMethod']]['text'];
			}
			$shipping_cost = $_REQUEST['shipping'];
		}else{
			$shipping_method = $_LANG['Delivery'][$_REQUEST['deliveryMethod']]['text'];
			$shipping_cost = $_REQUEST['shipping'];
		}
		
		$arrSetting['shipping_method'] = $shipping_method;
		$arrOrderRefSetting['shipping_method'] = $shipping_method;
		$arrSetting['shipping_cost'] = number_format($shipping_cost,2,'.','');
		$arrOrderRefSetting['shipping_cost'] = number_format($shipping_cost,2,'.','');
		
		$socObj->dbcon->update_record($socObj->table.'order_foodwine', $arrSetting, " WHERE OrderID='$OrderID'");
		$socObj->dbcon->update_record($socObj->table.'order_reviewref', $arrOrderRefSetting, " WHERE OrderID_foodwine='$OrderID'");
		$order->orderSendMail($OrderID);
		
		$socObj->payOrderByGoogle($OrderID);

		$cart->delCart();
		exit;
		break;
		
	case 'account':
	
		//ini_set('display_errors', '1');
		//error_reporting(E_ALL);
		
		$req = array(
			'OrderID' => $_REQUEST['OrderID'],
			'shipping' => $_REQUEST['shipping'],
			'amount' => number_format($_REQUEST['amount'],2,'.',''),
			'total_money' => $_REQUEST['total_money']
		);
		
		$state_query = "SELECT id, stateName as state, description FROM aus_soc_state ORDER BY description";
		$dbcon->execute_query($state_query);
		$state_list = $dbcon->fetch_records();
		
		$smarty->assign('state_list', $state_list);
		
		$fetch_user = "SELECT * FROM aus_soc_bu_detail WHERE StoreID = '".$_SESSION['ShopID']."'";
		$result = $dbcon->getOne($fetch_user);
		if (isset($result)) {
			$req['bu_address'] = $result['bu_address'];
			$req['bu_suburb'] = $result['bu_suburb'];
			$req['bu_state'] = $result['bu_state'];
			$req['bu_postcode'] = $result['bu_postcode'];
		}
		
		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty -> assign('is_website', 1);

		$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty->assign('headerInfo', $headerInfo['info']);
		$tmp_header = $smarty->fetch('../template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);

		$smarty -> assign('req', $req);
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Delivery Details', 4, $templateInfo['bgcolor']));
		$content =	$smarty->fetch('../payment_account.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		
		
		
		break;

	case 'credit':

		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		if (!$StoreID){
			echo "<script>alert('Please login first.');location.href='soc.php?cp=home'</script>";
			exit;
		}
		$message = $order->creditPayment();
		
		//var_dump($req);
		$req 	= array('aboutPage'=>"<br><div align=center>".$message);

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Payment', 4, $templateInfo['bgcolor']));
		$smarty -> assign('is_website', 1);

		//start shopper header
		$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty->assign('headerInfo', $headerInfo['info']);
		$tmp_header = $smarty->fetch('../template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header

		$smarty -> assign('req', $req);
		$content =	$smarty->fetch('../about.tpl');
		$smarty->assign('is_content',1);
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		break;
		
    default :    	
    	$cart_info = $cart->getGoods();
    	$smarty->assign('total_money', $cart_info['total_money']);
        //$smarty->assign('products', $products);
    	
        //old
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Payment');
		$smarty -> assign('keywords','Payment');
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		if (!$StoreID){
			echo "<script>alert('Please login first.');location.href='../soc.php?cp=home'</script>";
			exit;
		}
		$pid = $_REQUEST['pid'];
		$isbid = $_REQUEST['isbid'];
		$ref_id = $_REQUEST['refid'];

		$productlink = '../soc.php?cp=dispro&StoreID='.$StoreID.'&proid='.$pid;

		// send confirm email
		$arrPayment = array('check'=>'Check','mo'=>'Other','bank_transfer'=>'Bank Transfer','cash'=>'Cash', 'cod'=>'COD', 'cash_on_pickup'=>'Cash on Pickup', 'credit_card'=>'Credit Card', 'eftpos'=>'Eftpos');
		$arrSetting = array(
			'description'	=>	$arrPayment[$_REQUEST['payment']] ? $arrPayment[$_REQUEST['payment']] : 'Credit Card: ' . $_REQUEST['payment'],
			'total_money'	=> 	number_format($_REQUEST['total_money'],2,'.',''),	
		);
		
		
		$commission_money = round($_REQUEST['product_money'] * $adaptive->commission_rate, 2);
		$commission_money = $commission_money > $adaptive->commission_max_money ? $adaptive->commission_max_money : $commission_money;
		$arrOrderRefSetting = array(
			'description'	=>	$arrPayment[$_REQUEST['payment']] ? $arrPayment[$_REQUEST['payment']] : 'Credit Card: ' . $_REQUEST['payment'],
			'amount'		=> 	number_format($_REQUEST['total_money'],2,'.',''),	
			//'commission' 	=> 	$commission_money
		);
		if(isset($_REQUEST['dest'])){
			if($_REQUEST['overcountry']==1){
				/***/
				$shipping_method = $_LANG['Delivery'][$_REQUEST['deliveryMethod']]['text']."(Overseas)";
			}else{
				$shipping_method = $_LANG['Delivery'][$_REQUEST['deliveryMethod']]['text'];
			}
			$shipping_cost = $_REQUEST['shipping'];
		}else{
			$shipping_method = $_LANG['Delivery'][$_REQUEST['deliveryMethod']]['text'];
			$shipping_cost = $_REQUEST['shipping'];
		}
		$arrSetting['shipping_method'] = $shipping_method;
		$arrOrderRefSetting['shipping_method'] = $shipping_method;
		$arrSetting['shipping_cost'] = number_format($shipping_cost,2,'.','');
		$arrOrderRefSetting['shipping_cost'] = number_format($shipping_cost,2,'.','');

		$cart->delCart();
		
		if ($_REQUEST['payment']=='check' or $_REQUEST['payment']=='mo' or $_REQUEST['payment']=='cash' or $_REQUEST['payment']=='bank_transfer' or 'cod' == $_REQUEST['payment'] or 'cash_on_pickup' == $_REQUEST['payment'] or 'eftpos' == $_REQUEST['payment']){
			$arrPayment = array('check'=>'Check','mo'=>'Other','bank_transfer'=>'Bank Transfer','cash'=>'Cash', 'cod'=>'COD', 'cash_on_pickup'=>'Cash on Pickup', 'credit_card'=>'Credit Card', 'eftpos'=>'Eftpos');
			$arrSetting['status'] = 1;
			//Update Order Info	
			$socObj->dbcon->update_record($socObj->table.'order_foodwine', $arrSetting, " WHERE OrderID='$OrderID'");					
			$socObj->dbcon->update_record($socObj->table.'order_reviewref', $arrOrderRefSetting, " WHERE OrderID_foodwine='$OrderID'");					
			$order->orderSendMail($OrderID);
			
			$socstoreObj->updateCouponCode($_SESSION['couponInfo']['UserID'],$_SESSION['couponInfo']['StoreID'],$_SESSION['couponInfo']['pid'],$_SESSION['couponInfo']['coupon_code']);
			unset($_SESSION['couponInfo']);
			header("Location: ../soc.php?cp=message&StoreID=$StoreID&msg=Please contact this seller directly.".$link);
			exit;
		}
		
		//Update Order Info	
		$socObj->dbcon->update_record($socObj->table.'order_foodwine', $arrSetting, " WHERE OrderID='$OrderID'");
		
		// credit card payment method is removed, if add again, plz check the following code for auction
		$month	=	getExpMonth($_REQUEST['month']);
		$year	=	getExpYear($_REQUEST['year']);
		$req 	= array(
			'StoreID' => $_REQUEST['StoreID'],
			'OrderID' => $_REQUEST['OrderID'],
			'postage' => $_REQUEST['postage'],
			'shipping' => $_REQUEST['shipping'],
			'credit' => $_REQUEST['payment'],
			'OrderID' => $_REQUEST['OrderID'],
			'amount' => number_format($_REQUEST['amount'],2,'.',''),
			'payment' => $_REQUEST['payment'],
			'month' => $month,
			'year' => $year,
			'ref_id' => $ref_id,
			'is_foodwine'=>1
		);

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty -> assign('is_website', 1);

		//start shopper header
		$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty->assign('headerInfo', $headerInfo['info']);
		$tmp_header = $smarty->fetch('../template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header

		$smarty -> assign('req', $req);
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Payment', 4, $templateInfo['bgcolor']));
		$content =	$smarty->fetch('../payment_credit.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		break;
}
$smarty->assign('hide_race_banner',0);
?>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include('../indexPart.php');
$indexTemplate	=	'../index.tpl';
include('../include/class.cart.php');

//Check Login
if(($_SESSION['UserID'] == '' || empty($_SESSION['LOGIN'])) && $_REQUEST["cp"] != 'add'){
	header("Location:../soc.php?cp=login");
}

$cart = new Cart();
$cart_info = $cart->getGoods();

switch($_REQUEST["cp"]){

    //add product to basket
    case 'add':
        $pid = intval($_REQUEST['pid']);
        $qty = intval($_REQUEST['qty']);
        
        $status = $cart->add($pid, $qty);
        echo $status;
        exit();
        break;
     
    //empty basket
    case 'empty':
        $sn = $_POST['SN'];
        if (empty($sn) || $sn != 'a2e4822a98337283e39f7b60acf85ec9') {
        	echo "<script>alert('Please come from the currect url.');</script>";
			exit;
        }
        
        $status = $cart->delCart();
        echo $status;
        exit();
        break;
        
    //save basket changes
    //send order
    case 'save':
    case 'sendorder':    	
    	$quantity = $_POST['quantity'];
    	if (is_array($quantity)) {
    		foreach ($quantity as $key => $val) {
    			$cart->changeGoodsAmount($key, $val);
    		}
    	}
    	if ($_REQUEST["cp"] == 'save') {
    		header('Location: ' . SOC_HTTPS_HOST . 'foodwine/?act=basket&msg=Save successfully.');
    		exit();
    	} elseif ($_REQUEST["cp"] == 'sendorder') {
			$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');			
    		$lang = $GLOBALS['_LANG'];    		
	    	$product_list = $cart_info['can_buy_list'];
	    	
			if(!count($product_list)){
				echo "<script>alert('Your basket is empty.');location.href='../soc.php?cp=home';</script>";
				exit;
			}
    		
			if(!$socObj->checkwebsiteinvaild($StoreID)){
				echo "<script>alert('Website name does not exist.');location.href='../soc.php?cp=home';</script>";
				exit;
			}
			$_SESSION['StoreID'] = $StoreID;
	
			if (!$_SESSION['ShopID']){
				echo "<script>alert('Please login first.');location.href='../soc.php?cp=home'</script>";
				exit;
			}
			if(isset($_REQUEST['coupon_op'])&&isset($_REQUEST['coupon_code'])){
				$pid  = $_REQUEST['pid'];
				if($_REQUEST['coupon_op']=="add"){
					if($couponInfo = $socstoreObj->getCodeProduct($_SESSION['ShopID'],$StoreID,$pid,$_REQUEST['coupon_code'])){
						unset($_SESSION['couponInfo']);
						$_SESSION['couponInfo']['StoreID'] 	 		= $couponInfo['StoreID'];
						$_SESSION['couponInfo']['UserID']  	 		= $couponInfo['UserID'];
						$_SESSION['couponInfo']['pid']  	 		= $couponInfo['pid'];
						$_SESSION['couponInfo']['offer']  	 		= $couponInfo['offer'];
						$_SESSION['couponInfo']['quantity']  		= $couponInfo['quantity'];
						$_SESSION['couponInfo']['coupon_code']  	= $couponInfo['coupon_code'];
						$_SESSION['couponInfo']['postage']  		= $couponInfo['postage'];
						$_SESSION['couponInfo']['shipping_method']  = $couponInfo['shipping_method'];
						$_SESSION['couponInfo']['isoversea'] 		= $couponInfo['isoversea'];
						$_SESSION['couponInfo']['shipping']  		= $couponInfo['postage']*$couponInfo['quantity'];
					}else{
						unset($_SESSION['couponInfo']);
						$_SESSION['couponMsg'] = "Coupon code \"{$_REQUEST['coupon_code']}\" is not valid.";
					}
				}elseif ($_REQUEST['coupon_op']=="delete"){
					unset($_SESSION['couponInfo']);
				}
				header('Location: ../soc.php?cp=buy&StoreID='.$StoreID.'&pid='.$pid);
				exit();
			}
		
			if($_SESSION['couponMsg']){
				$smarty->assign('couponMsg',$_SESSION['couponMsg']);
				unset($_SESSION['couponMsg']);
			}
			$templateInfo = $socObj -> getTemplateInfo();
			$smarty -> assign('templateInfo', $templateInfo);
			$smarty -> assign('is_website', 1);
			//start shopper header
			$headerInfo = $socObj -> displayStoreWebside(true);			
        	$headerInfo['info']['attribute'] = 5;
			$smarty->assign('headerInfo', $headerInfo['info']);
			$tmp_header = $smarty->fetch('../template/tmp-header-include.tpl');
			$smarty->assign('tmp_header', $tmp_header);
			//end shopper header
	
			//Insert Order
			$reviewKey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
			$arrSetting = array(
				'buyer_id'			=> 	$_SESSION['ShopID'],
				'StoreID'			=> 	$StoreID,
				'reviewkey'			=> 	$reviewKey,
				'p_status'			=> 	'order',
				'description'		=>	'',
				'order_date' 		=>	time(),
				'type'				=> 	'purchasing',
				'product_money'		=> 	number_format($cart_info['total_money'],2,'.',''),
				'total_money'		=> 	number_format($cart_info['total_money'],2,'.',''),
				'month'				=> 	12,
		   		'shipping_method'  	=> 	'',
				'shipping_cost' 	=> 	0,	
				'status' 			=> 	0	
			);
					
			$socObj->dbcon->insert_record($socObj->table.'order_foodwine',$arrSetting);
			$ref_id = $socObj->dbcon->lastInsertId();
			$cart_info['OrderID'] = $ref_id;
			
			$arrOrderRefSetting = array(
				'buyer_id'			=> 	$_SESSION['ShopID'],
				'OrderID_foodwine'	=> 	$ref_id,
				'StoreID'			=> 	$StoreID,
				'reviewkey'			=> 	$reviewKey,
				'p_status'			=> 	'order',
				'description'		=>	'',
				'order_date' 		=>	time(),
				'type'				=> 	'purchasing',
				'amount'			=> 	number_format($cart_info['total_money'],2,'.',''),
				'price'				=> 	number_format($cart_info['total_money'],2,'.',''),
				'month'				=> 	1,
		   		'shipping_method'  	=> 	'',
				'shipping_cost' 	=> 	0,	
				'attribute' 		=> 	5	
			);			
			$socObj->dbcon->insert_record($socObj->table.'order_reviewref',$arrOrderRefSetting);
			
			foreach ($product_list as $product) {
				$arr_product_info = array(
					'OrderID'		=> 		$ref_id,
					'StoreID'		=>		$StoreID,
					'pid'			=> 		$product['pid'],			  
					'price'			=> 		$product['price'],			  
					'unit'			=> 		$product['unit'],			  
					'quantity'		=> 		$product['quantity'],			  
					'amount'		=> 		$product['amount'],			  
				);
				$socObj->dbcon->insert_record($socObj->table.'order_detail_foodwine', $arr_product_info);
			}
			//Delete the cart
			//$cart->delCart($cart_info['info']['id']);
			
			//Insert Order End
		
			$req 	= $foodWine->selectPayment();
			$req = array_merge($req, $cart_info);
			if(isset($_SESSION['couponInfo'])){
				$smarty->assign('couponInfo',$_SESSION['couponInfo']);
				$req['price'] 	= $_SESSION['couponInfo']['offer'];
				$req['postage'] = $_SESSION['couponInfo']['postage'];
				$req['total'] 	= $_SESSION['couponInfo']['quantity']*$_SESSION['couponInfo']['postage']+$_SESSION['couponInfo']['quantity']*$_SESSION['couponInfo']['offer'];
			}
			$req['price'] = number_format($req['total_money'],2,'.','');
			$req['product_money'] = number_format($req['total_money'],2,'.','');
			$req['total_money'] = number_format(($req['total_money'] + $req['postage']),2,'.','');
			$req['info']['bu_name'] = getStoreByName($StoreID);
			$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Send Order Information to '.$req['info']['bu_name'].'');
			//var_dump($req);
			$smarty->assign('itemTitle', $socObj->getTextItemTitle('Send Order Information', 4, $templateInfo['bgcolor']));
			
			
			$account_query = "SELECT * FROM aus_soc_account_list WHERE user_id = '".$_SESSION['ShopID']."' AND store_id = '".$StoreID."'";
			$account_result = $dbcon->getOne($account_query);
			
			if (isset($account_result)) {
				$smarty->assign('has_account', true);
			}
			
			$smarty -> assign('req', $req);
			$smarty -> assign('lang', $lang);
			$content =	$smarty->fetch('sendorder.tpl');
			$smarty -> assign('content', $content);
			$smarty->assign('div','list');
			$smarty->assign('sidebar',0);
			break;
    	}
    	exit();
	    break;

    case 'delete':
        
        $pid = $_GET['pid'];
        if ($pid) {
        	$cart->deleteGoodsById($pid);
        }
        header('Location: ' . SOC_HTTPS_HOST . 'foodwine/?act=basket&msg=Deleted successfully.');
        exit;
        break;
        
    default :
    	
    	$products = $cart_info['can_buy_list'];
    	$smarty->assign('total_money', $cart_info['total_money']);
        $smarty->assign('products', $products);
        
        $req	=	$socObj -> displayStoreWebside(true, false, $cart_info['info']['StoreID']);
        $req['info']['attribute'] = 5;
        $req = array_merge($req, $_REQUEST);
        $smarty->assign('req', $req);
        $smarty->assign('headerInfo', $req['info']);
        $tmp_header = $smarty->fetch('../template/tmp-header-include.tpl');
        $smarty->assign('tmp_header', $tmp_header);
        $smarty->assign('itemTitle', $socObj->getTextItemTitle('Your basket', 4, '3c3082', '', false));
        $smarty -> assign('sidebar', 0);
        $smarty->assign('pageTitle', 'Sell Goods Online - Selling Online - Your basket');
        
        $categories = $foodWine->getCategoryList($foodWineType);
        $smarty->assign('categories', $categories);
        $smarty -> assign('sidebar', 0);
		$smarty->assign('isstorepage',1);
        $smarty->assign('content', $smarty->fetch('../foodwine/basket_items.tpl'));
        break;
}
$smarty->assign('hide_race_banner',1);
$smarty->assign('show_join_banner', 1);
$smarty->assign('show_season_banner', 1);
?>

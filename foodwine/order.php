<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//ini_set('display_errors', '1');
//error_reporting(E_ALL);

include('../indexPart.php');
$indexTemplate	=	'../index.tpl';
include('../include/class.order.php');
include('../include/class.cart.php');

//Check Login
if($_SESSION['UserID'] == '' && $_SESSION['level'] != 1){
	header("Location:../soc.php?cp=login");
}

$order = new Order();
$isneworder = true;

switch($_REQUEST["cp"]){
	case 'orderdone':
		$OrderID = $_REQUEST['OrderID'];
		$order->updateReviewed($OrderID);
		
		$return_cp = ($_REQUEST['return_cp'] ? ('&cp='.$_REQUEST['return_cp']) : '');
		
		header("Location:/foodwine/?act=order".$return_cp);
		exit();
		break;
	case 'emailorder':
		
		$OrderID = $_REQUEST['OrderID'];
        $req = $order->getOrderInfo($OrderID);
        $store_info = $socObj->displayStoreWebside(true, '', $req['info']['StoreID']);
        $req = array_merge($req, $store_info);
        $smarty->assign('req', $req);
        $smarty -> assign('sidebar', 0);
        $smarty->assign('pageTitle', 'Sell Goods Online - Selling Online - Online Orders');
        $smarty->assign('content', $smarty->fetch('../foodwine/email_order.tpl'));
		break;
	case 'viewpastorder':
		$isneworder	= false;
    default :    	
    	$OrderID = $_REQUEST['OrderID']; 
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:''); 
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : $_GET['page'];
		if (!$page){
			$page = 1;
		}      
        $req = $socObj->getStoreInfo($StoreID);
        $req['OrderID'] = $OrderID;
        $req['StoreID'] = $StoreID;
    	$req['order_lists'] = $order->getOrderList($_SESSION['StoreID'], $isneworder, $OrderID, $page);
        $req = array_merge($req, $_REQUEST);
        $smarty->assign('req', $req);
        $smarty -> assign('sidebar', 0);
        $smarty->assign('pageTitle', 'Sell Goods Online - Selling Online - Online Orders');
        $smarty->assign('content', $smarty->fetch('../foodwine/order.tpl'));
        break;
}
?>

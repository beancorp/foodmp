<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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
	case 'delete':
		$bid = $_REQUEST['bid'];
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		$res = $foodWine->deleteBookRequest($bid, $StoreID);
		$msg = $res ? 'Deleted successfully.' : 'Deleted failed.';
		
		header('Location: ' . SOC_HTTPS_HOST . 'foodwine/?act=book&cp=viewpastorder&msg='.$msg);
		break;
	case 'viewpastorder':
		$isneworder	= false;
    default :    	
    	$id = $_REQUEST['id']; 
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:''); 
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : $_GET['page'];
		if (!$page){
			$page = 1;
		}      
		
        $req = $socObj->getStoreInfo($StoreID);
        $req['id'] = $id;
        $req['StoreID'] = $StoreID;
    	$req['book_lists'] = $foodWine->getBookOnlineList($_SESSION['StoreID'], $isneworder, $page);
    	
		if ($isneworder && $id) {
			$sql = "UPDATE {$table}book SET status=1 WHERE id='$id' AND StoreID='$StoreID'";
			$dbcon->execute_query($sql); 
		}
        $req = array_merge($req, $_REQUEST);
        $smarty->assign('req', $req);
        $smarty -> assign('sidebar', 0);
        $smarty->assign('pageTitle', 'Sell Goods Online - Selling Online - Online Book');
        $smarty->assign('content', $smarty->fetch('../foodwine/book.tpl'));
        break;
}
?>

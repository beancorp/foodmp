<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include('../indexPart.php');
$indexTemplate	=	'../index.tpl';
include_once(SOC_INCLUDE_PATH.'/class.cart.php');
include_once(SOC_INCLUDE_PATH.'/class.FoodWine.php');

//Check Login
if($_SESSION['UserID'] == '' && $_SESSION['level'] != 1){
	header("Location:/soc.php?cp=login");
}

$StoreID = $_SESSION['StoreID'];
$foodWine = new FoodWine();
switch($_REQUEST["cp"]){
		
	default:
        $req = $socObj -> displayStoreWebside(false,true);
		
		if (isset($_GET['subAttrib'])) {
			$req['info']['subAttrib'] = $_GET['subAttrib'];
		}
		
		$position_logo = '';
		$position_url = '';
		$logo_width = '170px';
		
		switch ($req['info']['subAttrib']) {
			case 1:
				$position_logo = 'right: 20px; top: 20px;';
				$position_url = 'top: 230px; left: 70px;';
				break;
			case 2:
				$position_logo = 'left: 20px; bottom: 20px;';
				$position_url = 'top: 220px; left: 120px;';
				break;
			case 3:
				$position_logo = 'right: 40px; top: 20px;';
				$position_url = 'top: 230px; left: 120px;';
				break;
			case 4:
				$position_logo = 'right: 40px; bottom: 20px;';
				$position_url = 'top: 305px; left: 55px;';
				break;
			case 5:
				$position_logo = 'right: 40px; bottom: 20px;';
				$position_url = 'top: 305px; left: 55px;';
				break;
			case 6:
				$position_logo = 'right: 20px; top: 20px;';
				//$position_url = 'top: 180px; left: 305px;';
				$position_url = 'left: 85px; top: 275px; width: 550px; text-align: center;';
				break;
			case 7:
				$position_logo = 'right: 10px; bottom: 40px;';
				//$position_url = 'top: 115px; left: 310px;';
				$position_url = 'top: 225px; left: 85px; width: 550px; text-align: center;';
				$logo_width = '125px';
				break;
			case 8:
				$position_logo = 'left: 275px; bottom: 40px;';
				$position_url = 'top: 185px; left: 215px;';
				break;
			case 9:
				$position_logo = 'right: 40px; top: 20px;';
				$position_url = 'top: 255px; left: 170px;';
				break;
			case 10:
				$position_logo = 'left: 20px; bottom: 20px;';
				$position_url = 'top: 210px; left: 55px;';
				break;
		}
		
		if (isset($_GET['subAttrib'])) {
			$smarty->assign('subAttrib', $_GET['subAttrib']);
		}
		$smarty->assign('logo_width', $logo_width);
		$smarty->assign('position_logo', $position_logo);
		$smarty->assign('position_url', $position_url);
		
        $req['store_url'] = str_replace('http://', '', SOC_HTTP_HOST).$req['info']['bu_urlstring'];
    	$smarty->assign('req', $req);
		$pageTitle = 'Flyers';
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Flyers');
		$smarty -> assign('keywords','Flyers');
    	$content = $smarty->fetch("flyers.tpl");    	
		$smarty->assign('content', $content);
		$smarty->assign('sidebar',0);
		
		break;		
}
?>

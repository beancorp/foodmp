<?php
// change this when go live

$site_path=dirname(dirname(__FILE__));     //new live site path
require_once $site_path.'/include/maininc.php';
require_once $site_path.'/include/smartyconfig.php';
require_once $site_path.'/include/class/common.php';
require_once $site_path.'/include/class.soc.php';
require_once $site_path.'/include/class.socbid.php';
require_once $site_path.'/include/functions.php';

$socbidObj = new socbidClass();
$currentStamp = time();
global $table;
$dbcon->execute_query("update {$table}product_auction set status=2 "
					."where end_stamp < $currentStamp and status!=2");

$sql =  "select au.*,p.deleted from ".$table."product_auction as au LEFT JOIN {$table}product as p ON (au.pid=p.pid) ".
		"where end_stamp < $currentStamp and finish=0 and (winner_id=0 or cur_price < reserve_price) AND p.deleted<>'YES'";
$dbcon->execute_query($sql);
$row = $dbcon->fetch_records();
//echo $sql . '<br/>';
//echo '<pre>';var_dump($row);exit;
if ($row){
	foreach ($row as $val){
		$StoreID = $socbidObj->getStoreID($val['pid']);
		$userinfo = $socbidObj->getUser($StoreID);
		$_query = "SELECT item_name FROM ". $table . "product WHERE pid='".$val['pid']."' ";
		$dbcon->execute_query($_query);
		$arrTemp=	$dbcon->fetch_records(true);
		$itemname = !empty( $arrTemp[0]['item_name'] ) ? $arrTemp[0]['item_name'] : "";
		$detail = array(
			'mailtoname'	=> $userinfo['bu_nickname'],
			'item_name'		=> $itemname
		);
		$mailtoseller = $socbidObj->getEmailAccount($StoreID);
		$socbidObj->sendMail('sellernotsold', $mailtoseller, $detail);
		$dbcon->execute_query("update {$table}product_auction set finish=1 where pid=".$val['pid']);
	}
}

$sql =  "select au.*, p.deleted from ".$table."product_auction as au LEFT JOIN {$table}product as p ON (p.pid=au.pid) ".
		"where end_stamp < $currentStamp and finish=0 and winner_id!=0 and cur_price >= reserve_price AND p.deleted<>'YES'";
$dbcon->execute_query($sql);
$row = $dbcon->fetch_records();

//echo $sql;
//var_dump($row);
if ($row){
	foreach ($row as $val){
		// sendmail to winner, loser and seller
		$socbidObj->socSendMail($val['winner_id'],$val['pid']);
		//var_dump($val);
		//exit;
		// update the status of auction
		$dbcon->execute_query("update {$table}product_auction set `finish`=1 where `pid`=".$val['pid']);
		// generate a order record for auction
		if(intval($val['cur_price']*100)>=intval($val['reserve_price']*100)){
			$dbcon->execute_query("select item_name,p_code from ".$table."product where pid=".$val['pid']);
			$productInfo = $dbcon->fetch_records();
			$productInfo = $productInfo[0];
			$orderInfo = array(
				'pid'		=> $val['pid'],
				'buyer_id'	=> $val['winner_id'],
				'StoreID'	=> $socbidObj->getStoreID($val['pid']),
				'order_date'=> time(),
				'type'		=> 'bid',
				'p_status'	=> 'Pending',
				'amount'	=> number_format($val['cur_price'],2,'.',''),
				'month'		=> 1,
				'product_code'=> $productInfo['p_code'],
				'price'		=> $val['cur_price'],
				'item_name'	=> $productInfo['item_name']
			);
			$dbcon->insert_query($table.'order_reviewref',$orderInfo);
		}
	}
}
?>

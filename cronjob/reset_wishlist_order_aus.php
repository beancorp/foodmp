#!/usr/bin/php
<?php

/*
 *  AUS LIVE
 */
$site_path=dirname(dirname(__FILE__));     //new live site path
include_once $site_path."/include/config.php" ;
include_once $site_path."/include/maininc.php" ;
include_once $site_path."/include/functions.php";

/*
 *  END AUS LIVE
 */

/*
 *      Test AUS
 */
//include_once "/data0/www/buyblitz/aus_soc_multi_seller/include/config.php";
//include_once "/data0/www/buyblitz/aus_soc_multi_seller/include/maininc.php";
//include_once "/data0/www/buyblitz/aus_soc_multi_seller/include/functions.php";

/*
 *   END Test AUS
 */
?>
<?php
	$nowtime = time()-1800;
	$query = "SELECT * FROM {$table}wishlist_order where order_date<$nowtime and p_status !='paid' and p_status !='cancel' and paid_method='paypal' ";
	$dbcon->execute_query($query);
	$result = $dbcon->fetch_records($dbcon);
	$i = 0;
	if($result){
		foreach ($result as $pass){
			$dbcon->beginTrans();
				$issuf = false;
				$queryupdate = "update {$table}wishlist SET gifted=gifted-{$pass['amount']} WHERE pid='{$pass['pid']}'";
				if($dbcon->execute_query($queryupdate)){
					$queryorder = "update {$table}wishlist_order SET p_status='cancel' WHERE OrderID='{$pass['OrderID']}'";
					if($dbcon->execute_query($queryorder)){
						$issuf = true;$i++;
					}
				}
			if($issuf){
				$dbcon->commitTrans();
				$dbcon->endTrans();
			}else{
				$dbcon->rollbackTrans();
				$dbcon->endTrans();	
			}
		}
	}
	echo $i." records are canceled.";
?>
#!/usr/bin/php
<?php
include_once "./include/config.php" ;
include_once "./include/maininc.php" ;
include_once "./include/functions.php" ;
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
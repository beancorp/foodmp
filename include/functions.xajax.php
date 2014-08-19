<?php

/**
 * $Id: functions.xajax.php jacky.zhou <at> Sat May 24 23:47:54 CST 2008 23:47:54
 * 
 * functions to process the xajax request
 * 
 * @author jacky.zhou
 * @package buyblitz
 * @subpackage include
 */

function verifyBid($bidform) {

	require_once(SOC_INCLUDE_PATH.'/xajax/xajax_core/xajax.inc.php');
	$objResponse = new xajaxResponse();

	if (!empty($_SESSION['StoreID'])) {


		//convert formvars
		$pid = isset($bidform['pid']) ? $bidform['pid'] : 0;
		$maxBid = isset($bidform['maxBid']) ? $bidform['maxBid'] : 0;
		$manualBid = isset($bidform['manualBid']) ? $bidform['manualBid'] : 0;

		$dbcon = &$GLOBALS['dbcon'];
		$table_prefix = &$GLOBALS['table'];

		$_query = "SELECT price FROM " . $table_prefix . "product WHERE pid =" . $pid;
		$dbcon -> execute_query($_query);
		$arrTemp=	$dbcon->fetch_records(true);
		$currentBid = $realprice = !empty($arrTemp[0]['price']) ? $arrTemp[0]['price'] : 0;

		//get initial price
		$_query = "SELECT initial_price FROM " . $table_prefix . "product_auction WHERE pid = " . $pid;
		$dbcon -> execute_query($_query);
		$arrTemp=	$dbcon->fetch_records(true);
		$initial_price = !empty($arrTemp[0]['initial_price']) ? $arrTemp[0]['initial_price'] : 0;

		//get maxbid
		$_query = "SELECT price, is_auto FROM " . $table_prefix . "product_bid WHERE pid = $pid AND price IN " .
		"( SELECT max(price) FROM " . $table_prefix . "product_bid WHERE pid= " .$pid . " )";
		$dbcon -> execute_query($_query);
		$arrTemp=	$dbcon->fetch_records(true);
		$maxBidPrice = !empty($arrTemp[0]['price']) ? $arrTemp[0]['price'] : 0;
		$maxBidIsAuto = !empty($arrTemp[0]['is_auto']) ? $arrTemp[0]['is_auto'] : 'no';

		if ($currentBid == 0) {
			$currentBid = $initial_price;
		}

		if (( $maxBid == 0 ) && ( $manualBid == 0 )) {
			$text = 'No bid, you can choose one bid type. maxbid for auto-bidding.';
			$objResponse->script('alert("'.$text.'");');
		} elseif (( $maxBid != 0 ) && ( $manualBid != 0 )) {
			$text = 'Bid can\'t be accepted, you can choose one bid type. maxbid for auto-bidding.';
			$objResponse->script('alert("'.$text.'");');
		} else {

			$bidprice = ($maxBid != 0) ? $maxBid : $manualBid;

			if ($maxBidIsAuto == 'yes') {
				if ($maxBid != 0) {
					if ($bidprice < $maxBidPrice) {
						$text = 'your bid must bigger than max bid price:$'.$maxBidPrice;
						$objResponse->script('alert("'.$text.'");');
					} else {
						if (($bidprice == $maxBidPrice) && ($realprice != 0)) {
							$text = 'your bid must bigger than max bid price:$'.$maxBidPrice;
							$objResponse->script('alert("'.$text.'");');
						} else {
							$objResponse->script('document.bidform.submit();');
						}
					}
				} else {
					if ($bidprice < $currentBid) {
						$text = 'Your bid must bigger than current bid: $'.$currentBid;
						$objResponse->script('alert("'.$text.'");');
					} elseif ($bidprice == $currentBid) {
						if ($realprice != 0) {
							$text = 'Your bid must bigger than current bid: $'.$currentBid;
							$objResponse->script('alert("'.$text.'");');							
						}
					} else {
						$objResponse->script('document.bidform.submit();');
					}
				}
			} else {
				if ($bidprice < $currentBid) {
					$text = 'Your bid must bigger than current bid: $'.$currentBid;
					$objResponse->script('alert("'.$text.'");');
				} else {
					if (($bidprice == $maxBidPrice) && ($realprice != 0)) {
						$text = 'Your bid must bigger than max bid price: $'.$maxBidPrice;
						$objResponse->script('alert("'.$text.'");');
					} else {
						$objResponse->script('document.bidform.submit();');
					}
				}
			}
		}
	} else {
		$text = 'Login please.';
		$objResponse->script('alert("'.$text.'");');
	}

	return $objResponse;
}
?>
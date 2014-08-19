<?php
@session_start();

include_once ('../include/config.php');
include_once ('../include/smartyconfig.php');
include_once ('functions.inc.php');
include_once ('maininc.php');
include_once ('class.soc.php');

include_once('../languages/'.LANGCODE.'/soc.php');
include_once('../languages/'.LANGCODE.'/foodwine/index.php');

if (isset($_GET['query'])) {
	
	//subAttrib : category
	
	$retailer_sql = "SELECT detail.StoreID AS store_id, detail.bu_name AS retailer_name, promo_store_codes.code AS code , detail.bu_state AS bu_state, detail.subAttrib AS category_id
						FROM aus_soc_bu_detail AS detail
						LEFT JOIN promo_store_codes ON detail.StoreID = promo_store_codes.store_id
						
					WHERE detail.CustomerType = 'seller'
					AND detail.status = 1
					AND detail.bu_name LIKE '%".$_GET['query']."%' 
					ORDER BY detail.bu_name ASC LIMIT 50;";

	$dbcon->execute_query($retailer_sql);
	$store_result = $dbcon->fetch_records(true);
	
	$result = array();
	$result['suggestions'] = array();
	
	if (is_array($store_result)) {
		foreach($store_result as $store) {
			$result['suggestions'][] = array('value' => ucwords($store['retailer_name']), 
												'data' => $store['store_id'],
												'code'=>$store["code"], 
												'state_id' => $store["bu_state"], 
												'category_id' => $store['category_id']);
		}
	}
	
	echo json_encode($result);
}

?>
<?php
include_once ('include/config.php');
@session_start();
include_once('include/smartyconfig.php');

include_once('maininc.php');
include_once('class.soc.php');
include_once('functions.php');
require_once('class.socstore.php');

$socObj = new socClass();
$socstoreObj = new socstoreClass();

$response = array();

function check_fan($store_id, $consumer_id) {
	global $dbcon;
	$count_query = "SELECT COUNT(fan_id) As fan_count FROM aus_soc_fans WHERE store_id = '".$store_id."' AND consumer_id = '".$consumer_id."'";
	$count_result = $dbcon->getOne($count_query);
	return (isset($count_result['fan_count']) && ($count_result['fan_count'] > 0));
}

function get_counter($store_id) {
	global $dbcon;
	$count_query = "SELECT COUNT(fan_id) As fan_count FROM aus_soc_fans WHERE store_id = '".$store_id."'";
	$count_result = $dbcon->getOne($count_query);
	$count = (isset($count_result['fan_count']) ? number_format($count_result['fan_count']) : 0);
	return $count;
}

function add_subscriber($store_id, $consumer_id) {
	global $dbcon;
	
	$consumer_query = "SELECT detail.bu_email, login.id As userid FROM aus_soc_bu_detail detail INNER JOIN aus_soc_login login ON login.StoreID = detail.StoreID WHERE detail.StoreID = '".$consumer_id."'";
	$consumer_result = $dbcon->getOne($consumer_query);
	
	if (!empty($consumer_result['bu_email'])) {
		$insert_query = "INSERT INTO aus_soc_emailalert (storeid, email, userid, subscribe_date) VALUES (".$store_id.", '".$consumer_result['bu_email']."', ".$consumer_result['userid'].", '".time()."');";
		$dbcon->execute_query($insert_query);
	}
}

function remove_subscriber($store_id, $consumer_id) {
	global $dbcon;
	
	$consumer_query = "SELECT login.id As userid FROM aus_soc_bu_detail detail INNER JOIN aus_soc_login login ON login.StoreID = detail.StoreID WHERE detail.StoreID = '".$consumer_id."'";
	$consumer_result = $dbcon->getOne($consumer_query);
	
	if (!empty($consumer_result['userid'])) {
		$delete_query = "DELETE FROM aus_soc_emailalert WHERE storeid = '".$store_id."' AND userid = '".$consumer_result['userid']."'";
		$dbcon->execute_query($delete_query);
	}
}

if (isset($_POST['get_counter'])) {
	$response['fan_counter'] = get_counter($_POST['get_counter']);
	
	if (isset($_SESSION['StoreID'])) {
		$response['are_you'] = check_fan($_POST['get_counter'], $_SESSION['StoreID']);
	} else {
		$response['are_you'] = false;
	}
	
	echo json_encode($response);
	exit;
}

if (isset($_POST['store_id'])) {
	if (isset($_SESSION['StoreID'])) {
		if (!check_fan($_POST['store_id'], $_SESSION['StoreID'])) {
			$response['error'] = false;
			$response['consumer_id'] = $_SESSION['StoreID'];
			$response['store_id'] = $_POST['store_id'];
			$response['message'] = '';
			
			$dbcon->execute_query("INSERT INTO aus_soc_fans (store_id, consumer_id) VALUES (".$response['store_id'].", ".$response['consumer_id'].");");
			
			add_subscriber($response['store_id'], $response['consumer_id']);
			
			$response['fan_counter'] = get_counter($response['store_id']);
			$response['are_you'] = true;
			
		} else {
			$dbcon->execute_query("DELETE FROM aus_soc_fans WHERE store_id = '".$_POST['store_id']."' AND consumer_id = '".$_SESSION['StoreID']."';");
			remove_subscriber($_POST['store_id'], $_SESSION['StoreID']);
			$response['fan_counter'] = get_counter($_POST['store_id']);
			$response['error'] = false;
			$response['are_you'] = false;
		}
	} else {
		$response['error'] = true;
		$response['message'] = 'Must be logged in';
		$response['are_you'] = false;
	}
} else {
	$response['error'] = true;
	$response['message'] = 'No store id sent';
}



echo json_encode($response);

?>

<?php
include_once ('include/config.php');
@session_start();
include_once('include/smartyconfig.php');
include_once('maininc.php');
include_once('functions.php');

if (isset($_GET['id'])) {
	$fetch_user = "SELECT * FROM aus_soc_bu_detail WHERE StoreID = '".$_GET['id']."'";
	$dbcon->execute_query($fetch_user);
	$user_result = $dbcon->fetch_records();
	
	if (isset($user_result[0])) {
		$user_data = $user_result[0];
		if ($user_data['status'] == 1) {
			if (empty($user_data['bu_urlstring']) && (!empty($user_data['bu_website']))) {
				setCounter($user_data['StoreID'], 0);
				header('Location:'.$user_data['bu_website']);
			}		
		}
	}
}
?>
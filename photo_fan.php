<?php
include_once ('include/config.php');
@session_start();
include_once('include/smartyconfig.php');

include_once('maininc.php');
include_once('class.soc.php');
include_once('functions.php');
require_once('class.socstore.php');

include_once ('fanpromo/functions.inc.php');

$socObj = new socClass();
$socstoreObj = new socstoreClass();

$response = array();

function check_fan($photo_id, $consumer_id) {
	global $dbcon;
	$count_query = "SELECT COUNT(fan_id) As fan_count FROM photo_promo_fan WHERE photo_id = '".$photo_id."' AND consumer_id = '".$consumer_id."'";
	$count_result = $dbcon->getOne($count_query);
	return (isset($count_result['fan_count']) && ($count_result['fan_count'] > 0));
}

function get_counter($photo_id) {
	global $dbcon;
	$count_query = "SELECT COUNT(fan_id) As fan_count FROM photo_promo_fan WHERE photo_id = '".$photo_id."'";
	$count_result = $dbcon->getOne($count_query);
	$count = (isset($count_result['fan_count']) ? number_format($count_result['fan_count']) : 0);
	return $count;
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

if (isset($_POST['photo_id'])) {
	
	$vote_enabled = false;
	
	//if not in vote time period, can not vote
	$grand_date_open = tab_content_by_key_name($dbcon, "grand-date-open"); //grand_vote start
	$grand_time_open = strtotime($grand_date_open);
	$grand_date_close = tab_content_by_key_name($dbcon, "grand-date-close"); //grand_vote close
	$grand_time_close = strtotime("23:59:59 ". $grand_date_close);
	$vote_on_off = tab_content_by_key_name($dbcon, "vote-on-off");
	$grand_trigger = tab_content_by_key_name($dbcon, "grand-trigger");   //vote trigger
	
	$grand_final_date = tab_content_by_key_name($dbcon, "grand-final-date");   //grand-final-date
	$grand_final_time= strtotime($grand_final_date);
	
	
	$current_time = time();

	$photo = getFranzyPhoto($dbcon, $_POST['photo_id']);
	
	if ($current_time <= $grand_final_time && $vote_on_off == 1 && $photo["grand_final"] <> 1){
			$vote_enabled = true;	
	}
	
	if ($current_time >= $grand_time_open && $current_time <= $grand_time_close && $vote_on_off == 1 && $photo["grand_final"] == 1 && $grand_trigger == 1){
			$vote_enabled = true;	
	}
	
	
	
	if (!$vote_enabled){
		$response['error'] = true;
		$response['message'] = 'Not time for vote';
	}else{
		if (isset($_SESSION['StoreID'])) {
			if (!check_fan($_POST['photo_id'], $_SESSION['StoreID'])) {
				$response['error'] = false;
				$response['consumer_id'] = $_SESSION['StoreID'];
				$response['photo_id'] = $_POST['photo_id'];
				$response['message'] = '';
				
				$dbcon->execute_query("INSERT INTO photo_promo_fan (photo_id, consumer_id) VALUES (".$response['photo_id'].", ".$response['consumer_id'].");");
				
				//if bonus time (double vote)
				$bonus_date_on_of  = tab_content_by_key_name($dbcon, "turn-on-bonus-date");
				$bonus_deadline_date = tab_content_by_key_name($dbcon, "bonus-date-deadline");
				$bonus_deadline_time = strtotime("23:59:59 ". $bonus_deadline_date);
				if(time() <= $bonus_deadline_time && $bonus_date_on_of == 1){
					$dbcon->execute_query("INSERT INTO photo_promo_fan (photo_id, consumer_id) VALUES (".$response['photo_id'].", ".$response['consumer_id'].");");
				}
				
				$response['fan_counter'] = get_counter($response['photo_id']);
				$response['are_you'] = true;
				
			} else {
				$dbcon->execute_query("DELETE FROM photo_promo_fan WHERE photo_id = '".$_POST['photo_id']."' AND consumer_id = '".$_SESSION['StoreID']."';");
				
				$response['fan_counter'] = get_counter($_POST['photo_id']);
				$response['error'] = false;
				$response['are_you'] = false;
			}
		} else {
			$response['error'] = true;
			$response['message'] = 'Must be logged in';
			$response['are_you'] = false;
		}
	}
} else {
	$response['error'] = true;
	$response['message'] = 'No store id sent';
}



echo json_encode($response);

?>

<?php
@session_start();
include_once('../include/smartyconfig.php');
include_once('class.login.php');
include_once('maininc.php');
include_once('config.php');
include_once('functions.php');
include_once('class.soc.php');

include_once('../fanpromo/functions.inc.php');

$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}

/*
ini_set('display_errors', 1);
error_reporting(E_ALL);
*/


if (isset($_POST['content_page']) && isset($_POST['text_id'])) {
	$content_page = htmlentities($_POST['content_page']);
	
	$guide = $_POST['guide'];
	
	
	$error = false;
	
	if ($_POST['key_name'] == "grand-date-open"){
		//get finale date
		$final_date = tab_content_by_key_name($dbcon, "grand-final-date");
		$final_time = strtotime($final_date);
		$grand_vote_open_time = strtotime($content_page);		
		
		if ($grand_vote_open_time < $final_time){
            if(LANGCODE == 'en-au'){
                $rs = array('message' => 'Upate Fail: Grand Vote Date Start must be later or equal Grand Final Date'); 
            }else{
                $rs = array('message' => 'Upate Fail: Grand Vote Date Start must be later or equal Fan Frenzy Final Date'); 
            }
			$error = true;		
		}
	}
	
	
	
	if ($_POST['key_name'] == "grand-date-close"){
		//get finale date
		$grand_vote_date_open = tab_content_by_key_name($dbcon, "grand-date-open");
		$grand_vote_time_open = strtotime($grand_vote_date_open);
		$grand_vote_time_close = strtotime("23:59:59 ".$content_page);
		
		if ($grand_vote_time_close <= $grand_vote_time_open){
			$rs = array('message' => 'Upate Fail: Grand Vote Date Close must be later or equal Grand Vote Date Close');
			$error = true;	
		}
	}
	
	if (!$error){
		$text_sql = "UPDATE photo_promo_text SET text_content = \"".$content_page."\" , guide=\"".$guide."\" WHERE text_id = '".$_POST['text_id']."' LIMIT 1";
		$dbcon->execute_query($text_sql);
	
		if ($_POST['key_name'] == "grand-trigger" && $content_page == 1){
			$sql = "TRUNCATE TABLE photo_promo_fan";
			$dbcon->execute_query($sql);
			$rs = array('message' => 'The content has been updated. All votes are reset to 0');
		}else{
			$rs = array('message' => 'The content has been updated.');
		}
	}
	
	echo json_encode($rs);
	exit;
}

if (isset($_POST['text_id'])) {
	
	$text_sql = "SELECT * FROM photo_promo_text WHERE text_id = '".$_POST['text_id']."' LIMIT 1";
	$text_result = $dbcon->getOne($text_sql);
	
	//$text_result['text_content']
	
	echo json_encode(array('content' => html_entity_decode($text_result['text_content']), 
							"type_var" => $text_result['type_var'], 
							"key_name" => $text_result['key_name'],
							"guide" => $text_result['guide'],
					));
	exit;
}


	$text_sql = "SELECT text_id, text_name,text_content  FROM photo_promo_text ORDER BY text_name ASC";

	$dbcon->execute_query($text_sql);
	$text_result = $dbcon->fetch_records(true);
	
	$select_options = '';
	if (is_array($text_result)) {
		foreach($text_result as $text) {
			$select_options .= '<option value="'.$text['text_id'].'">'.$text['text_name'].'</option>';
		}
	}
	
	
$smarty->assign('text_result', $text_result);	
$smarty->assign('select_options', $select_options);
$content = $smarty->fetch('admin_fanfrenzycontent.tpl');
$smarty->assign('content', $content);
$smarty->display('index.tpl');
unset($smarty);
exit;
?>
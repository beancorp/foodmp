<?php
ini_set('display_errors', 0);
@session_start();

include_once ('../include/config.php');
include_once ('../include/smartyconfig.php');
include_once ('functions.inc.php');
include_once ('maininc.php');
include_once ('class.soc.php');

include_once('../languages/'.LANGCODE.'/soc.php');
include_once('../languages/'.LANGCODE.'/foodwine/index.php');


if($_POST["action"] == "autoFill"){
	$code = $_POST["code"];
	$retailer_code_sql = "SELECT detail.StoreID As store_id, 
								detail.bu_name As retailer_name, codes.code AS code, 
								detail.bu_state AS bu_state,							 
								detail.subAttrib AS category_id
	
	FROM promo_store_codes As codes
	LEFT JOIN aus_soc_bu_detail As detail ON detail.StoreID = codes.store_id
	WHERE codes.code = '{$code}' LIMIT 0,1";
	
	$row = $dbcon->getOne($retailer_code_sql);
	if ($row){
		$result = array('retailer_name' => ucwords($row['retailer_name']), 
											'store_id' => $row['store_id'],
											'state_id' => $row["bu_state"], 
											'category_id' => $row['category_id']);
	}else{
		
	}
	echo json_encode($result);
	exit();
	
	
	
	
	
}



if($_POST["action"] == "checkCode"){
	$code = $_POST["code"];
	$retailer_id = $_POST["retailer_id"];
	$photo_id = $_POST["photo_id"];	
	
	$result["rs"] = true;
	
	if ($code){
		$code_info = getStoreIdByCode($dbcon, $_POST["code"]);
		if (!$code_info["code_id"]){
				$result["rs"] = false;
				$result["message"] = "code doesn't exist";
				$result["error_code"] = "code";
		}
		
		//if edit photo
		
		if ($result["rs"]){
			$past_photo = getPhotoByUserAndRetailer($dbcon, $_SESSION['StoreID'], $code_info["store_id"]);
			
			if ($photo_id >0 && $past_photo["photo_id"] >0  && $past_photo["photo_id"] != $photo_id){   //edit
				$result["rs"] = false;
				$result["message"] = "Only one entry per retailer";
				$result["error_code"] = "code";
			}
		
			if (!$photo_id && $past_photo["photo_id"] >0){   //create new
				$result["rs"] = false;
				$result["message"] = "Only one entry per retailer";
				$result["error_code"] = "code";
			}
			
			//dupicate code, mail to admin. 
			if ($result["message"] = "Only one entry per retailer"){
					$mail_param = array();
					$mail_param["mail_to"] = "demiswift99@gmail.com"; //mail admin
					$mail_param["subject"] = "User {$_SESSION["NickName"]} is trying to use duplicate code at " . date("H:i:s Y/m/d");
					$email_template =  get_email_template_by_key_name($dbcon, "email_admin_duplicate_code");
					$email_template["content"] = file_get_contents(ROOT_DIRECTORY. "skin/email_template/template_mail_{$email_template["id"]}.txt"); //get content from txt, not DB data
					$message = str_replace("##username##", nl2br($_SESSION["NickName"]), $email_template["content"]); //Replace
					
					
					
					
					
					$message = str_replace("##code##", nl2br($code), $message); //Replace
					$message = str_replace("##time##", date("H:i:s Y/m/d"), $message); //Replace
					$mail_param["content"] = $message;
					insert_queue_mail($dbcon, $mail_param);
			}
		}
	}
	
	echo json_encode($result);
	exit();
}


if (isset($_GET['query'])) {
	/*
	$retailer_sql = "SELECT detail.StoreID As store_id, detail.bu_name As retailer_name FROM aus_soc_bu_detail AS detail 
					INNER JOIN aus_soc_login as lg on lg.StoreID = detail.StoreID 
					WHERE detail.CustomerType = 'seller' AND detail.attribute = '5' 
					AND NOT(detail.bu_name IS NULL) 
					AND detail.renewalDate >= UNIX_TIMESTAMP(NOW())
					AND detail.status = 1 
					AND detail.bu_name LIKE '%".$_GET['query']."%' 
					ORDER BY detail.bu_name ASC;";

	$dbcon->execute_query($retailer_sql);
	$store_result = $dbcon->fetch_records(true);
	
	$result = array();
	$result['suggestions'] = array();
	
	if (is_array($store_result)) {
		foreach($store_result as $store) {
			$result['suggestions'][] = array('value' => ucwords($store['retailer_name']), 'data' => $store['store_id']);
		}
	}
	*/
	
	
	
	
	
	$retailer_code_sql = "SELECT detail.StoreID As store_id, 
								detail.bu_name As retailer_name, codes.code AS code, 
								detail.bu_state AS bu_state,							 
								detail.subAttrib AS category_id
	
	FROM promo_store_codes As codes
	LEFT JOIN aus_soc_bu_detail As detail ON detail.StoreID = codes.store_id
	WHERE codes.code LIKE '%".$_GET['query']."%' ORDER BY detail.bu_name ASC;";
	
	$dbcon->execute_query($retailer_code_sql);
	$store_result2 = $dbcon->fetch_records(true);
	if (is_array($store_result2)) {
		foreach($store_result2 as $store2) {
			$result['suggestions'][] = array('value' => ucwords($store2['retailer_name']), 
											'data' => $store2['store_id'], 
											'code' => $store2['code'],			
											'state_id' => $store2["bu_state"], 
											'category_id' => $store2['category_id']);
			
		}
	}else{
		/*
		$result['suggestions'][] = array('value' => "", 
											'data' => "", 
											'code' => "",			
											'state_id' => "", 
											'category_id' => "");
		*/
	}
	
	
	
	
	echo json_encode($result);
	exit();
}
?>
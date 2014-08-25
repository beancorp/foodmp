<?php

define("ROOT_DIRECTORY", getcwd()."/../");


function tab_content($dbcon, $tab_id) {
	
	$tab_query = "SELECT * FROM photo_promo_text WHERE text_id = '".$tab_id."'";
	$result = $dbcon->getOne($tab_query);
	
	return stripcslashes(html_entity_decode($result['text_content']));
}


function tab_content_by_key_name($dbcon, $key_name = "") {
	
	$tab_query = "SELECT * FROM photo_promo_text WHERE key_name = '".$key_name."'";
	$result = $dbcon->getOne($tab_query);
	
	return stripcslashes(html_entity_decode($result['text_content']));
}



/**
 * 
 * Enter description here ...
 * @param unknown_type $dbcon
 * @param unknown_type $store_id
 * @param unknown_type $retailer_location
 * @param unknown_type $consumer_id
 * @param unknown_type $image_path
 * @param unknown_type $thumb_path
 * @param unknown_type $description
 */
function insert_photo($dbcon, $store_id, $retailer_location, $consumer_id, $image_path = "", $thumb_path = "", $description = "", $state_id = 0, $category_id = 0, $retailer_name = "", $unique_id = "") {
	$photo_record = array(
		'store_id' => $store_id,
		'retailer_location' => $retailer_location,
		'consumer_id' => $consumer_id,
		'image' => $image_path,
		'thumb' => $thumb_path,
		'description' => $description,
		'state_id' => $state_id,
	 	'category_id'=> $category_id,
		'retailer_name'=> $retailer_name,
		'unique_id' => $unique_id,  
	);
	$dbcon->insert_query('photo_promo', $photo_record);
	return $dbcon->lastInsertId();
}


function update_photo($dbcon, $array_update, $photo_id){
	$condition = "WHERE photo_id = {$photo_id} LIMIT 1";
	$dbcon->update_query("photo_promo",$array_update,$condition);				
	return true;
}



function display_page($dbcon, $smarty, $page, $title, $lang) {
	$smarty->assign('pageTitle', $title);
	$smarty->assign('sidebarContent', '');
	$content = $smarty->fetch($page);
	
	$smarty->assign('contentStyle', 'float: left;width: 100%!important;padding: 0px!important; margin: 0px!important;');
	$smarty->assign('hideLeftMenu', 1);
	$smarty->assign('show_left_cms', 0);

	$smarty -> assign('content', $content);
	$smarty->assign('is_content',1);
	$smarty->assign('session', $_SESSION);
	$smarty -> display('../index.tpl');
	unset($smarty);
}


function getStoreIdByCode($dbcon, $code){
	$sql = "SELECT * FROM promo_store_codes WHERE code = '{$code}'";
	return $dbcon->getOne($sql);
}


function getStoreInfoByStoreId($dbcon, $store_id){
	$sql = "SELECT * FROM aus_soc_bu_detail WHERE StoreID = {$store_id}";
	return $dbcon->getOne($sql);
}



function getFranzyPhoto($dbcon, $photo_id){
	$photo_query = "SELECT photo_promo.*, promo_store_codes.code AS code, aus_soc_state.stateName AS state_abbreviation,  aus_soc_state.description AS state
					FROM photo_promo
					LEFT JOIN promo_store_codes ON photo_promo.store_id = promo_store_codes.store_id
					LEFT JOIN aus_soc_state ON photo_promo.state_id = aus_soc_state.id
					WHERE photo_id = {$photo_id}";
	return  $dbcon->getOne($photo_query);
}


function getPhotoByUserAndRetailer($dbcon, $user_id, $store_id){
	$photo_query = "SELECT photo_promo.*
					FROM photo_promo
					WHERE consumer_id = {$user_id} AND store_id = {$store_id}";
	return  $dbcon->getOne($photo_query);
}

function insert_queue_mail($dbcon, $insert_array){
	$dbcon->insert_query('queue_mail', $insert_array);
	$id = $dbcon->lastInsertId();
	
	
	$message_content = htmlspecialchars_decode($insert_array["content"]);
	$email_template_path = "../cronjob/mail_send/content_mail_".$id.".txt";
	
	
	$email_template_path = ROOT_DIRECTORY. "cronjob/mail_send/content_mail_".$id.".txt";
	
	
	
	//put in file
	file_put_contents($email_template_path, $insert_array["content"]);
	

	
	
	
	return $id;
}


//---------------------------  EMAIL TEMPLATE ----------------------------------------

function get_email_template_by_key_name($dbcon, $key_name){
	$sql = "SELECT * FROM promo_email_template WHERE key_name = '{$key_name}'";
	return $dbcon->getOne($sql);
}


//-------------------------------  GRAND TABLE -------------------------------------
function insert_grand_table($dbcon, $insert_array){
	$dbcon->insert_query('promo_grand_list', $insert_array);
	return $dbcon->lastInsertId();
}

function update_promo_grand_list($dbcon, $array_update, $photo_id){
	$condition = "WHERE photo_id = {$photo_id} LIMIT 1";
	$dbcon->update_query("promo_grand_list",$array_update,$condition);				
	return true;
}

function generate_fanpromo_code($dbcon, $store_id, $length = 5){
	$exist_code = getCodeByStoreId($dbcon, $store_id);
	if ($exist_code["code_id"])
		return $exist_code["code"];
	
	
	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    
    $stop = false;
    while($stop == false){
    	for ($i = 0; $i < $length; $i++) {
        	$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	$code = getStoreIdByCode($dbcon, $randomString);
    	if (!$code["code"]){
    		$stop = true;
    		$new_code = $randomString;
    	}
    	
    	
    	
    	
    	
    }	
    $arr = array(
		'store_id' => $store_id,
		'code' => $new_code
	);
	$dbcon->insert_query('promo_store_codes', $arr);
    return $new_code;	
}

function getCodeByStoreId($dbcon, $store_id){
	$sql = "SELECT * FROM promo_store_codes WHERE store_id = {$store_id}";
	return $dbcon->getOne($sql);
}




?>
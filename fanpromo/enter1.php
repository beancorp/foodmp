<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

@session_start();

include_once ('../include/config.php');
include_once ('../include/smartyconfig.php');
include_once ('functions.inc.php');
include_once ('maininc.php');
include_once ('class.soc.php');

include_once('../languages/'.LANGCODE.'/soc.php');
include_once('../languages/'.LANGCODE.'/foodwine/index.php');




$smarty->assign('about_text', tab_content($dbcon, 1));
$smarty->assign('how_to_enter', tab_content($dbcon, 2));
$smarty->assign('how_it_works', tab_content($dbcon, 3));


$consumer_id = $_SESSION['StoreID'];
//Work out the email domain from the http host.
$emaildomain = substr(SOC_HTTP_HOST,strpos(SOC_HTTP_HOST,':')+3,-1);


$photo_id = isset($_REQUEST["photo_id"]) ? (int)$_REQUEST["photo_id"] : 0;
 
if ($photo_id > 0){
	$photo = getFranzyPhoto($dbcon, $photo_id);
	if (!$photo["photo_id"]){
		header('location:'. SOC_HTTP_HOST . 'soc.php?cp=error404');
		exit();
	}else{   
        if(!isset($_SESSION['isAdmin']) && (isset($_SESSION['StoreID']) && ($_SESSION['StoreID'] != $photo['consumer_id']))){
            header('location:'. SOC_HTTP_HOST . 'soc.php?cp=error404');
            exit();
        }
    }
}

$error_message = "";


if (isset($_POST['code']) && $consumer_id > 0) {
	$retailer_code = trim($_POST['code']);
	$retailer_id = trim($_POST['retailer_id']);
	$state_id = trim($_POST['state_id']);
	$category_id = trim($_POST['category_id']);
	$description = trim ($_POST['entry_description']);
	$retailer_name = trim($_POST['retailer_name']);
	
	
	if (!$retailer_code)
		$retailer_id = 0;
		
	//if user post a retailer code
	if ($retailer_code != ""){
		$store = getStoreIdByCode($dbcon, $retailer_code);
		if (!$store["code_id"]){
			$error_flag = true;
			$error_message = "Non Existed Code";
		}else{
			$retailer_id = $store["store_id"];
			$retailer_info = getStoreInfoByStoreId($dbcon, $retailer_id);
			$retailer_name =  $retailer_info["bu_name"];
			$state_id = $retailer_info["bu_state"];
			$category_id = $retailer_info["subAttrib"];
		} 
		
		
		$past_photo = getPhotoByUserAndRetailer($dbcon, $consumer_id, $retailer_id);
		if ($photo_id >0 && $past_photo["photo_id"] >0  && $past_photo["photo_id"] != $photo_id){   //edit
				$error_flag = true;
				$error_message = "Only one entry per retailer";
			
		}
		
		if (!$photo_id && $past_photo["photo_id"] >0){   //create new
				$error_flag = true;
				$error_message = "Only one entry per retailer";
			
		}
		
		
		
		
	}	
	
	if ($photo_id > 0){
		if (!$error_flag){
			if ($photo["store_id"] > 0){//update an old photo, only description is allowwed
				$array_update["description"] = $description;
			}else {
				$array_update = array("store_id" => $retailer_id, 
										"description" =>  $description,
										"state_id" => $state_id,
										"category_id" => $category_id,
										"retailer_name" => $retailer_name,
									);
			}
			update_photo($dbcon,$array_update, $photo_id);
			header('location:'. SOC_HTTP_HOST . 'fanpromo/thank_you.php');
		}
	}else{
		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = strtolower(end($temp));
	
		if ((($_FILES["file"]["type"] != "image/gif")
					&& ($_FILES["file"]["type"] != "image/jpeg")
					&& ($_FILES["file"]["type"] != "image/jpg")
					&& ($_FILES["file"]["type"] != "image/pjpeg")
					&& ($_FILES["file"]["type"] != "image/x-png")
					&& ($_FILES["file"]["type"] != "image/png"))){
			$error_flag = true;
		}
		
		if (!in_array($extension, $allowedExts)) {
			$error_flag = true;
			$error_message = "File Extension is not allowed";
		}
		if ($_FILES["file"]["error"]) {
			$error_flag = true;
			$error_message = "Upload File Error";

			if(file_exists(getcwd()."/ie_upload/". $_REQUEST["ie_upload_filename"].".jpg")){
				$error_flag = false;
				$path_imagick = getcwd() . "/ie_upload/". $_REQUEST["ie_upload_filename"] . ".jpg";
			}else{
			}
		}else{
			$path_imagick = $_FILES["file"]["tmp_name"];
		}








		/*
		if ($_FILES['file']['size'] > 1024 * 1024){
			$error_flag = true;
			$error_message = "File is bigger than 1MB";
		}
		*/
		
		if (!$retailer_name){
			$error_flag = true;
			$error_message = "Retailer name is required";
		}
		if (!$error_flag){
                $upload_folder = 'uploads/'.$_SESSION['StoreID'].'/'; 
                $upload_dir = getcwd() .'/'. $upload_folder;
                if (!is_dir($upload_dir)) {
                    $old = umask(0); 
                    mkdir($upload_dir, 0777);
                    umask($old);
                }
                
                $unique_id = uniqid();
                $image_name = $unique_id.'.jpg';
				$brand_name = "brand_".$image_name;
				
				
				
				$im = new Imagick($path_imagick);
			
				if ($_POST['x1'] > 0){
					$x1 = (int)$_POST['x1'];
				}else{
					$x1 = 0;
				}
				
				if ($_POST['y1'] > 0){
					$y1 = (int)$_POST['y1'];
				}else{
					$y1 = 0;
				}
				
				
				if ($_POST["x2"] >0){
					$crop_width = $_POST["x2"] - $x1;
					$crop_height = $_POST["y2"] - $y1;
				}else{
					$crop_width = 618;
					$crop_height = 441;
				}
				
				
				
				/*
				if ($_POST['x1'] > 0){
					//$im->cropImage((int)$_POST['w'], (int)$_POST['h'], (int)$_POST['x1'], (int)$_POST['y1']);
					$im->cropImage(618, 441, (int)$_POST['x1'], (int)$_POST['y1']);
				}else{
					$im->cropImage(618, 441, 0, 0);
				}
				*/
				
				if ($_FILES["file"]["type"] == "image/gif"){
					 $im = $im->coalesceImages(); 
					foreach ($im as $frame) { 
					  $frame->cropImage($crop_width, $crop_height, $x1, $y1); 
					  $frame->thumbnailImage($crop_width, $crop_height); 
					  $frame->setImagePage($crop_width, $crop_height, 0, 0); 
					} 
					$im = $im->deconstructImages(); 
				}else{
					$im->cropImage($crop_width, $crop_height, $x1, $y1);
				}
				$im->setImageFormat('jpg');
				
				if($crop_width > 618){
					 $im->thumbnailImage(618, 441);
				}
				
				
				
				
				$image_path = $upload_folder.$image_name;
				$brand_path = $upload_folder.$brand_name;
				
				$thumb_path = $upload_folder.'thumb_'.$image_name;
				
				//add water mark
				$water_mark_file = new Imagick(getcwd() . "/../images/fanfrenzy_watermark.png");				
				$final_image = new Imagick();
				$final_image->newimage(618, 494, '#ffffffff');
				$final_image->compositeimage($im, Imagick::COMPOSITE_DEFAULT, 0, 0);
				$final_image->compositeimage($water_mark_file, Imagick::COMPOSITE_DEFAULT, 0, 441);
				$final_image->writeImage(getcwd() . "/". $brand_path);
								
				$im->writeImage(getcwd() . "/". $image_path);
				$im->thumbnailImage(290, 180);
				$im->writeImage(getcwd(). "/". $thumb_path);
				
				$photo_id = insert_photo($dbcon, $retailer_id, $_POST['retailer_location'], $consumer_id, $image_path, $thumb_path, $description, $state_id, $category_id, $retailer_name, $unique_id);
				
			
				if ($photo_id > 0) {
					
					$array_update["unique_id"] = str_pad($photo_id, 10, "0", STR_PAD_LEFT);
					update_photo($dbcon,$array_update, $photo_id);
					
					
					//header('location: https://foodmarketplace.com.au/photo_'.$photo_id.'.html');
					
					/*
					$email = $_SESSION["email"];
					$message = 'Hi '.ucwords($_SESSION["NickName"]).' <br /><br /> This email is sent to confirm that you enter our competition Fanfrenzy. <br />
									<br /><br /> Kind regards, <br /> FoodMarketplace.';
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: FoodMarketplace <no-reply@'.$emaildomain.'>' . "\r\n";
					mail($email, 'Email Verification', $message, $headers);
					*/
					$mail_param = array();	
					$mail_param["mail_to"] = $_SESSION["email"];
					$mail_param["subject"] = "Thank you";
					$email_template =  get_email_template_by_key_name($dbcon, "email_thank_you");
					//$message = str_replace("##username##", nl2br(ucwords($_SESSION["NickName"])), $email_template["content"]); //Replace
					
					//get template from txt
					$mail_template = file_get_contents(ROOT_DIRECTORY. "skin/email_template/template_mail_{$email_template["id"]}.txt");
					$message = str_replace("##username##", nl2br(ucwords($_SESSION["NickName"])), $mail_template); //Replace
					
					
					$mail_param["content"] = $message;
					
					insert_queue_mail($dbcon, $mail_param);
					
					//mail to admin
					$mail_param = array();
					
					$email_admin = tab_content($dbcon, 19);
					$mail_param["mail_to"] = $email_admin; //mail admin
					$mail_param["subject"] = "New Fanpromo Create";
					$email_template =  get_email_template_by_key_name($dbcon, "email_new_entry");
					$mail_template = file_get_contents(ROOT_DIRECTORY. "skin/email_template/template_mail_{$email_template["id"]}.txt");
					$new_link =  SOC_HTTP_HOST .'photo_'. $photo_id . '.html';
					$message = str_replace("##link_new_entry##", nl2br($new_link), $mail_template); //Replace
					$mail_param["content"] = $message;
					
					
					
					
					
					insert_queue_mail($dbcon, $mail_param);
					header('location:'. SOC_HTTP_HOST . 'fanpromo/thank_you.php');
				}
		}
	}
}

//this is for  registering form
$query	= "SELECT id, stateName, description FROM aus_soc_state ORDER BY description";
$result	= $dbcon->execute_query($query);
$state_list = $dbcon->fetch_records();
$suburb_data = array();


foreach($state_list as $state) {
	$query	= "SELECT suburb_id, suburb FROM aus_soc_suburb WHERE state = '".$state['stateName']."' ORDER BY suburb ASC";
	$result	= $dbcon->execute_query($query);
	$suburbs = $dbcon->fetch_records();
	$output = '';
	foreach($suburbs as $suburb) {
		$output .= '<option value="'.addslashes($suburb['suburb']).'">'.addslashes($suburb['suburb']).'</option>';
	}
	//$suburb_data[$state['stateName']] = $output;
	$suburb_data[$state['id']] = $output;
}








//get States
$states = getStateArray();
//get Category
$smarty -> loadLangFile('/index');




if (isset($_GET['code'])) {
		$retailer_code_sql = "SELECT detail.StoreID As store_id, detail.bu_name As retailer_name FROM promo_store_codes As codes
		INNER JOIN aus_soc_bu_detail As detail ON detail.StoreID = codes.store_id
		WHERE codes.code = '".$_GET['code']."' ORDER BY detail.bu_name ASC;";
		
		$retailer_code_result = $dbcon->getOne($retailer_code_sql);
		
		if (is_array($retailer_code_result)) {
			$smarty->assign('retailer_code_storeid', $retailer_code_result['store_id']);
			$smarty->assign('retailer_code_name', $retailer_code_result['retailer_name']);
		}
    	
}

$retailer_sql = "SELECT detail.StoreID As store_id, detail.bu_name As retailer_name FROM aus_soc_bu_detail AS detail 
		INNER JOIN aus_soc_login as lg on lg.StoreID = detail.StoreID 
		WHERE detail.CustomerType = 'seller' AND detail.attribute = '5' 
		AND NOT(detail.bu_name IS NULL) 
		AND detail.renewalDate >= UNIX_TIMESTAMP(NOW())
		AND detail.status = 1 ORDER BY detail.bu_name ASC;";

$dbcon->execute_query($retailer_sql);
$store_result = $dbcon->fetch_records(true);

$store_select = '';
if (isset($store_result)) {
	foreach($store_result as $store) {
		$store_select .= '<option value="'.$store['store_id'].'">'.ucwords($store['retailer_name']).'</option>';
	}
}

//Get tooltip about code from CMS
include_once ('fanpromo/functions.inc.php');      
$smarty->assign('tooltip_code', tab_content($dbcon, 9));


$ie_upload_name = uniqid();
$smarty->assign("ie_upload_name",  $ie_upload_name);
$smarty->assign("ie_upload_info", $ie_upload_name);
unset($_SESSION["new_name"]);


$smarty->assign('photo_id', $photo_id);
$smarty->assign('photo', $photo);
$smarty->assign('error_message', $error_message);

$smarty->assign('store_select', $store_select);

$smarty->assign('states', $states);
$smarty->assign('state_list', $state_list);
$smarty->assign('suburb_data', $suburb_data);  
if($photo["photo_id"] && isset($_SESSION['isAdmin'])){
    $smarty->assign('logged_in', true);       
}else{
    $smarty->assign('logged_in', isset($_SESSION['StoreID']));   
}
$smarty->assign('redirect_url', SOC_HTTP_HOST . 'entry');

display_page($dbcon, $smarty, 'enter1.tpl', 'Fan Promo - Enter', $_LANG);
?>
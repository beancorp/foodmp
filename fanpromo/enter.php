<?php
//ini_set('display_errors', '1');
//error_reporting(E_ALL);


$loginSuccessful = false;
if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];
    if ($username == 'admin' && $password == 'KahnKazzi88'){
        $loginSuccessful = true;
    }
}
if (!$loginSuccessful){
    header('WWW-Authenticate: Basic realm="Secret page"');
    header('HTTP/1.0 401 Unauthorized');
    print "Login failed!\n";
    exit;
}

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


if (isset($_POST['submit']) && isset($_SESSION['StoreID'])) {
	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);

	if ((($_FILES["file"]["type"] == "image/gif")
	|| ($_FILES["file"]["type"] == "image/jpeg")
	|| ($_FILES["file"]["type"] == "image/jpg")
	|| ($_FILES["file"]["type"] == "image/pjpeg")
	|| ($_FILES["file"]["type"] == "image/x-png")
	|| ($_FILES["file"]["type"] == "image/png"))
	
	&& in_array($extension, $allowedExts)) {
		if ($_FILES["file"]["error"] == 0) {
			$upload_folder = 'uploads/';
			
			$image_name = uniqid().'.jpg';
			
			$im = new Imagick($_FILES["file"]["tmp_name"]);
			$im->resizeImage(615, 415, Imagick::FILTER_LANCZOS, 1);
			$im->setImageBackgroundColor('white');
			$im->setCompressionQuality(100);
			$im = $im->flattenImages(); 
			$im->setImageFormat('jpg');
			
			$image_path = $upload_folder.$image_name;
			$thumb_path = $upload_folder.'thumb_'.$image_name;
			
			
			$im->writeImage(getcwd() . "/". $image_path);
			$im->thumbnailImage(290, 180);
			$im->writeImage(getcwd(). "/". $thumb_path);
			
			$photo_id = insert_photo($dbcon, $_POST['retailer_code'], $_POST['retailer_location'], $_SESSION['StoreID'], $image_path, $thumb_path, $_POST['entry_description']);
			
			
			
			if ($photo_id > 0) {
				header('location: https://foodmarketplace.com.au/photo_'.$photo_id.'.html');
			}
		}
	}
}

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
	$suburb_data[$state['stateName']] = $output;
}



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

$smarty->assign('store_select', $store_select);


$smarty->assign('state_list', $state_list);
$smarty->assign('suburb_data', $suburb_data);

$smarty->assign('logged_in', isset($_SESSION['StoreID']));

display_page($dbcon, $smarty, 'enter.tpl', 'Fan Promo - Enter', $_LANG);
?>
<?php
@session_start();

include_once ('include/config.php');
include_once ('include/smartyconfig.php');
include_once ('maininc.php');
include_once ('class.soc.php');

include_once('languages/'.LANGCODE.'/soc.php');
include_once('languages/'.LANGCODE.'/foodwine/index.php');

$response = array();

if (isset($_POST['photo_id'])) {
	
	$fan_promo_path = DOCUMENT_ROOT. 'fanpromo/';
	$upload_folder = $fan_promo_path . 'uploads/';
	
	$photo_query = "SELECT photo.*, 
						consumer.bu_name As consumer, 
						retailer.bu_name As retailer, 
						COUNT(fans.fan_id) As fan_count 
					FROM photo_promo photo
					LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id 
					LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
					INNER JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
					WHERE photo.photo_id = '".$_POST['photo_id']."'";
	
	$photo = $dbcon->getOne($photo_query);
	
	if (isset($photo)) {	
		
		$imagick = new Imagick();
		$imagick->readImage($fan_promo_path . $photo['image']);
		$imagick->rotateImage(new ImagickPixel(), 90);
		$im = $imagick->getImage();

		$im->setImageBackgroundColor('white');
		$im->setCompressionQuality(100);
		$im = $im->flattenImages(); 
		$im->setImageFormat('jpg');
		
		$image_name = uniqid().'.jpg';
		
		$image_path = $upload_folder.$image_name;
		$thumb_path = $upload_folder.'thumb_'.$image_name;
		
		$im->writeImage($image_path);
		$im->thumbnailImage(290, 180);
		$im->writeImage($thumb_path);
		
		
		$photo_query_update = "UPDATE photo_promo SET image = 'uploads/".$image_name."', thumb = '".'uploads/thumb_'.$image_name."' WHERE photo_id = '".$_POST['photo_id']."'";
		
		$dbcon->execute_query($photo_query_update);
		
		
		$response['image'] = '/fanpromo/uploads/'.$image_name;
		
		$response['success'] = true;
		
	} else {
	
		$response['success'] = false;
		
	}

	
} else {

	$response['success'] = false;
	
}

echo json_encode($response);


?>
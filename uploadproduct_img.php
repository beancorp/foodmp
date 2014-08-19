<?php
ini_set("max_execution_time", 3600);
include_once "include/config.php" ;
include_once "include/smartyconfig.php" ;
include_once ("class.upload.php");
include_once ("class.uploadImages.php");
include_once ('maininc.php');
include_once ('functions.php');
$objUpImg	=	new uploadImages();
if(isset($_FILES['Filedata'])&&$_FILES['Filedata']['size']>0){
		if(stripos($_FILES['Filedata']['name'],'.jpg')){
			$_FILES['Filedata']['type'] = 'image/jpeg';
		}elseif(stripos($_FILES['Filedata']['name'],'.gif')){
			$_FILES['Filedata']['type'] = 'image/gif';
		}elseif(stripos($_FILES['Filedata']['name'],'.png')){
			$_FILES['Filedata']['type'] = 'image/x-png';
		}
		$_FILES["upfiles"] = $_FILES['Filedata'];
		$data = $objUpImg	-> upload();
		if($data['msg']==""){
			echo $data['objImage']."|".$data['valueDis']."|".$data['valueDisW']."|".$data['valueDisH']."|".$data['valueSmall']."|".$data['valueBig'];
		}
}?>
		
		
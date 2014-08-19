<?php
/*
 * jQuery File Upload Plugin PHP Class 8.0.2
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

ini_set('display_errors', 0);
error_reporting(E_ALL);

// sleep(15);
  
///header('content-type: application/json; charset=utf-8');

//file type, 
//width, height, size
//

@session_start();           



if ((isset($_GET['ie_upload_name']) && empty($_FILES["file"])) || (isset($_GET['ie_upload_name']) && $_FILES["file"]["size"]==0)){
	//upload more than 1.5MB
	$upload_folder = 'ie_upload/';
	$image_name = $_GET["ie_upload_name"];
	$json_name = $image_name. ".json";
	
	if ($_SESSION["new_name"]){  
		$json_name = $_SESSION["new_name"] .".json";
	}
	$new_name = time(). rand(1,1000); 
	$_SESSION["new_name"] = $new_name;
	$image_name .= '.jpg';
	$json_data["error"] = 2; //error
	//$json_data["new_name"] = $_GET["ie_upload_name"].time().rand(1,10);
	$json_data["new_name"] = $new_name;	
	
	
	$json_path = $upload_folder.$json_name;
	$json_string = json_encode($json_data);
	file_put_contents(getcwd() . "/". $json_path, $json_string);
	
	//$data = file_get_contents($_FILES['file']['tmp_name']);
	//$data = file_get_contents(getcwd() . "/". $image_path);
	echo "json_path created:" .  getcwd() . "/". $json_path;
	exit();
}





if ($_FILES["file"]["name"]){
	$upload_folder = 'ie_upload/';
	$image_name = $_GET["ie_upload_name"];
	$json_name = $image_name. ".json";
	
	if ($_SESSION["new_name"]){  
		$json_name = $_SESSION["new_name"] .".json";
	}
	
	$new_name = time(). rand(1,1000); 
	$_SESSION["new_name"] = $new_name;
	
	
	$image_name .= '.jpg';
	
	$file_type = true;

	if ((($_FILES["file"]["type"] != "image/gif")
		&& ($_FILES["file"]["type"] != "image/jpeg")
		&& ($_FILES["file"]["type"] != "image/jpg")
		&& ($_FILES["file"]["type"] != "image/pjpeg")
		&& ($_FILES["file"]["type"] != "image/x-png")
		&& ($_FILES["file"]["type"] != "image/png"))){
		$json_data["error"] = 3; //error not file
		$file_type = false;
	}
	
	$data = "";
	//if ($_FILES['file']['size'] <= (1048576)){
	if ($_FILES['file']['size'] <= (1048576) && $_FILES['file']['size'] > 0 && $file_type){


		$im = new Imagick($_FILES["file"]["tmp_name"]);
		$image_path = $upload_folder.$image_name;
		$bool =  $im->writeImage(getcwd() . "/". $image_path);
		for ($i=1; $i<100000; $i++){
			$rs = file_exists(getcwd()."/".$image_path);
			if ($rs === true)
				break;
		}
		$im = new Imagick(getcwd()."/".$image_path);
		$json_data["width"] = $im->getImageWidth();
		$json_data["height"] = $im->getImageHeight();
		$data = file_get_contents($_FILES['file']['tmp_name']);

		$json_data["base64"] = 'data:image/png;base64,' . base64_encode($data);
		$json_data["error"] = 1; //done		
	}else{
        if(!isset($json_data["error"]))
		    $json_data["error"] = 2; //error
	}
	
	$json_data["size"] = $_FILES['file']['size'];
	$json_data["type"] = $_FILES["file"]["type"];
	//$json_data["new_name"] = $_GET["ie_upload_name"].time().rand(1,10);
	$json_data["new_name"] = $new_name;	
	
	
	$json_path = $upload_folder.$json_name;
	$json_string = json_encode($json_data);
	file_put_contents(getcwd() . "/". $json_path, $json_string);
	
	//$data = file_get_contents($_FILES['file']['tmp_name']);
	//$data = file_get_contents(getcwd() . "/". $image_path);
	echo "json_path created:" .  getcwd() . "/". $json_path;
}
?>

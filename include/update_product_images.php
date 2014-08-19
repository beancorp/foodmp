<?php
include_once "../include/config.php" ;
include_once "../include/maininc.php" ;
include_once "../include/functions.php" ;
include_once("../include/class/common.php");
include_once("../include/class.upload.php");
include_once("../include/class.uploadImages.php");

// need set the table name and field name
$base_path = getcwd();
$path_tag = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'?'\\':'/';
echo "OS: ".PHP_OS."\n<br>";

$sql = "select * from ".$GLOBALS['table']."product where deleted!='YES'";
$dbcon->execute_query($sql);
$result = $dbcon->fetch_records();

//echo "<pre>";
$count = 0;
foreach ($result as $row){
	//var_dump($row);
	$dateNow	=	time();
	$j=0;
	for ($i=0;$i < 7;$i++){
		
		$img = ($i==0)?$row['image_name']:$row['moreImage'.$i];
		//echo "path:".$base_path.'/../'.$img." \n<br>Real:".realpath($base_path.'/../').$img."\n<br>";
		if (empty($img) or $img == 'images/50x50.gif' or $img == 'images/79x79.jpg' 
			or $img == 'images/243x212.jpg' or $img == 'skin/red/images/default-thumbnail.gif'
			or !file_exists(realpath($base_path.'/../').'/'.$img)){
			//echo "Not Exists, Exit.\n<br>";
			continue;
		}else{
			echo "Current Image: $img\n<br>Exists: ".realpath($base_path.'/../').'/'.$img."\n<br>";
			if ($i==0){
				$arrPricture = moveImage(realpath($base_path.'/../'),$row['datec'],$img,243,212);
				$arrSetting	=	array(
				"StoreID"		=>	$row['StoreID'],
				"tpl_type"		=>	0,
				"pid"			=>	$row['pid'],
				"smallPicture"	=>	$arrPricture['small'],
				"picture"		=>	$arrPricture['big'],
				"attrib"		=>	0,
				"sort"			=>	0,
				"datec"			=>	$dateNow,
				"datem"			=>	$dateNow
				);
				insertImageToDB($arrSetting);
			}else{
				$arrPricture = moveImage(realpath($base_path.'/../'),$row['datec'],$img,79,79);
				$arrSetting	=	array(
				"StoreID"		=>	$row['StoreID'],
				"tpl_type"		=>	0,
				"pid"			=>	$row['pid'],
				"smallPicture"	=>	$arrPricture['small'],
				"picture"		=>	$arrPricture['big'],
				"attrib"		=>	1,
				"sort"			=>	$j,
				"datec"			=>	$dateNow,
				"datem"			=>	$dateNow
				);
				insertImageToDB($arrSetting);
				$j ++;
			}
			
			$count++;
		}
		echo "End Current Image: $img\n<br>";
	}
}
echo date('Y-m-d').": remove $count images.";

function moveImage($base, $stamp, $image, $width, $height){
	$arrResult	=	null;
	
	echo "base: $base\n<br>";
	// generate the new filename
	$obj = new uploadFile(700,525,10240);
	$name = $obj->getNewFileName($image,'s');
	
	// generate the target url
	$target = '/upload/userImages/'.date("Ym/d/", $stamp);
	
	// check the directory and create it if not exists
	if (!file_exists($base.'/upload/userImages/'.date("Ym",$stamp))){
		mkdir($base.'/upload/userImages/'.date("Ym",$stamp));
		echo "mkdir: ".$base."/upload/userImages/".date("Ym",$stamp)."\n<br>";
	}
	if (!file_exists($base.'/upload/userImages/'.date("Ym/d",$stamp))){
		mkdir($base.'/upload/userImages/'.date("Ym/d",$stamp));
		echo "mkdir: ".$base."/upload/userImages/".date("Ym/d",$stamp)."\n<br>";
	}

	// check file name exists, if exists, regenerate the filename
	while(file_exists($base.$target.$name[0]) or file_exists($base.$target.$name[1])){
		$name = $obj->getNewFileName($image,'s');
	}
	$bname = $name[0];
	$sname = $name[1];
	
	$arrResult	=	array('small'=>$target.'/'.$sname, 'big'=>$target.'/'.$bname);
	
	// generate the thump image
	MakeThumpImage($base.'/'.$image, $base.$target.$sname, $width, $height);
	echo "Create Thump image: $base/$image, $base$target$sname, $width, $height \n<br>";
	rename($base.'/'.$image,$base.$target.$bname);
	echo "Move Image from $base/$image to $base$target$bname\n<br>";
	
	return $arrResult;
}

function MakeThumpImage($srcFile,$dstFile,$dstW=0,$dstH=0, $hasScale=true, $clarity=100) {
	$boolResult = false;

	$data = @getimagesize($srcFile);

	if (is_array($data)) {

		switch ($data[2]) {
			case 1:
				$source = @imagecreatefromgif($srcFile);
				break;
			case 2:
				$source = @imagecreatefromjpeg($srcFile);
				break;
			case 3:
				$source = @imagecreatefrompng($srcFile);
				break;
		}

		list($width, $height) = getimagesize($srcFile);

		$dstW	=	$dstW > 0 ? $dstW : $this->_width;
		$dstH	=	$dstH > 0 ? $dstH : $this->_height;
		if ($hasScale) {
			if($width > $height){
				$dstH = $dstW / $width * $height ;
			}else{
				$dstW = $dstH / $height * $width ;
			}
		}

		$thumb = @imagecreatetruecolor($dstW, $dstH);
		@imagecopyresampled($thumb,$source,0,0,0,0,$dstW,$dstH,$width,$height);
		switch ($data[2]) {
			case 1:
				@imagegif($thumb,$dstFile);
				break;
			case 2:
				imagejpeg($thumb,$dstFile,$clarity);
				break;
			case 3:
				@imagepng($thumb,$dstFile);
				break;
		}
		@imagedestroy($thumb);
	}

	if (file_exists($dstFile)) {
		$boolResult = true;
	}

	return $boolResult;
}

function insertImageToDB($arrSetting){
	$GLOBALS['dbcon']->insert_record($GLOBALS['table']."image",$arrSetting);
}
?>
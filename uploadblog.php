<?php
ini_set("max_execution_time", 3600);
include_once "include/config.php" ;
include_once "include/smartyconfig.php" ;
include_once "include/class.upload.php";
if(isset($_FILES['Filedata'])&&$_FILES['Filedata']['size']>0){
	if(stripos($_FILES['Filedata']['name'],'.jpg')){
		$_FILES['Filedata']['type'] = 'image/jpeg';
	}elseif(stripos($_FILES['Filedata']['name'],'.gif')){
		$_FILES['Filedata']['type'] = 'image/gif';
	}elseif(stripos($_FILES['Filedata']['name'],'.png')){
		$_FILES['Filedata']['type'] = 'image/x-png';
	}
	$PW		=	empty($_REQUEST['pw']) ? 397 : $_REQUEST['pw'] ;
	$PH		=	empty($_REQUEST['ph']) ? 282 : $_REQUEST['ph'] ;

	$objUpload		= new uploadFile($PW, $PH ,10240 ,9 , $_FILES["Filedata"], "SOCExchange.com.au");
	$status = $objUpload->upload();	
	$disImageName		=	$objUpload -> newFileFullName;
	$disSName			=	$objUpload -> newFileFullNameSmall;
	$msg	=	$objUpload -> strMessage;
	if($status){
		echo $disImageName."|".$disSName."|".$_REQUEST['type'];
	}
}
?>
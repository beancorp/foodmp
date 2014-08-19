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
		if($_REQUEST['type']=='category'){
			$objUpload		= new uploadFile(600, 600 ,10240 ,15 , $_FILES["Filedata"], "SOCExchange.com.au.au");
			$status = $objUpload->uploadAndSmallPic(100,100);	
		}elseif($_REQUEST['type']=='template'){
			$objUpload		= new uploadFile(750, 245 ,10240 ,13 , $_FILES["Filedata"], "SOCExchange.com.au");
			$status = $objUpload->upload();
		}elseif($_REQUEST['type']=='product'){
			$UT		=	$_REQUEST["ut"];
			$rsc 	=	$_REQUEST["res"];

			if($rsc=="tmp-n-a"){
				$PW = '497';$PH = '195';
			}else{
				$PW = '243';$PH = '212';
			}
			$objUpload		= new uploadFile($PW, $PH ,10240 ,$UT , $_FILES["Filedata"], "SOCExchange.com.au");
			$status = $objUpload->upload();
		}elseif($_REQUEST['type']=='seller_product'){
			$UT		=	$_REQUEST["ut"];
                        $rsc=$_GET['res'];
			if($rsc=="tmp-n-a"){
				$PW = '497';$PH = '195';
			}else{
				$PW = '243';$PH = '212';
			}
			$objUpload		= new uploadFile($PW, $PH ,10240 ,$UT , $_FILES["Filedata"], "SOCExchange.com.au");
			$status = $objUpload->upload();
		}elseif($_REQUEST['type']=='store_banner'){
			$UT		=	$_REQUEST["ut"];
			$PW = '950';$PH = '75';
			$objUpload		= new uploadFile($PW, $PH ,10240 ,$UT , $_FILES["Filedata"], "SOCExchange.com.au");
			$status = $objUpload->upload();
		}elseif ($_REQUEST['type']=='invitation'){
			$objUpload		= new uploadFile(749, 216 ,10240 ,14 , $_FILES["Filedata"], "SOCExchange.com.au");
			$status = $objUpload->upload();
		}else{
			$objUpload		= new uploadFile(480, 320 ,10240 ,16 , $_FILES["Filedata"], "SOCExchange.com.au");
			$status = $objUpload->uploadAndSmallPic(100,100);
		}
		$objUpload->_maxSize = 1024*10;
		$disImageName		=	$objUpload -> newFileFullName;
		$disSName			=	$objUpload -> newFileFullNameSmall;
		$msg	=	$objUpload -> strMessage;
		if ($status) {
			if ($_REQUEST['type'] == 'store_banner') {
				$imagesize = getimagesize($disImageName);
				if ($imagesize[0] != 950 || $imagesize[1] != 75) {
					$status = 114;
				}
				echo $disImageName."|".$disSName."|".$_REQUEST['type']."|".$status;
			} else {
				echo $disImageName."|".$disSName."|".$_REQUEST['type'];
			}	
		}
		
}

?>
		
		
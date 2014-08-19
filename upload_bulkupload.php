<?php 
$rootpath = dirname(__FILE__);
$str = $_FILES['Filedata']['tmp_name'];
if(preg_match('/\\\/',$str)){$pd= "\\";}else if(preg_match('/\//',$str)){$pd = "/";}
$filename =  substr($str,strrpos($str,$pd)+1);
$filename = $rootpath."{$pd}upload{$pd}temp{$pd}".$filename;
$type = $_REQUEST['type'];
if($type=="csvmsg"){
	$upfile= "Products Information file";
}else{
	$upfile= "Products Images file";
}
if(move_uploaded_file($_FILES['Filedata']['tmp_name'],$filename)){
	echo $type."|".$filename."|".$upfile." upload successfully.";
}else{
	echo $type."|"."|".$upfile." upload unsuccessfully.";
}
?>
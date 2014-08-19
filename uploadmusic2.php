<?php
ini_set("max_execution_time", 3600);
include_once "include/session.php" ;
include_once "include/config.php" ;
include_once "include/smartyconfig.php";
include_once "include/maininc.php" ;
include_once "include/functions.php";
include_once "include/class/common.php";
include_once "include/class.wishlist.php";
$wishlist = new wishlist();

if(isset($_FILES['Filedata'])&&$_FILES['Filedata']['size']>0){
	$_FILES['music'] = $_FILES['Filedata'];
	$aryinfo = $wishlist->uploadMusic();
	if($aryinfo['msg']==""){
		echo $aryinfo['music']."|".$aryinfo['music_name']."|music";
	}
}
?>
		


<?php
@session_start();
include_once('../include/smartyconfig.php');
include_once('class.login.php');
include_once('maininc.php');
include_once('functions.php');
include_once('class.soc.php');

$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}

if (isset($_POST['submit'])) {
	$insert_query = "INSERT INTO aus_soc_product_library SET product_name = '".$_POST['product_name']."', product_image_small = '".$_POST['mainImage_svalue']."', product_image = '".$_POST['mainImage_bvalue']."'";
	$dbcon->execute_query($insert_query); 
}

if (isset($_GET['delete'])) {
	$delete_query = "DELETE FROM aus_soc_product_library WHERE product_id = '".$_GET['delete']."'";
	$dbcon->execute_query($delete_query);
	header('Location: /admin/?act=imagelibrary');
}

$images_query = "SELECT * FROM aus_soc_product_library ORDER BY product_name ASC";
$dbcon->execute_query($images_query);
$images_result = $dbcon->fetch_records();

$smarty->assign('images', $images_result);
		
$content = $smarty->fetch('admin_imagelibrary.tpl');
$smarty->assign('content', $content);
$smarty->display('index.tpl');
unset($smarty);
exit;
?>
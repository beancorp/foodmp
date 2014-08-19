<?php
include_once "include/session.php" ;
include_once "include/config.php" ;
include_once "include/maininc.php" ;
include_once "include/functions.php" ;

$id = $_REQUEST['id'];
if ($id and is_numeric($id)){
	$banner_table = $table."banner_soc";
	$sql = "select * from $banner_table where banner_id = $id";
	$dbcon->execute_query($sql);
	$grid = $dbcon->fetch_records();
	$dbcon->execute_query("update $banner_table set click=click+1 where banner_id=$id");
//        exit(htmlspecialchars_decode($grid[0]['banner_link']));
	header("Location: ".htmlspecialchars_decode($grid[0]['banner_link'])) ;
}
exit;
?>
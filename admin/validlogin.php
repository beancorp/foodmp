<?php

$user = $_SESSION['u'];
$pass = $_SESSION['p'];
$sqlv = "select * from " .$GLOBALS['table']. "login where user = '$user' and password = '$pass' " ;
$dbcon->execute_query($sqlv) ;
$rows = $dbcon->count_records() ;
$grid = $dbcon->fetch_records() ;
$_SESSION['level']=$chklevel = $grid[0]['level'];

if ($rows > 0 and $_SESSION['level'] == 0) { 
	$_SESSION['u'] = $user;
	$_SESSION['p'] = $pass; 
}else{ 
	$_SESSION["msglogin"] = "Please Check your Username or Password" ;
	header("Location: ../admin/") ;
}
?>
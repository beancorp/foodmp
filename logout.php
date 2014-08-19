<?php

ob_start();
include_once "include/session.php" ;
include_once "include/config.php" ;
include_once 'include/fbconfig.php';
include_once "include/maininc.php" ;
include_once "include/functions.php" ;

session_destroy();
$_SESSION = array();

// Modify by Haydn.H By 20120301 ========= Begin =========
if($_COOKIE){
    foreach ($_COOKIE as $key=>$val){
        if(preg_match("/^fbsr\_/", $key)){
            setcookie($key, "", -3600, '/');
        }
	if(preg_match("/^fbm\_/", $key)){
            setcookie($key, "", -3600, '');
        }
    }
}
// Modify by Haydn.H By 20120301 ========= End =========

header("Location:index.html");

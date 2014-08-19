<?php

$soc_include_path = str_replace('\\', '/', dirname(__FILE__));
define('SOC_INCLUDE_PATH', $soc_include_path);
define('BP', str_replace('/include', '', $soc_include_path));

include_once SOC_INCLUDE_PATH.'/config.php' ;
//YangBall 2010-10-15
include_once SOC_INCLUDE_PATH.'/base_include_helper.php';
//YangBall, 2011-03-08
include_once(SOC_INCLUDE_PATH . '/class.input.php');
//END-YangBall
include_once SOC_INCLUDE_PATH.'/class.db.php' ;

$dbcon = new  DatabaseConnection($host,$user,$pass,$database) ;
$dbcon->execute_query("set time_zone = '".TIME_MAP."';");
include_once(SOC_INCLUDE_PATH . '/functions.php');
?>
<?php 
@ob_start("gz_handler") ;
//set_magic_quotes_runtime("off") ;
@ini_set("max_execution_time","360") ;
ini_set("session.gc_maxlifetime","14400");
ini_set("error_reporting","E_ALL") ;
$cookiedomain = str_replace('www.','',substr(SOC_HTTP_HOST,strpos(SOC_HTTP_HOST,':')+3,-1));
ini_set("session.cookie_domain",$cookiedomain);
session_set_cookie_params(0, '/', $cookiedomain);
@session_start();
?>

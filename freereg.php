<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * added by YangBall, 2011-03-18
 * this entrie is free to register
 */
require dirname(__FILE__) . '/include/config.php';

@session_start();
if(isset($_SESSION['StoreID']) and '' != $_SESSION['StoreID']) {
    header('Location: /soc.php?cp=home');
    exit;
}
$_SESSION['_FREE_REGISTER_'] = '___';
header('Location: ' . SOC_HTTPS_HOST . 'soc.php?act=signon');
exit;
?>

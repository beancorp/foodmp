<?php
/**
 * estate seller control file
 * Mon Dec 08 03:56:50 GMT 2008 03:56:50
 * @author  : Jessee <support@infinitytesting.com.au>
 * @version : V0.1
 * ------------------------------------------------------------
 * 
 */

include_once ('../include/config.php');

@session_start();

switch($_REQUEST["act"]){
	case 'product':
		include('estate_product.php');
		break;
		
	default:
		include("search.php");
		break;
		
}

exit;
?>

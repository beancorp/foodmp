<?php
/**
 * Thu Dec 04 05:45:34 GMT 2008 05:45:34
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * auto control main
 * ------------------------------------------------------------
 * \auto\index.php
 */

include_once ('../include/config.php');

@session_start();

switch ($_REQUEST['act'])
{
	case 'product':
		include('product.php');
		break;
	
	default:
		include('search.php');
		break;
		
}
exit;
?>
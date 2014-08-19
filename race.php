<?php
include_once ('include/config.php');
@session_start();
include_once ('include/smartyconfig.php');
include_once ('include/maininc.php');
include_once ('include/class.soc.php');


include_once(SOC_INCLUDE_PATH . '/class.point.php');
 $objPoint = new Point();
for ($i = 1; $i < 4; $i++) {
 	$objPoint->addPointRecords($_SESSION['StoreID'], 'product', $i, false); 
}


exit;

?>

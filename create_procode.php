<?php
include_once ('include/config.php');
@session_start();
include_once ('include/smartyconfig.php');
include_once ('include/maininc.php');
include_once ('include/class.soc.php');
include_once ('include/class.soc.php');
include_once ('include/class.soc.php');
include_once ('class.adminpromotion.php');

$num = 1;
$total_num = 5;
$addtime = time();
$objAdminPromotion = new adminPromotion();

echo 'Current Time : ' . $addtime . '<br />';
for ($market = 1; $market <= 5; $market++) {
	if ($market == 4) {
		continue;
	}
	$num = 1;
	for ($i = 0; $i < 100000; $i++) {
		if ($num > $total_num) {
			break;
		}
		$promotion = rand(100000, 999999);
		$aryseting = array('promotion' => $promotion, 'attribute' => $market, 'addtime' => $addtime);
		if ($objAdminPromotion->createpromot($aryseting)) {
			echo $i.' : '.$promotion.'<br />';
			$num++;
		}	
	}
}

?>

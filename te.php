<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$to = '84521220@qq.com';
	$headers  = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\n";

	/* additional headers */
	$headers .= "To: ".$to." \n";
	$headers .= "From: yangball@126.com\n";



$sum = (isset($_GET['sum']) and intval($_GET['sum'])>0) ? intval($_GET['sum']) : 10;


$start = time();
for($i=1; $i<= $sum; $i++)
{
    $subject = 'My name is BALL.Yang --' . $i;

    $message = 'THIS IS MESSAGE.<br/> Now : ' . date('Y-m-d H:i:s');
    mail('', $subject, $message, $headers);
}


$x = time() - $start;
echo 'Count : ' . $sum . '<br/>';
echo 'All : ' . $x . '<br/>Per : ' . (floatval($x/$sum));
?>

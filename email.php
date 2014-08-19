<?php
/*$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$headers .= 'From: noreply@TheSOCExchange.com' . "\r\n";
$to = $_GET['to'] ? $_GET['to'] : 'kevin.liu@infinitytesting.com.au';
$subject = $_GET['subject'] ? $_GET['subject'] : 'Test Send Email';
$content = $_GET['content'] ? $_GET['content'] : 'Test Send Email Content';
$res = mail($to, $subject, $content, $headers);
if ($res){
	$msg = 'Email sent successfully.';
}else{
	$msg = 'Failed to send email.';
}

echo $msg;*/




ob_start();
include_once ("include/session.php");
include_once ('include/config.php');
include_once ('include/smartyconfig.php');
include_once ("maininc.php");
include_once ('class/common.php');
include_once ('class.emailClass.php');
include_once ('class.upload.php');
include_once SOC_INCLUDE_PATH . '/functions.php';


$arrTemp	=	$arrTemp[0];
$arrParams	=	array(
'display'			=>	'conseller',
'To'				=>	'kevin.liu@infinitytesting.com.au',
'Subject'			=>	'Message Alert From SOC Exchange',
'seller_nickname'	=>	'TEST',
'webside_link'		=>	'http://'.$_SERVER['HTTP_HOST']
);
$objEmail	=	new emailClass();
echo $objEmail -> send($arrParams,'email_contact_seller.tpl', false);
?>
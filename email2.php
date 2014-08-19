<?php
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$headers .= 'From: noreply@TheSOCExchange.com' . "\r\n";
$to = $_GET['to'] ? $_GET['to'] : 'test@thesocexchange.com.au';

$res = mail($to, 'Test Send Email','Test Send Email', $headers);
if ($res){
	$msg = 'Email sent successfully.';
}else{
	$msg = 'Failed to send email.';
}

echo $msg;
?>

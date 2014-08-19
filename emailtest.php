<?php
$message = 'Testing';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: FoodMarketplace <no-reply@foodmarketplace.com>' . "\r\n";
mail('the@lance.name', 'Email Verification', $message, $headers);

if ( function_exists( 'mail' ) )
{
    echo 'mail() is available';
}
else
{
    echo 'mail() has been disabled';
}  

?>
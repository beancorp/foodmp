<?php
require_once( 'EwayPayment2.php' );

$eway = new EwayPayment( '87654321', 'https://www.eway.com.au/gateway_cvn/xmltest/testpage.asp' );

//$eway = new EwayPayment( '19262956');

$eway->setMyXMLRequest($_POST['xmlrequest']);



if( $eway->doPayment() == EWAY_TRANSACTION_OK ) {
    echo "Success"; 
} else {
    echo "Error occurred (".$eway->getError()."): " . $eway->getErrorMessage();
}

?>

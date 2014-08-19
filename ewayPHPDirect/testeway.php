<?php
require_once( 'EwayPayment.php' );

$eway = new EwayPayment( '87654321', 'https://www.eway.com.au/gateway_cvn/xmltest/testpage.asp' );

//$eway = new EwayPayment( '19262956','https://www.eway.com.au/gateway_cvn/xmltest/testpage.asp');

//Substitute 'FirstName', 'Lastname' etc for $_POST["FieldName"] where FieldName is the name of your INPUT field on your webpage

$eway->setCustomerEmail( 'name@xyz.com.au' );

$eway->setCardHoldersName( 'John Smith' );
$eway->setCardNumber( '4444333322221111' );
$eway->setCardExpiryMonth( '01' );
$eway->setCardExpiryYear( '15' );

$eway->setTotalAmount( 100 );
$eway->setCVN( 123 );


if( $eway->doPayment() == EWAY_TRANSACTION_OK ) {
    echo "Transaction Successful: ". $eway->getTrxnStatus()."</br>";
    echo "Transaction Number: " . $eway->getTrxnNumber()."</br>";
    echo "Transaction Reference: " . $eway->getTrxnReference()."</br>";
    echo "Return Amount: " . $eway->getReturnAmount()."</br>";
    echo "Auth Code: " . $eway->getAuthCode()."</br>";
    echo "Option1: " . $eway->getTrxnOption1()."</br>";
    echo "Option2: " . $eway->getTrxnOption2()."</br>";
    echo "Option3: " . $eway->getTrxnOption3()."</br>";
} else {
    echo "Error occurred (".$eway->getError()."): " . $eway->getErrorMessage();
}
?>

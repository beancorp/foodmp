<?php
// Titles
$_LANG['tt']['itemname']		=	'Vehicle Title';
$_LANG['tt']['category']		=	'New / Used';
$_LANG['tt']['type']			=	'Type';
$_LANG['tt']['make']			=	'Make';
$_LANG['tt']['model']			=	'Model';
$_LANG['tt']['makeUser']		=	'Make';
$_LANG['tt']['modelUser']		=	'Model';
$_LANG['tt']['year']			=	'Year';
$_LANG['tt']['kms']				=	'KMS';
$_LANG['tt']['door']			=	'Door';
$_LANG['tt']['seat']			=	'Seat';
$_LANG['tt']['pattern']			=	'Vehicle Type';
$_LANG['tt']['transmission']	=	'Transmission';
$_LANG['tt']['color']			=	'Colour';
$_LANG['tt']['regNo']			=	'Reg. No';
$_LANG['tt']['regExpired']		=	'Reg. Expiry';
$_LANG['tt']['price']			=	'Price';
$_LANG['tt']['negotiable']		=	'Negotiable';
$_LANG['tt']['state']			=	'State';
$_LANG['tt']['suburb']			=	'Suburb';
$_LANG['tt']['postcode']		=	'Postcode';
$_LANG['tt']['street']			=	'Street Address';
$_LANG['tt']['content']			=	'Description';
$_LANG['tt']['featureList']		=	'Feature List';
//$_LANG['tt']['featured']		=	'Featured';
$_LANG['tt']['featured']		=	'<strong align="left">Display on<br /> your<br /> homepage</strong>';
$_LANG['tt']['enabled']			=	'Published';
$_LANG['tt']['deleted']			=	'Deleted';
$_LANG['tt']['datePay']			=	'Payment Date';
$_LANG['tt']['dateAdd']			=	'Date Added';

$_LANG['tt']['feetype']			=	'Fee Type';

$_LANG['tt']['body']			=	'Body';
$_LANG['tt']['location']		=	'Location';
$_LANG['tt']['contactPerson']	=	'Contact Person';

//value
for ($i=date('Y')-20; $i<=date('Y')+1; $i++){ $_LANG['val']['year']["$i"] = $i; }


$_LANG['val']['category']	=	array('1'=>'New', '2'=>'Used' );
$_LANG['val']['door'] 		=	array('0'=>'0 Door','1'=>'1 Door', '2'=>'2 Doors', '3'=>'3 Doors', '4'=>'4 Doors', '5'=>'5 Doors' );
$_LANG['val']['seat'] 		=	array('1'=>'1 Seat', '2'=>'2 Seats', '3'=>'3 Seats', '4'=>'4 Seats', '5'=>'5 Seats', '6'=>'6 Seats', '7'=>'7 Seats', '8'=>'8 Seats', '9'=>'9 Seats', '10'=>'10 Seats', '11'=>'11 Seats' );
$_LANG['val']['pattern'] =	array('1'=>'Sedan', '2'=>'Coupe', '3'=>'Hatch', 
								  '4'=>'Wagon', '5'=>'SUV', '6'=>'Convertible', '7'=>'Pickup', '8'=>'Bike',
								   '9'=>'Commercial' );
$_LANG['val']['transmission']	=	array('1'=>'Manual', '2'=>'Automatic' );
$_LANG['val']['min_price'] = array('-1'=>'Any', '1'=>'1', '2500'=>'2.5k', '5000'=>'5k', '7500'=>'7.5k', '10000'=>'10k',
							 	'15000'=>'15k', '20000'=>'20k', '25000'=>'25k', '30000'=>'30k', '35000'=>'35k',
							 	'40000'=>'40k', '45000'=>'45k', '50000'=>'50k', '60000'=>'60k', '70000'=>'70k',
							 	'80000'=>'80k', '90000'=>'90k', '100000'=>'100k', '150000'=>'150k', 
							 	'200000'=>'200k', '250000'=>'250k', '300000'=>'300k');
$_LANG['val']['max_price'] = array('2500'=>'2.5k', '5000'=>'5k', '7500'=>'7.5k', '10000'=>'10k',
							 	'15000'=>'15k', '20000'=>'20k', '25000'=>'25k', '30000'=>'30k', '35000'=>'35k',
							 	'40000'=>'40k', '45000'=>'45k', '50000'=>'50k', '60000'=>'60k', '70000'=>'70k',
							 	'80000'=>'80k', '90000'=>'90k', '100000'=>'100k', '150000'=>'150k', 
							 	'250000'=>'250k', '300000'=>'300k', '350000'=>'350k', '400000'=>'400k',
							 	'450000'=>'450k', '500000'=>'500k', ''=>'500k+');


?>
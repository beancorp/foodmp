<?php
// Titles
$_LANG['tt']['itemname']		=	'Property Name';
$_LANG['tt']['category']		=	'Type of Sale';
$_LANG['tt']['property']		=	'Type of Property';
$_LANG['tt']['bedroom']			=	'Bedrooms';
$_LANG['tt']['bathroom']		=	'Bathrooms';
$_LANG['tt']['carspaces']		=	'Parking';
$_LANG['tt']['inspect']			=	'Open House';
$_LANG['tt']['auction']			=	'Auction';
$_LANG['tt']['price']			=	'Price';
$_LANG['tt']['priceMethod']		=	'Price Method';
$_LANG['tt']['negotiable']		=	'Negotiable';
$_LANG['tt']['council']			=	'Council';
$_LANG['tt']['water']			=	'Water';
$_LANG['tt']['strata']			=	'Strata';
$_LANG['tt']['state']			=	'State';
$_LANG['tt']['priceNote']		=	'(per quarter)';
$_LANG['tt']['suburb']			=	'Suburb';
$_LANG['tt']['postcode']		=	'Postcode';
$_LANG['tt']['street']			=	'Street Address';
$_LANG['tt']['location']		=	'Location';
$_LANG['tt']['content']			=	'Description';
$_LANG['tt']['featureList']		=	'Feature List';
//$_LANG['tt']['featured']		=	'Featured';
$_LANG['tt']['featured']		=	'<strong align="left">Display on<br /> your<br /> homepage</strong>';
$_LANG['tt']['enabled']			=	'Published';
$_LANG['tt']['deleted']			=	'Deleted';
$_LANG['tt']['dateAdd']			=	'Date Added';
$_LANG['tt']['dateExpired']		=	'Expired Date';

$_LANG['tt']['contactPerson']	=	'Contact Person';
$_LANG['tt']['Outgoings']		=	'Outgoings';
$_LANG['tt']['address']			=	'Address';


$_LANG['labelCoagent']		=	'Co-Agent';
$_LANG['labelName']			=	'Name';
$_LANG['labelAddress']		=	'Address';
$_LANG['labelPhone']		=	'Phone';

//value
for ($i=date('Y')-20; $i<=date('Y')+1; $i++){ $_LANG['val']['year']["$i"] = $i; }

$_LANG['val']['category'] 	= 	array('1'=>'For Sale', '2'=>'For Rent', '3'=>'Shared', '5'=>'POA', '6'=>'Expressions of Interest', '4'=>'Auction');
$_LANG['val']['property']	=	array('8'=>'Commercial','1'=>'Holiday House', '2'=>'House','3'=>'Land',
									'4'=>'Retirement', '6'=>'Townhouse', '7' => 'Unit', '5'=>'Villa'  );
$_LANG['val']['bedroom'] 	=	array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6' => '6+' );
$_LANG['val']['bathroom'] 	=	array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6' => '6+' );
$_LANG['val']['carspaces'] 	=	array('0'=>'0','1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6' => '6+' );
$_LANG['val']['min_price'] 	=	array('-1'=>'Any','1'=>'1', '50000'=>'50k', '100000'=>'100k', '150000'=>'150k', '200000'=>'200k',
							 	'250000'=>'250k', '300000'=>'300k', '350000'=>'350k', '400000'=>'400k', '450000'=>'450k', 
							 	'500000'=>'500k', '550000'=>'550k', '600000'=>'600k', '650000'=>'650k', '700000'=>'700k', 
							 	'750000'=>'750k', '800000'=>'800k', '850000'=>'850k', '900000'=>'900k', '950000'=>'950k', 
							 	'1000000'=>'1m', '1250000'=>'1.25m', '1500000'=>'1.5m', '1750000'=>'1.75m', '2000000'=>'2m', 
							 	'2250000'=>'2.25m', '2500000'=>'2.5m', '2750000'=>'2.75m', '3000000'=>'3m', 
							 	'4000000'=>'4m', '5000000'=>'5m', '6000000'=>'6m');
$_LANG['val']['max_price'] 	=	array('50000'=>'50k', '100000'=>'100k', '150000'=>'150k', '200000'=>'200k',
							 	'250000'=>'250k', '300000'=>'300k', '350000'=>'350k', '400000'=>'400k', '450000'=>'450k', 
							 	'500000'=>'500k', '550000'=>'550k', '600000'=>'600k', '650000'=>'650k', '700000'=>'700k', 
							 	'750000'=>'750k', '800000'=>'800k', '850000'=>'850k', '900000'=>'900k', '950000'=>'950k', 
							 	'1000000'=>'1m', '1250000'=>'1.25m', '1500000'=>'1.5m', '1750000'=>'1.75m', '2000000'=>'2m', 
							 	'2250000'=>'2.25m', '2500000'=>'2.5m', '2750000'=>'2.75m', '3000000'=>'3m', 
							 	'4000000'=>'4m', '5000000'=>'5m', '6000000'=>'6m', ''=>'6m+' );

$_LANG['val']['priceMethod']=	array('1' => 'Per Week', '2' => 'Per Month');

?>

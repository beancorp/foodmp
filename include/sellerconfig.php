<?php

//chars limit or The SOC Exchange
$templateSet	=	array(
	'product-display'	=>	array(
		'maxChars'	=>	0,
		'product'	=>	1,
		'productCol'=>	1,
		'isSingerPro'=>	1,
	),
	'tmp-n-a'	=>	array(
		'maxChars'	=>	0,
		'product'	=>	4,
		'productCol'=>	1,
		'isSingerPro'=>	0,
	),
	'tmp-n-b'	=>	array(
		'maxChars'	=>	0,
		'product'	=>	6,
		'productCol'=>	1,
		'isSingerPro'=>	0,
	),
	'tmp-n-e'	=>	array(
		'maxChars'	=>	0,
		'product'	=>	1,
		'productCol'=>	1,
		'isSingerPro'=>	1,
	),
	'estate-a'	=>	array(
		'product'	=>	6,
		'productCol'=>	1
	),
	'estate-b'	=>	array(
		'product'	=>	6,
		'productCol'=>	1
	),
	'estate-c'	=>	array(
		'product'	=>	1,
		'productCol'=>	1
	),
	'auto-a'	=>	array(
		'product'	=>	5,
		'productCol'=>	1
	),
	'auto-b'	=>	array(
		'product'	=>	6,
		'productCol'=>	1
	),
	'auto-c'	=>	array(
		'product'	=>	1,
		'productCol'=>	1
	),
	'job-a'	=>	array(
		'product'	=>	6,
		'productCol'=>	1
	),
	'job-b'	=>	array(
		'product'	=>	6,
		'productCol'=>	1
	),
	'job-c'	=>	array(
		'product'	=>	1,
		'productCol'=>	1
	),
);

$product_csv = array(
'productcode'=>'p_code',
'itemname'=>'item_name',
'price'=>'price',
'unit'=>'unit',
'quantity'=>'stockQuantity',
'ono'=>'non',
'onsale'=>'on_sale',
'shippingcost'=>'postage',
'shippingmethod'=>'deliveryMethod',
'mainimage'=>'image_name',
'image1'=>'moreImage1',
'image2'=>'moreImage2',
'image3'=>'moreImage3',
'image4'=>'moreImage4',
'image5'=>'moreImage5',
'image6'=>'moreImage6',
'description'=>'description',
'homeitem'=>'isfeatured',
'allowshippingoverseas'=>'isoversea',
'shippingcostoverseas'=>'oversea_postage',
'shippingmethodoverseas'=>'oversea_deliveryMethod',
'youtubevideo'=>'youtubevideo',
);

$freelisting_csv = array(
'website_name'=>'bu_name',
'url_string'=>'bu_urlstring',
'nickname'=>'bu_nickname',
'attribute'=>'attribute',
'sub_attrib'=>'subAttrib',
'state'=>'bu_state',
'suburb'=>'bu_suburb',
'address'=>'bu_address',
'postcode'=>'bu_postcode',
'phone'=>'bu_phone',
'mobile'=>'mobile',
);

$auto_csv = array(
'#'=>'#',
'title'=>'item_name',
'new/used'=>'category',
'type'=>'carType',
'make'=>'make',
'model'=>'model',
'year'=>'year',
'kms'=>'kms',
'door'=>'door',
'seat'=>'seat',
'vechicltype'=>'pattern',
'transmission'=>'transmission',
'colour'=>'color',
'regno'=>'regNo',
'regexpiry'=>'regExpired', //2008-12-17
'price'=>'price',
'streetaddress'=>'location',
'state'=>'state', //2008-12-17
'suburb'=>'suburb',
'postcode'=>'postcode',
'negotiable'=>'negotiable',
'description'=>'content',
'featurelist'=>'featureList',
'homeitem'=>'featured',
'published'=>'enabled',
'mainimage'=>'image_name',
'image1'=>'moreImage1',
'image2'=>'moreImage2',
'image3'=>'moreImage3',
'image4'=>'moreImage4',
'image5'=>'moreImage5',
'image6'=>'moreImage6',
'youtubevideo'=>'youtubevideo',
);

$estate_csv = array(
'#'=>'#',
'propertyname'=>'item_name',
'typeofsale'=>'category',
'typeofproperty'=>'property',
'bedrooms'=>'bedroom',
'bathrooms'=>'bathroom',
'parking'=>'carspaces',
'openhouse'=>'inspect',
'auction'=>'auction',
'price'=>'price',
'pricemethod'=>'priceMethod',
'negotiable'=>'negotiable',
'council'=>'council',
'water'=>'water',
'strata'=>'strata',
'streetaddress'=>'location',
'state'=>'state', //2008-12-17
'suburb'=>'suburb',
'postcode'=>'postcode',
'description'=>'content',
'featurelist'=>'featureList',
'homeitem'=>'featured',
'published'=>'enabled',
'sold'=>'solded',
'coagentname'=>'coname',
'coagentaddress'=>'coaddress',
'coagentphone'=>'cophone',
'mainimage'=>'image_name',
'image1'=>'moreImage1',
'image2'=>'moreImage2',
'image3'=>'moreImage3',
'image4'=>'moreImage4',
'image5'=>'moreImage5',
'image6'=>'moreImage6',
'planpicture'=>'moreImage7',
'youtubevideo'=>'youtubevideo'
);


$blog_length = 200;
ini_set('auto_detect_line_endings','1');

$multi_msg = array(
		0=>'Product information or product images is missing.',
		1=>'File format is invalid.',
		2=>'Products file error;',#cannot open csv
		3=>'Duplicate item name.',
		4=>'Item name is required.',
		5=>'Description is required.',
		6=>'Price is invalid.',
		7=>'Shipping Cost is invalid.',
		8=>'Category is invalid.',
		9=>'Subcategory is invalid.',
		10=>'Category or subcategory is invalid.',
		11=>'Upload file larger than 70M.',
		12=>'Type is invalid.',
		13=>'Make is invalid.',
		14=>'Duplicate Vehicle Title.',
		15=>'Vehicle Title is required.',
		16=>'Shipping Cost(overseas) is invalid.',
		17=>'Warning: an unexpected error has occurred. Please check your files and try again or contact us.',
		/**this message for estate**/
		18=>'Type of Sale is invalid.',
		19=>'Type of Property is invalid.',
		20=>'State is invalid.',
		21=>'Suburb is invalid.',
		22=>'Street Address is required.',
		23=>'Zipcode is required.',
		24=>'Duplicate Property Name.',
		25=>'Email already exists.',
		26=>'website_name, url_string, nickname is required.',
		27=>'Products file error;',#cannot open csv
		28=>'Free Listing information is missing.',
		29=>'website_name already exists.',
		30=>'url_string already exists.',
		31=>'nickname already exists.',
		32=>'sub_attrib is not exists.',
);
?>
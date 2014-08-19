<?php

$first_date = date('01-m-Y');

echo $first_date;
echo "<br>";

$time_int = strtotime($first_date);


echo date("H:i:s d/m/Y", $time_int);


echo "<br>--------<br><br>";
echo $time_int;


echo "<br>--------<br><br>";
echo mktime(23, 59,59,  date('m'),date('t'), date('Y'));


echo "<br>--------<br><br>";


echo mktime(0, 0,0, date('m'), 1, date('Y'));













die;


/*

	$str = "&lt;p&gt;&lt;span style=&quot;font-size: xx-large; &quot;&gt;&lt;strong&gt;Food retailers open your website NOW and start building your fan count.&lt;/strong&gt;&lt;/span&gt;&lt;span style=&quot;font-size: x-large; &quot;&gt;&lt;strong&gt;&lt;br /&gt;
&lt;/strong&gt;&lt;/span&gt;&lt;span style=&quot;font-size: large; &quot;&gt;&lt;i&gt;&lt;br /&gt;
&lt;/i&gt;&lt;/span&gt;&lt;span style=&quot;font-size: x-large; &quot;&gt;&lt;i&gt;Transform your business and take it online for just $1 a day, plus have some fun along the way!&lt;br /&gt;
&lt;/i&gt;&lt;/span&gt;&lt;br /&gt;
Fruit Markets, Bakeries, Butchers, Deli's, Fish shops, Liquor stores, Cafes, Juice Bars, Fast Food, Grocery &amp;amp; Convenience stores, Restaurants, Pubs &amp;amp; Bars, it's time to do business the smart way!&lt;br /&gt;
&lt;br /&gt;
Simply get your customers, family and friends to become your fans by clicking the (Star image) icon on your FoodMarketplace website.&lt;br /&gt;
&lt;br /&gt;
$20,000 CASH will go to the food retailer with the most fans as at ipm on Wednesday 12th November, 2014. Someone will get the cash, why not you?&lt;/p&gt;
&lt;p&gt;&amp;nbsp;&lt;/p&gt;
&lt;p&gt;&lt;font class=&quot;Apple-style-span&quot; size=&quot;1&quot;&gt;&lt;u&gt;&lt;br /&gt;
&lt;/u&gt;&lt;/font&gt;&lt;/p&gt;
&lt;p&gt;&amp;nbsp;&lt;/p&gt;
&lt;p&gt;&lt;u style=&quot;font-size: x-small; &quot;&gt;Harris Farm Markets Edgecliff&lt;/u&gt;&lt;u style=&quot;font-size: x-small; &quot;&gt; i&lt;/u&gt;&lt;u style=&quot;font-size: x-small; &quot;&gt;s not eligible to participate in this promotion&lt;br /&gt;
&lt;/u&gt;&lt;span style=&quot;font-size: x-small; &quot;&gt;&lt;br /&gt;
&lt;u&gt;See Terms &amp;amp; Conditions&lt;/u&gt;&lt;br type=&quot;_moz&quot; /&gt;
&lt;/span&gt;&lt;/p&gt;";
	
	echo html_entity_decode($str);
	*/


include_once ('include/config.php');
@session_start();
include_once ('include/smartyconfig.php');
include_once ('maininc.php');
include_once ('class.emailClass.php');
include_once ('class.soc.php');
include_once ('class.phpmailer.php');
include_once ('class.socbid.php');
include_once ('class.socreview.php');
include_once ('functions.php');
include_once ('class.page.php');
include_once ('class/pagerclass.php');
include_once ('class.uploadImages.php');
include_once ('class.adminhelp.php');
include_once ('class.adminjokes.php');
include_once ('class.wishlist.php');
include_once ('class/ajax.php');
require_once('class.socstore.php');
require_once('textmagic/TextMagicAPI.php');
require_once('EwayPayment.php');
require_once('class.team.php');


$password = crypt("123456",getSalt());
echo $password;



//var_dump(file_exists("test.jpg"));
/*
$inFile = "C:/xampp/htdocs/foodmp/test.jpg";
$outFile = "C:/xampp/htdocs/foodmp/uploads/test-cropped.jpg";
$image = new Imagick($inFile);
$image->cropImage(400,400, 30,10);
$image->writeImage($outFile);
?>
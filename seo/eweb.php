<?php

define("URL", "http://dev.infinitytech.cn/php/buyblitz/");

switch ($_SERVER['SCRIPT_NAME']){
	
	default:
		$title = '';
		$kws ='';
	
	break;
	
	case "/php/buyblitz/index.php":
		$title = 'Online Grocery - Online Grocery Shopping - Online Shopping Mall';
		$kws ='Online Grocery, Online Grocery Shopping, Online Shopping Mall, Shopping Directory California, Grocery California, Grocery Store, Grocery Store California, Grocery Store Beverly Hills, Night Club Beverly Hills';
		
	break;
	
	case "/php/buyblitz/productDispay.php":
		$title = '';
		$kws ='';
		
	break;
	
	case "/php/buyblitz/statepage.php":
		$state = getStateDescByName($_REQUEST['state']);
		
		$title = "Grocery ".$state." - Bakeries ".$state." - Deli ".$state." | Buyblitz Shopping Directory ".$state."";
		$desc = "Buy Blitz Shopping Directory ".$state." find: Grocery ".$state.", Bakeries ".$state.", Deli ".$state."";
		$kws ="Grocery ".$state.", Bakeries ".$state.", Deli ".$state.", Shopping Directory ".$state.", Seafood Shop ".$state.", Night Club ".$state.", Fruit Market ".$state.", Butcher ".$state.", Bars ".$state.", Grocery Delivery ".$state.", Take Away Restaurants ".$state."";
		
	break;
	
		case "/php/buyblitz/searchByCategory.php":
		$state = getStateDescByName($_REQUEST['state']);
		$selectSubburb	=	StrReplace($_REQUEST[selectSubburb]);
		$categoryID	=	$_REQUEST['catID'];
		
		$sql = "SELECT * FROM ".$table."bu_category WHERE id=".$categoryID."";
		$dbcon->execute_query($sql);
		$cat = $dbcon->fetch_records();
	
		//will need to do switch for each cat
		$title = "".$cat[0]['Cat_Name']." ".$state." ".$selectSubburb."";
		$desc = "";
		$kws ="";
		
	break;
	
	}
	
	$kww = explode(",", trim($kws));
?>
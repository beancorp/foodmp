<?php
ini_set('display_errors', 0);
@session_start();

include_once ('../include/config.php');
include_once ('../include/smartyconfig.php');
include_once ('functions.inc.php');
include_once ('maininc.php');
include_once ('class.soc.php');

include_once('../languages/'.LANGCODE.'/soc.php');
include_once('../languages/'.LANGCODE.'/foodwine/index.php');

/*
if ($_SESSION['UserID']==''){
    header("Location:index.php");
}
*/

$current_user_id = $_SESSION['StoreID'];

if (!empty($_GET['image'])) {
	$photo_query = "SELECT photo.*,			
						retailer.*,
						
						aus_soc_state.stateName AS stateAbbreviation,
						aus_soc_state.description AS state,
						COUNT(fans.fan_id) As fan_count 
					FROM photo_promo photo
					LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id
					LEFT JOIN aus_soc_state  ON aus_soc_state.id = photo.state_id
					
					
					LEFT JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
					WHERE photo.photo_id = '".$_GET['image']."'";
	
	$photo = $dbcon->getOne($photo_query);
       
	if(!$_SESSION['isAdmin']){  
        if (!$photo["photo_id"] || (!$photo["approved"] && $current_user_id != $photo["consumer_id"])   || $photo["approved"] == 2 ){
            header('location:'. SOC_HTTP_HOST . 'soc.php?cp=error404');
            exit();
        }      
    }else{  
        if (!$photo["photo_id"]){
            header('location:'. SOC_HTTP_HOST . 'soc.php?cp=error404');
            exit();
        }  
    }
	                         
	if($_SESSION['isAdmin'] || ($current_user_id == $photo["consumer_id"])){ 
        $smarty->assign('editable', true);    
    } 
    
	$consumer_query = "SELECT * FROM aus_soc_bu_detail WHERE StoreID = {$photo["consumer_id"]}";
	$consumer = $dbcon->getOne($consumer_query);
	$photo["consumer_info"] = $consumer;
	
	
	
	//if have no storeId but there is name of Retailer, have to reselect 
	if (!$photo.store_id  && $photo.retailer_name && $photo["state_id"] > 0){
		$retailer_query = "SELECT * FROM aus_soc_bu_detail WHERE bu_name = '{$photo['retailer_name']}' AND bu_state =  {$photo['state_id']}";
		$retailer_info = $dbcon->getOne($retailer_query);
		if ($retailer_info){
			$photo["bu_suburb"] = $retailer_info["bu_suburb"];
		}	
	}
	
	
	
	
	
	
	
	$default_store_images = array(
			1 => 'restaurants.jpg', // Restaurents
			2 => 'liquorstores.jpg', // Liquor 
			3 => 'bakerygrocery.jpg', // Bakery 
			4 => 'seafood.jpg', // Seafood
			5 => 'meatdeli.jpg', // Meat
			6 => 'fruitsvegetables.jpg', // Fruitveg
			7 => 'pubsbars.jpg', // Bar, pubs
			8 => 'fastfood.jpg', // Fast food
			9 => 'cafes.jpg', // Cafe
			10 => 'juicebars.jpg' // Juice
		);
	
	$objUI = new uploadImages();
	if ($photo["store_id"]){
		$val['images'] = $objUI->getDisplayImage('', $photo['store_id'], 0, -1, -1, 6);
		$image2 = $val['images']['mainImage'][2]['bname']['text'];
		$val['images']['mainImage'][2]['bname']['text'] = $image2 == '/images/243x100.jpg' ? '/images/79x79.jpg' : $image2;
		$val['store_logo'] = $objUI->getDefaultImage($val['images']['mainImage'][2]['bname']['text'], true, 6, 4, 15);
		$val['store_logo_big'] = $objUI->getDefaultImage($val['images']['mainImage'][2]['bname']['text'], true, 6, 4, 9);
		$val['store_search_result_logo'] = $objUI->getDefaultImage($val['images']['mainImage'][5]['bname']['text'], true, 6, 4, 15);
		$val['store_search_result_logo_big'] = $objUI->getDefaultImage($val['images']['mainImage'][5]['bname']['text'], true, 6, 4, 9);
		$val['website_name'] = clean_url_name($photo['bu_urlstring']);
		$val['website_url'] = $photo['bu_website'];
		$val['default_store_image'] = IMAGES_URL.'/foodwine/category_icon/default/'.$default_store_images[$photo['subAttrib']];
		$photo["retailer_info"] = $val;
	}	
	
	
	$photo['photo_date'] = date('d M Y', strtotime($photo['timestamp']));
	
	
	//brand image
	$tmp = explode("/", $photo["image"]);
	$photo["brand_image"] = "uploads/{$photo["consumer_id"]}/brand_".end($tmp);
	
	
	
	$default_profile = '/images/no_profile_pic.jpg';
	
	$profile_pic = '/profile_images/'.$photo['consumer_id'].'.jpg';
	
	if (file_exists('../'.$profile_pic)) { 
		$smarty->assign('profile_picture', $profile_pic);
	} else {
		$smarty->assign('profile_picture', $default_profile);
	}
	include_once ('fanpromo/functions.inc.php');
    $smarty -> assign('add_retailer', tab_content($dbcon, 11));
    
	$smarty->assign('photo', $photo);
	
	$smarty->assign('grand_info_nominee', tab_content($dbcon, 8));
	
	
	$smarty->assign('current_user_id', $current_user_id);
	
	
	
	
	
	
	
	$grand_date_open = tab_content_by_key_name($dbcon, "grand-date-open");  //vote grand start
	$grand_time_open = strtotime($grand_date_open);
	$grand_date_close = tab_content_by_key_name($dbcon, "grand-date-close"); //vote grand end
	$grand_time_close = strtotime("23:59:59 ". $grand_date_close);
	$vote_on_off = tab_content_by_key_name($dbcon, "vote-on-off");   //vote on-off
	$grand_trigger = tab_content_by_key_name($dbcon, "grand-trigger");   //vote trigger
	
	
	$grand_final_date = tab_content_by_key_name($dbcon, "grand-final-date");   //grand-final-date
	$grand_final_time= strtotime("23:59:59 ". $grand_final_date);
	
	
	
	$current_time = time();
	
	/*
	echo "<pre>";
	var_dump($grand_date_open);
	var_dump($grand_time_open);
	var_dump($grand_date_close);
	var_dump($grand_time_close);
	var_dump($vote_on_off);
	var_dump($grand_trigger);
	var_dump($current_time);
	echo "</pre>";
	*/
	
	if ($current_time <= $grand_final_time  && $vote_on_off == 1 && $photo["grand_final"] <> 1){
			$display_vote_enable = 1;
	}
	
	if ($current_time >= $grand_time_open && $current_time <= $grand_time_close && $vote_on_off == 1 && $grand_trigger == 1 && $photo["grand_final"] == 1){
			$display_vote_enable = 1;
	}

	$smarty->loadLangFile('/index'); 
    $smarty->assign('vote_enabled', $display_vote_enable);
	$smarty->assign('fanpromo_view_page', true);
	
	
	$smarty->assign('share_description', "To 'Become a 'Fan' of my photo in the FoodMarketplace $1,000,000 Fan Frenzy, click here");
	$smarty->assign('share_title', "'Fan' my photo in the chase for $1,000,000 CASH");
	$smarty->assign('share_image', SOC_HTTP_HOST . "fanpromo/".$photo["brand_image"]);
	$smarty->assign('share_url', SOC_HTTP_HOST . "photo_".$photo["photo_id"]. ".html");
	
	display_page($dbcon, $smarty, 'view.tpl', 'Fan Promo - View Detail', $_LANG);
}


?>
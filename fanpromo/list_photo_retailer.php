<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
@session_start();

include_once ('../include/config.php');
include_once ('../include/smartyconfig.php');
include_once ('functions.inc.php');
include_once ('maininc.php');
include_once ('class.soc.php');

include_once('../languages/'.LANGCODE.'/soc.php');
include_once('../languages/'.LANGCODE.'/foodwine/index.php');




function limit_text($text, $limit) {
	if (str_word_count($text, 0) > $limit) {
		$words = str_word_count($text, 2);
		$pos = array_keys($words);
		$text = substr($text, 0, $pos[$limit]) . '...';
	}
	return $text;
}

$smarty->assign('fanfrenzy_text', tab_content($dbcon, 4));

//get Turn On Off Progress Bar
$smarty->assign('on_off_progress_bar', tab_content_by_key_name($dbcon, "progress-bar"));


//get States
$states = getStateArray();
$smarty->assign('states', $states);
//get Category
$smarty -> loadLangFile('/index');

if (isset($_REQUEST['retailer_id']) && trim($_REQUEST['retailer_id']) != ""){
	$sql = "SELECT * FROM  aus_soc_bu_detail WHERE StoreID = '".trim($_REQUEST['retailer_id'])."'";
	$res = $dbcon->getOne($sql,true);
}elseif (isset($_REQUEST['website_url']) && trim($_REQUEST['website_url']) != ""){
	$sql = "SELECT * FROM  aus_soc_bu_detail WHERE bu_urlstring = '".trim($_REQUEST['website_url'])."'";
	$res = $dbcon->getOne($sql,true); 
}

if ($res["StoreID"] > 0){
	$smarty->assign('retailer_id', $res["StoreID"]);
	$smarty->assign('retailer', $res);		
	$retailer_id = $res["StoreID"];
	$_REQUEST["retailer_id"] = $retailer_id;
	
}else{
	die("not exxits");
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
		
$default_logo = array(
			1 => 'default_restaurants.jpg', // Restaurents
			8 => 'default_fastfood.jpg',
			0 => 'default_logo.jpg',
		);

$tpl_query	= "select * from aus_soc_template_details where StoreID='$retailer_id'";
$tpl = $dbcon->getOne($tpl_query,true);
$tpl_type = $tpl["tpl_type"];
	
$objUI = new uploadImages();


if ($tpl_type > 1){
	$val['images'] = $objUI->getDisplayImage('', $retailer_id, 0, -1, -1, $tpl_type);

	if ($res["attribute"] == 5){ //foodwine
		$logo =  $val['images']['mainImage'][2]['bname']['text'];
	}else{
		$logo =  $val['images']['mainImage'][0]['bname']['text'];
	}
	$val['store_logo'] = $objUI->getDefaultImage($val['images']['mainImage'][2]['bname']['text'], true, 6, 4, 15);
	$val['store_logo_big'] = $objUI->getDefaultImage($val['images']['mainImage'][2]['bname']['text'], true, 6, 4, 9);
	$val['store_search_result_logo'] = $objUI->getDefaultImage($val['images']['mainImage'][5]['bname']['text'], true, 6, 4, 15);
	$val['store_search_result_logo_big'] = $objUI->getDefaultImage($val['images']['mainImage'][5]['bname']['text'], true, 6, 4, 9);
}else{
	$logo = $tpl["MainImg"];
}



if (strpos($logo, "upload")  === false ){
	if ($res["subAttrib"] == 1 || $res["subAttrib"] == 8){
		$val['default_logo'] = SOC_HTTP_HOST. 'template_images/'.$default_logo[$res["subAttrib"]];	
	}else{
		$val['default_logo'] = SOC_HTTP_HOST. 'template_images/'.$default_logo[0];
	}
}else{
	$val['default_logo'] = SOC_HTTP_HOST. $logo;
}



$retailer_info = $val;
$smarty->assign('retailer_info', $retailer_info);



/*
if (strpos($val['images']['mainImage'][2]['bname']['text'], "upload")  === false){
	
}else{
	$logo_retailer = $retailer_info["store_logo"]["text"];
}
*/

$logo_retailer = $val['default_logo'];

$smarty->assign('logo_retailer', $logo_retailer);




 



function view_photos($retailer_id) {	
		global $dbcon;
		$search_criteria = " AND (photo.store_id = $retailer_id )";
		
		
		$search_sort = '';
		if (isset($_POST['search_sort'])) {
			switch ($_POST['search_sort']) {
				case 1:
					$search_sort = ' DESC';
					break;
				case 2:
					$search_sort = ' ASC';
					break;
			}
		}
		
		$sql = "SELECT COUNT(*) AS count FROM (
					SELECT photo.*, consumer.bu_name As consumer, retailer.bu_name As retailer, COUNT(fans.fan_id) As fan_count FROM photo_promo photo
					LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id 
					LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
					INNER JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
					WHERE 1
					AND photo.approved = 1
					".((!empty($search_criteria)) ? $search_criteria : "")."
					GROUP BY photo.photo_id ORDER BY fan_count ".((!empty($search_sort)) ? $search_sort : 'DESC').", photo.timestamp DESC)  AS tmp WHERE 1";
		
    	$res = $dbcon->getOne($sql);
    	
    	$perPage = 18;	   
		$pageno	= empty($_REQUEST['p']) ? 1 :$_REQUEST['p'];
    	$count = $res['count'];
    	
    	
    	
		
		$start	= ($pageno-1) * $perPage;		
		$end = $start + $perPage;
		
		$total_page = ceil($count/$perPage);
		
		if ($count > 0){
			$sql = "SELECT photo.*, consumer.bu_name As consumer, retailer.bu_urlstring As retailer_url, retailer.bu_name As retailer, COUNT(fans.fan_id) As fan_count,
					(SELECT MAX(fan_id) FROM photo_promo_fan WHERE photo_id = photo.photo_id) AS last_fan_id  
					FROM photo_promo photo
					LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id 
					LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
					INNER JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
					WHERE 1
					AND photo.approved = 1
					".((!empty($search_criteria)) ? $search_criteria : "")."
					GROUP BY photo.photo_id ORDER BY fan_count ".((!empty($search_sort)) ? $search_sort : 'DESC').", last_fan_id ASC, photo.timestamp ASC LIMIT $start, $perPage";		
			$dbcon->execute_query($sql);
			$res = $dbcon->fetch_records(true);
		}
		
		if ($res) {
			foreach ($res as $key => $val) {
				$val['rank'] = $perPage * ($pageno - 1) + $key + 1;
				$res[$key] = $val;
			}	
		}
		
		$last_p = ($pageno - 1) > 0 ? ($pageno - 1) : 0;
		$next_p = ($pageno * $perPage < $count) ? ($pageno + 1) : 0;
		$info = array(
			'last_p' => $last_p,
			'next_p' => $next_p,
			'list' => $res
		);
	
	if (is_array($info['list']) && $count > 0) {
		
		echo '<div class="block-gallery-list tab-gallery block-gallery active">';
		
		$i = 1;
		
		foreach ($info['list'] as $photo) {
			
			
			if (($i % 3) == 1){
				echo '<div class="item-row">';
			}
		
			$date_uploaded = date('d.m.Y', strtotime($photo['timestamp']));
			echo '<div class="promo_thumb">';
			
			if (empty($search_criteria)) {				
				if ($photo['rank'] >= 1 && $photo['rank'] <= 3) {					
					echo '<div class="place_image place_'.$photo['rank'].'">&nbsp;</div>';
				}
			}
			
			echo '<a class="fan_photo" href="/photo_'.$photo['photo_id'].'.html"><img src="/fanpromo/'.$photo['thumb'].'" /></a>';
			
			if(!empty($photo['retailer'])){   
                $retailer_name = limit_text($photo['retailer'], 4);    
            }else{  
                $retailer_name = limit_text($photo['retailer_name'], 4);
            }
			
			echo '<div class="block-left">';			
			echo '<div class="name-user">'.$photo['consumer'].'</div>';
            if(!empty($photo['retailer_url'])){
			    echo '<div class="content"><a href="'.SOC_HTTP_HOST.$photo['retailer_url'].'">'.$retailer_name.'</a></div>';
            }else{
                echo '<div class="content">'.$retailer_name.'</div>';   
            }
			echo '<span>'.$date_uploaded.'</span>';
			echo '</div>';			//end div block-left
			
			echo '<div class="block-right">
                	<div class="fans">'.$photo["fan_count"].' Fans</div>
              	</div>';
			echo '</div>';
			
			if (($i % 3) == 0 || $i == count($info["list"])){
				echo '<div class="clear"></div>';
				echo '</div>'; //end div item-row
			}
			
			$i++;
			
		}
		
		echo '<div class="clear"></div>
				<div id="block-ft">
					<div class="btn-enter btn-enter-list"><a href="/entry">Enter Competition</a></div>';
		
		if ($total_page > 1){
			echo '<div class = "number-page">';
			if (!empty($info['last_p'])) {						
				echo '<span class="txt-next" id="list_prev" >< Previous</span>&nbsp&nbsp';
			}
			for($i = 1; $i<=$total_page; $i++){
				echo '<span rel="'.$i.'" class="search_list ';
				if ($pageno == $i) echo ' active ';
				echo '">'.$i.'</span>&nbsp&nbsp';
			}
			
			
			if (!empty($info['next_p'])) {						
				echo '<span class="txt-next" id="list_next" ><a href="javascript:void(0)">Next ></a></span>';
			}
			echo '</div>'; //end div number-page
		}
		echo '</div>'; //end div block-ft
		echo '</div>'; //end div block-gallery
	} else {
		echo '<div id="search_message">There are no photos to display that meet this search criteria.</div>';
	}
	echo '<script>
		
				function search_listing(page_number) {
					var retailer_id = $("#retailer_id").val();
					$("#promo-page-content").html("<img src='. "'/images/loading.gif' alt='Loading.......'>".'");
					
					$.ajax({
						url: "/fanpromo/list_photo_retailer.php",
						type: "POST",
						data: {action: "list", p: page_number, retailer_id: retailer_id} 					
					}).done(function(data) {
						$("#promo-page-content").html(data);
						if ($("#search_sort").val() == 2) {
							$(".place_image").hide();
						}
					});
				}
				
				$(document).ready(function() {
					$("#list_prev").click(function() {
						search_listing('.$info['last_p'].');
					});
					$("#list_next").click(function() {
						search_listing('.$info['next_p'].');
					});
					
					$(".search_list").click(function() {					
						search_listing($(this).attr("rel"));
					});
					
				});
			</script>';
	
	
	
	
	
	
	
}

if (isset($_POST['action'])) {
	
	switch ($_POST['action']) {
		case 'list':
			view_photos($_REQUEST["retailer_id"]);
			break;
	}
	exit;
}


display_page($dbcon, $smarty, 'list_photo_retailer.tpl', 'Fan Promo - List', $_LANG);
?>
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


//calculate timeOffset between time server and  GMT time
$date = new DateTime();
$timeOffset = $date->getOffset();
$smarty->assign('time_server_offset', $timeOffset);


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


//check if in grand final
$grand_final_date = tab_content_by_key_name($dbcon, "grand-final-date");
if (!$grand_final_date)
	$grand_final_date = "12/31/2014";

$grand_final_time = strtotime($grand_final_date);



if (time() >= $grand_final_time){
	$grand_final_flag = 1;
	$_SESSION["grand_final_flag"] = 1;
}else{
	$_SESSION["grand_final_flag"] = 0;
	$grand_final_flag = 0;
}
$smarty->assign('grand_final_flag', $grand_final_flag);//in grand final time

//count down to Grand Finale Date.   The Grand Finale Date start from 00h:00m:00s of the date
//get month , day , year of Grand OPen
$tmp = explode("/", $grand_final_date);


$smarty->assign('grand_month', (int)$tmp[0] - 1);
$smarty->assign('grand_day', $tmp[1]);
$smarty->assign('grand_year', $tmp[2]);

$smarty->assign('grand_tab', (isset($_REQUEST["grand_tab"]) && $_REQUEST["grand_tab"] > 0) ? 1 : 0);





function view_photos($grand_final = false) {
	
		if ($_SESSION["grand_final_flag"] == 1){
			$grand_final = true;
		}
		global $dbcon;
		
		$search_criteria = '';	
		if (!empty($_POST['search_name'])) {
			$search_criteria = " AND ((retailer.bu_name LIKE '%".$_POST['search_name']."%') OR (consumer.bu_name LIKE '%".$_POST['search_name']."%') OR (photo.retailer_name LIKE '%".$_POST['search_name']."%') OR (photo.description LIKE '%".$_POST['search_name']."%') OR (photo.unique_id LIKE '%".$_POST['search_name']."%'))";
		}
		
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
		if ($grand_final){
			$search_grandfinal = " AND photo.grand_final = 1";
		}else{
			$search_grandfinal = " AND photo.grand_final <> 1";	
		}
		
		
		$search_categories = '';
		if (!empty($_POST['search_categories'])) {
			$search_categories = " AND (retailer.subAttrib = '".$_POST['search_categories']."' OR photo.category_id = '".$_POST['search_categories'] . "') ";
		}
		
		$search_locations = '';
		if (!empty($_POST['search_locations'])) {
			$search_locations = " AND photo.state_id = '".$_POST['search_locations']."'";
		}
		
		
		$sql = "SELECT COUNT(*) AS count FROM (
					SELECT photo.*, consumer.bu_name As consumer, retailer.bu_name As retailer, COUNT(fans.fan_id) As fan_count FROM photo_promo photo
					LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id 
					LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
					LEFT JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
					WHERE 1
					AND photo.approved = 1
					".((!empty($search_grandfinal)) ? $search_grandfinal : "")."
					".((!empty($search_criteria)) ? $search_criteria : "")."
					".((!empty($search_categories)) ? $search_categories : "")."
					".((!empty($search_locations)) ? $search_locations : "")."
					GROUP BY photo.photo_id ORDER BY fan_count ".((!empty($search_sort)) ? $search_sort : 'DESC').", photo.timestamp DESC)  AS tmp WHERE 1";
		
    	$res = $dbcon->getOne($sql);
    	
    	$perPage = 18;	   
		$pageno	= empty($_REQUEST['p']) ? 1 :$_REQUEST['p'];		
		
    	$count = $res['count'];
    	
    	//echo "total: $count";
		
		//$pageno = ($pageno * $perPage > $count)? ceil($count/$perPage) : 1;
		
		$start	= ($pageno-1) * $perPage;		
		$end = $start + $perPage;
		
		$total_page = ceil($count/$perPage);
		
		if ($count > 0){
			$sql = "SELECT photo.*, consumer.bu_name As consumer, retailer.bu_urlstring As retailer_url, retailer.bu_name As retailer, COUNT(fans.fan_id) As fan_count,
					(SELECT MAX(fan_id) FROM photo_promo_fan WHERE photo_id = photo.photo_id) AS last_fan_id  
					FROM photo_promo photo
					LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id 
					LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
					LEFT JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
					WHERE 1
					AND photo.approved = 1
					".((!empty($search_grandfinal)) ? $search_grandfinal : "")."
					".((!empty($search_criteria)) ? $search_criteria : "")."
					".((!empty($search_categories)) ? $search_categories : "")."
					".((!empty($search_locations)) ? $search_locations : "")."
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
		
			
            if(LANGCODE == 'en-us'){
                $date_uploaded = date('F d, Y', strtotime($photo['timestamp']));
            }else{
                $date_uploaded = date('d.m.Y', strtotime($photo['timestamp']));     
            }
			echo '<div class="promo_thumb">';
			
			if (empty($search_criteria)) {				
				if ($photo['rank'] >= 1 && $photo['rank'] <= 3) {					
					echo '<div class="place_image place_'.$photo['rank'].'">&nbsp;</div>';
				}else{
					echo '<span class="place_image place_ranking">'.$photo['rank'].'<sup>th</sup></span>';
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
					var search_locations = $("#retailer_location").val();
					var search_name = $("#search_name").val();
					var search_sort = $("#search_sort").val();
					var search_categories = $("#search_categories").val();
					var page_type = $("#promo_page_type").val();
					
					$("#promo-page-content").html("<img src='. "'/images/loading.gif' alt='Loading.......'>".'");
					
					$.ajax({
						url: "/fanpromo/list.php",
						type: "POST",
						data: {action: "list", p: page_number, page_type: page_type, search_name: search_name, search_sort: search_sort, search_categories: search_categories, search_locations: search_locations} 					
					}).done(function(data) {
						$("#promo-page-content").html(data);
						if ($("#search_sort").val() == 2) {
							$(".place_image").hide();
						}
					});
				}
				
				$(document).ready(function() {
				
					$("body").on("click", "#list_prev", function(e) {
					 	e.preventDefault();
						search_listing('.$info['last_p'].');
					});
					
					$("body").on("click", "#list_next", function(e) {
					 	e.preventDefault();
						search_listing('.$info['next_p'].');
					});
					
					$("body").on("click", ".search_list", function(e) {
					 	e.preventDefault();
						search_listing($(this).attr("rel"));
					});
					
				});
			</script>';
	
	
	
	
	
	
	
}

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case 'list':
			
			if (isset($_POST['page_type'])) {
				switch($_POST['page_type']) {
					case 1: // normal
						view_photos();
						break;
						
					case 2: //grand nominee
						view_photos(true);
						break;
				}
			}
			
			break;
	}
	exit;
}

$sql = "SELECT COUNT(detail.StoreID) As retailer_count FROM aus_soc_bu_detail AS detail 
		INNER JOIN aus_soc_login as lg on lg.StoreID = detail.StoreID 
		WHERE detail.CustomerType = 'seller' AND detail.attribute = '5' 
		AND NOT(detail.bu_name IS NULL) 
		AND detail.renewalDate >= UNIX_TIMESTAMP(NOW())
		AND detail.status = 1;";
					
$res = $dbcon->getOne($sql);

$retailer_count = $res['retailer_count'];

$grand_trigger = tab_content_by_key_name($dbcon, "grand-trigger");

if ($grand_trigger == 1){
	$retailer_count = 3000;	
}


$retailer_goal = tab_content_by_key_name($dbcon, "retailer-number");
//$retailer_goal = 3000;

$bar_size = 840;
$one_percent = ($bar_size / 100);

$bar_scale = (($retailer_count / $retailer_goal) * 100);

if ($bar_scale < 1) {
	$bar_scale = 1;
}

$pixels = ($bar_scale * $one_percent);
$smarty->assign('bar_pixels', $pixels);
$smarty->assign('retailer_count', $retailer_count);
$smarty->assign('retailer_goal', $retailer_goal);

//get Date For Grand Open







display_page($dbcon, $smarty, 'list.tpl', 'Fan Promo - List', $_LANG);
?>
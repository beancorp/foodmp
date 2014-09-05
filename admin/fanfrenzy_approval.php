<?php
session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');
include_once('config.php'); 


require_once 'Pager/Pager.php';
include_once ('xajax/xajax_core/xajax.inc.php');

$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}
$dbcon = $GLOBALS['dbcon'];

$req = array();

$error_message = '';

if (isset($_GET['delete'])) {
	$delete_sql = "DELETE FROM photo_promo WHERE photo_id = '".$_GET['delete']."'";
	$dbcon->execute_query($delete_sql);
	$error_message .= '<div class="error_message">Photo has been removed.</div>';
}

if (isset($_GET['grand_final'])) {
	$finalist_sql = "UPDATE photo_promo SET grand_final = 1 WHERE photo_id = '".$_GET['grand_final']."'";
	$dbcon->execute_query($finalist_sql);
    if(CURRENCYCODE == 'AUD'){
        $error_message .= '<div class="error_message" style="color: #00FF00;">Photo has been selected for Grand Final.</div>'; 
    }else{
        $error_message .= '<div class="error_message" style="color: #00FF00;">Photo has been selected for Fan Frenzy Final.</div>';  
    }
}

$sql = "SELECT COUNT(*) AS count FROM (
					SELECT photo.*, consumer.bu_name As consumer, retailer.bu_name As retailer, COUNT(fans.fan_id) As fan_count FROM photo_promo photo
					LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id 
					LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
					INNER JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
					WHERE photo.approved = 0
					GROUP BY photo.photo_id ORDER BY fan_count DESC, photo.timestamp DESC)  AS tmp WHERE 1";
					
					
    	$res = $dbcon->getOne($sql);
    	
    	$perPage = 9;	   
		$pageno	= empty($_REQUEST['p']) ? 1 :$_REQUEST['p'];
    	$count = $res['count'];
		
		$pageno = ($pageno * $perPage > $count)? ceil($count/$perPage) : 1;
		
		$start	= ($pageno-1) * $perPage;		
		$end = $start + $perPage;
		
		$sql = "SELECT photo.*, consumer.bu_name As consumer, retailer.bu_name As retailer, COUNT(fans.fan_id) As fan_count FROM photo_promo photo
				LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id 
				LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
				INNER JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
				WHERE photo.approved = 0
				GROUP BY photo.photo_id ORDER BY fan_count DESC, photo.timestamp ASC LIMIT $start, $perPage";
		
		$dbcon->execute_query($sql);
		$res = $dbcon->fetch_records(true);
		
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
		
	$output = '';
	$output .= $error_message;
	if (isset($info['list'])) {
		foreach ($info['list'] as $photo) {
		
			$date_uploaded = date('d.m.Y', strtotime($photo['timestamp']));
			$output .= '<div class="promo_thumb_row">';
			$output .= '<div class="promo_thumb">';
			
			if ($photo['rank'] >= 1 && $photo['rank'] <= 3) {
				$output .= '<div class="place_image place_'.$photo['rank'].'">&nbsp;</div>';
			}
			$output .= '<a class="fan_photo" href="/photo_'.$photo['photo_id'].'.html" target="_blank"><img width="200px" src="/fanpromo/'.$photo['thumb'].'" /></a>';
			
			
			$retailer_name = $photo['retailer'];
			
			
			$output .= '<div class="fan_text">
			'.$photo['consumer'].' <br />
			'.$retailer_name.' <br />
			'.$date_uploaded.'			
			</div>
			<div class="fan_count">'.$photo['fan_count'].' Fans</div>
			
			</div>';
			
			$output .= '<a class="approve_link" href="/admin/?act=photos&approve='.$photo['photo_id'].'">Approve</a>';
			
			$output .= '</div>';
		}
		
		
		$output .= '<div id="list_pagination">';
		
		if (!empty($info['last_p'])) {						
			$output .= '<div id="list_prev">< Previous</div>';
		}
		
		if (!empty($info['next_p'])) {						
			$output .= '<div id="list_next">Next ></div>';
		}
		
		$output .= '</div>';
	
		$output .= '<script>
				function load_list(page) {
					$.ajax({
						url: "/admin/?act=photos",
						type: "POST",
						data: {action: "list", p: page} 					
					}).done(function(data) {
						$("#photos_listing").html(data);
					});
				}
				$(document).ready(function() {
					$("#list_prev").click(function() {
						load_list('.$info['last_p'].');
					});
					$("#list_next").click(function() {
						load_list('.$info['next_p'].');
					});
				});
			</script>';
	}
	
if (isset($_POST['action']) && $_POST['action'] == 'list') {
	echo $output;
	exit();
}
	
$smarty->assign('listing', $output);
$content = $smarty -> fetch('admin_photos.tpl');
$smarty -> assign('content', $content);


$smarty->assign('req',	$req);

unset($objAdminMain,$req,$xajax);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
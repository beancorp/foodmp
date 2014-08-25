<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');


require_once 'Pager/Pager.php';
include_once ('xajax/xajax_core/xajax.inc.php');


include_once ('../fanpromo/functions.inc.php');

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

if (isset($_POST['change_status_photo']) && $_POST['change_status_photo'] == 1) {
	$sql = "UPDATE photo_promo SET approved =". $_REQUEST['status_value'] . " WHERE photo_id = '".$_REQUEST['photo_id']."'";
	$rs = $dbcon->execute_query($sql);
	
	
	if ($_POST['status_value'] == 1){
			$email_template =  get_email_template_by_key_name($dbcon, "email_approved");
	}
	
	if ($_POST['status_value'] == 2){
			$email_template =  get_email_template_by_key_name($dbcon, "email_reject");
	}
	$mail_template = file_get_contents(ROOT_DIRECTORY. "skin/email_template/template_mail_{$email_template["id"]}.txt");
	
	
	
	$photo_info = getFranzyPhoto($dbcon, $_REQUEST['photo_id']);
	$consumer_info = getStoreInfoByStoreId($dbcon, $photo_info["consumer_id"]);
	
	//$message = str_replace("##username##", nl2br($consumer_info['bu_nickname']), $email_template["content"]); //Replace
	$message = str_replace("##username##", nl2br($consumer_info['bu_nickname']), $mail_template); //Replace
	$arr["mail_to"] = $consumer_info['bu_email'];
	$arr['subject'] = "Information about your entry Fan Frenzy";
	$arr["content"] = $message;
	
	insert_queue_mail($dbcon, $arr);
	
	
	/*
	$email = $_SESSION["email"];				
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: FoodMarketplace <no-reply@'.$emaildomain.'>' . "\r\n";
	$message = 'Hi '.ucwords($_SESSION["NickName"]) ;
	
	$sql = "SELECT aus_soc_login.* FROM aus_soc_login 
				LEFT JOIN photo_promo ON photo_promo.store_id =  aus_soc_login.StoreID 
				WHERE photo_promo.photo_id = {$_REQUEST['photo_id']}";
	if ($_POST['status_value'] == 1){
		$message .= "<br/><br/>Your photo is approved";
	}
	if ($_POST['status_value'] == 2){
		$message .= "<br/><br/>Your photo is rejected";
	}
	
	$message .= "<br /><br /> Kind regards, <br /> FoodMarketplace.";
	mail($email, 'Email Verification', $message, $headers);
	*/
	
	
	
	
	exit();
}


if (isset($_GET['grand_final'])) {
	$finalist_sql = "UPDATE photo_promo SET grand_final = 1 WHERE photo_id = '".$_GET['grand_final']."'";
	$dbcon->execute_query($finalist_sql);
	$error_message .= '<div class="error_message" style="color: #00FF00;">Photo has been selected for grand final.</div>';
}


switch ($_GET["photo_status"]){
	case 1:
		$status_display = 1;  //show approved photo 
		$smarty->assign('status_text', "approved");
		break;
	case 2:
		$status_display = 2; //show rejected photo
		$smarty->assign('status_text', "rejected");
		break;
	case 0:
		$status_display = 0; //show pending photo
		$smarty->assign('status_text', "pending");
		break;
	default:
		$status_display = 3; //show pending photo
		$smarty->assign('status_text', "");
		break;
}
$smarty->assign('photo_status', $status_display);



$search_criteria = '';	
if (!empty($_GET['search_text'])) {
	$search_criteria = " AND (photo.unique_id LIKE '%".$_GET['search_text']."%')";
	$smarty->assign('search_text', $_GET['search_text']);
}

$status_criteria = "";
if ($status_display == 1 || $status_display == 2 || $status_display === 0){
	$status_criteria = " AND photo.approved = {$status_display}";
}




$sql = "SELECT COUNT(*) AS count FROM (
					SELECT photo.*, consumer.bu_name As consumer, retailer.bu_name As retailer, COUNT(fans.fan_id) As fan_count FROM photo_promo photo
					LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id 
					LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
					LEFT JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
 					WHERE 1 
 					".((!empty($search_criteria)) ? $search_criteria : "")."
 					".((!empty($status_criteria)) ? $status_criteria : "")."
					GROUP BY photo.photo_id ORDER BY fan_count DESC, photo.timestamp DESC)  AS tmp WHERE 1";
					
					
    	$res = $dbcon->getOne($sql);
    	
    	$perPage = 3;	   
		$pageno	= empty($_GET['p']) ? 1 :$_GET['p'];
    	$count = $res['count'];
    	$smarty->assign('total_photo',$count);
    	
		
		//$pageno = ($pageno * $perPage > $count)? ceil($count/$perPage) : 1;
		
		$start	= ($pageno-1) * $perPage;		
		$end = $start + $perPage;
		
		
		/*
		$sql = "SELECT photo.*, consumer.bu_name As consumer, retailer.bu_name As retailer, COUNT(fans.fan_id) As fan_count FROM photo_promo photo
				LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id 
				LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
				INNER JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
				GROUP BY photo.photo_id ORDER BY fan_count DESC, photo.timestamp ASC LIMIT $start, $perPage";
		*/
		
		$sql = "SELECT photo.*, consumer.bu_name As consumer, retailer.bu_name As retailer, COUNT(fans.fan_id) As fan_count, 
						promo_store_codes.code AS code
				FROM photo_promo photo
				LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id 
				LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
				LEFT JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
				LEFT JOIN promo_store_codes ON promo_store_codes.store_id = photo.store_id
				WHERE 1
				".((!empty($search_criteria)) ? $search_criteria : "")."
 				".((!empty($status_criteria)) ? $status_criteria : "")."
				GROUP BY photo.photo_id ORDER BY photo.photo_id DESC, photo.timestamp ASC LIMIT $start, $perPage";
		
		
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
			$photo["time_uploaded"] = date('H:i d/m/Y', strtotime($photo['timestamp']));
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
			
			$output .= '<a class="remove_link" href="/admin/?act=photos&delete='.$photo['photo_id'].'">Remove</a>&nbsp;';
			
			$output .= '<a class="make_finalist" href="/admin/?act=photos&grand_final='.$photo['photo_id'].'">Make Finalist</a>';
			
			$output .= '</div>';
			
			$photos[] = $photo;
		}	
		
		$output .= '<div id="list_pagination">';
		
		if (!empty($info['last_p'])) {
			
			$smarty->assign('page_previous', $last_p);
			$output .= '<div id="list_prev">< Previous</div>';
		}
		
		if (!empty($info['next_p'])) {
			$smarty->assign('page_next', $next_p);
			$output .= '<div id="list_next">Next ></div>';
		}
		$smarty->assign('photo_status', $status_display);
		
		
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
                                
$smarty->assign('photo_status_value', array(0,1,2));
$smarty->assign('photo_status_label', array('-Select Status-','Approved','Rejected'));
$smarty->assign('photos', $photos);
$smarty->assign('listing', $output);
$content = $smarty -> fetch('admin_frenzy_photos.tpl');
$smarty -> assign('content', $content);


$smarty->assign('req',	$req);

unset($objAdminMain,$req,$xajax);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');

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


// SENT mail to user
if (isset($_POST['sent_mail']) && $_POST['sent_mail'] == 1) {
	//insert to Grand Table
	/*
	$insert_array = array("photo_id" => $_REQUEST['photo_id'],
							"time_winner" => time(), 
	  						"is_sent_email" => 0, 
							"sort_number" => $_REQUEST['sort_number'],
							);	
	insert_grand_table($dbcon, $insert_array);
	*/
	
	//sent mail to user
	switch ($_REQUEST['sort_number']){
		case 1:
			$email_template =  get_email_template_by_key_name($dbcon, "email_to_top1");
			break;
		case 2:
			$email_template =  get_email_template_by_key_name($dbcon, "email_to_top2");
			break;
		case 3:
			$email_template =  get_email_template_by_key_name($dbcon, "email_to_top3");
			break;
		default:
			 $email_template =  get_email_template_by_key_name($dbcon, "email_to_top97");
			break;
		
	}
	
	$mail_template = file_get_contents(ROOT_DIRECTORY. "skin/email_template/template_mail_{$email_template["id"]}.txt");
	
	
	//$message = str_replace("##username##", nl2br($_REQUEST['consumer_name']), $email_template["content"]); //Replace
	$message = str_replace("##username##", nl2br($_REQUEST['consumer_name']), $mail_template); //get from TXT file, not DB
	
	$arr["mail_to"] = $_REQUEST['consumer_email'];
	$arr['subject'] = "Congratulation";
	
	
	
	$arr["content"] = $message;
	
	insert_queue_mail($dbcon, $arr);
	
	
	//update
	$array_update['is_sent_email'] = 1;
	update_promo_grand_list($dbcon, $array_update, $_REQUEST['photo_id']);
	exit();
}	


switch ($_GET["status"]){
	case 1:
		$status = 1;  //show top 100 of this month
		$smarty->assign('status_text', "Top 100 OF This Month");
		break;
	case 2:
		$status = 2; //show full list
		$smarty->assign('status_text', "Full List");
		break;
	case 0:
	default:
		$status = 1; //get Top 100 of this month
		$smarty->assign('status_text', "Top 100 OF This Month");
		break;
}

$pageno	= empty($_GET['p']) ? 1 :$_GET['p'];


if ($status == 2){
	//Full List Grand
	$sql = "SELECT COUNT(*) AS count FROM promo_grand_list";
	$res = $dbcon->getOne($sql);
    	
    $perPage = 100;
	$count = $res['count'];
    $smarty->assign('total_photo_grand',$count);
    
    
	$start	= ($pageno-1) * $perPage;		
	$end = $start + $perPage;
	
	
	$sql = "SELECT photo.*, consumer.bu_name As consumer, retailer.bu_name As retailer, 
				promo_grand_list.is_sent_email,promo_grand_list.sort_number,	promo_grand_list.time_winner AS time_winner,	
				
				consumer.bu_email AS consumer_email,
				
				COUNT(fans.fan_id) As fan_count FROM photo_promo photo
				LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id 
				LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
				LEFT JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
				INNER JOIN promo_grand_list  ON promo_grand_list.photo_id = photo.photo_id
				WHERE photo.grand_final = 1
				GROUP BY photo.photo_id ORDER BY promo_grand_list.sort_number ASC, photo.timestamp ASC LIMIT $start, $perPage";
	
	$dbcon->execute_query($sql);
	$res = $dbcon->fetch_records(true);
	
	$last_p = ($pageno - 1) > 0 ? ($pageno - 1) : 0;
	$next_p = ($pageno * $perPage < $count) ? ($pageno + 1) : 0;
	
	$info = array(
			'last_p' => $last_p,
			'next_p' => $next_p,
			'list' => $res
	);
	
	
	
	if (!empty($info['last_p'])) {
		$smarty->assign('page_previous', $last_p);
	}	
	
	if (!empty($info['next_p'])) {
			$smarty->assign('page_next', $next_p);
	}
	
	
	
}

if ($status == 1){
	$pageno = 1;
	
	
	//default select 100 in month (current ly 10) 
	$last_day_of_this_month = mktime(23, 59,59,  date('m'),date('t'), date('Y'));
	$first_day_of_this_month = mktime(0, 0,0, date('m'), 1, date('Y'));
	
	$sql = "SELECT promo_grand_list.count_vote As fan_count,promo_grand_list.sort_number,
					(SELECT MAX(fan_id) FROM photo_promo_fan WHERE photo_id = photo.photo_id) AS last_fan_id ,
					photo.*, consumer.bu_name As consumer, consumer.bu_email AS consumer_email,
					promo_grand_list.is_sent_email, promo_grand_list.time_winner AS time_winner,	
					retailer.bu_urlstring As retailer_url, retailer.bu_name As retailer
						FROM photo_promo photo
						LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id 
						LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
						LEFT JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
						INNER JOIN promo_grand_list  ON promo_grand_list.photo_id = photo.photo_id
						WHERE 1
						AND promo_grand_list.time_winner  BETWEEN {$first_day_of_this_month} AND {$last_day_of_this_month}
						GROUP BY photo.photo_id ORDER BY fan_count DESC, last_fan_id ASC,  photo.timestamp ASC LIMIT 0, 100";
	$dbcon->execute_query($sql);
	$res = $dbcon->fetch_records(true);
}



$i = 1; 
foreach ($res as $row){
	
	$row["sort"] = $i * ($pageno -1) ;
	$row["time_uploaded"] = date('H:i d/m/Y', strtotime($row['timestamp']));
	
	$row["win_month"] = date('m/Y', $row['time_winner']);
	 
	$photos[] = $row;
	$i++;
}




$smarty->assign('status', $status);

                                
$smarty->assign('photo_status_value', array(0,1,2));
$smarty->assign('photo_status_label', array('-Select Status-','Approved','Rejected'));
$smarty->assign('photos', $photos);
$smarty->assign('listing', $output);
$content = $smarty -> fetch('fanfrenzy_grand_list.tpl');
$smarty -> assign('content', $content);


$smarty->assign('req',	$req);

unset($objAdminMain,$req,$xajax);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
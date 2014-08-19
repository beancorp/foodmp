<?php
//ini_set('display_errors', '1');
//error_reporting(E_ALL);
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include('../indexPart.php');
$indexTemplate = '../index.tpl';
include_once(SOC_INCLUDE_PATH . '/class.cart.php');
include_once(SOC_INCLUDE_PATH . '/class.FoodWine.php');
include_once(SOC_INCLUDE_PATH . '/class.guestEmailSubscriber.php');
include_once(SOC_INCLUDE_PATH . '/class.upload.php');
include_once(SOC_INCLUDE_PATH . '/class.uploadImages.php');
require_once(SOC_INCLUDE_PATH . '/html2pdf/html2pdf.class.php');


//Check Login
if ($_SESSION['UserID'] == '' && $_SESSION['level'] != 1) {
    header("Location:/soc.php?cp=login");
}

$StoreID = $_SESSION['StoreID'];
$foodWine = new FoodWine();
$guestSub = new guestEmailSubscriber();
$smarty->assign('dontshowpromo', 1);

switch ($_REQUEST["cp"]) {

    case 'list':

        $emailalerts = $foodWine->getEmailAlertsList($_SESSION['StoreID']);
        $emailalertsGuest = $guestSub->getGuestSubscriberListByStore($_SESSION['StoreID']);
        $req = $socObj->getStoreInfo($StoreID);
        $req = array_merge($req, $_REQUEST);
        $smarty->assign('req', $req);
        $smarty->assign('emailalerts', $emailalerts);
        $smarty->assign('emailalertsGuest', $emailalertsGuest);
        $smarty->assign('sidebar', 0);
        $pageTitle = 'Past Email Alerts';
        $smarty->assign('pageTitle', 'Sell Goods Online - Selling Online - Past Email Alerts');
        $smarty->assign('keywords', 'Past Email Alerts');
        $content = $smarty->fetch('emailalerts_list.tpl');
        $smarty->assign('content', $content);

        break;
	
	case 'pdf':
		$eid = intval($_REQUEST['eid']);
        $send_type = $_REQUEST['type'];
		
        $data = $foodWine->getEmailAlertsInfo($eid, $StoreID);
		
		//echo var_export($data);
		
        $req = $socObj->displayStoreWebside(true);
		$req['hotbuy_message'] = $data['hotbuy_message'];
        $req['products'] = $foodWine->getProductsList($StoreID, '', '', '', false, '', $data['pid_ary'], '', 1);
        $req['header_img'] = $foodWine->getEmailAlertsHeaderImg($StoreID, $send_type);
        $req['bgcolor'] = $send_type == 'specials' ? '#d8d9db' : '#f5f5f5';
        $req['info']['id'] = $eid;
        $req['info']['send_type'] = $send_type;
        $req['info']['start_date'] = date('d F Y', $data['start_date']);
        $req['info']['end_date'] = date('d F Y', $data['end_date']);
        $smarty->assign('req', $req);
		
		
		$smarty->assign('hide_template', true);
		$smarty->assign('hide_responsive', true);
		
		//$content = $smarty->fetch("email_alert_pdf.tpl");
        //$smarty->assign('content', $content);
		
		try {
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->writeHTML($smarty->fetch("email_alert_pdf.tpl"));
			$html2pdf->Output('email_alert.pdf');
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
		break;
		
    case 'preview':

        $eid = $_POST['eid'];
        $pid_ary = $_POST['pid'];
        $send_type = $_REQUEST['type'] ? $_REQUEST['type'] : '';

        $data = $foodWine->checkEmailAlertsData($_POST, $eid > 0 ? true : false);
        if (false == $data['status']) {
            header('Location: /foodwine/?act=emailalerts&msg=' . implode("\n\r", $data['msg']));
            exit();
        }
		
		if (!empty($_POST['flyerimage_svalue'])) {
			$objUI = new uploadImages();
			$arrImage = array('simage' => $_POST['flyerimage_svalue'], 'bimage' => $_POST['flyerimage_bvalue'], 'sort' => 0);
			if ($objUI->saveImageToDB($arrImage, $_SESSION['StoreID'], 0, 0, 7)) {
				$query = "SELECT * FROM aus_soc_image WHERE StoreID = ".$_SESSION['StoreID']." AND tpl_type = 7";
				if ($dbcon->execute_query($query)) {
					$result = $dbcon->fetch_records(true);
					if (count($result) > 0) {
						$image = $result[0]['picture'];
						$update_query = "UPDATE aus_soc_template_details SET emailalert_image='".$image."' WHERE StoreID = '".$_SESSION['StoreID']."'";
						$dbcon->execute_query($update_query);
					}
				}
			}
		}
				

        $eid = $foodWine->saveEmailAlerts($data['data'], $eid);
        $req = $socObj->displayStoreWebside(true);
		$req['hotbuy_message'] = $data['data']['hotbuy_message'];
        $req['products'] = $foodWine->getProductsList($StoreID, '', '', '', false, '', $pid_ary, '', 1);
        $req['header_img'] = $foodWine->getEmailAlertsHeaderImg($StoreID, $send_type);
        $req['bgcolor'] = $send_type == 'specials' ? '#d8d9db' : '#f5f5f5';
        $req['info']['id'] = $eid;
        $req['info']['send_type'] = $send_type;
        $req['info']['start_date'] = date('d F Y', $data['data']['start_date']);
        $req['info']['end_date'] = date('d F Y', $data['data']['end_date']);
        $smarty->assign('req', $req);
        $pageTitle = 'Preview Email Alerts';
        $smarty->assign('pageTitle', 'Sell Goods Online - Selling Online - Preview Email Alerts');
        $smarty->assign('keywords', 'Preview Email Alerts');
		$smarty->assign('preview', true);
        $content = $smarty->fetch("email_alert_preview.tpl");
        $smarty->assign('content', $content);
        $smarty->assign('sidebar', 0);

        break;
    case 'save':
        $data = $foodWine->checkEmailAlertsData($_POST, $_POST['eid'] > 0 ? true : false);
        if (false == $data['status']) {
            header('Location: /foodwine/?act=emailalerts&msg=' . implode("\n\r", $data['msg']));
            exit;
        }

        $foodWine->saveEmailAlerts($data['data'], $_POST['eid']);

        header('Location: /foodwine/?act=emailalerts&msg=Email Alerts Saved successfully.');
        exit;
        break;
    case 'send':

        $eid = intval($_REQUEST['eid']);
        $send_type = $_REQUEST['type'];
        if (empty($eid)) {
            header("Location:../soc.php?cp=inbox&msg=Please come frome the currect url.");
            exit();
        }
		
        $data = $foodWine->getEmailAlertsInfo($eid, $StoreID);
        $foodWine->saveEmailAlerts(array('send_status' => 1, 'send_date' => time()), $eid);
		
		$req = $socObj->displayStoreWebside(true);
		$req['hotbuy_message'] = $data['hotbuy_message'];
        $req['products'] = $foodWine->getProductsList($StoreID, '', '', '', false, '', $data['pid_ary'], '', 1);
        $req['header_img'] = $foodWine->getEmailAlertsHeaderImg($StoreID, $send_type);
        $req['bgcolor'] = $send_type == 'specials' ? '#d8d9db' : '#f5f5f5';
        $req['info']['id'] = $eid;
        $req['info']['send_type'] = $send_type;
        $req['info']['start_date'] = date('d F Y', $data['start_date']);
        $req['info']['end_date'] = date('d F Y', $data['end_date']);
        $smarty->assign('req', $req);
		
		$smarty->assign('hide_template', true);
		$smarty->assign('hide_responsive', true);
		
		try {
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->writeHTML($smarty->fetch("email_alert_pdf.tpl"));
			$pdf_filename = tempnam(SITE_ROOT."/upload/pdf/", "pdf").'.pdf';
			
			$html2pdf->Output($pdf_filename, 'F');
			
			$sql = "SELECT e.*, l.user FROM aus_soc_emailalert e, aus_soc_login l WHERE e.userid = l.id AND e.StoreID = '$StoreID'";
			$dbcon->execute_query($sql);
			$subscribers = $dbcon->fetch_records();
			$sub_num = count($subscribers);

			include_once(SOC_INCLUDE_PATH . '/class.guestEmailSubscriber.php');
			$guestSub = new guestEmailSubscriber();
			$subscribersGuests = $guestSub->getGuestSubscriberListByStore($StoreID);
			$sub_num = $sub_num + count($subscribersGuests);

			include_once(SOC_INCLUDE_PATH . '/class.phpmailer.php');

			$socObj = new socClass();
			
			$subject = $send_type == 'specials' ? "Specials from {$req['info']['bu_name']}" : "Hot Buy from {$req['info']['bu_name']}";
			$message_html_body = $smarty->fetch('email_alert_content.tpl');
			if ($subscribers) {

				foreach ($subscribers as $subscriber) {
					$mail = new PHPMailer();
					$mail->AddAddress($subscriber['user']);
					$mail->Subject = $subject;
					$mail->SetFrom("noreply@".EMAIL_DOMAIN, "FoodMarketplace");
					$mail->AddAttachment($pdf_filename, $send_type.'.pdf');
					$mail->MsgHTML($message_html_body);
					$mail_sent = $mail->Send();
					unset($mail);
				}
			}

			if ($subscribersGuests) {
				foreach ($subscribersGuests as $subscribersGuests) {
					$mail = new PHPMailer();
					$mail->AddAddress($subscribersGuests['email']);
					$mail->Subject = $subject;
					$mail->SetFrom("noreply@".EMAIL_DOMAIN, "FoodMarketplace");
					$mail->AddAttachment($pdf_filename, $send_type.'.pdf');
					$mail->MsgHTML($message_html_body);
					$mail_sent = $mail->Send();
					unset($mail);
				}
			}

			$msg = "You have sent your items to " . $sub_num . " subscribers.";

			if (!$subscribers && !$subscribersGuests) {
				$msg = 'Your have not subscribers.';
			}
			
			unlink($pdf_filename);
			
			header('Location: /foodwine/?act=emailalerts&msg=' . $msg);
			exit;
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			header('Location: /foodwine/?act=emailalerts&msg=' . $e);
		}
		
		//$msg = $foodWine->sendEmailAlert($StoreID, $res['type'], $res['pid_ary'], $eid);
		
        break;
    case 'viewsubscribers':
        $subscribers = $foodWine->getSubscribers($_SESSION['StoreID']);
        $subscribersGuest = $guestSub->getGuestSubscriberListByStore($_SESSION['StoreID']);

        $req = $socObj->getStoreInfo($StoreID);
        $req = array_merge($req, $_REQUEST);
        $smarty->assign('req', $req);
        $smarty->assign('subscribers', $subscribers);
        $smarty->assign('subscribersGuest', $subscribersGuest);
        $smarty->assign('sidebar', 0);
        $pageTitle = 'Subscribers';
        $smarty->assign('pageTitle', 'Sell Goods Online - Selling Online - Subscribers');
        $smarty->assign('keywords', 'Subscribers');
		
		
		if ($_SESSION['StoreID'] == '857271') {
			$fan_query = "SELECT detail.StoreID As StoreID, detail.bu_nickname As bu_nickname, detail.lastLogin As subscribe_date FROM aus_soc_fans fans INNER JOIN aus_soc_bu_detail detail ON detail.StoreID = fans.consumer_id  WHERE store_id = '857271' ORDER BY fans.fan_id ASC LIMIT 0, 5000";
			$dbcon->execute_query($fan_query);
			$fan_result = $dbcon->fetch_records();
			$smarty->assign('fan_result', $fan_result);
		}
		
		
        $content = $smarty->fetch('subscribers.tpl');
        $smarty->assign('content', $content);

        break;
    default:
        if ($_SESSION['UserID'] == '' AND $_SESSION['level'] != 2) {
            header("Location:index.php");
            exit();
        }
		

		$req = $socObj->displayStoreWebside(true);
		$req = array_merge($req, $socObj->getStoreInfo($StoreID));
        $req = array_merge($req, $_REQUEST);
        // $req = $socObj->getStoreInfo($StoreID);
        // $req = array_merge($req, $_REQUEST);
        $eid = $_REQUEST['eid'];
        if ($eid) {
			$subAttrib = $req['info']['subAttrib'];
            $req['info'] = $foodWine->getEmailAlertsInfo($eid, $StoreID);
			$req['info']['subAttrib'] = $subAttrib;
        }

        $foodWineType = getFoodWineType();
        $products = $foodWine->getProductsList($_SESSION['StoreID'], $foodWineType, '', '', '', '', '', '', 1);
        $req['has_calender'] = 1;
        $smarty->assign('req', $req);
        $smarty->assign('products', $products);
        $smarty->assign('sidebar', 0);
        $pageTitle = 'Email Alerts';
        $smarty->assign('pageTitle', 'Sell Goods Online - Selling Online - Email Alerts');
        $smarty->assign('keywords', 'Email Alerts');
        $content = $smarty->fetch('email_alert.tpl');
        $smarty->assign('content', $content);

        break;
}
?>

<?php
//ini_set('display_errors', '1');
//error_reporting(E_ALL);
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

if (isset($_SESSION['level'])) {

	if ($_SESSION['level'] == 2) {
		header("Location:/soc.php?cp=buyerhome");
	}

	if ($_SESSION['level'] == 1) {
		header("Location:/soc.php?cp=sellerhome");
	}

}

// Work out the email domain from the http host.
$emaildomain = substr(SOC_HTTP_HOST,strpos(SOC_HTTP_HOST,':')+3,-1);

$smarty->assign('securt_url',$securt_url);
$smarty->assign('normal_url',$normal_url);
$smarty->assign('soc_https_host',SOC_HTTPS_HOST);
$socObj = new socClass();

if (isset($_GET['confirmation'])) {
	$query = "SELECT * FROM aus_soc_bu_detail store INNER JOIN aus_soc_login login ON login.StoreID = store.StoreID WHERE store.StoreID = '".$_GET['confirmation']."' LIMIT 1";
	$result	= $dbcon->execute_query($query);
	$user_result = $dbcon->fetch_records();
	if (isset($user_result[0])) {
		$user_data = $user_result[0];
		$smarty->assign('name', $user_data['bu_name']);
		$smarty->assign('registration_sucessful', 'You have successfully registered. Just one more step until you can login. <br /><br /> A confirmation email has been sent to: ' . $user_data['user'] . '<br /><br />* Please check your \'Junk\' or \'Spam\' mailboxes for our email confirmation.');
		$smarty->assign('pageTitle','Sell Goods Online - Selling Online - Buying on FoodMarketplace');
		$smarty->assign('keywords','Buying on FoodMarketplace');
		$smarty->assign('req', $req);
		$smarty->assign('contentStyle', 'float: left;width: 100%!important;padding: 0px; margin: 0px;');
		$smarty->assign('sidebarContent', ' ');
		$content = $smarty -> fetch('signup.tpl');
		$smarty->assign('hideLeftMenu', 1);
		$smarty->assign('show_left_cms', 0);
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
		$smarty -> display($template_tpl);
		unset($smarty);
	}
	die();
} else if (isset($_GET['verification'])) {
	$fetch_user = "SELECT * FROM aus_soc_login WHERE email_verification = '".$_GET['verification']."'";
	$dbcon->execute_query($fetch_user);
	$user_result = $dbcon->fetch_records();
	
	if (isset($user_result[0])) {
		$user_data = $user_result[0];
		
		if (isset($_POST['change_password'])) {
			if ((!empty($_POST['new_password'])) && (!empty($_POST['confirm_password']))) {
				if ($_POST['new_password'] == $_POST['confirm_password']) {

					// Encrypt the password using PHP's built in libraries:
					$password = crypt($_POST['new_password'],getSalt());

					$change_sql = "UPDATE aus_soc_login SET password = '".$password."' WHERE id = '".$user_data['id']."'";
					if ($dbcon->execute_query($change_sql)) {
						$smarty->assign('password_change_successful', 'Password Created. <a href="soc.php?cp=login" style="color:#FFF;">Click here</a> to login.');
						
						$subject = 'FoodMarketplace Login';
						$message = '
						Hi '.ucwords($_POST['name']).'<br /><br /> 
						Your account login details are
						Username: '.$user_result['user'].' <br />
						password: '.$_POST['new_password'].' <br /><br />
						Kind regards, <br />
						Food Marketplace. <br />
						'.SOC_HTTP_HOST;
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$headers .= 'From: Email Verification <no-reply@'.$emaildomain.'>' . "\r\n";
						mail($email, 'Email Verification', $message, $headers);
						
						$_SESSION['LOGIN'] = "login";
						$_SESSION['level'] = $user_data['level'];
						$_SESSION['Password'] =	$_POST['new_password'];
						$_SESSION['UserID']	= $user_data['id'];
						$_SESSION['ShopID']	= $user_data['StoreID'];
						$_SESSION['StoreID'] = $user_data['StoreID'];
						$_SESSION['email']	= $user_data['user'];
						
						header("Location:/soc.php?cp=buyerhome");
						exit;						
					} else {
						$smarty->assign('password_change_failed', 'No matching account found');
					}
				} else {
					$smarty->assign('password_change_failed', 'Passwords do not match');
				}
			}
		}
		
		if ($user_data['status'] == 0) {
			$update_status = "UPDATE aus_soc_login SET status = 1 WHERE id = '".$user_data['id']."'";
			$dbcon->execute_query($update_status);
		}
		
	} else {
		$smarty->assign('invalid_verification', 'Invalid Verification Code');
	}
	
	if (isset($user_data['status'])) {
		if ($user_data['status'] == 2) {
			$smarty->assign('sms_verified', true);
		}
	}
	
	$smarty->assign('verification', $_GET['verification']);
	$smarty->assign('pageTitle','Sell Goods Online - Selling Online - Buying on SOC Exchange');
	$smarty->assign('keywords','Buying on SOC Exchange');
	$smarty->assign('req', $req);
	$sidebarContent = $smarty -> fetch('cgeton_sidebar.tpl');
	$smarty->assign('contentStyle', 'float: left;width: 100%!important;padding: 0px; margin: 0px;');
	$smarty->assign('sidebarContent', $sidebarContent);
	$content = $smarty -> fetch('verification.tpl');
	$smarty->assign('hideLeftMenu', 1);
	$smarty->assign('show_left_cms', 0);
	$smarty->assign('content', $content);
	$smarty->assign('is_content',1);
	$smarty->display($template_tpl);
	
	unset($smarty);
} else {
	if (isset($_POST['submit_form'])) {
		
	
		$referral_enabled = false;
		if (!empty($_POST['referrer'])) {
			$referral_enabled = validateReferrer($_POST['referrer']);
		}
	
		$email_verification_code = sha1(uniqid(mt_rand(), true));
		$now = time();
		foreach($_POST as $key => $value) {
			if (!is_array($_POST[$key])) {
				$_POST[$key] = mysql_real_escape_string($value);
			}
		}
		
		
		$renewal_date = strtotime('+5 year', date());
		$insert_store_query = "INSERT INTO aus_soc_bu_detail SET bu_abn = '', contact_name = '".$_POST['name']."',
					bu_name = '".$_POST['nickname']."',
					product_feetype = 'product',
					bu_nickname = '".$_POST['nickname']."', bu_college = '',
					PayDate = '".$now."', renewalDate = '".$renewal_date."', launch_date = '".$now."', attribute = 4, subAttrib = 0, ispayfee = 0, sold_status = 0, 
					bu_colleges_ACN = '', bu_address = '',
					bu_country = 13, bu_state = '".$_POST['state']."', bu_suburb = '".$_POST['suburb']."', bu_postcode = '".$_POST['postcode']."',
					CustomerType = 'buyer', bu_email = '".$_POST['email']."', bu_username = '".$_POST['email']."', referrer = '".(($referral_enabled) ? $_POST['referrer'] : '')."', ref_name = '".getrefname()."'";
		
		if ($dbcon->execute_query($insert_store_query)) { 

			$storeID = $dbcon->lastInsertId();
			
			if (!empty($_POST['team'])) {
				TeamEmailInvite::accept($_POST['team'], $storeID);
			}

			// Sign up with Facebook - save the facebook ID.
			if (!empty($_POST['fb_id'])) {
				$insert_fb_id_sql = "INSERT INTO aus_soc_facebook SET `fb_id` = '".$_POST['fb_id']."', `storeId` = '$storeID', attribute='4';";
				$dbcon->execute_query($insert_fb_id_sql) or die($insert_fb_id_sql);
			}
			
			if ($referral_enabled) {
				if (!empty($_POST['referrer'])) {
					require_once(SOC_INCLUDE_PATH . '/class.referrer.php');
					$referrer = new Referrer();
					$referrer->addReferrerRecord('reg', $storeID);
				}
			}
			
			
			
			$insert_login_query = "INSERT INTO aus_soc_login SET StoreID = '".$storeID."', user = '".$_POST['email']."', password = '".$email_verification_code."', level = 2, attribute = 4, suspend = 0, email_verification = '".$email_verification_code."'";
			
			
			if ($dbcon->execute_query($insert_login_query)) {

				$email = $_POST['email'];
				$message = 'Hi '.ucwords($_POST['name']).' <br /><br /> Your account has been created. <br /> Please click or copy and paste the following link to verify your account: <br /> <a href="'.SOC_HTTPS_HOST.'signup.php?verification='.$email_verification_code.'">'.SOC_HTTP_HOST.'signup.php?verification='.$email_verification_code.'</a><br /><br /> Kind regards, <br /> FoodMarketplace.';
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: FoodMarketplace <no-reply@'.$emaildomain.'>' . "\r\n";
				mail($email, 'Email Verification', $message, $headers);
				
				header('location: signup.php?confirmation='.$storeID);
			}
		}
		
				
		
		
		$smarty->assign('name', $_POST['name']);
		$smarty->assign('nickname', $_POST['nickname']);
		$smarty->assign('state', $_POST['state']);
		$smarty->assign('suburb', $_POST['suburb']);
		$smarty->assign('postcode', $_POST['postcode']);
		$smarty->assign('email', $_POST['email']);
		$smarty->assign('mobile', $_POST['mobile']);
		$smarty->assign('form_submit', true);
	}
	
	if (isset($_GET['referrer'])) {
		$smarty->assign('referrer', $_GET['referrer']);
	}
	
	if (isset($_GET['team'])) {
		$smarty->assign('team', $_GET['team']);
	}

	$query	= "SELECT id, stateName, description FROM aus_soc_state ORDER BY description";
	$result	= $dbcon->execute_query($query);
	$state_list = $dbcon->fetch_records();
	$suburb_data = array();
	
	foreach($state_list as $state) {
		$query	= "SELECT suburb_id, suburb FROM aus_soc_suburb WHERE state = '".$state['stateName']."' ORDER BY suburb ASC";
		$result	= $dbcon->execute_query($query);
		$suburbs = $dbcon->fetch_records();
		$output = '';
		foreach($suburbs as $suburb) {
			$output .= '<option value="'.addslashes($suburb['suburb']).'">'.addslashes($suburb['suburb']).'</option>';
		}
		$suburb_data[$state['id']] = $output;
	}
	
	$smarty->assign('state_list', $state_list);
	$smarty->assign('suburb_data', $suburb_data);
	$smarty->assign('pageTitle','Sell Goods Online - Selling Online - Buying on FoodMarketplace');
	$smarty->assign('keywords','Buying on FoodMarketplace');
	$sidebarContent = $smarty -> fetch('cgeton_sidebar.tpl');
	$smarty->assign('contentStyle', 'float: left;width: 100%!important;padding: 0px; margin: 0px;');
	$smarty->assign('sidebarContent', $sidebarContent);
	$content = $smarty -> fetch('signup.tpl');
	$smarty->assign('hideLeftMenu', 1);
	$smarty->assign('show_left_cms', 0);
	$smarty -> assign('content', $content);
	$smarty->assign('is_content',1);
	$smarty->assign('ocp', 'signup');
	$smarty -> display($template_tpl);
	unset($smarty);
}
?>
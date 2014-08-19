<?php
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

$socObj = new socClass();
$socstoreObj = new socstoreClass();
//echo var_export($_SESSION);
$free_signup_query = "SELECT free_signup FROM aus_soc_bu_detail WHERE StoreID = '".$_SESSION['StoreID']."'";
$dbcon->execute_query($free_signup_query);
$free_signup_result = $dbcon->fetch_records();
$free_signup = false;

if (isset($free_signup_result[0])) {
	$free_signup_data = $free_signup_result[0];
	if ($free_signup_data['free_signup'] == 1) {
		$free_signup = true;
	}
}
if ($free_signup) {
	$smarty->assign('free_signup', $free_signup);
	if (isset($_SESSION['StoreID'])) {
		if (isset($_GET['step']) && is_numeric($_GET['step'])) {
			switch ($_GET['step']) {
				case 1:
					if (isset($_POST['submit_form'])) {
						//foreach($_POST as $key => $value) {
						//	$_POST[$key] = mysql_real_escape_string($value);
						//}
						$additiona_query = '';
						if (isset($_POST['mobile'])) {
							$additional_query = ", mobile = '".$_POST['mobile']."'";
						}
						
						$address_hide = (isset($_POST['address_hide']) ? 1 : 0);
						$phone_hide = (isset($_POST['phone_hide']) ? 1 : 0);
						$mobile_hide = (isset($_POST['mobile_hide']) ? 1 : 0);
						$fax_hide = (isset($_POST['fax_hide']) ? 1 : 0);
						
						$paypal = (isset($_POST['type_paypal']) ? $_POST['paypal_email'] : '' );
						$eway = (isset($_POST['type_eway']) ? $_POST['eway_id'] : '' );
						
						$delivery_options = $_POST['delivery_option'];
						$delivery_text = $_POST['bu_delivery_text'];
						$delivery_prices = $_POST['bu_delivery'];
						
						if (isset($_POST['payments'])) {
							$delete_payment_options = "DELETE FROM aus_soc_payment_store_options WHERE store_id = '".$_SESSION['StoreID']."'";
							$dbcon->execute_query($delete_payment_options);	
							foreach ($_POST['payments'] as $option_id => $value) {
								$insert_payment_option = "INSERT INTO aus_soc_payment_store_options SET store_id = '".$_SESSION['StoreID']."', payment_option = '".$option_id."'";
								$dbcon->execute_query($insert_payment_option);	
							}
						}
						
						$update_store_query = "UPDATE aus_soc_bu_detail SET ref_name = '".getrefname()."', contact_name = '".mysql_real_escape_string($_POST['contact_name'])."', address_hide = '".$address_hide."', phone_hide = '".$phone_hide."', mobile_hide = '".$mobile_hide."', fax_hide = '".$fax_hide."', bu_name = '".mysql_real_escape_string($_POST['website_name'])."', bu_urlstring = '".mysql_real_escape_string($_POST['urlstring'])."', bu_address = '".mysql_real_escape_string($_POST['address'])."', bu_area = '".mysql_real_escape_string($_POST['phone_area'])."', bu_phone = '".mysql_real_escape_string($_POST['phone'])."', bu_fax = '".mysql_real_escape_string($_POST['fax'])."', contact = '".mysql_real_escape_string($_POST['contact'])."', bu_desc = '".mysql_real_escape_string($_POST['about_store'])."', bu_eway = '".$eway."', bu_paypal = '".$paypal."', facebook = '".mysql_real_escape_string($_POST['facebook'])."', twitter = '".mysql_real_escape_string($_POST['twitter'])."', myspace = '".mysql_real_escape_string($_POST['myspace'])."', linkedin = '".mysql_real_escape_string($_POST['linkedin'])."'".$additional_query." WHERE StoreID = '".$_SESSION['StoreID']."' LIMIT 1";
						$dbcon->execute_query($update_store_query);	
					}
					$select_detail = "SELECT store.mobile as main_mobile, store.*, login.* FROM aus_soc_bu_detail store INNER JOIN aus_soc_login login ON login.StoreID = store.StoreID WHERE store.StoreID = '".$_SESSION['StoreID']."' LIMIT 1";
					$dbcon->execute_query($select_detail);
					$detail_result = $dbcon->fetch_records();
					if (isset($detail_result[0])) {
						$detail_data = $detail_result[0];
						$smarty->assign('contact_name', $detail_data['contact_name']);
						$smarty->assign('bu_name', $detail_data['bu_name']);
						$smarty->assign('bu_desc', $detail_data['bu_desc']);
						$smarty->assign('bu_paypal', $detail_data['bu_paypal']);
						$smarty->assign('bu_eway', $detail_data['bu_eway']);
						$smarty->assign('bu_suburb', $detail_data['bu_suburb']);
						$smarty->assign('bu_state', $detail_data['bu_state']);
						$smarty->assign('bu_area', $detail_data['bu_area']);
						$smarty->assign('bu_phone', $detail_data['bu_phone']);
						$smarty->assign('bu_fax', $detail_data['bu_fax']);
						$smarty->assign('contact', $detail_data['contact']);
						$smarty->assign('bu_address', $detail_data['bu_address']);
						$smarty->assign('bu_urlstring', $detail_data['bu_urlstring']);
						$smarty->assign('bu_postcode', $detail_data['bu_postcode']);
						$smarty->assign('mobile', $detail_data['main_mobile']);
						$smarty->assign('store_id', $_SESSION['StoreID']);
						$smarty->assign('bu_email', $detail_data['bu_email']);
						$smarty->assign('status', $detail_data['status']);
						$smarty->assign('address_hide', $detail_data['address_hide']);
						$smarty->assign('phone_hide', $detail_data['phone_hide']);
						$smarty->assign('mobile_hide', $detail_data['mobile_hide']);
						$smarty->assign('fax_hide', $detail_data['fax_hide']);
						$smarty->assign('bu_nickname', $detail_data['bu_nickname']);
						$smarty->assign('facebook', $detail_data['facebook']);
						$smarty->assign('twitter', $detail_data['twitter']);
						$smarty->assign('myspace', $detail_data['myspace']);
						$smarty->assign('linkedin', $detail_data['linkedin']);
						
						$_SESSION['urlstring'] = $detail_data['bu_urlstring'];
						
					}
					
					$payment_options_query = "SELECT * FROM aus_soc_payment_options op LEFT JOIN aus_soc_payment_store_options sop ON sop.payment_option = op.option_id AND store_id = '".$_SESSION['StoreID']."'";
					$dbcon->execute_query($payment_options_query);
					$payment_option_result = $dbcon->fetch_records();
					$payment_options = array();
					foreach($payment_option_result as $option_data) {
						$payment_options[] = array('id' => $option_data['option_id'], 'name' => $option_data['option_name'], 'image' => $option_data['option_image'], 'selected' => (isset($option_data['payment_option'])));
					}
					$smarty->assign('payment_options', $payment_options);
					$query	= "SELECT id, stateName, description FROM aus_soc_state ORDER BY description";
					$result	= $dbcon->execute_query($query);
					$state_list = $dbcon->fetch_records();
					$suburb_data = array();
					$suburb_name = ((isset($detail_data['bu_suburb']) && (!empty($detail_data['bu_suburb']))) ? $detail_data['bu_suburb'] : '');

					foreach($state_list as $state) {
						$query	= "SELECT suburb_id, suburb FROM aus_soc_suburb WHERE state = '".$state['stateName']."' ORDER BY suburb ASC";
						$result	= $dbcon->execute_query($query);
						$suburbs = $dbcon->fetch_records();
						$output = '';
						foreach($suburbs as $suburb) {
							$name = addslashes($suburb['suburb']);
							$selected = (($suburb_name == $name) ? ' selected="selected"' : '');
							$output .= '<option value="'.addslashes($suburb['suburb']).'"'.$selected.'>'.$name.'</option>';
						}
						$suburb_data[$state['stateName']] = $output;
					}
					
					$smarty->assign('state_list', $state_list);
					$smarty->assign('suburb_data', $suburb_data);
					
					//$design_data = $socstoreObj->getDesignInfo();
					
					
					
					//echo var_export($design_data);
					$req = $socstoreObj->getDesignInfo();
					
					
					
					
					//$delivery =	$socstoreObj->_getProductOfferDelivery($_SESSION['StoreID']);
					
					//echo var_dump($delivery);
					//$req = $socstoreObj->storeProductAddOrEdit();
					
					$smarty -> assign('req', $req);
					break;
				case 2:
					$products_count = 0;
					$query_products = "SELECT count(pid) as products FROM aus_soc_product WHERE StoreID = '".$_SESSION['StoreID']."'";
					$dbcon->execute_query($query_products);
					$products_result = $dbcon->fetch_records();
					if (isset($products_result[0])) {
						$products_data = $products_result[0];
						$products_count = (int)$products_data['products'];
					}
					$products_count = 3;
					$smarty->assign('products_count', $products_count);

					if (isset($_POST['submit_form'])) {
						if ((!empty($_POST['image_action'])) && $_POST['image_action'] == 'delmain') {
							$select_template = "SELECT * FROM aus_soc_template_details WHERE StoreID = '".$_SESSION['StoreID']."' LIMIT 1";
							$dbcon->execute_query($select_template);
							$template_result = $dbcon->fetch_records();
							if (isset($template_result[0])) {
								$template_data = $template_result[0];
								if (!empty($template_data['MainImg'])) {
									unlink('/home/soc/thesocexchange.com.au/'.$template_data['MainImg']);
								}
							}
						}
						if (isset($_POST['TemplateName']) && isset($_POST['TemplateBGColor']) && isset($_POST['WebsiteIconID'])) {
							$insert_template = "UPDATE aus_soc_template_details SET TemplateName = '".$_POST['TemplateName']."', TemplateBGColor = '".$_POST['TemplateBGColor']."', TemplateStyle = '', TemplateFont = '', LogoImg = '', LogoDisplay = '', MainImg = '".$_POST['MainImageH']."', bannerImg = '', tpl_type = '', WebsiteIconID = '".$_POST['WebsiteIconID']."', Alerts = '' WHERE StoreID = '".$_SESSION['StoreID']."'";
							$dbcon->execute_query($insert_template);
						}
					}
					
					$socObj->resetTemplate();
					$req = $socstoreObj->getDesignTheme();
					$store_info = $socObj->displayStoreWebside();
					$req = array_merge($req, $store_info);
					$smarty->assign('session', $_SESSION);
					$req = array_merge($req,$socstoreObj->getFeaturedImage());
					if ($products_count < 4) {
						$req['TemplateName'] = 'tmp-n-e';
						$update_template = "UPDATE aus_soc_template_details SET TemplateName = 'tmp-n-e' WHERE StoreID = '".$_SESSION['StoreID']."'";
						$dbcon->execute_query($update_template);
					}
					$smarty->assign('req', $req);
					$categories = $socstoreObj->getImageOfStoreType();
					$smarty->assign('categories', $categories);
					$smarty->assign('cur_time',time());
					break;	
				case 3:
					$query = "SELECT store.mobile as main_mobile, store.*, login.* FROM aus_soc_bu_detail store INNER JOIN aus_soc_login login ON login.StoreID = store.StoreID WHERE store.StoreID = '".$_SESSION['StoreID']."' LIMIT 1";
					$result	= $dbcon->execute_query($query);
					$user_result = $dbcon->fetch_records();
					if (isset($user_result[0])) {
						$user_data = $user_result[0];
						if (isset($user_data['sms_verification'])) {
							if ($user_data['sms_verification'] == 0) {
								$smarty->assign('prompt_purchase', true);
							}
						}

						$smarty->assign('mobile', $user_data['main_mobile']);
						
						if (isset($user_data['status'])) {
							if ($user_data['status'] == 2) {
								$smarty->assign('sms_verified', 'SMS Code Verified');
							}
						}
						
						if (isset($_GET['test_gateway'])) {
							$smarty->assign('testing', true);
						}
						
						if (isset($_POST['purchase_button'])) {
							if (!empty($_POST['mobile'])) {
								$mobile = $_POST['mobile'];
								$eway = new EwayPayment('19262956', 'https://www.eway.com.au/gateway_cvn/xmlpayment.asp' );
								$eway->setCustomerEmail( $user_data['user'] );
								$eway->setCardHoldersName( $_POST['cardholder'] );
								$eway->setCardNumber( $_POST['cardnumber'] );
								$eway->setCardExpiryMonth( $_POST['expiry_month'] );
								$eway->setCardExpiryYear( $_POST['expiry_year'] );
								$eway->setTotalAmount( 200 );
								$eway->setCVN( $_POST['cvc'] );
								
								
								if (isset($_GET['test_gateway']) || $eway->doPayment() == EWAY_TRANSACTION_OK ) {
									$api = new TextMagicAPI(array(
										"username" => "weiver",
										"password" => "dRrMSF3GbpjiAGm"
									));
									mt_srand((double)microtime()*1000000);
									$rand_number = mt_rand(10000,99999);
									$text = 'Your verification code is '.$rand_number . '. Thank you. From SocExchange.';
									$phones = array($mobile);
									$results = $api->send($text, $phones, true);
									if (isset($results['messages'])) {
										if (count($results['messages']) == 1) {
											$update_mobile = "UPDATE aus_soc_bu_detail SET mobile = '".$mobile."' WHERE StoreID = '".$_SESSION['StoreID']."'";
											if ($dbcon->execute_query($update_mobile)) {
												$update_status = "UPDATE aus_soc_login SET sms_verification = '".$rand_number."' WHERE id = '".$_SESSION['UserID']."'";
												$dbcon->execute_query($update_status);
											}
										}
									}
									$smarty->assign('payment_successful', 'Payment was successful.');
									$smarty->assign('prompt_purchase', false);
								} else {
									$smarty->assign('payment_failed', 'Payment Failed');
									$smarty->assign('prompt_purchase', true);
								}
							} else {
								$smarty->assign('payment_failed', 'Mobile number is required');
								$smarty->assign('prompt_purchase', true);
							}
						} else if (isset($_POST['sms_verification'])) {
							if ($user_data['sms_verification'] == $_POST['sms_verification']) {
								$update_status = "UPDATE aus_soc_login SET status = 2 WHERE id = '".$_SESSION['UserID']."'";
								if ($dbcon->execute_query($update_status)) {
									$insert_rating = "INSERT INTO aus_soc_review SET review_type = '', content_type = 'review', type = 'store', StoreID = '".$_SESSION['StoreID']."', bu_name = 'SMS Verification', user_id = '".$_SESSION['UserID']."', rating = 5, content = 'SMS Verified', post_date = NOW(), status = 1;";
									if ($dbcon->execute_query($insert_rating)) {
										$smarty->assign('sms_verified', 'SMS Code Verified');
									}
								}			
							} else {
								$smarty->assign('sms_verified_failed', 'Incorrect Code.');
							}
						}
					}
					break;
				case 4:

				break;
			}
			$smarty->assign('step', $_GET['step']);
		} else {
			$smarty->assign('step', 1);
		}

		if (!empty($_SESSION)) {
			$_SESSION['website'] = $_SESSION['urlstring'];
			$smarty -> assign('session', $_SESSION);
		}
		
		$content = $smarty -> fetch('settings.tpl');
	} else {
		$content = '<div style="padding: 10px;">You must be <a href="soc.php?cp=login">logged in</a> to edit your settings.</div>';
	}
}
$smarty->assign('pageTitle','Sell Goods Online - Selling Online - Buying on SOC Exchange');
$smarty->assign('keywords','Buying on SOC Exchange');
$smarty->assign('contentStyle', 'float: left;width: 946px!important;padding: 0px; margin: 0px;');
$smarty->assign('sidebarContent', ' ');
$smarty->assign('hideLeftMenu', 1);
$smarty->assign('show_left_cms', 0);
$smarty -> assign('content', $content);
$smarty->assign('is_content',1);
$smarty -> display($template_tpl);
unset($smarty);
?>
<?php
include_once ('include/config.php');
include_once ('include/session.php');
include_once('include/smartyconfig.php');
include_once('maininc.php');
include_once('class.emailClass.php');
include_once('class.soc.php');
include_once('class.phpmailer.php');
include_once('class.socbid.php');
include_once('class.socreview.php');
include_once('functions.php');
include_once('class.page.php');
include_once('class/pagerclass.php');
include_once('class.upload.php');
include_once('class.uploadImages.php');
include_once('class.adminhelp.php');
include_once('class.adminjokes.php');
include_once('class.wishlist.php');
include_once('class/ajax.php');
include_once('class.producttag.php');
require_once('class.socstore.php');
require_once('textmagic/TextMagicAPI.php');
require_once('EwayPayment.php');
require_once('class.FoodWine.php');
require_once('class.cart.php');
require_once('class.team.php');

include_once(dirname(__FILE__) . '/languages/'.LANGCODE.'/soc.php');
include_once(dirname(__FILE__) . '/languages/'.LANGCODE.'/foodwine/index.php');

if ($_SESSION['level'] == 2) {
	header("Location:/soc.php?cp=buyerhome");
}

if (isset($_GET['example'])) {
	$smarty->assign('example_site', true);
}

$smarty->assign('securt_url',$securt_url);
$smarty->assign('normal_url',$normal_url);
$smarty->assign('soc_https_host',SOC_HTTPS_HOST);
$socObj = new socClass();
$socstoreObj = new socstoreClass();

function show_account_list() {
	global $dbcon;
	
	$account_list_query = "SELECT al.account_id, al.user_id, login.user FROM aus_soc_account_list al INNER JOIN aus_soc_login login ON al.user_id = login.StoreID
							WHERE al.store_id = '".$_SESSION['StoreID']."'";
	$dbcon->execute_query($account_list_query);
	$account_list = $dbcon->fetch_records();
	echo '<table>';
	foreach($account_list as $list) {
		echo '<tr>';
		echo '<td>'.$list['user'].'</td>';
		echo '<td><div class="delete_account" tag="'.$list['account_id'].'">Delete</div></td>';
		echo '</tr>';	
	}
	echo '</table>
	<script>
		$(document).ready(function() {
			$(".delete_account").click(function() {
				$.ajax({
					url: "registration.php?query_consumers=3",
					type: "POST",
					data: { account_id : $(this).attr("tag") },
					dataType: "html"
				}).done(function(msg) {
					$("#account_holder_list").html(msg);
				});
			});
		});
	</script>	
	';
}

if (isset($_GET['query_consumers'])) {
	switch ($_GET['query_consumers']) {
		case 1:
			$consumer_query = "SELECT detail.StoreID As id, login.user As value FROM aus_soc_bu_detail detail INNER JOIN aus_soc_login login ON detail.StoreID = login.StoreID WHERE CustomerType = 'buyer' AND login.user LIKE '%".trim($_GET['term'])."%'";
			$dbcon->execute_query($consumer_query);
			echo json_encode($dbcon->fetch_records());
			break;
		case 2:
			$account_list_insert = "INSERT INTO aus_soc_account_list SET store_id = '".$_SESSION['StoreID']."', user_id = '".$_POST['user_id']."'";
			$dbcon->execute_query($account_list_insert);
			show_account_list();
			break;
		case 3:
			$account_list_delete = "DELETE FROM aus_soc_account_list WHERE account_id = '".$_POST['account_id']."' AND store_id = '".$_SESSION['StoreID']."'";
			$dbcon->execute_query($account_list_delete);
			show_account_list();
			break;
	}
	exit();
}

$stepOperate = $socstoreObj->getStartSellingOperate();

$lang = $_LANG;

$state_query = "SELECT id, stateName as state, description FROM aus_soc_state ORDER BY description";
$dbcon->execute_query($state_query);
$state_list = $dbcon->fetch_records();

$suburb_query = "SELECT suburb_id, suburb as bu_suburb, zip FROM aus_soc_suburb ORDER BY suburb ASC";
$dbcon->execute_query($suburb_query);
$suburb_list = $dbcon->fetch_records();

$search = array();
$search['search_states'] = $state_list;
$search['cities'] = $suburb_list;
$smarty->assign('search', $search);

$smarty->assign('lang', $lang);
$smarty->assign('cuisine', $cuisine);

$smarty->assign('pageTitle','Sell Goods Online - Selling Online - Buying on FoodMarketplace');
$smarty->assign('keywords','Buying on FoodMarketplace');

$smarty->assign('sidebarContent', '
	<div style="float: right;">
		<img border="0" alt="" src="/skin/red/images/onedollaraday.jpg">
	</div>
');

$template_colours = array(
	1 => 48, // Restaurents
	2 => 45, // Liquor 
	3 => 39, // Bakery 
	4 => 42, // Seafood
	5 => 46, // Meat
	6 => 43, // Fruitveg
	7 => 47, // Bar, pubs
	8 => 41, // Fast food
	9 => 40, // Cafe
	10 => 44 // Juice
);

function checkActivate() {
	global $dbcon;
	if (isset($_SESSION['StoreID'])) {
		$fetch_user = "SELECT * FROM aus_soc_bu_detail WHERE StoreID = '".$_SESSION['StoreID']."'";
		$dbcon->execute_query($fetch_user);
		$user_result = $dbcon->fetch_records();
		if (isset($user_result[0])) {
			$user_data = $user_result[0];
			return ($user_data['status'] == 1);
		}
	}
	return false;
}

function validatePromo($promo) {
	global $dbcon;
	$fetch_promo = "SELECT * FROM aus_soc_promotion WHERE promotion = '".$promo."' AND (attribute = 5 OR attribute = 6)";
	$dbcon->execute_query($fetch_promo);
	$promo_result = $dbcon->fetch_records();
	if (isset($promo_result[0])) {
		$promo_data = $promo_result[0];
		return ($promo_data['Isused'] == 0);
	}
	return false;
}

function step($type, $hide, $preview = false) {
	$checkActivate = checkActivate();
	
	if ($type) {
		$steps = array('account' => (($preview) ? 'Account' : 'Account Information'), 
						'details' => 'Website Details', 
						'theme' => (($preview) ? 'Template' : 'Template / Colour / Images'), 
						'products' => 'Add Products', 
						'preview' => 'Preview',
						'activate' => 'Pay & Go Live');
	} else {
		$steps = array('account' => 'Account Information', 
						'theme' => 'Logo',
						'activate' => 'Pay & Go Live');
	}
	
	$identifier = (($type) ? "steps" : "steps_2");
					
	$current_step = (isset($_GET['step']) ? $_GET['step'] : 'account');	
	$output = '
		<style>
			';
	if ($preview) {
		$output .= '
			@media only screen and (max-width: 767px), screen and (max-device-width: 720px) {
				#container #rightCol {
					margin-top: 315px !important;
				}
				#mobile_image {
					top: -325px !important;
				}
			}		
		';
	}
		
	$output .= '	
			#'.$identifier.' {
				'.(($preview) ? 'width: 550px' : 'width: 750px;').'
				clear: both;
				overflow: hidden;
				'.(($hide) ? 'display: none;' : '').'
			}
			#'.$identifier.' .step {
				float: left;
				margin-left: 5px;
				'.(($preview) ? (($checkActivate) ? 'width: 105px;' : 'width: 85px;') : 'width: 115px;').'
				height: 70px;
				text-align: center;
				color: #777777;
				font-size: 11px;
				padding-top: 5px;
				overflow: hidden;
			}
			
			#'.$identifier.' .step a {
				text-decoration: none;
				font-size: 11px;
				color: #777777;
			}
			
			#'.$identifier.' .step span {
				font-size: 14pt;
				color: #bdbdbd;
				font-weight: bold;
			}
		</style>
		<div id="'.$identifier.'">
	';
	
	$i = 1;
	foreach ($steps as $k => $v) {
		if ($k == 'activate') {
			if ($checkActivate) {
				continue;
			}
		}
		
		$output .= '
			<div class="step">';		
		if (isset($_SESSION['StoreID'])) {
			$output .= '<a href="registration.php?step='.$k.'"><span'.(($current_step == $k) ? ' style="color: #656565;"' : '').'>Step '.$i.'</span><br /><br />' . $v .'</a>';
		} else {
			$output .= '<span'.(($current_step == $k) ? ' style="color: #656565;"' : '').'>Step '.$i.'</span><br /><br />' . $v;
		}
		$output .= '
			</div>';
		$i++;
	}
	
	$output .= '
		</div>
	';
	
	return $output;
}

foreach($_POST as $k => $v) {
	if (!is_array($v)) {
		$_POST[$k] = mysql_real_escape_string(stripslashes($v));
	}
}

$step = (isset($_REQUEST['step']) ? $_REQUEST['step'] : 'account');
$smarty->assign('session', $_SESSION);
switch ($step) {
	case 'account':
		include_once(dirname(__FILE__) . '/include/config.php');
		$isUpdate = ((!empty($_SESSION['StoreID']) && $_SESSION['StoreID'] > 0 ) ? true : false);
		$req = $socstoreObj->sellerValidation($isUpdate);
		
		if (isset($_POST['cp'])) {
			$own_website = (isset($_POST['own_website']) && $_POST['own_website'] == 1);
			
			if ($isUpdate) {
				//delete flyer fanpromo
				unlink(dirname(__FILE__) . "/fanpromo/flyer_store/flyer_{$_SESSION['StoreID']}.pdf");
				
				if ($socstoreObj->sellerSave($isUpdate)) {
				
					if (!$own_website) {
						if (isset($_POST['subattr5'])) {
						
							if ($_POST['subattr5'] == 1 || $_POST['subattr5'] == 7 || $_POST['subattr5'] == 8 || $_POST['subattr5'] == 9 || $_POST['subattr5'] == 10) {
								$tpl_fw = 'foodwine-g';
							} else {
								$tpl_fw = 'foodwine-b';
							}
							
							$arrSettingTemplate = array(
								'TemplateBGColor' => $template_colours[$_POST['subattr5']],
								'TemplateName' 		=> $tpl_fw,
								'TemplateBGColor' 	=> $template_colours[$_SESSION['subAttrib']],
								'tpl_type' 			=> $_SESSION['attribute'] + 1
							);
							$strCondition = "WHERE StoreID = '".$_SESSION['StoreID']."'";
							$dbcon->update_record("aus_soc_template_details", $arrSettingTemplate, $strCondition);
							
							$_SESSION['subAttrib'] = $_POST['subattr5'];
						}			
					}
					
					if (!$own_website) {
						header('Location:registration.php?step=details');
					} else {
						header('Location:registration.php?step=theme');
					}
					exit();
				}
			} else {
				$renewal_date_time = strtotime('+7 days');
				$renewalDate = mktime(0, 0, 0, date("m", $renewal_date_time), date("d", $renewal_date_time), date("Y", $renewal_date_time));
				
				$referral_enabled = false;
				if (!empty($_POST['referrer'])) {
					$referral_enabled = validateReferrer($_POST['referrer']);
				}
				
				if ($own_website) {
					$arrSettingStore = array(
						'bu_cuisine'	=>	empty($_POST['bu_cuisine']) ? 0 : $_POST['bu_cuisine'],
						'bu_name'		=>	$_POST['business_name'],
						'bu_address'	=>	$_POST['bu_address'],
						'bu_suburb'		=>	$_POST['bu_suburb'],
						'bu_state'		=>	$_POST['bu_state'],
						'bu_phone'		=>	$_POST['bu_phone'],
						'bu_fax'		=>	$_POST['bu_fax'],
						'bu_website'	=>	$_POST['bu_website'],
						'bu_email'		=>	$_POST['bu_user'],
						'bu_postcode'	=>	$_POST['bu_postcode'],
						'address_hide'	=>	($_POST['address_hide'] ? $_POST['address_hide'] : 0),
						'phone_hide'	=>	($_POST['phone_hide'] ? $_POST['phone_hide'] : 0),
						'bu_area'		=>	$_POST['bu_area'],
						'CustomerType'	=>	"listing",
						'contact'		=>	$_POST['contact'],
						'mobile'		=>  $_POST['mobile'],
						'attribute'		=>	$_POST['attribute'],
						'subAttrib'		=>	$_POST['subattr5'],
						'licence'		=>  $_POST['licence'],
						'referrer'		=>  (($referral_enabled) ? $_POST['referrer'] : ''),
						'launch_date'	=>	time(),
						'renewalDate'	=> 	$renewalDate,
						'status'		=> 0
					);
				} else {
					$arrSettingStore = array(
						'bu_cuisine'	=>	empty($_POST['bu_cuisine']) ? 0 : $_POST['bu_cuisine'],
						'bu_username'	=>	$_POST['attribute'] == 5 ? $_POST['bu_username'] : '',
						'bu_name'		=>	$_POST['bu_name'],
						'bu_nickname'	=>	$_POST['bu_nickname'],
						'bu_address'	=>	$_POST['bu_address'],
						'bu_suburb'		=>	$_POST['bu_suburb'],
						'bu_state'		=>	$_POST['bu_state'],
						'bu_phone'		=>	$_POST['bu_phone'],
						'bu_fax'		=>	$_POST['bu_fax'],
						'bu_website'	=>	(isset($_POST['bu_website']) ? $_POST['bu_website'] : ''),
						'bu_urlstring'	=>	clean_url_name($_POST['bu_urlstring']),
						'bu_email'		=>	$_POST['bu_user'],
						'bu_postcode'	=>	$_POST['bu_postcode'],
						'address_hide'	=>	($_POST['address_hide'] ? $_POST['address_hide'] : 0),
						'phone_hide'	=>	($_POST['phone_hide'] ? $_POST['phone_hide'] : 0),
						'bu_area'		=>	$_POST['bu_area'],
						'CustomerType'	=>	"seller",
						'contact'		=>	$_POST['contact'],
						'mobile'		=>  $_POST['mobile'],
						'attribute'		=>	$_POST['attribute'],
						'subAttrib'		=>	$_POST['subattr5'],
						'licence'		=>  $_POST['licence'],
						'referrer'		=>  (($referral_enabled) ? $_POST['referrer'] : ''),
						'launch_date'	=>	time(),
						'renewalDate'	=> 	$renewalDate,
						'status'		=> 0
					);
				}
				
				if (isset($_COOKIE['promo']) && (!empty($_COOKIE['promo']))) {
					if (validatePromo($_COOKIE['promo'])) {
						$arrSettingStore['status'] = 1;
						$arrSettingStore['paid'] = time();
						$arrSettingStore['renewalDate'] = mktime(0, 0, 0, date("m"), date("d"), date("Y")+1);
					}
				}
				
				if (isset($_POST['bu_name'])) {
					$existing_store_query = "SELECT * FROM aus_soc_bu_detail WHERE bu_name = '".$_POST['bu_name']."' AND is_popularize_store = 1";
					$existing_store_result = $dbcon->getOne($existing_store_query);
					if (isset($existing_store_result)) {
						$existing_store_delete_query = "DELETE FROM aus_soc_bu_detail WHERE StoreID = '".$existing_store_result['StoreID']."'";
						$dbcon->execute_query($existing_store_delete_query);
					}
				}
				
				$arrSettingStore['ref_name'] = getrefname();				
				
				if ($dbcon->insert_record("aus_soc_bu_detail", $arrSettingStore)) {
					$StoreID = $dbcon->lastInsertId();
					
					if (($_POST['subattr5'] == 1) || 
						($_POST['subattr5'] == 7) || 
						($_POST['subattr5'] == 8) || 
						($_POST['subattr5'] == 9) ||
						($_POST['subattr5'] == 10)) {							
							
					
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (`fid`, `order`, `StoreID`, `category_name`) VALUES(2, 1, ".$StoreID.", 'Appetisers');");
						$category_1 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (`fid`, `order`, `StoreID`, `category_name`) VALUES(2, 2, ".$StoreID.", 'Entrees');");
						$category_2 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (`fid`, `order`, `StoreID`, `category_name`) VALUES(2, 3, ".$StoreID.", 'Pastas');");
						$category_3 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (`fid`, `order`, `StoreID`, `category_name`) VALUES(2, 4, ".$StoreID.", 'Mains');");
						$category_4 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (`fid`, `order`, `StoreID`, `category_name`) VALUES(2, 5, ".$StoreID.", 'Deserts');");
						$category_5 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (`fid`, `order`, `StoreID`, `category_name`) VALUES(2, 6, ".$StoreID.", 'Wine by the glass');");
						$category_6 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (`fid`, `order`, `StoreID`, `category_name`) VALUES(2, 7, ".$StoreID.", 'Champagne');");
						$category_7 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (`fid`, `order`, `StoreID`, `category_name`) VALUES(2, 8, ".$StoreID.", 'White wine');");
						$category_8 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (`fid`, `order`, `StoreID`, `category_name`) VALUES(2, 9, ".$StoreID.", 'Red wine');");
						$category_9 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (`fid`, `order`, `StoreID`, `category_name`) VALUES(2, 10, ".$StoreID.", 'Beers');");
						$category_10 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (`fid`, `order`, `StoreID`, `category_name`) VALUES(2, 11, ".$StoreID.", 'Soft Drinks & Coffees');");
						$category_11 = $dbcon->lastInsertId();

						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Ostriche al Naturale', 'OstrichealNaturale', '20.00', '', 'Freshly shucked Sydney rock oysters', '', ".$StoreID.", 0, 1, ".$category_2.", '', 1398045522, 1398143877, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_1 = $dbcon->lastInsertId();
						
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Fiore di zucca', 'Fioredizucca', '22.00', '', 'Lightly died zucchini flowers stuffed with scallops.', '', ".$StoreID.", 0, 1, ".$category_2.", '', 1398045695, 1398213590, 1, 0, 'wine', 1, '', '', 0, 0);");
						$product_2 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_image (StoreID, tpl_type, pid, smallPicture, picture, attrib, sort, datec, datem) VALUES(".$StoreID.", 0, ".$product_2.", '/upload/temp/1404231088475921_s.jpg', '/upload/temp/1404231088475921.jpg', 0, 0, 1239412912, 1398213590);");

						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('prosciutto con endivia e fichi', 'prosciuttoconendiviaefichi', '23.00', '', 'prosciutto con endivia e fichi san daniele prosciutto with fig, witlof &amp; pine nut dressing', '', ".$StoreID.", 0, 1, ".$category_2.", '', 1398046038, 1398143784, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_3 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Crudo di salmone', 'Crudodisalmone', '20.00', '', 'beetroot cured salmon, mascarpone, pickled radish, smoked salmon roe &amp; nasturtium', '', ".$StoreID.", 0, 1, ".$category_1.", '', 1398046115, 1398201959, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_4 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Ravioli alla caprese', 'Ravioliallacaprese', '28.00', '', 'buffalo mozzarella, ricotta &amp; basil ravioli with roasted cherry tomato sauce', '', ".$StoreID.", 0, 1, ".$category_3.", '', 1398200978, 1398200978, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_5 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Sashimi of Yellow Fin tuna', 'SashimiofYellowFintuna', '26.00', '', 'Sashimi of Yellow Fin tuna, Jamon Iberico cream, wasabi, turnip, ponzu, puffed pork', '', ".$StoreID.", 0, 1, ".$category_2.", '', 1398201335, 1398201335, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_6 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Scarlett prawns', 'Scarlettprawns', '27.00', '', 'Scarlett prawns, heirloom tomato, white cucumber, cr&Atilde;&uml;me fraiche, tempura batter, Matcha tea oil', '', ".$StoreID.", 0, 1, ".$category_2.", '', 1398201438, 1398201438, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_7 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Roasted corn fed chicken', 'Roastedcornfedchicken', '40.00', '', 'Roasted corn fed chicken, grilled scampi, pine mushroom, heart of palm and wakame', '', ".$StoreID.", 0, 1, ".$category_4.", '', 1398201518, 1398201518, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_8 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Crispy fried quail', 'Crispyfriedquail', '22.00', '', 'crispy fried quail, sauted asian greens, ginger, sesame, garlic chips &amp; chilli jam', '', ".$StoreID.", 0, 1, ".$category_1.", '', 1398201736, 1398202019, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_9 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Seared scallops', 'Searedscallops', '21.00', '', 'seared scallops, carrot pure, wagyu bresaola, cress, orange &amp; cumin vinaigrette.', '', ".$StoreID.", 0, 1, ".$category_1.", '', 1398201834, 1398201929, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_10 = $dbcon->lastInsertId();
						
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Roast pork belly', 'Roastporkbelly', '44.00', '', 'roast pork belly, spiced sweet potato, sweet corn, tomato, coriander &amp; cacao jus', '', ".$StoreID.", 0, 1, ".$category_4.", '', 1398202136, 1398211329, 1, 0, 'wine', 1, '', '', 0, 0);");
						$product_11 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_image (StoreID, tpl_type, pid, smallPicture, picture, attrib, sort, datec, datem) VALUES(".$StoreID.", 0, ".$product_11.", '/upload/temp/1404231011464184_s.jpg', '/upload/temp/1404231011464184.jpg', 0, 0, 1239421995, 1398211329);");
						
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Grilled white fish', 'Grilledwhitefish', '44.00', '', 'grilled white fish, warm quinoa, dutch carrots, artichokes, pomegranate &amp; beetroot pure', '', ".$StoreID.", 0, 1, ".$category_4.", '', 1398202281, 1398202281, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_12 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Duck breast,', 'Duckbreast', '36.00', '', 'Duck Breast and Duck Leg Confit with Polenta and Bok Choy in Orange Sauce', '', ".$StoreID.", 0, 1, ".$category_4.", '', 1398202596, 1398211591, 1, 0, 'wine', 1, '', '', 0, 0);");
						$product_13 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_image (StoreID, tpl_type, pid, smallPicture, picture, attrib, sort, datec, datem) VALUES(".$StoreID.", 0, ".$product_13.", '/upload/temp/1404231091512522_s.jpg', '/upload/temp/1404231091512522.jpg', 0, 0, 1239422460, 1398211591);");
						
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Slow-roasted full-blood wagyu brisket', 'Slowroastedfullbloodwagyubrisket', '42.00', '', 'Slow-roasted full-blood wagyu brisket for two with seasonal accompaniments', '', ".$StoreID.", 0, 1, ".$category_4.", '', 1398202710, 1398202710, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_14 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Organic house bread', 'Organichousebread', '9.00', '', 'Organic house bread with cultured walnut butter and date molasses', '', ".$StoreID.", 0, 1, ".$category_1.", '', 1398202769, 1398202769, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_15 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Pistachio, sesame and rooftop honey', 'Pistachiosesameandrooftophoney', '15.00', '', 'Pistachio, sesame and rooftop honey', '', ".$StoreID.", 0, 1, ".$category_5.", '', 1398202863, 1398212160, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_16 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('White chocolate raspberry pave, coconut crunch, raspberry ice-cream', 'Whitechocolateraspberrypavecoconutcrunchraspberryicecream', '15.00', '', 'White chocolate raspberry pave, coconut crunch, raspberry ice-cream', '', ".$StoreID.", 0, 1, ".$category_5.", '', 1398202966, 1398202966, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_17 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Warm lemon pudding, poached strawberry creme fraiche, strawberry ice-cream', 'Warmlemonpuddingpoachedstrawberrycremefraichestrawberryicecr', '15.00', '', 'Warm lemon pudding, poached strawberry creme fraiche, strawberry ice-cream', '', ".$StoreID.", 0, 1, ".$category_5.", '', 1398203055, 1398203055, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_18 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Cheesecake, blackberries, pear', 'Cheesecakeblackberriespear', '15.00', '', 'Cheesecake, blackberries, pear', '', ".$StoreID.", 0, 1, ".$category_5.", '', 1398203152, 1398203152, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_19 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Mandarin, pomegranate Pavlova, blood orange sorbet', 'MandarinpomegranatePavlovabloodorangesorbet', '15.00', '', 'Mandarin, pomegranate Pavlova, blood orange sorbet', '', ".$StoreID.", 0, 1, ".$category_5.", '', 1398203188, 1398203188, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_20 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Crisp buttermilk chicken, cabbage, chilli mayonnaise', 'Crispbuttermilkchickencabbagechillimayonnaise', '22.00', '', 'Crisp buttermilk chicken, cabbage, chilli mayonnaise', '', ".$StoreID.", 0, 1, ".$category_2.", '', 1398203325, 1398203325, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_21 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Spaghetti allo Scoglio', 'SpaghettialloScoglio', '27.00', '', 'Spaghetti tossed in extra virgin olive oil, garlic, white wine and cherry tomatoes with prawns, octopus, calamari, mussels and fish\r\n', '', ".$StoreID.", 0, 1, ".$category_3.", '', 1398210418, 1398212046, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_22 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Penne alla Norma', 'PenneallaNorma', '25.00', '', 'Penne pasta served in a rich traditional tomato base sauce with basil, diced eggplant and shaved salted ricotta cheese\r\n', '', ".$StoreID.", 0, 1, ".$category_3.", '', 1398210506, 1398212131, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_23 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Lasagne', 'Lasagne', '23.00', '', 'Bolognaise sauce between layers of pasta', '', ".$StoreID.", 0, 1, ".$category_3.", '', 1398210581, 1398211032, 1, 0, 'wine', 1, '', '', 0, 0);");
						$product_24 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_image (StoreID, tpl_type, pid, smallPicture, picture, attrib, sort, datec, datem) VALUES(".$StoreID.", 0, ".$product_24.", '/upload/temp/1404230917130384_s.jpg', '/upload/temp/1404230917130384.jpg', 0, 0, 1239435651, 1398211032);");
						
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Spaghetti Carbonara', 'SpaghettiCarbonara', '24.00', '', 'Bacon, egg &amp; light cream sauce', '', ".$StoreID.", 0, 1, ".$category_3.", '', 1398210671, 1398211161, 1, 0, 'wine', 1, '', '', 0, 0);");
						$product_25 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_image (StoreID, tpl_type, pid, smallPicture, picture, attrib, sort, datec, datem) VALUES(".$StoreID.", 0, ".$product_25.", '/upload/temp/1404230986105192_s.jpg', '/upload/temp/1404230986105192.jpg', 0, 0, 1239435965, 1398211161);");
						
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Penne Alpesto', 'PenneAlpesto', '25.00', '', 'Basil, pine nuts &amp; light cream sauce', '', ".$StoreID.", 0, 1, ".$category_3.", '', 1398210770, 1398211982, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_26 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Souffle Grace Vanilla &amp; Crumble', 'SouffleGraceVanillaCrumble', '15.00', '', '', '', ".$StoreID.", 0, 1, ".$category_5.", '', 1398213869, 1398213869, 1, 0, 'wine', 1, '', '', 0, 0);");
						$product_27 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_image (StoreID, tpl_type, pid, smallPicture, picture, attrib, sort, datec, datem) VALUES(".$StoreID.", 0, ".$product_27.", '/upload/temp/1404231035998453_s.jpg', '/upload/temp/1404231035998453.jpg', 0, 0, 1239436523, 1398213869);");
						
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('2009 Domaine Chandon Vintage Brut Yarra Valley, Victoria', '2009DomaineChandonVintageBrutYarraValleyVictoria', '14.00', 'glass', 'A classic blend of cool climate Chardonnay and Pinot Noir: Complex aromas and flavours, layers of grapefruit, citrus blossom; complimented with roasted fig and nutty yeast characters.\r\n', '', ".$StoreID.", 0, 1, ".$category_6.", '', 1398214155, 1398214407, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_28 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('NV Taittinger Brut Rserve Reims, Champagne', 'NVTaittingerBrutRAtildecopyserveReimsChampagne', '27.00', 'glass', 'Expressive with aromas of peach, white flowers and vanilla; Lively and fresh with excellent balance. This is a delicate wine with flavours of fresh fruit and honey.\r\n', '', ".$StoreID.", 0, 1, ".$category_6.", '', 1398214205, 1398214389, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_29 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('2012 Craggy Range Avery Vineyard Sauvignon Blanc Marlborough, New Zealand', '2012CraggyRangeAveryVineyardSauvignonBlancMarlboroughNewZeal', '14.00', '', 'This dry wine is one of finesse and relative subtlety, showing delicately rich, floral blossom and sweet herb flavours, soft crisp acidity and fine, flowing finish.\r\n', '', ".$StoreID.", 0, 1, ".$category_6.", '', 1398214289, 1398214427, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_30 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('2011 Howard Park', '2011HowardParkMiamupCabernetSauvignonMargaretRiverWesternAus', '14.00', '', 'A good representation of the classical Margaret river style Cabernet: Blackberries, dark chocolate and a mix of spices on the nose. On the palate the wine delivers blackcurrants and earthiness with good structure.\r\n', '', ".$StoreID.", 0, 1, ".$category_6.", '', 1398214358, 1398214358, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_31 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Basedow Oscars Favourite Semillon', 'BasedowOscarsFavouriteSemillon', '30.00', '', '', '', ".$StoreID.", 0, 1, ".$category_8.", '', 1398218441, 1398218441, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_32 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Silks Sauvignon (Marlborough NZ)', 'SilksSauvignonMarlboroughNZ', '30.00', '', '', '', ".$StoreID.", 0, 1, ".$category_8.", '', 1398218478, 1398218478, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_33 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Walpara Hills Sauvignon Blanc (Marlborough NZ)', 'WalparaHillsSauvignonBlancMarlboroughNZ', '35.00', '', '', '', ".$StoreID.", 0, 1, ".$category_8.", '', 1398218504, 1398218504, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_34 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Eagle Vale Semillon Sauvignon Blanc (Margaret River WA)', 'EagleValeSemillonSauvignonBlancMargaretRiverWA', '34.00', '', '', '', ".$StoreID.", 0, 1, ".$category_8.", '', 1398218568, 1398218568, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_35 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Shaw and Smith M3 Vineyard Chardonnay (Adelaide Hills SA)', 'ShawandSmithM3VineyardChardonnayAdelaideHillsSA', '35.00', '', '', '', ".$StoreID.", 0, 1, ".$category_8.", '', 1398218601, 1398218601, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_36 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Bimbadeen Estate Shiraz (Hunter Valley NSW)', 'BimbadeenEstateShirazHunterValleyNSW', '30.00', '', '', '', ".$StoreID.", 0, 1, ".$category_9.", '', 1398218647, 1398218647, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_37 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Goose Island Cabernet Merlot (McLaren Vale SA)', 'GooseIslandCabernetMerlotMcLarenValeSA', '30.00', '', '', '', ".$StoreID.", 0, 1, ".$category_9.", '', 1398218672, 1398218672, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_38 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Oyster Bay Merlot (Marborough NZ)', 'OysterBayMerlotMarboroughNZ', '36.00', '', '', '', ".$StoreID.", 0, 1, ".$category_9.", '', 1398218734, 1398218734, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_39 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Chateau Fakra Cabernet Sauvignon (Kfardebian, Lebanon)', 'ChateauFakraCabernetSauvignonKfardebianLebanon', '45.00', '', '', '', ".$StoreID.", 0, 1, ".$category_9.", '', 1398218762, 1398218762, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_40 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Cullen Diane Madeline Red Blend (Margaret River WA)', 'CullenDianeMadelineRedBlendMargaretRiverWA', '140.00', '', '', '', ".$StoreID.", 0, 1, ".$category_9.", '', 1398218788, 1398218788, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_41 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Chateau Tanunda Terroirs of the Barossa Lundoch Shiraz (Barossa SA)', 'ChateauTanundaTerroirsoftheBarossaLundochShirazBarossaSA', '95.00', '', '', '', ".$StoreID.", 0, 1, ".$category_9.", '', 1398218852, 1398218852, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_42 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Wills Domain Semillon (Margaret River WA)', 'WillsDomainSemillonMargaretRiverWA', '40.00', '', '', '', ".$StoreID.", 0, 1, ".$category_8.", '', 1398218887, 1398218887, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_43 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Cascade Premium', 'CascadePremium', '6.00', '', '', '', ".$StoreID.", 0, 1, ".$category_10.", '', 1398218984, 1398218984, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_44 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Cascade Light', 'CascadeLight', '6.00', '', '', '', ".$StoreID.", 0, 1, ".$category_10.", '', 1398219009, 1398219009, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_45 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('James Boags Premium', 'JamesBoagsPremium', '6.00', '', '', '', ".$StoreID.", 0, 1, ".$category_10.", '', 1398219036, 1398219036, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_46 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Crown Lager', 'CrownLager', '6.00', '', '', '', ".$StoreID.", 0, 1, ".$category_10.", '', 1398219061, 1398219061, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_47 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('All Soft Drinks &amp; Coffees', 'AllSoftDrinksampCoffees', '4.50', '', '', '', ".$StoreID.", 0, 1, ".$category_11.", '', 1398219174, 1398219237, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_48 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('NV Grant Burge Blanc De Noirs', 'NVGrantBurgeBlancDeNoirs', '100.00', '', 'Adelaide Hill, SA', '', ".$StoreID.", 0, 1, ".$category_7.", '', 1398219432, 1398219432, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_49 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('NV Taittinger Brut R &amp; serve Reims, Champagne', 'NVTaittingerBrutRAtildecopyserveReimsChampagne', '195.00', '', 'Champagne, FRANCE', '', ".$StoreID.", 0, 1, ".$category_7.", '', 1398219513, 1398219513, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_50 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('NV Veuve Cliquot Ponsardin Brut Magnum 1.5L', 'NVVeuveCliquotPonsardinBrutMagnum15L', '350.00', '', 'Champagne, FRANCE', '', ".$StoreID.", 0, 1, ".$category_7.", '', 1398219599, 1398219759, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_51 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('2011 Stonier Reserve Chardonnay', '2011StonierReserveChardonnay', '98.00', '', 'Mornington Peninsula, VIC', '', ".$StoreID.", 0, 1, ".$category_8.", '', 1398220061, 1398220061, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_52 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) VALUES('Heineken', 'Heineken', '8.00', '', '', '', ".$StoreID.", 0, 1, ".$category_10.", '', 1398220151, 1398220151, 1, 0, 'wine', 0, '', '', 0, 0);");
						$product_53 = $dbcon->lastInsertId();
						
					} else {
					
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (fid, StoreID, category_name) VALUES (1, ".$StoreID.", 'Bread');");
						$category_1 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (fid, StoreID, category_name) VALUES (1, ".$StoreID.", 'Dairy');");
						$category_2 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (fid, StoreID, category_name) VALUES (1, ".$StoreID.", 'Fruit & Vegetables');");
						$category_3 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (fid, StoreID, category_name) VALUES (1, ".$StoreID.", 'Fresh Fish');");
						$category_4 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (fid, StoreID, category_name) VALUES (1, ".$StoreID.", 'Meat');");
						$category_5 = $dbcon->lastInsertId();
						$dbcon->execute_query("INSERT INTO aus_soc_product_category_foodwine (fid, StoreID, category_name) VALUES (1, ".$StoreID.", 'Grocery');");
						$category_6 = $dbcon->lastInsertId();
					
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) 
												VALUES ('FRESH Baguette', 'FRESHBaguette', '1.98', 'Loaf', 'Baked daily and out of the oven at 5am.', '', ".$StoreID.", 0, 1, ".$category_1.", '', 1380590975, 1391738495, 1, 0, 'special', 0, '', 'sell', 0, 0);");
						
						$product_1 = $dbcon->lastInsertId();
						
						$dbcon->execute_query("INSERT INTO aus_soc_image (StoreID, tpl_type, pid, smallPicture, picture, attrib, sort, datec, datem) 
						VALUES (".$StoreID.", 0, ".$product_1.", '/upload/temp/1402070130269223_s.jpg', '/upload/temp/1402070130269223.jpg', 0, 0, 1238002476, 1391738495);");
						
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) 
												VALUES ('Watties Baked beans', 'WattiesBakedbeans', '1.28', 'Tin', 'Great with anything. A quick meal in a can for the whole family!', '', ".$StoreID.", 0, 1, ".$category_2.", '', 1380591184, 1391738446, 1, 0, 'special', 0, '', 'sell', 0, 0);");
						
						$product_2 = $dbcon->lastInsertId();
						
						$dbcon->execute_query("INSERT INTO aus_soc_image (StoreID, tpl_type, pid, smallPicture, picture, attrib, sort, datec, datem) 
						VALUES (".$StoreID.", 0, ".$product_2.", '/upload/temp/1402070111701649_s.jpg', '/upload/temp/1402070111701649.jpg', 0, 0, 1238030747, 1391738446);");
						
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) 
												VALUES ('Carrots', 'Carrots', '1.00', '2 kg for', 'There isn''t a better buy in all of Sydney.', '', ".$StoreID.", 0, 1, ".$category_3.", '', 1380591332, 1391737791, 1, 0, 'special', 0, '', 'sell', 1, 0);");
						
						$product_3 = $dbcon->lastInsertId();
						
						$dbcon->execute_query("INSERT INTO aus_soc_image (StoreID, tpl_type, pid, smallPicture, picture, attrib, sort, datec, datem) 
						VALUES (".$StoreID.", 0, ".$product_3.", '/upload/temp/1210161053472965_s.jpg', '/upload/temp/1210161053472965.jpg', 0, 0, 1238030829, 1391737791);");
						
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) 
												VALUES ('Flathead Fillets', 'FlatheadFillets', '18.98', 'kg', 'Be quick, stock will not last at these prices!', '', ".$StoreID.", 0, 1, ".$category_4.", '', 1380591437, 1391737756, 1, 0, 'special', 0, '', 'sell', 0, 0);");
						
						$product_4 = $dbcon->lastInsertId();
						
						$dbcon->execute_query("INSERT INTO aus_soc_image (StoreID, tpl_type, pid, smallPicture, picture, attrib, sort, datec, datem) 
						VALUES (".$StoreID.", 0, ".$product_4.", '/upload/temp/1402071253120813_s.jpg', '/upload/temp/1402071253120813.jpg', 0, 0, 1238064359, 1391737756);");
						
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) 
												VALUES ('Premium Mince', 'PremiumMince', '9.98', '2kg for', 'Premium Mince great on the BBQ or as a bolognaise.', '', ".$StoreID.", 0, 1, ".$category_5.", '', 1380591633, 1391737922, 1, 0, 'special', 0, '', 'sell', 1, 0);");
						
						$product_5 = $dbcon->lastInsertId();
						
						$dbcon->execute_query("INSERT INTO aus_soc_image (StoreID, tpl_type, pid, smallPicture, picture, attrib, sort, datec, datem) 
						VALUES (".$StoreID.", 0, ".$product_5.", '/upload/temp/1402071281114260_s.jpg', '/upload/temp/1402071281114260.jpg', 0, 0, 1238065022, 1391737922);");
						
						$dbcon->execute_query("INSERT INTO aus_soc_product_foodwine (item_name, url_item_name, price, unit, description, p_code, StoreID, deleted, enabled, category, tags, datec, datem, stock_quantity, isfeatured, type, is_special, youtubevideo, sale_state, priceorder, israceold) 
												VALUES ('FRESH Strawberries', 'FRESHStrawberries', '1.00', '2 punnets for', 'Unbeatable Value!!!', '', ".$StoreID.", 0, 1, ".$category_3.", '', 1380591727, 1391737833, 1, 0, 'special', 0, '', 'sell', 1, 0);");
						
						$product_6 = $dbcon->lastInsertId();
						
						$dbcon->execute_query("INSERT INTO aus_soc_image (StoreID, tpl_type, pid, smallPicture, picture, attrib, sort, datec, datem) 
						VALUES (".$StoreID.", 0, ".$product_6.", '/upload/temp/1312040865037048_s.jpg', '/upload/temp/1312040865037048.jpg', 0, 0, 1238065719, 1391737833);");
					
					}
					
					if (isset($_COOKIE['promo']) && (!empty($_COOKIE['promo']))) {
						$update_promo = "UPDATE aus_soc_promotion SET Isused = 1, user = '".$_POST['bu_user']."', username = '".$_POST['bu_username']."', usedtime = '".time()."', StoreID = '".$StoreID."' WHERE promotion='".$_COOKIE['promo']."'";
						$dbcon->execute_query($update_promo);
					}
					
					$url_store_name = clean_url_name($_POST['bu_urlstring']);
					$arrSettingLogin = array(
						'StoreID'	=>	$StoreID,
						'user'		=>	$_POST['bu_user'],
						'username'	=>	$_POST['attribute'] == 5 ? $_POST['bu_username'] : '',
						'level'		=>	(($own_website) ? 3 : 1),
						'store_name'=>	$url_store_name,
						'attribute'	=>  $_POST['attribute']
					);
					if ($_POST['bu_password']) $arrSettingLogin['password'] = crypt($_POST['bu_password'],getSalt());
					
					if ($dbcon->insert_record("aus_soc_login", $arrSettingLogin)) {
						//generate code for Photo Fan Frenzy
						$UserID					=	$dbcon->lastInsertId();
						$_SESSION['UserID']		=	$UserID;
						$_SESSION['UserName']	=	$_POST['bu_name'];
						$_SESSION['NickName']	=	$_POST['bu_nickname'];
						$_SESSION['StoreID']	=	$StoreID;
						$_SESSION['ShopID']		=	$StoreID;
						$_SESSION['LOGIN']		=	"login";
						$_SESSION['level']		=	(($own_website) ? 3 : 1);
						$_SESSION['attribute']	=	$_POST['attribute'];
						$_SESSION['subAttrib']	=	$_POST['subattr5'];
						$_SESSION['email']		= 	$_POST['bu_user'];
						
						
						if ($_SESSION['attribute'] == 5) {
						
							if ($_POST['subattr5'] == 1 || $_POST['subattr5'] == 7 || $_POST['subattr5'] == 8 || $_POST['subattr5'] == 9 || $_POST['subattr5'] == 10) {
								$tpl_fw = 'foodwine-g';
							} else {
								$tpl_fw = ((!$own_website) ? 'foodwine-b' : '');
							}
							
							$_SESSION['registered'] = 1;
							
							$arrSettingTemplate = array(
								'TemplateName' 		=> $tpl_fw,
								'TemplateBGColor' 	=> $template_colours[$_SESSION['subAttrib']],
								'StoreID'			=> $StoreID,
								'tpl_type' 			=> $_SESSION['attribute'] + 1
							);
							
							if ($referral_enabled) {
								if (!empty($_POST['referrer'])) {
									require_once(SOC_INCLUDE_PATH . '/class.referrer.php');
									$referrer = new Referrer();
									$referrer->addReferrerRecord('reg', $_SESSION['StoreID']);
								}
							}
							
							if (!empty($_POST['team'])) {
								TeamEmailInvite::accept($_POST['team'], $StoreID);
							}
							
							if ($dbcon->insert_record("aus_soc_template_details", $arrSettingTemplate)) {
								$_SESSION['TemplateName'] = $tpl_fw;
								if (!$own_website) {
									header('Location:registration.php?step=activate&register=true');
								} else {
									header('Location:registration.php?step=theme&register=true');
								}
								exit();
							}
						}
					}
				}
			}
		}
		
		$lang = $_LANG;
		krsort($lang['seller']['attribute']);
		$smarty	-> assign('lang', $lang);

		$smarty->assign('isUpdate', $isUpdate);
		
		if (!$isUpdate) {
			if (isset($_REQUEST['referr']) && (!empty($_REQUEST['referr']))) {
				setcookie('cookieRefer', $_REQUEST['referr'], time()+604800);
				header("Location: /registration.php");
				exit();
			}
			
			if (isset($_COOKIE['cookieRefer']) && (!empty($_COOKIE['cookieRefer']))) {
				$req['referrer'] = $_COOKIE['cookieRefer'];
			}
			
			if (isset($_REQUEST['promo']) && (!empty($_REQUEST['promo']))) {
				$promoCode = strtolower(trim($_REQUEST['promo']));
				if (validatePromo($promoCode)) {
					setcookie('promo', $promoCode, time()+604800);
				}			
			}
		}
		
		if (isset($_GET['team'])) {
			$smarty->assign('team', $_GET['team']);
		}
		
		$smarty->assign('show_consumer_join_banner', true);
		
		$smarty->assign('req', $req);
		$smarty->assign('itemTitle', step(true, true).step(false, true));		
		$smarty->assign('content', $smarty->fetch('registration/registration_form.tpl'));
		
		break;
	case 'details':
		if ((isset($_SESSION['StoreID'])) && ($_SESSION['level'] = 1)) {
			if (isset($_POST['cp'])) {
				if ($socstoreObj->saveDesignInfo()) {
					if (isset($_POST['sold_status'])) {
						$arrSettingTemplate = array(
							'TemplateName' 	=> (($_POST['sold_status'] == 1) ? 'foodwine-a' : 'foodwine-b')
						);
						$strCondition = "WHERE StoreID = '".$_SESSION['StoreID']."'";
						$dbcon->update_record("aus_soc_template_details", $arrSettingTemplate, $strCondition);
					}
					
					$socstoreObj->destroyFormInputVar();
					header('Location:registration.php?step=theme');
					exit();
				}
			}
			
			if (isset($_SESSION['registered'])) {
				$smarty->assign('registered', true);
				unset($_SESSION['registered']);
			}
			
			$req = $socstoreObj->getDesignInfo();
			$data = $socstoreObj->storeProductAddOrEdit();
			$req = array_merge($req, $data);
			$req['select_shipping']['deliveryMethod'] = $req['select']['deliveryMethod'];
			$smarty->assign('req', $req);
			$smarty->assign('itemTitle', step(true, false));
			$smarty->assign('content', $smarty->fetch('registration/startselling_step2.tpl'));
		}
		break;
	case 'theme':
		if ((isset($_SESSION['StoreID'])) && ($_SESSION['level'] == 1)) {
			$socObj = new socClass();
			$socObj->resetTemplate();

			if (isset($_POST['cp'])) {
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
			
				if ($socstoreObj->saveDesignTheme()) {
					$socstoreObj->destroyFormInputVar();
					header('Location:registration.php?step=products');
					exit();
				}
			}
			
			$req = $socstoreObj->getDesignTheme();
			$socObj = new socClass();
			$store_info = $socObj->displayStoreWebside();
			$req = array_merge($req, $store_info);
			$comm = new common();
			
			$colour_query = "SELECT ColorID, ColorValue, ColorName FROM aus_soc_color WHERE status = 1";
			$colour_result = $dbcon->execute_query($colour_query);
			$colour_list = $dbcon->fetch_records();
			
			$default_bgcolour = $template_colours[$req['info']['subAttrib']];
			
			$smarty->assign('colour_list', $colour_list);
			$smarty->assign('default_bgcolour', $default_bgcolour);
			
			
			
			$alerts = empty($req['Alerts']) ? "" : $req['Alerts'];
			$req['input']['alerts'] = $comm->initEditor('alerts', $alerts, "SOCAlerts");
			$req = array_merge($req, $socstoreObj->getFeaturedImage());
			$smarty->assign('req', $req);
			
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
			
			$default_search_image = '/skin/red/images/foodwine/category_icon/default/'.$default_store_images[$req['info']['subAttrib']];
			$smarty->assign('default_search_image', $default_search_image);
			
			
			
			//echo var_export($menu_template);
			
			$categories = $socstoreObj->getImageOfStoreType();
			$smarty->assign('categories', $categories);
			$smarty->assign('cur_time',time());
			$smarty->assign('itemTitle', step(true, false));
			$smarty->assign('content', $smarty->fetch('registration/startselling_step3.tpl'));
		} elseif ((isset($_SESSION['StoreID'])) && ($_SESSION['level'] == 3)) {
			include_once('include/config.php');
			
			if (isset($_SESSION['registered'])) {
				$smarty->assign('registered', true);
				unset($_SESSION['registered']);
			}
			
			if (isset($_SESSION['StoreID']) && ($_SESSION['StoreID'] > 0)) {
			
				if (isset($_POST['submit_form'])) {
					if ($socstoreObj->updateLogo($_SESSION['StoreID'])) {
						header('Location:registration.php?step=activate');
						exit();
					}
				}
				
				$req = $socstoreObj->getLogos($_SESSION['StoreID']);
				$smarty -> assign('req', $req);
			}
			$smarty->assign('itemTitle', step(false, false));
			$content = $smarty ->fetch('registration/upload_logo.tpl');
			$smarty -> assign('content', $content);
		}
		break;
	case 'products':
		if ((isset($_SESSION['StoreID'])) && ($_SESSION['level'] == 1)) {
			$foodWine = new FoodWine();
			
			if (isset($_REQUEST['cp'])) {
				switch($_REQUEST['cp']) {
					case 'delete':

						if (isset($_REQUEST['pid'])) {
							$pid = array();
							if (is_array($_REQUEST['pid'])) {
								$pid = $_REQUEST['pid'];
							} elseif (is_string($_REQUEST['pid'])) {
								$pid[] = $_REQUEST['pid'];
							}

							foreach ($pid as $k => $id) {
								$pid[$k] = intval($id);
							}

							$foodWine->deleteProductByPID($pid);
						}
						header('Location: ' . SOC_HTTPS_HOST . 'registration.php?step=products&msg=Deleted successfully.');
						exit;
						break;
					case 'addcategory':

						$fid = $_POST['fid'];
						$cid = $_REQUEST['cid'];
						$name = $_POST['category_name'];
						$res = $foodWine->checkCategoryName($name, $fid, $_SESSION['StoreID'], $cid);
						if ($res) {
							header('Location: ' . SOC_HTTPS_HOST . 'registration.php?step=products&msg=The Category name already exists.');
							exit;
						}
						if ($cid) {
							$foodWine->editCategory($cid, array('category_name' => $name));
							$sub_url = "&tab=clist&msg=Edit Category successfully.#list";
						} else {
							$foodWine->addCategory($name, $fid, $_SESSION['StoreID']);
							$sub_url = "&msg=Add Category successfully.";
						}

						header('Location: ' . SOC_HTTPS_HOST . 'registration.php?step=products' . $sub_url);
						exit;
						break;
					case 'getcategoryname':

						$fid = $_POST['fid'];
						$cid = $_POST['cid'];
						$res = $foodWine->getCategoryInfo($cid, $fid, $_SESSION['StoreID']);
						exit($res['category_name']);
						break;
					case 'checkcategoryname':

						$fid = $_POST['fid'];
						$cid = $_POST['cid'];
						$name = $_POST['category_name'];
						$res = $foodWine->checkCategoryName($name, $fid, $_SESSION['StoreID'], $cid);
						exit($res);
						break;
					case 'deletecategory':

						if (isset($_REQUEST['cid'])) {
							$cid = array();
							if (is_array($_REQUEST['cid'])) {
								$cid = $_REQUEST['cid'];
							} elseif (is_string($_REQUEST['cid'])) {
								$cid[] = $_REQUEST['cid'];
							}

							foreach ($cid as $k => $id) {
								$cid[$k] = intval($id);
							}

							$foodWine->deleteCategoryByCID($cid);
						}
						header('Location: ' . SOC_HTTPS_HOST . 'registration.php?step=products&tab=clist&msg=Deleted category successfully.#list');
						exit;
						break;
					case 'savecategory':

						$cid_ary = $_POST['cid'];
						$category_name_ary = $_POST['category_name'];
												
						foreach ($category_name_ary as $name) {
							if (empty($name)) {
								header('Location: ' . SOC_HTTPS_HOST . 'registration.php?step=products&msg=Category name is required.');
								exit();
							}
						}
						$res = $foodWine->saveCategory($cid_ary, $category_name_ary, $_SESSION['StoreID']);
						$msg = $res ? 'Save category successfully.' : 'Save category failed.';
						header('Location: ' . SOC_HTTPS_HOST . 'registration.php?step=products&msg=' . $msg);
						exit();
						break;
					case 'savecategoryorder':
					
						$order = $_REQUEST['order'];
						if (is_array($order)) {
							foreach ($order as $k => $val) {
								echo $k = str_replace("'", '', stripcslashes($k));
								$foodWine->saveCategoryOrder($k, $val);
							}
						}
						header('Location: ' . SOC_HTTPS_HOST . 'registration.php?step=products&tab=clist&msg=Save category order successfully.#list');
						exit;
						break;
				}		
			}

			$foodWineType = getFoodWineType();
			
			if (isset($_SESSION['subAttrib']) && $_SESSION['subAttrib'] == 6) {
				$query_image_library = "SELECT * FROM aus_soc_product_library ORDER BY product_name ASC";
				$image_library_result = $dbcon->execute_query($query_image_library);
				$image_library = $dbcon->fetch_records();
				$smarty->assign('image_library', $image_library);
			}

			if ('POST' === $_SERVER['REQUEST_METHOD']) {
				$data = $foodWine->checkProductData($_POST, $_POST['pid'] > 0 ? true : false);
				if (false == $data['status']) {
					header('Location: ' . SOC_HTTPS_HOST . 'registration.php?step=products&msg=' . implode("\n\r", $data['msg']));
					exit;
				}
				$foodWine->saveProduct($data['data'], $data['images'], $_POST['pid']);
				header('Location: ' . SOC_HTTPS_HOST . 'registration.php?step=products&msg=Product saved successfully. ');
				exit;
			}
			
			$productInfo = array();
			if (isset($_GET['pid']) and $_GET['pid'] > 0) {
				$productInfo = $foodWine->getProductInfo($_SESSION['StoreID'], $_GET['pid']);
			}
			$smarty->assign('productInfo', $productInfo);
			
			$products = $foodWine->getProductsList($_SESSION['StoreID'], $foodWineType);
			$smarty->assign('productList', $products);

			$store_info = $socObj->displayStoreWebside(true);
			$req = array_merge($_REQUEST, $store_info);

			$smarty->assign('req', $req);
			$categories = $foodWine->getCategoryList($foodWineType, $_SESSION['StoreID']);
			$smarty->assign('categories', $categories);
			$smarty->assign('categories_num', ($categories ? count($categories) : 0));

			$smarty->assign('sidebar', 0);
			$smarty->assign('isstorepage', 0);
			$smarty->assign('foodwine_type', $foodWineType);
			$smarty->assign('secure_url',SOC_HTTPS_HOST);
			$smarty->assign('session', $_SESSION);
			$smarty->assign('itemTitle', step(true, false));
			$smarty->assign('content', $smarty->fetch('registration/product_step4.tpl'));
		}
		break;
	case 'preview':
		if ((isset($_SESSION['StoreID'])) && ($_SESSION['level'] == 1)) {
			$_REQUEST['StoreID'] = $_SESSION['StoreID'];
			$socObj->resetTemplate();
			$req = $socObj -> displayStoreWebside(false,true);
			
			if (($req['info']['attribute'] == 1 || $req['info']['attribute'] == 2 || $req['info']['attribute'] == 3) && count($req['items']['product']) == 0 && $req['info']['StoreID'] != $_SESSION['ShopID']) {
				echo "<script>alert('Sorry. You don\'t have permission to access this page.');location.href='/soc.php?cp=home';</script>";
				exit;
			}

			if($req['info']['attribute'] == 5 && !$req['info']['is_popularize_store']) {
				$is_foodwine_home = true;
				$smarty->assign('show_join_banner', 1);
				$smarty->assign('show_season_banner', 1);
				$smarty->assign('is_foodwine_home', $is_foodwine_home);
			}
			
			$image = 'skin/red/images/logo-main.png'; 
			$req['info']['bu_email'] = $_SESSION['email'];
			$req['info']['facelike_image'] = $image ? SOC_HTTP_HOST.$image : SOC_HTTP_HOST.'skin/red/images/logo-main.png';
			$req['info']['facelike_title'] = $req['info']['bu_name'];
			$req['info']['facelike_desc'] = addslashes(strip_tags($req['info']['description']));
			$req['info']['like_type'] = 'store';

			$rooturl = "http://{$_SERVER['HTTP_HOST']}";
			$homeurl = "$rooturl/{$req['info']['bu_urlstring']}";
			$req['widgetHTML']="<div align=\"center\" style=\"width:208px\"><a target=\"_blank\" href=\"$homeurl\"><img border=0 src=\"$rooturl/skin/red/images/soc-link-logo.jpg\" width=\"208\"/></a></div>";
			$templateInfo = $socObj -> getTemplateInfo();
			$smarty->assign('templateInfo', $templateInfo);
			$smarty->assign('sellerhome', 1);
			$smarty->assign('req',	$req);
			$smarty->assign('pageTitle',$req['info']['bu_name']." ".$req['info']['bu_suburb'].":".$req['info']['subAttribName'].':Food Market Place');
			$custom_seo_keywords = TRUE;
			$smarty->assign('keywords',$req['info']['subAttribName'].",".$req['info']['bu_suburb'].",".$req['info']['bu_state']);
			$is_set_desc = TRUE;
			$smarty->assign('description','Learn more about this '.$req['info']['subAttribName'].' local retailer in '.$req['info']['bu_suburb']);
			$smarty->assign('HostName',$_SERVER['HTTP_HOST']);
			$smarty->assign('UserID', $_SESSION['ShopID']);
			$search_type = $req['info']['sellerType'];
			
			if ($req['info']['is_popularize_store']) {
				$smarty->assign('itemTitle', $socObj->getTextItemTitle($req['info']['subAttribName'], 4, '3c3082'));
				$content = $smarty->fetch('foodwine/template/foodwine-freelisting.tpl');
				$smarty->assign('content', $content);
			} else {
				$smarty->assign('headerInfo', $req['info']);
				$tmp_header = $smarty->fetch(SITE_ROOT.'skin/red/template/tmp-header-include.tpl');
				$smarty->assign('header_message', step(true, false, true));
				$smarty->assign('tmp_header', $tmp_header);
				if ($req['template']['tpl_type'] > 1) {
					$smarty -> loadLangFile($search_type.'/index');
					$strTplPath	=	$search_type.'/template/';
					if(in_array($_REQUEST['name'],getSamplesitebyNames($samplesiteid))) {
						$smarty -> assign('is_samplestie',1);
					}
					$product_content = $smarty -> fetch($strTplPath. $req['template']['TemplateName'] . '.tpl');
					$smarty -> assign('content', $product_content);
				} else {			
					$strTplPath	=	'template/';
					if(in_array($_REQUEST['name'],getSamplesitebyNames($samplesiteid))) {
						$smarty -> assign('is_samplestie',1);
					}
					
					$product_content = $smarty -> fetch($strTplPath. $req['template']['TemplateName'] . '.tpl');
					$smarty -> assign('product_content', $product_content);
					$shop_summary = $smarty->fetch($strTplPath.'tmp-shop-summary.tpl');
					$smarty->assign('content',$shop_summary);
		
					if($req['template']['TemplateName']=='tmp-n-e') {
						if($req['items']['product'][0]['item_name']!="") {
							$smarty -> assign('pageTitle',$req['items']['product'][0]['item_name']);
						}
					}
				}
			}
			//echo var_export($req['info']);
			$smarty->assign('noShowGalleryBanner', true);
			$smarty->assign('sidebar', 0);
			$smarty->assign('is_website',1);
			$smarty->assign('isstorepage',1);
			$smarty->assign('hide_navigation',1);
			$_SESSION['logo_old'] = true;
		}
		break;
	case 'activate':
		$fetch_user = "SELECT * FROM aus_soc_bu_detail WHERE StoreID = '".$_SESSION['StoreID']."'";
		$dbcon->execute_query($fetch_user);
		$user_result = $dbcon->fetch_records();

		if (isset($user_result[0])) {
			$user_data = $user_result[0];
			
			$_SESSION['email'] = $user_data['bu_email'];
			if ($user_data['status'] == 0) {
				if (isset($_POST['cardName']) && isset($_POST['cardNumber']) 
					&& isset($_POST['cvc2']) && isset($_POST['expiryMonth']) && isset($_POST['expiryYear'])) {
					
					$eway_setting = getEwayInfo();

					$total_amount = (($_SESSION['level'] == 3) ? 25000 : 36500);
					
					if (EWAY_DEBUG==1) {
						$customerID = EWAY_DEFAULT_CUSTOMER_ID;
						$ewayURL = EWAY_TEST_GATEWAY_URL;
					} else {
						$customerID = $eway_setting['eway_customer_id'];
						$ewayURL = EWAY_DEFAULT_GATEWAY_URL;
					}
					$eway = new EwayPayment($customerID, $ewayURL);
					$eway->setCustomerEmail($user_data['bu_email']);
					
					$eway->setCardHoldersName($_POST['cardName']);
					$eway->setCardNumber($_POST['cardNumber']);
					$eway->setCardExpiryMonth($_POST['expiryMonth']);
					$eway->setCardExpiryYear(substr($_POST['expiryYear'],2));
					$eway->setCVN($_POST['cvc2']);
					$eway->setTotalAmount($total_amount);

					$transaction_status = $eway->doPayment();

					/* 
					* Send an email to the seller - either receipt or notification
					*/
					$arrParams = array(
						'bu_name'=>$user_data['bu_name'],
						'bu_email'=>$user_data['bu_email'],
						'amount'=>number_format(($total_amount/100),2),
						'status'=>$eway->getTrxnStatus()=='False'?false:$eway->getTrxnStatus(),
						'txnID'=>$eway->getTrxnNumber(),
						'error'=>$eway->getErrorMessage(),
						'date'=>date('r')
						);
        			$smarty = $GLOBALS['smarty'];
					$smarty->assign('req', $arrParams);

					if ($_SESSION['level'] == 3) {
						$message = 'Welcome<br /><br />You have successfully linked to your website<br />from the FoodMarketplace platform.<br /><br />Kind Regards,<br />'.SITENAME;
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$headers .= 'From: '.SITENAME.' <no-reply@'.EMAIL_DOMAIN.'>' . "\r\n";
						$headers .= 'BCC: info@'.EMAIL_DOMAIN . "\r\n";
						mail($user_data['bu_email'], 'Website Listing', $message, $headers);
					} else {
			       		$seller_message = $smarty->fetch('email_receipt.tpl');
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$headers .= 'From: '.SITENAME.' <no-reply@'.EMAIL_DOMAIN.'>' . "\r\n";
						$headers .= 'BCC: info@'.EMAIL_DOMAIN . "\r\n";
						$subject = $arrParams['status'] ? 'Payment Confirmed - #'.$arrParams['txnID']:'Payment Declined';
						mail($user_data['bu_email'], $subject, $seller_message, $headers);
					}
					//=========================
					
					if ($transaction_status == EWAY_TRANSACTION_OK) {
						
						$example_product_list = array('FRESH Baguette', 'Watties Baked beans', 'Carrots', 'Flathead Fillets', 'Premium Mince', 'FRESH Strawberries');
						
						$clear_products = true;
						$example_product_query	= "SELECT item_name FROM aus_soc_product_foodwine WHERE StoreID = '".$_SESSION['StoreID']."'";
						$example_product_result	= $dbcon->execute_query($example_product_query);
						$example_products = $dbcon->fetch_records();
						foreach($example_products as $example_product) {
							if (!in_array($example_product['item_name'], $example_product_list)) {
								$clear_products = false;
							}
						}
						
						if ($clear_products) {
							$example_product_remove_query	= "DELETE FROM aus_soc_product_foodwine WHERE StoreID = '".$_SESSION['StoreID']."'";
							$example_product_remove_result	= $dbcon->execute_query($example_product_remove_query);
						}
						
						
						$strCondition = "WHERE StoreID = '".$_SESSION['StoreID']."'";
						
						$arrSetting = array(
							'status'		=>	1,
							'paid'			=> time(),
							'renewalDate' 	=> mktime(0, 0, 0, date("m"), date("d"),   date("Y")+1)
						);
						
						
						//generate fanpromo code
						include_once 'fanpromo/functions.inc.php';
						generate_fanpromo_code($dbcon, $_SESSION['StoreID']);
					
						
						
						
						if ($dbcon->update_record("aus_soc_bu_detail", $arrSetting, $strCondition)) {						
							exit(json_encode(array('status'=>'true','tn'=>$eway->getTrxnNumber(),'jumpPath'=>(($_SESSION['level'] == 3) ? 'soc.php?cp=listinghome' : 'soc.php?cp=sellerhome'))));
						}
					} else {
						$errMsg= "Error occurred (".$eway->getError()."): " . $eway->getErrorMessage();
						exit(json_encode(array('status'=>'false', 'msg'=>preg_replace("/[\n\r]/","",$errMsg))));
					}
				}
				
				$eway_setting = getEwayInfo();
				$req['eway_info'] = array();
				$req['ewayEmail'] = $user_data['bu_email'];
				$req['expiryMonth'] = getExpMonth();
				$req['expiryYear']= substr(getExpYear(),2);
				$req['payment_type'] = (($_SESSION['level'] == 3) ? 1 : 2);				
				$smarty -> loadLangFile('payment');
				$smarty->assign('req',$req);
				
				$smarty->assign('itemTitle', step(($_SESSION['level'] == 1), false));
				$content = $smarty ->fetch('eway_gateway.tpl');
				$smarty->assign('content', $content);
			}
		}
		break;
}
$smarty->assign('sidebar', 0);
$smarty->assign('hideLeftMenu', 0);
$smarty->assign('show_left_cms', 1);
$smarty->assign('is_content',1);
$smarty->display($template_tpl);
unset($smarty);

?>
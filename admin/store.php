<?php
/**
 * Wed Oct 15 13:54:54 GMT+08:00 2008 13:54:54
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * Store function control
 * ------------------------------------------------------------
 * \admin\store.php
 */

session_start();
//ini_set('session.bug_compat_warn', 0); 
//ini_set('session.bug_compat_42', 0);
include_once ('../include/smartyconfig.php');
include_once ('class.login.php');
include_once ('maininc.php');
include_once ('class.adminstore.php');
include_once ('functions.php');
require_once ('Pager/Pager.php');
include_once ('xajax/xajax_core/xajax.inc.php');

//check login

//echo session_id();
$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}
$smarty -> loadLangFile('soc');

$objAdminStore 	= new adminStore();
$xajax 			= new xajax();

//control
switch($_REQUEST["cp"]){
	case 'export':
		$customer_type = (isset($_GET['customer_type']) ? $_GET['customer_type'] : 'seller');
		$status = (isset($_GET['status']) ? $_GET['status'] : 1);
		$attribute = (isset($_GET['attribute']) ? $_GET['attribute'] : 0);
		//AND (d.launch_date BETWEEN '".strtotime('-1 year')."' AND '".time()."') 
		$query_stores = "SELECT d.*, s.stateName FROM aus_soc_bu_detail d INNER JOIN aus_soc_state s ON s.id = d.bu_state WHERE d.CustomerType = '".$customer_type."'".(($attribute > 0) ? "AND d.attribute = ".$attribute : "")." AND is_popularize_store = 0 AND d.status = '".$status."' ORDER BY d.launch_date DESC";
		$dbcon->execute_query($query_stores);
		$store_list = $dbcon->fetch_records();
		//echo $query_stores;
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=".$customer_type."_export.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo '<table>';
		echo '<thead>';
		echo '<th>Store ID</th>';
		if ($customer_type == 'seller' || $customer_type == 'listing') {
			echo '<th>Business Name</th>';
		}
		if ($customer_type == 'buyer') {
			echo '<th>Name</th>';
		}
		if ($customer_type == 'seller') {
			echo '<th>Store URL</th>';
			echo '<th>Nickname</th>';
		}
		if ($customer_type == 'listing') {
			echo '<th>Website</th>';
		}
		echo '<th>Address</th>';
		echo '<th>Suburb</th>';
		echo '<th>State</th>';
		echo '<th>Postcode</th>';
		echo '<th>Email</th>';
		echo '<th>Phone</th>';
		echo '<th>Date Joined</th>';
		echo '</thead>';
		echo '<tbody>';
		foreach ($store_list as $store) {
			echo '<tr>';
			echo '<td>'.$store['StoreID'].'</td>';
			echo '<td>'.$store['bu_name'].'</td>';
			if ($customer_type == 'seller') {
				echo '<td>'.$store['bu_urlstring'].'</td>';
				echo '<td>'.$store['bu_nickname'].'</td>';
			}
			if ($customer_type == 'listing') {
				echo '<td>'.$store['bu_website'].'</td>';
			}
			echo '<td>'.$store['bu_address'].'</td>';
			echo '<td>'.$store['bu_suburb'].'</td>';
			echo '<td>'.$store['stateName'].'</td>';
			echo '<td>'.$store['bu_postcode'].'</td>';
			echo '<td>'.$store['bu_email'].'</td>';
			echo '<td>'.$store['bu_area'].$store['bu_phone'].'</td>';
			echo '<td>'.(($store['launch_date'] > 0) ? gmdate("d/m/Y", $store['launch_date']) : '').'</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
		exit();
		break;
	case 'adduser':
		$req['header']	=	$objAdminStore->lang['header']['able'];
		$req['list']['state']   =   $objAdminStore->getStateList();
		$req['list']['cuisine']  =   getCuisineList();
		if(isset($_REQUEST['StoreID'])&&$_REQUEST['StoreID']!=""){
			$req['info'] = $objAdminStore->getStoreInfo($_REQUEST['StoreID']);
			$req['list']['suburb']  =   $objAdminStore->getsuburbsbysid($req['info']['bu_state']);
		}
		if(isset($_POST)&&!empty($_POST)){
			$attribute = $_REQUEST['attribute'];
			$url_store_name = clean_url_name($_REQUEST['bu_urlstring']);
			if(5 == $attribute) {
				include_once(SOC_INCLUDE_PATH . '/class.FoodWine.php');
				$fw_type = getFoodWineType($_REQUEST['subattr5']);
				$tpl_fw = $fw_type == 'food' ? 'foodwine-a' : 'foodwine-d';
	        }
			$arrSetting = array(
				'attribute' => $attribute,
				'bu_username' => $attribute == 5 ? $_REQUEST['bu_username'] : '',
				'bu_cuisine' => $_REQUEST['bu_cuisine'],
				'bu_email' => $_REQUEST['bu_email'],
				'bu_nickname' => $_REQUEST['bu_nickname'],
				'gender' => $_REQUEST['gender'],
				'bu_name' => $_REQUEST['bu_name'], 
				'bu_urlstring' => $url_store_name, 
				'bu_address' => $_REQUEST['bu_address'], 
				'address_hide' => $_REQUEST['address_hide']?$_REQUEST['address_hide']:0, 
				'bu_state' => $_REQUEST['bu_state'], 
				'bu_suburb' => $_REQUEST['bu_suburb'], 
				'bu_postcode' => $_REQUEST['bu_postcode'], 
				'bu_area' => $_REQUEST['bu_area'], 
				'bu_phone' => $_REQUEST['bu_phone'], 
				'phone_hide' => $_REQUEST['phone_hide']?$_REQUEST['phone_hide']:0, 
				'mobile' => $_REQUEST['mobile'], 
				'contact' => $_REQUEST['contact'], 
				'bu_fax' => $_REQUEST['bu_fax'],
				'CustomerType'	=>	"seller",
				'licence'		=>	$_REQUEST['licence'],
				);
			$loginary = array(
			'username'		=>	$attribute == 5 ? $_REQUEST['bu_username'] : '',
			'user'			=>	$_REQUEST['bu_email'],
			'password'		=>	$_REQUEST['bu_password'],
			'level'			=>	"1",
			'store_name'	=>	$url_store_name,
			'attribute'		=>  $_REQUEST['attribute'],
			'suspend'		=>  isset($_REQUEST['suspend'])&&$_REQUEST['suspend']?$_REQUEST['suspend']:0
			);
			$templateary = array(
			'TemplateName'	=>	$attribute == 0 ? 'tmp-n-a' : ($attribute == 1 ? 'estate-c' : ($attribute == 2 ? 'auto-c' : ($attribute == 3 ? ($_REQUEST['subattr3'] == 3 ? 'job-c' : 'job-c') : $tpl_fw))),
			'tpl_type'		=>	($attribute+1)
			);
			
			switch ($_REQUEST['attribute']){
				case '1':
					$arrSetting['subAttrib'] = $_REQUEST['subattr1'];
					break;
				case '2':
					$arrSetting['subAttrib'] = $_REQUEST['subattr2'];
					break;
				case '3':
					$arrSetting['subAttrib'] = $_REQUEST['subattr3'];
					break;
				case '5':
					$arrSetting['subAttrib'] = $_REQUEST['subattr5'];
					break;
				default:
					$arrSetting['subAttrib'] = 0;
					break;
			}
			if($_REQUEST['StoreID']!=""){
				$StoreID = $_REQUEST['StoreID'];
				$strCondition ="where StoreID='$StoreID'";
				if ($dbcon-> update_record($table."bu_detail", $arrSetting, $strCondition)) {
					$loginary['StoreID'] =	$StoreID;
					$dbcon-> update_record($table."login", $loginary, $strCondition);
					$dbcon-> update_record($table."template_details", $templateary, $strCondition);
					$msg = "Edit user successfully.";
					$req['msg'] = $msg;
					$req['isok'] = 'yes';
				}else{
					$msg = "Faild to edit user.";
				}
			}else{
				$arrSetting['PayDate'] =	time();
				$arrSetting['launch_date']	=	time();
				$arrSetting['ref_name'] = getrefname();
				$arrSetting['renewalDate'] = mktime(0,0,0,date('m'),date('d'),date('Y')+1);
				if ($dbcon-> insert_record($table."bu_detail", $arrSetting)) {
					$StoreID = $dbcon->lastInsertId();
					$loginary['StoreID'] =	$StoreID;
					$templateary['StoreID']=$StoreID;
					$dbcon-> insert_record($table."login", $loginary);
					$dbcon-> insert_record($table."template_details", $templateary);
					
					if ($attribute == 1 || $attribute == 2 || $attribute == 3) {
						include_once(SOC_INCLUDE_PATH.'/class.soc.php');
						include_once(SOC_INCLUDE_PATH.'/class.socstore.php');
						$socstoreObj = new socstoreClass();
        				$res = $socstoreObj -> productActive($StoreID, 'year', array(), '', false);
        			}	
					
					unset($loginary);
					unset($templateary);
					$msg = "Create user successfully.";
					$req['msg'] = $msg;
					$req['isok'] = 'yes';
				}else{
					$msg = "Faild to create user.";
					$arrSetting['bu_password'] = $_REQUEST['bu_password'];
					$req['info'] = $arrSetting;
					$req['list']['suburb']  =   $objAdminStore->getsuburbsbysid($req['info']['bu_state']);
				}
			}
		}
		$smarty->assign('req',$req);
		$content = $smarty->fetch('admin_seller_form.tpl');
		$smarty->assign('content',$content);
		$_REQUEST["cp"] = "store";
		break;
	case 'getstatelist':
		$state = $_REQUEST['state'];
		$suburbs = $objAdminStore->getsuburbsbysid($state);
		echo "<option value='' selected>Select City</option>";
		foreach ($suburbs as $pass){
			echo "<option value='{$pass['suburb']}'>{$pass['suburb']}</option>"; 
		}
		exit();
		break;
	case 'checkform':
		$username = $_REQUEST['username'];
		$email = $_REQUEST['email'];
		$nickname = $_REQUEST['nickname'];
		$website = $_REQUEST['website'];
		$urlstring = $_REQUEST['urlstring'] ;
		$attribute = $_REQUEST['attribute']?$_REQUEST['attribute']:0;
		$storeID = $_REQUEST['StoreID']?$_REQUEST['StoreID']:"";
		if(!get_magic_quotes_gpc()){
			$username = addslashes($username);
			$email = addslashes($email);
			$nickname = addslashes($nickname);
			$website = addslashes($website);
			$urlstring = addslashes($urlstring);
		}
		echo $objAdminStore->checkfrom($email,$nickname,$website,$urlstring,$attribute,$storeID,$username);
		exit();
		break;
	case 'viewexp':
		$req['header']	=	"View Expired Users";
		$xajax -> registerFunction('getStoreList2');
		$xajax -> registerFunction('getStoreListSearch2');
		$xajax -> registerFunction('getSuburbList');
		$xajax -> registerFunction('renewuser');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$req['list'] 	=	$objAdminStore -> getStoreList2();
		$req['state'] 	=	$objAdminStore -> getStateList();
		$req['order'] = $_REQUEST['order'];
		$req['field'] = $_REQUEST['field'];
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB);
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_store_exp.tpl');
		$smarty -> assign('content', $content);
		break;
    case 'duplicateListing':
        $req['header'] = $objAdminStore->lang['header'][$_REQUEST["cp"]];
        $xajax->registerFunction('getDuplicateList');
        $xajax->registerFunction('getDuplicateListSearch');
        $xajax->registerFunction('deleteDuplicateList');
        $smarty->assign_by_ref('objAdminStore', $objAdminStore);
        $req['xajax_Javascript'] = $xajax->getJavascript('/include/xajax');

        $req['list'] = $objAdminStore->getDuplicateList();
        $smarty->assign('req', $req);
        $content = $smarty->fetch('admin_duplicate_listing.tpl');
        $smarty->assign('content', $content);
        
        $xajax->processRequest();
        break;
	case 'stats':
		$req  = $objAdminStore->getstatslog();
		$req['header']	=	"Member Stats";
		$smarty -> assign('req',$req);
		$content = $smarty->fetch('admin_stats.tpl');
		$smarty -> assign('content',$content);
		break;
	case 'freelisting':
		unset($_SESSION['pageParam']);
		$req['header']	=	"Import Free Listing";
		if(isset($_POST)&&!empty($_POST)){
			$objAdminStore->importFreeListingByCSV();
			//print_r($_SESSION['pageParam']);
		}
		
		$smarty -> assign('error_info', $_SESSION['pageParam']);
		$smarty -> assign('req',$req);
		$smarty->assign('soc_http_host',SOC_HTTP_HOST);
		$content = $smarty->fetch('admin_store_freelisting.tpl');
		$smarty -> assign('content',$content);
		break;
	default:
		$req['header']	=	$objAdminStore->lang['header']['able'];
		$xajax -> registerFunction('getStoreList');
		$xajax -> registerFunction('getStoreListSearch');
		$xajax -> registerFunction('deleteStoreList');
		$xajax -> registerFunction('getSuburbList');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		
		$req['list'] 	=	$objAdminStore -> getStoreList();
		$req['state'] 	=	$objAdminStore -> getStateList();
		$req['order'] = $_REQUEST['order'];
		$req['field'] = $_REQUEST['field'];
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_store.tpl');
		$smarty -> assign('content', $content);
		$req['Menu']["store"] = "style='color:#FF0000;font-weight:bold;'";
		break;

}
$req['Menu'][$_REQUEST["cp"]] = "style='color:#FF0000;font-weight:bold;'";
$smarty -> assign('req',	$req);

unset($objAdminStore,$req,$xajax);
$smarty -> display('index.tpl');
unset($smarty);
exit;
?>
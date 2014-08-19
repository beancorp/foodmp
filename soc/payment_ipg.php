<?php
/**
 * Fri Nov 21 07:58:07 GMT 2008 07:58:07
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * Payment IPG control
 * ------------------------------------------------------------
 * \soc\payment_ipg.php
 */


@session_start();
include_once ('include/smartyconfig.php');
include_once ('class/ajax.php');
include_once ('maininc.php');
include_once ('class.soc.php');
include_once ('class.socbid.php');
include_once ('class.socstore.php');
include_once ('class.emailClass.php');
include_once ('class.paymentipg.php');
include_once ('functions.php');

$smarty -> loadLangFile('payment');

$objPayIPG = new paymentIPG();

$socObj = new socClass();
$socbidObj = new socbidClass();
$socstoreObj = new socstoreClass();

$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');

//display logo or not
unset($_SESSION['logo_old']);
unset($_SESSION['logo_new']);

$is_logo = 'true';
$menu_bgcolor = ' bgcolor="#65BFF3"';  //show the new left banner logo, set background color.
$menu_bottom =  ' bgcolor="#65BFF3"';
$keywordsList = 'flat rate selling, how to sell online, online trading post, sell goods online, sell items online, sell products online, sell stuff online, sell things online, selling online, simple selling online';

$_SESSION['logo_new'] = true;

switch($_REQUEST["cp"]){
	/*****IPG interface by test begin *****************/
	case 'regedit':case 'upgrade':
		//flower is test
		$socstoreObj	=	new socstoreClass();
		$isUpdate	=	!empty($_SESSION['StoreID']) && $_SESSION['StoreID'] >0 ? true : false;
		if ($_REQUEST['cp']=='next' || $_REQUEST['cp']=='save') {
			if ($socstoreObj->sellerSave($isUpdate)) {
				if ($_REQUEST['cp']=='next') {
					$socstoreObj -> destroyFormInputVar();
					header('Location:soc.php?act=signon&step=' . $stepOperate['nextStep']);
					exit;
				}else{
					$socstoreObj -> destroyFormInputVar();
					header('Location:soc.php?act=admin');
					exit;
				}
			}
		} else {
			$socstoreObj -> destroyFormInputVar();
		}

		$itemtitle = $socstoreObj->getTextItemTitle('Start Selling',2);
		$menubar = 	$socstoreObj->getStartsellingMenu($stepOperate);
		$smarty -> assign('itemTitle', 	$itemtitle.$menubar);
		$req = $socstoreObj->sellerValidation($isUpdate);
		$paypalInfo = $socstoreObj->getIPGInfo();
		$req['paymentMethod']	=	'ipg' ;

		if($_SESSION['UserID']!='' AND $_SESSION['level']==1){
			$req['agree']='1';
		}

		$req['act']	=	'ipg';
		$smarty -> assign('req', 		$req);
		$smarty -> assign('paypalInfo', $paypalInfo);
		$smarty -> assign('isUpdate', 	$isUpdate);

		$smarty -> assign('content', $smarty -> fetch('startselling_step1.tpl'));
		$smarty -> assign('sidebar', 0);

		unset($req,$socstoreObj);
		break;

	case 'keepon':
		//flower is test
		$socstoreObj	=	new socstoreClass();

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Pay Reports');
		$smarty -> assign('keywords','Pay Reports');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Pay Reports', 2));

		$req = $socObj->payReports();

		$paypalInfo = $socstoreObj->getIPGInfo();
		$req['paymentMethod']	=	'ipg' ;

		$smarty -> assign('paypalInfo', $paypalInfo);

		$smarty -> assign('req', $req);
		$content = $smarty -> fetch('payreports.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		unset($socstoreObj);
		break;

	case 'ipgconfirm':
		if($socstoreObj ->getFormInputVar()){
			$ary = $socstoreObj ->getFormInputVar();
			unset($_SESSION['pageParam']['bu_procode']);
			unset($ary['bu_procode']);	
		}else{
			header('Location:/soc.php?act=signon');
		}
		Header("Cache-Control: no-cache");
		$offset = 60 * 60 * 24 * 3;
		$ExpireString = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
		Header($ExpireString);
		$objAjax = new ajax();
		$req['element']['jsSubmit']	=	$objAjax -> regFun('paymentSubmit', array(array("mainIPGForm",0)));
		$objAjax -> processRequest();
		$req['ipgForm']	=	$objPayIPG -> getPaymentParams($ary);
		$req['history'] = "-2";
		$smarty -> assign('req', $req);
		$smarty -> assign('xajax_Javascript', $objAjax -> getJSInit());
		$content =	$smarty -> fetch('payment_ipg.tpl');
		unset($objAjax);
		$smarty -> assign('is_content',1);
		$smarty -> assign('content', $content);
		break;
		/*****IPG interface by test end *******************/

	default:
		if (!empty($_POST)) {
			$keepon = isset($_POST['keepon'])?$_POST['keepon']:"";
			if($keepon!='yes'&&isset($_POST['bu_user_default'])){
				if($_POST['bu_user']==""&&isset($_POST['bu_user_default'])){
					$_POST['bu_user'] = $_POST['bu_user_default'];
				}
				$arraySeting = array('attribute'=>"{$_POST['attribute']}",
				'StoreID'=>"{$_POST['StoreID']}",
				'email'=>"{$_POST['bu_user']}",
				'websitename'=>"{$_POST['bu_name']}",
				'urlstring'=>"{$_POST['bu_urlstring']}",
				'nickname'=>"{$_POST['bu_nickname']}");
				$errmsg = step1checkedvaild($arraySeting);
				if($errmsg!=""){
					$comm = new common();
					unset($_POST['subAttrib']);
					unset($_POST['attribute']);
					$comm->setFormInuptVar($_POST);
					$comm->addOperateMessage($errmsg);
					unset($comm);
					header('Location:soc.php?act=signon');
					exit();
				}

				if($_POST['StoreID']==""&&$_POST['bu_procode']!=""){
					unset($_POST['agree']);
					$socstoreObj ->setFormInuptVar($_POST);
					if($socstoreObj -> checkvalidpromot($_POST['bu_procode'], $_POST['attribute'])){

						$arrUserInfo	=	$socstoreObj ->getFormInputVar();
						$arrUserInfo['amount']		=	10;
						$arrUserInfo['bu_email']	=	$arrUserInfo['bu_user'];
						$booleanResult = $objPayIPG -> insertUserInfo($arrUserInfo);
						if($booleanResult){
							$socstoreObj -> destroyFormInputVar();
							$socstoreObj -> updatepromotcode($arrUserInfo['bu_procode'] ,$arrUserInfo['bu_user']);
							header('Location: /login_ipn.php?uname='.$arrUserInfo['bu_user'].'&password='.$arrUserInfo['bu_password'].'&user_type='.$arrUserInfo['attribute']);
						}else{
							header('Location: /soc.php?act=signon');
						}
						exit();
					}else{
						echo "<script language='javascript'>
							if(confirm('The promotion code which you entered is not invalid. Would you like to go ahead with the registration?')){
							location.href = '/soc.php?act=ipg&cp=ipgconfirm';
						}else{
							location.href = '/soc.php?act=signon';
						}
					  </script>";
						exit();
					}
				}
			}
			Header("Cache-Control: no-cache");
			$offset = 60 * 60 * 24 * 3;
			$ExpireString = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
			Header($ExpireString);
			$objAjax = new ajax();

			$req['element']['jsSubmit']	=	$objAjax -> regFun('paymentSubmit', array(array("mainIPGForm",0)));
			$objAjax -> processRequest();
			$req['ipgForm']	=	$objPayIPG -> getPaymentParams();
			$req['history'] = "-1";
                        $req['history_url']=$_SERVER["HTTP_REFERER"];
			$smarty -> assign('req', $req);
			$smarty -> assign('xajax_Javascript', $objAjax -> getJSInit());
			$content =	$smarty -> fetch('payment_ipg.tpl');
			unset($objAjax);
		}else{
			header('Location: /soc.php?act=signon');
		}

		$smarty -> assign('is_content',1);
		$smarty -> assign('content', $content);
		break;
}






//active the menu of top navigation
$smarty->assign('cp', $_REQUEST["cp"]);
$smarty->assign('requesturi', $_SERVER['REQUEST_URI']);

if (!empty($_SESSION)) {
	$userData = $_SESSION;
	$storeName = getStoreURLNameById($userData['StoreID']);
	if (!empty($storeName)) {
		$userData['website'] = clean_url_name($storeName);
	}
	$smarty -> assign('session', $userData);
}

$states = $socObj->getStatesList();
$smarty -> assign('sidebar', 0);
$smarty -> assign('states', $states);
$smarty -> assign('locations', $states);

if ($_REQUEST['statename'] && $_REQUEST['collegeid']) {
	$smarty -> assign('stateOnLoad', 	"selectCollegebyName('collegeobj', '". $statename ."&collegeid=".$_REQUEST['collegeid']."');");
}

$smarty -> assign('menu_bgcolor', $menu_bgcolor);
$smarty -> assign('menu_bottom', $menu_bottom);
$smarty -> assign('is_logo', $is_logo);
//end of display logo or not

unset($socObj);
include_once('soc/seo.php');
$smarty -> assign('menu_bgcolor', $menu_bgcolor);
$smarty -> display('index.tpl');
unset($smarty,$socObj,$socbidObj);
exit;
?>
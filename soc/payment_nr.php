<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
@session_start();
include_once ('include/smartyconfig.php');
include_once ('class/ajax.php');
include_once ('maininc.php');
include_once ('class.soc.php');
include_once ('class.socbid.php');
include_once ('class.socstore.php');
include_once ('class.emailClass.php');
include_once ('class.paymentNR.php');
include_once ('class.paymentipg.php');
include_once ('functions.php');


$smarty -> loadLangFile('payment');

$NR = new PaymentNR();

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

switch($_GET['cp']) {

    case '_request':
        
        parse_str($_POST['formData'], $data);
        if(empty($data)) {
            exit(json_encode(array('status'=>'false','msg'=>$_LANG['payment']['nr']['data_error'])));
        }
        if('' == trim($data['cardNumber'])) {
            exit(json_encode(array('status'=>'false','msg'=>$_LANG['payment']['nr']['card_number_empty'])));
        }
        if(trim($data['amount'])<=0) {
            exit(json_encode(array('status'=>'false','msg'=>$_LANG['payment']['nr']['price_error'])));
        }

        if(true === $NR->payNR($data)) {
			
            if(true === $NR->userProcess($data)) {
                exit(json_encode(array('status'=>'true','msg'=>'success','jumpPath'=>$NR->getJumpPath())));
            }
            
        }
        exit(json_encode(array('status'=>'false','msg'=>$NR->msg())));
       
        exit;
        break;
    default:
        if('POST' != $_SERVER['REQUEST_METHOD']) {
            header('Location: /soc.php?act=signon');
            exit;
        }
        
        $smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Pay Netregistry');
        if($_POST['StoreID']=="" and $_POST['bu_procode']!=""){
            unset($_POST['agree']);
            $socstoreObj ->setFormInuptVar($_POST);
            if($socstoreObj -> checkvalidpromot($_POST['bu_procode'], $_POST['attribute'])){
                $arrUserInfo	=	$socstoreObj ->getFormInputVar();
                $arrUserInfo['amount']		=	10;
                $arrUserInfo['bu_email']	=	$arrUserInfo['bu_user'];
                $booleanResult = $objPayIPG -> insertUserInfo($arrUserInfo);
                if($booleanResult){
						$bu_username = $arrUserInfo['attribute'] == 5 ? $arrUserInfo['bu_username'] : '';
                        $socstoreObj -> destroyFormInputVar();                        
                        $socstoreObj -> updatepromotcode($arrUserInfo['bu_procode'] ,$arrUserInfo['bu_user'], $bu_username);
                        
                        header('Location: /login_ipn.php?uname='.($arrUserInfo['attribute'] == 5 ? $arrUserInfo['bu_username'] : $arrUserInfo['bu_user']).'&password='.$arrUserInfo['bu_password'].'&user_type='.$arrUserInfo['attribute'].'&regsuc=1');
                }else{
                        header('Location: /soc.php?act=signon');
                }
                exit();
            }else{
            		unset($_POST['bu_procode']);
                    /*echo "<script language='javascript'>
                            if(!confirm('The promotion code which you entered is not invalid. Would you like to go ahead with the registration?')){
                                location.href = '/soc.php?act=signon';
                            }
                            </script>";*/
                    echo "<script language='javascript'>
                            	alert('The promotion code which you entered is not invalid.');
                                location.href = '/soc.php?act=signon';
                            </script>";
            }
        }

        Header("Cache-Control: no-cache");
        $offset = 60 * 60 * 24 * 3;
        $ExpireString = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        Header($ExpireString);

        $req['ipgForm']	=	$NR ->getPaymentParams();
        $req['history'] = "-1";
        $req['history_url']=$_SERVER["HTTP_REFERER"];

        
        //assign
        $smarty->assign('orderType', $NR->getOrderType());      //order type
        $smarty->assign('req', $req);
        $smarty -> assign('is_content',1);
        $content =	$smarty -> fetch('payment_nr.tpl');
        $smarty -> assign('content', $content);         //content

        break;
}

switch ($_REQUEST['attribute']) {
	case '1':
		$search_type = 'estate';
		break;

	case '2':
		$search_type = 'auto';
		break;

	case '3':
		$search_type = 'job';
		break;

	case '5':
		$search_type = 'foodwine';
		break;
	case '0':
	default:
		$search_type = '';
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
include_once('leftmenu.php');
include_once('soc/seo.php');
$smarty -> assign('menu_bgcolor', $menu_bgcolor);
$smarty -> display('index.tpl');
unset($smarty,$socObj,$socbidObj);
exit;
?>

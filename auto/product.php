<?php
/**
 * Thu Dec 11 00:27:00 GMT 2008 00:27:00
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * auto control function
 * ------------------------------------------------------------
 * auto\product.php
 */
 
@session_start();
include_once ('../include/smartyconfig.php');
include_once ('maininc.php');
include_once ('functions.php');
include_once ('class/pagerclass.php');
include_once ('class.emailClass.php');
include_once ('class.autoClass.php');
include_once ('class.upload.php');
include_once ('class.uploadImages.php');
include_once ('class/ajax.php');
include_once ('class.paymentNR.php');

include('../indexPart.php');
$indexTemplate	=	'../index.tpl';

$smarty -> loadLangFile('payment');

$NR = new PaymentNR();
$autoObj		=	new autoClass();
$socstoreObj	=	new socstoreClass();
$stepOperate	=	$socstoreObj->getStartSellingOperate();


switch($_REQUEST["cp"]){
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

        if(true === $NR->payFeeNR($data)) {
			
            if(true === $NR->feeProcess($data)) {
                exit(json_encode(array('status'=>'true','msg'=>'success','jumpPath'=>$NR->getJumpPath())));
            }
            
        }
        exit(json_encode(array('status'=>'false','msg'=>$NR->msg())));
       
        exit;
        break;
	case 'pay':
	case 'renew':
		
		$pay_type = $_REQUEST["cp"] == 'pay' ? 'productfee' : 'productrenew';
		$quantity =  $_REQUEST['quantity'];
		$product_feetype = $_REQUEST['feetype'] ? $_REQUEST['feetype'] : 'product';		
		$reviewKey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
        $arrSetting = array(
            'buyer_id' => $_SESSION['StoreID'],
            'StoreID' => $_SESSION['StoreID'],
            'reviewkey' => $reviewKey,
            'p_status' => 'order',
            'description' => 'NR',
            'order_date' => time(),
            'type' => $pay_type,
            'product_feetype' => $product_feetype,
            'price' => 10
        );
        
		if ($product_feetype == 'product') {
			$ckpid = $_REQUEST['ckpid'];
			if (is_array($ckpid)) {
				$quantity = count($ckpid);
				$pids = implode(',', $ckpid);	
			} else {
				$quantity = 1;
				$pids = $_REQUEST['pid'];
			}
			
        	$arrSetting['pids'] = $pids;
		}
		
		$fee = $socstoreObj->getProductFee(2, $product_feetype, $quantity);        
        $arrSetting['amount'] = number_format($fee, 2, '.', '');
        $arrSetting['month'] = $product_feetype == 'year' ? 12 : ($product_feetype == 'month' ? $quantity : 0);        
		$socObj->dbcon->insert_record($socObj->table . 'order_reviewref', $arrSetting);
        $ref_id = $socObj->dbcon->lastInsertId();		
		
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Pay Netregistry');
        Header("Cache-Control: no-cache");
        $offset = 60 * 60 * 24 * 3;
        $ExpireString = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        Header($ExpireString);

        $req['ref_id'] 					=	$ref_id;
        $req['orderType'] 				=	$pay_type;
        $req['ipgForm']					=	$NR ->getPaymentParamsFee($fee);
        $req['ipgForm']['request_url']	=	'/auto/?act=product&cp=_request';
        $req['history'] 				= 	"-1";
        $req['history_url']				=	'/soc.php?act=signon&step=4';

        
        //assign
        $smarty->assign('orderType', $NR->getOrderType());      //order type
        $smarty->assign('req', $req);
        $smarty -> assign('is_content',1);
        $smarty -> assign('sidebar', 0);
        $content =	$smarty -> fetch('../payment_nr.tpl');
        $smarty -> assign('content', $content);         //content

        break;

	default:
		if (!isset($_SESSION['level']) || ($_SESSION['level'] != 1)) {
			header('Location:/soc.php?cp=home');
			exit();
		}
		$op	=	$_REQUEST['op'];
		if (!empty($op)) {
			if ($autoObj->storeProductAddOrEditOperateOfAuto($op)) {
				if ($op == 'next') {
					$autoObj -> destroyFormInputVar();
					header('Location:/soc.php?act=signon&options='.$_REQUEST['options'].'&step=' . $stepOperate['nextStep']);
					exit;
				}elseif ($op=='del'){
					$arrTemp = $autoObj -> getFormInputVar();
					$autoObj -> destroyFormInputVar();
					header('Location:/auto/?act=product&options='.$_REQUEST['options'].'&step='.$stepOperate['step']."&msg=" . $arrTemp['msg']);
					exit;
				}elseif($op=='edit' || $op=='paynow' || $op=='paylater'){
					$arrTemp = $autoObj -> getFormInputVar();
					$autoObj -> destroyFormInputVar();
					header('Location:/soc.php?act=signon&options='.$_REQUEST['options'].'&step='.$stepOperate['step']."&msg=" . $arrTemp['msg']);
					exit;
				}elseif ($op=='upload'){
					$arrTemp = $autoObj -> getFormInputVar();
					$autoObj -> destroyFormInputVar();
					$_SESSION['showmore'] = $arrTemp['error_more'];
					header('Location:/soc.php?act=signon&options='.$_REQUEST['options'].'&step='.$stepOperate['step']."&msg=" . urlencode($arrTemp['msg']));
					exit;
				}else{
					$autoObj -> destroyFormInputVar();
					header('Location:/soc.php?act=admin');
					exit;
				}
			}else{
				$arrTemp = $autoObj -> getFormInputVar();
				$autoObj -> destroyFormInputVar();
				header('Location:/soc.php?act=signon&options='.$_REQUEST['options'].'&step='.$stepOperate['step']."&msg=".$arrTemp["msg"]);
				exit;
			}
		}
		
		
		$itemtitle = $socstoreObj->getTextItemTitle('Start Selling',3);
		$menubar = 	$socstoreObj->getStartsellingMenu($stepOperate, true);
		$smarty -> assign('itemTitle', 	$itemtitle.$menubar);
		$smarty -> assign('pageTitle', 	'The SOC Exchange');

		//$storeName = getStoreByName($_SESSION['StoreID']);
		$storeName = getStoreURLNameById($_SESSION['StoreID']);
		if (!empty($storeName)) {
			$storeName = clean_url_name($storeName);
		}

		$smarty -> assign('storeName', $storeName);

		$req = $autoObj -> storeProductAddOrEditOfAuto();
		$isEditOption = (!empty($req['select']['pid']) || $op == 'del') ? true : false;
		$req['select']['options'] = $_REQUEST['options'] ? $_REQUEST['options'] : ($isEditOption ? 'edit' : '');
		
		$objAjax	=	new ajax();
		$req['element']['jsSector']	=	$objAjax -> regFun('getSectorList',array(array('this.value',5),array('this.id',5),array(3,5),array('Select a Make',4),array('true',5)));
		
		$req['element']['jsSuburb']	=	$objAjax -> regFun('getSuburbList',array(array('this.value',5),array('this.id',5),array(2,5),array('Select a City/ Suburb',4)));
		
		$objAjax -> processRequest();

		$req['msg'] = $_REQUEST['msg'];
		if(isset($_SESSION['showmore'])&&$_SESSION['showmore']!=""){
			$req['msg'] .= $_SESSION['showmore'];
			unset($_SESSION['showmore']);
		}
		$req['StoreID'] = $_SESSION['StoreID'];
		$tmp = $socObj -> displayStoreWebside();
		$tmp['info']['hasitems'] = count($req['list']) > 0 ? true : false;
		$req['info'] = $tmp['info'];
		$smarty -> assign('xajax_Javascript', $objAjax -> getJSInit());
		$smarty -> assign('PBDateFormat',DATAFORMAT_DB);
		$smarty -> assign('cur_time',time());
		$smarty -> assign('req', 		$req);
		$smarty -> assign('content', $smarty -> fetch('auto_product_save.tpl'));
		$smarty -> assign('sidebar', 0);
		$smarty -> assign('search_type','auto');
		$smarty->assign("is_content",1);

		break;
}
	if($_REQUEST['multcp']){
		switch ($_REQUEST['multcp']){
			case 'delete':
				if(count($_REQUEST['ckpid'])){
					if($autoObj->mulitopeartionauto('delete',$_REQUEST['ckpid'])){
						$multmsg = "Deleted successfully.";
					}else{
						$multmsg = "Failed to delete.";
					}
				}else{
					$multmsg = "Failed to delete.";
				}
				
				break;
			case 'publish':
				if(count($_REQUEST['ckpid'])){
					if($autoObj->mulitopeartionauto('publish',$_REQUEST['ckpid'])){
						$multmsg = "Published successfully.";
					}else{
						$multmsg = "Failed to publish.";
					}
				}else{
					$multmsg = "Failed to publish.";
				}
				break;
			case 'unpublish':
				if(count($_REQUEST['ckpid'])){
					if($autoObj->mulitopeartionauto('unpublish',$_REQUEST['ckpid'])){
						$multmsg = "Unpublished successfully.";
					}else{
						$multmsg = "Failed to unpublish.";
					}
				}else{
					$multmsg = "Failed to unpublish.";
				}
				break;
			default:
				break;
		}
		header("Location: /auto/?act=product&options=edit&step=4&msg=$multmsg");
		exit();
	}

$partBottom 	=	true;
include('../indexPart.php');
exit;
?>
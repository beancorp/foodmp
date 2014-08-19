<?php
@session_start();
include_once ('include/config.php');
include_once ('include/smartyconfig.php');
include_once ('maininc.php');
include_once ('class.emailClass.php');
include_once ('class.soc.php');
include_once ('class/pagerclass.php');
include_once ('functions.php');
include_once ('class.wishlist.php');
include_once ('class.uploadImages.php');
include_once ("class.processcsv.php");

if($_SERVER['SERVER_PROTOCOL']=='HTTP/1.1'){
	$hosturl="http://{$_SERVER['HTTP_HOST']}";
}else{
	$hosturl="https://{$_SERVER['HTTP_HOST']}";
}
$hosturl=$securt_url;
$smarty -> assign('securt_url',$securt_url);
$smarty -> assign('normal_url',$normal_url);

$strcp = isset($_REQUEST['cp'])?$_REQUEST['cp']:"";
$socObj = new socClass();
$wishlist = new wishlist();
$smarty -> assign('pageTitle',getStoreByName(($_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:''))).'  - Wish List @ SOC Exchange Australia');
switch ($strcp){
	case 'buy':
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		if(!$socObj->checkwebsiteinvaild($StoreID)){
			echo "<script>alert('Website name does not exist.');location.href='/soc.php?cp=home';</script>";
			exit;
		}
		$_SESSION['StoreID'] = $StoreID;
		$smarty->assign('hosturl',$hosturl);
		if(isset($_SESSION['ShopID'])&&$_SESSION['ShopID']){
			$smarty->assign('buyerinfo',getCustomInfo($_SESSION['ShopID']));
		}
		$templateInfo = $socObj -> getTemplateInfo();
		$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty -> assign('is_website', 1);
		$smarty->assign('headerInfo', $headerInfo['info']);
		
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header
		$req = $wishlist->wishlistItemsProduct($StoreID,$_REQUEST['pid']);
		$req= $req[0];
		$req['template'] = $headerInfo['template'];
		$req['image_name'] = file_exists(ROOT_PATH.$req['image_name']['name'])?$req['image_name']['name']:"";
		if($req['protype']){
			$req['fotgive'] = $req['price'];
		}else{
			$req['fotgive'] = $req['price']-$req['gifted'];
		}
		$req['info'] = $wishlist->getwishlistInfo($StoreID);
		$req['info']['bu_name'] = getStoreByName($StoreID);
		$req['info']['SELLER'] = getCustomInfo($StoreID);
		//var_dump($req);
		$smarty->assign('itemTitle', $socObj->getTextItemTitle('Gift this item', 4, substr($req['info']['color'],1)));
		$smarty -> assign('req', $req);
		$content =	$smarty->fetch('wishlist/wishlist_buy.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('div','list');
		$smarty->assign('sidebar',0);
		break;
	case 'payment':
		$isneworder = true;
		$productlink = $hosturl."/soc.php?act=wishlistproc&cp=buy&StoreID={$_POST['StoreID']}&pid={$_POST['pid']}";
		if(isset($_SESSION['orderinfo'])&&isset($_SESSION['orderinfo'][$_POST['StoreID']][$_POST['pid']])&&$_SESSION['orderinfo'][$_POST['StoreID']][$_POST['pid']]&&$_SESSION['orderinfo'][$_POST['StoreID']][$_POST['pid']."_amount"]==$_POST['amount']){
			$orderID = $_SESSION['orderinfo'][$_POST['StoreID']][$_POST['pid']];
			$isneworder = $wishlist->chekcisneworder($orderID);
		}
		if($isneworder){
			if(!magic_quotes_runtime()){$addsc=true;}else{$addsc=false;}
			$arySeting['pid'] = $_POST['pid'];
			$arySeting['buyer_id'] = isset($_SESSION['ShopID'])?$_SESSION['ShopID']:0;
			$arySeting['StoreID'] = $_POST['StoreID'];
			$arySeting['order_date'] = time();
			$arySeting['p_status']	= "order";
			$arySeting['name']	= $addsc?stripslashes($_POST['yourname']):$_POST['yourname'];
			$arySeting['email'] = $addsc?stripslashes($_POST['youremail']):$_POST['youremail'];
			$arySeting['message'] = $addsc?stripslashes($_POST['message']):$_POST['message'];
			$arySeting['amount'] = $_POST['tmp_price'];
			$arySeting['total_amount'] = $_POST['amount'];
			$arySeting['item_name']	= $_POST['item_name'];
			$arySeting['price']	= $_POST['old_price'];
			$arySeting['ecardtpl'] = $_POST['ecardtpl'];
			
			$retval = 1;
			$dbcon->beginTrans();	
			$query = "SELECT * FROM {$table}wishlist where pid={$arySeting["pid"]}";
			$dbcon->execute_query($query);
			$result = $dbcon->fetch_records(true);
			$amounts = $_POST['tmp_price'];
			if($result[0]['protype']){
				$query = "update {$table}wishlist set gifted=gifted+$amounts where pid={$arySeting['pid']}";
				if(!$dbcon->execute_query($query)){
					if (TRANSACTION_DEBUG > 0){
						echo "Failed to set Amount to gift. ".$arySeting['pid'].'='.$amounts;
					}
					$retval = 0;
				}
			}else{
				if($result[0]['price']*100-$result[0]['gifted']*100<$amounts*100){
					if (TRANSACTION_DEBUG > 1){
						echo "Amount to gift is greater than the item price.";
					}
					$retval = 0;
				}else {
					$query = "update {$table}wishlist set gifted=gifted+$amounts where pid={$arySeting['pid']}";
					if(!$dbcon->execute_query($query)){
						if (TRANSACTION_DEBUG > 0){
							echo "Failed to set Amount to gift. ".$arySeting['pid'].'='.$amounts;
						}
					$retval = 0;
					}
				}
			}
			if ($retval == 0) {
				$dbcon->rollbackTrans();
				$dbcon->endTrans();
				$query = "SELECT price-gifted as amount FROM {$table}wishlist where pid={$arySeting["pid"]}";
				$dbcon->execute_query($query);
				$result = $dbcon->fetch_records();
				if ($result[0]['amount']>0){
					echo "<script>alert('The Amount to gift should not be larger than ".$result[0]['amount'].". Please try again.');location.href='".$productlink."';</script>";
				}else{
					echo "<script>alert('The Amount to gift is 0.');location.href='".$productlink."';</script>";
				}
				exit;
			} else {
				$dbcon->commitTrans();
				$dbcon->endTrans();
				$arySeting['paid_method'] = $_POST['payment_M'];
				$arySeting['message'] .= "\n\n".($addsc?stripslashes($_POST['signature']):$_POST['signature']);
				$dbcon->insert_query($table."wishlist_order",$arySeting);
				$orderID = $dbcon->insert_id();
				$_SESSION['orderinfo'][$arySeting['StoreID']][$arySeting['pid']] = $orderID;
				$_SESSION['orderinfo'][$arySeting['StoreID']][$arySeting['pid']."_amount"]= $arySeting['amount'];
				$productlink = $hosturl.$wishlist->getProURL($arySeting['StoreID'],$arySeting['pid']);
				$sellerInfo = $wishlist->getSellerInfo($arySeting['StoreID'],$arySeting['pid']);
				
				if($_POST['payment_M']=='googlecheckout'){
					$arrParams = array('accept'=>'seller',
								   'Subject'=>'Item gifted on SOC Exchange Australia',
								   'buyer_nickname'=>$arySeting['name'],
								   'buyer_email'=>$arySeting['email'],
								   'message'=>$arySeting['message'],
								   'amount'=>$arySeting['amount'],
								   'total_amount'=>$arySeting['total_amount'],
								   'item_name'=>$arySeting['item_name'],
								   'productLink'=>$productlink,
								   'wishlistLink'=>$hosturl.$wishlist->getProURL($arySeting['StoreID']),
								   'seller_name'=>$sellerInfo['bu_name'],
								   'To'=>$sellerInfo['bu_email'],
								   'email_regards'=>$email_regards);
					
					if(($arrParams['total_amount']*100-$arrParams['amount']*100)>0){
						if($_POST['payment_M']=='paypal'){
							$arrParams['payInculde']='Paypal charges included';
						}else{
							$arrParams['payInculde']='GoogleCheckOut charges included';
						}
					}
					$objEmail	=	new emailClass();
					$objEmail -> send($arrParams,'wishlist/wishlist_email.tpl',true);
					unset($objEmail);
					$arrParams['accept'] = 'buyer';
					$arrParams['To']	= $arySeting['email'];
					$objEmail	=	new emailClass();
					$objEmail -> send($arrParams,'wishlist/wishlist_email.tpl',true);
					unset($objEmail);
				}
			}
		}
		if($_POST['payment_M']=='paypal'){
			$req['paypal_info'] = $_POST;
			$req['paypal_info']['custom'] = $orderID;
			if(PAYPAL_DEBUG == 1){
				$req['paypal_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr/';
			}else{
				$req['paypal_url'] = 'https://www.paypal.com/cgi-bin/webscr/';
			}
			$req['paypal_title'] = "Paypal Information";
			$smarty->assign('req',$req);
			$smarty->display('paypal.tpl');exit();
		}elseif($_POST['payment_M']=='googlecheckout'){
			
			include_once "ecardsend.php";
			
			$ecard = new ecardClass();
			
			$query = "SELECT * FROM {$table}wishlist_order WHERE OrderID='$orderID'";
			$dbcon->execute_query($query);
			$result = $dbcon->fetch_records(true);
			$orderInfo = $result[0];
			
			$query = "SELECT * FROM {$table}bu_detail WHERE StoreID='{$orderInfo['StoreID']}'";
			$dbcon->execute_query($query);
			$result = $dbcon->fetch_records(true);
			$sellerInfo = $result[0];
			
			$query = "SELECT * FROM {$table}wishlist a left join {$table}wishlist_image b on a.pid=b.pid and a.StoreID=b.StoreID and b.attrib=0 and b.sort=0 where a.pid={$orderInfo['pid']} ";
			$dbcon->execute_query($query);
			$result = $dbcon->fetch_records(true);
			$proinfo = $result[0];
			$profile = "/images/243x212.jpg";
			if($proinfo['smallPicture']){
				if(file_exists(ROOT_PATH.$proinfo['smallPicture'])){
					$profile = $proinfo['smallPicture'];
				}
			}
			$products = array('nickname'=>$sellerInfo['bu_nickname'],'message'=>substr(strip_tags($orderInfo['message']),0,250).(strlen(strip_tags($orderInfo['message']))>250?"...":""),'buyerName'=>$orderInfo['name'],'proPrice'=>number_format($orderInfo['total_amount'],2),'proName'=>substr($proinfo['item_name'],0,30).(strlen($proinfo['item_name'])>30?"...":""),'proDesc'=>substr(strip_tags($proinfo['description']),0,60).(strlen(strip_tags($proinfo['description']))>60?"...":""),'profile'=>$profile);
			if(($orderInfo['total_amount']*100-$orderInfo['amount']*100)>0){
				$products['payInculde']='GoogleCheckOut charges included';
			}
			$subject = "Wishlist Ecard";
			$message .= $ecard->bulidheader($subject,$orderInfo['name'].' <noreply@thesocexchange.com>',$sellerInfo['bu_nickname'].' <'.$sellerInfo['bu_email'].'>');
			
			$message .= $ecard->bulidmessagecontent('0016e6496c3e0aad8d0475778edd',$products,$orderInfo['ecardtpl'],'googlecheckout');
			@mail($sellerInfo['bu_email'],$subject,'',$message);		
			@mail($orderInfo['email'],$subject,'',$message);		
			$wishlist->googlepayment($orderID,$_SESSION['StoreID'],$arySeting['total_amount'],$arySeting['item_name']);
		}
		
		break;
	case 'emailfriends':
		if($_SESSION['UserID']=='' AND $_SESSION['level']!=2){
			header("Location:index.php");
		}
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Email My Wish List to family and friends');
		$smarty -> assign('keywords','Email My Wish List to family and friends');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Email My Wish List to family and friends', 8,'','/soc.php?act=wishlist&step=4'));
		$sid = $_SESSION['ShopID'];
		$req['own_name'] = $_SESSION['NickName'];
		$req['own_email'] = $_SESSION['email'];
		$wishlistinfo = $wishlist->getwishlistInfo($sid);
		$password = ''; 
		if($wishlistinfo['isprotected']){
			$password =  $wishlistinfo['password'];
		}
		
		if(isset($_REQUEST['optval'])&&$_REQUEST['optval']=="send"){
			$own_name = $_REQUEST['own_name']!=""?$_REQUEST['own_name']:$_SESSION['NickName'];
			$own_email = $_REQUEST['own_email']!=""?$_REQUEST['own_email']:$_SESSION['email'];
			
			if($_POST['is_choose_upload']==1){
				$procsv = new processcsv();
				$csvAry =$procsv->showCSVEmail($_SESSION['ShopID'],'wishlist');
				$k =0;
				if($csvAry){
					foreach ($csvAry as $pass){	
						$arrParams = array('To'=>$pass['emailAddress'],
										   'Subject'=>$own_name.'\'s wish list invitation',
										   'fromName'=>get_magic_quotes_gpc()?stripslashes($own_name):$own_name,
										   'From'=>$own_email,
										   'seller_name'=>$_SESSION['UserName'],
										   'nickname'=>$pass['emailName'],
										   'password'=>$password,
										   'message'=>$_REQUEST['message'],
										   'signature'=>$_REQUEST['signature'],
										   'webside_link'=>	'http://'.$_SERVER['HTTP_HOST'].'/'.$_SESSION['urlstring']."/wishlist");
					    if(get_magic_quotes_gpc()){
					   	   $arrParams = stripslashes_deep($arrParams);
					    }
						$objEmail	=	new emailClass();
						if(@$objEmail -> send($arrParams,'wishlist/wishlist_contact_email.tpl')){
							$arySetings =array('Subject'=>$own_name.'\'s wish list invitaction',
											'seller_name'=>$_SESSION['UserName'],
											'password'=>$password,
											'message'=>$_REQUEST['message'],
											'webside_link'=>'http://'.$_SERVER['HTTP_HOST'].'/'.$_SESSION['urlstring']."/wishlist",
											'addtime'=>time(),
											'email'=>$pass['emailAddress'],
											'signature'=>$_REQUEST['signature'],
											'nickname'=>$pass['emailName'],
											'StoreID'=>$_SESSION['ShopID'],
											'fromName'=>$own_name,
											);
							$wishlist->saveEmailFriends($arySetings);
							$k++;
						}
						unset($objEmail);
					}
				}
			}else{
				for ($i=0,$k=0;$i<10;$i++){
					if (eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",$_REQUEST['emailaddress'][$i])) { 		
						
						$arrParams = array('To'=>$_REQUEST['emailaddress'][$i],
										   'Subject'=>$own_name.'\'s wish list invitation',
										   'fromName'=>get_magic_quotes_gpc()?stripslashes($own_name):$own_name,
										   'From'=>$own_email,
										   'seller_name'=>$_SESSION['UserName'],
										   'nickname'=>$_REQUEST['nickname'][$i],
										   'password'=>$password,
										   'message'=>$_REQUEST['message'],
										   'signature'=>$_REQUEST['signature'],
										   'webside_link'=>	'http://'.$_SERVER['HTTP_HOST'].'/'.$_SESSION['urlstring']."/wishlist");
										   if(get_magic_quotes_gpc()){
										   	   $arrParams = stripslashes_deep($arrParams);
										   }
						$objEmail	=	new emailClass();
						if(@$objEmail -> send($arrParams,'wishlist/wishlist_contact_email.tpl')){
							$arySetings =array('Subject'=>$own_name.'\'s wish list invitaction',
											'seller_name'=>$_SESSION['UserName'],
											'password'=>$password,
											'message'=>$_REQUEST['message'],
											'webside_link'=>'http://'.$_SERVER['HTTP_HOST'].'/'.$_SESSION['urlstring']."/wishlist",
											'addtime'=>time(),
											'email'=>$_REQUEST['emailaddress'][$i],
											'signature'=>$_REQUEST['signature'],
											'nickname'=>$_REQUEST['nickname'][$i],
											'StoreID'=>$_SESSION['ShopID'],
											'fromName'=>$own_name,
											);
							$wishlist->saveEmailFriends($arySetings);
							$k++;
						}
						unset($objEmail);
					}
				}
			}
			if($k>1){
				$req['msg'] = "$k emails have been sent.";
			}else{
				$req['msg'] = "$k email has been sent.";
			}
			$req['nickname'] = $_REQUEST['nickname'];
			$req['emailaddress'] = $_REQUEST['emailaddress'];
			$req['own_name'] = $_REQUEST['own_name'];
			$req['own_email'] = $_REQUEST['own_email'];
			$req['message'] = $_REQUEST['message'];
			$req['signature'] = $_REQUEST['signature'];
		}
		if(get_magic_quotes_gpc()){
		   $req = stripslashes_deep($req);
		}
		$smarty -> assign('req',$req);
		$content = $smarty -> fetch('wishlist/wishlist_contact.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty -> assign('is_content',2);
		break;
	case 'wishmsglist':
		if($_SESSION['UserID']=='' AND $_SESSION['level']!=2){
			header("Location:index.php");
			exit();
		}
		include_once ('xajax/xajax_core/xajax.inc.php');
		function getwishmsg_list($page,$sid){
			$objResponse 	= new xajaxResponse();
			$wishlist = new wishlist();
			$wishlist -> smarty -> assign('PBDateFormat',DATAFORMAT_DB);
			$req['maillist'] = $wishlist -> getwishmsgList($sid,$page);
			$wishlist -> smarty -> assign('req',	$req);
			$content = $wishlist -> smarty -> fetch('wishlist/wishlist_emaillist.tpl');
			$objResponse -> assign("refcontent",'innerHTML',$content);
			return $objResponse;
		}
		$xajax 		= new xajax();
		$xajax -> registerFunction('getwishmsg_list');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Email My Wish List to family and friends');
		$smarty -> assign('keywords','Email My Wish List to family and friends');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Email My Wish List to family and friends', 8,'','/soc.php?act=wishlistproc&cp=emailfriends'));
		$sid = $_SESSION['ShopID'];
		$req['maillist'] = $wishlist -> getwishmsgList($sid,1);
		$smarty -> assign('req',$req);
		$content = $smarty -> fetch('wishlist/wishlist_msglist.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty -> assign('is_content',2);
		break;
	case 'wishlistmsg':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Email My Wish List to family and friends');
		$smarty -> assign('keywords','Email My Wish List to family and friends');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Email My Wish List to family and friends', 6));
		if(isset($_REQUEST['msgid'])&&$_REQUEST['msgid']!=""){
			$emailinfo = $wishlist->getwishlistemails($_REQUEST['msgid']);
			$smarty->assign('req',$emailinfo);
			$content = $smarty->fetch('wishlist/wishlist_contact_email.tpl');
			$smarty -> assign('content', $content);
			$smarty -> assign('sidebar', 0);
			$smarty -> assign('is_content',2);
		}
		break;
}
$smarty -> assign('sidebar', 0);
$smarty -> assign('search_type',$search_type);
$smarty->assign("is_content",1);
$smarty->assign('isstorepage',1);
include('leftmenu.php');
include_once('soc/seo.php');
$smarty -> assign('act', $_REQUEST['act']);
$smarty->assign('modcp','wishlist');
$smarty -> display($template_tpl);		
?>

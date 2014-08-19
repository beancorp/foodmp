<?php
/**
 * store seting
 * Wed Feb 13 08:58:45 CST 2008 08:58:45
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * store_set.php
 */
@session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified:   " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");     //   HTTP/1.1
header ("Pragma: no-cache");

include_once ('include/smartyconfig.php');
include_once ('include/class.soc.php');
include_once ('include/class.socstore.php');
include_once ('include/class.producttag.php');
include_once ('include/class.emailClass.php');
include_once ('include/maininc.php');
include_once ('include/functions.php');
include_once ('class.upload.php');
include_once ('class.uploadImages.php');

$smarty -> assign('pageTitle',	$_LANG['pageTitle']);
$smarty -> assign('keywords',	$_LANG['keywords']);
$smarty -> assign('description',$_LANG['description']);

//display logo or not
unset($_SESSION['logo_old']);
unset($_SESSION['logo_new']);
$is_logo = '';
$menu_bgcolor = '';
$menu_bottom = '';

if (isset($_REQUEST['act']) & $_REQUEST['act'] == 'signon') {
  $smarty->assign('show_signon_banner_small', 1);
}
if (isset($_REQUEST['cp'])) {
if ( ($_REQUEST['cp'] == 'home')
|| ($_REQUEST['cp'] == 'category')
|| ($_REQUEST['cp'] == 'features')
|| ($_REQUEST['act'] == 'signon' && empty($_REQUEST['step']))
|| ($_REQUEST['cp'] == 'cgeton')
|| ($_REQUEST['cp'] == 'faq')
|| ($_REQUEST['cp'] == 'payments')
|| ($_REQUEST['cp'] == 'prolist') ) {

	$_SESSION['logo_new'] = true;

}elseif ( ($_REQUEST['cp'] == 'contact')
|| ($_REQUEST['cp'] == 'protection')){

	$_SESSION['logo_old'] = true;

}
}

if ( isset($_SESSION['logo_new']) && $_SESSION['logo_new'] === true) {
	$is_logo = 'true';
	$menu_bgcolor = ' bgcolor="#65BFF3"';  //show the new left banner logo, set background color.
	$menu_bottom =  ' bgcolor="#65BFF3"';
}elseif (isset($_SESSION['logo_old']) && $_SESSION['logo_old'] === true){
	$menu_bottom = ' class="ltpanel_bot_slogo"';  //show old logo
	$menu_bgcolor = ' background="images/ltpanel_bgrep.jpg"';  //show old logo
	$is_logo = 'true';
}else{
	$menu_bgcolor = ' class="ltpanel_bot"';	//other set
	$menu_bottom = ' class="ltpanel_bot"';	//other set
}

if($_REQUEST['step']=='payment_eway')
    $smarty -> assign('eway','foodwine');

$smarty -> assign('menu_bgcolor', $menu_bgcolor);
$smarty -> assign('menu_bottom', $menu_bottom);
$smarty -> assign('is_logo', $is_logo);
$smarty -> assign('securt_url',$securt_url);
$smarty -> assign('normal_url',$normal_url);

$iframe_attribute = '';
if (isset($_GET['iframe'])) {
	$smarty->assign('hide_template', 1);
	$iframe_attribute = '&iframe=1';
}
//end of display logo or not

$socstoreObj = new socstoreClass();
$stepOperate = $socstoreObj->getStartSellingOperate();
/**
 * added by YangBall, 2011-03-29
 * free_reg_suc
 */
if('frs' == $_REQUEST["step"]) {
    $stepOperate = array('name'=>'free_reg_suc','nextStep'=>2,'step'=>'');
}

//END-YangBall
//print_r($stepOperate);
switch($stepOperate['name']){
	//get suburb by ajax
	case 'suburb':
		$Subburb	= '<select name="bu_suburb" id="bu_suburb" style="width: 200px;" class="validate[required]">';
		$stateid = $_REQUEST['SID'];
		$cities = getSuburbArray($stateid ? $stateid : 0,$_REQUEST['suburb']);
		$Subburb.= '<option value="">Select Suburb</option>';
		foreach ($cities as $row){
			$Subburb.= '<option value="'.$row['bu_suburb'].'" ';
			//			$Subburb.= ($row['selected'])?'selected':'';
			$Subburb.= '>'.$row['bu_suburb'].'</option>';
		}
		//$Subburb	.=	getSubburb1($_REQUEST['SID'],$_REQUEST['suburb']);
		$Subburb	.= "</select>";
		unset($socstoreObj);
		echo $Subburb;
		exit;
		break;

	case 'college':
		$stateid = intval($_REQUEST['SID']);
		$College	= "<select name='bu_college' id='bu_college' class='inputB'>";
		$College	.=	$socstoreObj->getCollegesByState($stateid,$_REQUEST['college']);
		$College	.= "</select>";
		unset($socstoreObj);
		echo $College;
		exit;
		break;

	case 'getCollege':
		$statename 	= $_REQUEST['statename'];
		$stateid	= getStateByID($statename);
		if ($stateid > 0) {
			if ($_REQUEST['redirect'] == 1) {
				if ($_REQUEST['type'] == 1) {
					$College	= "<select name='collegeid' id='collegeid'  class='region' style='width:235px;' onchange=\"location.href='soc.php?cp=collegepage&statename='+document.statesearch.statename.value+'&collegeid='+this.value\">";
				} else {
					$College	= "<select name='collegeid' id='collegeid'  class='region' onchange=\"location.href='soc.php?cp=collegepage&statename='+document.unisearch.bu_state.value+'&collegeid='+this.value\">";
				}
				$College	.= '<option value="">Colleges/Universities</option>';
			} else {
				$College	= "<select name='collegeid' id='collegeid'>";
				$College	.= '<option value="" html="">Colleges/Universities</option>';
			}
			$College	.=	$socstoreObj->getCollegesByState($stateid,$_REQUEST['collegeid']);
			$College	.= "</select>";
			unset($socstoreObj);
		} else {
			$College	= "<select name='collegeid' id='collegeid' class='inputB'>";
			$College	.= '<option value="">Colleges/Universities</option>';
			$College	.= "</select>";
		}
		echo $College;
		exit;
		break;
	case 'getCollege2':
		$statename 	= $_REQUEST['statename'];
		$stateid	= getStateByID($statename);
		$College	.= '<option value="" html="">Colleges/Universities</option>';
		$College	.=	$socstoreObj->getCollegesByState($stateid,$_REQUEST['collegeid']);
		echo $College;
		exit();
		break;
	case 'subcategory':
		$Subburb	= "<select name='categorySubList' class='text' id='categorySubList'>";
		$Subburb.= "<option value=''>Select a Subcategory</option>";
		if (is_numeric($_REQUEST['cid'])){
			$arrTemp	=	$socstoreObj-> _getProductCategoryList($_REQUEST['cid']);
			if (is_array($arrTemp)) {
				foreach ($arrTemp as $temp){
					if ($_REQUEST['subid'] == $temp['id']) {
						$Subburb	.=	"<option value='$temp[id]' selected>$temp[name]</option>";
					}else{
						$Subburb	.=	"<option value='$temp[id]'>$temp[name]</option>";
					}
				}
			}
		}
		$Subburb	.= "</select>";
		unset($socstoreObj);
		echo $Subburb;
		exit;
		break;

	case 'subburb':
		if (empty($_REQUEST['state'])){
			$state = 'NSW';
		}else{
			$state = $_REQUEST['state'];
		}
		$Subburb = "<select id=\"selectSubburb\" name=\"selectSubburb\" class=\"select2\">";
		$Subburb.= getSubburb($state);
		$Subburb.= "</select>";
		$Subburb.= "<script>document.getElementById('stateName').innerHTML='".getStateDescByName($state)."';</script>";
		unset($socstoreObj);
		echo $Subburb;
		exit;
		break;

	case 'statename':
		if (empty($_REQUEST['state'])){
			$state = 'NSW';
		}else{
			$state = $_REQUEST['state'];
		}
		echo getStateDescByName($state);
		exit;
		break;

	case 'checkStockQuantity':
		$pid		= intval($_POST['pid']);
		$quantity 	= intval($_POST['quantity']);

		$stockQuantity = $socstoreObj->getStockQuantityByPid($pid);

		echo $stockQuantity;

		unset($stockQuantity,$socstoreObj);
		exit;
		break;

	case 'checkEmailExist':
		$email = addslashes($_POST['bu_user']);
		$attribute = addslashes($_POST['attribute']);
		if ($attribute == 5) {
			$strCondition = " WHERE user='".$email."' and attribute in('4')";
		} else {
			$strCondition = " WHERE user='".$email."' and attribute in('$attribute','4')";
		}

		if ($_SESSION['level'] == 2) {
			$strCondition .= " AND StoreID<>'".$_SESSION['ShopID']."'";
		}
		//checking weather user exists or not in the login table
		if (!empty($email)) {
			if (!$socstoreObj -> dbcon-> checkRecordExist($socstoreObj->table."login", $strCondition)) {
				//email is not availble
				if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
					echo 'invalid';
				} else {
					echo 'verified';
				}
			} else {
				//email existed
				echo "existed";
			}
		} else {
			echo 'empty';
		}

		exit;
		break;

	case 'checkWebsiteExist':
		$bu_name = clean_url_name($_POST['bu_name']);
		$strCondition = " WHERE t1.store_name='".$bu_name."' AND ((t2.renewalDate > " .time().") or (t2.`attribute`=3 && t2.`subAttrib`=3))";

		if ($_SESSION['ShopID']) {
			$strCondition .= " AND t1.StoreID<>'".$_SESSION['ShopID']."'";
		}
		$query = $socstoreObj->table."login as t1 left join ".$socstoreObj->table."bu_detail as t2 on t1.StoreID=t2.StoreID";

		if (!empty($bu_name)) {
			if (!$socstoreObj -> dbcon-> checkRecordExist($query, $strCondition)) {
				if (!checkTmpcustomExist(array('bu_urlstring'=> $bu_name) )) {
					echo 'verified';
				}else{
					echo "existed";
				}
			} else {
				echo "existed";
			}
		} else {
			echo 'empty';
		}

		exit;
		break;

	case 'checkUsernameExist':
		$bu_username = $_POST['bu_username'];
		$strCondition = " WHERE t1.username='".$bu_username."' AND ((t2.renewalDate > " .time().") or (t2.`attribute`=3 && t2.`subAttrib`=3))";

		if ($_SESSION['ShopID']) {
			$strCondition .= " AND t1.StoreID<>'".$_SESSION['ShopID']."'";
		}
		$query = $socstoreObj->table."login as t1 left join ".$socstoreObj->table."bu_detail as t2 on t1.StoreID=t2.StoreID";

		if (!empty($bu_username)) {
            $namereg = '/["<>\\\'\\\]{1,}/i';
			if (preg_match($namereg, $bu_username) > 0) {
				echo  'invalid';
			} elseif (!$socstoreObj -> dbcon-> checkRecordExist($query, $strCondition)) {
				if (!checkTmpcustomExist(array('bu_urlstring'=> $bu_username) )) {
					echo 'verified';
				}else{
					echo "existed";
				}
			} else {
				echo "existed";
			}
		} else {
			echo 'empty';
		}

		exit;
		break;
		
	case 'checkNicknameExist':
		if (!empty($_POST['bu_nickname'])) {
			$strCondition = " WHERE bu_nickname = '".$_POST['bu_nickname']."' AND is_popularize_store = 0";
			if ($_SESSION['ShopID']) {
				$strCondition .= " AND StoreID<>'".$_SESSION['ShopID']."'";
			}
			$query = $socstoreObj->table."bu_detail";

			if (!$socstoreObj -> dbcon-> checkRecordExist($query, $strCondition)) {
				if (!checkTmpcustomExist(array('bu_nickname'=> $_POST['bu_nickname']))) {
					echo 'verified';
				}else{
					echo "existed";
				}
			} else {
				echo "existed";
			}

		} else {
			echo 'empty';
		}
		exit;
		break;
	case 'checkBunameUnique':
		if (!empty($_POST['bu_name'])) {
			$strCondition = " WHERE bu_name='".$_POST['bu_name']."' AND is_popularize_store = 0";
			if ($_SESSION['ShopID']) {
				$strCondition .= " AND StoreID <> '".$_SESSION['ShopID']."'";
			}
			$query = $socstoreObj->table."bu_detail";

			if (!$socstoreObj -> dbcon-> checkRecordExist($query, $strCondition)) {
				if (!checkTmpcustomExist(array('bu_name'=> $_POST['bu_name']))) {
					echo 'verified';
				}else{
					echo "existed";
				}
			} else {
				echo "existed";
			}

		} else {
			echo 'empty';
		}
		exit;
		break;

	case 'startSelling_ipn':
		$socstoreObj->startSellingIPN();
		break;
	
	case 'startSelling_eway':
		$socstoreObj->startSellingEway();
		break;
            
	case 'ewaySubmit':
		$socstoreObj->ewaySubmit(true);
		
	break;
	
	case 'startSelling_return':
		$itemtitle = $socstoreObj->getTextItemTitle('Welcome Back',3);
		$smarty -> assign('itemTitle', 	$itemtitle);

		$smarty -> assign('content', $smarty -> fetch('startselling_return.tpl'));
		$smarty -> assign('sidebar', 0);

		unset($req);
		break;

	case 'design_info':

		if (!isset($_SESSION['level']) || ($_SESSION['level'] != 1)) {
			header('Location:soc.php?cp=home');
			exit();
		}

		if (!empty($_REQUEST['cp'])) {
			if ($socstoreObj -> saveDesignInfo()) {
				if ($_REQUEST['cp']=='next') {
					$socstoreObj -> destroyFormInputVar();

					header('Location:soc.php?act=signon&step=' . $stepOperate['nextStep']);
					exit;
				} elseif ($_REQUEST['cp']=='delmain'){
					$socstoreObj -> destroyFormInputVar();
				} else {
					$socstoreObj -> destroyFormInputVar();
					header('Location:soc.php?act=admin');
					exit;
				}
			}
		}

		$req 	= $socstoreObj->getDesignInfo();

		$req = array_merge($req, $socstoreObj->storeProductAddOrEdit());
		//only run one in session.
		if(empty($_SESSION['hascount'])){
			$req['hascount']	=	$_REQUEST['hascount'];
			$_SESSION['hascount']	=	$_REQUEST['hascount'];
			$socObj = new socClass();
			$info = $socObj->getOrderDetail($_SESSION['ShopID']);
			$req['SV'] = $info[0]['amount'];
			$req['OID'] = $_SESSION['email'];
		}

                /**
                 * added by YangBall, 2011-01-20
                 * change step continue button
                 */
                 $smarty->assign('stepButtonStatu',$socstoreObj->getSellerStepContinueButtonStatus($_SESSION['StoreID']));
                //END-YangBall

		$itemtitle = $socstoreObj->getTextItemTitle('Start Selling',3);
		$menubar = 	$socstoreObj->getStartsellingMenu($stepOperate);
		$smarty -> assign('itemTitle', 	$itemtitle.$menubar);

		$smarty -> assign('req', $req);
		$content = $smarty ->fetch('startselling_step2.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);


		break;

	case 'design_theme':
		if (!isset($_SESSION['level']) || ($_SESSION['level'] != 1)) {
			header('Location:soc.php?cp=home');
			exit();
		}

        //auto, estate, career and Single paid default Template A
        $socObj = new socClass();
        $socObj->resetTemplate();

		if (!empty($_REQUEST['cp'])) {
			if ($socstoreObj -> saveDesignTheme()) {
				if ($_REQUEST['cp']=='next') {
					$socstoreObj -> destroyFormInputVar();

					header('Location:soc.php?act=signon&step=' . $stepOperate['nextStep']);
					exit;
				}elseif ($_REQUEST['cp']=='delmain'){
					$socstoreObj -> destroyFormInputVar();
                                }else {
					$socstoreObj -> destroyFormInputVar();
					header('Location:soc.php?act=admin');
					exit;
				}
			}
		}

		$req 	= $socstoreObj->getDesignTheme();

		$socObj = new socClass();
		$store_info = $socObj->displayStoreWebside();
		$req = array_merge($req, $store_info);

        //paypal required on Buy & Sell and Food & Wine
		//if (($req['info']['attribute'] == 0 || ($req['info']['attribute'] == 5 && $req['info']['sold_status'] == 1 && $req['info']['foodwine_type'] == 'food')) && $req['info']['bu_paypal'] == '') {
		//	exit("<script>alert('Please fill in Paypal Account in step2 before you edit step3 and step4.');location.href='/soc.php?act=signon&step=2';</script>");
		//}
    
    $comm = new common();
    $alerts = empty($req['Alerts']) ? "" : $req['Alerts'];
    $req['input']['alerts'] = $comm->initEditor('alerts', $alerts, "SOCAlerts");
                /**
                 * added by YangBall, 2011-01-20
                 * change step continue button
                 */
                 $smarty->assign('stepButtonStatu',$socstoreObj->getSellerStepContinueButtonStatus($_SESSION['StoreID']));
                //END-YangBall
		$itemtitle = $socstoreObj->getTextItemTitle('Start Selling',3);
		$menubar = 	$socstoreObj->getStartsellingMenu($stepOperate);
		$smarty -> assign('itemTitle', 	$itemtitle.$menubar);
		$smarty -> assign('session', $_SESSION);
                $req=array_merge($req,$socstoreObj->getFeaturedImage());
		$smarty -> assign('req', $req);

		$categories = $socstoreObj -> getImageOfStoreType();
		$smarty -> assign('categories', 		$categories);
		$smarty -> assign('cur_time',time());

		$content = $smarty ->fetch('startselling_step3.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
    
    unset($comm);
    
		break;

	case 'product':	
	
	
	
		if (isset($_REQUEST['ap'])&&$_REQUEST['ap']=='copy') {
			$_SESSION['copy_id'] = $_REQUEST['pid'];
			header('Location: /soc.php?act=signon&step=4');exit();
		}
		if(isset($_SESSION['copy_id'])&&$_SESSION['copy_id']){
			$_REQUEST['pid'] = $_SESSION['copy_id'];
			unset($_SESSION['copy_id']);
			$smarty->assign('iscopy','1');
		}

		if (!isset($_SESSION['level']) || ($_SESSION['level'] != 1)) {
			header('Location:soc.php?cp=home');
			exit();
		}
		$pid_query = $stepOperate['step'] == 4 && $_REQUEST['pid'] ? '&pid='.$_REQUEST['pid'] : '';
		$options_query = $stepOperate['step'] == 4 && $_REQUEST['options'] ? '&options='.$_REQUEST['options'] : '';
		set_time_limit(1800);
		if ($_SESSION['attribute'] == 1){
			header('Location: /estate/?act=product'.$iframe_attribute.'&step='.$stepOperate['step'].$pid_query.$options_query.'&msg='.$_REQUEST['msg']);
		}elseif ($_SESSION['attribute'] == 2){
			header('Location: /auto/?act=product'.$iframe_attribute.'&step='.$stepOperate['step'].$pid_query.$options_query.'&msg='.$_REQUEST['msg']);
		}elseif ($_SESSION['attribute'] == 3){
			header('Location: /job/?act=product'.$iframe_attribute.'&step='.$stepOperate['step'].$pid_query.$options_query.'&msg='.$_REQUEST['msg']);
                }elseif(5 == $_SESSION['attribute']) {
                        header('Location: /foodwine/?act=product'.$iframe_attribute.'&step='.$stepOperate['step'].$pid_query.$options_query.'&msg='.$_REQUEST['msg']);
                }else{
			if (!empty($_REQUEST['cp'])) {
				if ($socstoreObj->storeProductAddOrEditOperate()) {
					if ($_REQUEST['cp']=='next') {
						$socstoreObj -> destroyFormInputVar();
						header('Location:soc.php?act=signon'.$iframe_attribute.'&step=' . $stepOperate['nextStep']);
						exit;
					}elseif ($_REQUEST['cp']=='del'){
						$arrTemp = $socstoreObj -> getFormInputVar();
						$socstoreObj -> destroyFormInputVar();
						header('Location:soc.php?act=signon'.$iframe_attribute.'&step='.$stepOperate['step']."&msg=" . $arrTemp['msg']);
						exit;
					}elseif($_REQUEST['cp']=='edit'){
						$arrTemp = $socstoreObj -> getFormInputVar();
						$socstoreObj -> destroyFormInputVar();
						header('Location:soc.php?act=signon'.$iframe_attribute.'&step='.$stepOperate['step']."&msg=" . $arrTemp['msg']);
						exit;
					}elseif($_REQUEST['cp']=='upload'){
						//exit;
						$arrTemp = $socstoreObj -> getFormInputVar();
						$socstoreObj -> destroyFormInputVar();
						$_SESSION['showmore'] = $arrTemp['error_more'];
						header('Location:soc.php?act=signon'.$iframe_attribute.'&step='.$stepOperate['step']."&msg=".$arrTemp['msg']);
						exit;
					}else{
						$socstoreObj -> destroyFormInputVar();
						header('Location:soc.php?act=admin'.$iframe_attribute);
						exit;
					}
				}else{
					$arrTemp = $socstoreObj -> getFormInputVar();
					$socstoreObj -> destroyFormInputVar();
					header('Location:soc.php?act=signon'.$iframe_attribute.'&step='.$stepOperate['step']."&msg=".$arrTemp['msg']);
				}
			}
			if($_REQUEST['multcp']){
				switch ($_REQUEST['multcp']){
					case 'delete':
						if(count($_REQUEST['ckpid'])){
							if($socstoreObj->mulitopeartion('delete',$_REQUEST['ckpid'])){
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
							if($socstoreObj->mulitopeartion('publish',$_REQUEST['ckpid'])){
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
							if($socstoreObj->mulitopeartion('unpublish',$_REQUEST['ckpid'])){
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
				header('Location:soc.php?act=signon'.$iframe_attribute.'&step='.$stepOperate['step']."&msg=$multmsg");
				exit();
			}

			
			$menubar = 	$socstoreObj->getStartsellingMenu($stepOperate);
			if (!isset($_GET['iframe'])) {
				$itemtitle = $socstoreObj->getTextItemTitle('Start Selling',3);
				$smarty -> assign('itemTitle', 	$itemtitle.$menubar);
			}
			$storeName = getStoreURLNameById($_SESSION['StoreID']);
			if (!empty($storeName)) {
				$storeName = clean_url_name($storeName);
			}

			$smarty -> assign('storeName', $storeName);

			//$req = $socstoreObj->storeProductAddOrEdit();

			$socObj = new socClass();
	        $store_info = $socObj->displayStoreWebside(true);
	        $req = array_merge($socstoreObj->storeProductAddOrEdit(), $store_info);

	        //paypal required on Buy & Sell and Food & Wine
			//if (($req['info']['attribute'] == 0 || ($req['info']['attribute'] == 5 && $req['info']['sold_status'] == 1 && $req['info']['foodwine_type'] == 'food')) && $req['info']['bu_paypal'] == '') {
			//	exit("<script>alert('Please fill in Paypal Account in step2 before you edit step3 and step4.');location.href='/soc.php?act=signon&step=2';</script>");
			//}

                        include_once('class.productcertified.php');
                        $obj_db_certified=new ProductCertified();

                        if(!is_array($req['auction'])){
                            $req['auction'] = array();
                        }
                        foreach($req['auction'] as $key=>$value) {
                            $auction_pid.=$value['pid'].',';
                        }
                        $auction_pid=rtrim($auction_pid,',');
                        if(!is_array($req['auction'])){
                            $arr_auction_new = array();
                        }
                        $arr_auction_new=$obj_db_certified->checkNewCertifiedByPid($auction_pid);

                        if(!is_array($arr_auction_new)){
                            $arr_auction_new = array();
                        }

                        $tmp = array();
                        foreach($arr_auction_new as $key=>$val) {
                            $tmp[]=$val['product_id'];
                        }
                        $arr_auction_new=$tmp;
                        foreach($req['auction'] as $key=>$val) {
                            if(in_array($val['pid'],$arr_auction_new)){
                                $req['auction'][$key]['auction_new']=true;
                            }else{
                                $req['auction'][$key]['auction_new']=false;
                            }
                        }
			$req['msg'] = $_REQUEST['msg'];
			$req['auctionDeleteTime'] = time()-7776000; // 90day
			$req['displayType'] = $_REQUEST['type']?$_REQUEST['type']:'sale';
			if(isset($_SESSION['showmore'])&&$_SESSION['showmore']!=""){
				$req['msg'] .= $_SESSION['showmore'];
				unset($_SESSION['showmore']);
			}
			$req['StoreID'] = $_SESSION['StoreID'];
			$smarty -> assign('req', 		$req);
                        for($i=0;$i<24;$i++) {
                            $arr_hour[]=$i<10?'0'.$i:$i;
                        }
                        $smarty->assign('arr_hour',$arr_hour);
                        for($i=0;$i<60;$i++) {
                            $arr_min[]=$i<10?'0'.$i:$i;
                        }
                        $smarty->assign('arr_min',$arr_min);
			if($req['select']['isattachment']){
				$script = "isattach(1);";
			}else{
				$script = "isattach(0);";
			}
			
			
			$select_detail = "SELECT * FROM aus_soc_bu_detail store WHERE store.StoreID = '".$_SESSION['StoreID']."' LIMIT 1";
			$dbcon->execute_query($select_detail);
			$detail_result = $dbcon->fetch_records();
			
			if (isset($detail_result[0])) {
				
				$detail_data = $detail_result[0];
				$delivery_items = unserialize($detail_data['bu_delivery_text']);
				$delivery_values = unserialize($detail_data['bu_delivery']);
				
				if (empty($delivery_values)) {
					$delivery_values = array();
				}
				
				$smarty->assign('delivery_values', $delivery_values);
				$smarty->assign('delivery_items', $delivery_items);
			}

			
			$smarty -> assign('onLoad', 'selectSubCG(\'categoryDiv\',\'category\',\''.$req['select']['categorySub'].'\');//'.$script.'//ToggleRow(document.getElementById(\'OfferDelevery\'));');
			$smarty -> assign('content', $smarty -> fetch('startselling_step4.tpl'));
			$smarty -> assign('sidebar', 0);
		}
		break;

	case 'store':

		if (!isset($_SESSION['level']) || ($_SESSION['level'] != 1)) {
			header('Location:soc.php?cp=home');
			exit();
		}

		$itemtitle = $socstoreObj->getTextItemTitle('Start Selling',3);
		$menubar = 	$socstoreObj->getStartsellingMenu($stepOperate);
		$smarty -> assign('itemTitle', 	$itemtitle.$menubar);

		$storeName = getStoreURLNameById($_SESSION['StoreID']);
		if (!empty($storeName)) {
			$storeName = clean_url_name($storeName);
		}

		$smarty -> assign('StoreID', $_SESSION['StoreID']);
		$smarty -> assign('storeName', $storeName);

		$content = $smarty ->fetch('startselling_step5.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		break;
		/**
 	* create by roy 20081211
 	* add custom info to temp table
 	* @usa_tmp_custom
 	*/
	case 'addtemp':
		$tmpstr ="";
		foreach ($_POST as $key=>$values){
			$tmpstr .= $tmpstr==""?"$key=".urlencode($values):"&$key=".urlencode($values);
		}
		echo addtmpcustom($tmpstr);
		exit();
		break;

		/**
	 * end created
	 */
	 
	 case 'upload_logo':
		include_once('include/config.php');
		if (isset($_SESSION['StoreID']) && ($_SESSION['StoreID'] > 0)) {
		
			if (isset($_POST['submit_form'])) {
				if ($socstoreObj->updateLogo($_SESSION['StoreID'])) {
					//echo 'saved';
				}
			}
			
			$req = $socstoreObj->getLogos($_SESSION['StoreID']);
			$smarty -> assign('req', $req);
		}

		$content = $smarty ->fetch('upload_logo.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
	 
		break;
	
	 case 'payment_eway':
		$req = array();
		$mSearchType = 'foodwine';
		include_once('include/config.php');
		$version2 = (isset($_GET['version2']));
		if ($_POST) {
			if ($_POST['bu_user'] == "" && isset($_POST['bu_user_default'])) {
				$_POST['bu_user'] = $_POST['bu_user_default'];
			}
			
			$own_website = (isset($_POST['own_website']) && $_POST['own_website'] == 1);
			
			if ((!$own_website)) {
				$arraySeting = array('attribute'=>"{$_POST['attribute']}",
				'StoreID'=>"{$_POST['StoreID']}",
				'email'=>"{$_POST['bu_user']}",
				'websitename'=>"{$_POST['bu_name']}",
				'urlstring'=>"{$_POST['bu_urlstring']}",
				'nickname'=>"{$_POST['bu_nickname']}");
				$errmsg = step1checkedvaild($arraySeting);
				
				if ($errmsg!="") {
					$comm = new common();
					if($_POST['attribute']!=0){
						//$errmsg = str_replace('Nickname','Contact Person',$errmsg);
					}
					$comm->setFormInuptVar($_POST);
					$comm->addOperateMessage($errmsg);
					unset($comm);
					if ($version2) {
						header('Location:soc.php?act=signon&version2');
					} else {
						header('Location:soc.php?act=signon');
					}
					exit();
				}
			}

			$tmparray = array();
			$tmparray['StoreID'] 		= $_POST['StoreID'];
			$tmparray['bu_catag'] 		= $_POST['bu_catag'];
			$tmparray['bu_cuisine'] 	= $_POST['bu_cuisine'];
			$tmparray['bu_username']	= $_POST['attribute'] == 5 ? $_POST['bu_username'] : '';
			$tmparray['bu_user']		= $_POST['bu_user'];
			$tmparray['bu_password'] 	= $_POST['bu_password'];
			$tmparray['bu_nickname'] 	= $_POST['bu_nickname'];
			$tmparray['business_name'] 	= $_POST['business_name'];
			//$tmparray['gender'] 		= $_POST['gender'];
			$tmparray['bu_website']		= $_POST['bu_website'];
			$tmparray['bu_name'] 		= $_POST['bu_name'];
			$tmparray['bu_address'] 	= $_POST['bu_address'];
			$tmparray['address_hide'] 	= $_POST['address_hide'] ? $_POST['address_hide'] : 0;
			$tmparray['bu_state'] 		= $_POST['bu_state'];
			$tmparray['bu_suburb'] 		= $_POST['bu_suburb'];
			$tmparray['bu_postcode'] 	= $_POST['bu_postcode'];
			$tmparray['bu_area'] 		= $_POST['bu_area'];
			$tmparray['bu_phone'] 		= $_POST['bu_phone'];
			$tmparray['phone_hide'] 	= $_POST['phone_hide'] ? $_POST['phone_hide'] : 0;
			$tmparray['mobile'] 		= $_POST['mobile'];
			$tmparray['contact'] 		= $_POST['contact'];
			$tmparray['bu_fax'] 		= $_POST['bu_fax'];
			$tmparray['bu_procode'] 	= $_POST['bu_procode'];
			$tmparray['bu_urlstring'] 	= $_POST['bu_urlstring'];
			$tmparray['attribute'] 		= $_POST['attribute'];
			$tmparray['referrer'] 		= $_POST['referrer'];
			$tmparray['own_website']	= $_POST['own_website'];
			$tmparray['fb_id']			= $_POST['fb_id'] ;
			$tmparray['subAttrib']		= $_POST['subattr5'];
			$tmparray['licence'] 		= $_POST['licence'];
			$tmparray['paid'] 			= time();
			
			foreach ($tmparray as $key=>$values){
				$tmpstr .= $tmpstr==""?"$key=".urlencode($values):"&$key=".urlencode($values);
			}
                        
			if (isset($_SESSION['custom'])) {
				unset($_SESSION['custom']);
			}
                        
			$_SESSION['custom'] = addtmpcustom($tmpstr);

			unset($_POST['agree']);
			$socstoreObj ->setFormInuptVar($_POST);
			
			if ($_POST['StoreID']==""&&$_POST['bu_procode']!="") {
				if ($socstoreObj -> checkvalidpromot($_POST['bu_procode'], $_POST['attribute'])) {
					$_POST['mc_gross'] = "10";
					$socstoreObj -> paypalsubmit();
					exit();
				} else {
					unset($_POST['bu_procode']);
                    echo "<script language='javascript'>
                            	alert('The promotion code which you entered is not invalid.');
                                location.href = '/soc.php?act=signon';
                            </script>";
					exit();
				}
			}
			if (isset($_POST['StoreID']) && $_POST['StoreID']!="") {
				$arrSetting = array(
					'pid'		=> 0,
					'buyer_id'	=> $_POST['StoreID'],
					'StoreID'	=> $_POST['StoreID'],
					'p_status'	=> 'order',
					'description'=>'Eway',
					'order_date' =>time(),
					'type'		=> 'upgrade',
					'amount'	=> $_POST['amount'],
					'month'		=> $_POST['amount']>9?12:$_POST['amount']
				);
			} else {
				$arrSetting = array(
					'pid'		=> 0,
					'buyer_id'	=> 0,
					'StoreID'	=> 0,
					'p_status'	=> 'order',
					'description'=>'Eway',
					'order_date' =>time(),
					'type'		=> 'reigstration',
					'amount'	=> $_POST['amount'],
					'month'		=> $_POST['amount']>9?12:$_POST['amount']
				);
			}
			
			$dbcon->insert_record($table.'order_reviewref',$arrSetting);
			$ref_id = $dbcon->lastInsertId();
			$query = "update `{$table}tmpcustom` set custom=CONCAT(custom,'&ref_id={$ref_id}') WHERE id='{$_POST['custom']}'";
			$dbcon->execute_query($query);
			$eway_setting = getEwayInfo();
			
			if (EWAY_DEBUG==1) {
				$req['cardName'] = 'Infinity Test';
				$req['cardNumber'] = '4444333322221111';
				$req['cvc2']='123';
				$_POST['ewayCustomerID'] = '87654321';
				$_POST['ewaygatewayURL'] = 'https://www.eway.com.au/gateway_cvn/xmltest/testpage.asp';
			} else { 
				$_POST['ewayCustomerID'] = $eway_setting['eway_customer_id'];
				$_POST['ewaygatewayURL'] = 'https://www.eway.com.au/gateway_cvn/xmlpayment.asp';
			}
						
			//if (($_POST['attribute'] == 5) && 
			//	($tmparray['subAttrib'] == 1 || $tmparray['subAttrib'] == 7 || $tmparray['subAttrib'] == 9)) {
			if ($own_website) {
				$req['payment_type'] = 1;
			} else {
				$req['payment_type'] = 2;
			}
			
			$_POST['ewayCustomerEmail'] = $_POST['bu_user'];
			$_POST['ewayCustomerAddress'] = $_POST['bu_address'];
			
			//redirect URL
			$_POST['ewayURL']=SOC_HTTPS_HOST.'soc.php?act=signon&step=startSelling_eway';
			
			$req['eway_info'] = $_POST;
			$req['ewayEmail'] = $_POST['bu_user'];
			$req['expiryMonth'] = getExpMonth();
			$req['expiryYear']= getExpYear();
			
			if ($version2) {
				$req['history_url'] = '/soc.php?act=signon&version2';
			} else {
				$req['history_url'] = '/soc.php?act=signon';
			}

			$smarty -> loadLangFile('payment');

			$storeName = getStoreURLNameById($_SESSION['StoreID']);
			if (!empty($storeName)) {
				$storeName = clean_url_name($storeName);
			}

			$smarty -> assign('StoreID', $_SESSION['StoreID']);
			$smarty -> assign('storeName', $storeName);
			
			$smarty->assign('req',$req);
			$content = $smarty ->fetch('payment_form.tpl');
			$smarty -> assign('content', $content);
			$smarty -> assign('sidebar', 0);
			
		} else {
			if (!$_SESSION['paymentEway']) {
				$eway_setting = getEwayInfo();
				$req['eway_info'] = $_SESSION['pageParam'];
				$req['eway_info']['ewaygatewayURL'] = 'https://www.eway.com.au/gateway/xmlpayment.asp';
				$req['eway_info']['ewayCustomerID'] = $eway_setting['eway_customer_id'];
				$req['eway_info']['ewayTotalAmount'] = "40150";
				$req['eway_info']['ewayCustomerEmail'] = $_SESSION['pageParam']['bu_user'];
				$req['eway_info']['ewayCustomerAddress'] = $_SESSION['pageParam']['bu_address'];
				$req['eway_info']['ewayURL'] = SOC_HTTPS_HOST.'soc.php?act=signon&step=startSelling_eway';
				$req['ewayEmail'] = $_SESSION['pageParam']['bu_user'];
				$req['expiryMonth'] = getExpMonth();
				$req['expiryYear']= substr(getExpYear(),2);
				
				if ($version2) {
					$req['history_url'] = '/soc.php?act=signon&version2';
				} else {
					$req['history_url'] = '/soc.php?act=signon';
				}
				
				$smarty -> loadLangFile('payment');
				$storeName = getStoreURLNameById($_SESSION['StoreID']);
				if (!empty($storeName)) {
					$storeName = clean_url_name($storeName);
				}
				$smarty -> assign('StoreID', $_SESSION['StoreID']);
				$smarty -> assign('storeName', $storeName);
				$smarty->assign('req',$req);
				$content = $smarty ->fetch('payment_form.tpl');
				$smarty -> assign('content', $content);
				$smarty -> assign('sidebar', 0);
			} else {
				if ($version2) {
					header('Location: /soc.php?act=signon&version2');
				} else {
					header('Location: /soc.php?act=signon');
				}
			}
		}

        break;
	 
	case 'payment_paypal':
		$req = array();
		if($_POST){
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
				if($_POST['attribute']!=0){
					//$errmsg = str_replace('Nickname','Contact Person',$errmsg);
				}
				$comm->setFormInuptVar($_POST);
				$comm->addOperateMessage($errmsg);
				unset($comm);
				header('Location:soc.php?act=signon');
				exit();
			}

			$tmparray = array();
			$tmparray['StoreID'] 		= $_POST['StoreID'];
			$tmparray['bu_catag'] 		= $_POST['bu_catag'];
			$tmparray['bu_cuisine'] 	= $_POST['bu_cuisine'];
			$tmparray['bu_username']	= $_POST['attribute'] == 5 ? $_POST['bu_username'] : '';
			$tmparray['bu_user']		= $_POST['bu_user'];
			$tmparray['bu_password'] 	= $_POST['bu_password'];
			$tmparray['bu_nickname'] 	= $_POST['bu_nickname'];
			$tmparray['gender'] 		= $_POST['gender'];
			$tmparray['bu_name'] 		= $_POST['bu_name'];
			$tmparray['bu_address'] 	= $_POST['bu_address'];
			$tmparray['address_hide'] 	= $_POST['address_hide'] ? $_POST['address_hide'] : 0;
			$tmparray['bu_state'] 		= $_POST['bu_state'];
			$tmparray['bu_suburb'] 		= $_POST['bu_suburb'];
			$tmparray['bu_postcode'] 	= $_POST['bu_postcode'];
			$tmparray['bu_area'] 		= $_POST['bu_area'];
			$tmparray['bu_phone'] 		= $_POST['bu_phone'];
			$tmparray['phone_hide'] 	= $_POST['phone_hide'] ? $_POST['phone_hide'] : 0;
			$tmparray['mobile'] 		= $_POST['mobile'];
			$tmparray['contact'] 		= $_POST['contact'];
			$tmparray['bu_fax'] 		= $_POST['bu_fax'];
			$tmparray['bu_procode'] 	= $_POST['bu_procode'];
			$tmparray['bu_urlstring'] 	= $_POST['bu_urlstring'];
			$tmparray['attribute'] 		= $_POST['attribute'];
			$tmparray['referrer'] 		= $_POST['referrer'];

			// Modify by Haydn.H By 20120306 ========= Begin =========
			$tmparray['fb_id']              = $_POST['fb_id'] ;
			// Modify by Haydn.H By 20120306 ========= End =========

			switch ($_POST['attribute']){
				case '0':
					$tmparray['subAttrib'] = 0;
					break;
				case '1':
					$tmparray['subAttrib'] = $_POST['subattr1'];
					break;
				case '2':
					$tmparray['subAttrib'] = $_POST['subattr2'];
					break;
				case '3':
					$tmparray['subAttrib'] = $_POST['subattr3'];
					break;
				case '5':
					$tmparray['subAttrib'] = $_POST['subattr5'];
					break;
				default:
					$tmparray['subAttrib'] = 0;
					break;
			}
			
			$tmparray['licence'] 		= $_POST['licence'];
			$tmparray['paid'] 			= time();

			foreach ($tmparray as $key=>$values) {
				$tmpstr .= $tmpstr==""?"$key=".urlencode($values):"&$key=".urlencode($values);
			}
			
			$_POST['custom'] = addtmpcustom($tmpstr);


			unset($_POST['agree']);
			$socstoreObj ->setFormInuptVar($_POST);
			/**
             * added by YangBall, 2011-03-28
             * edit by Kevin Lau, 2011-11-28
             * add free register or buy & sell market
             */
            if(FREE_REGISTER && $_POST['attribute'] != 5) {
            	if ($_POST['StoreID']=="" && $_POST['bu_procode'] != "") {
            		if(!$socstoreObj -> checkvalidpromot($_POST['bu_procode'], $_POST['attribute'])){
						unset($_POST['bu_procode']);
                    	echo "<script language='javascript'>
                            	alert('The promotion code which you entered is not invalid.');
                                location.href = '/soc.php?act=signon';
                            </script>";
						exit();
            		}
            	}

                $_POST['mc_gross'] = "120";
                $socstoreObj -> paypalsubmit(true);
                exit();
            }
    		//END-YangBall

			if($_POST['StoreID']==""&&$_POST['bu_procode']!=""){
				if($socstoreObj -> checkvalidpromot($_POST['bu_procode'], $_POST['attribute'])){
					$_POST['mc_gross'] = "10";
					$socstoreObj -> paypalsubmit();
					exit();
				}else{
					unset($_POST['bu_procode']);
                    echo "<script language='javascript'>
                            	alert('The promotion code which you entered is not invalid.');
                                location.href = '/soc.php?act=signon';
                            </script>";
					exit();
				}
			}
			if(isset($_POST['StoreID'])&&$_POST['StoreID']!=""){
				$arrSetting = array(
					'pid'		=> 0,
					'buyer_id'	=> $_POST['StoreID'],
					'StoreID'	=> $_POST['StoreID'],
					'p_status'	=> 'order',
					'description'=>'PayPal',
					'order_date' =>time(),
					'type'		=> 'upgrade',
					'amount'	=> $_POST['amount'],
					'month'		=> $_POST['amount']>9?12:$_POST['amount']
				);
			}else{
				$arrSetting = array(
					'pid'		=> 0,
					'buyer_id'	=> 0,
					'StoreID'	=> 0,
					'p_status'	=> 'order',
					'description'=>'PayPal',
					'order_date' =>time(),
					'type'		=> 'reigstration',
					'amount'	=> $_POST['amount'],
					'month'		=> $_POST['amount']>9?12:$_POST['amount']
				);
			}
			$dbcon->insert_record($table.'order_reviewref',$arrSetting);
			$ref_id = $dbcon->lastInsertId();
			$query = "update `{$table}tmpcustom` set custom=CONCAT(custom,'&ref_id={$ref_id}') WHERE id='{$_POST['custom']}'";
			$dbcon->execute_query($query);
			$req['paypal_info'] = $_POST;
			$paypal_setting = getPaypalInfo();
			if($paypal_setting['paypal_mode'] == 0){
				$req['paypal_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr/';
			}else{
				$req['paypal_url'] = 'https://www.paypal.com/cgi-bin/webscr/';
			}
			$req['paypal_title'] = "Paypal Information";
			$smarty->assign('req',$req);
			$smarty->display('paypal.tpl');
			exit();
		}else{
			header('Location: /soc.php?act=signon');
		}
		break;
	case 'confirmPromot':
		if($socstoreObj->getFormInputVar()){
			$req['paypal_info'] = $socstoreObj->getFormInputVar();
			if(isset($req['paypal_info']['StoreID'])&&$req['paypal_info']['StoreID']!=""){
				$arrSetting = array(
					'pid'		=> 0,
					'buyer_id'	=> $req['paypal_info']['StoreID'],
					'StoreID'	=> $req['paypal_info']['StoreID'],
					'p_status'	=> 'order',
					'description'=>'PayPal',
					'order_date' =>time(),
					'type'		=> 'upgrade',
					'amount'	=> $req['paypal_info']['amount'],
					'month'		=> $req['paypal_info']['amount']>9?12:$req['paypal_info']['amount']
				);
			}else{
				$arrSetting = array(
					'pid'		=> 0,
					'buyer_id'	=> 0,
					'StoreID'	=> 0,
					'p_status'	=> 'order',
					'description'=>'PayPal',
					'order_date' =>time(),
					'type'		=> 'reigstration',
					'amount'	=> $req['paypal_info']['amount'],
					'month'		=> $req['paypal_info']['amount']>9?12:$req['paypal_info']['amount']
				);
			}
			$dbcon->insert_record($table.'order_reviewref',$arrSetting);
			$ref_id = $dbcon->lastInsertId();
			$query = "update `{$table}tmpcustom` set custom=CONCAT(custom,'&ref_id={$ref_id}') WHERE id='{$req['paypal_info']['custom']}'";
			$dbcon->execute_query($query);
			if(PAYPAL_DEBUG == 1){
				$req['paypal_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr/';
			}else{
				$req['paypal_url'] = 'https://www.paypal.com/cgi-bin/webscr/';
			}
			$req['paypal_title'] = "Paypal Information";
			$smarty->assign('req',$req);
			$smarty->display('paypal.tpl');
			exit();
		}else{
			header('Location: /soc.php?act=signon');
		}
		break;


        case 'free_reg_suc':

            if(empty($_SESSION['StoreID'])) {
                header('Location: /soc.php?act=signon');
            }
            $socObj = new socClass();
            $req = $socObj->displayStoreWebside();
            $smarty->assign('req', $req);
            $smarty->assign('https_host', SOC_HTTPS_HOST);
            $setSearchType	= !empty($_REQUEST['attribute'])? $_REQUEST['attribute'] : $_SESSION['attribute'];
            $search_type = $setSearchType==1 ? 'estate' : ($setSearchType==2 ? 'auto' : ($setSearchType==3 ? 'job' : ($setSearchType==5 ? 'foodwine' : 'store')));
            $smarty -> assign('search_type',$search_type);

            $smarty -> assign('isUpdate', 	true);

            $smarty -> assign('sidebar', 0);
            $smarty->assign('showRandomBanner', true);
            $smarty -> assign('content', $smarty -> fetch('free_reg_suc.tpl'));
        break;

        // Modify by Haydn.H By 20120302 ========= Begin =========
        case 'fbcheckkey':
            if (!empty($_POST['fb_id'])) {
                $strCondition = " WHERE fb_id='".$_POST['fb_id']."'";
                $query = $socstoreObj->table."facebook";

                if (!$socstoreObj -> dbcon-> checkRecordExist($query, $strCondition)) {
                    if (!checkTmpcustomExist(array('fb_id'=> $_POST['fb_id']))) {
                       echo 'verified';
                    }else{
                       echo "existed";
                    }
                }else{
                   echo "existed";
                }
            }else{
                echo 'empty';
            }
            exit;
            break;
        // Modify by Haydn.H By 20120302 ========= End =========

	default: //store infomation
	include_once(dirname(__FILE__) . '/../include/config.php');
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
	}

	$itemtitle = $socstoreObj->getTextItemTitle('Start Selling',3);
	$menubar = 	$socstoreObj->getStartsellingMenu($stepOperate);
	$smarty -> assign('itemTitle', 	$itemtitle.$menubar);

	if(is_array($_SESSION['pageParam']) && !$isUpdate){
		$socstoreObj->_notVar = true;
	}

	$req = $socstoreObj->sellerValidation($isUpdate);

	$paypalInfo = SIGNON_PAYMENT == 'ipg' ?$socstoreObj->getIPGInfo() : $socstoreObj->getPaypalInfo();
	$req['paymentMethod']	=	SIGNON_PAYMENT ;
	$req['msg'] = urldecode($req['msg']);

	if($_SESSION['UserID']!='' AND $_SESSION['level']==1){
		$req['agree']='1';
	}
        if(is_array($_SESSION['pageParam'])) {
            $showRadio = true;
        }
        else {
            $showRadio = false;
        }

	if(isset($_REQUEST['referr'])&&$_REQUEST['referr']!=""){
		setcookie('cookieRefer',$_REQUEST['referr'],time()+604800);
		header("Location: /soc.php?act=signon");exit();
	}
	if(isset($_COOKIE['cookieRefer'])&&$_COOKIE['cookieRefer']!="" and false == $isUpdate){
		$req['referrer'] = $_COOKIE['cookieRefer'];
	}

	array_walk($req, 'html_decode');

	if (!$isUpdate) {
		$req['bu_user'] = $req['bu_user'] ? $req['bu_user'] : $_REQUEST['email'];
		$req['re_bu_user'] = $req['re_bu_user'] ? $req['re_bu_user'] : $_REQUEST['email'];
		$req['msg'] = $req['msg'] ? $req['msg'] : $_REQUEST['msg'];
	}
    $smarty->assign('showRadio',$showRadio);

    $req['attribute'] = 5;
	$smarty -> assign('req', $req);
	$paypalInfo['paypal_url'] = SIGNON_PAYMENT == 'ipg' ?$paypalInfo['paypal_url']:SOC_HTTPS_HOST."soc.php?act=signon&step=payment_paypal";
        /**
         * added by YangBall, 2011-02-17
         * add NR payment method
         */
        if('eway'==SIGNON_PAYMENT)
        {
             $req['paymentMethod'] = 'eway';
             $ewayInfo['eway_url'] =  SOC_HTTPS_HOST."soc.php?act=signon&step=payment_eway";
        }
        elseif('ipg' == SIGNON_PAYMENT) {
             $req['paymentMethod'] = 'ipg';
             $paypalInfo = $socstoreObj->getIPGInfo();
         }
         elseif('nr' == SIGNON_PAYMENT) {
             $req['paymentMethod'] = 'nr';
             require_once(dirname(__FILE__) . '/../include/class.paymentNR.php');

             $NR = new PaymentNR();
             $paypalInfo = $NR->getPaymentInfo();
         }
         else {
             $req['paymentMethod'] = 'paypal';
             $paypalInfo['paypal_url'] = SOC_HTTPS_HOST."soc.php?act=signon&step=payment_paypal";
         }

        //END-YangBall
	$lang = $_LANG;
	krsort($lang['seller']['attribute']);
	$smarty	-> assign('lang', $lang);
	$smarty -> assign('paypalInfo', $paypalInfo);
	$smarty-> assign('ewayInfo',$ewayInfo);
	$smarty -> assign('isUpdate', $isUpdate);
	$smarty -> assign('payment_method', $req['paymentMethod']);

	if (FREE_REGISTER) {
		if (isset($_GET['version2'])) {
			$smarty -> assign('content', $smarty -> fetch('startselling_step1_free_v2.tpl'));
		} else {
			//$smarty -> assign('content', $smarty -> fetch('startselling_step1_free.tpl'));
			$smarty -> assign('content', $smarty -> fetch('startselling_step1_free_v2.tpl'));
		}
	} else {
		$smarty -> assign('content', $smarty -> fetch('startselling_step1.tpl')); // free : startselling_step1_free.tpl; normal : startselling_step1.tpl
	}

	$smarty -> assign('sidebar', 0);
	$smarty -> assign('search_type',$search_type);
    $smarty->assign('showRandomBanner', true);

	$socstoreObj -> destroyFormInputVar();
	unset($req);
	break;
}

$smarty->assign("is_content",1);

//left menu
include('leftmenu.php');
include_once('soc/seo.php');
$smarty -> assign('act', $_REQUEST['act']);
$smarty -> display($template_tpl);
//unset($smarty);
exit;


<?php
include_once 'class.productcertified.php';

if(!isset($_SESSION['StoreID'])) {
    header("Location:/soc.php?cp=login");
}

$act = isset($_REQUEST['act'])? $_REQUEST['act']: 'default';
$req = $_REQUEST;
$certified = new ProductCertified();

$usa_country_id=13;
$buyer_attribute_id=4;
switch(trim($act)) {
    case 'list':
        $smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Certified List');
        $smarty -> assign('itemTitle', $socObj->getTextItemTitle("Certified Bidders",3));
        $smarty -> assign('sidebar', 0);
        $pid = isset($_REQUEST['pid'])? $_REQUEST['pid']: NULL;
        $pageIndex = isset($_REQUEST['pageID'])? $_REQUEST['pageID']: 1;
        $req['products'] = $certified->getProducts();
        $req['certifieds'] = $certified->getCertifieds($pid, $pageIndex);
        $smarty->assign('req', $req);
        $smarty->assign('store_name',getStoreByURL($_SESSION['StoreID']));
        $smarty->assign('content', $smarty->fetch('certified/list.tpl'));
        break;

    case 'audit':
        if(!(isset($_REQUEST['cid']) && ctype_digit($_REQUEST['cid']))
                || !(isset($_REQUEST['cp']) && ctype_digit($_REQUEST['cid']))) {
            echo '{success:0}';
        }else {
            $r = $certified->audit($_REQUEST['cid'], $_REQUEST['op'] == '1');
            echo "{success:1,time:'{$r['time']}'}";
        }
        exit;
        break;

    case 'add':
        $arr_field=array('product_id','store_id','contact_email','contact_phone','country','state','city','full_name','is_authorised','created_time','product_store_id','product_comments','certified_name1','certified_name2','certified_email1','certified_email2','certified_phone1','certified_phone2','post_code');
        $country_id=$_SESSION['attribute']==$buyer_attribute_id ? $_POST['sle_country_select'] : $usa_country_id;

        $arr_val=array($_POST['hdn_produce_id'],$_SESSION['StoreID'],$_POST['txt_email'],$_POST['txt_phone'],$country_id,$_POST['hdn_state'],$_POST['hdn_city'],$_POST['txt_name'],0,time(),$_POST['hdn_store_id'],$_POST['txta_ps'],trim($_POST['txt_iden_name1']),trim($_POST['txt_iden_name2']),trim($_POST['txt_iden_email1']),trim($_POST['txt_iden_email2']),trim($_POST['txt_iden_phone1']),trim($_POST['txt_iden_phone2']),trim($_POST['txt_postcode']));
        // Send Email
        $storeid=$_POST['hdn_store_id'];
        $product_id=$_POST['hdn_produce_id'];
        $state=$certified->getApplyState($product_id);
        /*
       if($state==1 or $state==0){
            $smarty->assign('msg', 'Error');
            $smarty->assign('content',$smarty->fetch('alertmsg.tpl'));
        }
        else {*/
        $certified->addCertified($arr_field,$arr_val);
        $arr_product_info=$certified->getProduceById($product_id);
        $storeinfo=$socObj->getStoreInfo($storeid);
        $subject=' SOC: Auction participation request';
        $message="Dear {$storeinfo['bu_nickname']},<br/>";
        $message.= '<p>'.$_POST['txt_name'].' has submitted details below for your approval, in-order to participate as a bidder on the following auction - <a href="http://'.$_SERVER['HTTP_HOST'].'/'.getStoreByURL($storeid).'/'.$arr_product_info['url_item_name'].'">' .$arr_product_info['item_name'].'</a></p>';
        $message.= '<p><b>Details</b><br/>';
        $message.="{$_LANG['labelEmail']} :{$_POST['txt_email']}<br/>";
        $message.="{$_LANG['labelName']} :{$_POST['txt_name']}<br/>";
        $message.="{$_LANG['labelCountry']} :{$_POST['txt_country_str']}<br/>";
        $message.="{$_LANG['labelState']} :{$_POST['txt_state_str']}<br/>";
        $message.="{$_LANG['labelCity']} :{$_POST['txt_city_str']}<br/>";
        $message.="{$_LANG['labelZIP']} :{$_POST['txt_postcode']}<br/>";
        $message.="{$_LANG['labelPhone']} :{$_POST['txt_phone']}<br/>";
        $message.="</p>";

        $message.='<p><b>'.$_LANG['langIdentify_1'].'</b><br/> ';
        $message.="Name :{$_POST['txt_iden_name1']}<br/>";
        $message.="Email :{$_POST['txt_iden_email1']}<br/>";
        $message.="Phone :{$_POST['txt_iden_phone1']}<br/>";
        $message.='</p>';
        
        $message.='<p><b>'.$_LANG['langIdentify_2'].'</b><br/> ';
        $message.="Name :{$_POST['txt_iden_name2']}<br/>";
        $message.="Email&nbsp;:{$_POST['txt_iden_email2']}<br/>";
        $message.="Phone :{$_POST['txt_iden_phone2']}<br/>";
        $message.='</p>';

        $message.="<p>I acknowledge that in the event that I am the successful (winning) bidder in this auction, I accept the responsibility for payment of the full amount in accordance with the Terms and Conditions as stated by SOC Exchange or those specific payment instructions as stated by the vendor.</p>";
        
        $message.= "<p><b>Comments</b> : ".nl2br($_POST['txta_ps'])."<br/></p>";
        
        $mail_to=$storeinfo['bu_email'];
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $headers .= "To: {$mail_to}\r\n";
        $headers .= "From: info@thesocexchange.com\r\n";
        $message=stripslashes($message);
        $storeMessage = $message.'<a href="http://'.$_SERVER['HTTP_HOST'].'/soc.php?cp=certified&act=list'.'">Please click this link to authorize or decline this request.</a><br/><br/>Sincerely,<br/>SOC Exchange<br/>';

        @mail('', $subject, getEmailTemplate($storeMessage), fixEOL($headers));
        if($_POST['chk_email']=='send') {        // Send to me
            $mail_to=$_POST['txt_email'];
            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
            $headers .= "To: {$mail_to}\r\n";
            $headers .= "From: info@thesocexchange.com\r\n";
            @mail('', $subject, getEmailTemplate($message.'<br/><br/>Sincerely,<br/>SOC Exchange<br/>'), fixEOL($headers));
        }
        sendInboxMessage($_POST['hdn_store_id'], 'Certified Bidder',$storeMessage);

        $url='http://'.$_SERVER['HTTP_HOST'].'/'.getStoreByURL($storeid).'/'.$arr_product_info['url_item_name'];
        $str='<script type="text/javascript">alert("Your request has been sent successfully.");location.href="'.$url.'";</script>';
        echo $str;
        die();
        //}
        break;

    case 'alert':
        $smarty->assign('msg', $socObj -> displayPageFromCMS(96));
        $smarty->assign('content',$smarty->fetch('alertmsg.tpl'));
        break;


    case 'detail' :
        include_once('class.db.country.php');
        include_once('class.db.bu_detail.php');
        $obj_db_country=new country();
        $obj_db_bu_detail=new bu_detail();

        $arr_certified_info=$certified->getCretifiedById($_GET['id']);
        $smarty->assign('certified_info',$arr_certified_info);
        $country_name=$obj_db_country->getCountryById($arr_certified_info['country']);
        $smarty->assign('country_name',$country_name);
        if($country_name['country_name']=='Australia') {
            include_once('class.db.state.php');
            $obj_db_state=new state();
            $tmp=$obj_db_state->getStateById($arr_certified_info['state']);
            $state_name=$tmp['description'];
        }
        else {
            $state_name=$arr_certified_info['state'];
        }
        $product_info=$certified->getProduceById($arr_certified_info['product_id']);
        $smarty->assign('product_info',$product_info);
        $bu_detail_info=$obj_db_bu_detail->getRowByStoreId($arr_certified_info['store_id']);
        $smarty->assign('nickname',$bu_detail_info['bu_nickname']);
        $smarty->assign('state_name',$state_name);
        $smarty->assign('content',$smarty->fetch('certified/detail.tpl'));
        $smarty->assign('pageTitle','Sell Goods Online - Selling Online - Certified Registration');
        $smarty -> assign('itemTitle', $socObj->getTextItemTitle('Bidder\'s Details',3));
        $smarty->assign('sidebar',0);
        break;

    default:
        $state=$certified->getApplyState($_GET['pid']);
        if($state==='1' or $state==='0') {
            echo '<script type="text/javascript">alert("Error");history.go(-1);</script>';
            exit;
        }
        include_once('class.db.bu_detail.php');
        include_once('class.db.country.php');
        $obj_db_country=new country();
        $country_arr=$obj_db_country->getCountryByName('Australia');
        $country_id=$country_arr['country_id'];
        $smarty->assign('usa_country_id',$country_id);      //usa id

        $bu_detail=new bu_detail();
        $email=$_SESSION['email'];
        $arr_user_info=$bu_detail->getRowByStoreId($_SESSION['StoreID']);
        $smarty->assign('user_info',$arr_user_info);

        if($_SESSION['attribute']==4) {
            $phone=$arr_user_info['bu_phone'];
        }
        else {
            $phone=$arr_user_info['bu_area'].' '.$arr_user_info['bu_phone'];
        }
        $smarty->assign('phone', $phone);
        $cities=getSuburbArray($arr_user_info['bu_state'],$arr_user_info['bu_suburb']);
        $Subburb	= "<select name='sle_country' id='bu_suburb' class='select'>";
        $Subburb.= '<option value="">Select City</option>';
        foreach ($cities as $row) {
            if($row['bu_suburb']==$arr_user_info['bu_suburb'])
                $Subburb.= '<option value="'.$row['bu_suburb'].'" selected="selected" >'.$row['bu_suburb'].'</option>';
            else
                $Subburb.= '<option value="'.$row['bu_suburb'].'" >'.$row['bu_suburb'].'</option>';
        }
        $Subburb	.= "</select>";
        $Subburb=str_ireplace('"', "'", $Subburb);
        $smarty->assign('sle_city',$Subburb);
        $smarty->assign('pageTitle','Sell Goods Online - Selling Online - Certified Registration');
        
        $req = $socObj->customersGeton();
		$headerInfo = $socObj -> displayStoreWebside(true);
        $req['template'] = $headerInfo['template'];
        $smarty->assign('headerInfo', $headerInfo['info']);
        $smarty->assign('req',$req);
        $smarty->assign('product_id',$_GET['pid']);
        $smarty->assign('store_id',$_GET['StoreID']);
        if($_SESSION['jump_uri']) {
            $url='http://'.$_SERVER['HTTP_HOST'].'/'.$_SESSION['jump_uri'];
            $submit_tips='Your request has been sent successfully. Please <a href="'.$url.'">click here</a> to go back the Auction.';
            $smarty->assign('submit_tips',$submit_tips);
            unset($_SESSION['jump_uri']);
        }
        
        /*
         *  Country and City
         */
        $country_info=$obj_db_country->getCountryById($arr_user_info['bu_country']);
        if($arr_user_info['bu_country']==$usa_country_id or $arr_user_info['bu_country']===0 or $arr_user_info['bu_country']===null) {  // AUS
            include_once('class.db.state.php');
            $country_name='Australia';
            $obj_db_state=new state();
            $tmp=$obj_db_state->getStateById($arr_user_info['bu_state']);
            $state_name=$tmp['description'];

        }
        else {
            $country_name=$country_info['country_name'];
            $state_name=$arr_user_info['bu_state'];
        }
        /*if($_SESSION['attribute']==0 or $_SESSION['attribute']==1 or $_SESSION['attribute']==2 or $_SESSION['attribute']==3) {
            $country_name='Australia';
        }
        else {
            $country_name='';
        }*/

        $smarty->assign('country_name',$country_name);
        $smarty->assign('country_info',$country_info);
        $smarty->assign('state_name',$state_name);
        $product_info=$certified->getProduceById($_GET['pid']);
        $smarty->assign('product_info',$product_info);
        $smarty->assign('sidebar', 0);
        $templateInfo = $socObj -> getTemplateInfo();
        $smarty->assign('itemTitle', $socObj->getTextItemTitle('Get certified to bid at this auction', 4, $templateInfo['bgcolor']));
        $smarty -> assign('templateInfo', $templateInfo);

        $tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
        $smarty->assign('tmp_header', $tmp_header);
        $smarty->assign('content',$smarty->fetch('certified/certified.tpl'));
		$smarty->assign('isstorepage',1);

}

$smarty->assign('hide_race_banner',1);
$smarty->assign('is_content',1);
$smarty->assign('is_logo', TRUE);
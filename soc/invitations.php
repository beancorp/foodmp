<?php
@session_start();
include_once ('include/config.php');
include_once ('include/smartyconfig.php');
include_once ('maininc.php');
include_once ('class.emailClass.php');
include_once ('class.soc.php');
include_once ('class/pagerclass.php');
include_once ('functions.php');
include_once ('class.invitations.php');
include_once ("class.processcsv.php");
if (!isset($_SESSION['level']) || ($_SESSION['level'] != 1)) {
	header('Location:soc.php?cp=home');
	exit();
}
if ($_SESSION['attribute']==3&&$_SESSION['subAttrib']==3) {
	header('Location:soc.php?cp=home');
	exit();
}
$socObj = new socClass();
$invation = new invitations();
$procsv = new processcsv();
$smarty -> assign('pageTitle','Wish List - Invitations @ SOC Exchange Australia');
if(isset($_POST['opt'])&&$_POST['opt']=="send"){
	$j=0;
	
	if($_POST['is_choose_upload']==1){
		$csvAry =$procsv->showCSVEmail($_SESSION['ShopID'],'invitation');
		$aryInvtation_Name = array();
		$aryInvtation_Email = array();
		if($csvAry){
			foreach ($csvAry as $pass){
				$aryInvtation_Name[] = $pass['emailName'];
				$aryInvtation_Email[]= $pass['emailAddress'];
			}
		}
	}else{
		$aryInvtation_Name = $_POST['invitation_name'];
		$aryInvtation_Email = $_POST['invitation_email'];
	}
	for($l=0;$l<count($aryInvtation_Email);$l++):
		$aryResult = array();
		$aryResult['subject'] = $_POST['subject'];
		$aryResult['invitation_name'] = $aryInvtation_Name[$l];
		$aryResult['invitation_email'] = $aryInvtation_Email[$l];
		$aryResult['event_time']	= $_POST['event_time'];
		$aryResult['event_addr']	= $_POST['event_addr'];
		$aryResult['event_addr2']   = $_POST['event_addr2'];
		$aryResult['event_RSVP']	= $_POST['event_RSVP'];
		$aryResult['event_to']		= $aryInvtation_Name[$l];
		$aryResult['event_1']		= $_POST['event_1'];
		$aryResult['event_2']		= $_POST['event_2'];
		$aryResult['email_template']= $_POST['email_template'];
		$aryResult['add_time']		= time();
		$aryResult['status']		= 'send';
		$aryResult['StoreID']		= $_SESSION['ShopID'];
		$aryResult['ispass']		= isset($_POST['ispass'])?$_POST['ispass']:0;
		
		$sellerInfo = $invation->get_SellerInfo($_SESSION['ShopID']);
		$tempid = $_POST['email_template'];
		if($_REQUEST['cp']=="preview"):
			$TempInfo = $invation->getTemplate($tempid);
			if(get_magic_quotes_gpc()){
				$aryResult = stripslashes_deep($aryResult);
			}
			echo $invation->previewHTML(array_merge($aryResult,$sellerInfo),$TempInfo['Images'],$TempInfo['Info']);
			exit();
		else:
			if($invation->save_invitations($aryResult)){
				if(get_magic_quotes_gpc()){
					 $aryResult = stripslashes_deep($aryResult);
				}
				$message = $invation->bulidEmailheader("{$aryResult['subject']}",$sellerInfo['wishlist_StoreName'],"{$aryResult['invitation_name']}<{$aryResult['invitation_email']}>");
				$message .= $invation->bulidHTML($tempid,array_merge($aryResult,$sellerInfo));
				if(@mail("",$aryResult['subject'],"",  fixEOL($message))):
					if(@$_REQUEST['template_type']=='user'):
						$bannerInfo = array('StoreID'=>$_SESSION['ShopID'],'template'=>$_REQUEST['usertpl_img'],'addtime'=>time(),'lastupdate'=>time());
						$invation->saveTemplateBanner($bannerInfo);
					endif;
					$j++;
				endif;
			}
		endif;
	endfor;
	$req['msg'] = "You have sent $j invitation".($j>1?"s":"");
}
if(isset($_REQUEST['id'])){
$req['invationInfo'] = $invation->getInvationById($_SESSION['ShopID'],base64_decode($_REQUEST['id']));
$req['invationTpl'] = $invation->getTemplateByID($req['invationInfo'][0]['email_template']);
}
$req['email'] = $_SESSION['email'];
$req['userbanner'] = $invation->getUserTemplate($_SESSION['ShopID']);
$req['template'] =$invation->getTempList();
$smarty->assign('req',$req);
$itemtitle = $socObj->getTextItemTitle('Invite your friend(s) to attend an event',3);
$smarty -> assign('itemTitle', 	$itemtitle);
$content = $smarty -> fetch('invitations/invitations_form.tpl');
$smarty -> assign('content', $content);
$smarty -> assign('sidebar', 0);

$smarty -> assign('sidebar', 0);
$smarty -> assign('search_type',$search_type);
$smarty->assign("is_content",1);
include('leftmenu.php');
include_once('soc/seo.php');
$smarty -> assign('act', $_REQUEST['act']);
$smarty->assign('modcp','wishlist');
$smarty -> display('index.tpl');
?>
<?php
$smarty -> assign('itemTitle', '<div id="content_cmslong"><span id="titles" style="width:580px;"><h1 style="font-size:14px;font-weight:400;color:#FFFFFF;margin:0px;">Send gallery to my friends&nbsp;</h1> </span>&nbsp;<a href="/soc.php?act=gallery">Gallery Home</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/soc.php?act=galleryinfo&cid='.$_REQUEST['cid'].'">&lt;&lt; Back</a></div><br />');
$req['galleryinfo'] = $gallery->getGalleryCategoryInfo($StoreID,$_REQUEST['cid']);
$req['eventlist'] = $gallery->geteventlist($StoreID);

$site = getStoreURLNameById($StoreID);
$galleryurl = $req['galleryinfo']['gallery_url'];

$hostgallery='http://'.$_SERVER['HTTP_HOST']."/$site/gallery/$galleryurl";
if(isset($_REQUEST['sendinvit'])&&$_REQUEST['sendinvit']=="sendinvit"){
	if(get_magic_quotes_gpc()){
		$_POST['content'] = stripslashes($_POST['content']);
		$_POST['subject'] = stripslashes($_POST['subject']);
	}
	
	$arryParms= array('galleryLink'=>$hostgallery,'message'=>$_POST['content']."\r\n\r\n\r\n".$_POST['signature'],'gallerypass'=>$req['galleryinfo']['gallery_category_password'],'seller_name'=>$_SESSION['NickName'],'Subject'=>$_POST['subject'],'fromname'=>$_SESSION['NickName'],'cid'=>$_REQUEST['cid']);
	$paramARY = array('message'=>addslashes($arryParms['message']),
					  'StoreID'=>$StoreID,
					  'subject'=>addslashes($arryParms['Subject']),
					  'categoryid'=>$_REQUEST['cid'],
					  'addtime'=>time());
	$i = 0;
	foreach ($_POST['userlist'] as $pass){
		$arryParms['To'] = $pass;
		$paramARY['email']	= $pass;
		$emails = new emailClass();
		$emails->send($arryParms,'gallery/galleryEvent_email.tpl',true);
		$gallery->addsendemaillog($paramARY);
		unset($emails);	
		$i++;	
	}
	$req['msg']= "You have sent $i email".(($i>1)?"s":"");
}
$smarty->assign('gallerylink',$hostgallery);
$smarty->assign('req',$req);
$content = $smarty -> fetch('gallery/galleryEvent.tpl');
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
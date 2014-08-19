<?php
        /*
         *  @INFINITY_TEMP [Yang Ball 2010-07-28] <Des>
         */
include_once ('include/config.php');
include_once ('include/smartyconfig.php');
include_once ('maininc.php');
include_once ('class.emailClass.php');
include_once ('class.soc.php');
include_once ('class/pagerclass.php');
include_once ('functions.php');
include_once ('class.invitations.php');
$invation = new invitations();
                if(!in_array($_REQUEST['id'],array(107,108,109,111))) exit('ERROR');
                $store_id=853490;       //AU:live:853490
		$textInfo = $invation->getInvationById($store_id,$_REQUEST['id']);
		$sellerInfo = $invation->get_SellerInfo($store_id);
		if(!$textInfo){
			echo "Invalid Invites History.";
			exit();
		}
		$textInfo = $textInfo[0];
		$tempid = $textInfo['email_template'];
		if($tempid==1||$tempid==2){
			$usertmp = $invation->getInvationUserTPL($store_id);
			$_REQUEST['usertpl_img'] = $usertmp['template'];
			$_REQUEST['template_type'] = 'user';
		}
		$TempInfo = $invation->getTemplate($tempid);
		echo $invation->previewHTML(array_merge($textInfo,$sellerInfo),$TempInfo['Images'],$TempInfo['Info']);
		exit();
        /*
         *  @INFINITY_END
         */
?>

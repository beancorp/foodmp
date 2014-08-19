<?php
/**
 * Fri Feb 06 09:00:22 GMT 2009 09:00:22
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * regedit free seller
 * ------------------------------------------------------------
 * soc\reg_free_seller.php
 */

session_start();
include_once ('include/smartyconfig.php');
include_once ('maininc.php');
include_once ('class.soc.php');
include_once ('class.socbid.php');
include_once ('class.socstore.php');
include_once ('class.emailClass.php');
include_once ('class.paymentipg.php');
include_once ('functions.php');

$objPayIPG = new paymentIPG();

switch($_REQUEST["cp"]){
	default:
		if (is_array($_POST)) {
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
			if($objPayIPG -> userRegFree()){
				
				echo '<script language="javascript">window.location.href = "'. $objPayIPG -> jumpPath .'";</script>';
				
			}else{
				
				echo '<script language="javascript">history.back();</script>';
				
			}

		}else {

			echo '<script language="javascript">history.back();</script>';

		}
		break;
}

unset($objPayIPG);
exit;
?>
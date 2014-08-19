<?php

include_once "include/session.php" ;
include_once "include/config.php" ;
include_once "include/maininc.php" ;
include_once "include/functions.php" ;


//$QUERY	= "UPDATE ".$table."bu_detail set bu_name='".$_POST['cu_name']."',bu_phone='".$_POST['cu_phone']."',bu_nickname='".$_POST['cu_nickname']."', bu_state='".$_POST['cu_state']."', bu_postcode='".$_POST['cu_postcode']."' where StoreID='$_POST[theStoreID]'";
if($_SESSION['UserID']=='' AND $_SESSION['level']!=2){
	header("Location:soc.php?cp=home");
	exit;
}

$QUERY="select count(".$table."login.StoreID) as num from ".$table."login , ".$table."bu_detail where ".$table."login.StoreID = ".$table."bu_detail.StoreID AND ".$table."login.StoreID ='$_POST[theStoreID]'";


$result	=	$dbcon->execute_query($QUERY) ;

$grid = $dbcon->fetch_records();

if($grid[0]['num']==0){ 
  session_destroy();
  header("Location:soc.php?cp=home");
}else{
		$phone = $_POST['phone1']."-".$_POST['phone2'];
		// add Country by Jessee 20081203
		if ($_POST['cu_country'] == $countryID){
			$QUERY	= "UPDATE ".$table."bu_detail set bu_name='".$_POST['cu_name']."',bu_phone='$phone',bu_country='".$_POST['cu_country']."',bu_state='".$_POST['cu_state']."',bu_postcode='".$_POST['cu_postcode']."',bu_nickname='".$_POST['cu_nickname']."',bu_suburb='".$_POST['suburb']."'   where StoreID='$_POST[theStoreID]'";
		}else{
			$QUERY	= "UPDATE ".$table."bu_detail set bu_name='".$_POST['cu_name']."',bu_phone='$phone',bu_country='".$_POST['cu_country']."',bu_state='".$_POST['fstate']."',bu_postcode='".$_POST['cu_postcode']."',bu_nickname='".$_POST['cu_nickname']."',bu_suburb='".$_POST['fsuburb']."'   where StoreID='$_POST[theStoreID]'";
		}

		//$QUERY	= "UPDATE ".$table."bu_detail set bu_name='".$_POST['cu_name']."',bu_state='".$_POST['cu_state']."',bu_postcode='".$_POST['cu_postcode']."',bu_nickname='".$_POST['cu_nickname']."',bu_suburb='".$_POST['suburb']."'   where StoreID='$_POST[theStoreID]'";
		$result	=	$dbcon->execute_query($QUERY) ;
	//echo print_r($_POST);
		if ($_POST['cu_pass'])
		{
			$pass = crypt($_POST['cu_pass'],getSalt());
			$QUERY	="UPDATE ".$table."login set password ='$pass' WHERE id=".$_SESSION['UserID']."";
			$result	=	$dbcon->execute_query($QUERY) ;
		}
		$msg	=	"Your details have been updated.";
		$_SESSION['UserName']	=	$_POST['cu_name']; 
		$_SESSION['PostCode']	=	$_POST['cu_postcode'];
		$_SESSION['State']		=	getStateByName($_POST['cu_state']);
		// add Country by Jessee at 20081203
		$_SESSION['Country']	=	$_POST['cu_country'];
		// Add suburb by ping.hu at 20080115
		$_SESSION['Suburb']		=	$_POST['suburb'];
	 	header("Location:soc.php?cp=edit_customers_geton&msg=$msg");
	//}
}

?>
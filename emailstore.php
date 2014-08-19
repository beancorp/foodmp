<?php
include_once('include/maininc.php');
include_once('include/functions.php');
include_once('include/smartyconfig.php');

session_start();
ob_start() ;

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Email the Store</title>
<script language="javascript">
function closePopupLogin(){
	window.opener.location.href('customers_geton.php');
	window.close() ;
}

function checkForm(obj){
	var errors	=	'';

	obj.fromEmail.value=='' ? (errors += '-  Your Email is required.\n') : '' ;
	obj.fromName.value=='' ? (errors += '-  Your Name is required.\n') : '' ;
	obj.fromPhone.value=='' ? (errors += '-  Contact Phone is required.\n') : '' ;
	obj.subject.value=='' ? (errors += '-  Subject is required.\n') : '' ;
	obj.body.value=='' ? (errors += '-  Message is required.\n') : '' ;

	if (errors != ''){
		errors = '-  Sorry, the following fields are required.\n' + errors;
		alert(errors);
		return false;
	}else{
		return true;
	}
}
</script>
<link href="skin/red/css/global.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.STYLE1 {
	color: #FF0000;
	font-weight: bold;
}

.upfile{ width:287px;}
-->
</style>
</head>


<?php
if(isset($_REQUEST['buyer'])&&trim($_REQUEST['buyer'])!=""){
	$query = "SELECT * FROM {$table}bu_detail WHERE StoreID='".base64_decode($_REQUEST['buyer'])."' limit 1";
	$dbcon ->execute_query($query);
	$result = $dbcon->fetch_records(true);
	if($result){
		$_SESSION['NickName'] = $result[0]['bu_nickname'];
		$_SESSION["UserName"] = $result[0]['bu_name'];
		$_SESSION['email']  = $result[0]['bu_email'];
		$_SESSION['LOGIN'] = "login";
	}
}
$url		=	$_REQUEST['url'];
$StoreID 	= 	$_REQUEST['StoreID'];
$emailaddress = $_SESSION['email'];
$level 		=	$_REQUEST['Level'];
$StoreName 	=	stripslashes(getStoreByName($_REQUEST['StoreID']));
$LOGIN 		=	trim($_SESSION['LOGIN']);

$place	=	empty($_REQUEST['place']) ? '' : $_REQUEST['place'];
$pid	=	empty($_REQUEST['pid']) ? '' : $_REQUEST['pid'];
$readonly = "";
if ($pid >0) {
	if ($place == 1) {
		$_query	= "select t1.location, st2.stateName, st2.description, st3.suburb as suburbName".
		" from ".$table."product_realestate as t1 ".
		" left join ".$table."state as st2 on t1.state=st2.id ".
		" left join ".$table."suburb as st3 on t1.suburb=st3.suburb_id ".
		" where t1.pid='$pid'";
		$dbcon -> execute_query($_query);
		$arrTemp	=	$dbcon -> fetch_records(true);
		
		if (is_array($arrTemp)) {
			$arrTemp	=	$arrTemp[0];
			$subject	=	"$arrTemp[location], $arrTemp[suburbName], $arrTemp[stateName]";
		}
		
	}elseif ($place == 2){
		$_query	= "select t1.regNo, st2.name as carTypeName,t1.make,t1.model, st3.name as makeName, st4.name as modelName".
		" from ".$table."product_automotive as t1 ".
		" left join ".$table."product_sort as st2 on t1.carType=st2.id ".
		" left join ".$table."product_sort as st3 on t1.make=st3.id ".
		" left join ".$table."product_sort as st4 on t1.model=st4.id ".
		" where t1.pid='$pid'";
		$dbcon -> execute_query($_query);
		$arrTemp	=	$dbcon -> fetch_records(true);
		
		if (is_array($arrTemp)) {
			$arrTemp	=	$arrTemp[0];
			
			$_tsql = "select make,makeUser,model,modelUser from ".$table."product_automotive where  pid='{$pid}'";
			$dbcon -> execute_query($_tsql);
			$tmpary = $dbcon -> fetch_records(true);
			if($arrTemp['make']=='-2'){
				$arrTemp['makeName']	 = $tmpary[0]['makeUser'];
				if($arrTemp['model']=='-2'){
					$arrTemp['modelName'] = $tmpary[0]['modelUser'];
				}
			}else{
				if($arrTemp['model']=='-2'){
					$arrTemp['modelName'] = $tmpary[0]['modelUser'];
				}	
			}
			
			$subject	=	"$arrTemp[makeName] $arrTemp[modelName] $arrTemp[regNo]";
		}
	}elseif ($place == 3){
		$_query	= "select t1.item_name,t1.category".
		" from ".$table."product_job as t1 ".
		" where t1.pid='$pid'";
		$dbcon -> execute_query($_query);
		$arrTemp	=	$dbcon -> fetch_records(true);
		
		if (is_array($arrTemp)) {
			$arrTemp	=	$arrTemp[0];
			$subject	=	"$arrTemp[item_name]";
			if($arrTemp['category'] == 2){
				$readonly = "readonly='true'";
			}
		}
	}else{
		$subject  = "";
	}
}
$query = "SELECT * FROM {$table}bu_detail WHERE StoreID = $StoreID";
$dbcon->execute_query($query);
$result = $dbcon->fetch_records(true);
if($result){
	$result = $result[0];
	require_once(LANGPATH."/soc.php");
	$subAttribName	=	($result['attribute'] == 1 || $result['attribute'] == 2) ? $_LANG['seller']['attribute'][$result['attribute']]['subattrib'][$result['subAttrib']] : ($result['attribute']==3 ? 'Advertiser' : ($result['attribute']==5 ? 'Retailer' : ''));
}
?>

<body>
<br>
<?php if(empty($LOGIN) && 0){// || $LOGIN!="login" || $_SESSION['level']!=2){?>
<br>
<CENTER>You need to be a member of <span class="STYLE1">SOC Exchange Australia</span> to use this service.
  <br>
  <span class="STYLE1">It's FREE</span>. To register now <a href="soc.php?cp=customers_geton&ctm=1" target="_blank">Click Here</a>.
</CENTER>
<?php } else {?>

<form action="/emailstoresubmit.php" method="post" enctype="multipart/form-data" id="startselling" style="margin:0px; padding:0px;" onSubmit="return checkForm(this);"> 
<CENTER><strong style="font-size:16px;">Contact <?php if($subAttribName !=""):echo $subAttribName;else:echo "Seller";endif;?></strong><BR>

<table width="540" cellspacing="6" >
<tr>
<td width="156" align="right" class="text">Your Email *</td>
<td width="368" align="left" ><input name="fromEmail" type="text" class="inputBox" value='<?php echo $emailaddress?>' <?php echo $readonly?>/></td>
</tr>

<tr>
<td width="156" align="right" class="text" >Your Name *</td>
<td align="left" ><input name="fromName" type="text" class="inputBox" value="<?php echo str_replace("\"","&quot;",((empty($_SESSION['NickName']))?$_SESSION["UserName"]:$_SESSION["NickName"]))?>" /></td></tr>
<tr>
  <td align="right" class="text" >Contact Phone *</td>
  <td align="left" ><input name="fromPhone" type="text" class="inputBox" id="fromPhone" value="" maxlength="30" /></td>
</tr>
<tr>
<td width="156" align="right" class="text" >Subject *</td>
<td align="left" >
	<?php 
		if($subject!=""){
	?>
	<input name="subject" type="hidden" value="<?php echo htmlspecialchars($subject) ?>" />
	<span class="inputBox" style="float:left;"><?php echo htmlspecialchars($subject) ?> </span>
	<?php
		}else{
	?>
		<input name="subject" type="text" class="inputBox" value="" />
	<?php
		}
	?>
	
	<input type="hidden" name="pid" value="<?php echo $pid==""?0:$pid; ?>"/>
</td></tr>
<tr>
<td width="156" align="right" valign="top" class="text">Message *</td>
<input type="hidden" name="StoreID" value="<?php echo $StoreID?>">
<td rowspan="2" align="left"><textarea name="body" rows="8" cols="60"></textarea></td>
</tr>
<tr align="right">
  <td width="156" class="text">&nbsp;</td>
  </tr>
<tr>
  <td align="right" class="text" >Attachment&nbsp;&nbsp;</td>
  <td align="left" ><input type="file" name="attachment" maxlength="50" class="upfile"></td>
</tr>
<?php if(empty($LOGIN)){// || $LOGIN!="login" || $_SESSION['level']!=2){?>
<tr height="30">
  <td align="right" class="text" >&nbsp;</td>
  <td align="left" >
    <input type="checkbox" name="signup" id="signup" value="1" /><label style="float:none; display:inline" for="signup">I would like to become a member of the SOC exchange now.</label>
    </td>
</tr>
<?php }?>
<tr>
  <td colspan="2" align="left" class="text">Note: The retailer/seller you are contacting will see your email address when this message arrives. Please always act responsibly when using email.</td>
  </tr>
</table>

<input type="image" style="border:none;" src="skin/red/images/buttons/or-submit.gif" value="Send"/>
</CENTER></form>

<?php  } ?>
</body>
</html>

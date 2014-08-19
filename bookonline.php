<?php
include_once('include/maininc.php');
include_once('include/functions.php');
include_once('include/smartyconfig.php');

session_start();
ob_start() ;

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Book Online</title><head>
<script language="javascript">
function closePopupLogin(){
	window.opener.location.href('customers_geton.php');
	window.close() ;
}

function checkForm(obj){ 
	var errors = '';
	var r = /^[0-9]*[1-9][0-9]*$/;
	obj.email.value=='' ? (errors += '-  Your Email is required.\n') : '' ;
	obj.name.value=='' ? (errors += '-  Your Name is required.\n') : '' ;
	obj.phone.value=='' ? (errors += '-  Contact Phone is required.\n') : '' ;
	obj.quantity.value=='' ? (errors += '-  No. of People is required.\n') : (!r.test(obj.quantity.value) ? (errors += '-  No. of People must be numberal.\n') : '');
	obj.reservation_date.value=='' ? (errors += '-  Reservation Date is required.\n') : '' ;
	obj.comments.value=='' ? (errors += '-  Comments is required.\n') : '' ;

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
?>

<body>
<br>
<?php if(empty($LOGIN)){// || $LOGIN!="login" || $_SESSION['level']!=2){?>
<br>
<CENTER>You need to be a member of <span class="STYLE1">SOC Exchange Australia</span> to use this service.
  <br>
  <span class="STYLE1">It's FREE</span>. To register now <a href="soc.php?cp=customers_geton&ctm=1" target="_blank">Click Here</a>.
</CENTER>
<?php } else {?>

<form action="/bookonlinesubmit.php" method="post" enctype="multipart/form-data" name="mainForm" id="startselling" style="margin:0px; padding:0px;" onSubmit="return checkForm(this);"> 
<CENTER><strong style="font-size:16px;">Book Online</strong><BR>

<table width="540" cellspacing="6" >
<tr>
<td width="156" align="right" class="text">Your Email *</td>
<td width="368" align="left" ><input name="email" type="text" class="inputBox" value='<?php echo $emailaddress?>' <?php echo $readonly?>/></td>
</tr>

<tr>
<td width="156" align="right" class="text" >Your Name *</td>
<td align="left" ><input name="name" type="text" class="inputBox" value="<?php echo (empty($_SESSION['NickName']))?$_SESSION["UserName"]:$_SESSION["NickName"]?>" /></td></tr>
<tr>
  <td align="right" class="text" >Contact Phone *</td>
  <td align="left" ><input name="phone" type="text" class="inputBox" id="phone" value="" maxlength="30" /></td>
</tr>
<tr>
  <td align="right" class="text" >No. of People *</td>
  <td align="left" ><input name="quantity" type="text" class="inputBox" id="quantity" value="" maxlength="30" /></td>
</tr>
<tr>
  <td align="right" class="text" >Reservation Date *</td>
  <td>
  	<input name="reservation_date" id="reservation_date" type="text" class="inputB date"  size="11" readonly="readonly" maxlength="11" value=""/>
    <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.mainForm.reservation_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a>
    <select name="start_hour" class="inputB time" id="start_hour" style="width:70px;">
    <?php for($i = 0;$i < 24;$i++) { ?>
        <option value="<?php echo $i<10 ? '0'.$i : $i;?>"><?php echo $i<10 ? '0'.$i : $i;?></option>
    <?php } ?>
    </select>&nbsp;:&nbsp;
    <select name="start_minute" class="inputB time" id="start_minute" style="width:71px;">
    <?php for($i = 0;$i < 60;$i++) { ?>
        <option value="<?php echo $i<10 ? '0'.$i : $i;?>"><?php echo $i<10 ? '0'.$i : $i;?></option>
    <?php } ?>
    </select>
 </td>
</tr>
<tr>
<td width="156" align="right" valign="top" class="text">Comments *</td>
<input type="hidden" name="StoreID" value="<?php echo $StoreID?>">
<td rowspan="2" align="left"><textarea name="comments" rows="8" cols="60"></textarea></td>
</tr>
<tr align="right">
  <td width="156" class="text">&nbsp;</td>
  </tr>
<tr>
  <td></td>
  <td align="left" class="text">Note: Book Online.</td>
  </tr>
</table>
<input type="hidden" name="booker_id" value="<?php echo $_SESSION['StoreID']; ?>" />
<input type="image" style="border:none" src="skin/red/images/buttons/or-submit.gif" value="Send"/>
</CENTER></form>

<?php  } ?>

<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.php" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
</body>
</html>

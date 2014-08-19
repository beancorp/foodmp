<?php
include_once('include/maininc.php');
include_once('include/functions.php');
include_once('include/smartyconfig.php');
include_once "include/class.guestEmailSubscriber.php";
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



$StoreID 	= 	$_REQUEST['StoreID'];
$emailaddress = $_SESSION['email'];
$level 		=	$_REQUEST['Level'];
$StoreName 	=	stripslashes(getStoreByName($_REQUEST['StoreID']));
$LOGIN 		=	trim($_SESSION['LOGIN']);

$readonly = "";

if(!isset($_REQUEST['GuestID']))
{
    $query = "SELECT * FROM {$table}bu_detail WHERE StoreID = '$StoreID'";
    $dbcon->execute_query($query);
    $result = $dbcon->fetch_records(true);
}else
{
    
    $guestSub = new guestEmailSubscriber();
    
    $result = $guestSub->getGuestSubscriber($StoreID, trim($_REQUEST['GuestID']));
}

if($result){
	$result = $result[0];
        
        $subsEmail = (isset($result['bu_email']))?$result['bu_email']:$result['email'];
        $subsNickname = (isset($result['bu_nickname']))?$result['bu_nickname']:$result['nickname'];
	require_once(LANGPATH."/soc.php");
        
}
?>

<body>
<br>
<?php if(empty($LOGIN)){// || $LOGIN!="login" || $_SESSION['level']!=2){?>
<br>
<CENTER>You need to be a member of <span class="STYLE1">SOC exchange Australia</span> to use this service.
  <br>
  <span class="STYLE1">It's FREE</span>. To register now <a href="soc.php?cp=customers_geton&ctm=1" target="_blank">Click Here</a>.
</CENTER>
<?php } else {?>

<form action="/email_subscriber_submit.php" method="post" enctype="multipart/form-data" id="startselling" style="margin:0px; padding:0px;" onSubmit="return checkForm(this);"> 
<CENTER><strong style="font-size:16px;">Contact Subscriber</strong><BR>

<table width="540" cellspacing="6" >
<tr>
<td width="156" align="right" class="text">Subscriber Nickname</td>
<td width="368" align="left" ><span style="color:#F26521"><?php echo $subsNickname?></span></td>
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
	
</td></tr>
<tr>
<td width="156" align="right" valign="top" class="text">Message *</td>
<td rowspan="2" align="left"><textarea name="body" rows="8" cols="60"></textarea></td>
</tr>
<tr align="right">
  <td width="156" class="text">&nbsp;</td>
  </tr>
<!--<tr>
  <td align="right" class="text" >Attachment&nbsp;&nbsp;</td>
  <td align="left" ><input type="file" name="attachment" maxlength="50" class="upfile"></td>
</tr>-->
<tr>
  <td colspan="2" align="left" class="text">Note: The subscriber you are contacting will not be able to see your email address when this message arrives. You may continue to communicate through FoodMarketplace or if you would like them to contact you directly you should include your email address in the message box.</td>
  </tr>
</table>
<input type="hidden" name="StoreID" value="<?php echo $StoreID?>" />
<input type="hidden" name="emailaddress" value="<?php echo nl2br($subsEmail)?>" />
<input type="hidden" name="fromtoname" value="<?php echo str_replace("\"","&quot;",$subsNickname);?>" />
<input type="hidden" name="fromEmail" class="inputBox" value='<?php echo $emailaddress?>' />
<input type="hidden" name="GuestID" class="inputBox" value='<?php echo $_REQUEST['GuestID']?>' />
<input type="image" style="border:none" src="skin/red/images/buttons/or-submit.gif" value="Send" />
</CENTER></form>

<?php  } ?>
</body>
</html>

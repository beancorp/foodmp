<?php
include_once "include/session.php" ;
include_once "include/config.php" ;
include_once "include/maininc.php" ;
include_once "include/functions.php" ;

$msgid = $_REQUEST['msgid'] ;
$LOGIN = $_SESSION['LOGIN'];
$StoreID = $_SESSION['StoreID'];

//update status
$sqlr = "update ".$GLOBALS['table']."message set status=1 where messageID='$msgid' and '$StoreID' IN (StoreID)";
$dbcon->execute_query($sqlr) ;
//get infomations

$sqlr = "select * from ".$GLOBALS['table']."message where messageID = '$msgid' and '$StoreID' IN (StoreID)" ;

$dbcon->execute_query($sqlr) ;

$grid = $dbcon->fetch_records() ;

?>

<html>
<head>
<title>Message</title>

<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet">
<style>
    td h2 span{ display:inline;}
</style>
</head>
<body style="margin:10px 10px 10px 10px">

<?php if(empty($LOGIN)){// || $LOGIN!="login" || $_SESSION['level']!=2){?>
<br>
<CENTER>You need to be a member of <span class="STYLE1">SOC Exchange Australia</span> to use this service.
  <br>
  <span class="STYLE1">It's FREE</span>. To register now <a href="soc.php?cp=customers_geton&ctm=1" target="_blank">Click Here</a>.
</CENTER>
<?php } elseif(empty($grid)) {?>
<br>
<CENTER>Please come from the currect url. <a href="soc.php?cp=sellerhome" target="_blank">Back</a>.
</CENTER>
<?php } else {?>
<div style="background-color:#FCF5F8;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="85" height="20">&nbsp;<b>From</b>&nbsp;</td>
    <td><?php echo nl2br($grid[0]['fromtoname'])?>&nbsp;</td>
  </tr>
  <?php if($_SESSION['attribute']==3 && $_SESSION['subAttrib']){ echo "<tr>
    <td width='85' height='20'>&nbsp;<b>Email</b>&nbsp;</td>
    <td>".$grid[0]['emailaddress']."&nbsp;</td></tr>";}?>
<?php if (!empty($grid[0]['phone'])){?>
  <tr>
    <td height="20">&nbsp;<b>Phone</b>&nbsp;</td>
    <td><?php echo nl2br($grid[0]['phone'])?>&nbsp;</td>
  </tr>
<?php } ?>
  <tr>
    <td height="20">&nbsp;<b>Subject</b>&nbsp;</td>
    <td><?php echo nl2br($grid[0]['subject'])?>&nbsp;</td>
  </tr>
<?php if (!empty($grid[0]['attachmentName'])){ ?>
  <tr>
    <td height="20">&nbsp;<b>Attachment</b>&nbsp;</td>
    <td><a href="<?php echo $grid[0]['attachment']?>" target="_blank"><?php echo $grid[0]['attachmentName']?></a>&nbsp;</td>
  </tr>
<?php }?>
  <tr>
    <td height="22" valign="top">&nbsp;<b>Message</b>&nbsp;</td>
    <td valign="top"><?php echo empty($grid[0]['message']) ? "" : (	$grid[0]['subject'] == 'SOCExchange Purchase Order' ? $grid[0]['message'] : ($grid[0]['message']))?>&nbsp;</td>
  </tr>
</table>
</div>

<BR><BR>
<?php
	if(nl2br($grid[0]['emailaddress'])!="SYSTEM"&&nl2br($grid[0]['emailaddress'])!=""){
?>
<b>Reply to this message:</b><BR>

<form method="post" action="replytomsg.php">

	<input type="hidden" name="emailaddress" value="<?php echo nl2br($grid[0]['emailaddress'])?>" >

	<input type="hidden" name="subject" value="<?php echo nl2br($grid[0]['subject'])?>" >

	<input type="hidden" name="fromtoname" value="<?php echo nl2br($grid[0]['fromtoname'])?>" >

	<input type="hidden" name="StoreID" value="<?php echo nl2br($grid[0]['StoreID'])?>" >

	<input type="hidden" name="pid" value="<?php echo $grid[0]['pid']?>" >

	<textarea name="body" rows="5" cols="70" style=" border:solid #ccc 1px;float:none" id="body"></textarea><BR>

	<input type="image" src="skin/red/images/buttons/or-reply.gif" value="Submit"/>

</form>
<?php
	}
?>

<?php  } ?>

</body>
</html>
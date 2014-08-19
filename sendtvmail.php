<?php
	ob_start();
	include_once ("include/session.php");
	include_once ('include/config.php');
	include_once ('include/smartyconfig.php');
	include_once ("maininc.php");
	if($_POST){
		if(get_magic_quotes_gpc()){
			$nickname = stripslashes($_REQUEST['nickname']);
			$message = nl2br(stripslashes($_REQUEST['message']));
			$email1 = stripslashes($_REQUEST['email1']);
			$name1 = stripslashes($_REQUEST['name1']);
			$email2 = stripslashes($_REQUEST['email2']);
			$name2 = stripslashes($_REQUEST['name2']);
			$email3 = stripslashes($_REQUEST['email3']);
			$name3 = stripslashes($_REQUEST['name3']);	
			
			$email4 = stripslashes($_REQUEST['email4']);
			$name4 = stripslashes($_REQUEST['name4']);
			$email5 = stripslashes($_REQUEST['email5']);
			$name5 = stripslashes($_REQUEST['name5']);
			$email6 = stripslashes($_REQUEST['email6']);
			$name6 = stripslashes($_REQUEST['name6']);
		}else{
			$nickname = $_REQUEST['nickname'];
			$message = nl2br($_REQUEST['message']);
			$email1 = $_REQUEST['email1'];
			$name1 = $_REQUEST['name1'];
			$email2 = $_REQUEST['email2'];
			$name2 = $_REQUEST['name2'];
			$email3 = $_REQUEST['email3'];
			$name3 = $_REQUEST['name3'];
			
			$email4 = $_REQUEST['email4'];
			$name4 = $_REQUEST['name4'];
			$email5 = $_REQUEST['email5'];
			$name5 = $_REQUEST['name5'];
			$email6 = $_REQUEST['email6'];
			$name6 = $_REQUEST['name6'];
		}
		$message .= "<br><br>Sincerely,<br>$nickname";
		if(trim($nickname)!=""){
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$headers .= 'From: '.$nickname.'<noreply@TheSOCExchange.com>' . "\r\n";
			$subject = "$nickname wants you to see this";
			$i =0;
			if(eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",trim($email1))){
				$message1 = "Dear $name1,<br><br>".$message;
				@mail($email1, $subject, $message1, $headers);
				$i++;
			}	
			if(eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",trim($email2))){
				$message2 = "Dear $name2,<br><br>".$message;
				@mail($email2, $subject, $message2, $headers);
				$i++;
			}
			if(eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",trim($email3))){
				$message3 = "Dear $name3,<br><br>".$message;
				@mail($email3, $subject, $message3, $headers);
				$i++;
			}
			if(eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",trim($email4))){
				$message4 = "Dear $name4,<br><br>".$message;
				@mail($email4, $subject, $message4, $headers);
				$i++;
			}
			if(eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",trim($email5))){
				$message5 = "Dear $name5,<br><br>".$message;
				@mail($email5, $subject, $message5, $headers);
				$i++;
			}
			if(eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",trim($email6))){
				$message6 = "Dear $name6,<br><br>".$message;
				@mail($email6, $subject, $message6, $headers);
				$i++;
			}
			if($i==0){
				$msg = "Please enter a valid email address at least.";
			}else if($i==1){
				$msg = $i." email has been sent.";
			}else{
				$msg = $i." emails have been sent.";
			}
		}else{
			$msg = "Please enter your Nickname.";
		}
	}else{
		if(isset($_SESSION['ShopID'])){
			$query = "select bu_nickname from {$table}bu_detail where StoreID='{$_SESSION['ShopID']}'";
			$dbcon->execute_query($query);
			$result = $dbcon->fetch_records(true);
			$nickname = $result[0]['bu_nickname'];
		}
	}
?>
<html>
<head>
<title>Email to your friends</title>
<style type="text/css">
	table tr td{padding:0 5px 5px 0; font:11px Verdana; color:#777777;}
	input { width:150px; height:27px; border:1px solid #CCCCCC; color:#777777; font:11px Verdana; padding:5px;}
	textarea { width:346px; height:150px; font:11px Verdana; border:1px solid #CCCCCC; color:#777777;}
</style>
</head>
<body style="margin:5px 0 0 15px;">
<form action="" method="POST">
<table cellpadding="0" cellspacing="0">
<tr><td align="center" colspan="4"><strong style="font-size:14px;">Send this page to your friends</strong></td></tr>
<tr><td align="center" colspan="4" style="color:red; height:12px;" ><?php echo $msg;?>&nbsp;</td></tr>
<tr><td colspan="4" align="center" style="padding:0 0 15px 0;">Your Name*:&nbsp;<input type="text" name="nickname" value="<?php echo $nickname;?>"/><td></tr>
<tr>
	<td width="88">Friend's Name:</td>
	<td><input type="text" value="" name="name1"/></td>
	<td>Email:</td>
	<td><input type="text" value="" name="email1"/></td>
</tr>
<tr>
	<td>Friend's Name:</td>
	<td><input type="text" value="" name="name2"/></td>
	<td>Email:</td>
	<td><input type="text" value="" name="email2"/></td>
</tr>
<tr>
	<td>Friend's Name:</td>
	<td><input type="text" value="" name="name3"/></td>
	<td>Email:</td>
	<td><input type="text" value="" name="email3"/></td>
</tr>
<tr>
	<td>Friend's Name:</td>
	<td><input type="text" value="" name="name4"/></td>
	<td>Email:</td>
	<td><input type="text" value="" name="email4"/></td>
</tr>
<tr>
	<td>Friend's Name:</td>
	<td><input type="text" value="" name="name5"/></td>
	<td>Email:</td>
	<td><input type="text" value="" name="email5"/></td>
</tr>
<tr>
	<td>Friend's Name:</td>
	<td><input type="text" value="" name="name6"/></td>
	<td>Email:</td>
	<td><input type="text" value="" name="email6"/></td>
</tr>
<tr>
<td valign="top">Message:</td>
<td colspan="3"><textarea name="message">Check out this website I found.
The SOC Exchange.com.au - is a site where you can sell everything online for $1 a Month, FLAT RATE. 
Click on the link below to view their TV ads. 

http://<?php echo $_SERVER['HTTP_HOST']?>/soc.php?cp=youtube</textarea>
</td>
</tr>
<tr>
	<td></td>
	<td colspan="3">
		<input type="image" style="width:81px; height:29px; border:0;" src="skin/red/images/buttons/or-submit.gif" value="Submit"/>
	</td>
</tr>
</tr>
</table>
</form>
</body>
</html>
<?php

ob_start();
include_once ("include/session.php");
include_once ('include/config.php');
include_once ('include/smartyconfig.php');
include_once ("maininc.php");
include_once ('class/common.php');
include_once ('class.emailClass.php');
include_once ('class.upload.php');
include_once SOC_INCLUDE_PATH . '/functions.php';
$continue	=	true;
if ($_REQUEST['cp']=='transmit' && !empty($_REQUEST['msgid'])) {
	$id	=	$_REQUEST['msgid'];
	$_query	=	"select * from ".$GLOBALS['table']."message where messageID=$id and StoreID='$_SESSION[StoreID]'";
	$dbcon->execute_query($_query);
	$arrTemp = $dbcon->fetch_records(true);

	if (is_array($arrTemp)) {
		$arrTemp	=	$arrTemp[0];

		$arrParams	=	array(
		'display'			=>	'conseller_detail',
		'To'				=>	$_SESSION['email'],
		'Subject'			=>	$arrTemp['subject'],
		'fromPhone'			=>	$arrTemp['phone'],
		'fromName'			=>	Input::StripString($arrTemp['fromtoname']),
		'fromEmail'			=>	$arrTemp['emailaddress'],
		'message'			=>	$arrTemp['message'],
		'seller_nickname'	=>	Input::StripString($_SESSION['NickName']),
		'webside_link'		=>	'http://'.$_SERVER['HTTP_HOST']
		);

		if ($arrTemp['attachment']) {
			$arrParams['attachment']		=	$arrTemp['attachment'];
			$arrParams['attachmentName']	=	$arrTemp['attachmentName'];
			$arrParams['attachmentType']	=	$arrTemp['attachmentType'];
		}

		$objEmail	=	new emailClass();
		if($objEmail -> send($arrParams,'email_contact_seller.tpl')){
			$msg = 'Your email has been sent. ' ;
		}else {
			$msg = 'Your email hasn\'t been sent. ' . $objEmail -> msg ;
		}
		unset($objEmail);
	}


} else {

	$objCommon	=	new common();
	$_var 		= 	$objCommon -> setFormInuptVar();
	extract($_var);

	$message		=	$body;
	$emailaddress	=	$fromEmail;
	$date		=	time();

	$arrSetting	=	array(
	"subject"		=>	$subject,
	"message"		=>	$message,
	"phone"			=>	$fromPhone,
	"StoreID"		=>	$StoreID,
	"date"			=>	$date,
	"emailaddress"	=>	$emailaddress,
	"fromtoname"	=>	$fromName,
	"pid"			=>  $pid
	);

	if ($_FILES['attachment']['error'] == '0') {
		$objUpload	=	new uploadFile(0,0,2048,11,$_FILES['attachment']);

		if (! $objUpload -> uploadOther()) {
			$msg	=	$objUpload ->strMessage;
			$continue	=	false;
		}else {
			$arrSetting['attachment']		=	"/".$objUpload ->newFileFullName;
			$arrSetting['attachmentName']	=	$_FILES['attachment']['name'];
			$arrSetting['attachmentType']	=	$_FILES['attachment']['type'];
		}
		$msg	=	$objUpload ->strMessage;
		unset($objUpload);
	}

	if ($continue) {

		if (!$dbcon-> insert_record($GLOBALS['table']."message", $arrSetting)) {
			$msg = $dbcon -> _errorstr;
		}else{
			$_query	=	"select bu_email, bu_nickname, outerEmail from ".$GLOBALS['table']."bu_detail where StoreID='$StoreID'" ;
			$dbcon->execute_query($_query);
			$arrTemp = $dbcon->fetch_records(true);

			if (is_array($arrTemp)) {
				$arrTemp	=	$arrTemp[0];
				$arrParams	=	array(
					'display'			=>	'conseller',
					'To'				=>	$arrTemp['bu_email'],
					'Subject'			=>	'Message Alert From FoodMarketplace',
					'seller_nickname'	=>	Input::StripString($arrTemp['bu_nickname']),
					'webside_link'		=>	'http://'.$_SERVER['HTTP_HOST']
				);
				$objEmail	=	new emailClass();
				$objEmail -> send($arrParams,'email_contact_seller.tpl');

				if ($arrTemp['outerEmail'] >0 ) {
					$arrParams['display']	=	'conseller_detail';
					$arrParams['Subject']	=	str_replace("''","'",$subject);
					$arrParams['fromPhone']	=	str_replace("''","'",$fromPhone);
					$arrParams['fromName']	=	Input::StripString(str_replace("''","'",$fromName));
					$arrParams['fromEmail']	=	str_replace("''","'",$emailaddress);
					$arrParams['message']	=	str_replace("''","'",$message);

					if ($arrSetting['attachment']) {
						$arrParams['attachment']		=	$arrSetting['attachment'];
						$arrParams['attachmentName']	=	$arrSetting['attachmentName'];
						$arrParams['attachmentType']	=	$arrSetting['attachmentType'];
					}

					$objEmail	=	new emailClass();
					$objEmail -> send($arrParams,'email_contact_seller.tpl');
				}
				$msg = 'Your email has been sent. ' ;
				//$msg .= $objEmail -> msg;
				unset($objEmail);
			}
		}

		if ($_REQUEST['signup']) {
			header('Location:soc.php?cp=customers_geton&ctm=1&attribute=0&&email='.$emailaddress.'&msg='.$msg);
		}

	}
}
?>



<html>
<head>
<title>Email Sent</title>
<LINK href="/skin/red/css/global.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
</head>

<body>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="3">&nbsp;<br /><br /></td>
    </tr>

    <tr>
      <td valign="top" colspan="3" align="center">
      <p class="txt"><font color="#FF0000">
        <?php echo $msg ; $msg=""?>
      </font></p>
    </tr>

  <tr>
      <td colspan="3" align="center"><a href="#" onClick="window.close()">Close window</a>&nbsp;</td>
  </tr>
  </table>
</body>
</html>
<?php

ob_start();
include_once "include/session.php" ;
include_once "include/config.php" ;
include_once "include/maininc.php" ;
include_once "include/functions.php" ;
include_once "include/smartyconfig.php";

$msg = $_REQUEST['msg'];

if(!empty($_REQUEST['iserName'])){

	$username	=	$_REQUEST['iserName'];

	$query		=	"Select t1.*,t2.bu_nickname from ".$table."login as t1 left join ".$table."bu_detail as t2 on t1.StoreID=t2.StoreID where t1.user ='$username' ORDER BY t2.attribute ASC";

	$result		=	$dbcon->execute_query($query);

	$grid 		= $dbcon->fetch_records();

	if(!empty($grid)){

		$to  = $grid[0]['user']; ; // note the comma

		//		$to  = "yogendra.tripathi@sparrowi.com"; ; // note the comma

		/* subject */

		$subject = "Username/password";

		$message = "<html><head><title>Forget Password</title></head><body><table width='100%'>
					  <tr><td>Dear ".$grid[0]['bu_nickname'].",</td></tr>
					  <tr><td height='120' align='center'><table width='90%'>
					  <tr><td colspan=2 style='hieght:5px;'>&nbsp;<td></tr>
					  <tr><td colspan=2>Following is your account details:</td></tr>
					  <tr><td colspan=2 style='hieght:5px;'>&nbsp;<td></tr>";
		/**
		 * edit by royluo 20090108
		 */
		foreach ($grid as $pass){
			$usertype = "";
			switch ($pass['attribute']){
				case 0:
					/*Online Store*/
					$usertype = "Buy & Sell - ";
					break;
				case 1:
					/*Real Estate*/
					$usertype = "Real Estate - ";
					break;
				case 2:
					/*Vehicles*/
					$usertype = "Auto - ";
					break;
				case 3:
					/*Job Market*/
					$usertype = "Job Market -";
					break;
				case 4:
					/*Buyer*/
					$usertype = "Buyer - ";
					break;
				case 5:
					/*Buyer*/
					$usertype = "Food & Wine - ";
					break;
			}
			$message .="<tr><td align = 'left'>$usertype</td><td align = 'left'> Username: ".($pass['attribute']==5?$pass['username']:"<a href='mailto:{$pass['user']}'>{$pass['user']}</a>")."</td></tr>
					  <tr><td align = 'left'></td><td align='left'>Password: ".$pass['password']."</td></tr>
					  <tr><td colspan=2 style='hieght:5px;'>&nbsp;<td></tr>";
		}
		
		$message .= "</table></td>
					  </tr>
					<tr><td align = 'left'><p>Sincerely,<br>SOC exchange Australia</p></td></tr>
					</table></body></html>";

		/* To send HTML mail, you can set the Content-type header. */

		$headers  = "MIME-Version: 1.0\r\n";

		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";



		/* additional headers */

		//$headers .= "To: ".$to." \r\n";

		$headers .= "From: mail@TheSOCExchange.com\r\n";

		//		$headers .= "Cc: yogendra.tripathi@sparrowi.com\r\n";



		/* and now mail it */
		mail($to, $subject, getEmailTemplate($message), fixEOL($headers));

		$msg	=	"Your password has been sent, please check your Email.";

		header("Location:forgetpass.php?msg=$msg");

	}else{

		$msg	=	"Failed to send your password through. Please try again.";

		header("Location:forgetpass.php?msg=$msg");

	}

}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<title>SOC Exchange Australia ::  Home</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="css/main.css" rel="stylesheet" type="text/css" />

<script language="JavaScript" type="text/JavaScript">

</script>

</head>



<script language="javascript1.1">

function checkForm()

{

	var	errors='';

	if(document.forget.iserName.value==''){

		errors	+="- Sorry, Username/email is reqired\n";

	}

	/*if(document.forget.CUserName.value==''){

	errors	+="- Sorry, confirm username/email is reqired\n";

	}

	if(document.forget.iserName.value!=document.forget.CUserName.value){

	errors	+="- Sorry, Username/email and confirm Username/email should be same\n";

	}*/

	if(errors!=''){

		alert(errors);

		return false;

	}

	return true;

}



</script>



<body>

  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">

    <tr>

      <td class="bgrep"><table width="100%"  border="0" cellspacing="0" cellpadding="0">

          <tr>

            <td align=left><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="ltpanel_bgrep">
				<tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <td height="24" colspan="3" align="center" valign="top"  class="arial11" style="padding-right:10px; ">&nbsp;<font color="#FF0000">
                  <center><strong><?php echo $msg;?></strong></center></font></td>
              	</tr>

                <tr>

                  <td align="right" valign="top" class="ltpanel_bot" style="padding-right:10px; ">&nbsp;</td>

                  <td>&nbsp;</td>

                  <td align="left" style="padding-left:10px; ">&nbsp;</td>

                </tr>

				<form name="forget" action="forgetpass.php" method="post" onsubmit="return checkForm()">

                <tr align="center">

                  <td colspan="3" valign="top" class="sheet_title" style="padding-right:10px; ">Please Enter your details </td>

                </tr>

                <tr>

                  <td align="right" valign="top" class="ltpanel_bot" style="padding-right:10px; ">&nbsp;</td>

                  <td>&nbsp;</td>

                  <td align="left" style="padding-left:10px; ">&nbsp;</td>

                </tr>

                <tr>

                  <td align="right" valign="middle" class="btnLogin" style="padding-right:10px; ">Enter Email Address:</td>

                  <td>&nbsp;</td>

                  <td width="50%" align="left" style="padding-left:10px; "><input name="iserName" type="text" class="hbutton" id="iserName" style="padding-top:4px;"/></td>

                </tr>

                <!--<tr>

                  <td align="right" valign="top" class="btnLogin" style="padding-right:10px; ">Confirm Username/email:</td>

                  <td>&nbsp;</td>

                  <td align="left" style="padding-left:10px; "><input name="CUserName" type="text" class="hbutton" id="CUserName" /></td>

                </tr>-->

                <tr>

                  <td align="right" valign="top" class="ltpanel_bot" style="padding-left:10px; "></td>

                  <td>&nbsp;</td>

                  <td align="left" style="padding-left:10px; ">&nbsp;</td>

                </tr>

                <tr>

                  <td colspan="3" align="center" valign="top" class="ltpanel_bot" style="padding-left:10px; "><input name="imageField" type="image" src="/skin/red/images/buttons/or-submit.gif" width="81" height="29" border="0" /></td>

                </tr>

				</form>

                <tr>

                  <td width="50%" align="right" valign="top" class="ltpanel_bot" style="padding-left:10px; "></td>

                  <td width="8">&nbsp;</td>

                  <td align="left" style="padding-left:10px; ">&nbsp;</td>

                </tr>

            </table></td>

          </tr>

      </table></td>

    </tr>

</table>


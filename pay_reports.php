<?

include_once "include/session.php" ;
include_once "include/config.php" ;
include_once "include/maininc.php" ;
include_once "include/functions.php" ;

$date	=	time();
if($_SERVER['HTTP_HOST']=="usa.buyblitz.com" || $_SERVER['HTTP_HOST']=="buyblitz.com" ||$_SERVER['HTTP_HOST']=="www.buyblitz.com"){
	$path	=	"http://".$_SERVER['HTTP_HOST']."";
}else if ($_SERVER['HTTP_HOST']=="mercury.myserverhosts.com"){
	$URL		=	"http://".$_SERVER['HTTP_HOST']."/~buyblitz/admin/";
}else if ($_SERVER['HTTP_HOST']=="dev.infinitytech.cn"){
	$URL		=	"http://".$_SERVER['HTTP_HOST']."/php/buyblitz/";
}else{
	$path	=	"http://".$_SERVER['HTTP_HOST']."/buyblitz_usa";
}

$url=$path."/activate.php?action=return";
$url1=$path."/activate.php?action=cancel";
$url2=$path."/activate.php?action=notify";
$StoreID = $_SESSION['StoreID'];

if($_SESSION['UserID']=='' AND $_SESSION['level']!=1){
	header("Location:index.php");
}

getStoreID($_SESSION['UserID']);
$dateformat = str_replace("-","/",str_replace("%","",DATAFORMAT_DB));

$QUERY		=	"SELECT * FROM ".$table."bu_detail WHERE StoreID='$_SESSION[ShopID]'";
$rows	=	mysql_fetch_object(mysql_query($QUERY));

if($rows->PayDate!=''){
	$startDate	=	date($dateformat,$rows->PayDate);
	$nextyear  = date($dateformat,$rows->renewalDate);

}else{
	$startDate	=	date($dateformat);
	$nextyear  = date($dateformat);
	$msg	=	"You have not currently paid. Your site will not be live at Buyblitz until payment is received. To pay now click here.";
	
}



if(isset($_REQUEST['submit']) AND $_REQUEST['submit']!=''){
	$fromDate	=	(isset($_REQUEST['fromDate']) && $_REQUEST['fromDate'] !='') ? $_REQUEST['fromDate']:'';
	$toDate		=	(isset($_REQUEST['toDate']) && $_REQUEST['toDate'] !='') ? $_REQUEST['toDate']:'';
	$date		=	"From $fromDate To $toDate";
	$flag=1;
}

$lastMonth	=	(isset($_REQUEST['lastMonth']) && $_REQUEST['lastMonth'] !='') ? $_REQUEST['lastMonth']:'';

if($lastMonth!=''){
	$date		=	"For the Last Month";
	$flag=1;
}

if($flag==1){
	$PaymentReports		=	getPaymentStore($StoreID,$fromDate,$toDate,$lastMonth);
	$PaymentReports1	=	explode("~",$PaymentReports);
	$OrderAddr			=	explode(",",$PaymentReports1[0]);
	$storeNameAddr		=	explode(",",$PaymentReports1[1]);
	$dateAddr			=	explode(",",$PaymentReports1[2]);
	$CostAddr			=	explode(",",$PaymentReports1[3]);
	$tot=0;

	foreach($CostAddr AS $values){
		$tot +=$values;
	}

	if ($tot==0)
	{
		$PaymentReports = "No Payment Records";
	}
	else
	{
		$PaymentReports		="<table width=100%>";
		$PaymentReports		.="<tr><td align=center colspan=4 background='../images/heading_bg.gif' class='header'><b>Payments Reports $date</b><br /><br /></td></tr>";
		$PaymentReports		.="<tr><td align=left><b>Order ID</b></td><td align=left><b>Order Ammount</b></td><td align=left><b>Order Date</b></td></tr>";
		$i=0;
		foreach($CostAddr AS $values){
			if($values >0){
				if($i%2==0){
					$bgColor	=	"tdRow";
				}else{
					$bgColor	=	"tdRow1";
				}

				$PaymentReports		.="<tr><td class=$bgColor align=left>$OrderAddr[$i]</td><td class=$bgColor align=left>$$values</td><td class=$bgColor align=left>$dateAddr[$i]</td></tr>";
				$i++;
			}
		}
		$PaymentReports		.="</table>";
	}
}

$lastmonth		=	mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
$datelast		=	date("Y-m-d",$lastmonth);

echo $PaymentReports;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<title>The SOC Exchange Australia ::  Payment Reports</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="css/style.css" rel="stylesheet" type="text/css" />

<style type="text/css">

<!--

.style2 {

	color: #FFFFFF;

	font-weight: bold;

	font-size: 14px;

}

.style3 {font-weight: bold}

-->

</style>

<script language="JavaScript" type="text/JavaScript">

<!--



function MM_findObj(n, d) { //v4.01

	var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {

		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}

		if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];

		for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);

		if(!x && d.getElementById) x=d.getElementById(n); return x;

}


function changeMoney(values)
{
	try{
		document.getElementById('disMoney').innerHTML= (values >= 10 ? '1 year &nbsp; &nbsp;' : values+' month(s)');
	}catch(ex){
		alert(ex);
	}
}
//-->

</script>

<body onload="MM_preloadImages('images/about_o.gif','images/businesses_o.gif','images/customers_o.gif','images/home_o.gif','images/faqs_o.gif','images/contact_o.gif','images/admin_home_o.gif','images/change_password_o.gif','images/payments_o.gif','images/store_orders_o.gif','images/update_store_o.gif','images/newadbookings_o.gif','images/newlogout_o.gif')" >

  <table width="821"  border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td  align="center" background="images/bgrep.png"><table width="770" border="0" cellspacing="0" cellpadding="0">

          <tr>

            <td><? include_once("top.php"); ?></td>

          </tr>

          <tr>

            <td align=left><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="ltpanel_bgrep">

                <tr>

                  <td width="167" height="500" valign="top" class="ltpanel_bot"><? include_once("left1.php"); ?></td>

                  <td width="8"><img src="images/sp.gif" width="1" height="1" alt="spacer" /></td>

                  <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">

                    <tr>

                      <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">

                          <tr>

                            <td class="title_ltcr"><img src="images/sp.gif" width="1" height="1" alt="spacer" /></td>

                            <td class="title_bg style2"><img src="images/payment_reports_heading.gif" width="145" height="21" /></td>

                            <td class="title_rtcr"><img src="images/sp.gif" width="1" height="1" alt="spacer" /></td>

                          </tr>

                      </table></td>

                    </tr>

                    <tr>

                      <td >

<table width="100%"  border="0">

        <tr>

          <td width="17%">&nbsp;</td>

          <td width="41%">&nbsp;</td>

          <td width="19%">&nbsp;</td>

          <td width="23%">&nbsp;</td>

        </tr>



        <tr>

          <td align="left">&nbsp;</td>

          <td>&nbsp;</td>

          <td>&nbsp;</td>

          <td>&nbsp;</td>

        </tr>

        <tr>

          <td align="left">Joining Date:</td>

          <td><?=$startDate?></td>

          <td align="left">Renewed Date:</td>

          <td><?=$nextyear?></td>

        </tr>

        <tr align="center">

          <td colspan="4">&nbsp;</td>

        </tr>

        <tr align="center">

          <td colspan="4" align="left"><span class="style3">

            <?=$msg?>

          </span></td>

          </tr>
		  
		  <?php if($_SESSION['CatID'] != 11){?>

        <tr align="center">

          <td colspan="4">
		  <form action="https://www.paypal.com/cgi-bin/webscr" method="post"  name="paypall" id="paypall">

          <input type="hidden" name="cmd" value="_xclick">

          <input type="hidden" name="business" value="info@thesocexchange.com">

           <input type="hidden" name="item_name" value="Deposit money in your account">                                                   

           <input type="hidden" name="amount" value="365"> 

           <input type="hidden" name="currency_code" value="AUD"> 

		   <input type="hidden" name="item_number" value="<?=$_SESSION['StoreID']?>">

  <!--          <input type="hidden" name="amount" value="365">  -->

           <input type="hidden" name="StoreID" value="<?=$_SESSION['StoreID']?>"> 

           <input type="hidden" name="return" value="activate.php">

          <input type="hidden" name="cancel_return" value="pay_reports.php">

          <input type="hidden" name="notify_url" value="activate.php">

            <input name="submit" type="submit" class="greenButt" value="Pay Now" />

          </form></td>

        </tr>

        <tr>

          <td colspan="4" style="padding-left:3px; ">If your store listing is about to expire you can extend this service for another 12 months by clicking the link above &lsquo;Pay Now&rsquo;. </td>

          </tr>

		<?php }else{ ?>
		
		<tr><td colspan="4">
		<script language=javascript>
		function checkForm(){
			document.paypall.return.value = 'http://www.buyblitz.com/activate.php?month='+document.paypall.amount.options[document.paypall.amount.selectedIndex].value;
			document.paypall.notify_url.value = 'http://www.buyblitz.com/activate.php?month='+document.paypall.amount.options[document.paypall.amount.selectedIndex].value;
			return true;
		}
		</script>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <form  name="paypall" action="https://www.paypal.com/cgi-bin/webscr" method="post" onsubmit="checkForm();">
    	   <input type="hidden" name="cmd" value="_xclick">
           <input type="hidden" name="business" value="info@thesocexchange.com">
           <input type="hidden" name="item_name" value="Deposit money in your account">                                 
           <input type="hidden" name="currency_code" value="AUD"> 
		   <input type="hidden" name="item_number" value="<?=$_SESSION['StoreID']?>">
           <input type="hidden" name="StoreID" value="<?=$_SESSION['StoreID']?>"> 
           <input type="hidden" name="return" value="http://www.socexchange.com.au/activate.php">
          <input type="hidden" name="cancel_return" value="http://www.socexchange.com.au/soc.php?cp=payreports">
          <input type="hidden" name="notify_url" value="http://www.socexchange.com.au/activate.php">
    <tr>
      <td align="center" valign="top" class="pad10px"><img src="images/amex.gif" border="0"/>&nbsp;<img src="images/mastercard.gif" border="0"/>&nbsp; <img src="images/visa.gif" border="0"/>&nbsp; <img src="images/paypal_intl.gif" width="88" height="33" border="0"/></td>
      </tr>
    <tr>
      <td align="right" valign="top" class="pad10px"><table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#666666">
        <tr>
          <td width="9%" height="30" align="center" bgcolor="#FFFFFF">$1</td>
          <td width="11%" align="center" bgcolor="#FFFFFF">$2</td>
          <td width="11%" align="center" bgcolor="#FFFFFF">$3</td>
          <td width="10%" align="center" bgcolor="#FFFFFF">$4</td>
          <td width="11%" align="center" bgcolor="#FFFFFF">$5</td>
          <td width="10%" align="center" bgcolor="#FFFFFF">$6</td>
          <td width="10%" align="center" bgcolor="#FFFFFF">$7</td>
          <td width="10%" align="center" bgcolor="#FFFFFF">$8</td>
          <td width="10%" align="center" bgcolor="#FFFFFF">$9</td>
          <td width="8%" align="center" bgcolor="#FFFFFF">$10</td>
        </tr>
        <tr>
          <td height="35" align="center" bgcolor="#FFFFFF">1<br />
            month</td>
          <td align="center" bgcolor="#FFFFFF">2<br />
            months</td>
          <td align="center" bgcolor="#FFFFFF">3<br />
            months</td>
          <td align="center" bgcolor="#FFFFFF">4<br />
            months</td>
          <td align="center" bgcolor="#FFFFFF">5<br />
            months</td>
          <td align="center" bgcolor="#FFFFFF">6<br />
            months</td>
          <td align="center" bgcolor="#FFFFFF">7<br />
            months</td>
          <td align="center" bgcolor="#FFFFFF">8<br />
            months</td>
          <td align="center" bgcolor="#FFFFFF">9<br />
            months</td>
          <td align="center" bgcolor="#FFFFFF">1<br />
            year </td>
        </tr>
        <tr>
          <td height="30" align="center" bgcolor="#FFFFFF"><input name="amount" type="radio" value="1" /></td>
          <td align="center" bgcolor="#FFFFFF"><input name="amount" type="radio" value="2" /></td>
          <td align="center" bgcolor="#FFFFFF"><input name="amount" type="radio" value="3" /></td>
          <td align="center" bgcolor="#FFFFFF"><input name="amount" type="radio" value="4" /></td>
          <td align="center" bgcolor="#FFFFFF"><input name="amount" type="radio" value="5" /></td>
          <td align="center" bgcolor="#FFFFFF"><input name="amount" type="radio" value="6" /></td>
          <td align="center" bgcolor="#FFFFFF"><input name="amount" type="radio" value="7" /></td>
          <td align="center" bgcolor="#FFFFFF"><input name="amount" type="radio" value="8" /></td>
          <td align="center" bgcolor="#FFFFFF"><input name="amount" type="radio" value="9" /></td>
          <td align="center" bgcolor="#FFFFFF"><input name="amount" type="radio" value="10" checked="checked" /></td>
        </tr>
      </table>
        <span > <br />
       <input name="submit" type="submit" class="greenButt" value="Pay Now" />
       </span>
       </td>
      </tr>
  </form>
  <tr>
    <td class="pad10px"><div align="center">
      <p align="left">Open your website now. It is only $1 per month. A 1 year purchase for $10 in advance, will give you a 17% discount on the normal monthly rate. All State & Federal taxes are included.</p>
      <p align="left"></p>
    </div></td>
  </tr>
</table>
		
		</td></tr>

		<?php }?>

<!--  		

		<form action="pay_reports.php" method="post" name="formp" id="formp" >            

        <tr>

          <td align="right"><div align="justify">From:</div></td>

          <td align="left"><div align="justify"><span class="style11"><font face="Verdana" size="1"><font face="Verdana" size="1">

                <input name="fromDate" type="text" class="inputB" id="fromDate" size="15" value=""  readonly >

                <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.formp.fromDate);return false;" HIDEFOCUS><img align="absmiddle" src="cal/calbtn.gif" width="34" height="22" border="0" alt=""></a></font></font></span></div></td>

          <td align="right"><div align="justify">To:</div></td>

          <td align="left"><div align="justify"><span class="style11"><font face="Verdana" size="1"><font face="Verdana" size="1">

                <input name="toDate" type="text" class="inputB" id="toDate2" size="15" value=""  readonly >

                <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.formp.toDate);return false;" HIDEFOCUS><img align="absmiddle" src="cal/calbtn.gif" width="34" height="22" border="0" alt=""></a></font></font></span></div></td>

          <td align="left"><div align="justify"><a href="pay_reports.php?lastMonth=<?=$datelast?>">Last Month </a></div></td>

        </tr>

        <tr>

          <td colspan="5" align="center">&nbsp;</td>

          </tr>

        <tr>

          <td colspan="5" align="center">

            <input name="submit" type="image" src="images/submit1.jpg" width="54" height="20" border="0" value="Submit" /></td>

        </tr>

		</form>

        <tr>

          <td colspan="5" align="center">&nbsp;</td>

        </tr>

        <tr>

          <td colspan="5" align="center"><?=$PaymentReports?></td>

          </tr>

		 -->  

      </table>

</td>

                    </tr>

                  </table></td>

                </tr>

            </table></td>

          </tr>

          <tr>

            <td height="23"><? include_once("bot.php"); ?></td>

          </tr>

      </table></td>

    </tr>

    <tr>

      <td><img src="images/bottom.gif" width="821" height="27" alt="spacer" /></td>

    </tr>

  </table>

 <iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="cal/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">

</iframe>



</body>

</html>
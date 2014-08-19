{literal}
<style type="text/css">
	ul.mainlist {list-style:none; margin:0; width:750px; background:#9E99C1; float:left; }
	ul.mainlist li{padding:0;margin:0; float:left; width:100%; }
	
	ul.listhead { list-style:none; margin:0; padding:0; width:100%; float:left; height:23px;}
	ul.listhead li{ padding:0; background:#9E99C1;border-left:1px solid #9E99C1;  color:#FFFFFF; font-weight:bold; text-align:center;height:22px;
			   line-height:22px;}
	ul.listhead li{ margin:0 0 1px 0px ; float:left;width:120px;}
	ul.listhead li.detail{width:627px;}
	
	ul.list { height:23px;
			  list-style:none; margin:0; padding:0; width:100%; float:left;}
	
	ul.list li{float:left;
				width:120px;
			   height:22px;
			   line-height:22px;
			   margin:0 0 0px 0px;
			   border-left:1px solid #9E99C1; 
			   border-bottom:1px solid #9E99C1;
			   text-align:center;}
	ul.list li{ padding:0; background:#ffffff;}
	ul.list li.detail{width:627px;}
	
	ul.mainlist li.lifooter{ border-left:1px solid #FFFFFF;
			border-bottom:1px solid #FFFFFF;
			border-right:1px solid #FFFFFF;
			background:#FFFFFF;
			width:748px;
			height:22px;
			line-height:22px;
			text-align:right;
    }
	ul.mainlist li.pagelist{ border-left:1px solid #FFFFFF;
			border-bottom:1px solid #ffffff;
			border-right:1px solid #ffffff;
			background:#FFFFFF;
			width:748px;
			height:22px;
			line-height:22px;
			text-align:center;
    }
#content_top{ }
#content_top .red{ color: red;}
#content_top h3{ color:red; font-weight:bold; margin:0;}
#content_top .purple{color: purple;}
</style>
<script>
	function changepayment(obj){
		if(obj.value==1){
			document.getElementById('paymentCon').style.display='none';
			document.getElementById('chequeCon1').style.display='';
			document.getElementById('chequeCon2').style.display='';
		}else{
			document.getElementById('chequeCon1').style.display='none';
			document.getElementById('chequeCon2').style.display='none';
			document.getElementById('paymentCon').style.display='';
		}
	}
	function checkform(obj){
		if(confirm('Please make sure you have entered your Paypal details correctly. Continue?')){
			obj.submit();
		}
	}
</script>
{/literal}
{$req.xajax_Javascript}

<div class="purpleTitle" style="text-align:center;"> My Referrer ID is:  {$req.refid}
&nbsp; {if $smarty.session.attribute eq '0'}<img src="/skin/red/images/adminhome/icon-email-forward.gif" /><a href="/soc.php?cp=ref_email" style="margin-right:10px;">Refer friends</a> 
	<div style="padding-top:10px;font-weight:bold;">Get HTML code to refer users:&nbsp;<input type="text" class="inputB" style="width:158px;" value="{$req.widgetHTML|escape:html}" onclick="this.select();"/></div>{/if}
</div>
<div style="color:#FF0000; text-align:center">{$req.msg}</div>
<div id="refcontent">
{include file="referrer_userlist.tpl"}
</div>
{php}
/*
<div style="margin-top:10px; width:100%;">
<form action=""  method="post" onsubmit="checkform(this);return false;">
<table cellspacing="4" width="100%">
	<input type="hidden" name="checktype" value="2"/>
	<tr id="paymentCon" >
		<td>Paypal Account:*</td> <td><input type="text" name="pname" value="{$req.pname}"  class="inputB"	 /></td>
	</tr>
	<tr><td colspan="2">
	{if $req.ref.earn_amount >= $req.minamount.min_commission}
	
	<input type="image" src="/skin/red/images/requestPayment.gif" style="padding:0; margin:0; float:left;"/>{else}<img src="/skin/red/images/requestPayment1.gif" style="padding:0; margin:0; float:left;"/>{/if}<span style="font-size:9px;padding-top:18px; float:left;">&nbsp;You can request a payment once your balance reaches over ${$req.minamount.min_commission|number_format:2}.</span></td></tr>
</table>
<input type="hidden" name="request_send" value="send"/>

</form>
</div>
*/
{/php}
<div style="height:40px;"></div>
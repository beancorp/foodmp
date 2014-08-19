<link href="/skin/red/css/global.css" rel="stylesheet" type="text/css" />
<script language="javascript">
{literal}
$(document).ready(function(){


	$("#mainSubmit").click(function(){
		
		if('' == $.trim($("#txtname").val())) {
			$("#ajaxmessage").css('color','red').html("Name is required.");
			return false;
		}	
		if('' == $.trim($("#cardNumber").val())) {
			$("#ajaxmessage").css('color','red').html("Card Number is required.");
			return false;
		}	
		$(this).attr('src','/skin/red/images/buttons/gray-submit.gif');
		$(this).attr('disabled','disabled');
		$("#ipgback").hide();
		$("#ipgback_gray").show();
		$("#ajaxmessage").html('Processing...');
		var formData = $("#mainIPGForm").serialize();
		$.post(
			'{/literal}{if $req.ipgForm.request_url}{$req.ipgForm.request_url}{else}/soc.php?act=nr&cp=_request{/if}{literal}',
			{'formData':formData},
			function(response) {
				response = eval('('+response+')');
				if('false' == response.status) {
					$("#ajaxmessage").css('color','red').html(response.msg);
					$("#mainSubmit").attr('src','/skin/red/images/buttons/or-submit.gif');
					$("#mainSubmit").attr('disabled','');
					$("#ipgback").show();
					$("#ipgback_gray").hide();
					return;
				}
				else {
					$("#ajaxmessage").css('color','red').html('Thank you for your successful payment');
					if(response.jumpPath) {
						location.href=response.jumpPath;
					}
					
				}
				
				
				
			}
		);
	});
	
});

{/literal}
</script>
<div id="ajaxmessage" class="text" style="text-align:center;color:red;" ></div>
<form id="mainIPGForm" name="mainIPGForm" method="post" action="" onsubmit="{$req.element.jsSubmit}return false;">
<table width="100%" height="269"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="160" align="center">

<table width="89%" height="208">
<tr>
  <td height="35">&nbsp;</td>
  <td align="left">
  <td></tr>
  
  <tr>
  <td width="327" height="35" align="left"><strong>Name</strong></td>
  <td width="487" align="left"><INPUT name="txtname" type="text" class="inputB" id="txtname" value="" style="width:200px;">
  <td width="61"></tr>
  
<tr>
  <td width="327" height="35" align="left"><strong>{$lang.payment.CCNumber}</strong></td>
  <td width="487" align="left"><INPUT name="cardNumber" type="text" class="inputB" id="cardNumber" value="{$req.ipgForm.cardNumber}" style="width:200px;" maxlength="19">
  <td width="61"></tr>
  
  
  <tr>
  <td width="327" height="35" align="left"><strong>{$lang.payment.cvc2}</strong> <em style="font-style:italic">{$lang.payment.note}</em></td>
  <td width="487" align="left"><input name="cvc2" type="text" id="cvc2"maxlength="6" class="inputB" style="width:50px;" />
  <td width="61"></tr>
  
  
<tr>
  <td width="327" height="35" align="left"><strong>{$lang.payment.expiry}</strong> <em style="font-style:italic">{$lang.payment.expiryFormat}</em></td>
  <td align="left"><select name="expiryMonth" id="expiryMonth" class="inputB" style="width:50px;">{$req.ipgForm.expiryMonth}</select>
  /    
	<select name="expiryYear" id="expiryYear" class="inputB" style="width:65px;">{$req.ipgForm.expiryYear}</select>
  <td></tr>
<tr>
  <td width="327" height="35" align="left"><strong>{$lang.payment.amount}</strong></td>
  <td align="left">${$req.ipgForm.amount} {$lang.CurCode}<td>
</tr>
<tr>
  <td width="327" height="35" align="left"><strong>{$lang.labelEmail}</strong> <em style="font-style:italic">{$lang.payment.forReceipt}</em></td>
  <td align="left">{$req.ipgForm.email}
  <td></tr>
<tr>
  <td width="327" height="25" align="right">
  <img id='ipgback' src="/skin/red/images/buttons/or-back.gif" style="height:29px;padding:3px; +padding-top:5px; cursor:pointer;" onclick="javascript:location.href='{$req.history_url}';"/> 
    <img id='ipgback_gray' src="/skin/red/images/buttons/gray-back.gif" style="height:29px;padding:3px; +padding-top:5px; cursor:pointer; display:none;"/> 
  </td>
  <td align="left" valign="middle">
  <input id="mainSubmit" type="image" name="mainSubmit" src="/skin/red/images/buttons/or-submit.gif" class="input-none-border" style="border:none; float:left;" />
  <img src="/skin/red/images/buttons/gray-submit.gif"  style="float:left; display:none;" width="0" height="0" />
      <input id="amount" type="hidden" name="amount" value="{$req.ipgForm.amount}"/>
      {if $req.ipgForm.request_url} 
      <input id="ref_id" type="hidden" name="ref_id" value="{$req.ref_id}"/>
      <input id="orderType" type="hidden" name="orderType" value="{$req.orderType}"/>
      {/if}
  
  <td></tr>
<tr style="display:none;">
  <td height="49" colspan="2" align="left"><img src="/skin/red/images/icon-visa.gif" width="37" height="23" /> <img src="/skin/red/images/icon-mastercard.gif" width="37" height="23" /><!-- <img src="/skin/red/images/logo-amex.gif" width="37" height="23" />--></td>
  <td></tr>
</table>

</td>
  </tr>
</table></form>
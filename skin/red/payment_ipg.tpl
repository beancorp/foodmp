<link href="/skin/red/css/global.css" rel="stylesheet" type="text/css" />
<script language="javascript">
{literal}
//myset
xajax.callback.global.onRequest= function(){
	var obj = xajax.$('mainSubmit'); 
	obj.src='/skin/red/images/buttons/gray-submit.gif';
	obj. disabled="disabled";
	var bakobj = document.getElementById('ipgback');
	bakobj.src = "/skin/red/images/buttons/gray-back.gif";
	bakobj.disabled="disabled";
	xajax.$('ajaxmessage').innerHTML='Processing...'
};

xajax.callback.global.onComplete= function(){
	//xajax.$('ajaxmessage').innerHTML='';
}

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
  <td width="327" height="35" align="left"><strong>{$lang.payment.CCNumber}</strong> <em style="font-style:italic">{$lang.payment.note}</em></td>
  <td width="487" align="left"><INPUT name="cardNumber" type="text" class="inputB" id="cardNumber" value="{$req.ipgForm.cardNumber}" style="width:200px;" maxlength="19">
  / <input name="cvc2" type="text" id="cvc2"maxlength="6" class="inputB" style="width:50px;" />
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
  <img id='ipgback' src="/skin/red/images/buttons/or-back.gif" style="height:29px;padding:3px; +padding-top:5px; cursor:pointer;" onclick="javascript:location.href='{$req.history_url}';"/> </td>
  <td align="left" valign="middle">
  <input id="mainSubmit" type="image" name="mainSubmit" src="/skin/red/images/buttons/or-submit.gif" class="input-none-border" style="border:none; float:left;" />
  <img src="/skin/red/images/buttons/gray-submit.gif"  style="float:left;" width="0" height="0" />
      <input id="amount" type="hidden" name="amount" value="{$req.ipgForm.amount}"/>
  
  <td></tr>
<tr style="display:none;">
  <td height="49" colspan="2" align="left"><img src="/skin/red/images/icon-visa.gif" width="37" height="23" /> <img src="/skin/red/images/icon-mastercard.gif" width="37" height="23" /><!-- <img src="/skin/red/images/logo-amex.gif" width="37" height="23" />--></td>
  <td></tr>
</table>

</td>
  </tr>
</table></form>
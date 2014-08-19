<link href="/skin/red/css/global.css" rel="stylesheet" type="text/css" />
<script language="javascript">
{literal}
$(document).ready(function(){


	$("#mainSubmit").click(function(){
		
		
		if('' == $.trim($("#cardNumber").val())) {
			$("#ajaxmessage").css('color','red').html("Card Number is required.");
			return false;
		}
                    
                if('' == $.trim($("#cardName").val())) {
			$("#ajaxmessage").css('color','red').html("CardHolder's Name is required.");
			return false;
		}
                
		$(this).attr('src','/skin/red/images/buttons/gray-submit.gif');
		$(this).attr('disabled','disabled');
		$("#ipgback").hide();
		$("#ipgback_gray").show();
		$("#ajaxmessage").html('Processing...');
             //   $("#mainForm").submit();
                    
		var formData = $("#mainForm").serialize();
                    
                   $.ajax({
                        type: 'POST',
                        url: '{/literal}{$req.eway_info.ewayURL}{literal}',
                        data: formData,
                        success: function(response) {
                                  
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
                      });
		
                   
	});
	
});

{/literal}
</script>
<style>
{literal}
#payment_box {
	background-color: #EFEFEF;
    border-radius: 10px 10px 10px 10px;
    margin-bottom: 10px;
    padding: 10px;
}
{/literal}
</style>
<!-- Begin eWAY Linking Code -->
<div id="payment_box">
	
	<div id="ewayBlock">
		<a href="http://www.eway.com.au/secure-site-seal" title="Credit Card Processing">Credit Card Processing</a>
		<a href="http://www.eway.com.au/secure-site-seal "title="Payment Gateway">Payment Gateway</a>
		<a href="http://www.eway.com.au/secure-site-seal" title="E-Commerce">E-Commerce</a>
	</div>
	<script language="javascript">
		var element = "ewayBlock";
	</script>
	<script language="javascript" src="https://www.eway.com.au/developer/payment-code/verified-siteseal.ashx?image=12&size=3&format=horizontal&color=CECECE"></script>
	<!-- End eWAY Linking Code -->
	<br />

	<div id="ajaxmessage" class="text" style="text-align:center;color:red;" ></div>
	<form id="mainForm" name="mainForm" method="post" action="" onsubmit="return false"  >
	{foreach from=$req.eway_info key=k item=ewayitem}
		{if $k neq 'submit'}
		<input type="hidden" name="{$k}" value="{$ewayitem}"/>
		{/if}
	{/foreach}
	<table width="100%" height="269"  border="0" cellpadding="0" cellspacing="0">
	  <tr>
		<td height="160" align="center">

	<table width="89%" height="208">
	<tr>
	  <td height="35">&nbsp;</td>
	  <td align="left">
	  <td></tr>
	<tr>
	  <td width="327" height="35" align="left"><strong>CardHolder's Name</strong> <em style="font-style:italic"></em></td>
	  <td width="487" align="left"><INPUT autocomplete="off" name="cardName" type="text" class="inputB" id="cardName" value="{$req.cardName}" style="width:200px;" maxlength="50">
	  <td width="61"></tr>
	<tr>
	  <td width="327" height="35" align="left"><strong>{$lang.payment.CCNumber}</strong> <em style="font-style:italic">{$lang.payment.note}</em></td>
	  <td width="487" align="left"><INPUT autocomplete="off" name="cardNumber" type="text" class="inputB" id="cardNumber" value="{$req.cardNumber}" style="width:200px;" maxlength="19">
	  / <input name="cvc2" autocomplete="off" type="text" id="cvc2"maxlength="6" class="inputB" style="width:50px;" value="{$req.cvc2}" />
	  <td width="61"></tr>
	<tr>
	  <td width="327" height="35" align="left"><strong>{$lang.payment.expiry}</strong> <em style="font-style:italic">{$lang.payment.expiryFormat}</em></td>
	  <td align="left"><select name="expiryMonth" id="expiryMonth" class="inputB" style="width:50px;">{$req.expiryMonth}</select>
	  /    
		<select name="expiryYear" id="expiryYear" class="inputB" style="width:65px;">{$req.expiryYear}</select>
	  <td></tr>
	<tr>
	  <td width="327" height="35" align="left"><strong>{$lang.payment.amount}</strong></td>
	  <td align="left"><strong>{if $req.payment_type eq 1}$220.00{else}$401.50{/if}</strong> {$lang.CurCode} ({$lang.SalesTax} Included)<td>
	</tr>
	<tr>
	  <td width="327" height="35" align="left"><strong>{$lang.labelEmail}</strong> <em style="font-style:italic">{$lang.payment.forReceipt}</em></td>
	  <td align="left">{$req.ewayEmail}</td></tr>
	<tr>
	  <td width="327" height="25" align="right">
	  <img id='ipgback' src="/skin/red/images/buttons/or-back.gif" style="height:29px;padding:3px; +padding-top:5px; cursor:pointer;" onclick="javascript:location.href='{$req.history_url}';"/> </td>
	  <td align="left" valign="middle">
	  <input id="mainSubmit" type="image" name="mainSubmit" src="/skin/red/images/buttons/or-submit.gif" class="input-none-border" style="border:none; float:left;" />
	  <img src="/skin/red/images/buttons/gray-submit.gif"  style="float:left;" width="0" height="0" />
		  
	  
	  <td></tr>
	<tr style="display:none;">
	  <td height="49" colspan="2" align="left"><img src="/skin/red/images/icon-visa.gif" width="37" height="23" /> <img src="/skin/red/images/icon-mastercard.gif" width="37" height="23" /><!-- <img src="/skin/red/images/logo-amex.gif" width="37" height="23" />--></td>
	  <td></tr>
	</table>

	</td>
	  </tr>
	</table>
	<input type="hidden" name="payment_type" value="{$req.payment_type}" />
	</form>
</div>
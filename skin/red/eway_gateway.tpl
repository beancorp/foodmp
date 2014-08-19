<link href="/skin/red/css/global.css" rel="stylesheet" type="text/css">
<script language="javascript">
	{literal}
		$(document).ready(function(){
			$("#mainSubmit").click( function() {
				if ('' == $.trim($("#cardNumber").val())) {
					$("#ajaxmessage").css('color','red').html("Card Number is required.");
					return false;
				}
							
				if ('' == $.trim($("#cardName").val())) {
					$("#ajaxmessage").css('color','red').html("CardHolder's Name is required.");
					return false;
				}
						
				$(this).attr('src','/skin/red/images/buttons/gray-submit.gif');
				$(this).attr('disabled','disabled');
				$("#ipgback").hide();
				$("#ipgback_gray").show();
				$("#ajaxmessage").html('Processing...');

				var formData = $("#mainForm").serialize();
							
				$.ajax({
					type: 'POST',
					url: 'registration.php?step=activate',
					data: formData,
					success: function(response) {             
						response = eval('('+response+')');
						if ('false' == response.status) {
							$("#mainSubmit").attr('disabled',false);
							$("#ajaxmessage").css('color','red').html(response.msg);
							$("#mainSubmit").attr('src','/skin/red/images/buttons/or-submit.gif');
							$("#ipgback").show();
							$("#ipgback_gray").hide();
							return;
						} else {
							var fb_param = {};
							fb_param.pixel_id = '6011881611039';
							fb_param.value = '0.00';
							fb_param.currency = {/literal}'{$lang.CurCode}'{literal};
							(function(){
							  var fpw = document.createElement('script');
							  fpw.async = true;
							  fpw.src = '//connect.facebook.net/en_US/fp.js';
							  var ref = document.getElementsByTagName('script')[0];
							  ref.parentNode.insertBefore(fpw, ref);
							})();
							$.ajax ({
								url: "https://track.performtracking.com/aff_l?offer_id=497"
							});
							$.ajax ({
								url: "https://www.clixGalore.com/AdvTransaction.aspx?AdID=14969&OID=" + response.tn
							});
							$.ajax ({
								url: "{/literal}{$smarty.const.SOC_HTTP_HOST}{literal}payment_goal.html"
							}).done(function(html) {
								$("#ajaxmessage").css('color','red').html('Thank you for your successful payment');
								if (response.jumpPath) {
									location.href=response.jumpPath;
								}
							});
						}
					}
				});        
			});
		});
	{/literal}
</script>
<style>
{literal}
		.payment_box {
			background-color: #EFEFEF;
			border-radius: 10px 10px 10px 10px;
			margin-bottom: 10px;
			padding: 10px;
			overflow: hidden;
		}
		
		.payment_text {
			text-align: center;
			font-size: 14px;
			float: left;
			padding-top: 15px;
			margin-left: 150px;
		}
{/literal}
</style>

<div class="payment_box">
	{if $req.payment_type eq 1}
		
		<div class="payment_text">
			<font style="font-weight:normal;font-size:18px;">Drive traffic to your website now for  </font>
			<font style="font-size:26px;color:#F11F44">
			just
			<b style="font-size:26px;color:#F11F44;font-weight:bold;">$250</b>
			</font>
			<br />
			$0.68 cents x 365 days = $250 flat rate yearly subscription. <br />
			<font style="font-size:10px;">No {$lang.SalesTax} applicable.</font>
		</div>
	{else}
		
		<div class="payment_text">
			<font style="font-weight:normal;font-size:18px;">Open your website now for </font>
			<font style="font-size:26px;color:#F11F44">
			just
			<b style="font-size:26px;color:#F11F44;font-weight:bold;">$365</b>
			per year
			</font>
			<br />
			$1 x 365 days = $365 flat rate yearly subscription! <br />
			<font style="font-size:10px;">No {$lang.SalesTax} applicable.</font>
		</div>
	{/if}
</div>

<div class="payment_box">
	<!-- Begin eWAY Linking Code -->
	<div id="eWAYBlock">
		<div style="text-align:center;">
			<a href="http://www.eway.com.au/secure-site-seal?i=12&s=3&pid=324ea25e-8336-4eba-97cd-77b1f85f7b26" title="eWAY Payment Gateway" target="_blank">
				<img alt="eWAY Payment Gateway" src="https://www.eway.com.au/developer/payment-code/verified-seal.ashx?img=12&size=3&pid=324ea25e-8336-4eba-97cd-77b1f85f7b26" />
			</a>
		</div>
		<div style="text-align:center;">
			<a style="color: #CECECE;" target="_blank" href="http://www.eway.com.au/?pid=324ea25e-8336-4eba-97cd-77b1f85f7b26" title="eWAY Payment Gateway">eWAY Payment Gateway</a>
		</div>
	</div>
	<!-- End eWAY Linking Code -->
	<div class="text" id="ajaxmessage" style="text-align:center;color:red;"></div>
	
	<form action="" id="mainForm" method="post" name="mainForm" onsubmit="return false">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center" height="160">
					<table width="89%">
						<tr>
							<td height="35">&nbsp;</td>

							<td align="left"></td>
						</tr>

						<tr>
							<td align="left" height="35" width="327">
								<strong>CardHolder's Name</strong>
								<em style="font-style:italic"></em>
							</td>

							<td align="left" width="487">
								<input autocomplete="off" class="inputB" id="cardName" maxlength="50" name="cardName" style="width:200px;" type="text" value="{$req.cardName}">
							</td>
						</tr>

						<tr>
							<td align="left" height="35" width="327">
								<strong>{$lang.payment.CCNumber}</strong>
								<em style="font-style:italic">{$lang.payment.note}</em>
							</td>
							<td align="left" width="487">
								<input autocomplete="off" class="inputB" id="cardNumber" maxlength="19" name="cardNumber" style="width:200px;" type="text" value="{$req.cardNumber}"> 
								/ <input autocomplete="off" class="inputB" id="cvc2" maxlength="6" name="cvc2" style="width:50px;" type="text" value="{$req.cvc2}">
							</td>
						</tr>

						<tr>
							<td align="left" height="35" width="327">
								<strong>{$lang.payment.expiry}</strong>
								<em style="font-style:italic">{$lang.payment.expiryFormat}</em>
							</td>
							<td align="left">
								<select class="inputB" id="expiryMonth" name="expiryMonth" style="width:50px;">{$req.expiryMonth}</select> / <select class="inputB" id="expiryYear" name="expiryYear" style="width:65px;">{$req.expiryYear}</select>
							</td>
						</tr>

						<tr>
							<td align="left" height="35" width="327">
								<strong>{$lang.payment.amount}</strong>
							</td>
							<td align="left">
								<strong>{if $req.payment_type eq 1}$250.00{else}$365.00{/if} {$lang.CurCode} (No {$lang.SalesTax} applicable)</strong>
							</td>
						</tr>

						<tr>
							<td align="left" height="35" width="327">
								<strong>{$lang.labelEmail}</strong>
								<em style="font-style:italic">{$lang.payment.forReceipt}</em>
							</td>
							<td align="left">{$req.ewayEmail}</td>
						</tr>

						<tr>
							<td align="right" height="25" width="327"></td>
							<td align="left" valign="middle"><input class="input-none-border" id="mainSubmit" name="mainSubmit" src="/skin/red/images/buttons/or-submit.gif" style="border:none; float:left;" type="image"> <img height="0" src="/skin/red/images/buttons/gray-submit.gif" style="float:left;" width="0"></td>
						</tr>
						
						<tr style="display:none;">
							<td align="left" colspan="2" height="49"><img height="23" src="/skin/red/images/icon-visa.gif" width="37"> <img height="23" src="/skin/red/images/icon-mastercard.gif" width="37"><!-- <img src="/skin/red/images/logo-amex.gif" width="37" height="23" />--></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<input name="payment_type" type="hidden" value="{$req.payment_type}">
	</form>
</div>
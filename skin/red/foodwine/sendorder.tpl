{if $req.display eq ''}
<script language="javascript">
var stockquantity = "{$req.stockQuantity}";
var coupon={if $couponInfo.shipping_method}true{else}false{/if};
var coupon_postage="{$couponInfo.postage}";
var coupon_deliveryMethod="{$couponInfo.shipping_method}";
var coupon_overcountry="{$couponInfo.isoversea}";
var coupon_shipping="{$couponInfo.shipping}";
{literal}

function CheckForm(){
	//checkQuantity(false);
	
	var payment = 0;
	var to = '';
	var objbuyitem = document.buyitem;
	var length = objbuyitem.elements.length;
	
	for (var i=0; i<length; i++) { 
		if (objbuyitem.elements[i].name.indexOf("payment") != -1 && objbuyitem.elements[i].checked == true) { 
			payment = 1; 
			to = objbuyitem.elements[i].value;
		}
	}
	
	if (!objbuyitem.agree.checked){
		alert("You must agree to the terms.");
	} else if (payment!=1) {
		alert("Please select a payment method.");
	} else {
		if (to == 'paypal') {
			/*document.paypal.amount.value = eval(objbuyitem.price.value)*eval(objbuyitem.quantity.value)+eval(objbuyitem.postage.value);
			document.paypal.quantity.value = objbuyitem.quantity.value;
			document.paypal.price.value = objbuyitem.price.value;
			document.paypal.shipping.value = objbuyitem.shipping.value;
			document.paypal.postage.value = objbuyitem.postage.value;
			document.paypal.deliveryMethod.value= objbuyitem.deliveryMethod.value;*/
			if(coupon) {
				document.paypal.paypal_dest.value='';
				//document.paypal.quantity.value = objbuyitem.quantity.value;
				//document.paypal.price.value = objbuyitem.price.value;
				//document.paypal.shipping.value = objbuyitem.shipping.value;
				document.paypal.overcountry.value = coupon_overcountry;
				document.paypal.deliveryMethod.value= coupon_deliveryMethod;
			}
			else {
			}
			
			document.paypal.submit();
		} else if (to == 'google'){
			document.buyitem.action = '?act=payment&cp=googlecheckout';
			document.buyitem.submit();
			
		} else if (to == 'account'){
			document.buyitem.action = '?act=payment&cp=account';
			document.buyitem.submit();
		} else {
			objbuyitem.submit();
		}
	}
}

function FormatNumber(srcStr,nAfterDot){
	var srcStr,nAfterDot;
	var resultStr,nTen;
	srcStr = ""+srcStr+"";
	strLen = srcStr.length;
	dotPos = srcStr.indexOf(".",0);
	if (dotPos == -1){
		resultStr = srcStr+".";
		for (i=0;i<nAfterDot;i++){
			resultStr = resultStr+"0";
		}
		return resultStr;
	}else{
		if ((strLen - dotPos - 1) >= nAfterDot){
			nAfter = dotPos + nAfterDot + 1;
			nTen =1;
			for(j=0;j<nAfterDot;j++){
				nTen = nTen*10;
			}
			resultStr = Math.round(parseFloat(srcStr)*nTen)/nTen;
			return resultStr;
		}else{
			resultStr = srcStr;
			for (i=0;i<(nAfterDot - strLen + dotPos + 1);i++){
				resultStr = resultStr+"0";
			}
			return resultStr;
		}
	}
}

function checkQuantity(notClew) {
	//if instock quantity is dropped to zero
	if (!notClew && ($("#quantity").val()<1 || $("#quantity").val().replace(/^\+?[\d]{1,}/g, '') != '' ) ){
		alert("Please enter a valid quantity.");
		window.setTimeout( function(){ $("#quantity").focus(); }, 0);
		window.setTimeout( function(){ $("#quantity").select(); }, 0);
	}else {
		$.post("soc.php?act=signon&step=checkStockQuantity",{ quantity:$("#quantity").val(),pid:$("#pid").val() } ,function(intstockquantity)
		{
			stockquantity = intstockquantity;
			
			var ccQuantity	=	parseInt($("#quantity").val() * 1);
			
			$("#quantity").val(ccQuantity);
			var shippings = $('input[name="postage"]').val();
						
			var total = ($("#price").val()) * parseInt($("#quantity").val()) + (shippings*parseInt($("#quantity").val()));
			var totalvalue = '$'+ FormatNumber(total,2);
			$("#total").html(totalvalue);
			$("input[name='amount']").val(total);
			$('input[name="shipping"]').val(shippings *parseInt($("#quantity").val()));
			$('input[name="quantity"]').val(ccQuantity);
		});
	}
}
function showbt_tran(bt){
	if(bt==1){
		$('#bt_tran').css('display','');
	}else{
		try{
		$('#bt_tran').css('display','none');
		}catch(ex){
		}
	}
}
function changeShipping(obj,price,bl){
	if($(obj).attr('checked')){
		//$('#shipping').attr('innerHTML','$' +price);
		if(bl){	
			$('input[name="overcountry"]').val(1);	
			$("#hdn_paypal_overcountry").val(1);
		}else{
			$('input[name="overcountry"]').val(0);
			$("#hdn_paypal_overcountry").val(0);
		}
		$('input[name="deliveryMethod"]').val($(obj).val());
		$('input[name="shipping"]').val(price*parseInt($("#quantity").val()));
		$('input[name="postage"]').val(price);
		
		var total = FormatNumber($("#price").val(),2) + FormatNumber(price,2);
		var totalvalue = '$'+ FormatNumber(total,2);
		$("#total").html(totalvalue);
		//$("input[name='amount']").val(total);
		$("input[name='total_money']").val(total);
	}
	
}
function couponSubmit(){
	if($('#disconcode').val()==""){
		alert('The coupon code is required.');
		return 0;
	}
	$('#coupon_code').val($('#disconcode').val());
	$('#coupon_op').val('add');
	$('#coupon_code_form').submit();
}
function coupondelete(){
	if(confirm('Are you sure to clear the coupon code?')){
		$('#coupon_op').val('delete');
		$('#coupon_code_form').submit();
	}
	return false;
}
{/literal}
</script>
{literal}
<style type="text/css">
ul.shipping{margin:0;padding:0;list-style:none;}
ul.shipping li{padding-left: 10px; padding-top: 4px;}
</style>
{/literal}
        <form action="" method="POST" name="coupon_code_form" id="coupon_code_form">
        	<input type="hidden" name="coupon_code" id="coupon_code" value=""/>
        	<input type="hidden" name="coupon_op" id="coupon_op" value="add"/>
        </form>
        
		<form action="{$soc_https_host}foodwine/index.php?act=payment" style="margin:0;padding:0; float:none; padding:0 15px; width:auto" method="post" name="buyitem" id="buyitem" onsubmit="javascript:CheckForm(); return false;"> 
          		<input type="hidden" name="item_name" value="{$req.bu_name} Order Pay :{$req.OrderID}" />
				<input type="hidden" name="OrderID" value="{$req.OrderID}">
          		<input type="hidden" name="amount" value="{$req.product_money}" />
          		<input type="hidden" name="total_money" value="{$req.total_money}" />
				
          		<input type="hidden" name="quantity" id="quantity" value="{if $couponInfo.quantity}{$couponInfo.quantity}{else}1{/if}" />
				<input type="hidden" name="price" id="price" value="{$req.price}">
				<input type="hidden" name="pid" id="pid" value="{$req.pid}">
				<input type="hidden" name="StoreID" id="StoreID" value="{$req.StoreID}">
                
	            {if $req.isattachment eq '0'}
				{if $couponInfo.coupon_code}
					<h3 style="margin-top:10px;">{if $couponInfo.isoversea}Oversea{else}Destination{/if}</h3>
					{if $couponInfo.shipping_method eq '5'}<strong>{/if}{$lang.Delivery[$couponInfo.shipping_method].text}{if $couponInfo.shipping_method eq '5'}</strong>{/if}  
					<input type="hidden" name="postage" value="{$couponInfo.postage}"/>
					<input type="hidden" name="deliveryMethod" value="{$couponInfo.shipping_method}"/>
					<input type="hidden" name="overcountry"  value="{$couponInfo.isoversea}"/>
		            <input type="hidden" name="shipping" value="{$couponInfo.shipping}">
				{else}
				
				<h3 style="margin-top:10px;">Destination</h3>
		          <ul class="shipping">
					{foreach from=$req.deliveryMethod|explode:"|" item=opcl key=oplk}
	    				<li><input type="radio" name="dest" {if $oplk eq 0}checked{/if}  value="{$opcl}" onclick="changeShipping(this,'{if $req.postage}{foreach from=$req.postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2:'.':''}{/if}{/foreach}{else}{0|number_format:2:'.':''}{/if}',false)"/>
	    				&nbsp;{if $opcl eq '5'}<strong>{/if}{$lang.Delivery[$opcl].text}{if $opcl eq '5'}</strong>{/if} 
	    				{if $opcl eq '1' or $opcl eq '2' or $opcl eq '5' or $opcl eq '6'}(Fee:${foreach from=$req.postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}){/if}	    				
	    				</li>
	    			{/foreach}
	    			</ul>        
					<input type="hidden" name="overcountry" value="0"/>
					{if $req.isoversea && 0}
						<h3 style="margin-top:10px;">Overseas</h3>
						<ul class="shipping">
						{foreach from=$req.oversea_deliveryMethod|explode:"|" item=opcl key=oplk}
		    				<li><input type="radio" name="dest" value="{$opcl}" onclick="changeShipping(this,'{if $req.oversea_postage}{foreach from=$req.oversea_postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2:'.':''}{/if}{/foreach}{else}{0|number_format:2:'.':''}{/if}',true)"/>
		    				&nbsp;{if $opcl eq '5'}<strong>{/if}{$lang.Delivery[$opcl].text}{if $opcl eq '5'}</strong>{/if}&nbsp;{if $opcl eq '1' or $opcl eq '2' or $opcl eq '5' or $opcl eq '6'}(Fee:${foreach from=$req.oversea_postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}){/if}
		    				
		    				</li>
		    			{/foreach}
		    			</ul>
	    			{/if}
	    			
	    			<input type="hidden" name="deliveryMethod"  value="{foreach from=$req.deliveryMethod|explode:"|" item=costl key=costk}{if $costk eq 0}{$costl}{/if}{/foreach}">
	    			
		            <input type="hidden" name="postage" value="{foreach from=$req.postage|explode:"|" item=costl key=costk}{if $costk eq 0}{$costl|number_format:2:'.':''}{/if}{/foreach}" />
		            
		            <input type="hidden" name="shipping" value="{foreach from=$req.postage|explode:"|" item=costl key=costk}{if $costk eq 0}{$costl|number_format:2:'.':''}{/if}{/foreach}">
		            		            
	            {/if}
	            {/if}
				<h3>Payment Method</h3>
				{if $req.isattachment eq '0'}
				{if $req.credit_card == ''}
	            <input name="payment" type="radio" class="checkbox" value="visa" onclick="showbt_tran(0)" /><img src="/skin/red/images/icon-visa.gif" alt="" class="payment" /><input name="payment" type="radio" class="checkbox" value="mastercard" onclick="showbt_tran(0)" /><img src="/skin/red/images/icon-mastercard.gif" alt="" class="payment" /><input name="payment" type="radio" class="checkbox" value="discover" onclick="showbt_tran(0)" /><img src="/skin/red/images/icon-discover.gif" alt="" class="payment" /><br /><br />
	            {/if}
	            {if $req.paypal_enable != 'disabled'}
	            <input type="radio" name="payment" value="paypal" {$req.paypal_enable} onclick="showbt_tran(0)" checked="checked" /><img src="/skin/red/images/payments_ico.png" border="0" />
	            {/if} 
	            {if $req.googlecheckout != 'disabled'} 
	            <input type="radio" name="payment" value="google" onclick="showbt_tran(0)" /><img src="/skin/red/images/icon-googlecheckout.png" border="0" /><br><br> 
				{/if}
	            {if $req.check_payment != 'disabled'}
	            <input name="payment" type="radio" class="checkbox" value="check" onclick="showbt_tran(0)" /> <strong>{$lang.Payments.4.text}</strong>
	            {/if}
	            {if $req.cash_payment != 'disabled'}
	            <input name="payment" type="radio" class="checkbox" value="cash" onclick="showbt_tran(0)" /> <strong>{$lang.Payments.1.text}</strong>
	            {/if}
                
                {if $req.bank_transfer != 'disabled'}
	            <input name="payment" type="radio" class="checkbox" value="bank_transfer" onclick="showbt_tran(1)" /> <strong>{$lang.Payments.5.text}</strong>
	            {/if}
				
				{if $req.cod != 'disabled'}
					<input name="payment" type="radio" class="checkbox" value="cod" onclick="showbt_tran(0)" /> <strong>{$lang.Payments.9.text}</strong>
				{/if}
				
				{if $req.cash_on_pickup != 'disabled'}
					<input name="payment" type="radio" class="checkbox" value="cash_on_pickup" onclick="showbt_tran(0)" /> <strong>{$lang.Payments.10.text}</strong>
				{/if}
				
				{if $req.eftpos != 'disabled'}
					<input name="payment" type="radio" class="checkbox" value="eftpos" onclick="showbt_tran(0)" /> <strong>{$lang.Payments.11.text}</strong>
				{/if}
				
	            {if $req.other_payment != 'disabled'} 
	            <input name="payment" type="radio" class="checkbox" value="mo" onclick="showbt_tran(0)" /> <strong>{$lang.Payments.6.text}</strong>
	            {/if}
				{else}
				{if $req.paypal_enable != 'disabled'}
	            <input type="radio" name="payment" value="paypal" {$req.paypal_enable} onclick="showbt_tran(0)" /><img src="/skin/red/images/payments_ico.png" border="0" />
	            {/if} 
				{/if}
				
				{if $has_account} 
					<br /><br />
					<input type="radio" name="payment" value="account" checked="checked" onclick="showbt_tran(0)" /><strong style="color: #cd2040;">&nbsp;Offline Payment</strong>
				{/if}
                
	            {if $req.bank_transfer != 'disabled'}
			<div style="margin-top:10px; display:none;" id="bt_tran">
				<table cellpadding="0" cellspacing="2" width="100%">
				<tr><td style="color:#666666;font-size:12px;font-weight:bold;border:0; padding:0 0 5px 0; margin:0;" colspan="2">Bank Transfer</td></tr>
				<tr>
					<td style="border:0; margin:0; padding:0;color:#777777;width:160px;">Account Name:</td><td style="border:0; margin:0; padding:0;color:#777777;">{$req.btinfo.bt_account_name|escape:'html'}</td>
				</tr>
				<tr>
					<td style="border:0; margin:0; padding:0;color:#777777">BSB:</td><td style="border:0; margin:0; padding:0;color:#777777;">{$req.btinfo.bt_BSB}</td>
				</tr>
				<tr>
					<td style="border:0; margin:0; padding:0;color:#777777">Account Number:</td><td style="border:0; margin:0; padding:0;color:#777777;">{$req.btinfo.bt_account_num}</td>
				</tr>
				<tr>
					<td style="border:0; margin:0; padding:0;color:#777777">Bank Transfer Instructions:</td><td style="border:0; margin:0; padding:0;color:#777777;" valign="top">{$req.btinfo.bt_instruction|escape:'html'}</td>
				</tr>
				</table>
			</div>
			{/if}
	
				<p>Important: Please remember you are entering a legally binding contract to buy the item(s)<br />from the seller.{if $req.isattachment eq '1'}<br/><span style="color:#FF0000;font-size:10px;">The file can only be downloaded once and must be downloaded within 24 hours.</span>{/if}</p>
	            
	            <input name="agree" type="checkbox" class="checkbox" id="agree" value="yes" /> 
	            I agree
	
	
				<br /><br />
				<span class="submit">
						{if $req.isattachment eq '0'}
						<input type="image" name="imageField" src="/skin/red/images/bu-buynow.gif" />
						{else}
						<input type="image" name="imageField" src="/skin/red/images/buttons/attachment_buy.gif" />
						{/if}
	             </span>
				{if $couponInfo.shipping_method}
					<input type="hidden" name="dest" value="set"/>
				{/if}
        </form>
        <form action="?act=payment&cp=paypal" method="post" name="paypal" >     
          <input type="hidden" name="OrderID" value="{$req.OrderID}" />
		  <input type="hidden" name="StoreID" value="{$req.StoreID}" />
		  <!---shipping method--->
          <input type="hidden" name="shipping" value="{if $req.postage}{foreach from=$req.postage|explode:"|" item=costl key=costk}{if $costk eq 0}{$costl|number_format:2:'.':''}{/if}{/foreach}{else}{0|number_format:2:'.':''}{/if}" />
          <input type="hidden" name="deliveryMethod"  value="{foreach from=$req.deliveryMethod|explode:"|" item=costl key=costk}{if $costk eq 0}{$costl}{/if}{/foreach}" id="hdn_payay_delivaeryMethod">
          <input type="hidden" name="postage" value="{if $req.postage}{foreach from=$req.postage|explode:"|" item=costl key=costk}{if $costk eq 0}{$costl|number_format:2:'.':''}{/if}{/foreach}{else}{0|number_format:2:'.':''}{/if}" />
          <input type="hidden" name="overcountry" id="hdn_paypal_overcountry" value="0"/>
		  <input type="hidden" name="dest" id="paypal_dest" value=""/>
        </form>

{else}
<div class="publc_clew" style="padding-top:10%;">{$req.msg}</div>
{/if}
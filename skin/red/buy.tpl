{if $req.display eq ''}
<script language="javascript">
var stockquantity = {$req.stockQuantity};
var coupon={if $couponInfo.shipping_method}true{else}false{/if};
var coupon_postage="{$couponInfo.postage}";
var coupon_deliveryMethod="{$couponInfo.shipping_method}";
var coupon_overcountry="{$couponInfo.isoversea}";
var coupon_shipping="{$couponInfo.shipping}";
{literal}

function CheckForm(){
	checkQuantity(false);
	
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
	
	if($("#quantity").val()<1 || $("#quantity").val().replace(/^\+?[\d]{1,}/g, '') != ''  ){
		window.setTimeout( function(){ $("#quantity").focus(); }, 0);
		window.setTimeout( function(){ $("#quantity").select(); }, 0);
	}else if(parseInt($("#quantity").val()) > stockquantity ){
		alert("The quantity should not be larger than "+ stockquantity +". Please try again.");
	}else if (!objbuyitem.agree.checked){
		alert("You must agree to the terms.");
	}else if (payment!=1){
		alert("Please select a payment method.");
	}else{
		if (to == 'paypal'){
			document.paypal.amount.value = eval(objbuyitem.price.value)*eval(objbuyitem.quantity.value)+eval(objbuyitem.postage.value);
			document.paypal.quantity.value = objbuyitem.quantity.value;
			document.paypal.price.value = objbuyitem.price.value;
			document.paypal.shipping.value = objbuyitem.shipping.value;
			document.paypal.postage.value = objbuyitem.postage.value;
			document.paypal.deliveryMethod.value= objbuyitem.deliveryMethod.value;
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
			
			//alert(paypal.amount.value);
			document.paypal.submit();
		} else if(to == 'google'){
			if(coupon) {
				
			}
			
			document.buyitem.action = 'soc.php?cp=googlecheckout';
			document.buyitem.submit();
		}else{
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
		$('#shipping').attr('innerHTML','$' +price);
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
		
		var total = ($("#price").val()) * parseInt($("#quantity").val()) + (price*parseInt($("#quantity").val()));
		var totalvalue = '$'+ FormatNumber(total,2);
		$("#total").html(totalvalue);
		$("input[name='amount']").val(total);
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
<div style="float:left;">
			{if $req.image_name ne ''}
        	<img src="{$req.image_name}" width="241" alt="" class="bigimage" />
        	{else}
        	<img src="images/243x212.jpg" width="241" alt="" class="bigimage" />
        	{/if}
        	<br />
            <!--{if $req.is_auction == 'yes'}<a href="soc.php?cp=disauction&StoreID={$req.StoreID}&proid={$req.pid}" class="productarrow">{else}<a href="soc.php?cp=dispro&StoreID={$req.StoreID}&proid={$req.pid}" class="productarrow">{/if}{$req.item_name}</a>-->
</div>
        <form action="" method="POST" name="coupon_code_form" id="coupon_code_form">
        	<input type="hidden" name="coupon_code" id="coupon_code" value=""/>
        	<input type="hidden" name="coupon_op" id="coupon_op" value="add"/>
        </form>
        
			<form action="{$soc_https_host}soc.php?cp=payment" style="margin:0;padding:0;"  method="post" name="buyitem" id="buyitem" onsubmit="javascript:CheckForm(); return false;"> 
				<input type="hidden" name="item_name" id="item_name" value="{$req.item_name}">
				
                {if $req.isbid eq "1"}
				<input type="hidden" name="isbid" value="1">
                <input type="hidden" name="quantity" id="quantity" value="1" />
				<input type="hidden" name="refid" value="{$req.ref_id}">
                {/if}
				<input type="hidden" name="price" id="price" value="{$req.price}">
				<input type="hidden" name="pid" id="pid" value="{$req.pid}">
				<input type="hidden" name="StoreID" id="StoreID" value="{$req.StoreID}">
                
				<table cellpadding="0" cellspacing="0" width="100%">
	            <colgroup>
	            <col {if $req.isattachment eq '1'}width="35%"{else}width="25%"{/if} />
	            {if $req.isattachment eq '1'}{else}<col width="15%" />{/if}
	            <col {if $req.isattachment eq '1'}width="45%"{else}width="25%"{/if} />
	            <col width="20%" />
	            <col width="15%" />
	            </colgroup>
	            <thead>
	            <tr>
	            <th>Product Code</th>
	            <th {if $req.isattachment eq '1'}style="display:none"{/if}>Quantity</th>
	            <th>Price (per item)</th>
	            <th {if $req.isattachment eq '1'}style="display:none"{/if}>Shipping</th>
	            <th class="bolder">Total</th>
				</tr>
	            </thead>
	            <tbody>
	            <tr>
	            <td>{$req.p_code}&nbsp;<input type="hidden" name="product_code" value="{$req.p_code}" /></td>
	            <td {if $req.isattachment eq '1'}style="display:none"{/if}>{if $req.isbid eq "1"}1{else}<input name="quantity" type="text" class="text" id="quantity" onchange="checkQuantity();" {if $couponInfo.coupon_code}style="display:none"{/if} maxlength="12" value="{if $couponInfo.quantity}{$couponInfo.quantity}{else}1{/if}"/>{/if}{$couponInfo.quantity}</td>
	            <td>${$req.price|number_format:2}&nbsp;</td>
	            <td {if $req.isattachment eq '1'}style="display:none"{/if} id="shipping">
                    {if $req.postage}
                        ${foreach from=$req.postage|explode:"|" item=costl key=costk}{if $costk eq 0}{$costl|number_format:2}{/if}{/foreach}&nbsp;
                    {else}
                        ${0|number_format:2}
                    {/if}
                </td>
	            <td class="bolder" id="total">${$req.total|number_format:2}&nbsp;</td>
	            </tr>
	            </tbody>
	            </table>
	            {if $req.isbid ne "1"}
	            <div style="margin:10px 0 0 0;">
	            Enter your coupon code if you have one.<br/>
	            <input type="text" name="disconcode" id="disconcode" {if $couponInfo.coupon_code}disabled{/if} class="inputB" style="width:120px;float:left;" maxlength="10" value="{$couponInfo.coupon_code}" />{if $couponInfo.coupon_code}<a href="javascript:coupondelete();" style="margin:10px 0 0 10px;float:left;">Clear Coupon code</a>{else}<img src="{$smarty.const.IMAGES_URL}skin/red/coupon.gif" style="float:left;margin:1px 5px;cursor:pointer;" onclick="couponSubmit();"/>{/if} 
	            <div style="clear:both;font-size:0;padding:0;margin:0;height:0;"></div>
	            <div style="color:red;margin:5px;" id="coupon_msg">{$couponMsg}</div>
	            </div>
	            {/if}
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
                    {if $req.info.bu_delivery_text[$opcl] neq ''}
	    				<li><input type="radio" name="dest" {if $oplk eq 0}checked{/if}  value="{$opcl}" onclick="changeShipping(this,'{if $req.postage}{foreach from=$req.postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2:'.':''}{/if}{/foreach}{else}{0|number_format:2:'.':''}{/if}',false)"/>
	    				&nbsp;{if $opcl eq '5'}<strong>{/if}{$req.info.bu_delivery_text[$opcl]|escape:'html'}{if $opcl eq '5'}</strong>{/if} 
	    				(Fee:${if $req.postage}{foreach from=$req.postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}{else}0.00{/if})    				
	    				</li>
                    {/if}
	    			{/foreach}
	    			</ul>
					<input type="hidden" name="overcountry" value="0"/>
					{if $req.isoversea}
						<h3 style="margin-top:10px;">Overseas</h3>
						<ul class="shipping">
						{foreach from=$req.oversea_deliveryMethod|explode:"|" item=opcl key=oplk}
		    				<li><input type="radio" name="dest" value="{$opcl}" onclick="changeShipping(this,'{if $req.oversea_postage}{foreach from=$req.oversea_postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2:'.':''}{/if}{/foreach}{else}{0|number_format:2:'.':''}{/if}',true)"/>
		    				&nbsp;{if $opcl eq '5'}<strong>{/if}{$req.info.bu_delivery_text[$opcl]|escape:'html'}{if $opcl eq '5'}</strong>{/if}&nbsp;(Fee:${if $req.oversea_postage}{foreach from=$req.oversea_postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}{else}0.00{/if})
		    				
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
	            <input name="payment" type="radio" class="checkbox" value="visa" onclick="showbt_tran(0)" /><img src="{$smarty.const.IMAGES_URL}skin/red/icon-visa.gif" alt="" class="payment" /><input name="payment" type="radio" class="checkbox" value="mastercard" onclick="showbt_tran(0)" /><img src="{$smarty.const.IMAGES_URL}skin/red/icon-mastercard.gif" alt="" class="payment" /><input name="payment" type="radio" class="checkbox" value="discover" onclick="showbt_tran(0)" /><img src="{$smarty.const.IMAGES_URL}skin/red/icon-discover.gif" alt="" class="payment" /><br /><br />
	            {/if}
	            
				<style>
					{literal}
						#payment_type {
							width: 150px;
							background-color: #FFF;
							border-radius: 10px 10px 10px 10px;
							padding: 10px;
						}
						
						#payment_type label {
							float: right;
							clear: right;
							width: 100px;
							height: 50px;
							cursor: pointer;
						}
						
						#payment_type input {
							float: left;
							margin-left: 5px;
							margin-top: 10px;
						}
						
						#payment_type_column {
							float: left;
							overflow: hidden;
							margin-right: 10px;
						}
					{/literal}				
				</style>
				
				{if $free_signup}
					<fieldset id="payment_type">
					{foreach from=$payment_options item=option}
						<label for="payment_{$option.value}"><img src="{$option.image}" alt="{$option.name}" /></label><input id="payment_{$option.value}" type="radio" name="payment" value="{$option.value}" />
					{/foreach}
					</fieldset>
				{else}
					{if $req.paypal_enable != 'disabled'}
						<input type="radio" name="payment" value="paypal" {$req.paypal_enable} onclick="showbt_tran(0)"  checked="checked" /><img src="{$smarty.const.IMAGES_URL}skin/red/payments_ico.png" border="0" />
					{else}
						No available payment method in this store. <br/>
						Please contact the seller for futher information.
					{/if}
				{/if}	



				
	            {if $req.googlecheckout != 'disabled'} 
	            <input type="radio" name="payment" value="google" onclick="showbt_tran(0)"/><img src="{$smarty.const.IMAGES_URL}skin/red/icon-googlecheckout.png" border="0" /><br><br> 
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
	            <input type="radio" name="payment" value="paypal" {$req.paypal_enable} onclick="showbt_tran(0)" checked="checked"/><img src="{$smarty.const.IMAGES_URL}skin/red/payments_ico.png" border="0" />
	            {/if} 
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
					{if $req.stockQuantity eq 0}
					<img src="{$smarty.const.IMAGES_URL}skin/red/sold_icon.png" border="0" />
					{else}
						{if $req.isattachment eq '0'}
						<input type="image" name="imageField" src="{$smarty.const.IMAGES_URL}skin/red/bu-buynow.gif" />
						{else}
						<input type="image" name="imageField" src="{$smarty.const.IMAGES_URL}skin/red/buttons/attachment_buy.gif" />
						{/if}
	            	{/if}            </span>
	            {if $req.isbid ne "1"}
	            {if !($smarty.session.attribute eq 3 && $smarty.session.subAttrib eq 3)}
	                {if $smarty.session.attribute neq "" && $smarty.session.attribute neq 4}
	            	 <a href="javascript:checkaddtoWishlist('{$req.pid}','{$smarty.session.ShopID}','{$req.item_name|replace:"'":"\'"}');"><img src="{$smarty.const.IMAGES_URL}skin/red/add-to-wishlist.gif" style="margin:10px 0 0 0;"/></a>
	            	{include file='wishlist/wishlist_link.tpl'}
	            	{/if}
	            {/if}
	            {/if}
				{if $couponInfo.shipping_method}
					<input type="hidden" name="dest" value="set"/>
				{/if}
        </form>
        <form action="soc.php?cp=paypal" method="post"  name="paypal" >
          <input type="hidden" name="cmd" value="_xclick" />
          <input type="hidden" name="price" value="{$req.price}" />
            {if $req.isbid eq "1"}
            <input type="hidden" name="isbid" value="1">
            <input type="hidden" name="quantity" id="quantity" value="1" />
            <input type="hidden" name="refid" value="{$req.ref_id}">
            {else} 
         	<input type="hidden" name="quantity" id="quantity" value="{if $couponInfo.quantity}{$couponInfo.quantity}{else}1{/if}" />
          {/if}
          <input type="hidden" name="pid" value="{$req.pid}" />
          <input type="hidden" name="business" value="{$req.paypal}" />
          <input type="hidden" name="item_name" value="{$req.item_name}" />
          <input type="hidden" name="amount" value="" />
          <input type="hidden" name="currency_code" value="USD" />
          <input type="hidden" name="item_number" value="{$req.pid}" />
          <input type="hidden" name="StoreID" value="{$req.StoreID}" />
          <input type="hidden" name="return" value="{$securt_url}product_activate.php" />
          <input type="hidden" name="cancel_return" value="{$securt_url}product_activate.php" />
          <input type="hidden" name="notify_url" value="{$securt_url}product_activate.php" />
          
          <!---shipping method--->
           <input type="hidden" name="shipping" value="{if $req.postage}{foreach from=$req.postage|explode:"|" item=costl key=costk}{if $costk eq 0}{$costl|number_format:2:'.':''}{/if}{/foreach}{else}{0|number_format:2:'.':''}{/if}" />
           
          <input type="hidden" name="deliveryMethod"  value="{$couponInfo.shipping_method}" id="hdn_payay_delivaeryMethod">
          <input type="hidden" name="postage" value="{if $req.postage}{foreach from=$req.postage|explode:"|" item=costl key=costk}{if $costk eq 0}{$costl|number_format:2:'.':''}{/if}{/foreach}{else}{0|number_format:2:'.':''}{/if}" />
          <input type="hidden" name="overcountry" id="hdn_paypal_overcountry" value="0"/>
		         <input type="hidden" name="dest" id="paypal_dest" value=""/>
        </form>

{else}
<div class="publc_clew" style="padding-top:10%;">{$req.msg}</div>
{/if}
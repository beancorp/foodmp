<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$pageTitle}</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="{$keywords}" />
<meta name="Description" content="{$description}" />
<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<script src="/skin/red/js/jquery-1.js" type="text/javascript"></script>
<!--[if lt IE 7]><script defer type="text/javascript" src="skin/red/js/pngfix.js"></script><![endif]-->
<!--[if IE 7]><link type="text/css" href="skin/red/css/ie.css" rel="stylesheet" media="screen" /><![endif]-->
<!--[if lt IE 7]><link type="text/css" href="skin/red/css/ie6.css" rel="stylesheet" media="screen" /><![endif]-->
<script language="javascript">
{literal}
function checkQuantity(isChange) {

	var obj = document.getElementById('mainForm');
	
	var intQuantity = parseInt(obj.quantity.value);
	var floatOffer  = FormatNumber(parseFloat(obj.offer.value),2);
	
	if(isNaN(intQuantity) || intQuantity<1){
		intQuantity	= 1;
	}
	
	if(isNaN(floatOffer) || floatOffer<=0){
{/literal}
		floatOffer	= {$req.offer};
{literal}
	}
	if (isChange){
		obj.quantity.value = intQuantity;
		obj.offer.value = floatOffer;
	}
	var postval = $('input[name="postage"]').val();
	if(!postval){
        postval = 0;
    }
	var totalvalue = '$'+ FormatNumber(floatOffer * intQuantity + parseFloat(postval)*intQuantity,2);
	document.getElementById("totalDiv").innerHTML = totalvalue;
	
	return false;
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
function changeShipping(obj,price,bl){
	$('input[name="deliveryMethod"]').val($(obj).val());
	$('input[name="postage"]').val(price);
	if(bl){$('input[name="isoversea"]').val(1);}else{$('input[name="isoversea"]').val(0);}
	checkQuantity(true);
}
{/literal}
</script>
{literal}
<style type="text/css">
ul.shipping{margin:0;padding:0;list-style:none;width:250px;}
ul.shipping li{padding-left: 10px; padding-top: 4px;}
ul.shipping li input{border:0 none;color:#777777;}
</style>
{/literal}
</head>

<body>
<div align="center">
	<div id="oboForm_top"></div>
	<div id="oboForm" align="left" style="margin-top:0;">
	{if $req.LOGIN neq 'login'}
		<ul id="input-table">
		<li class="publc_clew" style="padding-top:23%;">Please login before proceeding with offer.</li>
		</ul>
	{elseif $req.display eq ''}
		<form action="" method="post" name="mainForm" id="mainForm" onsubmit="javascript: checkQuantity(true);">
		<ul id="input-table" style="padding:20px 15px;">
			
			<li><samp>{$lang.obo.buyerNickname} :</samp><span>{$req.buyerNickname} </span></li>
			
			<li><samp>{$lang.obo.productCode} :</samp><span>{$req.p_code} </span></li>
			
			<li><samp>{$lang.obo.productName} :</samp><span>{$req.item_name} </span></li>
			
			<li style="height:auto;"><samp>Destination:</samp><span style="float:left;">
	          	<ul class="shipping">
				{foreach from=$req.deliveryMethod|explode:"|" item=opcl key=oplk}
                {if $req.info.bu_delivery_text[$opcl] neq ''}
					<li><input type="radio" name="dest" {if $oplk eq 0}checked{/if}  value="{$opcl}" onclick="changeShipping(this,'{foreach from=$req.postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2:'.':''}{/if}{/foreach}',false)"/>
					&nbsp;{if $opcl eq '5'}<strong>{/if}{$req.info.bu_delivery_text[$opcl]|escape:'html'}{if $opcl eq '5'}</strong>{/if} 
					(Fee:${if $req.postage}{foreach from=$req.postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}{else}0.00{/if})   				
					</li>
                {/if}
				{/foreach}
				</ul>
			</span>
			<div class="clear"></div>
			</li>
			{if $req.isoversea}
			<li style="height:auto;">
				<samp>Oversea:</samp>
				<span style="float:left;">
				<ul class="shipping">
				{foreach from=$req.oversea_deliveryMethod|explode:"|" item=opcl key=oplk}
                {if $req.info.bu_delivery_text[$opcl] neq ''}
    				<li><input type="radio" name="dest" value="{$opcl}" onclick="changeShipping(this,'{foreach from=$req.oversea_postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2:'.':''}{/if}{/foreach}',true)"/>
    				&nbsp;{if $opcl eq '5'}<strong>{/if}{$req.info.bu_delivery_text[$opcl]|escape:'html'}{if $opcl eq '5'}</strong>{/if}&nbsp;&nbsp;
    				(Fee:${if $req.oversea_postage}{foreach from=$req.oversea_postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}{else}0.00{/if})
    				
    				</li>
                {/if}
    			{/foreach}
    			</ul>
    			<div class="clear"></div>
    			</span>
    			<div class="clear"></div>
    		</li>
			{/if}
			<li><samp style=" padding-top:4px;">{$lang.obo.quantity} * :</samp><span><input name="quantity" type="text" id="quantity" value="{$req.quantity}" size="6" maxlength="4" onChange="checkQuantity(true);" onkeyup="checkQuantity();" /></span></li>
			
			<li><samp style="font-weight:bold; color:red; padding-top:4px;float:left;">{$lang.obo.ttOffer } * :</samp><span style="float:left;"><input name="offer" type="text" id="offer" value="{$req.price}" size="12" maxlength="12" onChange="checkQuantity(true);" onkeyup="checkQuantity();" style="color:red; border:solid 2px red; height:14px;float:left;"/></span>
			<div style="width:220px;color:#000000;padding-left:10px;float:left;">* In the offer field, enter decimal point only. E.g. $12000.00</div></li>
			
			<li><samp>{$lang.obo.total} :</samp><span id="totalDiv">
            {if $req.postage}
                {foreach from=$req.postage|explode:"|" item=costl key=costk}
                    {if $costk eq 0}
                        {math equation = "x + y" x=$req.price y=$costl format="$%.2f"}
                    {/if}
                {/foreach}
            {else}
                {$req.price|string_format:"$%.2f"}
            {/if}
			</span></li>
			
			<li><samp>&nbsp;</samp><span>
			<input type="hidden" name="deliveryMethod"  value="{foreach from=$req.deliveryMethod|explode:"|" item=costl key=costk}{if $costk eq 0}{$costl}{/if}{/foreach}">
		    <input type="hidden" name="postage" value="{foreach from=$req.postage|explode:"|" item=costl key=costk}{if $costk eq 0}{$costl|number_format:2:'.':''}{/if}{/foreach}" />
			<input type="hidden" name="isoversea" value="0" />
			
			
			<input name="StoreID" type="hidden" value="{$req.StoreID}" />
			<input name="pid" type="hidden" value="{$req.pid}" />
			<input name="p_code" type="hidden" value="{$req.p_code}" />
			<input name="item_name" type="hidden" value="{$req.item_name}" />
			<input name="submit" type="image" style="border:none" src="/skin/red/images/buttons/or-submit.gif" value="{$lang.but.submit}"/>
			</span></li>
		</ul>
		</form>
	{else}
		<ul id="input-table">
		<li class="publc_clew" style="padding-top:23%;">{$req.msg}</li>
		<li class="publc_clew"><a href="javascript:window.close();void(0);" style="font-style:italic;">Close Window</a></li>
		</ul>
	{/if}
	</div>
	<div id="oboForm_bottom"></div>
</div>
</body>
</html>
<link type="text/css" href="/skin/red/css/foodwine.css" rel="stylesheet"/>
{literal}
<script type="text/javascript">
	$(document).ready(function() {
		setTimeout(function(){$("#msg_show").hide();},5000);
	})
	
	function checkForm(cp)
	{
		var form = document.getElementById("basketform");
		form.cp.value = cp;
		form.submit();
		
		return true;
	}

	function doDelete(pid)
	{
		if(window.confirm("Are you sure you want to delete this item?")) {
			location = '/foodwine/?act=basket&cp=delete&pid=' + pid;
		}
	}
</script>
{/literal}
<a style=" visibility:hidden;" name="top"></a>
<form id="basketform" name="basketform" action="/foodwine/?act=basket" method="post">
<p align="center" class="txt" id="msg_show"><font style="color:red;">{$req.msg}</font></p>
<div class="stock_items_list">
	<table style="width:100%;border-collapse:collapse;">
    	<thead>
        	<th width="45%">Item</th>
            <th width="18%">Quantity</th>
            <th width="18%">Price</th>
            <th width="18%">Sub Total</th>
            <th width="10%">&nbsp;</th>
        </thead>
        {if count($products) > 0}
            {foreach from=$products item=p key=k}
            <tr>
                <td>
            	{if $p.images.big.smallPicture}
                <a title="{$p.item_name}" href="/{$p.store_info.url_bu_name}/{$p.url_item_name}"><img style="float:left;" src="{if $p.images.big.smallPicture}{$p.images.big.smallPicture}{else}/images/80x58.jpg{/if}" width="80" height="58" alt="{$p.name}" title="{$p.name}"/></a>
                {/if}
                <a style="text-decoration:none" title="{$p.item_name}" href="/{$p.store_info.url_bu_name}/{$p.url_item_name}"><span class="p-name">{$p.item_name}</span></a></td>
                <td align="center"><input type="text" onkeyup="return quantityChange('{$p.pid}');" class="quantity text" maxlength="5" name="quantity[{$p.pid}]" value="{$p.quantity}" /></td>
                <td>
                    {if $p.priceorder eq 1}
                		{$p.unit} ${$p.price}
                    {else}
                        ${$p.price} {$p.unit}
                    {/if}
                </td>
                <td>${$p.amount}</td>
                <td><a href="javascript:void(0);" onclick="return doDelete('{$p.pid}')" class="del-item">Delete</a></td>
            </tr>
            {/foreach}
        {else}
        	<tr><td colspan="5" align="center">No Products</td></tr>
        {/if}
    </table>
</div>
<div class="clear"></div>
<input type="hidden" name="cp" value="save" />
<input type="hidden" name="StoreID" value="{$req.info.StoreID}" />
<div class="basket-save"><a href="javascript:void(0);" onclick="return checkForm('save');">Save Changes</a><span>Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ${$total_money}</span></div>
{if $req.info.suburb_delivery}
<div style="width:100%">
<strong style="float:left; width:140px;">Suburbs we deliver to:</strong><div style="float:left; width:565px; padding-bottom:20px">{$req.info.suburb_delivery}</div>
</div>
{/if}
<div class="basket-send-order">
<a href="javascript:void(0);" onclick="return checkForm('sendorder');"><img src="/skin/red/images/foodwine/basket_send_order.png" /></a></div>
<div class="clear"></div>
</form>
<div class="stock_items_bottom">
	<a href="#top" class="back_to_top" title="Back to top">Back to top</a>
</div>
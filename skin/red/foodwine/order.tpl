<link type="text/css" href="/skin/red/css/foodwine.css" rel="stylesheet"/>
<script type="text/javascript">
var cp = "{$req.cp}";
var page = "{$req.page}";
{literal}
	function mouseEventLI(obj, flag)
	{
		if(flag) {
			$(obj).addClass("select");
		} else {
			$(obj).removeClass("select");
		}
		
		return true;
	}
	
	function changeOrder(orderid, key)
	{
		key = key > 0 ? key : 1;
		url = '/foodwine/?act=order';
		if(cp) {
			url += '&cp=' + cp;
		}
		if(page) {
			url += '&page=' + page;		
		}
		if(orderid) {
			location.href = url + '&OrderID=' + orderid + '#order' + (key - 1);
		}
	}
{/literal}
</script>
{include file="../seller_home_rightmenu.tpl"}
<form id="orderform" name="orderform" action="/foodwine/?act=order" method="post">
{if $req.cp eq ''}
<h1 class="soc-orderonline">Your Online Orders</h1>
<div class="view-passorder"><a href="/foodwine/?act=order&cp=viewpastorder" style="font-size:12px; line-height:9px; text-decoration:none">View Past Online Orders</a></div>
{else}
<h1 class="soc-orderonline">Your Past Online Orders</h1>
<div class="view-passorder"><a href="/foodwine/?act=order" style="font-size:12px; line-height:9px; text-decoration:none">View New Online Orders</a></div>
{/if}
<div class="clear"></div>
<div style="border-bottom:1px solid #CCCCCC; margin:0 0 10px;"></div>
<div class="orderonline_items_list" style="min-height:460px;">
	<ul>
    {foreach from=$req.order_lists.items item=order key=k}
        <a name="order{$k}"></a>
    	{if $req.OrderID eq $order.OrderID}    	<li class="current">
            <div class="buyer-info">
            <table style="width:100%; " class="table-info">
                <tr class="first">
                    <td width="12%"><img src="/skin/red/images/foodwine/order-current.png">&nbsp;&nbsp;{$order.ordersn}</td>
                    <td width="33%"><strong class="buyer-name-storng">{$order.bu_name}</strong></td>
                    <td width="13%">{$order.order_date|date_format:"%d/%m/%Y %I:%M %p"}</td>
                    <td width="52%">{if $order.seller_reviewed eq '0'}<img src="/skin/red/images/foodwine/order-new.png">{/if}</td>
                </tr>
                <tr>
                    <td width="12%"></td>
                    <td width="33%">{$order.bu_address}, {$order.bu_suburb} {$order.bu_state} {$order.bu_postcode}</td>
                    <td width="13%"></td>
                    <td width="52%"></td>
                </tr>
                <tr>
                    <td width="12%"></td>
                    <td width="33%">{if $order.bu_phone}<strong>P</strong> {$order.bu_phone} {/if}{if $order.mobile}<strong>M</strong> {$order.mobile}{/if}</td>
                    <td width="13%"></td>
                    <td width="52%"></td>
                </tr>
                <tr>
                    <td width="12%"></td>
                    <td width="33%"><strong>Delivery option:</strong> {$order.shipping_method}</td>
                    <td width="13%"></td>
                    <td width="52%"></td>
                </tr>
                <tr>
                    <td width="12%"></td>
                    <td width="33%"><strong>Delivery Charge:</strong> ${$order.shipping_cost}</td>
                    <td width="13%"></td>
                    <td width="52%"></td>
                </tr>
            </table>
            </div>
            <table style="width:100%; border-collapse:collapse" class="table">
                <thead>
                    <th width="45%">Item</th>
                    <th width="15%">Quantity</th>
                    <th width="22%">Price</th>
                    <th width="18%">Sub Total</th>
                </thead>
                {if count($order.product_lists) > 0}
                    {foreach from=$order.product_lists item=p key=k}
                    <tr>
                        <td><img style="float:left;" src="{if $p.big_image}{$p.big_image}{else}/images/80x58.jpg{/if}" width="60" height="35" alt="{$p.name}" title="{$p.name}"/><span class="p-name">{$p.item_name}</span></td>
                        <td>&nbsp;&nbsp;{$p.quantity}</td>
                        <td>
                        {if $p.priceorder eq 1}
                		{$p.unit} ${$p.price}
                        {else}
                            ${$p.price} {$p.unit}
                        {/if}
                    	</td>
                        <td>${$p.amount}</td>
                    </tr>
                    {/foreach}
                {else}
                    <tr><td colspan="5" align="center">No Products</td></tr>
                {/if}
            </table>
            <div class="clear"></div>
            <div class="basket-save"><span style="padding-left:562px;">Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ${$order.total_money}</span></div>
            {if $order.seller_reviewed eq '0'}
            <div class="order-done"><input type="image" src="/skin/red/images/foodwine/order-done.jpg" /></div>
            {/if}
            <div class="clear"></div>
    	</li>
        {else}
    	<li onmouseover="mouseEventLI(this, true);" onmouseout="mouseEventLI(this, false);" onclick="changeOrder('{$order.OrderID}', '{$k}');">
            <span class="img"><img src="/skin/red/images/foodwine/basket_save.png" /></span><span class="orderid">{$order.ordersn}</span><span class="buyer-name">{$order.bu_name}</span><span class="order-date">{$order.order_date|date_format:"%d/%m/%Y %I:%M %p"}</span>{if $order.seller_reviewed eq '0'}<span><img src="/skin/red/images/foodwine/order-new.png"></span>{/if}
    <div class="clear"></div>
    	</li>
    	{/if}
    {/foreach}
    </ul>
    
</div>
<div class="clear"></div>
<div>&nbsp;{$req.order_lists.linkStr}</div>
<input type="hidden" name="cp" value="orderdone" />
<input type="hidden" name="return_cp" value="{$req.cp}" />
<input type="hidden" name="OrderID" value="{$req.OrderID}" />
<input type="hidden" name="StoreID" value="{$req.StoreID}" />
</form>
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
	
	function showEmailalerts(eid)
	{
		if(eid) {
			location.href = '/foodwine/?act=emailalerts' + '&eid=' + eid;
		}
	}
{/literal}
</script>
{include file="../seller_home_rightmenu.tpl"}
<form id="orderform" name="orderform" action="/foodwine/?act=order" method="post">
<h1 class="soc-orderonline">Your Past Email Alerts</h1>
<div class="view-passorder"><a href="/foodwine/?act=emailalerts" style="font-size:12px; line-height:9px; text-decoration:none">Add New Email Alerts</a></div>
<div class="view-passorder" style="margin-right:20px;"><a href="/foodwine/?act=emailalerts&cp=viewsubscribers" style="font-size:12px; line-height:9px; text-decoration:none">View Subscribers</a></div>
<div class="clear"></div>
<div class="clear"></div>
<div style="border-bottom:1px solid #CCCCCC; margin:0 0 10px;"></div>
<div class="orderonline_items_list" style="min-height:460px;">
	<ul>
    <li style="background-color:#CCCCCC; height:15px; padding:7px 0;">
            <span class="img">&nbsp;</span><span class="buyer-name" style="width:130px;">Created Date</span><span class="buyer-name" style="width:130px;">Modify Date</span><span class="order-date" style="width:160px;">Active Date</span><span class="buyer-name" style="width:130px;">Sent Date</span>
    <div class="clear"></div>
    	</li>
    {if $emailalerts}
    {foreach from=$emailalerts item=l key=k}
        <a name="order{$k}"></a>
    	<li onclick="showEmailalerts('{$l.id}')" onmouseover="mouseEventLI(this, true);" onmouseout="mouseEventLI(this, false);">
            <span class="img"><img src="/skin/red/images/foodwine/basket_save.png" /></span><span class="buyer-name" style="width:130px;">{$l.datec|date_format:"%d.%m.%Y %H:%M"}</span><span class="buyer-name" style="width:130px;">{$l.datem|date_format:"%d.%m.%Y %H:%M"}</span><span class="order-date" style="width:160px;">{$l.start_date|date_format:"%d.%m.%Y"} - {$l.end_date|date_format:"%d.%m.%Y"}</span>{if $l.send_date}<span class="buyer-name" style="width:130px;">{$l.send_date|date_format:"%d.%m.%Y %H:%M"}</span>{/if}
    <div class="clear"></div>
    	</li>
    {/foreach}
    {else}
    	<li style="text-align:center; border:none">No Records</li>
    {/if}
    </ul>
    
</div>
<div class="clear"></div>
<div>&nbsp;{$req.order_lists.linkStr}</div>
<input type="hidden" name="cp" value="orderdone" />
<input type="hidden" name="return_cp" value="{$req.cp}" />
<input type="hidden" name="OrderID" value="{$req.OrderID}" />
<input type="hidden" name="StoreID" value="{$req.StoreID}" />
</form>
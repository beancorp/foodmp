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
	
	function popcontactwin(StoreID) {
		place = typeof(place) == 'undefined' ? '' : place;
		pid = typeof(pid) == 'undefined' ? '' : pid;
		window.open("/email_subscriber.php?StoreID="+StoreID, "emailsubscriber","width=600,height=460,scrollbars=yes,status=yes");
	}		function popcontactwinguest(StoreID,GuestID) {		place = typeof(place) == 'undefined' ? '' : place;		pid = typeof(pid) == 'undefined' ? '' : pid;		window.open("/email_subscriber.php?StoreID="+StoreID+"&GuestID="+GuestID, "emailsubscriber","width=600,height=460,scrollbars=yes,status=yes");	}
{/literal}
</script>
{include file="../seller_home_rightmenu.tpl"}
<form id="orderform" name="orderform" action="/foodwine/?act=order" method="post">
<h1 class="soc-orderonline">Your Subscribers</h1>
<div class="view-passorder"><a href="/foodwine/?act=emailalerts" style="font-size:12px; line-height:9px; text-decoration:none">Add New Email Alerts</a></div>
<div class="view-passorder" style="margin-right:20px;"><a href="/foodwine/?act=emailalerts&cp=list" style="font-size:12px; line-height:9px; text-decoration:none">View Past Email Alerts</a></div>
<div class="clear"></div>
<div style="border-bottom:1px solid #CCCCCC; margin:0 0 10px;"></div>
<div class="orderonline_items_list" style="min-height:460px;">
	<h2 style="color:#777777;">To contact an individual subscriber by email, click on their nickname.</h2>
	<ul>
    <li style="background-color:#CCCCCC; height:15px; padding:7px 0; cursor:default">
            <span class="img">&nbsp;</span><span class="buyer-name" style="width:200px;">Nickname</span><span class="order-date" style="width:200px;">Date Subscribed</span>
    <div class="clear"></div>
    	</li>
    {if $subscribers}    {foreach from=$subscribers item=subscriber key=k}        <a name="order{$k}"></a>    	<li style="cursor:default">            <span class="img"><img src="/skin/red/images/foodwine/basket_save.png" /></span><span class="order-date" style="width:200px"><a style="text-decoration:none;" href="javascript:void(0);" onclick="popcontactwin('{$subscriber.StoreID}');">{$subscriber.bu_nickname}</a></span><span style="width:200px;" class="order-date">{if $subscriber.subscribe_date}{$subscriber.subscribe_date|date_format:"%d.%m.%Y %H:%M"}{else}Before {$subscriber.before_date|date_format:"%d.%m.%Y"}{/if}</span>    <div class="clear"></div>    	</li>    {/foreach}	{/if}    	        {if $subscribersGuest}		 {foreach from=$subscribersGuest item=subscriberGuest key=k}                <a name="order{$k}"></a>                <li style="cursor:default">                    <span class="img"><img src="/skin/red/images/foodwine/basket_save.png" /></span><span class="order-date" style="width:200px"><a style="text-decoration:none;" href="javascript:void(0);" onclick="popcontactwinguest('{$subscriberGuest.store_id}','{$subscriberGuest.id}');">{$subscriberGuest.nickname}</a></span><span style="width:200px;" class="order-date">{if $subscriberGuest.subscribe_date}{$subscriberGuest.subscribe_date|date_format:"%d.%m.%Y %H:%M"}{else}Before {$subscriberGuest.subscribe_date|date_format:"%d.%m.%Y %H:%M"}{/if}</span>                      <div class="clear"></div>                </li>            {/foreach}         		 {/if}         		 {if empty($subscribers) && empty($subscribersGuest) }             <li style="text-align:center; border:none">No Records</li>         {/if}       	    
    
	{if $fan_result}
		{foreach from=$fan_result item=subscriber key=k}
				<li style="cursor:default">
					<span class="img"><img src="/skin/red/images/foodwine/basket_save.png" /></span>
					<span class="order-date" style="width:200px"><a style="text-decoration:none;" href="javascript:void(0);" onclick="popcontactwin('{$subscriber.StoreID}');">{$subscriber.bu_nickname}</a></span><span style="width:200px;" class="order-date">{if $subscriber.subscribe_date}{$subscriber.subscribe_date|date_format:"%d.%m.%Y %H:%M"}{else}Before {$subscriber.before_date|date_format:"%d.%m.%Y"} {/if}</span>
					<div class="clear"></div>
				</li>
		{/foreach}
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
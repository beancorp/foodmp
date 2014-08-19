<link type="text/css" href="/skin/red/css/foodwine.css" rel="stylesheet"/>
<script type="text/javascript">
var cp = "{$req.cp}";
var page = "{$req.page}";
var onDelete = false;
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
	
	function changeOrder(id, key)
	{
		if(!onDelete) {
			key = key > 0 ? key : 1;
			url = '/foodwine/?act=book';
			if(cp) {
				url += '&cp=' + cp;
			}
			if(page) {
				url += '&page=' + page;		
			}
			if(id) {
				location.href = url + '&id=' + id + '#order' + (key - 1);
			}
		}
	}
	
	function delBookRequest(StoreID, bid)
	{
		if(StoreID && bid)
		{
			location.href = '/foodwine/?act=book&cp=delete&StoreID=' + StoreID + '&bid=' + bid;
		}
	}
	
	function mouseEventDel(flag)
	{
		onDelete = flag;
	}
{/literal}
</script>
{include file="../seller_home_rightmenu.tpl"}
<form id="orderform" name="orderform" action="/foodwine/?act=book" method="post">
{if $req.cp eq ''}
<h1 class="soc-orderonline">Your Booking Requests</h1>
<div class="view-passorder"><a href="/foodwine/?act=book&cp=viewpastorder" style="font-size:12px; line-height:9px; text-decoration:none">View Past Booking Requests</a></div>
{else}
<h1 class="soc-orderonline">Your Past Booking Requests</h1>
<div class="view-passorder"><a href="/foodwine/?act=book" style="font-size:12px; line-height:9px; text-decoration:none">View New Booking Requests</a></div>
{/if}
<div class="clear"></div>
<div style="border-bottom:1px solid #CCCCCC; margin:0 0 10px;"></div>
<div class="orderonline_items_list">
	<ul>
    {foreach from=$req.book_lists.items item=book key=k}
        <a name="order{$k}"></a>
    	{if $req.id eq $book.id}    	
        <li class="current" style="background-color:#EEEEEE;">
            <div class="buyer-info" style="padding:0 5px 20px 0;height:auto">
            <table style="width:100%; " class="table-info">
                <tr class="first">
                    <td width="12%"><img src="/skin/red/images/foodwine/order-current.png">&nbsp;&nbsp;{$book.sn}</td>
                    <td width="33%"><strong class="buyer-name-storng">{$book.firstname} {$book.lastname}</strong></td>
                    <td width="13%">{$book.book_date|date_format:"%d.%m.%Y"}</td>
                    <td width="52%">{if $book.status eq '0'}<img src="/skin/red/images/foodwine/order-new.png">{else}
            <a title="Delete" onmouseover="mouseEventDel(true);" onmouseout="mouseEventDel(false);" style="float:right; padding-right:154px; z-index:999" onclick="delBookRequest('{$book.StoreID}', '{$book.id}');" href="javascript:void(0);"><img src="/skin/red/images/icon-deletes.gif"></a>
            {/if}</td>
                </tr>
                <tr style="height:3px;">
                    <td width="12%"></td>
                    <td width="33%"></td>
                    <td width="13%"></td>
                    <td width="52%"></td>
                </tr>
                <tr>
                    <td width="12%"></td>
                    <td width="33%">{if $book.phone}<strong>P</strong> {$book.phone}{/if}</td>
                    <td colspan="2"><strong>Reservation Detail</strong></td>
                </tr>
                <tr>
                    <td width="12%"></td>
                    <td width="33%">{if $book.email}<strong>E</strong> {$book.email}{/if}</td>
                    <td width="13%" colspan="2"><span style="width:97px; display:block">Day / Date / Time:</span>{$book.reservation_date_format}</td>
                </tr>
                <tr>
                    <td width="12%"></td>
                    <td width="33%"></td>
                    <td width="13%" colspan="2"><span style="width:97px; display:block">No. of People:</span>{$book.quantity}{if $book.quantity >= 10}+{/if} people</td>
                </tr>
                <tr>
                    <td width="12%"></td>
                    <td width="33%"></td>
                    <td width="13%" colspan="2"><span style="width:97px; display:block">Comments:</span><span style="width:255px;">{$book.comments}</span></td>
                </tr>
            </table>
            </div>
            <div class="clear"></div>
    	</li>
        {else}
    	<li onmouseover="mouseEventLI(this, true);" onmouseout="mouseEventLI(this, false);" onclick="changeOrder('{$book.id}', '{$k}');">
            <span class="img"><img src="/skin/red/images/foodwine/basket_save.png" /></span>
            <span class="orderid">{$book.sn}</span>
            <span class="buyer-name">{$book.firstname} {$book.lastname}</span>
            <span class="order-date">{$book.book_date|date_format:"%d.%m.%Y"}</span>
            {if $book.status eq '0'}
            <span><img src="/skin/red/images/foodwine/order-new.png"></span>
            {else}
            <a title="Delete" onmouseover="mouseEventDel(true);" onmouseout="mouseEventDel(false);" style="float:right; padding-right:160px; z-index:999" onclick="delBookRequest('{$book.StoreID}', '{$book.id}');" href="javascript:void(0);"><img src="/skin/red/images/icon-deletes.gif"></a>
            {/if}
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
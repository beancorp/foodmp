{literal}
<style type="text/css">
	ul.mainlist {list-style:none; margin:0; width:750px; background:#9E99C1; float:left; }
	ul.mainlist li{padding:0;margin:0; float:left; width:100%; }
	
	ul.listhead { list-style:none; margin:0; padding:0; width:100%; float:left; height:23px;}
	ul.listhead li{ padding:0; background:#9E99C1; color:#FFFFFF; font-weight:bold; text-align:center;height:23px;line-height:23px; border-left:1px solid #FFFFFF;}
	ul.listhead li{ margin:0 0 1px 0px ; float:left;width:89px;overflow:hidden;}
	ul.listhead li.qty{width:112px;}	
	ul.listhead li.item{width:141px;overflow:hidden;}
	ul.listhead li.ship{width:130px;}
	ul.listhead li.status{width:92px;}
	
	ul.list { height:31px; list-style:none; margin:0; padding:0; width:100%; float:left;}
	ul.list li{float:left;	width:89px;   height:30px;   line-height:30px;   margin:0 0 0px 0px;  border-left:1px solid #9E99C1;   border-bottom:1px solid #9E99C1;  text-align:center;}
	ul.list li{ padding:0; background:#ffffff;}
	ul.list li.qty{width:112px;}
	ul.list li.item{width:141px;}
	ul.list li.ship{width:130px;height:27px;padding-top:3px;font-size:10px;line-height:12px;}
	ul.list li.status{width:92px;}
	ul.mainlist li.pagelist{ border-left:1px solid #FFFFFF;
			border-bottom:1px solid #ffffff;
			border-right:1px solid #ffffff;
			background:#FFFFFF;
			width:750px;
			height:22px;
			line-height:22px;
			text-align:center;
    }
	ul.list li .inputX{_margin:3px 0 0 0;}
	.templatelist{
		list-style:none;
		margin-left:15px;
		float:left;
		_margin-left:10px;
		width:400px;
		overflow:hidden;
	}
	.templatelist li{
		list-style:none;
		float:left;
		width:200px;
		font-weight:bold;
		margin-left:20px;
		margin-right:20px;
		margin-bottom:20px;
		text-align:center;
		overflow:hidden;
	}
	.tabtmp{
		list-style:none;
		margin:0;
		float:left;
	}
	.tabtmp li{
		list-style:none;
		width:200px;
		height:40px;
		line-height:40px;
		text-align:center;
		float:left;
		cursor:pointer;
		font-weight:bold;
	}
	.tabtmp li.active_tab{
		background-color:#9E99C1;
	}
</style>
{/literal}
<div style="background-color: rgb(238, 238, 238); width:{if $smarty.session.attribute eq '0'}4{else}2{/if}00px; height: 40px; margin: 0pt 0px; border-bottom: 1px solid rgb(238, 238, 238);">
 	<ul class="tabtmp">
    	<li class="active_tab"><a href="/soc.php?cp=purchase" style="font-weight:bold;color:#FFF;text-decoration:none; line-height:40px;">Items Purchased</a></li>
{if $smarty.session.attribute eq '0'}
        <li style="width:200px;"><a href="/soc.php?cp=saleshistory" style="font-weight:bold;text-decoration:none; line-height:40px;">Items Sold</a></li>
{/if}
    </ul>
    <div style="clear: both;"></div>
 </div>
<ul class="mainlist">
	<li>
		<ul class="listhead">
			<li style="border-color:#9E99C1;">Order Date</li>
			<li class="item">Item Name</li>
			<li class="qty">Seller</li>
            <li class="ship">Shipping</li>
            <li>Total</li>
            <li class="status">Status</li>
            <li>Invoice</li>
		</ul>
	</li>
	{if $req.purchase.list}
		{foreach from=$req.purchase.list item=rpl}
        <form>
		<li><ul class="list">
				<li>{$rpl.order_date|date_format:"$PBDateFormat"}</li>
				<li class="item" title="{$rpl.item_name}">{$rpl.item_name|truncate:25}</li>
				<li class="qty" title="{$rpl.bu_name}">{$rpl.bu_name|truncate:21}&nbsp;</li>
                <li class="ship">{$rpl.shipping_method|truncate:24} (${$rpl.shipping_cost|number_format:2|truncate:23})</li>
                <li title="${$rpl.amount|number_format:2}">${$rpl.amount|number_format:2|truncate:14}</li>
                <li class="status">{if $rpl.type eq 'bid' and $rpl.p_status eq 'Pending'}<input type="button" value="Pay now" style="cursor:pointer;padding:3px;margin:3px;width:60px;" onclick="location.href='{$soc_https_host}soc.php?cp=buy&StoreID={$rpl.StoreID}&refid={$rpl.ref_id}&pid={$rpl.pid}'" class="inputX" />{else}{$rpl.p_status}{/if}</li>
                <li>{if $rpl.type ne 'bid' or $rpl.p_status ne 'Pending'}<input type="button" value="Print Invoice" style="cursor:pointer;padding:3px;margin:3px;width:80px;" onclick="window.open('/invoice_print.php?orderid={$rpl.ref_id}','_blank','width=965,height=400,toolbar=0,scrollbars=yes,location=0,statusbar=0,menubar=0,resizable=1');void(0);" />{/if}</li>
			</ul>
		</li>
        </form>
		{/foreach}
	{/if}
	<li class="pagelist">
		{$req.purchase.links.all}
	</li>
	</ul>
	<div style="clear:left;"></div>	
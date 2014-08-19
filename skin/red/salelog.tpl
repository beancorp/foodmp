{if $notfull neq '1'}
{literal}
<style type="text/css">
body{text-algin:center;margin:0 auto;}
 .hittable{
	 text-align:center;
 }
 .hittable th{
	 background-color:#9E99C1;
	 border-right:1px solid #FFF;
	 color: #FFF;
	 font-weight:bold;
	 height:23px;
	 font-size:12px;
 }
 .hittable th a{
	 color:#FFF;
	 font-weight:bold;
 }
 .hittable td{
	 border-right:1px solid #9E99C1;
	 border-bottom:1px solid #9E99C1;
	 height:23px;
	 font-size:12px;
 }
 .hittable td.firsttd{
	  border-left:1px solid #9E99C1;
 }
 .hittable th.endth{
	 background-color:#9E99C1;
	 border-right:1px solid #9E99C1;
 }
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
<script>
function changeStatus(refid,stat){
	if(confirm('Are you sure to change the status of order?')){
		$.getJSON('/include/jquery_svr.php',{svr:"changebidorder",refid:refid,stat:stat},function(data){
			if(data.status=="true"){
				$("#order_status"+refid).html(data.html);
				$("#order_status"+refid+" option[value='"+stat+"']").attr("selected","selected");
			}else{
				alert('Failed to change the status of order.');
			}
		});
	}else{
		$("#order_status"+refid+" option[value='"+$("#old_order_status"+refid).val()+"']").attr("selected","selected");
	}
}
</script>
{/literal}
{if $req.print}<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
{literal}
<style type="text/css">
#salehistory{width:950px;margin:auto;}
body{text-align:center;background:none;}
</style>
{/literal}
{/if}
{$req.xajax_Javascript}
<div id="salehistory">
{/if}
{if $req.print}
<table  cellpadding="0"  cellspacing="0" width="100%" class="hittable">
<tr>
<th>Item name</th>
<th>Date of purchase</th>
<th>Buyer name</th>
<th>Quantity</th>
<th>Total price ($)</th>
<th>Payment method</th>
<th class="endth">Order Status</th>
</tr>
{else}
<div style="background-color: rgb(238, 238, 238); width:{if $smarty.session.attribute ne '4'}4{else}2{/if}00px; height: 40px; margin: 0pt 0px; border-bottom: 1px solid rgb(238, 238, 238);">
 	<ul class="tabtmp">
    	<li><a href="/soc.php?cp=purchase" style="font-weight:bold;text-decoration:none; line-height:40px;">Items Purchased</a></li>
{if $smarty.session.attribute eq '0'}
        <li class="active_tab" style="width:200px;"><a href="/soc.php?cp=saleshistory" style="font-weight:bold;color:#FFF;text-decoration:none; line-height:40px;">Items Sold</a></li>
{/if}
    </ul>
    <div style="clear: both;"></div>
 </div>
    <div style="clear: both;"></div>
<table  cellpadding="0"  cellspacing="0" width="100%" class="hittable">
<tr>
<th><a href="javascript:void(0);" onclick="xajax_getsalelogX({$StoreID},{$page},'item_name',{if $order eq 'ASC'}'DESC'{else}'ASC'{/if})">Item name</a>{if $field eq 'item_name'}{if $order eq 'ASC'}&darr;{elseif $order eq 'DESC'}&uarr;{/if}{/if}</th>
<th><a href="javascript:void(0);" onclick="xajax_getsalelogX({$StoreID},{$page},'order_date',{if $order eq 'ASC'}'DESC'{else}'ASC'{/if})">Date of purchase</a>{if $field eq 'order_date'}{if $order eq 'ASC'}&darr;{elseif $order eq 'DESC'}&uarr;{/if}{/if}</th>
<th><a href="javascript:void(0);" onclick="xajax_getsalelogX({$StoreID},{$page},'bu_nickname',{if $order eq 'ASC'}'DESC'{else}'ASC'{/if})">Buyer name</a>{if $field eq 'bu_nickname'}{if $order eq 'ASC'}&darr;{elseif $order eq 'DESC'}&uarr;{/if}{/if}</th>
<th><a href="javascript:void(0);" onclick="xajax_getsalelogX({$StoreID},{$page},'month',{if $order eq 'ASC'}'DESC'{else}'ASC'{/if})">Quantity</a>{if $field eq 'month'}{if $order eq 'ASC'}&darr;{elseif $order eq 'DESC'}&uarr;{/if}{/if}</th>
<th><a href="javascript:void(0);" onclick="xajax_getsalelogX({$StoreID},{$page},'amount',{if $order eq 'ASC'}'DESC'{else}'ASC'{/if})">Total price ($)</a>{if $field eq 'amount'}{if $order eq 'ASC'}&darr;{elseif $order eq 'DESC'}&uarr;{/if}{/if}</th>
<th><a href="javascript:void(0);" onclick="xajax_getsalelogX({$StoreID},{$page},'description',{if $order eq 'ASC'}'DESC'{else}'ASC'{/if})">Payment method</a>{if $field eq 'description'}{if $order eq 'ASC'}&darr;{elseif $order eq 'DESC'}&uarr;{/if}{/if}</th>
<th class="endth"><a href="javascript:void(0);" onclick="xajax_getsalelogX({$StoreID},{$page},'p_status',{if $order eq 'ASC'}'DESC'{else}'ASC'{/if})">Order Status</a>{if $field eq 'p_status'}{if $order eq 'ASC'}&darr;{elseif $order eq 'DESC'}&uarr;{/if}{/if}</th></tr>
{/if}
{if $req.product}
	{foreach from=$req.product item=pl}
    <tr>
        <td class="firsttd">{$pl.item_name}</td>
        <td>{$pl.order_date|date_format:"$PBDateFormat"}</td>
        <td><a href="soc.php?cp=disreview&StoreID={$pl.buyer_id}&pid={$pl.pid}">{$pl.bu_nickname}</a>&nbsp;</td>
        <td>{$pl.month}</td>
        <td>{$pl.amount|number_format:2}</td>
        <td>{$pl.description}&nbsp;</td>
        <td>{if $pl.type eq 'bid' and not $req.print}<select name="order_status{$pl.ref_id}" onchange="changeStatus({$pl.ref_id},this.value)" id="order_status{$pl.ref_id}">
                {if $pl.p_status eq 'Pending'}
                <option value="Pending">Pending</option>
                {/if}
                {if $pl.p_status eq 'Pending' or $pl.p_status eq 'Paid'}
                <option value="Paid">Paid</option>
                {/if}
                {if $pl.p_status ne 'Completed'}
                <option value="Shipped">Shipped</option>
                {/if}
                <option value="Completed">Completed</option>
                </select><input type="hidden" name="old_order_status{$pl.ref_id}" id="old_order_status{$pl.ref_id}" value="{$pl.p_status}" />{else}{$pl.p_status}{/if}</td>
	</tr>
    {/foreach}
    <tr>
    <td colspan="7" style=" border:0;text-align:center">
    {$req.links.all}
    </td>
    </tr>
{else}
	 <tr>
    <td colspan="7" style="border-left:1px solid #9E99C1; border-bottom:1px solid #9E99C1; border-right:1px solid #9E99C1; text-align:center">
    No records.
    </td>
    </tr>
{/if}
</table>
{if $notfull neq '1'}
{if $req.print}{else}<div><a href="javascript:window.open('/salelog_print.php','_blank','width=965,toolbar=0,scrollbars=yes,location=0,statusbar=0,menubar=0,resizable=1');void(0);">Print</a></div>{/if}
</div>
{/if}
{if $req.print}
<script type="text/javascript">	window.print();</script>
{/if}
<table width="860px" cellpadding="0" cellspacing="0">
<colgroup>
	<col width="100px"/>
	<col width="100px"/>
	<col width="100px"/>
	<!--<col width="100px"/>-->
	<col width="80px"/>
	<col width="90px"/>
	<col width="90px"/>
	<col width="80px"/>
	<col width="80px"/>
	<col width="70px"/>
	<col width="70px"/>
</colgroup>
<tr>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpurchaseRecords('{$req.list.sort.page}',xajax.getFormValues('mainSearch'),'attribute','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Market Place</a>
    {if $req.list.sort.field eq 'attribute'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpurchaseRecords('{$req.list.sort.page}',xajax.getFormValues('mainSearch'),'buyer_nickname','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Buyer Nickname</a>
    {if $req.list.sort.field eq 'buyer_nickname'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpurchaseRecords('{$req.list.sort.page}',xajax.getFormValues('mainSearch'),'seller_webname','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Seller Website Name</a>
    {if $req.list.sort.field eq 'seller_webname'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td><!--
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpurchaseRecords('{$req.list.sort.page}',xajax.getFormValues('mainSearch'),'items','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Items</a>
    {if $req.list.sort.field eq 'items'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>-->
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpurchaseRecords('{$req.list.sort.page}',xajax.getFormValues('mainSearch'),'amount','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Price</a>
    {if $req.list.sort.field eq 'amount'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpurchaseRecords('{$req.list.sort.page}',xajax.getFormValues('mainSearch'),'commission_type','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Commission Type</a>
    {if $req.list.sort.field eq 'commission_type'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpurchaseRecords('{$req.list.sort.page}',xajax.getFormValues('mainSearch'),'commission','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Commission</a>
    {if $req.list.sort.field eq 'commission'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpurchaseRecords('{$req.list.sort.page}',xajax.getFormValues('mainSearch'),'paymethod','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Payment Method</a>
    {if $req.list.sort.field eq 'paymethod'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpurchaseRecords('{$req.list.sort.page}',xajax.getFormValues('mainSearch'),'orderDate','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Date</a>
    {if $req.list.sort.field eq 'orderDate'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpurchaseRecords('{$req.list.sort.page}',xajax.getFormValues('mainSearch'),'p_status','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Payment Status</a>
    {if $req.list.sort.field eq 'p_status'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop">&nbsp;</td>
</tr>
{if $req.list.purlist}
	{foreach from=$req.list.purlist item=l}
	<tr>
		<td class="tablelist">{if $l.attribute eq '0'}Buy & Sell{else}Food & Wine{/if}&nbsp;</td>
		<td class="tablelist">{$l.buyer_nickname}&nbsp;</td>
		<td class="tablelist">{$l.seller_webname}&nbsp;</td>
		<!--<td class="tablelist">{$l.items}</td>-->
		<td class="tablelist">${$l.amount|number_format:2}&nbsp;</td>
		<td class="tablelist">{if $l.commission_type eq '0'}{$lang.main.lb_commission_type_manual}{else}{$lang.main.lb_commission_type_automatic}{/if}</td>
		<td class="tablelist">${$l.commission|number_format:2}&nbsp;</td>
		<td class="tablelist">{$l.paymethod}</td>
		<td class="tablelist">{$l.orderDate|date_format:"$PBDateFormat"}</td>
		<td class="tablelist">{$l.p_status}</td>
		<td class="tablelist"><input type="button" value="{$lang.but.view}" class="hbutton" onClick="javascript:xajax_viewPurchase('{$l.ref_id}', '{$req.list.pageno}')" /></td>
	</tr>
	{/foreach}
	<tr>
		<td class="tablelis" colspan="10" align="center" style="background-color:#FFFFFF">{$req.list.links.all}</td>    </tr>
{else}
<tr>
	<td class="tablelist" colspan="10" align="center" style="background-color:#FFFFFF">{$lang.pub_clew.nothing}</td>
</tr>
{/if}


</table>
<table width="860px" cellpadding="0" cellspacing="0">
<colgroup>
	<col width="100px"/>
	<col width="150px"/>
	<col width="100px"/>
	<col width="150px"/>
	<col width="100px"/>
	<col width="80px"/>
	<col width="100px"/>
	<col width="100px"/>
	<col width="80px"/>
</colgroup>
<tr>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpaymentRecords('{$req.list.sort.page}',xajax.getFormValues('searchForm'),'buyer_nickname','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Buyer Nickname</a>
    {if $req.list.sort.field eq 'buyer_nickname'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpaymentRecords('{$req.list.sort.page}',xajax.getFormValues('searchForm'),'buyer_email','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Buyer Email</a>
    {if $req.list.sort.field eq 'buyer_email'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpaymentRecords('{$req.list.sort.page}',xajax.getFormValues('searchForm'),'seller_webname','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Seller Website Name</a>
    {if $req.list.sort.field eq 'seller_webname'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpaymentRecords('{$req.list.sort.page}',xajax.getFormValues('searchForm'),'seller_email','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Seller Email</a>
    {if $req.list.sort.field eq 'seller_email'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpaymentRecords('{$req.list.sort.page}',xajax.getFormValues('searchForm'),'items','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Items</a>
    {if $req.list.sort.field eq 'items'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpaymentRecords('{$req.list.sort.page}',xajax.getFormValues('searchForm'),'amount','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Price</a>
    {if $req.list.sort.field eq 'amount'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpaymentRecords('{$req.list.sort.page}',xajax.getFormValues('searchForm'),'paymethod','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Payment Method</a>
    {if $req.list.sort.field eq 'paymethod'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpaymentRecords('{$req.list.sort.page}',xajax.getFormValues('searchForm'),'paymethod','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Commission</a>
    {if $req.list.sort.field eq 'commission'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
	<td class="tabletop"><a href="#" onclick="javascript:xajax_getpaymentRecords('{$req.list.sort.page}',xajax.getFormValues('searchForm'),'orderDate','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Date</a>
    {if $req.list.sort.field eq 'orderDate'}
    	{if $req.list.sort.order eq 'ASC'}
        	&darr;
        {elseif $req.list.sort.order eq 'DESC'}
        	&uarr;
        {/if}
    {/if}</td>
</tr>
{if $req.list.purlist}
	{foreach from=$req.list.purlist item=l}
	<tr>
		<td class="tablelist">{$l.buyer_nickname}&nbsp;</td>
		<td class="tablelist">{$l.buyer_email}&nbsp;</td>
		<td class="tablelist">{$l.seller_webname}&nbsp;</td>
		<td class="tablelist">{$l.seller_email}&nbsp;</td>
		<td class="tablelist">{$l.items}</td>
		<td class="tablelist">${$l.amount|number_format:2}&nbsp;</td>
		<td class="tablelist">{$l.paymethod}</td>
		<td class="tablelist">{$l.commission}</td>
		<td class="tablelist">{$l.orderDate|date_format:"$PBDateFormat"}</td>
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
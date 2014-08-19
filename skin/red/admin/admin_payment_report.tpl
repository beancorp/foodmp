<table width="950px" cellpadding="0" cellspacing="0">
<colgroup>
	<col width="100px"/>
	<col width="150px"/>
	<col width="60px"/>
	<col width="80px"/>
	<col width="90px"/>
	<col width="90px"/>
	<col width="60px"/>
	<col width="80px"/>
	<col width="110px"/>
	<col width="110px"/>
	<col width="20px"/>
</colgroup>
<tr>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payReport{$req.sorturl}&field=date&order={if $req.field eq 'date'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Date</a>{if $req.field eq 'date'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payReport{$req.sorturl}&field=bu_nickname&order={if $req.field eq 'bu_nickname'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Nickname</a>{if $req.field eq 'bu_nickname'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payReport{$req.sorturl}&field=bu_email&order={if $req.field eq 'bu_email'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Email</a>{if $req.field eq 'bu_email'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>	
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payReport{$req.sorturl}&field=attribute&order={if $req.field eq 'attribute'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">User Type</a>{if $req.field eq 'attribute'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payReport{$req.sorturl}&field=bu_state&order={if $req.field eq 'bu_state'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">State</a>{if $req.field eq 'bu_state'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payReport{$req.sorturl}&field=details&order={if $req.field eq 'details'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Description</a>{if $req.field eq 'details'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payReport{$req.sorturl}&field=amount&order={if $req.field eq 'amount'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Amount</a>{if $req.field eq 'amount'}{if $req.order eq 'asc'}&uarr;{elseif $req.order eq 'desc'}&darr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payReport{$req.sorturl}&field=name&order={if $req.field eq 'name'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Account/Name</a>{if $req.field eq 'name'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payReport{$req.sorturl}&field=address&order={if $req.field eq 'address'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Address</a>{if $req.field eq 'address'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
</tr>
{if $req.reflist.list}
	{foreach from=$req.reflist.list item=l}
	<tr>
		<td class="tablelist">{$l.addtime|date_format:"$PBDateFormat"}&nbsp;</td>
		<td class="tablelist">{$l.bu_nickname}&nbsp;</td>
		<td class="tablelist">{$l.bu_email}&nbsp;</td>
		<td class="tablelist">{$l.attribute}&nbsp;</td>		
		<td class="tablelist">{$l.bu_state}&nbsp;</td>
		<td class="tablelist">{$l.details|truncate:6:''}&nbsp;</td>
		<td class="tablelist">${$l.amount|number_format:2}&nbsp;</td>
		<td class="tablelist">{$l.name}&nbsp;</td>
		<td class="tablelist">{$l.address}&nbsp;</td>
	</tr>
	{/foreach}
	<tr>
		<td class="tablelis" colspan="9" align="center" style="background-color:#FFFFFF">{$req.reflist.links.all}</td>    </tr>
{else}
<tr>
	<td class="tablelist" colspan="9" align="center" style="background-color:#FFFFFF">{$lang.pub_clew.nothing}</td>
</tr>
{/if}
<tr>
	<td class="tablelist" colspan="9" align="center" style="background-color:#FFFFFF">Total User: {$req.reflist.total.num|number_format:0} &nbsp;&nbsp;&nbsp;&nbsp;Total Amount:${$req.reflist.total.totals|number_format:2}</td>
</tr>
</table>
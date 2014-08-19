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
</colgroup>
<tr>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_report{$req.sorturl}&field=bu_nickname&order={if $req.field eq 'bu_nickname'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Nickname</a>{if $req.field eq 'bu_nickname'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_report{$req.sorturl}&field=bu_email&order={if $req.field eq 'bu_email'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Email</a>{if $req.field eq 'bu_email'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_report{$req.sorturl}&field=bu_phone&order={if $req.field eq 'bu_phone'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Phone</a>{if $req.field eq 'bu_phone'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_report{$req.sorturl}&field=contact&order={if $req.field eq 'contact'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Preferred Contact</a>{if $req.field eq 'contact'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_report{$req.sorturl}&field=launch_date&order={if $req.field eq 'launch_date'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Joined Date</a>{if $req.field eq 'launch_date'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_report{$req.sorturl}&field=renewalDate&order={if $req.field eq 'renewalDate'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Expiry Date</a>{if $req.field eq 'renewalDate'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_report{$req.sorturl}&field=bu_state&order={if $req.field eq 'bu_state'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">State</a>{if $req.field eq 'bu_state'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_report{$req.sorturl}&field=referNum&order={if $req.field eq 'referNum'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Number of Referrals</a>{if $req.field eq 'referNum'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_report{$req.sorturl}&field=ref_income&order={if $req.field eq 'ref_income'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Current Referral Owing</a>{if $req.field eq 'ref_income'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_report{$req.sorturl}&field=ref_total&order={if $req.field eq 'ref_total'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Total Referral Earnt</a>{if $req.field eq 'ref_total'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<!--<td class="tabletop"><a href="/admin/?act=referral&cp=ref_report{$req.sorturl}&field=status&order={if $req.field eq 'status'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Payment</a>{if $req.field eq 'status'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>-->
</tr>
{if $req.reflist.list}
	{foreach from=$req.reflist.list item=l}
	<tr>
		<td class="tablelist">{$l.bu_nickname}&nbsp;</td>
		<td class="tablelist">{$l.bu_email}&nbsp;</td>
		<td class="tablelist">{$l.bu_phone}&nbsp;</td>
		<td class="tablelist">{$l.contact}&nbsp;</td>
		<td class="tablelist">{$l.launch_date|date_format:"$PBDateFormat"}</td>
		<td class="tablelist">{$l.renewalDate|date_format:"$PBDateFormat"}</td>
		<td class="tablelist">{$l.bu_state}&nbsp;</td>
		<td class="tablelist">{$l.referNum|number_format:0}</td>
		<td class="tablelist">${$l.ref_income|number_format:2}</td>
		<td class="tablelist">${$l.ref_total|number_format:2}</td>
		<!--<td class="tablelist" {if $l.checktype neq "0"}style="color:#FF0000"{/if}>{$l.status}&nbsp;</td>-->
	</tr>
	{/foreach}
	<tr>
		<td class="tablelis" colspan="10" align="center" style="background-color:#FFFFFF">{$req.reflist.links.all}</td>    </tr>
{else}
<tr>
	<td class="tablelist" colspan="10" align="center" style="background-color:#FFFFFF">{$lang.pub_clew.nothing}</td>
</tr>
{/if}
</table>
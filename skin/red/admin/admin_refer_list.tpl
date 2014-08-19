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
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_list{$req.sorturl}&field=bu_nickname&order={if $req.field eq 'bu_nickname'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Nickname</a>{if $req.field eq 'bu_nickname'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_list{$req.sorturl}&field=bu_email&order={if $req.field eq 'bu_email'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Email</a>{if $req.field eq 'bu_email'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_list{$req.sorturl}&field=bu_phone&order={if $req.field eq 'bu_phone'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Phone</a>{if $req.field eq 'bu_phone'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_list{$req.sorturl}&field=contact&order={if $req.field eq 'contact'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Preferred Contact</a>{if $req.field eq 'contact'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_list{$req.sorturl}&field=launch_date&order={if $req.field eq 'launch_date'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Joined Date</a>{if $req.field eq 'launch_date'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_list{$req.sorturl}&field=renewalDate&order={if $req.field eq 'renewalDate'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Expiry Date</a>{if $req.field eq 'renewalDate'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_list{$req.sorturl}&field=bu_state&order={if $req.field eq 'bu_state'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">State</a>{if $req.field eq 'bu_state'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_list{$req.sorturl}&field=referNum&order={if $req.field eq 'referNum'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Number of Referrals</a>{if $req.field eq 'referNum'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_list{$req.sorturl}&field=ref_income&order={if $req.field eq 'ref_income'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Current Referral Owing</a>{if $req.field eq 'ref_income'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_list{$req.sorturl}&field=ref_total&order={if $req.field eq 'ref_total'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Total Referral Earnt</a>{if $req.field eq 'ref_total'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_list{$req.sorturl}&field=rfgst&order={if $req.field eq 'rfgst'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Status</a>{if $req.field eq 'rfgst'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
</tr>
{if $req.reflist.list}
	{foreach from=$req.reflist.list item=l}
	<tr>
		<td class="tablelist">{$l.bu_nickname}&nbsp;</td>
		<td class="tablelist"><a href="/admin/?act=referral&cp=ref_user&StoreID={$l.StoreID}">{$l.bu_email}</a>&nbsp;</td>
		<td class="tablelist">{$l.bu_phone}&nbsp;</td>
		<td class="tablelist">{$l.contact}&nbsp;</td>
		<td class="tablelist">{$l.launch_date|date_format:"$PBDateFormat"}</td>
		<td class="tablelist">{$l.renewalDate|date_format:"$PBDateFormat"}</td>
		<td class="tablelist">{$l.bu_state}&nbsp;</td>
		<td class="tablelist">{$l.referNum}</td>
		<td class="tablelist">${$l.ref_income|number_format:2}</td>
		<td class="tablelist">${$l.ref_total|number_format:2}</td>
		<td class="tablelist">{$l.rfgst}&nbsp;</td>
	</tr>
	{/foreach}
	<tr>
		<td class="tablelis" colspan="10" align="center" style="background-color:#FFFFFF">{$req.reflist.links.all}</td>    </tr>
{else}
<tr>
	<td class="tablelist" colspan="10" align="center" style="background-color:#FFFFFF">{$lang.pub_clew.nothing}</td>
</tr>
{/if}

<tr>
<td class="tablelis" colspan="10" align="center" style="background-color:#FFFFFF; padding-top:10px;">
For period {if $req.selected.start_date}{$req.selected.start_date}&nbsp;{if $req.selected.s_hour lt 10}0{/if}{$req.selected.s_hour}:00{else}{/if}{if $req.selected.start_date}&nbsp;-&nbsp;{else}&nbsp;&lt;=&nbsp;{/if}{if $req.selected.end_date}{$req.selected.end_date}&nbsp;{if $req.selected.e_hour lt 10}0{/if}{$req.selected.e_hour}:00{else}Now{/if}
</td></tr>
<tr>
	<td class="tablelis" colspan="10" align="center" style="background-color:#FFFFFF">Total Referrals: ${$req.reflist.total.total|number_format:2}&nbsp;&nbsp;&nbsp;&nbsp;Outstanding Referrals: ${$req.reflist.total.curtotal|number_format:2}&nbsp;&nbsp;&nbsp;&nbsp;Requested Referrals: ${$req.reflist.total.reqtotal|number_format:2}</td></tr>
</table>
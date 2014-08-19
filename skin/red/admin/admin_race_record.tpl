<table width="950px" cellpadding="0" cellspacing="0">
<colgroup>
	<col width="180px"/>
	<col width="150px"/>
	<col width="130px"/>
	<col width="120px"/>
	<col width="100px"/>
	<col width="100px"/>
</colgroup>
<tr>
	<td class="tabletop"><a href="/admin/?act=race&cp=race_list{$req.sorturl}&field=bu_nickname&order={if $req.field eq 'bu_nickname'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Nickname</a>{if $req.field eq 'bu_nickname'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=race&cp=race_list{$req.sorturl}&field=bu_email&order={if $req.field eq 'bu_email'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Email</a>{if $req.field eq 'bu_email'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=race&cp=race_list{$req.sorturl}&field=bu_phone&order={if $req.field eq 'bu_phone'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Phone</a>{if $req.field eq 'bu_phone'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=race&cp=race_list{$req.sorturl}&field=launch_date&order={if $req.field eq 'launch_date'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Joined Date</a>{if $req.field eq 'launch_date'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=race&cp=race_list{$req.sorturl}&field=bu_state&order={if $req.field eq 'bu_state'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">State</a>{if $req.field eq 'bu_state'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=race&cp=race_list{$req.sorturl}&field=bu_suburb&order={if $req.field eq 'bu_suburb'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Suburb</a>{if $req.field eq 'bu_suburb'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=race&cp=race_list{$req.sorturl}&field=points&order={if $req.field eq 'points'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Points</a>{if $req.field eq 'points'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
</tr>
{if $req.reflist.list}
	{foreach from=$req.reflist.list item=l}
	<tr>
		<td class="tablelist">{$l.bu_nickname}&nbsp;</td>
		<td class="tablelist">{$l.bu_email}&nbsp;</td>
		<td class="tablelist">{$l.bu_phone}&nbsp;</td>
		<td class="tablelist">{$l.launch_date|date_format:"$PBDateFormat"}</td>
		<td class="tablelist">{$l.bu_state}&nbsp;</td>
		<td class="tablelist">{$l.bu_suburb}</td>
		<td class="tablelist"><a href="/admin/?act=race&cp=record_list&StoreID={$l.StoreID}">{$l.total_points}</a>&nbsp;</td>
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
</table>
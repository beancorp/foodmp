<table width="650px" cellpadding="0" cellspacing="0" align="center">
<colgroup>
	<col width="100px"/>
	<col width="150px"/>
	<col width="90px"/>
	<col width="90px"/>
	<col width="90px"/>
</colgroup>
<tr>
	<td class="tabletop"><a href="/admin/?act=referral&cp=downlist&field=create_time&order={if $req.field eq 'create_time'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Date</a>{if $req.field eq 'create_time'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=downlist&field=start_date&order={if $req.field eq 'start_date'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Start Date</a>{if $req.field eq 'start_date'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=downlist&field=end_date&order={if $req.field eq 'end_date'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">End Date</a>{if $req.field eq 'end_date'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=downlist&field=export_num&order={if $req.field eq 'export_num'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Exported Number</a>{if $req.field eq 'export_num'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop">Action</td>
</tr>
{if $req.reflist.list}
	{foreach from=$req.reflist.list item=l}
	<tr>
		<td class="tablelist">{$l.create_time|date_format:"$PBDateFormat"}&nbsp;</td>
		<td class="tablelist">{if $l.start_date}{$l.start_date|date_format:"$PBDateFormat"}{/if}&nbsp;</td>
		<td class="tablelist">{if $l.end_date}{$l.end_date|date_format:"$PBDateFormat"}{/if}&nbsp;</td>
		<td class="tablelist">{$l.export_num}</td>
		<td class="tablelist"><a href="/admin/?act=referral&cp=download&file={$l.filename}">Download</a>&nbsp;</td>
	</tr>
	{/foreach}
	<tr>
		<td class="tablelis" colspan="9" align="center" style="background-color:#FFFFFF">{$req.reflist.links.all}</td>
	</tr>
{else}
	<tr>
		<td class="tablelist" colspan="6" align="center" style="background-color:#FFFFFF">{$lang.pub_clew.nothing}</td>
	</tr>
{/if}
</table>
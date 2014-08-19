<table cellpadding="0" cellspacing="0">
	<colgroup>
			<col width="100px"/>
			<col width="130px"/>
			<col width="200px"/>
			<col width="150px"/>
			<col width="150px"/>
	</colgroup>
	<tr>
		<td class="tabletop">
			<a href="/admin/?act=referral&cp=ref_config&field=ReferrerID&order={if $req.field eq 'ReferrerID'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}&page={$req.page}">Referrer ID</a>{if $req.field eq 'ReferrerID'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
			<td class="tabletop"><a href="/admin/?act=referral&cp=ref_config&field=percent&order={if $req.field eq 'percent'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}&page={$req.page}">Commission Rate</a>{if $req.field eq 'percent'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
			<td class="tabletop"><a href="/admin/?act=referral&cp=ref_config&field=min_commission&order={if $req.field eq 'min_commission'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}&page={$req.page}">Earnings Before Commission</a>{if $req.field eq 'min_commission'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
			<td class="tabletop"><a href="/admin/?act=referral&cp=ref_config&field=min_refer&order={if $req.field eq 'min_refer'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}&page={$req.page}">Withdrawal Amount</a>{if $req.field eq 'min_refer'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
            
            <td class="tabletop"><a href="/admin/?act=referral&cp=ref_config&field=status&order={if $req.field eq 'status'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}&page={$req.page}">Status</a>{if $req.field eq 'status'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
            
			<td class="tabletop">Action</td>
		</tr>
	{if $req.configlist.list neq ""}
		{foreach from=$req.configlist.list item=rpl}
		<tr>
			<td class="tablelist">{$rpl.ReferrerID}</td>
			<td class="tablelist">{$rpl.percent}%</td>
			<td class="tablelist">${$rpl.min_commission|number_format:2}</td>
			<td class="tablelist">${$rpl.min_refer|number_format:2}</td>
            <td class="tablelist">{$rpl.status}&nbsp;</td>
			<td class="tablelist"><input type="button" class="hbutton"  value="&nbsp;{$lang.but.edit}&nbsp;" onclick="refedit('{$rpl.id}');"/>&nbsp;<input type="button" class="hbutton" value="{$lang.but.delete}"  onclick="refdel('{$rpl.id}')"/></td>
		</tr>
		{/foreach}
		<tr>
			<td colspan="5" align="center">{$req.configlist.links.all}</td>
		</tr>
	{else}
		<tr>
			<td class="tablelist" colspan="5" align="center" style="background-color:#FFFFFF">{$lang.pub_clew.nothing}</td>
		</tr>
	{/if}
	</table>
	<input name="pageno" type="hidden" id="pageno" value="{$req.configlist.pageno}"/>
	<table cellpadding="0" cellspacing="0">
		<colgroup>
			<col width="100px"/>
			<col width="400px"/>
			<col width="100px"/>
			<col width="100px"/>
		</colgroup>
		<tr><td colspan="3" align="left"><div style="margin-bottom:10px;">
		Total Referrals: {if $req.ref.total_ref}{$req.ref.total_ref}{else}0{/if} <br/>
		Total Earnings: ${$req.ref.total_amount|number_format:2}
		</div></td>
		<td valign="top" align="right"><a href="/admin/?act=referral&cp=ref_list" style="text-decoration:none;">&lt;&lt;back</a></td>
		</tr>
		<tr>
			<td class="tabletop">
			<a href="/admin/?act=referral&cp=ref_user&StoreID={$req.StoreID}&field=addtime&order={if $req.field eq 'addtime'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}&page={$req.page}">Date</a>{if $req.field eq 'addtime'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
			<td class="tabletop"><a href="/admin/?act=referral&cp=ref_user&StoreID={$req.StoreID}&field=details&order={if $req.field eq 'details'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}&page={$req.page}">Description</a>{if $req.field eq 'details'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
			<td class="tabletop"><a href="/admin/?act=referral&cp=ref_user&StoreID={$req.StoreID}&field=amount&order={if $req.field eq 'amount'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}&page={$req.page}">Amount</a>{if $req.field eq 'amount'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
			<td class="tabletop"><a href="/admin/?act=referral&cp=ref_user&StoreID={$req.StoreID}&field=balance&order={if $req.field eq 'balance'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}&page={$req.page}">Balance</a>{if $req.field eq 'balance'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
		</tr>
		{if $req.ref.refer_list}
		{foreach from=$req.ref.refer_list item=rpl}
		<tr>
			<td class="tablelist">{$rpl.addtime|date_format:"$PBDateFormat"}</td>
			<td class="tablelist">{$rpl.details}</td>
			<td class="tablelist">{if $rpl.amount}${$rpl.amount|number_format:2}{else}&nbsp;{/if}</td>
			<td class="tablelist">${$rpl.balance|number_format:2}</td>
		</tr>
		{/foreach}
		<tr>
			<td colspan="3" align="center">{$req.ref.links.all}</td>
		</tr>
	{/if}
	<tr>
	<td  align="right" colspan="4"> Current Referral Owing: ${$req.ref.earn_amount|number_format:2}&nbsp;&nbsp;&nbsp;</td>
	</tr>
	</table>
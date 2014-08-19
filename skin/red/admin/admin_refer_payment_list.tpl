<table width="850px" cellpadding="0" cellspacing="0">
<colgroup>
	<col width="100px"/>
	<col width="150px"/>
	<col width="90px"/>
	<col width="120px"/>
	<col width="190px"/>
	<col width="90px"/>
</colgroup>
<tr>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payment{$req.sorturl}&field=bu_nickname&order={if $req.field eq 'bu_nickname'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Nickname</a>{if $req.field eq 'bu_nickname'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payment{$req.sorturl}&field=bu_email&order={if $req.field eq 'bu_email'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Email</a>{if $req.field eq 'bu_email'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payment{$req.sorturl}&field=attribute&order={if $req.field eq 'attribute'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">User Type</a>{if $req.field eq 'attribute'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payment{$req.sorturl}&field=paymethod&order={if $req.field eq 'paymethod'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Payment method</a>{if $req.field eq 'paymethod'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payment{$req.sorturl}&field=ref_income&order={if $req.field eq 'ref_income'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Current Referral Requested</a>{if $req.field eq 'ref_income'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<!--<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payment{$req.sorturl}&field=ref_total&order={if $req.field eq 'ref_total'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Total Referral Income</a>{if $req.field eq 'ref_total'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
		<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payment{$req.sorturl}&field=launch_date&order={if $req.field eq 'launch_date'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Joined Date</a>{if $req.field eq 'launch_date'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>
	<td class="tabletop"><a href="/admin/?act=referral&cp=ref_payment{$req.sorturl}&field=renewalDate&order={if $req.field eq 'renewalDate'}{if $req.order eq 'desc' or $req.order eq ''}asc{else}desc{/if}{else}asc{/if}">Expiry Date</a>{if $req.field eq 'renewalDate'}{if $req.order eq 'asc'}&darr;{elseif $req.order eq 'desc'}&uarr;{/if}{/if}</td>-->
	<td class="tabletop">Amount Paid </td>
</tr>
{if $req.reflist.list}
<form action="" method="post">
	{foreach from=$req.reflist.list item=l}
	<tr>
		<td class="tablelist">{$l.bu_nickname}&nbsp;</td>
		<td class="tablelist">{$l.bu_email}&nbsp;</td>
		<td class="tablelist">{$l.attribute}&nbsp;</td>
		<td class="tablelist">{$l.paymethod}&nbsp;{if $l.name}<br/>{$l.name}{/if}{if $l.address}<br/>{$l.address|nl2br}{/if}</td>		
		<td class="tablelist">${$l.ref_income|number_format:2}</td>
		<!--<td class="tablelist">${$l.ref_total|number_format:2}</td>
		<td class="tablelist">{$l.launch_date|date_format:"$PBDateFormat"}</td>
		<td class="tablelist">{$l.renewalDate|date_format:"$PBDateFormat"}</td>-->
		<td class="tablelist"><input type="text" name="sendmoney[{$l.StoreID}]" class="inputB" size="10" maxlength="10"/></td>
	</tr>
	{/foreach}
	<tr>
		<td class="tablelis" colspan="9" align="center" style="background-color:#FFFFFF">{$req.reflist.links.all}</td>    </tr>
	<tr>
		<td class="tablelis" colspan="9" align="right" style="background-color:#FFFFFF">
		<input type="hidden" name="optval" value="saveform"/>
		<input type="submit" value="Pay Users" style=" background-color:#DDEBFF;
border:1px solid #999999;
color:#000000;float:left; margin:0 5px 0 565px; +margin:0 5px 0 465px;"  />
		</form>
		<form action="/admin/exportpaypal.php" method="post" id="expform" >
		<input type="submit" style="background-color:#DDEBFF;
border:1px solid #999999;
color:#000000;float:left" value="Export Paypal Report & Mark as Paid" />
		<input type="hidden" name="s_hour" value="{$req.selected.s_hour}"/>
		<input type="hidden" name="e_hour" value="{$req.selected.e_hour}"/>
		<input type="hidden" name="paymethod" value="{$req.selected.paymethod}"/>
		<input type="hidden" name="usertype" value="{$req.selected.usertype}"/>
		<input type="hidden" name="nickname" value="{$req.selected.nickname}"/>
		<input type="hidden" name="start_date" value="{$req.selected.start_date}"/>
		<input type="hidden" name="end_date" value="{$req.selected.end_date}"/>
		
		</form>
		</td>
	</tr>

{else}
<tr>
	<td class="tablelist" colspan="6" align="center" style="background-color:#FFFFFF">{$lang.pub_clew.nothing}</td>
</tr>
{/if}
<tr><td class="tablelist" colspan="6" align="center" style="background-color:#ffffff">Total Amount Requested: &nbsp;${$req.reflist.total.curtotal|number_format:2}</td></tr>
</table>
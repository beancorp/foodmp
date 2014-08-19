	<ul class="mainlist">
	<li>
		<ul class="listhead">
			<li>Date</li>
			<li style="border-left:1px solid #FFFFFF">Nickname</li>
			<li class="detail" style="border-left:1px solid #FFFFFF">Email</li>
            <li class="detail" style="border-left:1px solid #FFFFFF">Message</li>
		</ul>
	</li>
	{if $req.maillist.wishtlist_emaillist}
		{foreach from=$req.maillist.wishtlist_emaillist item=rpl}
		<li><ul class="list">
				<li>{$rpl.addtime|date_format:"$PBDateFormat"}</li>
				<li >{$rpl.nickname}</li>
				<li class="detail">{$rpl.email}&nbsp;</li>
                <li class="detail"><input type="button" value="View" onclick="javascript:location.href='/soc.php?act=wishlistproc&cp=wishlistmsg&msgid={$rpl.id}'" style="margin:3px 0;" /></li>
			</ul>
		</li>
		{/foreach}
	{/if}
	<li class="pagelist">
		{$req.maillist.links.all}
	</li>
	</ul>
	<div style="clear:left;"></div>	
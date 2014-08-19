	<ul class="mainlist">
	<li>
		<ul class="listhead">
			<li>Date</li>
			<li style="border-left:1px solid #FFFFFF">Nickname</li>
			<li class="detail" style="border-left:1px solid #FFFFFF">Email</li>
            <li class="detail" style="border-left:1px solid #FFFFFF">Message</li>
		</ul>
	</li>
	{if $req.ref.refer_emaillist}
		{foreach from=$req.ref.refer_emaillist item=rpl}
		<li><ul class="list">
				<li>{$rpl.addtime|date_format:"$PBDateFormat"}</li>
				<li >{$rpl.nickname}</li>
				<li class="detail">{$rpl.email}&nbsp;</li>
                <li class="detail"><input type="button" value="View" onclick="javascript:location.href='/soc.php?cp=refermessage&msgid={$rpl.id}'" style="margin:3px 0;" /></li>
			</ul>
		</li>
		{/foreach}
	{else}
		<li style="text-align:center; width:627px;border-right:1px solid #9E99C1;">
			<ul class="list">
			<li style="width:100%;">No Records</li>
			</ul>
		</li>
	{/if}
	<li class="pagelist">
		{$req.ref.links.all}
	</li>
	</ul>
	<div style="clear:left;"></div>	
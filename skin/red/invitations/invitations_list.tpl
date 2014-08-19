<ul class="mainlist">
	<li>
		<ul class="listhead">
			<li>Date</li>
			<li style="border-left:1px solid #FFFFFF">Recipient</li>
			<li class="detail" style="border-left:1px solid #FFFFFF">Event</li>
			<li style="border-left:1px solid #FFFFFF; width:80px;">Template</li>
            <li style="border-left:1px solid #FFFFFF; width:80px;">Preview</li>
            <li style="border-left:1px solid #FFFFFF; width:79px;">Action</li>
		</ul>
	</li>
	{if $req.invitation_list}
		{foreach from=$req.invitation_list item=rpl}
		<li><ul class="list">
				<li>{$rpl.add_time|date_format:"$PBDateFormat"}</li>
				<li title="{$rpl.invitation_name}">{$rpl.invitation_name|truncate:12:"..."}</li>
				<li class="detail" title="{$rpl.subject}">{$rpl.subject|truncate:25:"..."}&nbsp;</li>
				<li style="width:80px;">{if $rpl.type=='user'}Custom{else}{$rpl.type}{/if}&nbsp;</li>
                <li style="width:80px;"><input type="button" value="View" onclick="javascript:window.open('/soc.php?act=invithis&cp=view&id={$rpl.id}');" style="margin:3px 0; cursor:pointer" /></li>
                <li style="width:79px;"><input type="button" value="Send" onclick="javascript:location.href='/soc.php?act=invitations&id={$rpl.id|escape:base64_encode}';" style="margin:3px 0;cursor:pointer"/></li>
			</ul>
		</li>
		{/foreach}
	{/if}
	<li class="pagelist">
		{$req.links.all}
	</li>
	</ul>
<div style="clear:left;"></div>	
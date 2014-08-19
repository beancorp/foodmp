
	<form method="post">
	<div class="wrap">
	
		<ul class="title">
			<li style="width:25px; *width:35px; font-weight:bold;">#</li>
			<li style="width:25px;"><input type="checkbox" class="checkbox" onclick="selectAll();" class="input-none-border" />&nbsp;</li>
			<li style="width:450px; *width:490px; font-weight:bold;"><a href="#" onclick="javascript:xajax_listMessage('{$req.sort.page}','subject','{if $req.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.msg.message}</a>{if $req.sort.field eq 'subject'}{if $req.sort.order eq 'ASC'}&darr;{elseif $req.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
			<li style="width:100px; *width:120px; text-align:center; font-weight:bold;"><a href="#" onclick="javascript:xajax_listMessage('{$req.sort.page}','date','{if $req.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.msg.date}</a>{if $req.sort.field eq 'date'}{if $req.sort.order eq 'ASC'}&darr;{elseif $req.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
			<li style="width:45px; *width:55px;">&nbsp;</li>
		</ul>
		<div style="clear:both;"></div>
		{if $req.list}
		{foreach from=$req.list item=row}
		
		<ul class="data">
			<li style="width:25px; *width:35px;">{$row.alert}</li>
			<li style="width:25px;"><input type="checkbox" id="messageId_{$row.alert}" name="messageId[]" value="{$row.messageID}" class="checkbox" class="input-none-border"/>&nbsp;</li>
			<li style="width:450px; *width:490px;">{$row.subject}</li>
			<li style="width:100px; *width:120px; text-align:center;">{$row.date}</li>
			<li style="width:45px; *width:55px;"><input type="button" value="{$lang.but.view}" class="hbutton" onClick="{$row.js.view}" />&nbsp;</li>
		</ul>
		<div style="clear:both;"></div>
		{/foreach}
		<div class="action">
			<input name="delete" type="button" class="hbutton" value="{$lang.but.delete}" onclick="{$req.js.delete}" />
		</div>
		<div class="page_nav">
			{$req.links.all}
		</div>
		{else}
		<div>
			{$lang.pub_clew.nothing}
		</div>
		{/if}
	
	</div>
	<input type="hidden" name="cp" value="process" />
	<input type="hidden" name="opt" value="pre" />
	</form>

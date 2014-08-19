<div id="ajaxmessage" class="publc_clew" style="text-align:center;">{$req.title}</div>

<form method="post">
<div id="admin_msg">
	<div class="wrap">
		<div class="row">
			<div class="title">{$lang.msg.storename}</div>
			<div class="data">
				<select name="storeid">
				{foreach from=$req.input.stores item=row}
				<option value="{$row.StoreID}">{$row.bu_name}</option>
				{/foreach}
				</select>
			</div>
			<div class="clear"></div>
		</div>
		<div class="row">
			<div class="title">{$lang.msg.subject}</div>
			<div class="data"><input type="text" name="subject" size="50" class="inputbox" /></div>
			<div class="clear"></div>
		</div>
		<div class="row">
			<div class="title">{$lang.msg.message}</div>
			<div class="data">{$req.input.message}</div>
			<div class="clear"></div>
		</div>
		<div class="row">
			<div class="title">&nbsp;</div>
			<div class="data"><input type="checkbox" value="1" name="isouter" style="float:left; width:16px;height:16px; padding:0; margin:0;" /><div style="float:left; padding-left:6px; line-height:17px;">Forward to users's external email boxes as well.</div></div>
			<div class="clear"></div>
		</div>
		<div class="row">
			<div class="title">&nbsp;</div>
			<div class="data"><input type="submit" value="{$lang.msg.sendtoall}" class="hbutton" /></div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<input type="hidden" name="cp" value="process" />
<input type="hidden" name="opt" value="individual" />
</form>

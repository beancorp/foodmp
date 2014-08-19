<div id="ajaxmessage" class="publc_clew" style="text-align:center;">{$req.title}</div>
{literal}
<script type="text/javascript">
function preview()
{		
	$("#cp").val('reminderpreview');
	document.reminder_form.target = '_blank';
	document.reminder_form.action = '/soc.php?cp=reminderpreview';
	document.reminder_form.submit();
	
	return true;
}
function checkreminderform()
{		
	$("#cp").val('process');
	document.reminder_form.target = '_self';
	document.reminder_form.action = '';
	document.reminder_form.submit();
	
	return true;
}
</script>
{/literal}
<form method="post" name="reminder_form" action="">
<div id="admin_msg">
	<div class="wrap">
		<div class="row">
			<div class="title">{$lang.msg.subject}</div>
			<div class="data"><input type="text" name="subject" size="60" class="inputbox" value="Fresh Produce Report update reminder from SOC exchange" /></div>
			<div class="clear"></div>
		</div>
		<div class="row">
			<div class="title">{$lang.msg.message}</div>
			<div class="data">{$req.input.message}</div>
			<div class="clear"></div>
		</div>
		<div class="row">
			<div class="title">&nbsp;</div>
			<div class="data">
            	<input type="button" value="{$lang.msg.sendpreview}" onclick="return preview();" class="hbutton" /> &nbsp;
                <input type="button" value="{$lang.msg.sendreminder}" onclick="return checkreminderform();" class="hbutton" /></div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<input type="hidden" name="cp" id="cp" value="process" />
<input type="hidden" name="opt" id="opt" value="sendreminder" />
</form>

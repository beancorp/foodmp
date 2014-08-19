
	<form method="post">
	<div class="wrap">
		<div class="row">
			<div class="title">{$lang.msg.postedon}</div>
			<div class="data">{$req.data.date}</div>
			<div class="clear"></div>
		</div>
		<div class="row">
			<div class="title">To:</div>
			<div class="data">{$req.recipients}</div>
			<div class="clear"></div>
		</div>
		<div class="row">
			<div class="title">{$lang.msg.subject}</div>
			<div class="data">{$req.data.subject}</div>
			<div class="clear"></div>
		</div>
		<div class="row">
			<div class="title">{$lang.msg.message}</div>
			<div class="data">{$req.data.message}</div>
			<div class="clear"></div>
		</div>
		<div class="row">
			<div class="title">&nbsp;</div>
			<div class="data">
				<input type="button" value="{$lang.but.delete}" class="hbutton" onclick="{$req.js.delete}" />
				<input type="button" value="{$lang.but.back}" class="hbutton" onclick="{$req.js.back}" />
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<input type="hidden" name="cp" value="process" />
	<input type="hidden" name="opt" value="pre" />
	<input type="hidden" name="messageId" value="{$req.data.messageID}" />
	</form>

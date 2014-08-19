<link href="css/global.css" rel="stylesheet" type="text/css">
<div align="center" style="border-bottom-color:#999999;">
	<div id="ajaxmessage" class="publc_clew" style="height:50px; text-align:center">{$req.input.title}</div>
	<form id="mainForm" name="mainForm" method="post" action="" onSubmit="javascript:xajax_savePassword(xajax.getFormValues('mainForm')) ; return false;">
	<div id="input-table" style="width:720px;">

	<ul>
	<li id="lable" style="width:45%;">{$lang.main.lb_oldpass}</li>
	<li id="input2" style="width:53%;"><input name="oldpass" id="oldpass" type="text" size="30" maxlength="30" style="width:180px;" value="{$req.list.real}"></li>
	<li id="lable" style="width:45%;">{$lang.main.lb_newpass}</li>
	<li id="input2" style="width:53%;"><input name="newpass" id="newpass" type="text" size="30" maxlength="30" style="width:180px;" value="{$req.list.free}"></li><br>
	<li id="lable" style="width:45%;">{$lang.main.lb_confirmpass}</li>
	<li id="input2" style="width:53%;"><input name="confirmpass" id="confirmpass" type="text" size="30" maxlength="30" style="width:180px;" value="{$req.list.free}"></li><br>
	<li id="lable" style="width:45%;"></li>
	<li id="input" style="width:53%;">
		<input type="submit" class="hbutton" name="submitButton" value=" {$lang.but.submit} ">
	</li>
	</ul>
</div>
</form>
</div>
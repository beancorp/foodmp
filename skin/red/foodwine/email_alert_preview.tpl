<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet"/>
<link type="text/css" href="/skin/red/css/foodwine.css" rel="stylesheet"/>
{literal}
<style type="text/css">
p{ padding:3px 0; margin:0;font:12px arial,sans-serif; color:#777777; }
</style>
{/literal}
<form name="mainForm" action="/foodwine/?act=emailalerts" method="post">
<div style="margin:0">
<h1 class="soc-emailalerts">Your Email Alerts Preview</h1>
<div class="oper-emailalerts">
    <input type="hidden" name="cp" value="" />
    <input type="hidden" name="eid" value="{$req.info.id}" />
    <input type="hidden" name="type" value="{$req.info.send_type}" />
    <input type="hidden" name="StoreID" value="{$req.info.StoreID}" />
    <input type="image" border="0" value="Previous" class="preview-emailalerts" onclick="javascript:document.mainForm.cp.value='';" src="/skin/red/images/foodwine/edit-emailalerts.jpg" />
    <input type="image" border="0" value="Send Email Alerts" style="padding-right:0;" class="send-emailalerts" onclick="javascript:document.mainForm.cp.value='send';" src="/skin/red/images/foodwine/send-emailalerts.jpg" />
</div>
<div class="clear"></div>
<div style="background-color:#d8d9db; border:1px solid #CCCCCC; text-align:center">
	{include file='email_alert_content.tpl'}
</div>

<div class="oper-emailalerts" style="padding-top:10px;">
    <input type="image" border="0" value="Previous" class="preview-emailalerts" onclick="javascript:document.mainForm.cp.value='';" src="/skin/red/images/foodwine/edit-emailalerts.jpg" />
    <input type="image" border="0" value="Send Email Alerts" style="padding-right:0;" class="send-emailalerts" onclick="javascript:document.mainForm.cp.value='send';" src="/skin/red/images/foodwine/send-emailalerts.jpg" />
</div>
</div>
</form>
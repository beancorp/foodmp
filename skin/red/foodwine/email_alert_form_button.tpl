<form id="mainForm" name="mainForm" action="/foodwine/?act=emailalerts" method="post">
<input type="hidden" name="cp" value="" />
<input type="hidden" name="eid" value="{$req.info.id}" />
<input type="hidden" name="StoreID" value="{$req.StoreID}" />
<input type="image" border="0" value="Send Email Alerts" onclick="javascript:document.mainForm.cp.value='send';" src="/skin/red/images/foodwine/send-emailalerts.jpg" style="margin:15px 10px 30px 0; float:right; border:none" />
<input type="image" border="0" value="Previous" onclick="javascript:document.mainForm.cp.value='';" src="/skin/red/images/foodwine/previous-emailalerts.jpg" style="margin:15px 10px 30px 0; float:right; border:none" />
</form>
<link href="css/global.css" rel="stylesheet" type="text/css">
{literal}
<script language="javascript" type="text/javascript">
function checkForm(obj)
{
	xajax_saveEwayInfo(xajax.getFormValues('mainForm')) ;
	return false;
}
</script>
{/literal}
<div align="center" style="border-bottom-color:#999999;">
	<div id="ajaxmessage" class="publc_clew" style="height:50px; text-align:center">{$req.input.title}</div>
	<form id="mainForm" name="mainForm" method="post" action="" onSubmit="return checkForm(this);return false;">
	<div id="input-table" style="width:720px;">

	<ul>
	
	<li id="lable" style="width:45%;">{$lang.main.lb_eway_email}</li>
	<li id="input2" style="width:53%;"><input name="eway_email" id="eway_email" type="text" size="30" style="width:300px;" value="{$req.info.eway_customer_email}"></li>
	<li id="lable" style="width:45%;">{$lang.main.lb_eway_id}</li>
	<li id="input2" style="width:53%;"><input name="eway_id" id="eway_id" type="text" size="30" style="width:300px;" value="{$req.info.eway_customer_id}"></li>
	<li id="lable" style="width:45%;"></li>
	<li id="input" style="width:53%;">
		<input type="submit" class="hbutton" name="submitButton" value=" {$lang.but.submit} ">
	</li>
	</ul>
</div>
</form>
</div>
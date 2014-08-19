<link href="css/global.css" rel="stylesheet" type="text/css">
{literal}
<script language="javascript" type="text/javascript">
function checkForm(obj)
{
	if(obj.operator.value == '') {
		alert("Please enter the Username.");
	} else {
		xajax_savePaypalInfo(xajax.getFormValues('mainForm')) ;
	}
	
	return false;
}
</script>
{/literal}
<div align="center" style="border-bottom-color:#999999;">
	<div id="ajaxmessage" class="publc_clew" style="height:50px; text-align:center">{$req.input.title}</div>
	<form id="mainForm" name="mainForm" method="post" action="" onSubmit="return checkForm(this);return false;">
	<div id="input-table" style="width:720px;">

	<ul>
	<li id="lable" style="width:45%;">{$lang.main.lb_paypal_mode}</li>
	<li id="input2" style="width:53%;">
    	<input type="radio" {if $req.info.paypal_mode neq "1"}checked="checked"{/if} value="0" id="state" class="input-none-border" name="paypal_mode">		{$lang.main.lb_paypal_sandbox}
        <input type="radio"{if $req.info.paypal_mode eq "1"}checked="checked"{/if} value="1" id="state" class="input-none-border" name="paypal_mode">
        {$lang.main.lb_paypal_live}
    </li>
	<li id="lable" style="width:45%;">{$lang.main.lb_paypal_email}</li>
	<li id="input2" style="width:53%;"><input name="paypal_email" id="paypal_email" type="text" size="30" style="width:300px;" value="{$req.info.paypal_email}"></li>
	<li id="lable" style="width:45%;">{$lang.main.lb_paypal_app_id}</li>
	<li id="input2" style="width:53%;"><input name="paypal_app_id" id="paypal_app_id" type="text" size="30" style="width:300px;" value="{$req.info.paypal_app_id}"></li>
	<li id="lable" style="width:45%;">{$lang.main.lb_paypal_api_username}</li>
	<li id="input2" style="width:53%;"><input name="paypal_api_username" id="paypal_api_username" type="text" size="30" style="width:300px;" value="{$req.info.paypal_api_username}"></li>
	<li id="lable" style="width:45%;">{$lang.main.lb_paypal_api_password}</li>
	<li id="input2" style="width:53%;"><input name="paypal_api_password" id="paypal_api_password" type="text" size="30" style="width:300px;" value="{$req.info.paypal_api_password}"></li>
	<li id="lable" style="width:45%;">{$lang.main.lb_paypal_api_signature}</li>
	<li id="input2" style="width:53%;"><input name="paypal_api_signature" id="paypal_api_signature" type="text" size="30" style="width:300px;" value="{$req.info.paypal_api_signature}"></li>
	<li id="lable" style="width:45%;">&nbsp;</li>
	<li id="input2" style="width:53%;">&nbsp;</li>
	<li id="lable" style="width:45%;">Log Information:</li>
	<li id="input2" style="width:53%;"></li>
	<li id="lable" style="width:45%;">Username<font style="color:#FF0000">*</font></li>
	<li id="input2" style="width:53%;"><input name="operator" id="operator" type="text" size="30" style="width:300px;" value=""></li>
	<li id="lable" style="width:45%;">Comments</li>
	<li id="input2" style="width:53%;height:90px;"><textarea id="comments" name="comments" style="width: 300px; height: 80px;"></textarea></li>
	<li id="lable" style="width:45%;"></li>
	<li id="input" style="width:53%;">
		<input type="submit" class="hbutton" name="submitButton" value=" {$lang.but.submit} ">
	</li>
	</ul>
</div>
</form>
</div>
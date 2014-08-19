<link href="css/global.css" rel="stylesheet" type="text/css">
<div align="center" style="border-bottom-color:#999999;">
	<div id="ajaxmessage" class="publc_clew" style="height:50px; text-align:center">{$req.input.title}</div>
	<form id="mainForm" name="mainForm" method="post" action="" onSubmit="javascript:xajax_saveCommission(xajax.getFormValues('mainForm')) ; return false;">
	<div id="input-table" style="width:720px;">

	<ul>
	<li id="lable" style="width:45%;">{$lang.main.lb_commission_type}</li>
	<li id="input2" style="width:53%;">
    	<input type="radio" {if $req.info.commission_type neq "1"}checked="checked"{/if} value="0" id="state" class="input-none-border" name="commission_type">		{$lang.main.lb_commission_type_manual}
        <input type="radio"{if $req.info.commission_type eq "1"}checked="checked"{/if} value="1" id="state" class="input-none-border" name="commission_type">
        {$lang.main.lb_commission_type_automatic}
    </li>
	<li id="lable" style="width:45%;">{$lang.main.lb_commission_rate}</li>
	<li id="input2" style="width:53%;"><input name="commission_rate" id="commission_rate" type="text" size="30" maxlength="30" style="width:180px;" value="{$req.info.commission_rate}"></li>
	<li id="lable" style="width:45%;">{$lang.main.lb_commission_max}</li>
	<li id="input2" style="width:53%;"><input name="commission_max" id="commission_max" type="text" size="30" maxlength="30" style="width:180px;" value="{$req.info.commission_max}"></li><br>
	<li id="lable" style="width:45%;"></li>
	<li id="input" style="width:53%;">
		<input type="submit" class="hbutton" name="submitButton" value=" {$lang.but.submit} ">
	</li>
	</ul>
</div>
</form>
</div>
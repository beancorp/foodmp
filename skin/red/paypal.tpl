<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$req.paypal_title}</title>
<body onload="document.getElementById('paypal_form').submit();">
<form name="paypal_form" id="paypal_form" action="{$req.paypal_url}" method="post">
{foreach from=$req.paypal_info key=k item=paypalitem}
	{if $k neq 'submit'}
	<input type="hidden" name="{$k}" value="{$paypalitem}"/>
    {/if}
{/foreach}
</form>
</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$req.eway_title}</title>
<body onload="document.getElementById('ewayForm').submit();">
<form name="ewayForm" id="ewayForm" action="{$req.eway_url}" method="post">
{foreach from=$req.eway_info key=k item=ewayitem}
	{if $k neq 'submit'}
	<input type="hidden" name="{$k}" value="{$ewayitem}"/>
    {/if}
{/foreach}
</form>
</body>
</html>
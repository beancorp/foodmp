<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>The Food Marketplace Australia - Import Products error list</title>
<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
</head>
<body style="text-align:center">
<table id="myproducts" border="0" cellpadding="0" cellspacing="0" width="500" class="sortable">
<thead>
	<tr><th class="unsortable">#</th><th>Error Message</th></tr>
	</thead>
	{foreach from=$errlist item=el}
	<tr>
		<td>{$el.ID}</td>
		<td>
		{foreach from=$el.msg item=msgl}
			{$msgl}<br/>
		{/foreach}
		</td>
	</tr>
	{/foreach}
</table>
</body>
</html>

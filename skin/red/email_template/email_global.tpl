<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$req.Subject}</title>
</head>

<body>

<table width="839" border="0" align="center" cellpadding="0" cellspacing="0">

  
  <tr>
    <td width="839" height="175" scope="col">
    {if $req.foodwine_new}
    <img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/email_referer/foodwine-header.jpg"/>
    {else}
	<img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/email_referer/header{if $req.hide_padtop || $req.banner_best}1{/if}.jpg"/>
    {/if}
    </td>
  </tr>
  
  {if !$req.hide_padtop}
  <tr>
    <td width="839" height="25" scope="col">&nbsp;
		
	</td>
  </tr>
  {/if}
  
  <tr>
    <td>
		<table width="839" border="0" cellspacing="0" cellpadding="0">
      		<tr>
				<td width="140" scope="col">&nbsp;</td>
				<td width="559" scope="col">
					{$req.emailContents}
				</td>
				<td width="140" scope="col">&nbsp;</td>
			</tr>
		</table>
	</td>
  </tr>

   <tr>
    <td width="839" height="25" scope="col">&nbsp;
		
	</td>
  </tr>
  <tr>
    <td>
		<img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/email_referer/footer.jpg" height="42" width="839"/> 
	</td>
  </tr>
</table>
</body>
</html>
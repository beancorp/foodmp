<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$req.Subject}</title>
</head>

<body>
{if $req.preview}
{literal}
 <style type="text/css">
 	body {
		margin:0;
		padding:0;
		font-weight:normal;
	}
	p{font-weight:normal;}
 </style>
{/literal}
{/if}
<table width="{if $req.is_in_website}100%{else}839{/if}" border="0" align="center" cellpadding="0" cellspacing="0">
	{if $req.preview}
	<tr>
		<td width="839" scope="col" style="background-color:#777777;">
			<div style="height:15px; float:right; padding:5px;">
				<a style="color: rgb(255, 255, 255); font-size: 13px;font-family:Arial, Helvetica, sans-serif;" href="javascript:window.close();">Close</a>
			</div>
		</td>
  	</tr>
	{/if}
  
  {if !$req.is_in_website}
  <tr>
    <td width="839" height="175" scope="col">
	<img src="{$req.webside_link}/skin/red/images/email_referer/foodwine-header.jpg"/></td>
  </tr>
  {/if}
  
  <tr>
    <td><table width="{if $req.is_in_website}100%{else}839{/if}" border="0" cellspacing="0" cellpadding="0">
      <!--<tr>
        <th width="140" scope="col">&nbsp;</th>
        <th width="559" scope="col">
        	<div style=" border:none;">
            <p style="padding:0; margin:0; font-family:Arial, Helvetica, sans-serif;font-weight:normal; font-size:8px; border:none;">&nbsp;</p>
			<a href="{$req.webside_link}/soc.php?cp=race" title="Go to leaderboard" style=" border:none;"><img style=" border:none;" src="{$req.webside_link}/skin/red/images/email_referer/refer_email_banner.jpg"/></a>
            </div>
        </th>
        <th width="140" scope="col">&nbsp;</th>
      </tr>-->
      <tr>
        <th width="140" scope="col">&nbsp;</th>
        <th width="559" scope="col">
			<div align="left">
			  {$req.message}
		  </div>
        </th>
        <th width="140" scope="col">&nbsp;</th>
      </tr>
      
      
    </table></td>
  </tr>
  	{if !$req.is_in_website}
    <tr style="background-color:#A0CDF9;height:4px;">
    <td></td>
  </tr>
  <tr style="background-color:#7DB7F0;height:39px;">
    <td></td>
  </tr>
  {/if}
</table>
</body>
</html>

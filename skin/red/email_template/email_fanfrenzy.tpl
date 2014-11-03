<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$req.Subject}</title>
</head>

<body>

<table width="839" border="0" align="center" cellpadding="0" cellspacing="0">

  
  <tr>
    <td width="839" height="175" scope="col">
    <img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/email_referer/header.png"/>
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
    <td >
         <table width="839" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="660" height="42" style="background-color:#f3f3f5;">
                    <img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/email_referer/trans.gif" height="42" width="660"/>
                </td>
                <td width="139" style="background-color:#f3f3f5;">
                    <a href="{if $smarty.const.LANGCODE eq 'en-au'}http://foodmarketplace.com.au{else}http://foodmarketplace.com{/if}" style="text-decoration: none;color: #4f467b;font-weight: bold;">{if $smarty.const.LANGCODE eq 'en-au'}foodmarketplace.com.au{else}foodmarketplace.com{/if}</a>
                </td>
                <td width="40" height="42" style="background-color:#f3f3f5;">
                    <img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/email_referer/trans.gif" height="42" width="40"/>
                </td>
            </tr>
         </table> 
    </td>
  </tr>
</table>
</body>
</html>
<table width="595" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>

                      <td height="54" class="darkBlue" style="padding-left: 10px;"><strong>

                        {$req.itemsCount}
                      </strong> items found from your search<strong> </strong> </td>

                      <td align="right" style="padding-left: 10px;" class="Previewhead"><a class="redButt" href="search.php?{$req.urlparam}" linkindex="7">&lt;&lt;back to the mega-mall</a></td>

  </tr>
</table>		
<table width="585" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="images/10_level_bars.jpg" width="595" height="32" /></td>
  </tr>
</table>
<table width="595" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td width="50%" height="276" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      {foreach from=$req.category[0] item=catlist}
      <tr>
        <td width="33%" height="26" valign="bottom"><a href="soc.php?cp=searchpro&id={$catlist.id}&{$req.urlparam}"><strong>{$catlist.name}</strong></a>&nbsp;{*({$catlist.number})&nbsp;*}</td>
	  </tr>
	  {foreach from=$catlist.sublist item=scl}
      <tr>
        <td>&nbsp;&nbsp;<a href="soc.php?cp=searchpro&id={$scl.id}&{$req.urlparam}">{$scl.name}</a>{*&nbsp;({$scl.number})*}</td>
      </tr>
	  {/foreach}
      {/foreach}
    </table></td>
    <td width="50%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      {foreach from=$req.category[1] item=catlist}
      <tr>
        <td width="33%" height="26" valign="bottom"><a href="soc.php?cp=searchpro&id={$catlist.id}&{$req.urlparam}"><strong>{$catlist.name}</strong></a>&nbsp;{*({$catlist.number})&nbsp;*}</td>
      </tr>
      {foreach from=$catlist.sublist item=scl}
  <tr>
    <td>&nbsp;&nbsp;<a href="soc.php?cp=searchpro&id={$scl.id}&{$req.urlparam}">{$scl.name}</a>&nbsp;{*({$scl.number})*}</td>
  </tr>
      {/foreach}
      {/foreach}
    </table></td>
    <!--td width="33%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      {foreach from=$req.category[2] item=catlist}
      <tr>
        <td width="33%"><strong>{$catlist.name}</strong>&nbsp;({$catlist.number})&nbsp;</td>
	  </tr>
	  {foreach from=$catlist.sublist item=scl}
      <tr>
        <td>&nbsp;&nbsp;{$scl.name}&nbsp;({$scl.number})</td>
      </tr>
	  {/foreach}
      {/foreach}
    </table></td-->
  </tr>
</table>
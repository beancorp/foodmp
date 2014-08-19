<table width="595" border="0" cellpadding="0" cellspacing="2">
  <tr>
    <td width="50%" height="276" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      {foreach from=$req.category[0] item=catlist}
      <tr>
        <td width="33%" height="26" valign="bottom"><a href="soc.php?cp=prolist&id={$catlist.id}"><strong>{$catlist.name}</strong></a>&nbsp;{*({$catlist.number})&nbsp;*}</td>
	  </tr>
	  {foreach from=$catlist.sublist item=scl}
      <tr>
        <td>&nbsp;&nbsp;<a href="soc.php?cp=prolist&id={$scl.id}">{$scl.name}</a>&nbsp;{*({$scl.number})*}</td>
      </tr>
	  {/foreach}
      {/foreach}
    </table></td>
    <td width="50%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      {foreach from=$req.category[1] item=catlist}
      <tr>
        <td width="33%" height="26" valign="bottom"><a href="soc.php?cp=prolist&id={$catlist.id}"><strong>{$catlist.name}</strong></a>&nbsp;{*({$catlist.number})&nbsp;*}</td>
      </tr>
      {foreach from=$catlist.sublist item=scl}
  <tr>
    <td>&nbsp;&nbsp;<a href="soc.php?cp=prolist&id={$scl.id}">{$scl.name}</a>&nbsp;{*({$scl.number})*}</td>
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
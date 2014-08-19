{include_php file='include/jssppopup.php'}
<table width="585" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="595" height="32" border="0" align="center" cellpadding="0" cellspacing="0" background="images/soc_bar_bg.jpg">
  <tr>
    <td><span class="SplHeadingBar1">{$req.categoryName}</span></td>
    <td align="right" id="aboutLink" class=aboutLink><a href="javascript:history.back();">&lt;&lt;Back</a>&nbsp;</td>
  </tr>
</table>
<table width="585" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="585" border="0" align="center" cellpadding="0" cellspacing="0">
{foreach from=$req.product item=pl}
  <tr class="template_zi tdRowC2">
    <td width="347" height="25"> <strong>&nbsp;{$pl.name}</strong></td>
    <td width="238" align="right" class="template_zi">{if $pl.hasMore}<a href="soc.php?cp=searchpro&id={$pl.id}&{$req.urlparam}">More List... </a>{/if}</td>
  </tr>
  {foreach from=$pl.product item=spl}
  <tr>
    <td colspan="2" align="center"><table width="96%" border="0" cellspacing="0" cellpadding="3">
      <tr>
        <td height="2" colspan="3"><img src="" width="1" height="2" alt=""></td>
      </tr>
      <tr bgcolor="{if $spl.bgcHas}{/if}">
        <td width="106" height="110" rowspan="2" align="center" valign="middle" style="padding:5px;"><a href="productDispay.php?StoreID={$spl.StoreID}"><img border="0" src="{$spl.image_name.name}" width="100" /></a></td>
        <td width="293" height="25" valign="middle"><span class="specialName_blue"><a href="productDispay.php?StoreID={$spl.StoreID}"><strong>{$spl.item_name} </strong></a></span>		</td>
        <td width="145" rowspan="2" align="center">{if $spl.is_auction == 'yes'}<img src="images/auction_button.jpg" border="0" />{else}${$spl.price} {if $spl.non}{$lang.non[$spl.non]}{/if}{/if}</td>
      </tr>
      <tr bgcolor="{if $spl.bgcHas}{/if}">
        <td valign="top" class="TextJustify TextFS14">{$spl.description}{if $spl.descMore}&nbsp; <a style="display:inline" href="soc.php?cp=disprodes&amp;StoreID={$spl.urlParam}">more &gt;&gt;</a>{/if}</td>
      </tr>
    </table>
	<table width="96%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr class="tdRowC2">
			<td height="2"><img name="" src="" width="1" height="2" alt="" /></td>
		</tr>
      </table>
	  </td>
  {/foreach}  </tr>
  {if $pl.pagination}
  <tr>
    <td height="25" colspan="2" align="center">{$pl.pagination}</td>
  </tr>
  {/if}
  <tr>
    <td colspan="2"><img name="" src="" width="1" height="8" alt=""></td>
  </tr>
{/foreach}
</table>

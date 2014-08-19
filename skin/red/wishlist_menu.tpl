<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr align="center">
	
    <td width="15%"><a href="/soc.php?act=wishlist&step=1" class="anch"><img src="/skin/red/images/navimages/detail{$req.storeActive}.gif" border="0"></a></td>

    <td width="20%">{if $req.bulid}<a href="/soc.php?act=wishlist&step=2" class="anch">{/if}<img src="/skin/red/images/navimages/design_info{$req.chooseActive}.gif" border="0">{if $req.bulid}</a>{/if}</td>

    <td width="25%">{if $req.bulid && $req.enable}<a href="/soc.php?act=wishlist&step=3" class="anch">{/if}<img src="/skin/red/images/navimages/design_theme{$req.colorActive}.gif" border="0">{if $req.bulid && $req.enable}</a>{/if}</td>
    <td width="25%">
        <div style="vertical-align:top; padding-top:10px;font-family: Arial; font-size: 14px; font-weight: bolder; text-decoration:none;">{if $req.bulid && $req.enable}<a href="/soc.php?act=wishlist&step=preview" target="_blank" style="font-family: Arial; font-size: 14px; font-weight: bolder; text-decoration:none;">{/if}Preview{if $req.bulid && $req.enable}</a>{/if}</div>
    </td>
    <td width="20%" valign="top">
        <div style="vertical-align:top; padding-top:10px;font-family: Arial; font-size: 14px; font-weight: bolder; text-decoration:none;">{if $req.bulid && $req.enable}<a href="/soc.php?act=wishlist&step=4" style="font-family: Arial; font-size: 14px; font-weight: bolder; text-decoration:none;">{/if}Launch{if $req.bulid && $req.enable}</a>{/if}</div>
    </td>

  </tr>
  <tr align="center" valign="top">
	<td height="40" style="font-size:11px; height:50px; color:#777;">Choose Template</td>
    <td style="font-size:11px; height:50px; color:#777;">Setup Wishlist</td>
    <td style="font-size:11px; height:50px; color:#777;">Wishlist items</td>
    <td style="font-size:11px; height:50px; color:#777;">&nbsp;</td>
</tr>
</table>
{if $hide_template} 

{else}
	{if ($notLogin)}
	<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="steps">
	  <tr align="center">
		<td width="15%"><img src="/skin/red/images/navimages/detail_active.gif"  border="0"></td>
		<td width="20%"><img src="/skin/red/images/navimages/design_info.gif"  border="0"></td>
		<td width="25%"><img src="/skin/red/images/navimages/design_theme.gif" border="0"></td>
		<td width="20%"><img src="/skin/red/images/navimages/product.gif" border="0"></td>
		<td width="20%" rowspan="2" valign="top"><div style="vertical-align:top; padding-top:10px; font-family: Arial; font-size: 14px; font-weight: bolder; text-decoration:none;">{if $smarty.session.attribute == 0}View my website{else}Preview{/if}</div></td>
	</tr> 
		<tr align="center" valign="top">
		<td height="40" style="font-size:11px; height:50px; color:#777;">Website Details</td>
		<td style="font-size:11px; height:50px; color:#777;">{if $mainAttribute eq '0'}Details / Featured Image{else}Details{/if}</td>
		<td style="font-size:11px; height:50px; color:#777;">{if $mainAttribute eq '0'}Template / Colour / Icon{else}Template / Colour / Featured Image{/if}</td>
		<td style="font-size:11px; height:50px; color:#777;">Specify Product</td>
	</tr>
	</table>
	{else}
	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	{if $userlevel eq 1}	
	  <tr align="center">
		
		<td width="15%"><a href="{$soc_https_host}soc.php?act=signon" class="anch"><img src="/skin/red/images/navimages/detail{$req.storeActive}.gif" border="0"></a></td>
	 {if $smarty.session.attribute eq 3 && $smarty.session.subAttrib eq 3 }
	 
		 <td width="20%"><a href="{$soc_http_host}soc.php?act=signon&step={$req.product}" class="anch"><img src="/skin/red/images/navimages/design_info{$req.imageActive}.gif" border="0"></a></td>
		 
	 {else}

		<td width="20%"><a href="{$soc_https_host}soc.php?act=signon&step={$req.designinfo}" class="anch"><img src="/skin/red/images/navimages/design_info{$req.chooseActive}.gif" border="0"></a></td>

		<td width="25%"><a href="/soc.php?act=signon&step={$req.designtheme}" class="anch"><img src="/skin/red/images/navimages/design_theme{$req.colorActive}.gif" border="0"></a></td>

		<td width="20%"><a href="/soc.php?act=signon&step={$req.product}" class="anch"><img src="/skin/red/images/navimages/product{$req.imageActive}.gif" border="0"></a></td>
		
	 {/if}
	  
		<td width="20%" valign="top"><div style="vertical-align:top; padding-top:10px;"><a href="/{$smarty.session.urlstring}" style="font-family: Arial; font-size: 14px; font-weight: bolder; text-decoration:none;">{if $smarty.session.attribute == 0}View my website{else}Preview{/if}</a></div></td>

	  </tr>
	  {/if}
	  {if $userlevel eq 2}
	  <tr align="center">
		
		<td><a href="{$soc_https_host}soc.php?act=signon" class="anch"><img src="/skin/red/images/navimages/detail{$req.storeActive}.gif" border="0"></a></td>
		<td><img src="/skin/red/images/navimages/design_info.gif"  border="0"></td>
		<td><img src="/skin/red/images/navimages/design_theme.gif" border="0"></td>
		<td><img src="/skin/red/images/navimages/product.gif" border="0"></td>
		<td rowspan="2" valign="top"><div style="vertical-align:top; padding-top:10px; font-family: Arial; font-size: 14px; font-weight: bolder; text-decoration:none;">Preview</div></td>
	</tr>
	  {/if}
		<tr align="center" valign="top">
		<td height="40" style="font-size:11px; height:50px; color:#777;">Website Details</td>
		
		{if $smarty.session.attribute eq 3 && $smarty.session.subAttrib eq 3 }
			<td style="font-size:11px; height:50px; color:#777;">Specify Product</td>
		{else}
		<td style="font-size:11px; height:50px; color:#777;">{if $mainAttribute eq '0'}Details / Featured Image{else}Details{/if}</td>
		<td style="font-size:11px; height:50px; color:#777;">{if $mainAttribute eq '0'}Template / Colour / Icon{else}Template / Colour / Featured Image{/if}</td>
		
		<td style="font-size:11px; height:50px; color:#777;">Specify Product</td>
		{/if}
	</tr>
	</table>
	{/if}
{/if}
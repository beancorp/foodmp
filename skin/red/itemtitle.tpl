	{if $hasSOC}
		{if $hasRss == 'blog'}
<table width="595" height="32"  border="0" align="center" cellpadding="0" cellspacing="0" background="skin/red/images/soc_bar_bg.jpg">
	<tr>
		<td width="8" valign="middle"><img src="images/sp.gif" width="8" height="1" alt="spacer" /></td>
		<td width="311" class="SplHeadingBar1">{$itemImage}</td>
		<td width="43" align="right" valign="top"><a href="soc.php?cp=blogrss&amp;StoreID={$StoreID}" title="Soc RSS Blog" target="_blank"><img border="0" src="images/rss_logo.gif" width="41" height="29" alt="RSS Blog" /></a></td>
		<td width="88" align="left" valign="middle"><a href="soc.php?cp=blogrss&amp;StoreID={$StoreID}" style="font:Verdana, Arial, Helvetica, sans-serif;font-weight:bold;font-size:14px;color:#ffffff;">RSS BLOG</a></td>
		<td width="137" align="right" id="aboutLink"><a href="javascript:history.back();">&lt;&lt; Back</a></td>
		<td width="8"><img src="images/sp.gif" width="8" height="1" alt="spacer" /></td>
	</tr>
</table>
		{elseif $hasRss == 'items' }
<table width="595" height="32"  border="0" align="center" cellpadding="0" cellspacing="0" background="skin/red/images/soc_bar_bg.jpg">
	<tr>
		<td width="8" valign="middle"><img src="images/sp.gif" width="8" height="1" alt="spacer" /></td>
		<td width="257" class="SplHeadingBar1">{$itemImage}</td>
		<td width="46" align="right" valign="top"><a href="soc.php?cp=productrss&amp;StoreID={$StoreID}"><img src="images/rss_logo.gif" alt="RSS Items" width="41" height="29" border="0" /></a></td>
		<td width="101" align="left" valign="middle"><a href="soc.php?cp=productrss&amp;StoreID={$StoreID}" style="font:Verdana, Arial, Helvetica, sans-serif;font-weight:bold;font-size:14px;color:#ffffff;">RSS Items</a></td>
		<td width="175" align="right" id="aboutLink"><a href="javascript:history.back();">&lt;&lt; Back</a></td>
		<td width="8"><img src="images/sp.gif" width="8" height="1" alt="spacer" /></td>
	</tr>
</table>
		{else}
<table width="595" height="32"  border="0" align="center" cellpadding="0" cellspacing="0" background="skin/red/images/soc_bar_bg.jpg">
	<tr>
		<td width="8" valign="middle"><img src="images/sp.gif" width="8" height="1" alt="spacer" /></td>
		<td width="404" class="SplHeadingBar1">{$itemImage}&nbsp;</td>
		<td width="175" align="right" id="aboutLink"><a href="javascript:history.back();">&lt;&lt; Back</a></td>
		<td width="8"><img src="images/sp.gif" width="8" height="1" alt="spacer" /></td>
	</tr>
</table>
		{/if}
	{else}
<table width="595" height="32"  border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td width="8" valign="middle" class="title_ltcr"><img src="images/sp.gif" width="8" height="1" alt="spacer" /></td>
		<td valign="middle" class="title_bg SplHeadingBar1">{$itemImage}&nbsp;</td>
		<td width="8" class="title_rtcr"><img src="images/sp.gif" width="8" height="1" alt="spacer" /></td>
	</tr>
</table>
	{/if}
<table width="720" cellpadding="0" cellspacing="0" id="choosetemplate">
	<tr>
	<td width="33%" align="center" valign="top">
		<h3 style="font-size:20px; color:#666; font-weight:bold;">A</h3>
		<a href="/skin/red/auto/images/auto-c.jpg" rel=lightbox  ><img src="/skin/red/auto/images/auto-c-s.jpg" border="0" /></a>
	</td>
    {if ($req.info.product_feetype eq 'year' && ($req.info.product_renewal_date >= $cur_time)) || !$req.info.is_single_paid}
	<td width="33%" align="center" valign="top">
		<h3 style="font-size:20px; color:#666; font-weight:bold;">B</h3>
		<a href="/skin/red/auto/images/auto-a.jpg" rel=lightbox  ><img src="/skin/red/auto/images/auto-a-s.jpg" alt="" border="0" /></a>
	</td>
	<td width="33%" align="center" valign="top">
		<h3 style="font-size:20px; color:#666; font-weight:bold;">C</h3>
		<a href="/skin/red/auto/images/auto-b.jpg" rel=lightbox  ><img src="/skin/red/auto/images/auto-b-s.jpg" border="0" /></a>
	</td>
    {/if}
	<tr>
	<td align="center">
		<p style="text-align:center;">If you want to feature <strong>one item</strong><br />choose <strong>template A</strong></p>
	</td>
    {if ($req.info.product_feetype eq 'year' && ($req.info.product_renewal_date >= $cur_time)) || !$req.info.is_single_paid}
	<td align="center">
		<p style="text-align:center;">If you have a short banner,<br/> choose <strong>template B</strong></p>
	</td>
	<td align="center">
		<p style="text-align:center;">If you have a banner across the top, <br />choose <strong>template C</strong></p>
	</td>
    {/if}
	</tr>
	<tr>
	  <td height="25" align="center"><input type="radio" name="TemplateName" id="TemplateName" value="auto-c" {if $req.TemplateName eq 'auto-c'}checked{/if} /></td>
    {if ($req.info.product_feetype eq 'year' && ($req.info.product_renewal_date >= $cur_time)) || !$req.info.is_single_paid}
	  <td align="center"><input type="radio" name="TemplateName" id="TemplateName" value="auto-a" {if $req.TemplateName eq 'auto-a'}checked{/if} /></td>
      <td align="center"><input type="radio" name="TemplateName" id="TemplateName" value="auto-b" {if $req.TemplateName eq 'auto-b'}checked{/if} /></td>
   {/if}
  </tr>
</table>

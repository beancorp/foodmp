<table width="750" cellpadding="0" cellspacing="0" id="choosetemplate">
	<tr>
	<td width="33%" align="center" valign="top">
		<h3 style="font-size:20px; color:#666; font-weight:bold;">A</h3>
		<a href="/skin/red/estate/images/estate-c.jpg" rel=lightbox  >
	  <img src="/skin/red/estate/images/estate-c-s.jpg" alt="" width="200" height="202" /></a>	</td>
    {if ($req.info.product_feetype eq 'year' && ($req.info.product_renewal_date >= $cur_time)) || !$req.info.is_single_paid}
	<td width="33%" height="252" align="center" valign="top">
		<h3 style="font-size:20px; color:#666; font-weight:bold;">B</h3>
		<a href="/skin/red/estate/images/estate-a.jpg" rel=lightbox  >
	  <img src="/skin/red/estate/images/estate-a-s.jpg" alt="" width="200" /></a>	</td>
	<td width="33%" align="center" valign="top">
		<h3 style="font-size:20px; color:#666; font-weight:bold;">C</h3>
		<a href="/skin/red/estate/images/estate-b.jpg" rel=lightbox  >
	  <img src="/skin/red/estate/images/estate-b-s.jpg" alt="" width="200" /></a>	</td>
    {/if}
	</tr>
	<tr>
	<td align="center" valign="top">
		<p style="text-align:center;">If you want to feature <strong>one item</strong><br />choose <strong>template A</strong></p>
	</td>
    {if ($req.info.product_feetype eq 'year' && ($req.info.product_renewal_date >= $cur_time)) || !$req.info.is_single_paid}
	<td align="center" valign="top">
		<p style="text-align:center;">If you have a short banner,<br/> choose <strong>template B</strong></p>
	</td>
	<td align="center" valign="top">        
		<p style="text-align:center;">If you have a banner across the top, <br />choose <strong>template C</strong></p>
	</td>
    {/if}
	</tr>
	<tr>
	  <td height="25" align="center" valign="middle">
	  <input type="radio" name="TemplateName" id="TemplateName" value="estate-c" {if $req.TemplateName eq 'estate-c'}checked{/if} />
	  </td>
    {if ($req.info.product_feetype eq 'year' && ($req.info.product_renewal_date >= $cur_time)) || !$req.info.is_single_paid}
	  <td align="center" valign="middle">
	  <input type="radio" name="TemplateName" id="TemplateName" value="estate-a" {if $req.TemplateName eq 'estate-a'}checked{/if} />
	  </td>
	  <td align="center" valign="middle">
	  <input type="radio" name="TemplateName" id="TemplateName" value="estate-b" {if $req.TemplateName eq 'estate-b'}checked{/if} />
	  </td>
    {/if}
  </tr>
</table>

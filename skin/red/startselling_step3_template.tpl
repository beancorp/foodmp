<table width="720" cellpadding="0" cellspacing="0" id="choosetemplate">
	<tr>
	<td width="33%" align="center" valign="top">
		<h3 style="font-size:20px; color:#666; font-weight:bold;">A</h3>
		<a href="/skin/red/images/templateA-big.jpg" rel="lightbox"><img src="/skin/red/images/templateA.jpg" alt="" /></a>	</td>
	<td width="33%" align="center" valign="top">
		<h3 style="font-size:20px; color:#666; font-weight:bold;">B</h3>
		<a href="/skin/red/images/templateB-big.jpg" rel="lightbox"><img src="/skin/red/images/templateB.jpg" alt="" /></a>	</td>
	<td width="33%" align="center" valign="top">
		<h3 style="font-size:20px; color:#666; font-weight:bold;">C</h3>
		<a href="/skin/red/images/templateC-big.jpg" rel="lightbox"><img src="/skin/red/images/templateC.jpg" alt="" /></a>	</td>
	</tr>
	<tr>
	<td align="center" valign="top">
		<p style="text-align:center;">If you want to feature <strong>one product</strong><br />choose <strong>template A</strong></p>
	</td>
	<td align="center" valign="top">        
		<p style="text-align:center;">If you have a short banner,<br/> choose <strong>template B</strong></p>
	</td>
	<td align="center" valign="top">        
		<p style="text-align:center;">If you want to feature <strong> 6 products </strong> on your homepage choose <strong>template C</strong></p>
	</td>
	</tr>
	<tr>
	  <td align="center"><input type="radio" name="TemplateName" value="tmp-n-e" {if $req.TemplateName eq 'tmp-n-e'}checked{/if}  onclick="tmp_change(this.value)" /></td>
	  <td align="center"><input type="radio" name="TemplateName" value="tmp-n-a" {if $req.TemplateName eq 'tmp-n-a'}checked{/if} onclick="tmp_change(this.value)" /></td>
	  <td align="center"><input type="radio" name="TemplateName" value="tmp-n-b" {if $req.TemplateName eq 'tmp-n-b'}checked{/if} onclick="tmp_change(this.value)" /></td>
  </tr>
</table>

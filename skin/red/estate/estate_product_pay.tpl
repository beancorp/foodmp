<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<link type="text/css" href="/skin/red/css/swfupload_product.css" rel="stylesheet" media="screen" />

<p align="center" class="txt"><font style="color:red;">{$req.msg}</font></p>
<form name="mainForm" id="mainForm" method="post" action="/estate/?act=product&cp=pay">
<input name="pids" id="pids" type="hidden" value="{$req.pids}" />
<input name="step" type="hidden" value="{$req.step}" />
<input name="quantity" type="hidden" value="{$req.quantity}"/>
<input name="feetype" type="hidden" value="month"/>
<input name="op" type="hidden" value="pay"/>
<fieldset id="uploadproduct">  
<table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td height="30" width="5%" align="right">{$lang.tt.feetype} &nbsp;</td>
    <td>
    	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	  <tr>
		<td>
		   <p class="p12 bigger" style="padding:0; margin:0; font-size:12px;"><em>Listing your product now. It is only $10 per month.</em></p>
		   <p class="pl2" style="padding:0; margin:0; font-size:12px;">All State &amp; Federal taxes are included.</p>
		</td>
	  </tr>
		<tr>
		  <td align="right" valign="top" class="pad10px">
			
		  <table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#666666">
			<tr>
			  <td width="10%" height="30" align="center" bgcolor="#FFFFFF">$10</td>
			  <td width="11%" align="center" bgcolor="#FFFFFF">$20</td>
			  <td width="10%" align="center" bgcolor="#FFFFFF">$30</td>
			  <td width="10%" align="center" bgcolor="#FFFFFF">$40</td>
			  <td width="10%" align="center" bgcolor="#FFFFFF">$50</td>
			  <td width="10%" align="center" bgcolor="#FFFFFF">$60</td>
			</tr>
			<tr>
			  <td height="35" align="center" bgcolor="#FFFFFF">1<br />
				month</td>
			  <td align="center" bgcolor="#FFFFFF">2<br />
				months</td>
			  <td align="center" bgcolor="#FFFFFF">3<br />
				months</td>
			  <td align="center" bgcolor="#FFFFFF">4<br />
				months</td>
			  <td align="center" bgcolor="#FFFFFF">5<br />
				months</td>
			  <td align="center" bgcolor="#FFFFFF">6<br />
				months</td>
			</tr>
			<tr>
			  <td height="30" align="center" bgcolor="#FFFFFF"><input name="month" type="radio" value="1" checked="checked"/></td>
			  <td align="center" bgcolor="#FFFFFF"><input name="month" type="radio" value="2" /></td>
			  <td align="center" bgcolor="#FFFFFF"><input name="month" type="radio" value="3" /></td>
			  <td align="center" bgcolor="#FFFFFF"><input name="month" type="radio" value="4" /></td>
			  <td align="center" bgcolor="#FFFFFF"><input name="month" type="radio" value="5" /></td>
			  <td align="center" bgcolor="#FFFFFF"><input name="month" type="radio" value="6" /></td>
			</tr>
		  </table>
		  </td>
		</tr>
		<tr>
			<td><p  class="p12" style="padding:10px 0; margin:0; font-size:12px;">Simply click <strong>Pay Now to Payment</strong> to begin your listing. </p></td>
		</tr>
	</table>
	</td>
  </tr>
  <tr>
      <td height="30" >&nbsp;</td>
      <td><input name="SubmitPic" type="image" src="/skin/red/images/bu-paynow.gif" style="border:none;" onclick="javascript:document.mainForm.op.value='paynow';" value="Pay Now" border="0"/></td>
  </tr>
</table>
</fieldset>

</form>

<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.php" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>

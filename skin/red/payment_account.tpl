{literal}
<script language="javascript">
function checkForm(obj){
	try{
		RegExp.multiline=true;
		var errors 	=	'';
		
		if(obj.bu_address.value==''){
			errors += '-  Address is required.\n';
		}
		
		if(obj.bu_suburb.value==''){
			errors += '-  Suburb is required.\n';
		}
		
		if(obj.bu_state.value==''){
			errors += '-  State is required.\n';
		}
	
		if(obj.bu_postcode.value==''){
			errors += '-  Postcode is required.\n';
		}
	} catch(ex) {
		alert(ex);
	}

	if (errors!=''){
		errors = '-  Sorry, the following fields are required.\n'+errors;
		alert(errors);
		return false;
	} else {
		return true;
	}
}
</script>
{/literal}
<form name="personalDetail" id="personalDetail" method="post" action="/soc.php?cp=account_payment"  onsubmit="return checkForm(this)">
	<table width="100%"  border="0" cellspacing="6" cellpadding="">
	  <tr align="left" valign="middle">
		<td colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
		<td class="rightPadding"><div align="right">Address<span class="star">*</span>: </div></td>
		<td><input name="bu_address" type="text" class="inputB" id="bu_address" value="{$req.bu_address}" /></td>
	  </tr>
	  <tr>
		<td class="rightPadding"><div align="right">Suburb<span class="star">*</span>:</div></td>
		<td><input name="bu_suburb" type="text" class="inputB" id="bu_suburb" value="{$req.bu_suburb}" /></td>
	  </tr>
	  <tr>
		<td class="rightPadding"><div align="right">State<span class="star">*</span>: </div></td>
		<td>	
			<select id="bu_state" name="bu_state" style="width: 275px;">
				{foreach from=$state_list key=k item=v}
				   <option value="{$v.id}" {if $v.id eq $req.bu_state}selected="selected"{/if}>{$v.description}</li>
				{/foreach}
			</select>
		</td>
	  </tr>
	  <tr>
		<td height="25" class="rightPadding"><div align="right">Post Code<span class="star">*</span>:</div></td>
		<td><input name="bu_postcode" type="text" class="inputB" id="bu_postcode" value="{$req.bu_postcode}" /></td>
	  </tr>
	  <tr align="left" valign="middle">
		<td colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
		<td colspan="2">
			<input type="hidden" name="OrderID" value="{$req.OrderID}" />
			<input type="hidden" name="amount" value="{$req.amount}" />
			<input type="hidden" name="shipping" value="{$req.shipping}" />
			<input type="hidden" name="total_money" value="{$req.total_money}" />
			<input type="hidden" name="price" value="{$req.price}" />
			<input type="hidden" name="quantity" value="{$req.quantity}" />
		</td>
	  </tr>
	  <tr align="center">
		<td colspan="2"><input name="Submit" type="image" src="/skin/red/images/buttons/or-paynow.gif" value="Confirm Purchase" /></td>
	  </tr>
	  <tr>
		<td colspan="2">&nbsp;</td>
	  </tr>
	</table>
  </form>
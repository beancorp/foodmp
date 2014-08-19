{literal}
<script language="javascript">
function checkForm(obj){
	try{
		RegExp.multiline=true;
		var errors 	=	'';

		
		if(obj.firstName.value==''){
			errors += '-  First Name is required.\n';
		}
	
		if(obj.lastName.value==''){
			errors += '-  Last Name is required.\n';
		}
	
		if(obj.cardNumber.value==''){
			errors += '-  Card Number is required.\n';
		}
	
		if(obj.address1.value==''){
			errors += '-  Address is required.\n';
		}
		
		if(obj.city.value==''){
			errors += '-  City is required.\n';
		}
		
		if(obj.state.value==''){
			errors += '-  State is required.\n';
		}
	
		if(obj.postcode.value==''){
			errors += '-  Post code is required.\n';
		}
		
		if(obj.email.value==''){
			errors += '-  Email is required.\n';
		}
	}catch(ex){
		alert(ex);
	}

	if(errors!=''){
		errors = '-  Sorry, the following fields are required.\n'+errors;
		alert(errors);
		return false;
	}else{
	  return true;
	}

}

</script>
{/literal}
<form name="personalDetail" id="personalDetail" method="post" action="soc.php?cp=credit"  onsubmit="return checkForm(this)">
                    <table width="100%"  border="0" cellspacing="6" cellpadding="">
                      <tr align="center">
                        <td colspan="2" class="Previewhead">&nbsp;</td>
                      </tr>
                      <tr align="center">
                        <td colspan="2" class="Previewhead"><strong>Your Personal Details </strong></td>
                      </tr>
                      <tr>
                        <td width="30%" valign="top">&nbsp;</td>
                        <td width="60%" align="left" valign="top">&nbsp;</td>
                      </tr>
                      <tr align="left" valign="middle">
                        <td height="25" colspan="2" bgcolor="#E8EFF7"  ><strong>Credit Card Details</strong></td>
                      </tr>
                      <tr>
                        <td valign="top" class="rightPadding"  >&nbsp;</td>
                        <td valign="top" >&nbsp;</td>
                      </tr>
                      <tr>
                        <td valign="top" class="rightPadding"  ><div align="right">First Name (as it appears on card)<span class="star">*</span>: </div></td>
                        <td valign="top" ><input name="firstName" type="text" class="inputB" id="firstName" value="" /></td>
                      </tr>
                      <tr>
                        <td valign="top" class="rightPadding"  style="padding-left:10px; "><div align="right">Last Name (as it appears on card)<span class="star">*</span>: </div></td>
                        <td valign="top" class="Previewhead" ><input name="lastName" type="text" class="inputB" id="lastName" /></td>
                      </tr>
                      <tr>
                        <td class="rightPadding"><div align="right">Card Type<span class="star">*</span>: </div></td>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="30%" valign="middle"><select name="cardType" class="select" id="cardType">
                                <option value="1" {if $req.payment == 'visa'}selected{/if}>VISA</option>
                                <option value="2" {if $req.payment == 'mastercard'}selected{/if}>MasterCard</option>
                                <option value="4" {if $req.payment == 'discover'}selected{/if}>Discover</option>
                              </select></td>
                              <td width="70%" valign="bottom"><img src="../../images/payment_mastercard.gif" width="37" height="23" border="0"/> <img src="../../images/payment_visa.gif" width="37" height="23" border="0"/> <img src="../../images/payment_discover.gif" width="36" height="23" /></td>
                            </tr>
                          </table>
                            </td>
                      </tr>
                      <tr>
                        <td align="right" class="rightPadding">Card Number<span class="star">*</span>:</td>
                        <td><input name="cardNumber" type="text" class="inputB" id="cardNumber" /></td>
                      </tr>
                      <tr>
                        <td align="right" class="rightPadding">Expiry Date<span class="star">*</span>:</td>
                        <td>                          <select name="month" class="select" style="width:100px;" id="select2">

						{$req.month}
                          </select>
                        <select name="year"  class="select" style="width:100px;" id="year">
						{$req.year}
                        </select></td>
                      </tr>
                      <tr align="left" valign="middle">
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      <tr align="left" valign="middle" bgcolor="#E8EFF7">
                        <td height="25" colspan="2"><p align="left"><strong>Delivery address: </strong></p></td>
                      </tr>
                      <tr>
                        <td class="rightPadding">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="rightPadding"><div align="right">Address 1<span class="star">*</span>: </div></td>
                        <td><input name="address1" type="text" class="inputB" id="address1" /></td>
                      </tr>
                      <tr>
                        <td class="rightPadding"><div align="right">Address 2<span class="star"> &nbsp;</span>: </div></td>
                        <td><input name="address2" type="text" class="inputB" id="address2" /></td>
                      </tr>
                      <tr>
                        <td class="rightPadding"><div align="right">Town/City<span class="star">*</span>:</div></td>
                        <td><input name="city" type="text" class="inputB" id="city" /></td>
                      </tr>
                      <tr>
                        <td class="rightPadding"><div align="right">State<span class="star">*</span>: </div></td>
                        <td><input name="state" type="text" class="inputB" id="state" /></td>
                      </tr>
                      <tr>
                        <td height="25" class="rightPadding"><div align="right">Post Code<span class="star">*</span>:</div></td>
                        <td><input name="postcode" type="text" class="inputB" id="postcode" /></td>
                      </tr>
                      <tr align="left" valign="middle">
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      <tr align="left" valign="middle" bgcolor="#E8EFF7">
                        <td height="25" colspan="2"><strong>Contact Information </strong></td>
                      </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2">This information will only be used to contact you regarding your payment, if needed. </td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="rightPadding" align="right">Email Address<span class="star">*</span>: </td>
                        <td><input name="email" type="text" class="inputB" id="email" size="40" /></td>
                      </tr>
                      <tr>
                        <td class="rightPadding" align="right">Home Telephone: </td>
                        <td><input name="phone" type="text" class="inputB" id="phone4" size="25" /></td>
                      </tr>
                      <tr align="left" valign="middle">
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2">
							<input type="hidden" name="amount" value="{$req.amount}" />
							<input type="hidden" name="price" value="{$req.price}" />
							<input type="hidden" name="postage" value="{$req.postage}" />
							<input type="hidden" name="quantity" value="{$req.quantity}" />
							<input type="hidden" name="pid" value="{$req.pid}">
							<input type="hidden" name="StoreID" value="{$req.StoreID}">
							<input type="hidden" name="ref_id" value="{$req.ref_id}">
						</td>
                      </tr>
                      <tr align="center">
                        <td colspan="2"><input name="Submit" type="image" src="skin/red/images/buttons/or-paynow.gif" value="Confirm Purchase" /></td>
                      </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                    </table>
                  </form>
{literal}
<script type="text/javascript">
<!--//
function checkEmpty(id, field)
{
	if (document.getElementById(id).value == '')
	{
		alert('Please fill in ' + field + '.');
		document.getElementById(id).focus();
		return false;
	}
	return true;
}

function checkType(id, field, type)
{
	var Exp;
	switch (type)
	{
		case 'Email':
			Exp = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
			break;
	}
	if (!Exp.test(document.getElementById(id).value))
	{
		alert('Invalid ' + field + ', please try another.');
		document.getElementById(id).focus();
		return false;
	}
	return true;
}

function checkForm()
{
	var validate;
	
	validate = !checkEmpty('bu_abn', 'First Name') ? false :
	           !checkEmpty('bu_name', 'Last Name') ? false :
	           !checkEmpty('Address', 'Address') ? false :
	           !checkEmpty('Suburb', 'City / Suburb') ? false :
	           !checkEmpty('State', 'State') ? false :
	           !checkEmpty('Postcode', 'Post Code') ? false :
	           !checkEmpty('Country', 'Country') ? false :
	           !checkEmpty('Email', 'Email') ? false :
			   !checkType('Email', 'Email', 'Email') ? false :
	           !checkEmpty('Phone', 'Phone') ? false :
	           !checkEmpty('Email', 'Email') ? false :
	           !checkEmpty('Comments', 'Comments') ? false :
	           !checkEmpty('validation', 'validation') ? false : true;
	
	return validate;
}
//-->
</script>
{/literal}
<form name="formcu" id="formcu" method="post" onsubmit="return checkForm()">
<table>
	<tr>
		<td colspan="2">Do you have a question or comment? Simply complete your details below and click 'submit'.</td>
	</tr>
	<tr>
		<td>
		<table id="table14" cellspacing="4" cellpadding="0" border="0">
            {if $msg ne ''}
            <tr>
                <td colspan="2" style="color:red;">{$msg}</td>
            </tr>
            {/if}
			<tr align="left">
				<td align="right"><span class="formlable"><span class="lbl">First Name</span></span><span class="redmark">*</span></td>
				<td>
					<span class="style11"><font face="Verdana" size="1">
					<input name="First_Name" type="text" class="inputB" id="bu_abn" value="{$tmpval.First_Name}" size="30" />
					</font></span>
				</td>
			</tr>
			<tr align="left">
				<td align="right"><span class="formlable"><span class="lbl">Last Name</span></span><span class="redmark">*</span></td>
				<td>
					<span class="style11"><font face="Verdana" size="1"> <font face="Verdana" size="1">
					<input name="Last_Name" type="text" class="inputB" id="bu_name" value="{$tmpval.Last_Name}" size="30" />
					</font> <font face="Verdana" size="1"> </font> </font></span>
				</td>
			</tr>
			<tr align="left" valign="top">
				<td align="right"><span class="formlable"><span class="lbl">Address</span></span><span class="redmark">*</span></td>
				<td><span class="style11"><font face="Verdana" size="1">
				<textarea name="Address" cols="30" rows="5" class="inputB" id="Address" >{$tmpval.Address}</textarea>
				</font></span></td>
			</tr>
			<tr align="left">
				<td align="right"><span class="formlable"><span class="lbl">City/ Suburb</span></span><span class="redmark">*</span></td>
				<td><span class="style11"><font face="Verdana" size="1"> <font face="Verdana" size="1">
				<input name="Suburb" type="text" class="inputB" id="Suburb" value="{$tmpval.Suburb}" size="30" />
				</font><font face="Verdana" size="1"> </font> </font></span></td>
			</tr>
			<tr align="left">
				<td align="right"><span class="formlable"><span class="lbl">State</span></span><span class="redmark">*</span></td>
				<td><span class="style11"><font face="Verdana" size="1"> <font face="Verdana" size="1"><font face="Verdana" size="1">
				<input name="State" type="text" class="inputB" id="State" value="{$tmpval.State}" size="30" />
				</font></font> </font></span></td>
			</tr>
			<tr align="left">
				<td align="right"><span class="formlable"><span class="lbl">Post Code</span></span><span class="redmark">*</span></td>
				<td><span class="style11"><font face="Verdana" size="1"> <font face="Verdana" size="1">
				<input name="Postcode" type="text" class="inputB" id="Postcode" value="{$tmpval.Postcode}" size="30" />
				</font></font></span></td>
			</tr>
			<tr align="left">
				<td align="right"><span class="formlable"><span class="lbl">Country</span></span><span class="redmark">*</span></td>
				<td><span class="style11"><font face="Verdana" size="1"> <font face="Verdana" size="1">
				<input name="Country" type="text" class="inputB" id="Country" value="{$tmpval.Country}" size="30" />
				</font></font></span></td>
			</tr>
			<tr align="left">
				<td align="right"><span class="formlable"><span class="lbl">Email</span></span><span class="redmark">*</span></td>
				<td><span class="style11"><font face="Verdana" size="1"> <font face="Verdana" size="1">
				<input name="Email" type="text" class="inputB" id="Email" value="{$tmpval.Email}" size="30" />
				</font></font></span></td>
			</tr>
			<tr align="left">
				<td align="right"><span class="formlable"><span class="lbl">Phone</span></span><span class="redmark">*</span></td>
				<td><span class="style11"><font face="Verdana" size="1"> <font face="Verdana" size="1">
				<input name="Phone" type="text" class="inputB" id="Phone" value="{$tmpval.Phone}" size="30" />
				</font></font></span></td>
			</tr>
			<tr align="left" valign="top">
				<td align="right"><span class="formlable"><span class="lbl">Comments</span></span><span class="redmark">*</span></td>
				<td><span class="style11"><font face="Verdana" size="1">
				<textarea name="Comments" cols="30" rows="5" class="inputB" id="Comments">{$tmpval.Comments}</textarea>
				</font></span></td>
			</tr>
			<tr align="left">
              <td align="right"><span class="formlable"><span class="lbl">Validation Code </span></span><span class="redmark">*</span></td>
			  <td >
			  <table align="left" cellpadding="0" cellspacing="0">
			  	<tr>
				<td width="44px">
                <input name="validation" type="text" class="inputB" id="validation" value="" size="4" maxlength="4"  style="width:40px;"/></td>
				<td> <span style="background:url(authimg.php) no-repeat center center;float:left; width:70px; height:22px; "></span></td><td>&nbsp;</td>
				</tr>
			  </table>
			  </td>
		  </tr>
			<tr align="left">
				<td>&nbsp; </td>
				<td><input name="imageField" type="image" src="skin/red/images/buttons/or-submit.gif" border="0" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>

		</table>		
		</td>
	</tr>
</table>

</form>
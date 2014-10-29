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
	//!checkEmpty('bu_name', 'Name') ? false :
	//!checkEmpty('Phone', 'Phone') ? false :
	validate = 
			   !checkType('Email', 'Email', 'Email') ? false :
	           
	           !checkEmpty('Email', 'Email') ? false :
	           !checkEmpty('Comments', 'Comments') ? false :
			   !checkEmpty('validation', 'Validation Code') ? false : true;
	
	return validate;
}
//-->
</script>
{/literal}
<form name="formcu" id="formcu" method="post" onsubmit="return checkForm();">
<div style="margin:10px 0 0 0; width:100%;">&nbsp;</div>
<table width="100%" cellpadding="0" cellspacing="0" >

	{if $msg ne ''}
	<tr>
		<td colspan="2" style="color:red;">{$msg}</td>
	</tr>
	{/if}
	<tr>
		<td>
		<table id="table14" cellspacing="4" cellpadding="0" border="0" style="margin:10px 0 0 0;">
			<tr align="left">
				<td align="right"><span class="formlable"><span class="lbl">Type</span></span><span class="redmark">*</span></td>
				<td>
					<select name="type" id="type" class="select">	
						{foreach from=$type item=val}
							<option value="{$val}" {if $tmpval.type eq $val} selected="selected"{/if}>{$val}</option>
						{/foreach}
					</select>
				</td>
			</tr>
			<!--<tr align="left">
				<td align="right"><span class="formlable"><span class="lbl">Username</span></span><span class="redmark">*</span></td>
				<td>
					<span class="style11"><font face="Verdana" size="1"> <font face="Verdana" size="1">
					<input name="bu_Name" type="text" class="inputB" id="bu_name" value="{$tmpval.bu_Name}" size="30" />
					</font> <font face="Verdana" size="1"> </font> </font></span>				</td>
			</tr>-->
			<tr align="left">
				<td align="right"><span class="formlable"><span class="lbl">User Name</span></span><span class="redmark">&nbsp;</span></td>
				<td>
					<span class="style11"><font face="Verdana" size="1"> <font face="Verdana" size="1">
					<input name="nickName" type="text" class="inputB" id="nick_name" value="{$tmpval.nickName}" size="30" />
					</font> <font face="Verdana" size="1"> </font> </font></span>				</td>
			</tr>
			<tr align="left">
				<td align="right"><span class="formlable"><span class="lbl">Contact Email</span></span><span class="redmark">*</span></td>
				<td><span class="style11"><font face="Verdana" size="1"> <font face="Verdana" size="1">
				<input name="Email" type="text" class="inputB" id="Email" value="{$tmpval.Email}" size="30" />
				</font></font></span></td>
			</tr>
			<tr align="left">
				<td align="right"><span class="formlable"><span class="lbl">Phone</span></span><span class="redmark">&nbsp;</span></td>
				<td><span class="style11"><font face="Verdana" size="1"> <font face="Verdana" size="1">
				<input name="Phone" type="text" class="inputB" id="Phone" value="{$tmpval.Phone}" size="30"  />
				</font></font></span></td>
			</tr>
			<tr align="left" valign="top">
				<td align="right"><span class="formlable"><span class="lbl">Comments</span></span><span class="redmark">*</span></td>
				<td><span class="style11"><font face="Verdana" size="1">
				<textarea name="Comments" cols="30" rows="20" class="inputB" id="Comments" >{$tmpval.Comments}</textarea>
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
		<td valign="top">
		<table >
			<tr>
				<td>&nbsp; </td>
				<td class="tip2">
				<p class="bigger"><em style="color:#74ace1; text-decoration:underline;"></em></p>
				<!--<img src="./images/logo-new.png" alt="">-->
                <span style="display: block; width: 170px; margin-top: 10px; line-height: 20px; class="txt-for-media">For media enquiries please contact: <a style="color:#1c1bea;text-decoration: underline;" href="mailto:media@foodmarketplace.com">media@foodmarketplace.com</a>.</span>
				</td>
			</tr>
		</table>		
		</td>
		
	</tr>
</table>
</form>
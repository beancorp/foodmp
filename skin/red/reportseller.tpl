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
	
	validate = !checkEmpty('bu_name', 'Your Name') ? false :
			   !checkType('Email', 'Email', 'Email') ? false :
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
				<td align="right">
                    <span class="formlable"><span class="lbl">Seller Name</span></span>
                </td>
				<td>
                    <input type="text" class="inputB" value="{$req.store.bu_name}" size="30" disabled='disabled' style="background:#ebeae5"/>
                </td>
			</tr>
			<tr align="left" style="display:none;">
				<td align="right">
                    <span class="formlable"><span class="lbl">Seller Email</span></span>
                </td>
				<td>
                    <input type="text" class="inputB" value="{$req.store.bu_email}" size="30" disabled='disabled' style="background:#ebeae5"/>
                </td>
			</tr>
            {if $req.store.phone_hide eq 0}
			<tr align="left" style="display:none;">
				<td align="right">
                    <span class="formlable"><span class="lbl">Seller Phone</span></span>
                </td>
				<td>
                    <input type="text" class="inputB" value="{$req.store.bu_area} {$req.store.bu_phone}" size="30" disabled='disabled' style="background:#ebeae5"/>
                </td>
			</tr>
            {/if}
			<tr align="left">
				<td align="right">
                    <span class="formlable">
                        <span class="lbl">Your Name</span></span>
                </td>
				<td>
					<span class="style11">
                        <font face="Verdana" size="1">
                            <font face="Verdana" size="1">
                                <input name="your_name" type="text" class="inputB" id="bu_name" value="{$tmpval.your_name}" size="30" disabled='disabled' style="background:#ebeae5"/>
                            </font>
                            <font face="Verdana" size="1"> </font>
                        </font>
                    </span>
                </td>
			</tr>
			<tr align="left">
				<td align="right"><span class="formlable"><span class="lbl">Contact Email</span></span></td>
				<td><span class="style11"><font face="Verdana" size="1"> <font face="Verdana" size="1">
				<input name="Email" type="text" class="inputB" id="Email" value="{$tmpval.Email}" size="30" disabled='disabled' style="background:#ebeae5"/>
				</font></font></span></td>
			</tr>
			<tr align="left" valign="top">
				<td align="right"><span class="formlable"><span class="lbl">Comments</span></span><span class="redmark">*</span></td>
				<td><span class="style11"><font face="Verdana" size="1">
				<textarea name="Comments" cols="30" rows="20" class="inputB" id="Comments">{$tmpval.Comments}</textarea>
				</font></span></td>
			</tr>
			<tr align="left">
              <td align="right"><span class="formlable"><span class="lbl">Validation Code </span></span><span class="redmark">*</span></td>
			  <td >
			  <table align="left" cellpadding="0" cellspacing="0">
			  	<tr>
				<td width="44px">
                <input name="validation" type="text" class="inputB" id="validation" value="" size="4" maxlength="4"  {if $nologin eq 'no'}disabled='disabled' style="background:#ebeae5; width:40px;"{else} style="width:40px;" {/if}/></td>
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
        {include file='flashbanner.tpl'}
		<table >
			<tr>
				<td>&nbsp; </td>
			  <td class="tip2"><p class="bigger"><em style="color:#74ace1; text-decoration:underline;">{$smarty.const.SITENAME}</em></p>
                P.O.Box 472<br />
                Double Bay, NSW 2028<br />
                Australia<br/>
				Email Address: <a style="color:#74ace1;" href="mailto:reportseller@{$smarty.const.EMAIL_DOMAIN}">reportseller@{$smarty.const.EMAIL_DOMAIN}</a></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>
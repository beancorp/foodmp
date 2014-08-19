{if $req.offer.display eq ''}
<script language="javascript">
{literal}
function selectContact(obj){
	if (obj.options[obj.selectedIndex].value == 'email'){
		document.getElementById('divEmail').style.display = '';
		document.getElementById('divPhone').style.display = 'none';
	}else{
		document.getElementById('divEmail').style.display = 'none';
		document.getElementById('divPhone').style.display = '';
	}
}
{/literal}
</script>
<div id="ajaxmessage" class="text" style="text-align:center;color:red;">{$req.offer.msg}</div>
<form id="mainForm" name="mainForm" method="post" action="" onSubmit="xajax_offerReview(xajax.getFormValues('mainForm')); return false;">
<div id="tabledatalist"><table width="100%" border="0" cellpadding="2">
  <tr>
    <td width="26%" height="25">&nbsp;</td>
    <td width="74%">&nbsp;</td>
  </tr>
  <tr>
    <td height="25" align="right" valign="middle">{$lang.obo.ttContact} : </td>
    <td><select name="contact" onchange="selectContact(this);">
      <option value="email" selected="selected">email</option>
      <option value="phone">phone</option>
    </select>    </td>
  </tr>
  <tr id="divPhone" style="display:none;">
    <td height="25" align="right" valign="middle">{$lang.obo.ttPhone} : </td>
    <td><input name="phone" type="text" id="phone" size="30" maxlength="25" /></td>
  </tr>
  <tr id="divEmail">
    <td height="25" align="right" valign="middle">{$lang.obo.ttEmail} : </td>
    <td><input name="email" type="text" id="email" size="30" maxlength="50" /></td>
  </tr>
  <tr>
    <td height="25" align="right" valign="top">{$lang.obo.ttComment} : </td>
    <td><textarea name="comment" cols="60" wrap="physical"></textarea></td>
  </tr>
  <tr>
    <td height="25" align="right">&nbsp;</td>
    <td><input name="id" type="hidden" id="id" value="{$req.offer.id}" />
        <input name="UserID" type="hidden" id="UserID" value="{$req.offer.UserID}" />
        <input name="submit" type="image" style="border:none" src="/skin/red/images/buttons/or-submit.gif" value="{$lang.but.submit}"/></td>
  </tr>
</table>
</div>
<input name="pageno" type="hidden" id="pageno" value="{$req.offer.pageno}"/>
</form>
{elseif $req.offer.display eq 'error'}
<br />

<div class="publc_clew" style="text-align:center;">Failed. <br><br>{$req.offer.msg}</div>
{/if}
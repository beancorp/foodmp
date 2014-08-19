{literal}
<script language="javascript">
function confirmpass(){

	if(document.getElementById("cpass").value!=document.getElementById("new_password").value){

		alert("New password and confirm password are not matching.");

		return false;

	}

}
</script>
{/literal}
<table width="70%" border="0" align="center" cellpadding="2" cellspacing="2" >

                                  {if $req.msg ne ''}

                                  <tr align="center" bgcolor="#FFFFEA">

                                    <td height="20" colspan="5"  class="redrequired">
                                      {$req.msg}                                  
                                       </td>

                                  </tr>

                                  {/if}

                                  <tr align="center" bgcolor="#F8F8F8">

                                    <td height="20" colspan="5">&nbsp;</td>

                                  </tr>

                                  <form action="" method="post" name="formcng" id="formcng" onsubmit="return confirmpass()" >

                                  <tr>

                                    <td align="right">Current Password:</td>

                                    <td>&nbsp;</td>

                                    <td><input name="passwords" type="password" class="inputBox" id="passwords" value="{$req.Password}" /></td>

                                    <td>&nbsp;</td>

                                    <td>&nbsp;</td>

                                  </tr>

                                  <tr>

                                    <td align="right">New Password:</td>

                                    <td>&nbsp;</td>

                                    <td><input name="new_password" type="password" class="inputBox" id="new_password" /></td>

                                    <td>&nbsp;</td>

                                    <td>&nbsp;</td>

                                  </tr>

                                  <tr>

                                    <td align="right">Confirm Password </td>

                                    <td>&nbsp;</td>

                                    <td><input name="cpass" type="password" class="inputBox" id="cpass" /></td>

                                    <td>&nbsp;</td>

                                    <td>&nbsp;</td>

                                  </tr>

                                  <tr>

                                    <td align="right">&nbsp;</td>

                                    <td>&nbsp;</td>

                                    <td><input type="image" value="submit" src="skin/red/images/buttons/or-submit.gif"></td>

                                    <td>&nbsp;</td>

                                    <td>&nbsp;</td>

                                  </tr></form>

                              </table>
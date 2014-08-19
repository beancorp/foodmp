{literal}<script language="javascript">
function closePopupLogin(){
	window.opener.location.href('customers_geton.php');
	window.close() ;
}

function checkForm(obj){
	var errors	=	'';

	obj.subject.value=='' ? (errors += '-  Subject is required.\n') : '' ;
	obj.body.value=='' ? (errors += '-  Message is required.\n') : '' ;

	if (errors != ''){
		errors = '-  Sorry, the following fields are required.\n' + errors;
		alert(errors);
		return false;
	}else{
		return true;
	}
}
</script>
<style type="text/css">
<!--
.STYLE1 {
	color: #FF0000;
	font-weight: bold;
}

.upfile{ width:287px;}
-->
</style>
{/literal}
<h1 class="itemTitle">Send Message</h1>
<div style="padding-top:40px;">
{if $req.einfo.LOGIN eq ''}

<CENTER>You need to be a member of <span class="STYLE1">Food Marketplace Australia</span> to use this service.
  <br>
  <span class="STYLE1">It's FREE</span>. To register now <a href="soc.php?cp=customers_geton&ctm=1" target="_blank">Click Here</a>.
</CENTER>
{else}

<form action="" method="post" enctype="multipart/form-data" id="startselling" style="margin:0px; padding:0px;" onSubmit="return checkForm(this);"> 
<CENTER><B>You are now going to send an email to {$req.einfo.StoreName}</B><BR>
<span style="color:#F00">{$req.einfo.msg}</span>
<input type="hidden" name="fromEmail" value="{$req.einfo.emailaddress}"/>
<table width="365" >
<tr>
<td width="156" align="right" class="text" >Your Name *</td>
<td align="left" ><input name="fromName" type="text" class="inputBox" value="
{if $req.einfo.NickName}{$req.einfo.NickName}{else}{$req.einfo.UserName}{/if}" /></td></tr>
<tr>
<td width="156" align="right" class="text" >Subject *</td>
<td align="left" >
	<input name="subject" type="text" class="text" value="{if $req.einfo.subject}{$req.einfo.subject}{/if}" />
</td></tr>
<tr>
<td width="156" align="right" valign="top" class="text">Message *</td>
<input type="hidden" name="StoreID" value="{if $req.einfo.StoreID}{$req.einfo.StoreID}{/if}">
<td rowspan="2" align="left"><textarea name="body" rows="8" cols="60"></textarea></td>
</tr>
<tr align="right">
  <td width="156" class="text">&nbsp;</td>
  </tr>
  <input type="hidden" value="sendemail" name="sendemail"/>
 <tr>
 	<td></td>
 	<td align="left"><input type="image" style="border:none; margin-left:-3px; padding-top:8px;" src="/skin/red/images/buttons/or-submit.gif" value="Send"/></td>
 </tr>
</table>
</CENTER></form>

{/if}
</div>
<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<script language="javascript" src="/skin/red/js/uploadImages.js"></script>
{if $req.display eq ''}
<form id="imageForm" name="imageForm" method="post" action="" onsubmit="javascript: return checkImageForm(this); " enctype="multipart/form-data">

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="100%" align="center" valign="middle"><table width="100%" height="22" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td style="color:#FF0000;">&nbsp;</td>
      </tr>
    </table>
    <table width="680" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#CCCCFF">
  <tr>
    <td height="32" colspan="3" align="left" bgcolor="#CCCCFF"><span class="itemTitle"><strong> &nbsp;&nbsp;Upload  Images</strong></span> &nbsp;&nbsp;(Supported image type are jpg, gif, png)</td>
  </tr>
  <tr>
    <td width="16%" height="37" align="center" bgcolor="#F5F8FE"><span class="style4 style6">Image</span></td>
    <td width="68%" align="center" bgcolor="#F5F8FE"><input name="upfiles" type="file" class="clsTextField" id="upfiles" size="60" /></td>
    <td width="16%" align="center" bgcolor="#F5F8FE"><input name="Submit" type="image" src="/skin/red/images/buttons/or-upload.gif" class="hbutton"/></td>
  </tr>
  
  <tr>
    <td height="33" colspan="3" align="center" bgcolor="#F5F8FE" class="error" id="message">{$req.msg}</td>
  </tr>
</table></td>
  </tr>
</table>
<input name="cp" type="hidden" id="cp" value="save" />
<input name="tpltype" type="hidden" id="tpltype" value="{$req.tpltype}" />
<input name="attrib" type="hidden" id="attrib" 	 value="{$req.attrib}" />
<input name="index" type="hidden" id="index" 	 value="{$req.index}" />
<input name="objImage" type="hidden" id="edit" 	 value="{$req.objImage}" />
</form>
{elseif $req.display eq 'save'}
	{if $req.valueDis neq ''}
	<script language="javascript">
	
		{literal}try{{/literal}
		
		window.opener.document.getElementById('{$req.objImage}_dis').src 		= '/{$req.valueDis}';
		window.opener.document.getElementById('{$req.objImage}_dis').width		=	{$req.valueDisW};
		window.opener.document.getElementById('{$req.objImage}_dis').height		=	{$req.valueDisH};
		
		window.opener.document.getElementById('{$req.objImage}_svalue').value 	= '/{$req.valueSmall}';
		window.opener.document.getElementById('{$req.objImage}_bvalue').value 	= '/{$req.valueBig}';
		window.close();
		{literal}}catch(ex){}{/literal}
	
	</script>
	{else}
	
	{/if}
{/if}
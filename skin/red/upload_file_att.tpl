<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<script>
{literal}
function checkImageForm(obj){
	RegExp.multiline=true;
	try{
		var errors	=	'';
		if(obj.upfiles.value==''){
			errors += 'File is required.';
		}
		if(errors!=''){
			$('#message').html(errors);
			return false;
		}else{
			return true;
		}
		
	}catch(ex){alert(ex);}
}
{/literal}
</script>
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
    <td height="32" colspan="3" align="left" bgcolor="#CCCCFF"><span class="itemTitle"><strong> &nbsp;&nbsp;Upload  File</strong></span> &nbsp;&nbsp;(Supported file type are mp3, mov, avi, jpg, png, gif, pdf, exe, zip, rar, wma, doc, xls, txt, ppt, swf, fla)</td>
  </tr>
  <tr>
    <td width="16%" height="37" align="center" bgcolor="#F5F8FE"><span class="style4 style6">File</span></td>
    <td width="68%" align="center" bgcolor="#F5F8FE"><input name="upfiles" type="file" class="clsTextField" id="upfiles" size="60" /></td>
    <td width="16%" align="center" bgcolor="#F5F8FE"><input name="Submit" type="image" src="/skin/red/images/buttons/or-upload.gif" class="hbutton"/></td>
  </tr>
  
  <tr>
    <td height="33" colspan="3" align="center" bgcolor="#F5F8FE" class="error" id="message">{$req.msg}</td>
  </tr>
</table></td>
  </tr>
</table>
<input name="cp" type="hidden" id="cp" value="attachment" />
<input name="opt" type="hidden" id="opt" value="save" />
</form>
{elseif $req.display eq 'save'}
	<script language="javascript">
		window.opener.document.getElementById('uploadfiles').innerHTML 		= '{$req.filelinks}';
		window.opener.document.getElementById('att_filename').value 		= '{$req.filename}';
		window.opener.document.getElementById('att_fileurl').value 			= '{$req.fileurl}';
		window.opener.document.getElementById('att_fopt').value 			= 0;
		window.opener.document.getElementById('att_filenewname').value 		= '{$req.filenewname}';
		window.close();
	</script>
{/if}
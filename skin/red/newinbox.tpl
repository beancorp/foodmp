<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
{literal}  
	<script>
	  function del(s)
	  {
	  	if(s==1){
			var e = $('input[@name="delchk[]"][@checked][@alt=1]');
		}else if(s==2){
			var e = $('input[@name="delchk[]"][@checked][@alt=2]');		
		}
		var t = e.length ;
		if(t>0)
		{ if(confirm('Do you want to delete the records?')) {  return true }  else { return false ; }  }
		else
		{  alert('Please select the boxes.') ;  return false ;}
	  } //
		</script>
		{/literal}
 </p>

 <form name="form1" id="form1" method="post" action="" onsubmit="return del(1);">
<table width="742" border="0" cellspacing="0" cellpadding="0">  
{if $req.inbox ne ''}
  <tr>
	<td width="11%" style="font-weight:bold;">Alert</td>
	<td width="55%" style="font-weight:bold;">Subject</td>
	<td width="12%" style="font-weight:bold;">Date Received</td>
	<td width="11%" style="font-weight:bold;">Attachment</td>
	<td width="11%" style="font-weight:bold;">Action</td>
  </tr>
	{foreach from=$req.inbox item=inbox key=id}
  <tr>
	<td height="25"><input alt="1" name="delchk[]" type="checkbox" value="{$inbox.messageID}" />&nbsp;{$id+1}.{if $inbox.Status eq 0}<font color=blue>new</font>{/if}&nbsp;</td>
	<td>&nbsp;<a href="#" onclick="javascript:window.open('/showmsg.php?msgid={$inbox.messageID}','MGS','width=600,height=400,scrollbars=yes,status=yes')">{if $inbox.subject neq ''}{$inbox.subject} {else}No subject{/if}</a></td>
	<td>&nbsp;{$inbox.date}</td>
	<td>&nbsp;{$inbox.attachmentStatus}</td>
	<td align="left">&nbsp;<a href="#" onclick="javascript:window.open('/emailstoresubmit.php?cp=transmit&msgid={$inbox.messageID}','MGS','width=600,height=400,scrollbars=yes,status=yes');" title="Forward to My External Mailbox">Forward</a></td>
  <tr>
  {/foreach}
	<td height="35" colspan="5"><input name="submit" type="image" src="/skin/red/images/buttons/bu-delete.gif" value="Delete" /></td>
	</tr>
	<tr>
	 <td colspan="5"><div align="center">{$req.linkStr1}</div></td>
	</tr>

	{else}
	<tr><td colspan="5"><BR><BR>There are no message in your inbox</td></tr>
	{/if}
</table>
</form>				

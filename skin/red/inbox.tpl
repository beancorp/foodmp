<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
{literal}  
<style>
	.tabtmp{
		list-style:none;
		margin:0;
		float:left;
	}
	.tabtmp li{
		list-style:none;
		width:auto;
		height:40px;
		padding:0px 20px 0px 20px;
		line-height:40px;
		text-align:center;
		float:left;
		font-weight:bold;
	}
	.tabtmp li.active_tab{
		background-color:#9E99C1;
	}
</style>
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
  function saveOuterEmail(val,StoreID){
	if(!StoreID){
                if($("#bid_message_2")) {
                    $("#bid_message_2").html('<br/>Please login to your account to place a bid. <a href="/soc.php?cp=customers_geton&ctm=1">You can Register for free to bid on this auction.</a>').show();
                }
                else {
                    alert('Please login to your account to place a bid. You can Register for free to bid as a buyer.');
                    location.href='/soc.php?cp=login';
                }
                return 0;
	}
	val = val?1:0;
	$.getJSON('/include/jquery_svr.php',{svr:"saveOuterEmail",outerEmail:val,StoreID:StoreID},function(data){
		if (data.status == 'on'){
			$('#outerEmail').attr("checked",true);
			$('#outerEmail').val(1);
			$('#outerEmailClew').html('turned on');
		}else if(data.status == 'off'){
			$('#outerEmail').attr("checked",false);
			$('#outerEmail').val(0);
			$('#outerEmailClew').html('turned off');
		}else{
			$('#outerEmailClew').html('failed');
		}
	});
  }
		</script>
		{/literal}
 </p>
{if $req.msg ne ''}
<div class="publc_clew" style="height:30px;">{$req.msg}</div>
{/if}

    <div style="background-color: rgb(238, 238, 238); width:750px; height: 40px; margin: 0pt 0px; border-bottom: 1px solid rgb(238, 238, 238);">
 	<ul class="tabtmp">
        <li ><a href="/emailAlerts.php" style="font-weight:bold;text-decoration:none; line-height:45px; display:block;">Send Email Alerts</a></li>
    	<li title="Send an email alert of your listed items, to your subscribers now!"><a href="/soc.php?cp=customers_geton_alerts" style="font-weight:bold;text-decoration:none; line-height:45px;">My Email Alerts</a></li>
		{if !($smarty.session.attribute eq 3 && $smarty.session.subAttrib eq 3)}
		<li style="padding-left:0px;padding-right:0px;">
		<div style="padding-top:10px;float:left;" title="all mails forwarded to your external email address"><img src="/skin/red/images/adminhome/icon-email-forward.gif" align="absmiddle" height="20">&nbsp;&nbsp;Forward messages to my email address&nbsp;&nbsp;<input name="outerEmail" type="checkbox" id="outerEmail" value="{$smarty.session.outerEmail}" {if $smarty.session.outerEmail eq 1}checked{/if} onclick="saveOuterEmail(this.checked,{$smarty.session.ShopID|default:0})">
		</div>
		<div id="outerEmailClew" style="float:left; color:red; height:19px;padding-top:12px; margin-left:5px;"></div>
		</li>
		{/if}
    </ul>
    <div style="clear: both;"></div>
            </div>
<h2 class="adminTitle">Alert Inbox</h2>
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

					

					<BR><BR>
					<h2 class="adminTitle">Alert Outbox</h2>

					<table width="742" border="0" cellspacing="0" cellpadding="0">  

 <form name="form1" id="form1" method="post" action="" onsubmit="return del(2);">

   
					{if $req.outbox ne ''}         

                              <tr>
                                <td width="11%" style="font-weight:bold;">Alert</td>
                                <td width="55%" style="font-weight:bold;">Subject</td>
                                <td width="34%" style="font-weight:bold;">Date Sent</td>
                              </tr>
                          {foreach from=$req.outbox item=outbox key=id}
							<tr>
                                <td height="25"><input alt="2" name="delchk[]" type="checkbox" value="{$outbox.messageID}" />&nbsp;{$id+1}</td>
                                <td>&nbsp;<a href="#" onclick="javascript:window.open('showoutmsg.php?msgid={$outbox.messageID}','MGS','width=600,height=400,scrollbars=yes,status=yes')">{if $outbox.subject neq ''}{$outbox.subject} {else}No subject{/if}</a></td>
                                <td>&nbsp;{$outbox.date}</td>
                              </tr>
                          {/foreach}

                              <tr>
                                <td height="35" colspan="3"><input name="submit"  type="image" src="/skin/red/images/buttons/bu-delete.gif" value="Delete" /></td>
                              </tr>
							  <tr>
                                 <td colspan="3"> 
			 	<div align="center">
			 	{$req.linkStr2}				</div>								 </td>
                                </tr>
						  
                                {else}
        						<BR><BR>There are no message in your outbox
        						{/if}
</form>				
</table>

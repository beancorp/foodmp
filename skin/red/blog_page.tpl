{literal}
<script language="JavaScript" type="text/JavaScript">
function checkForm(obj){
	var boolResult	=	false;
	try{
		if(obj.content.value != ''){
			boolResult	=	true;
		}
		if(!boolResult){
			alert("Please input content then submit.");
		}
	}catch(ex){
		alert(ex);
	}
	return boolResult;
}

var bshow = true;
function showOrHide(){
	try{
		if(bshow){
			//divshow.style.display = 'block';
			document.getElementById('divimg').style.display = 'block';
			document.getElementById('linkChange').innerHTML="Hide";
			//divhide.style.display = 'none';
		}else{
			//divshow.style.display = 'none';
			document.getElementById('divimg').style.display = 'none';
			document.getElementById('linkChange').innerHTML="More Images";
			//divhide.style.display = 'block';
		}
		bshow = !bshow;
	}catch(ex){
		alert(ex);
	}
}

function confirmDelete(bid,cid){
	if (confirm("Are you sure to delete the comment?")){
		location.href="soc.php?cp=bcomment&act=del&StoreID={/literal}{$req.StoreID}{literal}&bid="+bid+"&cid="+cid;
	}
}

function confirmDeleteReply(bid,cid){
	if (confirm("Are you sure to delete the reply?")){
		location.href="soc.php?cp=reply&act=del&StoreID={/literal}{$req.StoreID}{literal}&bid="+bid+"&cid="+cid;
	}
}

function checkReply(obj){
	var boolResult	=	false;
	try{
		if(obj.reply.value != ''){
			boolResult	=	true;
		}
		if(!boolResult){
			alert("Please input you reply then submit.");
		}
	}catch(ex){
		alert(ex);
	}
	return boolResult;
}

</script>
{/literal}
<p align="center" class="txt"><font color="#FF0000">{$req.select.msg}</font></p>
<table id="blogpage_view" width="96%" align="center" border="0" cellpadding="0" cellspacing="2">
  <tr>
    <td valign="top"><table id="blogpage_view_table" width="100%" border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td height="26" colspan="2" align="left" valign="bottom"><font class="blogTitle"><strong>{$req.subject}</strong></font></td>
	  </tr>
	  <tr>
        <td height="26" align="left" valign="top">{$req.nickname} &nbsp;&nbsp;</td>
	    <td height="26" align="right" valign="top"> {$req.modify_date}</td>
	  </tr>
      <tr>
        <td id="blogpage_view_text" bgcolor="#f5f5f5" valign="top" width="*" height="*" style="text-align:justify">
			<div name="divhide" id="divhide" style="display:none">{$req.subContent}</div>
			<div name="divshow" id="divshow" style="display:block;">{$req.content}</div>
		</td>
        <td id="blogpage_view_logos" width="30%" align="center" valign="top" rowspan="2" bgcolor="#f5f5f5">
			<div id="topimg">{if $req.image1 != ''}<img align="baseline" valign="top" width="200" src="{$req.image1}" />{/if}</div><br />
			<div id="divimg" style="display:none">
				{if $req.image2 != ''}<img align="baseline" valign="top" width="150" src="{$req.image2}" /><br /><br />{/if}
				{if $req.image3 != ''}<img align="baseline" valign="top" width="150" src="{$req.image3}" /><br /><br />{/if}
				{if $req.image4 != ''}<img align="baseline" valign="top" width="150" src="{$req.image4}" /><br /><br />{/if}
			</div>
		</td>
      </tr>
      <tr>
        <td bgcolor="#f5f5f5" height="30" align="right" valign="center">{if $req.more ==  'true'}<a id="linkChange" href="javascript:showOrHide()">More Images</a>{/if}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$req.edit}&nbsp;&nbsp;{$req.del}&nbsp;&nbsp;&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="50%" height="20" valign="top" align="left"><br /><font class="blogComment"><strong>Comments</strong></font> &nbsp;{if $req.login=='false'}(<font color="#FF0000">Login now to enter a comment</font>){/if}<br />
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  {foreach from=$req.comment item=commentlist key=key}
	  <tr>
        <td height="20" colspan="2"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td height="1" bgcolor="#666666"><img src="images/spacer.gif" width="1" height="1" /></td>
		</tr>
      </table></td>
	  </tr>
      <tr>
        <td height="26" valign="top"><strong>{if $commentlist.username neq ''}{$commentlist.username}{else}{$lang.tit.anonymous}{/if}</strong></td>
	    <td align="right" valign="top">{$commentlist.post_date}</td>
      </tr>
      <tr>
        <td colspan="2" style="text-align:justify">{$commentlist.content}</td>
      </tr>
	  <tr>
        <td colspan="2" align="right">{if $req.isOwner == true}{if $commentlist.approved == "0"}<a href="soc.php?cp=bcomment&act=appr&StoreID={$req.StoreID}&bid={$req.blog_id}&cid={$commentlist.comment_id}"><img src="skin/red/images/buttons/or-approve.gif" border="0" /></a>{/if}&nbsp;<a href="javascript:confirmDelete({$req.blog_id},{$commentlist.comment_id})"><img src="skin/red/images/buttons/bu-delete.gif" border="0" /></a>
          &nbsp;&nbsp;&nbsp;{if $commentlist.approved == "1" and $commentlist.reply == ""}
          <a href="soc.php?cp=reply&amp;bid={$req.blog_id}&amp;cid={$commentlist.comment_id}"><img src="skin/red/images/buttons/or-reply.gif" border="0" /></a>
          {/if}&nbsp;{/if}</td>
      </tr>
	  <tr>
        <td height="20" colspan="2">
{if $commentlist.reply != ''}
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="30" rowspan="2">&nbsp;</td>
    <td width="*" bgcolor="#f5f5f5"><strong>Reply from Seller</strong><br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="63%" style="text-align:justify">&nbsp;</td>
    <td width="37%" align="right">{$commentlist.reply_date}</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:justify">{$commentlist.reply}</td>
    </tr>
</table></td>
  </tr>
  <tr>
    <td bgcolor="#f5f5f5" align="right">{if $req.isOwner == true and $commentlist.reply != ''}
      <a href="javascript:confirmDeleteReply({$req.blog_id},{$commentlist.comment_id})"><img src="skin/red/images/buttons/bu-delete.gif" border="0" /></a>&nbsp;&nbsp;&nbsp;&nbsp;{/if}</td>
  </tr>
</table>
{/if}</td>
	  </tr>
      {/foreach}
    </table></td>
  </tr>
  <tr>
    <td height="" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><div id="paging-wide"  style="background:#{$templateInfo.bgcolor}">&nbsp;{$req.navi}</div></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">
	{if $req.addComment == 'true'}
	<table width="100%" border="0" cellspacing="1" cellpadding="0"><form onsubmit="return checkForm(this);" action="soc.php?cp=bcomment" method="post" enctype="application/x-www-form-urlencoded" name="blogcomment" target="_self">
  <tr>
    <td align="center">
      <textarea name="content" cols="60" rows="8" style=" border:solid #ccc 1px;float:none" id="content"></textarea><br /><br />
      <a name="addComment"></a>

      
  	  <input type="hidden" name="act" value="new" />
	  <input type="hidden" name="StoreID" value="{$req.StoreID}" />
	  <input type="hidden" name="bid" value="{$req.blog_id}" />    </td>
  </tr>
  <tr>
    <td align="center"><input name="submit" type="image" src="skin/red/images/buttons/or-addcomment.gif" id="submit" value="Add Comment" /></td>
  </tr>
	</form>
</table>
	{/if}
</td>
  </tr>
</table>
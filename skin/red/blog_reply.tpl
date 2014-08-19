{literal}
<script language="JavaScript" type="text/JavaScript">
function checkForm(obj){
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
<p align="center" class="txt"><font color="#FF0000">{$req.msg}</font></p>
<table style="border:none;" width="80%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left">Add Reply</td>
  </tr>
  <tr>
    <td align="left"><form onsubmit="return checkForm(this);" action="soc.php?cp=reply" method="post" enctype="application/x-www-form-urlencoded" name="blogreply" target="_self">
      <textarea name="reply" cols="60" rows="8" class="inputB" style="width:350px;"></textarea><br /><br />
      
      <input name="submit" type="image" src="skin/red/images/buttons/or-addreply.gif" id="submit" value="Add Reply" />

	  <input type="hidden" name="act" value="new" />
	  <input type="hidden" name="StoreID" value="{$req.StoreID}" />
	  <input type="hidden" name="cid" value="{$req.cid}" />
	  <input type="hidden" name="bid" value="{$req.bid}" />
    </form></td>
  </tr>
</table>
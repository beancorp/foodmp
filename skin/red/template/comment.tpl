{literal}
<script language=javascript>
function checkForm()
{
	if (document.reviews.message.value == '')
	{
		alert('Message is required.');
		return false;
	}
	return true;
}
</script>
{/literal}

<div style="font-weight: bold; font-size: 14px; float:left;">{$req.storename}</div>
<div style="clear:both; color:#FF0000; padding:20px 0 20px 0;">{$msg}</div>

<form name="reviews" action="soc.php?cp=comment" method=post onsubmit="return checkForm();">
<div>
	<div style="float:left; width:100px; text-align:right; padding-right:5px;">Message</div>
	<div><textarea rows="5" cols="50" name="message" class="inputB textarea"></textarea></div>
</div>
<div style="padding:10px 0 0 105px;">
	<input type="submit" class="or_submit" value="" name="submit">
    <input type="reset" class="or_reset" value="" name="reset" />
	<input type="hidden" name="user_id" value="{$req.user_id}" />
	<input type="hidden" name="StoreID" value="{$req.StoreID}" />
	<input type="hidden" name="storename" value="{$req.storename}" />
	<input type="hidden" name="type" value="{$req.type}" />
	<input type="hidden" name="rid" id="rid" value="{$req.rid}" />
</div>
</form>

<div style="padding:20px 0 10px 105px;">If you see incorrect or inappropriate content on this page please <a href="soc.php?cp=contact" style="color:#80B0DE; text-decoration:none;">click here</a> to report.</div>

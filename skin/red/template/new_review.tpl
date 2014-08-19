{literal}
<script language=javascript>
function checkForm(){
	var enable=0;
	var length = document.reviews.elements.length;
	for (var x=0; x<length; x++) {
		if (document.reviews.elements[x].name.indexOf("rating") != -1) {
			if (document.reviews.elements[x].checked==true)
				enable=1;
		}  
	}
	if (enable==0) {
		alert('No rating selected.');
		return false;
	}
	if(document.reviews.message.value==''){
		alert("Message is required.");
		return false;
	}
	return true;
}
</script>
{/literal}

<div style="font-weight: bold; font-size: 14px; float:left;">{$req.storename}</div>
<div style="float:right; padding:0 30px 0 0;"><a href="/{$req.storeurl}" style="text-decoration:none; color:#80B0DE;">&lt;&lt;&nbsp;Back to Shop </a></div>
<div style="clear:both; color:#FF0000; padding:20px 0 20px 0;">{$msg}</div>

<form name="reviews" action="soc.php?cp=newreview" method=post onsubmit="return checkForm();">
<div>
	<div style="float:left; width:100px; text-align:right; padding-right:5px;">Rating</div>
	<div>
		<label for="rating_1"><input type="radio" name="rating" value="1" /><img src="images/star_1.gif" width="17" height="17" border=0 align="absmiddle" /></label>
		<label for="rating_2"><input type="radio" name="rating" value="2" /><img src="images/star_2.gif" width="34" height="17" border="0" align="absmiddle" /></label>
		<label for="rating_3"><input type="radio" name="rating" value="3" /><img src="images/star_3.gif" width="51" height="17" border="0" align="absmiddle" /></label>
		<label for="rating_3_5"><input type="radio" name="rating" value="3.5" /><img src="images/star_3.5.gif" width="68" height="17" border="0" align="absmiddle" /></label>
		<label for="rating_4"><input type="radio" name="rating" value="4" /><img src="images/star_4.gif" width="68" height="17" border="0" align="absmiddle" /></label>
		<label for="rating_4_5"><input type="radio" name="rating" value="4.5" /><img src="images/star_4.5.gif" width="83" height="17" border="0" align="absmiddle" /></label>
		<label for="rating_5"><input type="radio" name="rating" value="5" /><img src="images/star_5.gif" width="83" height="17" border="0" align="absmiddle" /></label>
	</div>
</div>
<div>
	<div style="float:left; width:100px; text-align:right; padding-right:5px;">Status</div>
	<div>
		<label for="status_1"><input name="status" type="radio" value="1" checked="checked" />Purchase completed</label>
		<label for="status_0"><input type="radio" name="status" value="0" />Purchase  not completed</label>
	</div>
</div>
<div>
	<div style="float:left; width:100px; text-align:right; padding-right:5px;">Message</div>
	<div><textarea rows="15" cols="50" name="message" class="inputB textarea">{$message}</textarea></div>
</div>
<div style="padding:10px 0 0 105px;">
	<input name="submit" type="submit" value="" class="or_submit" /> 
	<input name="reset" type="reset"  value="" class="or_reset" />
	<input type="hidden" name="user_id" value="{$req.user_id}" />
	<input type="hidden" name="StoreID" value="{$req.StoreID}" />
	<input type="hidden" name="storename" value="{$req.storename}" />
	<input type="hidden" name="type" value="{$req.type}" />
	<input type="hidden" name="reviewkey" value="{$req.reviewkey}" />
</div>
</form>

<div style="padding:20px 0 10px 0; text-align: center;">If you see incorrect or inappropriate content on this page please <a href="soc.php?cp=contact" style="color:#80B0DE; text-decoration:none;">click here</a> to report.</div>

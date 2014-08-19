{literal}
<script language="javascript">
function checkForm() {
	if(document.lookupreview.buyername.value == '') {
		alert("Please enter the buyer's name.");
		return false;
	}
	return true;
}
</script>
{/literal}

<div style="padding:0 0 10px 0;">Please enter the nickname of the user and click on the Lookup button to search for their review history.</div>

<form name="lookupreview" action="/soc.php?cp=lookupreview" method="POST" onsubmit="return checkForm();">
<table ><tr><td><input type="text" id="buyername" name="buyername" size="30" {if $buyername ne ''}value="{$buyername}"{/if} class="inputB" /></td><td>
<input type="image" src="/skin/red/images/buttons/or-lookup.gif" value="Lookup" /></span></td></tr></table>
</form>

<div style=" font-weight:bold; font-size:14px; padding:20px 0 10px 0;">{if $buyername ne ''}{$buyername}{/if}</div>

{if $action eq 'lookup'}
	{if $req.reviews ne ''}
	<ul id="state-advertisers">
		{foreach from=$req.reviews item=review}
		<li>
			<div style="float:left;"><img src="/images/star_{$review.rating}.gif"></div>
			<div style="float:right;"><strong>{$review.fdate}</strong> by <strong>{$review.username}</strong> (Purchase {if $review.status == 0}not{/if} completed)</div>
			<div style="clear:both; padding:10px 0 10px 0;">{$review.content}</div>
			<div style="clear:both;">{$review.comment}</div>
			<div style="float:right;"><a href="soc.php?StoreID={$review.StoreID}&cp=comment&type=user&rid={$review.review_id}" style="text-decoration:none; color:#80B0DE;">Add a Comment</a></div>
			<br style="clear:both;" />
		</li>
		{/foreach}
		{$req.linkStr}
	</ul>
	{else}
		<p class="font_red">No reviews for this buyer.Please try again.</p>
	{/if}
{/if}

<div style="padding:20px 0 10px 0; text-align: center;">If you see incorrect or inappropriate content on this page please <a href="soc.php?cp=contact" style="color:#80B0DE; text-decoration:none;">click here</a> to report.</div>

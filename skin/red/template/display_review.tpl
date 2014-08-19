{literal}
<script language=javascript>
function delReview(rid, StoreID)
{
	if (window.confirm("Are you sure you want to delete this review?")) {
		location.href = "soc.php?StoreID=" + StoreID + "&type=store&cp=delreview&rid=" + rid;
	}
	return true;
}
</script>
{/literal}
<div id="page_heading" style="font-weight: bold; font-size: 14px; float:left;">{$req.storeInfo.bu_name}</div>
<div style="clear:both; color:#FF0000; padding:0 0 20px 0;"></div>

{if $req.reviews ne ''}
<ul id="state-advertisers" style="width:720px;">
{foreach from=$req.reviews item=review}
	<li>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td rowspan='3' width='100' align='center'><img class="profile_image" src="{if $review.profile_image}{$review.profile_image}{else}{$smarty.const.IMAGES_URL}/no_profile_pic.jpg{/if}" width="100px" /><br/><strong>{$review.username}</strong></td>
			<td height="25">
				<img src="/images/star_{$review.rating}.gif">
			</td>
			<td align="right"><strong>{$review.fdate}</strong> {if $req.storeInfo.attribute neq '5'}(Purchase {if $review.status == 0}not{/if} completed){/if}
			</td>
		</tr>
		<tr>
			<td colspan="2" height="25">{$review.content}
			</td>
		</tr>
		{if $req.user_level==2 or $req.ShopID==$review.StoreID}
		<tr>
			<td colspan=2 align=right> &nbsp; <a href="soc.php?StoreID={$review.StoreID}&type=store&cp=comment&rid={$review.review_id}">Add a Comment</a> 
            {if $req.ShopID==$review.StoreID}
            &nbsp; <a href="javascript:viod(0);" onclick="return delReview('{$review.review_id}', '{$review.StoreID}');">Delete</a>
            {/if}
            </td>
		</tr>
		{/if}
		<tr>
			<td>{$review.comment}</td>
		</tr>
	</table>
	</li>
{/foreach}
</ul>
	<div id="paging-wide" style="background:#{$templateInfo.bgcolor}">
		&nbsp;{$req.linkStr}
    </div>
{else}
<table width="720"  border="0" cellspacing="0" cellpadding="0">
	<tr><td height="24" align="center">No reviews for this store.</td></tr>
	<tr><td height="200" align="center"></td></tr>
</table>
{/if}

<div id="review_bottom" style="padding:10px 0 50px 100px; text-align: center;">If you see incorrect or inappropriate content on this page please <a href="soc.php?cp=contact" style="color:#80B0DE; text-decoration:none;">click here</a> to report.</div>

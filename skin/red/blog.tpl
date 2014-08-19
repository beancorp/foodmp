<strong class="keywordresult">{$req.new}</strong>

<ul id="blog-list">
{if $req.blogitem ne ''}
	{foreach from=$req.blogitem item=bloglist key=key}
	<li class="blog-item">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
			  <td colspan="2"><a href="soc.php?cp=blogpage&StoreID={$bloglist.StoreID}&bid={$bloglist.blog_id}&pageid=1" class="title">{$bloglist.subject}</a> {if $bloglist.approval>0}({$bloglist.approval} new comments for your approval){/if}</td>
			  <td rowspan="2" align="right">{if $bloglist.image1 != ''}<img align="baseline" valign="top" width="80" src="{$bloglist.image1}" />{/if}</td>
		  </tr>
			<tr>
			  <td colspan="2">{$bloglist.content}</td>
		  </tr>
			<tr>
				<td width="20%"><span>{$bloglist.modify_date}</span></td>
				<td width="65%" align="center"><span>{if $bloglist.comment==0}{$bloglist.comment} Comment{else}{$bloglist.commentLink}{/if}</span></td>
				<td width="15%" align="right"><span>{$bloglist.more}</span>&nbsp;&nbsp;<span>{$bloglist.del}</span></td>
			</tr>
		</table>
  	</li>
	{/foreach}
{else}
	<p class="bigger">No blogs.</p>
{/if}
</ul>

<div id="paging-wide"  style="background:#{$templateInfo.bgcolor}">&nbsp;{$req.navi}</div>
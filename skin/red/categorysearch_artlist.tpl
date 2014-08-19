{include_php file='include/jssppopup.php'}
	<div id="content_cms"><span id="titles">{$req.article.categoryName} </span>&nbsp;<a href="javascript:history.go(-1)">&lt;&lt; Back</a> &nbsp;</div><br />
	
	<ul id="search-rss-list">	
	{if $req.article.categoryART}
	{foreach from=$req.article.list item=l}
	<li><span>{$l.title}</span>&nbsp;<br />
		<samp>{$l.context} &nbsp; <a href="?cp=article&amp;cgid={$req.article.categoryFID}&amp;id={$l.id}">more>></a> </samp><br /><br />
	</li>{/foreach}

	</ul>
	{else}
	<strong class="keywordresult" style="width:530px;">Sorry, there is no search result for this category.</strong>
	{/if}
	
	<div style="height:30px;">{$req.article.page_navi}</div>
	
	<div id="paging-wide" style="width:530px;">
	&nbsp;{$pl.pagination}
	</div>

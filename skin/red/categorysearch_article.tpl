	<div id="content_cms"><span id="titles">{$req.article.categoryName} </span>&nbsp;<a href="javascript:history.go(-1)">&lt;&lt; Back</a> &nbsp;</div><br />
	
	<ul id="search-rss-list">	
	{if $req.article.categoryART}
	<li><span id="title">{$req.article.list.title}</span></li>
	<li><samp>{$req.article.list.content} </samp></li><br/><br />
	</ul>
	{else}
	<strong class="keywordresult" style="width:530px;">Sorry, there is no search result for this category.</strong>
	{/if}
		
	<div id="paging-wide" style="width:530px;">
	&nbsp;{$pl.pagination}
	</div>

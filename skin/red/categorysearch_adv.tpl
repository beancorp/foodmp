{include_php file='include/jssppopup.php'}<a name="top"></a>
	{$req.titles}
	
	{if $req.product ne ''}
	<ul id="search-rss-list">	
	<li><span id="title" style="float:left;">{$req.categoryName} information and News</span> <a href="/soc.php?cp=categoryrss&amp;id={$req.categoryId}" target="_blank" style="float:right; padding-right:10px;" title="Get file of RSS on click. "><img src="/skin/red/images/blog_link.gif" border="0" align="absmiddle" /></a><br />
	  <br /></li>

	{if $req.article.categoryART}
	<li>
	{foreach from=$req.article.list item=l}<span>{$l.title}</span>&nbsp;<br />
		<samp>{$l.context} &nbsp; <a href="?cp=article&amp;cgid={$req.article.categoryFID}&amp;id={$l.id}">more>></a> </samp><br />
		{/foreach}<br />
	<samp><a href="?cp=artlist&amp;cgid={$req.article.categoryFID}">More headlines &gt;&gt; </a></samp><br />
	<br />
	</li>
	{/if}
	</ul>
{if $req.subcat}

<div style="background:url(/skin/red/images/search_title.gif) repeat-x left top;width:100%;height:37px;border-bottom:1px solid #ccc;">
		<form name="category_search" action="soc.php?cp=statepage" method="get">
		<div style="float:right;padding-top:6px;padding-right:5px;">	
		<strong>Sort By:</strong>&nbsp;&nbsp;<select name="sort" class="inputB" style="width:170px;padding:2px;" onchange="this.form.submit();">
		<option value="1" {if $req.sort eq "1" || $req.sort eq "" }selected{/if}>Latest items</option>
		<option value="2" {if $req.sort eq "2"}selected{/if}>Lowest to Highest price</option>
		<option value="3" {if $req.sort eq "3"}selected{/if}>Highest to Lowest price</option>
		<option value="4" {if $req.sort eq "4"}selected{/if}>Alphabetical</option>
		<option value="5" {if $req.sort eq "5"}selected{/if}>Seller (alphabetical by seller)</option>
		</select>
		</div>
		{if $req.buytypeState eq "'yes'"}
		<div style="float:right;padding-top:6px;padding-right:5px;">
			<strong>Time left:</strong>&nbsp;&nbsp;<select name="timelefts" class="inputB" style="width:100px;padding:2px;" onchange="this.form.submit();">
			<option value="0" {if $req.timelefts eq "0" || $req.timelefts eq "" }selected{/if}>All</option>
			<option value="1800" {if $req.timelefts eq "1800"}selected{/if}>30 Mins</option>
			<option value="3600" {if $req.timelefts eq "3600"}selected{/if}>1 Hour</option>
			<option value="7200" {if $req.timelefts eq "7200"}selected{/if}>2 Hours</option>
			<option value="86400" {if $req.timelefts eq "86400"}selected{/if}>24 Hours</option>
			</select>
		</div>
		{/if}
		<div style="float:right;padding-top:6px; padding-right:5px;">
			<strong>Type:</strong>&nbsp;&nbsp;<select name="buytypeState" class="inputB" style="width:90px;" onchange="this.form.submit();">
			<option value="'yes','no'" {if $req.buytypeState eq "'yes','no'" || $req.buytypeState eq ""}selected{/if}>Any</option>
			<option value="'yes'" {if $req.buytypeState eq "'yes'"}selected{/if}>Auctions</option>
			<option value="'no'" {if $req.buytypeState eq "'no'"}selected{/if}>Buy Now</option>
			</select>
		</div>
		<input type="hidden" name="cp" value="prolist"/>
		<input type="hidden" name="id" value="{$req.cid}"/>
		<input type="hidden" name="sub_category" value="{$req.select_subcategory}"/>
		</form>
	</div>
	
	
<div style="text-align:right;padding:10px 0 0 0;">
<div style="float:left;">
<form action="" method="get">
	<input type="hidden" name="cp" value="prolist"/>
	<input type="hidden" name="id" value="{$req.cid}"/>
	<select id="sub_category" name="sub_category" class="select" style="width:220px;margin-right:0px;margin-bottom:3px;" onchange="this.form.submit();">
				<option value="">All Sub-Categories</option>
				{foreach from=$req.subcat item=category}
				<option value="{$category.id}" {if $req.select_subcategory eq $category.id} selected="selected"{/if}>{$category.name}</option>
				{/foreach}
	</select>
	</form>
</div>
<div style="clear:both;"></div>
</div>
{/if}
	{foreach from=$req.product item=pl}
	<strong class="keywordresult" style="width:540px;border-bottom:solid 1px #ccc; font-size:14px; color: #392f7e; padding-top:10px; padding-bottom:2px; font-weight: bolder;">{$pl.name}</strong>
		
	<ul id="search-adv-list">
	{foreach from=$pl.product item=spl}
	 <li>
	  {if $spl.simage.text eq "/images/79x79.jpg"}
	        <a href="/{$spl.url_bu_name}/{$spl.url_item_name}">
	        <img src="{$spl.simage.text}" width="{$spl.limage.width}" height="{$spl.limage.height}" border="0"/></a>
        {else}
	        <div class="moreImg0_css">
	        <a href="/{$spl.url_bu_name}/{$spl.url_item_name}">
	        <img src="{$spl.limage.text}"  width="{$spl.limage.width}" height="{$spl.limage.height}" border="0" onmouseover="showmoreImage_fade('pid_{$spl.pid}',true);" onmouseout="showmoreImage_fade('pid_{$spl.pid}',false);" class="item" /></a>
	        <div id="pid_{$spl.pid}" class="moreImg_css"><img src="{$spl.bimage.text}" style="width:{$spl.bimage.width}px;height:{$spl.bimage.height}px;"/></div><div id="pid_{$spl.pid}_2" class="moreImg_arror"></div></div>
        {/if}
	 
	 
	 
	 
		<div id="context"><a href="/{$spl.url_bu_name}/{$spl.url_item_name}">{$spl.item_name}</a>{$spl.description}{if $spl.descMore}&nbsp; <a style="display:inline" href="soc.php?cp=disprodes&amp;StoreID={$spl.urlParam}">more &gt;&gt;</a>{/if}
		<div style="font-size:11px;padding-top:3px;">Location - {$spl.bu_suburb}</div>
		 <div>
    		<div style="float:left;padding:4px 15px 5px 0;">Location - {$spl.stateName}</div>
    		<a href="/{$spl.url_bu_name}" class="arrowgrey" style="float:left;">{$spl.bu_name}</a>
		  </div>
		</div>

     	<div style="float:left;width:175px;">
     		<div style="float:left;width:100%;">
        	<strong class="{if $spl.is_auction eq 'yes'}auctionit{else}buyit{/if}" {if $product.is_auction eq 'yes'}title="Auction"{else}title="Buy Now"{/if}>{if $spl.is_auction eq 'yes'}${$spl.cur_price|number_format:2}{else}${$spl.price|number_format:2}{/if}</strong>
        	</div>
        	{if $spl.non}
        	<div style="float:left;width:100%;">
            <strong>{$lang.non[$spl.non]}</strong>
            </div>
            {/if}
            {if $spl.is_auction eq 'yes'}
            <div style="float:left;width:100%;padding-left:10px;">
            <span class="{if $spl.is_auction eq 'yes' && $spl.end_stamp<600}auctionhurg{else}buyitnow{/if}" style="float:left;">
		    {if $spl.is_auction eq 'yes' && $spl.end_stamp<600}&lt;&nbsp;&nbsp;{$spl.end_stamp|timeup}{elseif $spl.is_auction eq 'yes'}{$spl.end_stamp|timeup}{/if}
		</span>
			</div>
			{/if}
            <div class="clear"></div>
          </div>
	  </li>
	{/foreach}
	</ul>
	{/foreach}
	
	{else}
	<strong class="keywordresult" style="width:530px;">Sorry, there is no search result for this category.</strong>
	{/if}
	<div id="paging-wide" style="width:530px;">
	&nbsp;{$req.pager.linksAllFront}&nbsp;&nbsp;&nbsp;&nbsp;<a href="#top" style="font-weight:bold; color:#fff; text-decoration:none;">Back to top</a>
	</div>
	

{if $req.ad.categoryCMSDown}
<div class="categoryCMSDown" align="center"><div align="left">{$req.ad.categoryCMSDown}</div></div>
{/if}
{include_php file='include/jssppopup.php'}<a name="top"></a>
		
		
		<div id="searchresults2" style="padding-bottom:0px; margin:0 0 10px 0;">
		{$req.titles|replace:'<br />':""}
	<div style="background:url(/skin/red/images/search_title.gif) repeat-x left top;width:100%;height:37px;border-bottom:1px solid #ccc;">
		<form name="category_search" action="soc.php?cp=statepage" method="get">
		<div style="float:right;padding-top:6px;padding-right:5px;">	
		<strong>Sort By:</strong>&nbsp;&nbsp;<select name="sort" class="inputB" style="width:170px;" onchange="this.form.submit();">
		<option value="1" {if $req.sort eq "1" || $req.sort eq "" }selected{/if}>Latest items</option>
		<option value="2" {if $req.sort eq "2"}selected{/if}>Lowest to Highest price</option>
		<option value="3" {if $req.sort eq "3"}selected{/if}>Highest to Lowest price</option>
		<option value="4" {if $req.sort eq "4"}selected{/if}>Alphabetical</option>
		<option value="5" {if $req.sort eq "5"}selected{/if}>Seller (alphabetical by seller)</option>
		</select>
		</div>
		{if $req.buytypeState eq "'yes'"}
		<div style="float:right;padding-top:6px;padding-right:5px;">
			<strong>Time left:</strong>&nbsp;&nbsp;<select name="timelefts" class="inputB" style="width:120px;" onchange="this.form.submit();">
			<option value="0" {if $req.timelefts eq "0" || $req.timelefts eq "" }selected{/if}>All</option>
			<option value="1800" {if $req.timelefts eq "1800"}selected{/if}>30 Mins</option>
			<option value="3600" {if $req.timelefts eq "3600"}selected{/if}>1 Hour</option>
			<option value="7200" {if $req.timelefts eq "7200"}selected{/if}>2 Hours</option>
			<option value="86400" {if $req.timelefts eq "86400"}selected{/if}>24 Hours</option>
			</select>
		</div>
		{/if}
		<div style="float:right;padding-top:6px; padding-right:5px;">
			<strong>Type:</strong>&nbsp;&nbsp;<select name="buytypeState" class="inputB" style="width:100px;" onchange="this.form.submit();">
			<option value="'yes','no'" {if $req.buytypeState eq "'yes','no'" || $req.buytypeState eq ""}selected{/if}>Any</option>
			<option value="'yes'" {if $req.buytypeState eq "'yes'"}selected{/if}>Auctions</option>
			<option value="'no'" {if $req.buytypeState eq "'no'"}selected{/if}>Buy Now</option>
			</select>
		</div>
		<input type="hidden" name="cp" value="prolist"/>
		<input type="hidden" name="id" value="{$req.cid}"/>
		<input type="hidden" name="pageno" value="{$req.pageno}"/>
		<input type="hidden" name="sub_category" value="{$req.select_subcategory}" />
		</form>
	</div>
  </div>
		
		
<div style="text-align:right">
{if $req.subcat}
<div style="float:left;">
<form action="" method="get">
<input type="hidden" name="cp" value="prolist"/>
<input type="hidden" name="id" value="{$req.cid}"/>

<select id="sub_category" name="sub_category" class="select" style="width:220px;margin-right:10px;margin-bottom:3px;" onchange="this.form.submit();">
			<option value="">All Sub-Categories</option>
			{foreach from=$req.subcat item=category}
			<option value="{$category.id}" {if $req.select_subcategory eq $category.id} selected="selected"{/if}>{$category.name}</option>
			{/foreach}
</select></form>
</div>
<div style="clear:both;"></div>
{/if}
</div>
        {if $req.product ne ''}
		{foreach from=$req.product item=pl}
        <strong class="keywordresult" style="font-size:14px; color: #392f7e; padding: 10px; font-weight: bolder;">{$pl.name}</strong>
            
        <ul id="search-list">
		{foreach from=$pl.product item=spl}
         <li>
         {if $spl.simage.text eq "/images/79x79.jpg"}
	        <a href="/{$spl.url_bu_name}/{$spl.url_item_name}">
	        <img src="{$spl.simage.text}" width="{$spl.limage.width}" height="{$spl.limage.height}" border="0"/></a>
        {else}
	        <div class="moreImg0_css">
	        <a href="/{$spl.url_bu_name}/{$spl.url_item_name}">
	        <img src="{$spl.simage.text}"  width="{$spl.limage.width}" height="{$spl.limage.height}" border="0" onmouseover="showmoreImage_fade('pid_{$spl.pid}',true);" onmouseout="showmoreImage_fade('pid_{$spl.pid}',false);" class="item" /></a>
	        <div id="pid_{$spl.pid}" class="moreImg_css"><img src="{$spl.bimage.text}" style="width:{$spl.bimage.width}px;height:{$spl.bimage.height}px;"/></div><div id="pid_{$spl.pid}_2" class="moreImg_arror"></div></div>
        {/if}
         
        	<div id="context" style="padding-left:5px;"><a href="/{$spl.url_bu_name}/{$spl.url_item_name}">{$spl.item_name}</a>{$spl.description}{if $spl.descMore}&nbsp; <a style="display:inline" href="soc.php?cp=disprodes&amp;StoreID={$spl.urlParam}">more &gt;&gt;</a>{/if}
        	
        	
	         <div style="font-size:11px;padding-top:3px;">Location - {$spl.bu_suburb}</div>
			 <div>
	    		<div style="float:left;padding:4px 15px 5px 0;">Location - {$spl.stateName}</div>
	    		<a href="/{$spl.url_bu_name}" class="arrowgrey" style="float:left;">{$spl.bu_name}</a>
			 </div>
		  </div>
		  
		  <div style="float:left;">
		  	<div style="float:left;">
        	<strong class="{if $spl.is_auction eq 'yes'}auctionit{else}buyit{/if}" {if $product.is_auction eq 'yes'}title="Auction"{else}title="Buy Now"{/if}>{if $spl.is_auction eq 'yes'}${$spl.cur_price|number_format:2}{else}${$spl.price}{/if}</strong>
        	</div>
        	 <div class="clear"></div>
        	<div style="float:left;">
            <strong>{if $spl.non}{$lang.non[$spl.non]}{/if}</strong>
            </div>
            <div class="clear"></div>
          </div>
             
			<span class="{if $spl.is_auction eq 'yes' && $spl.end_stamp<600}auctionhurg{else}buyitnow{/if}">
		              {if $spl.is_auction eq 'yes' && $spl.end_stamp<600}&lt;&nbsp;&nbsp;{$spl.end_stamp|timeup}{elseif $spl.is_auction eq 'yes'}{$spl.end_stamp|timeup}{/if}
		    </span>
            </li>
		{/foreach}
        </ul>
		{/foreach}
		{else}
		<strong class="keywordresult">Sorry, there is no search result for this category.</strong>
		{/if}
        <div id="paging-wide">
       	&nbsp;{$req.pager.linksAllFront}&nbsp;&nbsp;&nbsp;&nbsp;<a href="#top" style="font-weight:bold; color:#fff; text-decoration:none;">Back to top</a>
        </div>

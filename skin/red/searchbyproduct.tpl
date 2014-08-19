<div id="searchresults2" style="padding-bottom:0px; margin-bottom:0px;">
<h2 style="margin-bottom:0;">{$req.counter} item(s) found from your search</h2>

<div style="background:url(/skin/red/images/search_title.gif) repeat-x left top;width:100%;height:37px;border-bottom:1px solid #ccc;">
<div style="float:right;padding-top:6px;padding-right:5px;">
<form name="category_search" action="/soc.php" method="get">
<strong>Sort By:</strong>&nbsp;&nbsp;<select name="sort" class="inputB" style="width:170px;padding:2px;" onchange="this.form.submit();">
<option value="1" {if $selectedary.sort eq "1" || $selectedary.sort eq "" }selected{/if}>Latest items</option>
<option value="2" {if $selectedary.sort eq "2"}selected{/if}>Lowest to Highest price</option>
<option value="3" {if $selectedary.sort eq "3"}selected{/if}>Highest to Lowest price</option>
<option value="4" {if $selectedary.sort eq "4"}selected{/if}>Alphabetical</option>
<option value="5" {if $selectedary.sort eq "5"}selected{/if}>Seller (alphabetical by seller)</option>
</select>
<input type="hidden" name="cp" value="statepage"/>
<input type="hidden" name="product_name" value="{$selectedary.product_name}"/>
<input type="hidden" name="state_name" value="{$selectedary.state_name}"/>
<input type="hidden" name="business_name" value="{$selectedary.business_name}"/>
<input type="hidden" name="selectDistance" value="{$selectedary.selectDistance}"/>
<input type="hidden" name="selectSubburb" value="{$selectedary.selectSubburb}"/>
<input type="hidden" name="category" value="{$selectedary.category}"/>
<input type="hidden" name="bcategory" value="{$selectedary.bcategory}"/>
<input type="hidden" name="bsubcategory" value="{$selectedary.bsubcategory}"/>
<input type="hidden" name="sstate_name" value="{$selectedary.sstate_name}"/>
<input type="hidden" name="price_min" value="{$selectedary.price_min}"/>
<input type="hidden" name="price_max" value="{$selectedary.price_max}"/>
<input type="hidden" name="issold" value="{$selectedary.issold}"/>
<input type="hidden" name="pageId" value="{$selectedary.pageId}"/>
<input type="hidden" name="timelefts" value="{$selectedary.timelefts}"/>
<input type="hidden" name="buytype" value="{$selectedary.buytype}"/>
</form>
</div>
{if $selectedary.buytype eq "'yes'"}
<div style="float:right;padding-top:6px;padding-right:5px;">
<form name="search_timeleft" action="/soc.php" method="get">
<strong>Time left:</strong>&nbsp;&nbsp;<select name="timelefts" class="inputB" style="width:120px;padding:2px;" onchange="this.form.submit();">
<option value="0" {if $selectedary.timelefts eq "0" || $selectedary.timelefts eq "" }selected{/if}>All</option>
<option value="1800" {if $selectedary.timelefts eq "1800"}selected{/if}>30 Mins</option>
<option value="3600" {if $selectedary.timelefts eq "3600"}selected{/if}>1 Hour</option>
<option value="7200" {if $selectedary.timelefts eq "7200"}selected{/if}>2 Hours</option>
<option value="86400" {if $selectedary.timelefts eq "86400"}selected{/if}>24 Hours</option>
</select>
<input type="hidden" name="sort" value="{$selectedary.sort}"/>
<input type="hidden" name="cp" value="statepage"/>
<input type="hidden" name="product_name" value="{$selectedary.product_name}"/>
<input type="hidden" name="state_name" value="{$selectedary.state_name}"/>
<input type="hidden" name="business_name" value="{$selectedary.business_name}"/>
<input type="hidden" name="selectDistance" value="{$selectedary.selectDistance}"/>
<input type="hidden" name="selectSubburb" value="{$selectedary.selectSubburb}"/>
<input type="hidden" name="category" value="{$selectedary.category}"/>
<input type="hidden" name="bcategory" value="{$selectedary.bcategory}"/>
<input type="hidden" name="bsubcategory" value="{$selectedary.bsubcategory}"/>
<input type="hidden" name="sstate_name" value="{$selectedary.sstate_name}"/>
<input type="hidden" name="price_min" value="{$selectedary.price_min}"/>
<input type="hidden" name="price_max" value="{$selectedary.price_max}"/>
<input type="hidden" name="issold" value="{$selectedary.issold}"/>
<input type="hidden" name="pageId" value="{$selectedary.pageId}"/>
<input type="hidden" name="buytype" value="{$selectedary.buytype}"/>
</form>
</div>
{/if}
</div>
	<div style="float:left;">
  <strong class="keywordresult">
    	{if $req.page ne 'state'}
    	Your search results for the {$req.product_name}.
    	{else}
    			<form name="category_search" action="" method="POST">
                	Category:
                	<select name="category" class="select" style="width:220px;" onchange="this.form.submit();">
                		<option value="">All</option>
	                	{foreach from=$req.categories item=category}
	                	<option value="{$category.id}" {if $category_id eq $category.id} selected="selected"{/if}>{$category.name}</option>
	                	{/foreach}
                	</select>
                	<input type="hidden" name="selectDistance" value="200">
                	<input type="hidden" name="state_name" value="{$state_name}">
                	<input type="hidden" name="selectSubburb" value="{$selectSubburb}">
            	</form>
   	  {/if}    </strong></div>
   	  {if $selectedary.buytype eq "'yes'"}
<div style="float:right;padding-top:10px;font-weight:bold;padding-right:40px;">
<span style="padding-right:50px;font-weight:bold;">Current bid</span> <span style="padding-right:40px">|</span> <span style="font-weight:bold;color:#3e337f">Time left</span>
</div>
{/if}
<div style="clear:both;width:0;height:0;"></div>
	{if $req.counter ne 0}
    <ul id="search-list">
    	{if $req.page ne 'state'}
	    	{foreach from=$req.products item=product}
	        <li>{if $product.simage.text eq '/images/79x79.jpg'}<a href="/{$product.website_name}/{$product.url_item_name}">{$product.img_link}</a>{else}{$product.img_link}{/if}
	        	<div id="context" ><a href="/{$product.website_name}/{$product.url_item_name}">{$product.item_name}</a>
	        	{$product.description|truncate:200:"..."}
	        	<div style="font-size:11px;padding-top:3px;">Location - {$product.bu_suburb}</div>
	        	<div>
	        	  <div style="float:left;padding:4px 15px 5px 0;">State - {$product.state_name}</div><a style="float:left;" href="/{$product.website_name}" class="arrowgrey">{$product.bu_name}</a></div></div>
                <div style=" float:left; margin:0 0 0 20px;_width:100px;min-width:100px;">
                <div style=" width:100%;">
	        	<strong class="{if $product.is_auction eq 'yes'}auctionit{else}buyit{/if}" {if $product.is_auction eq 'yes'}title="Auction"{else}title="Buy Now"{/if}>
	        				{if $product.is_auction eq 'yes'}${$product.cur_price|number_format:2}{else}
	        				${$product.price|number_format:2}{/if}</strong>
                </div>
                {if $product.on_sale == 'sold' && $product.is_auction neq 'yes'}<div style="float:left; width:100%;padding-left:15px;"><img src="skin/red/images/sold_icon.gif" /></div>{/if}
                </div>
	            <span class="{if $product.is_auction eq 'yes' && $product.end_stamp<600 && $product.end_stamp>0}auctionhurg{else}buyitnow{/if}">
	            {if $product.is_auction eq 'yes' && $product.end_stamp<600 &&$product.end_stamp>0}
	            	&lt;&nbsp;&nbsp;{$product.end_stamp|timeup}
	            {elseif $product.is_auction eq 'yes' &&$product.end_stamp>0}{$product.end_stamp|timeup}
	            {elseif $product.is_auction eq 'yes' &&$product.end_stamp==0}Ended{/if}
	            </span>
	        </li>
	        {/foreach}
        {else}
	    	{foreach from=$req.products item=product}

	    	{if $product.item_name ne ''}		    	
	    		{if $product.flag eq 1}
	    		<h2 style="background-color: #80b0de; display:block;">{$product.name}</h2>
	    		{/if}
		        <li>{if $product.image_name ne ''}<a href="/{$product.website_name}/{$product.url_item_name}"><img src="{$product.image_name}" alt="{$product.bu_name}" title="{$product.bu_name}" width="{$product.limage.width}" height="{$product.limage.height}" border="0" class="item" /></a>{/if}
		        	<div id="context" ><a href="/{$product.website_name}/{$product.url_item_name}">{$product.item_name}</a>
		        	{$product.description|truncate:200:"..."}
		        	<div style="font-size:11px;padding-top:3px;">Location - {$product.bu_suburb}</div>
		        	<div>
		        		<div style="float:left;padding:4px 15px 5px 0;">State - {$product.state_name}</div>
		        		<a href="/{$product.website_name}" class="arrowgrey">{$product.bu_name}</a></div>
		        	</div>
                    <div style=" float:left; margin:0 0 0 20px;_width:100px;min-width:100px;">
                	<div style=" width:100%;">
		        	<strong class="{if $product.is_auction eq 'yes'}auctionit{else}buyit{/if}" {if $product.is_auction eq 'yes'}title="Auction"{else}title="Buy Now"{/if}>
	        				{if $product.is_auction eq 'yes'}${$product.cur_price|number_format:2}{else}
	        				${$product.price|number_format:2}{/if}</strong>
                    <div>
                    {if $product.on_sale == 'sold'}<div style="float:left; width:100%;padding-left:15px;"><img src="skin/red/images/sold_icon.gif"/></div>{/if}
                    </div>
		            <span class="{if $product.is_auction eq 'yes' && $product.end_stamp<600 && $product.end_stamp>0}auctionhurg{else}buyitnow{/if}">
		            {if $product.is_auction eq 'yes' && $product.end_stamp<600 &&$product.end_stamp>0}&lt;&nbsp;&nbsp;{$product.end_stamp|timeup}
		            {elseif $product.is_auction eq 'yes' &&$product.end_stamp>0}{$product.end_stamp|timeup}
		            {elseif $product.is_auction eq 'yes' &&$product.end_stamp==0}Ended{/if}
	            	</span>
		        </li>
		        {/if}
	        {/foreach}
        {/if}
	</ul>
	{/if}
    
	<div id="paging-wide">
		&nbsp;{$req.linkStr}
    </div>	
</div>
<div style="clear:both; width:auto; margin-bottom:15px; margin-top:10px; *margin-top:-20px; *padding-top:-20px;">
{include file="seller_context.tpl"}
</div>
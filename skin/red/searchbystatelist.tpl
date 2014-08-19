	{include file="searchpanel.tpl"}
	<div id="searchresults2" style="padding-bottom:0px; margin:10px 0;">
	<h2 style="margin-bottom:0;">{$req.counter} item(s) found from your search</h2>
	<div style="background:url(/skin/red/images/search_title.gif) repeat-x left top;width:100%;height:37px;border-bottom:1px solid #ccc;">
		<form name="category_search" action="soc.php?cp=statepage" method="get">
		<div style="float:right;padding-top:6px;padding-right:5px;">	
		<strong>Sort By:</strong>&nbsp;&nbsp;<select name="sort" class="inputB" style="width:170px;" onchange="this.form.submit();">
		<option value="1" {if $selectedary.sort eq "1" || $selectedary.sort eq "" }selected{/if}>Latest items</option>
		<option value="2" {if $selectedary.sort eq "2"}selected{/if}>Lowest to Highest price</option>
		<option value="3" {if $selectedary.sort eq "3"}selected{/if}>Highest to Lowest price</option>
		<option value="4" {if $selectedary.sort eq "4"}selected{/if}>Alphabetical</option>
		<option value="5" {if $selectedary.sort eq "5"}selected{/if}>Seller (alphabetical by seller)</option>
		</select>
		</div>
		{if $selectedary.buytypeState eq "'yes'"}
		<div style="float:right;padding-top:6px;padding-right:5px;">
			<strong>Time left:</strong>&nbsp;&nbsp;<select name="timelefts" class="inputB" style="width:120px;" onchange="this.form.submit();">
			<option value="0" {if $selectedary.timelefts eq "0" || $selectedary.timelefts eq "" }selected{/if}>All</option>
			<option value="1800" {if $selectedary.timelefts eq "1800"}selected{/if}>30 Mins</option>
			<option value="3600" {if $selectedary.timelefts eq "3600"}selected{/if}>1 Hour</option>
			<option value="7200" {if $selectedary.timelefts eq "7200"}selected{/if}>2 Hours</option>
			<option value="86400" {if $selectedary.timelefts eq "86400"}selected{/if}>24 Hours</option>
			</select>
		</div>
		{/if}
		<div style="float:right;padding-top:6px; padding-right:5px;">
			<strong>Type:</strong>&nbsp;&nbsp;<select name="buytypeState" class="inputB" style="width:100px;" onchange="this.form.submit();">
			<option value="'yes','no'" {if $selectedary.buytypeState eq "'yes','no'" || $selectedary.buytypeState eq ""}selected{/if}>Any</option>
			<option value="'yes'" {if $selectedary.buytypeState eq "'yes'"}selected{/if}>Auctions</option>
			<option value="'no'" {if $selectedary.buytypeState eq "'no'"}selected{/if}>Buy Now</option>
			</select>
		</div>
		<input type="hidden" name="category" value="{$category_id}"/>
		<input type="hidden" name="cp" value="statepage" />
		<input type="hidden" name="selectDistance" value="{$selectDistance}"/>
		<input type="hidden" name="p" value="{$selectedary.p}"/>
		<input type="hidden" name="state_name" value="{$state_name}"/>
		<input type="hidden" name="selectSubburb" value="{$selectSubburb}"/>
		</form>
	</div>
  </div>
  <div style="float:left;">
    <form name="category_search" action="soc.php?cp=statepage" method="get">
   
	<label style="font-size:14px; font-weight:blod;">Category</label>:
		<select name="category" class="select" style="width:220px;" onchange="this.form.submit();">
			<option value="">All</option>
			{foreach from=$req.categories item=category}
			<option value="{$category.id}" {if $category_id eq $category.id} selected="selected"{/if}>{$category.name}</option>
			{/foreach}
		</select>
        <input type="hidden" name="cp" value="statepage" />
		<input type="hidden" name="selectDistance" value="{$selectDistance}">
		<input type="hidden" name="state_name" value="{$state_name}">
		<input type="hidden" name="selectSubburb" value="{$selectSubburb}">
	</form>
	</div>	
	
	<div style="clear:both;"></div>
	
            	
	{if $req.counter ne 0}
    <ul id="search-list">
    	{if $req.page ne 'state'}
	    	{foreach from=$req.products item=product}
	        <li><a href="/{$product.website_name}/{$product.url_item_name}">{$product.img_link}</a>
	        	<div id="context">
				<a href="/{$product.website_name}/{$product.url_item_name}">{$product.item_name}</a>
	        	{$product.description|truncate:200:"..."}
	        	<a href="/{$product.website_name}" class="arrowgrey">{$product.bu_name}</a>
				</div>
	        	<strong class="{if $product.is_auction eq 'yes'}auctionit{else}buyit{/if}" {if $product.is_auction eq 'yes'}title="Auction"{else}title="Buy Now"{/if}>${$product.price}</strong>
	            <span>{$product.bu_suburb}</span>
	         </li>
	        {/foreach}
        {else}
	    	{foreach from=$req.products item=product}

	    	{if $product.item_name ne ''}		    	
	    		{if $product.flag eq 1}
	    		<!--<h2 class="adminTitle" style="margin:2px;">{$product.name}</h2>-->
	    		<h2 style="font-size:14px; color: #392f7e; padding: 0px; font-weight: bolder; height:20px; line-height:24px;">{$product.name}</h2>
	    		{/if}
		        <li>
		          {if $product.simage.text eq "/images/79x79.jpg"}
			       <a href="/{$product.website_name}/{$product.url_item_name}">
			        <img src="{$product.simage.text}" alt="{$product.bu_name}" title="{$product.bu_name}" width="140" height="140" border="0"/></a>
		        {else}
			        <div class="moreImg0_css">
			       <a href="/{$product.website_name}/{$product.url_item_name}">
			        <img src="{$product.simage.text}"  alt="{$product.bu_name}" title="{$product.bu_name}" width="140" height="140" border="0" onmouseover="showmoreImage_fade('pid_{$product.pid}',true);" onmouseout="showmoreImage_fade('pid_{$product.pid}',false);" /></a>
			        <div id="pid_{$product.pid}" class="moreImg_css"><img src="{$product.bimage.text}" style="width:{$product.bimage.width}px;height:{$product.bimage.height}px;"/></div><div id="pid_{$product.pid}_2" class="moreImg_arror"></div></div>
		        {/if}
		        
		        	<div id="context">
					<a href="/{$product.website_name}/{$product.url_item_name}">{$product.item_name}</a>
		        	{$product.description|truncate:200:"..."}
		        	<div style="font-size:11px;padding-top:3px;">Location - {$product.bu_suburb}</div>
		        	<div>
		        		<div style="float:left;padding:4px 15px 5px 0;">State - {$product.stateName}</div>
		        		<a href="/{$product.website_name}" class="arrowgrey" style="float:left;">{$product.bu_name}</a></div>
		        	</div>
		        	<strong class="{if $product.is_auction eq 'yes'}auctionit{else}buyit{/if}" {if $product.is_auction eq 'yes'}title="Auction"{else}title="Buy Now"{/if}>{if $product.is_auction eq 'yes'}${$product.cur_price|number_format:2}{else}${$product.price|number_format:2}{/if}</strong>
		        	<span class="{if $product.is_auction eq 'yes' && $product.end_stamp<600}auctionhurg{else}buyitnow{/if}">
		              {if $product.is_auction eq 'yes' && $product.end_stamp<600}&lt;&nbsp;&nbsp;{$product.end_stamp|timeup}{elseif $product.is_auction eq 'yes'}{$product.end_stamp|timeup}{/if}
		            </span>
		            </li>
		        {/if}
	        {/foreach}
        {/if}
	</ul>
	{/if}
	
	<br>
	<br>
    
	<div id="paging-wide">
		&nbsp;{$req.linkStr}
    </div>
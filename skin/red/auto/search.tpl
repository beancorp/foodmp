{include file="searchpanel.tpl"}

	<h2 style="color:#777;">{$req.counter|default:'0'} item(s) found from your search</h2>

	{if $req.counter ne 0}
    <ul id="search-list">
    	{if $req.page ne 'state'}
	    	{foreach from=$req.products item=product}
	         <li><a href="/{$product.website_name}/{$product.url_item_name}">{$product.img_link}</a>
	        	<div id="context"><a href="/{$product.website_name}/{$product.url_item_name}">{$product.item_name}</a>
	        	{$product.description|truncate:200:"..."}
	        	<a href="/{$product.bu_urlstring}" class="arrowgrey">{$product.bu_name}</a></div>
	        	<strong>{if $product.price > 0}${$product.price}{/if}</strong>
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
		        <img src="{$product.simage.text}" alt="{$product.bu_name}" title="{$product.bu_name}" width="{$product.limage.width}" height="{$product.limage.height}" border="0"/></a>    
		        {else}
		        <div class="moreImg0_css">
		        <a href="/{$product.website_name}/{$product.url_item_name}">
		        <img src="{$product.simage.text}" alt="{$product.bu_name}" title="{$product.bu_name}" width="{$product.limage.width}" height="{$product.limage.height}" class="item" border="0" onmouseover="showmoreImage_fade('pid_{$product.pid}',true);" onmouseout="showmoreImage_fade('pid_{$product.pid}',false);" /></a>
		        <div id="pid_{$product.pid}" class="moreImg_css"><img src="{$product.bimage.text}" style="width:{$product.bimage.width}px;height:{$product.bimage.height}px;"/></div><div id="pid_{$product.pid}_2" class="moreImg_arror"></div></div>
		        {/if}
		        
		        	<div id="context"><a href="/{$product.website_name}/{$product.url_item_name}">{$product.item_name}</a>
					{truncate content="`$product.content`" length="200" etc=''}
		        	<a href="/{$product.website_name}" class="arrowgrey">{$product.bu_name}</a></div>
		        	<strong>{if $product.price > 0 }${$product.price|number_format}{/if}</strong>
		            <span>{$product.bu_suburb}</span>		        
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

<div style="clear:both; width:auto; margin-bottom:15px; margin-top:10px;">
{include file="../seller_context.tpl" search_type="auto"}
</div>

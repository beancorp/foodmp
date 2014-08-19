{include file="searchpanel.tpl"}

	<h2 id="search_category_info" style="color:#777;">{$req.counter|default:'0'} retailers found from your search {if $searchForm.keyword or $searchForm.bcategory_name or $searchForm.state_name neq -1 or $searchForm.distance}for{/if} <em style="font-weight:bold; font-size:14px;">{if $searchForm.keyword}{$searchForm.keyword}, {/if}{$searchForm.bcategory_name} {if $searchForm.state_name neq -1}<font style="font-weight:normal">in</font>{/if} {if $searchForm.suburb}{$searchForm.suburb},{/if} {if $searchForm.state_name neq -1}{$searchForm.state_name}{/if} {if $searchForm.distance}within {$searchForm.distance} {$smarty.const.DISTANCE_SCALE}{/if}</em></h2>

    {if $req.counter and $req.category_name ne ''}
         <h2 style="font-size:14px; color: #392f7e; padding: 0px; margin:15px 0; font-weight: bolder; height:20px; line-height:24px;">{$req.category_name}</h2>
        {if $req.not_popularize_counter ne 0}
        <ul id="search-foodwine-list">
            {foreach from=$req.stores.not_popularize item=product key=k}

            {if $product.bu_name ne ''}
                <li {if $req.popularize_category_counter==0 && ($req.not_popularize_counter-1)==$k}style="border:none;"{/if}>
                <div class="moreImg0_css">
				{if $product.website_name eq '' && $product.website_url neq ''} 
					<a href="/listing.php?id={$product.StoreID}" target="_blank">
				{else} 
					<a href="http://{$product.website_name}.{$smarty.const.SHORT_HOSTNAME}">
				{/if}
				
				{if $product.store_logo.text neq "/images/79x79.jpg"}
					<img src="{$product.store_logo.text}" alt="{$product.bu_name}" title="{$product.bu_name}" width="140" border="0"/></a>
				{else}
					<img src="{$product.default_store_image}" alt="{$product.bu_name}" title="{$product.bu_name}" width="120" height="91" border="0"/>{$product.is_popularize_store}</a>
				{/if}
				
				
				KKKKKK
                <div id="pid_{$product.pid}" class="moreImg_css"><img src="{$product.bimage.text}" style="width:{$product.bimage.width}px;height:{$product.bimage.height}px;"/></div><div id="pid_{$product.pid}_2" class="moreImg_arror"></div></div>
                    <div id="context" style=" width:600px;">
						{if $product.website_name eq '' && $product.website_url neq ''} 
							<a href="/listing.php?id={$product.StoreID}" target="_blank" class="arrowgrey">{$product.bu_name}</a>
					    {else} 
							<a href="http://{$product.website_name}.{$smarty.const.SHORT_HOSTNAME}" class="arrowgrey">{$product.bu_name}</a>
					    {/if}
                        <span>{$product.suburbName}, {$product.stateName}{if $product.location neq ''}, { $product.location}{/if}</span>
                    </div>
                    </li>
                    <div style="clear:both; height:10px;"></div>
                {/if}
            {/foreach}
        </ul>
        {/if}
	
        {if $req.popularize_counter ne 0}
        <ul id="search-foodwine-popularize-list">
    
                    <li style="border:none;">
                    <ul style="padding:0; margin:0;">
                    {foreach from=$req.stores.popularize.0.items item=store}
                    <li>    
                    <div id="context" style=" width:100%;">
                        <a href="/{$store.website_name}" class="arrowgrey">{$store.bu_name}</a>
                        <!--<a href="/{$product.website_name}/{$product.url_item_name}" class="arrowgrey">{$product.item_name}</a>-->
                        <span>{$store.suburbName}, {$store.stateName}{if $store.location neq ''}, { $store.location}{/if}</span>
                    </div>
                    </li>
                    {/foreach}
                    </ul>
                    </li>
                    <div style="clear:both;"></div>
        </ul>
        {/if}
    {/if}    
	
	<div id="paging-wide" style="text-align:left">
    	<div style="float:left;">
		&nbsp;{$req.linkStr}
        </div> 
        <a href="#top" class="back_to_top" title="Back to top" style="float:right">Back to top</a>
        <div style="clear:both;"></div>
    </div>

<div style="clear:both; width:auto; margin-bottom:15px; margin-top:10px;">
{include file="../seller_context.tpl" search_type="foodwine"}
</div>

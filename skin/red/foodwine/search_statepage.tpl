{literal}
<script>new YAHOO.Hack.FixIESelectWidth('state_subburb');</script>
{/literal}
<form action="/foodwine/index.php?cp=search" id="statesearch" class="st-connecticut" method="get">
	<input type="hidden" name="cp" value="search" />
	<input type="hidden" name="tpl_search" value="search_statepage" />
	<div style="float:left; width: 180px; padding-top: 30px; padding-left: 8px; font-size:14px; font-weight: 900; color:#3c3380;">{$state_fullname} Homepage</div>
	<div style="float:right; width:300px; margin-right:10px;">
	<fieldset>
		<h2 id="location">Enter your location and find your local sellers</h2>
	</fieldset>
	<fieldset>
		<ol>
			<li>
				{literal}
				<script type="text/javascript">
				<!--//
				function switchState(state)
				{
					location.href = '/foodwine/index.php?cp=search&state_name=' + state;
				}
				//-->
				</script>
				{/literal}
				<select name="state_name" class="state" onchange="switchState(this.value)">
				{foreach from=$req.states item=state}
				<option value="{$state.state}"{$state.selected}>{$state.state}</option>
				{/foreach}
				</select>
			</li>
			<li>
				<span class="select-box">
				<select name="suburb" class="region" id="state_subburb">
				{foreach from=$req.cities item=city}
				<option value="{$city.bu_suburb}.{$city.zip}"{$city.selected} title="{$city.bu_suburb}">{$city.bu_suburb}</option>
				{/foreach}
				</select>
				</span>
			</li>
			<li>
				<select name="distance" class="radius">
				{foreach from=$req.distance item=distance}
				<option value="{$distance}"{if $selectDistance eq $distance } selected="selected" {/if}>within {$distance} {$smarty.const.DISTANCE_SCALE}</option>
				{/foreach}
				</select>
			</li>
		</ol>
	</fieldset>
	<fieldset class="searchlocation">
		<input src="/skin/red/images/bu-search.gif" type="image" />
	</fieldset>
	</div>
	</form>
{include file="searchpanel.tpl"}

	<h2 style="color:#777;">{$req.counter|default:'0'} retailers found from your search {if $searchForm.keyword or $searchForm.bcategory_name or $searchForm.state_name neq -1 or $searchForm.distance}for{/if} <em style="font-weight:bold; font-size:14px;">{$searchForm.keyword} {if $searchForm.state_name neq -1}<font style="font-weight:normal">in</font>{/if} {if $searchForm.suburb}{$searchForm.suburb},{/if} {if $searchForm.state_name neq -1}{$searchForm.state_name}{/if} {if $searchForm.distance}within {$searchForm.distance} km{/if}</em></h2>

	{if $req.not_popularize_counter ne 0}
    <ul id="search-foodwine-list">
	    	{foreach from=$req.stores.not_popularize item=product}

	    	{if $product.bu_name ne ''}				
				<li>			
		        <div class="moreImg0_css">
		        <a href="/{$product.website_name}">
		        <img src="{$product.store_logo.text}" alt="{$product.bu_name}" title="{$product.bu_name}" width="{$product.store_logo.width}" height="{$product.store_logo.height}" border="0"/></a>
		        <div id="pid_{$product.pid}" class="moreImg_css"><img src="{$product.bimage.text}" style="width:{$product.bimage.width}px;height:{$product.bimage.height}px;"/></div><div id="pid_{$product.pid}_2" class="moreImg_arror"></div></div>
		        	<div id="context" style=" width:659px;">
                        <a href="/{$product.website_name}" class="arrowgrey">{$product.bu_name}</a>
                        <!--<a href="/{$product.website_name}/{$product.url_item_name}" class="arrowgrey">{$product.item_name}</a>-->
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
        {foreach from=$req.stores.popularize item=category key=k}

            {if $category.items and ($category.category_name ne '')}
                <h2 style="font-size:14px; color: #392f7e; padding: 0px; margin:15px 0 0 0; font-weight: bolder; height:20px; line-height:24px;">{$category.category_name}</h2>
                <li {if ($req.popularize_counter-1)==$k}style="border:none;"{/if}>
                <ul style="padding:0; margin:0;">
                {foreach from=$category.items item=store}
                <li >    
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
            {/if}
        {/foreach}
	</ul>
    {/if}
    
	<div id="paging-wide">
		&nbsp;{$req.linkStr}
    </div>

<div style="clear:both; width:auto; margin-bottom:15px; margin-top:10px;">
{include file="../seller_context.tpl" search_type="foodwine"}
</div>

<div style="background:url(/skin/red/images/foodwine/search_panel_bg.jpg) repeat-x left top;width:100%;height:38px;border-bottom:1px solid #ccc;">
	<form name="foodwine_time_search" id="foodwine_time_search" action="/foodwine/index.php" method="get">
	<a name="top" style=" visibility:hidden;"></a>
		<div style="float:left;padding-top:3px;padding-left:5px;">
			<strong>Show</strong>&nbsp;&nbsp;
			<select name="bcategory" class="select" onchange="this.form.submit();">
            	<option value="">All Categories</option>
		  		{foreach from=$lang.seller.attribute.5.subattrib item=l key=k}
					<option value="{$k}" {if $searchForm.bcategory eq $k}selected="selected"{/if}>{$l}</option>				
				{/foreach}
			</select>
		</div>
		<div style="float:right;padding-top:0; padding-right:5px;">
			<strong>Sort By</strong>&nbsp;&nbsp;
			<select name="sort" class="inputB" style="width:170px;" onchange="this.form.submit();">
                <option value="1" {if $searchForm.sort eq "1" || $searchForm.sort eq "" }selected{/if}>Latest Stores</option>
                <option value="5" {if $searchForm.sort eq "5"}selected{/if}>Alphabetical</option>
            </select>
		</div>
		<input type="hidden" name="cp" value="search" />
		<input type="hidden" name="e4c387b8cf9f" value="1" />
		<input type="hidden" name="keyword" value="{$searchForm.keyword}" />
		<input type="hidden" name="search_state_name" value="{$searchForm.state_name}" />
		<input type="hidden" name="suburb" value="{$searchForm.suburb}.{$searchForm.postcode}.{$searchForm.suburb_id}" />
		<input type="hidden" name="distance" value="{$searchForm.distance}" />
	</form>
</div>
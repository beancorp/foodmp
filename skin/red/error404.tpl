{literal}
<script type="text/javascript">
</script>
{/literal}
<div id="div_sitemap">
<h1 style="font-weight:bold; font-size:24px;">Hmm, the page you're looking for can't be found.</h1>
<h2 style="font-size:12px;">Did you try searching? Enter a keyword(s) in the search field. Or, try one of the links below.</h2>
<div class="sitemap_category">
<div style="" id="sitemap_category_list">
	{foreach from=$req.sitemap item=categoryList key=type}
        {if $type=='foodwine'}	
        <div class="sitemap_category_foodwine">	
            {if $type=='foodwine'}
            <h2>Food & Wine</h2>
            <ul class="col1">
                
                    <li class="level_1">
                        <ul>
                            {foreach from=$categoryList item=cate key=k}
                                    <li class="col_{$k}"><a href="/foodwine/index.php?cp=search&bcategory={$k}&e4c387b8cf9f=1&search_state_name=-1" title="{$cate}">{$cate}</a></li>
                            {/foreach}
                        </ul>
                    </li>
            </ul>
            
            {/if}
        </div>	
        <div class="clear"></div>
        {/if}
    {/foreach}
</div>
</div>
</div>


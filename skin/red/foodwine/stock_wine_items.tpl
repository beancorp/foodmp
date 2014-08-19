<style>
{literal}
@media only screen and (max-width: 767px), screen and (max-device-width: 720px) {
	#fan_button {
		top: 70px !important;
	}
}
{/literal}
</style>
<link type="text/css" href="/skin/red/css/foodwine.css" rel="stylesheet"/>
<a style=" visibility:hidden;" name="top"></a>
<div class="sellerhome_wine_items_list">
	<ul>
    {foreach from=$req.items item=c key=ck}
        <li style="width:743px; float:left;  margin:0;border-bottom:1px solid #CCC; {if $ck==0}padding:0 6px 12px 3px;{else}padding:12px 6px 12px 3px;{/if}">
            <h3 class="foodwine-h3">{$c.category_name} </h3>
            <ul style="width:745px;">
    			{foreach from=$c.products.items item=p key=pk}
                    <li class="product-item" style="float:{if $pk%2 eq 0}left{else}right; margin-right:10px;{/if}; width:350px; min-height:60px">
                        <div class="desc">
                            <div class="name"><a title="{$p.item_name}" href="/{$req.info.url_bu_name}/{$p.url_item_name}">{$p.item_name|truncate:60:"..."}</a>
                            {if $p.price neq '0.00'}
                            <span>
                            {if $p.priceorder eq 1}
                                {$p.unit} ${$p.price}
                            {else}
                                ${$p.price} {$p.unit}
                            {/if}
                            </span>
                            {/if}
                            </div>
                            <div class="desc-1" style="width:350px;"><span>{$p.description|truncate:180:"..."}</span></div>
                        </div>
                    </li>
    			{/foreach}
                <div class="clear"></div>
            </ul>
        </li>
<div class="clear"></div>
    {/foreach}
    </ul>
</div>
<div class="clear"></div>
<div style="padding:30px 0 16px;"><a href="/soc.php?cp=bookonline&StoreID={$req.info.StoreID}" title="Book Online"><img src="/skin/red/images/foodwine/menu-bookonline.jpg" /></a></div>
<div class="clear"></div>
<!--<div class="stock_items_bottom">
	<a href="#top" class="back_to_top" title="Back to top" style="width:489px;background:#{$req.template.bgcolor};">Back to top</a>
</div>-->
<div id="paging" style="width:742px;background:#{$req.template.bgcolor};"><a href="#top" class="back_to_top" title="Back to top">Back to top</a></div>

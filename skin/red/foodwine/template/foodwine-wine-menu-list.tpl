<link type="text/css" href="/skin/red/css/foodwine.css" rel="stylesheet"/>
<h2 style="width:489px;background:#{$req.template.bgcolor}">{$req.info.bu_name}<a href="javascript:history.go(-1);void(0);">Back</a></h2>
<div class="sellerhome_wine_items_list" style="position:relative; top:-25px;">
	{if $req.items}
    {foreach from=$req.items item=c key=ck}
    {if $c.flag eq 0}
	<ul class="{if count($req.items)%2 eq 1 && $ck eq (count($req.items)-1)}level-1-1{else}level-1-2{/if}">
    {/if}
        <li style="float:{if $ck%2 eq 0}left{else}right{/if};">
            <h3 class="foodwine-h3">{$c.category_name} </h3>
            <ul>
    			{foreach from=$c.products.items item=p key=pk}
                    <li class="left product-item">
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
                            <div class="desc-1"><span>{$p.description|truncate:120:"..."}</span></div>
                        </div>
                    </li>
    			{/foreach}
            </ul>
        </li>
    {if $c.flag eq 1}
    </ul>
<div class="clear"></div>
    {/if}
    {/foreach}
    {/if}
</div>
<div class="clear"></div>
{include file="foodwine/template/foodwine-bottom-announcement.tpl"}
{include file="foodwine/template/foodwine-wine-bottom-button.tpl"}
<div id="paging" style="width:489px;background:#{$req.template.bgcolor}; padding-right:10px;">&nbsp;</div>

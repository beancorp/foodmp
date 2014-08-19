<h2 style="width:489px;background:#{$req.template.bgcolor}">{$req.info.bu_name} <a href="javascript:history.go(-1);void(0);">Back</a></h2>
<div class="sellerhome_items_list" style="position:relative;">
	<ul style="z-index:100; position:relative;">
	{if $req.items.stock_items}
    <li id="stock_items_heading" style="float:left; border:none; height:auto; padding:0">
    	<h3 class="foodwine-h3">{if $req.info.menu_type}Menu{else}Stock Items{/if} 
		<div>
		<fb:like href="{$smarty.const.SOC_HTTP_HOST}foodwine/index.php?act=product&cp=list&type=stock&StoreID={$req.info.StoreID}" send="false" width="150" show_faces="false" font="arial" style="padding-top:5px;"></fb:like></div>
		</h3>
    </li>
    {/if}
	{if $req.items.specials}
    <li id="special_items_heading" style="float:right; border:none; height:auto; padding:0;">
    	<h3 class="foodwine-h3" style="color:#FF9900;">Specials 
		<div style="z-index:100;">
		<fb:like href="{$smarty.const.SOC_HTTP_HOST}foodwine/index.php?act=product&cp=list&type=special&StoreID={$req.info.StoreID}" send="false" width="150" show_faces="false" font="arial" style="padding-top:5px;"></fb:like></div>
		</h3>
    </li>
    {/if}
    </ul>
	
	<ul style="z-index:2; position:relative; clear:both;">
	{if $req.items.stock_items}
    <li id="retailer_stock_items" style="float:left; border:none; height:auto; padding:0">
        <ul>
            {foreach from=$req.items.stock_items item=p key=k}
                <li class="left">
            		{if $p.small_image && $p.small_image neq '/images/243x212.jpg'}
                    <div class="img"><a title="{$p.item_name}" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}"><img src="{if $p.small_image}{$smarty.const.SOC_HTTP_HOST}{$p.small_image}{else}{$smarty.const.SOC_HTTP_HOST}images/80x58.jpg{/if}" width="80" height="58" alt="{$p.name}" title="{$p.name}"/></a>
                    </div>
					{else}
					<div class="img"><a title="{$p.item_name}" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}"><img src="{$smarty.const.SOC_HTTP_HOST}template_images/products/{$req.info.subAttrib}.jpg" width="80" height="58" alt="{$p.name}" title="{$p.name}"/></a>
                    </div>
					{/if}
                    <div class="desc">
                        <div class="name"><a title="{$p.item_name}" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}">{$p.item_name|truncate:60:"..."}</a></div>
                        {if $p.price neq '0.00'}
                        <div class="price">
                        {if $p.priceorder eq 1}
                            {$p.unit} ${$p.price}
                        {else}
                            ${$p.price} {$p.unit}
                        {/if}
                        </div>
                        {/if}
                    </div>
                </li>
            {/foreach}
        </ul>
        <div style="padding:10px 13px 35px 0 ; float:right;"><strong><a href="{$smarty.const.SOC_HTTP_HOST}foodwine/index.php?act=product&cp=list&type=stock&StoreID={$req.info.StoreID}">{if $req.info.menu_type}Full Menu{else}All Stock Items{/if}</a>&nbsp;&nbsp;({$req.stock_item_num})</strong></div>
    </li>
    {/if}
	{if $req.items.specials}
    <li id="retailer_specials_list" style="float:right; border:none; height:auto; padding:0;">
		<ul style="z-index:2">
		{foreach from=$req.items.specials item=p key=k}
			<li class="right">
				{if $p.small_image && $p.small_image neq '/images/243x212.jpg'}
				<div class="img"><a title="{$p.item_name}" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}"><img src="{if $p.small_image}{$smarty.const.SOC_HTTP_HOST}{$p.small_image}{else}{$smarty.const.SOC_HTTP_HOST}images/80x58.jpg{/if}" width="80" height="58" alt="{$p.name}" title="{$p.name}"/></a>
				</div>
				{else}
				<div class="img"><a title="{$p.item_name}" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}"><img src="{$smarty.const.SOC_HTTP_HOST}template_images/products/{$req.info.subAttrib}.jpg" width="80" height="58" alt="{$p.name}" title="{$p.name}"/></a>
				</div>
				{/if}
				<div class="desc">
					<div class="name"><a title="{$p.item_name}" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}">{$p.item_name|truncate:60:"..."}</a></div>
					<div class="price-special">
                    {if $p.priceorder eq 1}
                		{$p.unit} ${$p.price}
                    {else}
                        ${$p.price} {$p.unit}
                    {/if}
                    </div>
				</div>
			</li>
		{/foreach}
        </ul>
        <div style="padding:10px 13px 35px 0 ; float:right;"><strong><a href="{$smarty.const.SOC_HTTP_HOST}foodwine/index.php?act=product&cp=list&type=special&StoreID={$req.info.StoreID}">All Specials</a>&nbsp;&nbsp;({$req.special_num})</strong></div>
    </li>
    {/if}
    </ul>
</div>
<div class="clear"></div>
{include file="foodwine/template/foodwine-bottom-announcement.tpl"}
<div id="paging" style="width:489px;background:#{$req.template.bgcolor};">&nbsp;</div>

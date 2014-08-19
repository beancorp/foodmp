<link type="text/css" href="{$smarty.const.SOC_HTTP_HOST}skin/red/css/foodwine.css" rel="stylesheet"/>
<h2 style="width:489px;background:#{$req.template.bgcolor}">{$req.info.bu_name}<a href="javascript:history.go(-1);void(0);">Back</a></h2>

<div class="sellerhome_items_list" style="position:relative;">
	<div id="special_items_heading">
		<h3 class="foodwine-h3" style="color:#FF9900; float:left;">Specials </h3>
		<div style=" position:relative; float:left; width:300px; left: 15px; z-index: 100; top: -4px;"><fb:like href="{$smarty.const.SOC_HTTP_HOST}foodwine/index.php?act=product&cp=list&type=special&StoreID={$req.info.StoreID}" send="false" width="auto" show_faces="false" font="arial" style="padding-top:5px;"></fb:like></div>
	</div>
	<div class="clear"></div>
 
	{if $req.items.specials}
	<ul style="position:relative; z-index:2;">
		{foreach from=$req.items.specials item=p key=k}
			<li class="{if $k%2 eq 0}left{else}right{/if} {if $k<2}top{else}ntop{/if}" style="z-index:{php}echo 1000-$i;$i++;{/php};">
            	{if $p.small_image && $p.small_image neq '/images/243x212.jpg'}
				<div class="img"><a title="{$p.item_name}" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}"><img src="{$smarty.const.SOC_HTTP_HOST}{if $p.small_image}{$p.small_image}{else}images/80x58.jpg{/if}" width="80" height="58" alt="{$p.name}" title="{$p.name}"/></a>
				</div>
				{else}
				<div class="img"><a title="{$p.item_name}" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}"><img src="{$smarty.const.SOC_HTTP_HOST}template_images/products/{$req.info.subAttrib}.jpg" width="80" height="58" alt="{$p.name}" title="{$p.name}"/></a>
				</div>
				{/if}
				<div class="desc">
					<div class="name"><a title="{$p.item_name}" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}">{$p.item_name|truncate:60:"..."}</a></div>
                    {if $p.price neq '0.00'}
					<div class="price-special">
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
    {/if}
</div>
<div class="clear"></div>
<div id="view_all_link" style="padding:10px 13px 35px 0 ; float:right;"><strong><a href="{$smarty.const.SOC_HTTP_HOST}foodwine/index.php?act=product&cp=list&type=special&StoreID={$req.info.StoreID}">All Specials</a>&nbsp;&nbsp;({$req.special_num})</strong></div>
<div class="clear"></div>
{include file="foodwine/template/foodwine-bottom-announcement.tpl"}
<div id="paging" style="width:489px;background:#{$req.template.bgcolor};">&nbsp;</div>

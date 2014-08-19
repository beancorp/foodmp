<link type="text/css" href="{$smarty.const.SOC_HTTP_HOST}skin/red/css/foodwine.css" rel="stylesheet"/>
<h2 style="width:489px;background:#{$req.template.bgcolor}">{$req.info.bu_name}<a href="javascript:history.go(-1);void(0);">Back</a></h2>

<div class="sellerhome_items_list" style="position:relative;">
	<h3 class="foodwine-h3" style="color:#FF9900; float:left;">Specials </h3>
	<div style=" position:relative; float:left; width:350px; left: 15px; z-index: 100; top: -4px;"><fb:like href="{$smarty.const.SOC_HTTP_HOST}foodwine/index.php?act=product&cp=list&type=wine&s=1&StoreID={$req.info.StoreID}" send="false" width="auto" show_faces="false" font="arial" style="padding-top:5px;"></fb:like></div>
	<div class="clear"></div>
	
	{if $req.specials.items}
	<ul style="position:relative; z-index:2;">
		{foreach from=$req.specials.items item=p key=k}
			<li class="left {if $k<2}top{else}ntop{/if}" style="z-index:{php}echo 1000-$i;$i++;{/php}; width:100%; height:95px;">
            	{if $p.small_image && $p.small_image neq '/images/243x212.jpg'}
				<div class="img" style="height:90px; width:120px;"><a title="{$p.item_name}" href="{$smarty.const.SOC_HTTP_HOST}/{$req.info.url_bu_name}/{$p.url_item_name}"><img src="{$smarty.const.SOC_HTTP_HOST}{if $p.small_image}{$p.small_image}{else}/images/80x58.jpg{/if}" width="120" height="90" alt="{$p.name}" title="{$p.name}"/></a>
				</div>
                {else}
				<div class="img" style="height:90px; width:120px;"><a title="{$p.item_name}" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}"><img src="{$smarty.const.SOC_HTTP_HOST}template_images/products/{$req.info.subAttrib}.jpg" width="120" height="90" alt="{$p.name}" title="{$p.name}"/></a>
				</div>
				{/if}
				<div class="desc" style="{if $p.small_image && $p.small_image neq '/images/243x212.jpg'}margin-left:100px;{/if} margin-top:25px;">
					<div class="name"><a title="{$p.item_name}" href="{$smarty.const.SOC_HTTP_HOST}/{$req.info.url_bu_name}/{$p.url_item_name}">{$p.item_name|truncate:60:"..."}</a></div>
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
<div style="padding:10px 13px 40px 0; float:right;"><strong><a href="{$smarty.const.SOC_HTTP_HOST}/foodwine/index.php?act=product&cp=list&type=wine&s=1&StoreID={$req.info.StoreID}">All Specials</a>&nbsp;&nbsp;({$req.specials.total})</strong></div>
<div class="clear"></div>
{include file="foodwine/template/foodwine-bottom-announcement.tpl"}
{include file="foodwine/template/foodwine-wine-bottom-button.tpl"}
<div id="paging" style="width:489px;background:#{$req.template.bgcolor};">&nbsp;</div>

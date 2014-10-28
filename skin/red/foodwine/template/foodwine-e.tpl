{include_php file='include/jssppopup.php'}

<div id="seller" class="template-e">
{if $req.template.LogoDisplay eq '1'}
  {if $req.images.mainImage.2.bname.text neq '/images/242x201.jpg'}
	  <div id="store_logo" align="center" style="width:242px;padding:0">
	  <img src="{$smarty.const.SOC_HTTP_HOST}{$req.images.mainImage.2.bname.text}" width="{$req.images.mainImage.2.bname.width}" height="{$req.images.mainImage.2.bname.height}"/>
	  </div>
	  <div id="store_logo_gap" style="height:5px;"></div>
  {else}
	  <div id="store_logo" align="center" style="width:250px;padding:0">
	  {if $req.info.subAttrib eq 8}
		<img src="{$smarty.const.SOC_HTTP_HOST}/template_images/default_fastfood.jpg" width="{$req.images.mainImage.2.bname.width}" />
	  {elseif $req.info.subAttrib eq 1}
		<img src="{$smarty.const.SOC_HTTP_HOST}/template_images/default_restaurants.png" width="{$req.images.mainImage.2.bname.width}" />
	  {else}
		<img src="{$smarty.const.SOC_HTTP_HOST}/template_images/default_wine_logo.jpg" width="{$req.images.mainImage.2.bname.width}" />
	  {/if}
	  </div>
	  <div id="store_logo_gap" style="height:5px;"></div>
  {/if}
{/if}

{include file='foodwine/template/foodwine-summary.tpl'}
</div>
<div id="products">
{if $req.images.mainImage.0.bname.text neq '/images/497x206.jpg'}
<img src="{$smarty.const.SOC_HTTP_HOST}{$req.images.mainImage.0.bname.text}" width="{$req.images.mainImage.0.bname.width}" height="{$req.images.mainImage.0.bname.height}"/>
{else}
<img src="{$smarty.const.SOC_HTTP_HOST}/template_images/featured/{$req.info.subAttrib}.jpg" width="497px" height="200px"/>
{/if}
{include file='foodwine/template/foodwine-wine-specials2-list.tpl'}
</div>
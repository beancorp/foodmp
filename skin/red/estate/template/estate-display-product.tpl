<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
{include_php file='include/jssppopup.php'}
{literal}
<script language="javascript">
window.onload=function(){
Nifty("div#seller-left-head","medium tl ");
Nifty("div#seller-infonew","big bl");
}
</script>
{/literal}
{if $req.template.TemplateName eq 'estate-b'}
{if $req.info.images.mainImage.1.bname.text neq '/images/750x50.jpg'}
<div style="width:755px; margin-bottom:5px;"><img src="{$req.info.images.mainImage.1.bname.text}" width="{$req.info.images.mainImage.1.bname.width}" height="{$req.info.images.mainImage.1.bname.height}"/></div>
{/if}
{/if}
<div id="seller" class="estate-display-product">
{if $req.template.TemplateName eq 'estate-a'}
{if $req.info.images.mainImage.0.bname.text neq '/images/243x100.jpg'}
<img src="{$req.info.images.mainImage.0.bname.text}" width="{$req.info.images.mainImage.0.bname.width}" height="{$req.info.images.mainImage.0.bname.height}"/>
	<div style="height:5px;"></div>
{/if}{/if}
{include file='estate/template/estate-display-product-summary.tpl'}
</div>
<div id="products">
{include file='estate/template/estate-display-product-content.tpl'}
</div>
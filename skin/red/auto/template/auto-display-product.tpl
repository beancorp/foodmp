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
{if $req.info.subAttrib eq '2'}
{if $req.template.TemplateName eq 'auto-b'}
{if $req.info.images.mainImage.1.bname.text neq '/images/755x100.jpg'}
<div style="width:755px; margin-bottom:5px;"><img src="{$req.info.images.mainImage.1.bname.text}" width="{$req.info.images.mainImage.1.bname.width}" height="{$req.info.images.mainImage.1.bname.height}"/></div>
{/if}
{/if}{/if}
<div id="seller" class="auto-display-product">
{if $req.info.subAttrib eq '2'}
{if $req.template.TemplateName eq 'auto-a'}
{if $req.info.images.mainImage.0.bname.text neq '/images/243x100.jpg'}
<img src="{$req.info.images.mainImage.0.bname.text}" width="{$req.info.images.mainImage.0.bname.width}" height="{$req.info.images.mainImage.0.bname.height}"/>
	<div style="height:5px;"></div>
{/if}{/if}{/if}
{include file='auto/template/auto-display-product-summary.tpl'}
</div>
<div id="products">
{include file='auto/template/auto-display-product-content.tpl'}
</div>
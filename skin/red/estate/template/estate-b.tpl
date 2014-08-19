<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
{include_php file='include/jssppopup.php'}

{if $req.images.mainImage.1.bname.text neq '/images/750x50.jpg'}
<div style="width:755px; margin-bottom:5px;"><img src="{$req.images.mainImage.1.bname.text}" width="{$req.images.mainImage.1.bname.width}" height="{$req.images.mainImage.1.bname.height}"/></div>
{/if}

<div id="seller">
{include file='estate/template/estate-summary.tpl'}
</div>
<div id="products">
{include file='estate/template/estate-product-list.tpl'}
</div>
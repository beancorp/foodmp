{include_php file='include/jssppopup.php'}
{literal}
<script language="javascript">
window.onload=function(){
Nifty("div#seller-left-head","medium tl ");
Nifty("div#seller-infonew","big bl");
}
</script>
{/literal}
<div id="seller">
{include file='foodwine/template/foodwine-summary.tpl'}
</div>
<div id="products">
{include file='foodwine/template/foodwine-display-product-content.tpl'}
</div>
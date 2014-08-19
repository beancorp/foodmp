<link type="text/css" href="/skin/red/css/foodwine.css" rel="stylesheet"/>
<div id="seller_recipes">
<div style="float:left; width:380px; min-height:140px;">
<h1 style="margin-top:0; color:#3C3481; font-weight:bold">{$req.info.title}</h1>
<h2 class="recipe-h2">Ingredients</h2>
{$req.info.content}
</div>
{if $req.info.picture}
<div style="float:right; padding-right:10px;"><img src="{$req.info.picture}" width="350" /></div>
{/if}
</div>
{if $req.info.method}
<div class="clear"></div>
<h2 class="recipe-h2">Method</h2>
<div id="seller_recipes_method" style="padding:0 0 40px 0;">{$req.info.method}</div>
{/if}
{if $req.preview}
<div class="clear"></div>
<input type="image" style="float:right" border="0" value="Previous" class="preview-emailalerts" onclick="location.href='/foodwine/?act=recipes&cp=edit&preview=1';" src="/skin/red/images/foodwine/edit-emailalerts.jpg" />
{/if}
<div class="clear"></div>
<div id="paging" style="width:742px;background:#{$templateInfo.bgcolor};">{if $req.pre_info}<a href="/soc.php?cp=recipes&StoreID={$req.info.StoreID}&rid={$req.pre_info.id}" class="pre-item" title="{$req.pre_info.title}">{$req.pre_info.title}</a>{/if}{if $req.next_info}<a href="/soc.php?cp=recipes&StoreID={$req.info.StoreID}&rid={$req.next_info.id}" class="back_to_top next-item" title="{$req.next_info.title}">{$req.next_info.title}</a>{/if}&nbsp;</div>
<ul id="table" style="width:850px;">
		<li class="tabletop" style="width:120px;"><a href="#" onclick="javascript:xajax_getPromotionList('{$req.promotlist.sort.page}','attribute','{if $req.promotlist.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Market Place</a>{if $req.promotlist.sort.field eq 'attribute'}{if $req.promotlist.sort.order eq 'ASC'}&darr;{elseif $req.promotlist.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:100px;"><a href="#" onclick="javascript:xajax_getPromotionList('{$req.promotlist.sort.page}','addtime','{if $req.promotlist.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.promotion.lb_id}</a>{if $req.promotlist.sort.field eq 'addtime'}{if $req.promotlist.sort.order eq 'ASC'}&darr;{elseif $req.promotlist.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:120px;"><a href="#" onclick="javascript:xajax_getPromotionList('{$req.promotlist.sort.page}','promotion','{if $req.promotlist.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.promotion.lb_code}</a>{if $req.promotlist.sort.field eq 'promotion'}{if $req.promotlist.sort.order eq 'ASC'}&darr;{elseif $req.promotlist.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:180px;"><a href="#" onclick="javascript:xajax_getPromotionList('{$req.promotlist.sort.page}','user','{if $req.promotlist.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.promotion.lb_user}</a>{if $req.promotlist.sort.field eq 'user'}{if $req.promotlist.sort.order eq 'ASC'}&darr;{elseif $req.promotlist.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:90px;"><a href="#" onclick="javascript:xajax_getPromotionList('{$req.promotlist.sort.page}','usedtime','{if $req.promotlist.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.promotion.lb_usedate}</a>{if $req.promotlist.sort.field eq 'usedtime'}{if $req.promotlist.sort.order eq 'ASC'}&darr;{elseif $req.promotlist.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:80px;"><a href="#" onclick="javascript:xajax_getPromotionList('{$req.promotlist.sort.page}','Isused','{if $req.promotlist.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.promotion.lb_status}</a>{if $req.promotlist.sort.field eq 'Isused'}{if $req.promotlist.sort.order eq 'ASC'}&darr;{elseif $req.promotlist.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:120px;">{$lang.promotion.lb_opt}</li>
		<div style="clear:both;"></div>
		{if $req.promotlist.list}
		{foreach from=$req.promotlist.list item=l}
		
		<li style="width:120px;">{if $l.attribute eq '0'}Buy & Sell{elseif $l.attribute eq '1'}Real Estate{elseif $l.attribute eq '2'}Automotive{elseif $l.attribute eq '3'}Careers{elseif $l.attribute eq '5'}Food & Wine{else}All{/if}</li>
		<li style="width:100px;">{$l.addtime|date_format:"$PBDateFormat"}</li>
		<li style="width:120px;">{$l.promotion}</li>
		<li style="width:180px;" title="{$l.user}">{$l.user|truncate:21}</li>
		<li style="width:90px;">{if $l.usedtime}{$l.usedtime|date_format:"$PBDateFormat"}{/if}</li>
		<li style="width:80px;">{if $l.Isused eq '0'}New{else}Used{/if}</li>
		<li style="width:120px;">{if $l.Isused eq '0'}<input name="button2" type="button" class="hbutton" value="&nbsp;{$lang.but.edit}&nbsp;" onclick="javascript:xajax_getPromotionById('{$l.id}');">&nbsp;<input name="button2" type="button" class="hbutton" value="{$lang.but.delete}" onclick="javascript:if(confirm('{$lang.pub_clew.delete}')) deleteRecord('{$l.id}');">{/if}</li>
		<label>
		</label>
		{/foreach}
		<li style="width:720px; height:30px; background:#ffffff;">{$req.promotlist.links.all}</li>
		{else}
		<li style="width:720px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
		{/if}
</ul>
<input name="pageno" type="hidden" id="pageno" value="{$req.promotlist.sort.pageno}"/>
<input name="field" type="hidden" id="field" value="{$req.promotlist.sort.field}"/>
<input name="order" type="hidden" id="order" value="{$req.promotlist.sort.order}"/>
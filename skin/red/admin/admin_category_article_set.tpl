<link href="css/global.css" rel="stylesheet" type="text/css" />
<div id="content" align="center">
  <div >
	{if $req.catlist.list }
		<label>
		<select name="select" onchange="window.location.href='?act=pro&amp;cp=catartset&amp;cgid=' + this.options[this.selectedIndex].value;">
		{foreach from=$req.catlist.list item=l}
		  <option value="{$l.id}" {if $l.id eq $req.list.cgid} selected="selected" {/if}>{$l.name}</option>
	  	{/foreach}
	    </select>
		</label>
	{/if}  &nbsp;&nbsp;<a href="?act=pro&amp;cp=catartset&op=edit&cgid={$req.list.cgid}&p={$req.list.page}">Add New Article</a>	</div>
	
<div id="ajaxmessage" align="center"></div>


<div id="tabledatalist">
  <ul id="table" style="width:700px;">
	<li class="tabletop" style="width:80px;"><a href='index.php?act=pro&cp=catartset&cgid={$req.list.cgid}&amp;p={$req.list.page}&amp;field=id&amp;order={if $req.list.order eq 'ASC'}DESC{else}ASC{/if}'>{$lang.cgart.lb_id}</a>{if $req.list.field eq 'id'}{if $req.list.order eq 'ASC'}&darr;{elseif $req.list.order eq 'DESC'}&uarr;{/if}{/if}</li>
	<li class="tabletop" style="width:80px;"><a href='index.php?act=pro&cp=catartset&cgid={$req.list.cgid}&amp;p={$req.list.page}&amp;field=cgid&amp;order={if $req.list.order eq 'ASC'}DESC{else}ASC{/if}'>{$lang.cgart.lb_cgid}</a>{if $req.list.field eq 'cgid'}{if $req.list.order eq 'ASC'}&darr;{elseif $req.list.order eq 'DESC'}&uarr;{/if}{/if}</li>
	<li class="tabletop" style="width:300px;"><a href='index.php?act=pro&cp=catartset&cgid={$req.list.cgid}&amp;p={$req.list.page}&amp;field=title&amp;order={if $req.list.order eq 'ASC'}DESC{else}ASC{/if}'>{$lang.cgart.lb_title}</a>{if $req.list.field eq 'title'}{if $req.list.order eq 'ASC'}&darr;{elseif $req.list.order eq 'DESC'}&uarr;{/if}{/if}</li>
	<li class="tabletop" style="width:80px;"><a href='index.php?act=pro&cp=catartset&cgid={$req.list.cgid}&amp;p={$req.list.page}&amp;field=state&amp;order={if $req.list.order eq 'ASC'}DESC{else}ASC{/if}'>{$lang.cgart.lb_state}</a>{if $req.list.field eq 'state'}{if $req.list.order eq 'ASC'}&uarr;{elseif $req.list.order eq 'DESC'}&darr;{/if}{/if}</li>
	<li class="tabletop" style="width:120px;">{$lang.cgart.lb_operate}</li>
		
  {if $req.list.categoryArticleList}
		{foreach from=$req.list.categoryArticleList item=l}

	<li style="width:80px;">{$l.id}</li>
	<li style="width:80px;">{$l.cgid}</li>
	<li style="width:300px;">{$l.title}</li>
	<li style="width:80px;">{$lang.cgart.state[$l.state].name}</li>
	<li style="width:120px;">
	<input name="button" type="button" class="hbutton" value=" {$lang.but.edit} " onclick="javascript:location.href='?act=pro&amp;cp=catartset&amp;op=edit&id={$l.id}&amp;cgid={$req.list.cgid}&amp;p={$req.list.page}';">
	<input name="delete" type="button" class="hbutton" value="{$lang.but.delete}" onclick="javascript: if(confirm('{$lang.pub_clew.delete}'))xajax_deleteCategoryArticle({$l.id},'?act=pro&amp;cp=catartset&amp;cgid={$req.list.cgid}&amp;p={$req.list.page}');">
	</li>
  {/foreach}
  	<li style="width:700px; height:30px; background:#ffffff;">{$req.list.page_navi}</li>
	{else}
    <li style="width:700px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
   {/if}
   </ul>
</div>

</div>
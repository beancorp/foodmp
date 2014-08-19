<link href="css/global.css" rel="stylesheet" type="text/css" />

<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	<div align="center" style="border-bottom-color:#999999;" id="feat_list">
  <form id="mainForm" name="mainForm" method="post" action="" onsubmit="javascript: xajax_saveFeaturedCategories(xajax.getFormValues('mainForm')); return false;">

		<ul id="table" style="width:700px;">
		<li class="tabletop" style="width:100px;">#  </li>
		<li class="tabletop" style="width:100px;"><a href="#" onclick="javascript:xajax_getfeathProCat('0','id','{if $req.categoryList.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.pro.lb_cat_id}</a>{if $req.categoryList.sort.field eq 'id'}{if $req.categoryList.sort.order eq 'ASC'}&darr;{elseif $req.categoryList.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:250px;"><a href="#" onclick="javascript:xajax_getfeathProCat('0','name','{if $req.categoryList.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.pro.lb_cat_name}</a>{if $req.categoryList.sort.field eq 'name'}{if $req.categoryList.sort.order eq 'ASC'}&darr;{elseif $req.categoryList.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:200px;">{$lang.pro.lb_cat_order}</li><br />
		{if $req.categoryList.list}
		{foreach from=$req.categoryList.list item=cl}
		<li style="width:100px;"><input type="checkbox" name="category[{$cl.id}]" value="{$cl.id}" {if $cl.isfeatured eq 1}checked{/if}></li>
		<li style="width:100px;">{$cl.id}</li>
		<li style="width:250px;">{$cl.name}</li>
		<li style="width:200px;"><input name="fsort[{$cl.id}]" type="text" size="2" value="{if $cl.isfeatured eq 1}{$cl.fsort}{/if}"></li>

		{/foreach}
		<li style="width:700px; background:#FFFFFF;"><input type="submit" name="submit" class="hbutton" value="Update Featured Categories"></li>
		{else}
		<li style="width:700px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
		{/if}
		</ul>
  </form>
  </div>


<link href="css/global.css" rel="stylesheet" type="text/css" />
{if $req.display eq ''}
	{if !$req.nofull}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:720px;">
	<ul>
	<li id="lable" style="width:280px;">Parent Category: </li>
	<li id="input2" style="width:400px;">
	<select name="cateParent" onchange="javascript: xajax_getProductCategory(this.options[this.options.selectedIndex].value);">
			<option value="0" {if !$req.select.fid}selected{/if}>All Categories</option>
			{foreach from=$req.cateParent.list item=cp}
			<option value="{$cp.id}" {if $cp.id eq $req.select.fid}selected{/if}>{$cp.name}</option>
			{/foreach}
		  </select> &nbsp;<input name="button" type="button" class="hbutton" value="{$lang.but.add}" onclick="javascript:xajax_categoryAdd(xajax.getFormValues('mainForm'));">
	</li>
	</ul>
	</div>
	<form id="mainForm" name="mainForm" action="" method="post" onsubmit="javascript: xajax_categoryUpdateOperate(xajax.getFormValues('mainForm')); return false;">
	<div id="tabledatalist">
	{/if}
		<ul id="table" style="width:700px;">
		<li class="tabletop" style="width:80px;">#  </li>
		<li class="tabletop" style="width:80px;"><a href="#" onclick="javascript:xajax_getProductCategory('{$req.categoryList.fid}','id','{if $req.categoryList.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.pro.lb_cat_id}</a>{if $req.categoryList.sort.field eq 'id'}{if $req.categoryList.sort.order eq 'ASC'}&darr;{elseif $req.categoryList.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:200px;"><a href="#" onclick="javascript:xajax_getProductCategory('{$req.categoryList.fid}','name','{if $req.categoryList.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.pro.lb_cat_name}</a>{if $req.categoryList.sort.field eq 'name'}{if $req.categoryList.sort.order eq 'ASC'}&darr;{elseif $req.categoryList.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:80px;"><a href="#" onclick="javascript:xajax_getProductCategory('{$req.categoryList.fid}','sort','{if $req.categoryList.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.pro.lb_cat_order}</a>{if $req.categoryList.sort.field eq 'sort'}{if $req.categoryList.sort.order eq 'ASC'}&darr;{elseif $req.categoryList.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:200px;">{$lang.cgart.lb_operate}</li>
		{if $req.categoryList.list}
		{foreach from=$req.categoryList.list item=cl}
		<li style="width:80px;"></li>
		<li style="width:80px;">{$cl.id}</li>
		<li style="width:200px;">{$cl.name}</li>
		<li style="width:80px;">{$cl.sort}</li>
		<li style="width:200px;">
		<input name="button" type="button" class="hbutton" value=" {$lang.but.edit} " onclick="javascript:xajax_categoryUpdate({$cl.id});">
		<input name="button" type="button" class="hbutton" value="{$lang.but.delete}" onclick="javascript:xajax_categoryDelete({$cl.id});">
		</li>
		{/foreach}
		{else}
		<li style="width:700px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
		{/if}
		</ul>
	{if !$req.nofull }
	</div>
	<input name="fid" type="hidden" id="fid" value="{$req.categoryList.fid}"/>
	<input name="field" type="hidden" id="field" value="{$req.categoryList.sort.field}"/>
	<input name="order" type="hidden" id="order" value="{$req.categoryList.sort.order}"/>
	</form>
	</div>
	{/if}
	
{elseif $req.display eq 'update'}
	<div id="input-table" style="width:700px;">
		<li id="lable" style="width:280px;"></li>
		<li id="input2" style="width:400px;"></li>
		
		<li id="lable" style="width:280px;">Category Name: </li>
		<li id="input2" style="width:400px;"><input name="name" id="name" style="width:238px" value="{$req.category.name}" size="30" maxlength="100" /></li>
		
		<li id="lable" style="width:280px;">Category Order: </li>
		<li id="input2" style="width:400px;"><input name="order" id="order" style="width:238px" value="{$req.category.sort}" size="30" maxlength="12" /></li>
		
		<li id="lable" style="width:280px;">Category Image: </li>
		<li id="input2" style="width:400px;"><input name="image" id="image" style="width:238px" value="{$req.category.image}" size="30" /></li>
		
		<li id="lable" style="width:280px;"></li>
		<li id="input2" style="width:400px;"><input name="Submit1" type="submit" class="hbutton" id="Submit1" value="{$lang.but[$req.category.operate]} Category" /> 
		<input name="Submit12" type="button" class="hbutton" id="Submit12" value="{$lang.but.back}" onclick="javascript: xajax_getProductCategory(xajax.$('fid').value);"/>
		<input name="operate" type="hidden" id="operate" value="{$req.category.operate}"/><input name="id" type="hidden" id="id" value="{$req.category.id}"/>
		</li>
	</div>
{/if}
<link href="css/global.css" rel="stylesheet" type="text/css">
{if $req.display eq ''}
	{if !$req.nofull}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:750px;">
	<ul> 
	<form id="searchForm" name="searchForm" method="post" action="" onSubmit="javascript:xajax_getSeasonProductListSearch(xajax.getFormValues('searchForm')); return false;">
	</li>
     
	<li id="lable">Season</li>
	<li id="input2" style="width:200px;">
	<select name="season">
		<option value=""></option>
    {foreach from=$req.seasons item=season}
		<option value="{$season.id}" {$season.selected}>{$season.title} - {$season.desc}</option>
    {/foreach}
	</select>
	</li>
	
	<li id="lable">Type</li>
	<li id="input2" style="width:200px;">
	<select name="typeid">
		<option value=""></option>
		<option value="1">Fruit</option>
		<option value="2">Vegetables</option>
	</select>
	</li>
	<li id="lable">Keyword</li>
	<li id="input2" style="width:200px;">
	<input type="text" class="inputB" name="keyword" style="width:240px;"/>
	</li>
	
	<li id="lable"></li>
	<li id="input2" style="width:250px; "><table cellspacing="0" cellpadding="0"><tr><td><input name="search" class="hbutton" type="submit" id="search" value="  {$lang.but.search}  " /></td><td>&nbsp;</td></tr></table></li>
    
   
	</form>
	</ul>
	<div style="clear:both;"></div>
	</div>
	<form id="mainForm" name="mainForm" enctype="multipart/form-data" method="post" action="?act=adv&amp;cp=all" onsubmit="//xajax_saveBannerAllAndDefault(); return false;">
	<div id="tabledatalist" class="wrap">
	{/if}
		<ul id="table" style="width:890px;">
		<li class="tabletop" style="width:40px;"><a href="#" onclick="javascript:xajax_getSeasonProductList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','id','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.race.lb_id}</a>{if $req.list.sort.field eq 'id'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:250px;"><a href="#" onclick="javascript:xajax_getSeasonProductList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','title','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Name</a>{if $req.list.sort.field eq 'title'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:250px;"><a href="#" onclick="javascript:xajax_getSeasonProductList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','season_id','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Season</a>{if $req.list.sort.field eq 'season_id'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:120px;"><a href="#" onclick="javascript:xajax_getSeasonProductList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','typeid','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Type</a>{if $req.list.sort.field eq 'typeid'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:190px;">Record Operate</li>
		<div style="clear:both;"></div>
		{if $req.list.list}
		{foreach from=$req.list.list item=l}
		
		<li style="width:40px;">{$l.pid}</a></li>
		<li style="width:250px;">{$l.title}</li>
		<li style="width:250px;">{$l.seasonname}</li>
		<li style="width:120px;">{$l.typename}</li>
		<li style="width:190px;">        
        <input type="button" class="hbutton" value="{$lang.but.edit}" onclick="javascript:location.href='/admin/?act=pro&cp=editseason&pid={$l.pid}';" />
        </li>
		<label>
		</label>
		{/foreach}
		<li style="width:720px; height:30px; background:#ffffff;">{$req.list.links.all}</li>
		{else}
		<li style="width:720px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
		{/if}
		</ul>
	{if !$req.nofull }	</div>
	<input name="searchparam" type="hidden" id="searchparam" value='{$req.list.searchparam}' />
	<input name="pageno" type="hidden" id="pageno" value="{$req.list.pageno}"/>
	</form>
	</div>
	{/if}

{elseif $req.display eq 'suburb'}

<select name="suburb">
<option value="">Select Suburb  </option>
{foreach from=$req.suburb item=l}
<option value="{$l.suburb}">{$l.suburb} </option>
{/foreach}
</select>

{/if}

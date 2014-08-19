<link href="css/global.css" rel="stylesheet" type="text/css">
{if $req.display eq ''}
	{if !$req.nofull}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:750px;">
	<ul>
	<form id="searchForm" name="searchForm" method="post" action="" onSubmit="javascript:xajax_getPartnerSiteListSearch(xajax.getFormValues('searchForm')); return false;">
	</li>
     <li id="lable" style="white-space: nowrap;">{$lang.race.lb_sitename}</li>
	<li id="input2" style="width:200px;">
	<input type="text" value="" class="inputB" name="site_name" style="width:200px;"/>
	</li>
	
	<li id="lable">Suspended</li>
	<li id="input2" style="width:200px;">
	<select name="deleted">
		<option value="">Select Suspend</option>
		<option value="1">Yes</option>
		<option value="0">No</option>
	</select>
	</li>
	<li id="lable">{$lang.race.lb_domain}</li>
	<li id="input2" style="width:200px;">
	<input type="text" class="inputB" name="domain" style="width:200px;"/>
	</li>
	
	<li id="lable"></li>
	<li id="input2" style="width:250px; "><table cellspacing="0" cellpadding="0"><tr><td><input name="search" class="hbutton" type="submit" id="search" value="  {$lang.but.search}  " /></td><td>&nbsp;<input class="hbutton" type="button" value="Create New Partner Site" onclick="javascript:location.href='/admin/?act=race&cp=add_partner_site';" /></td></tr></table></li>
    
   
	</form>
	</ul>
	<div style="clear:both;"></div>
	</div>
	<form id="mainForm" name="mainForm" enctype="multipart/form-data" method="post" action="?act=adv&amp;cp=all" onsubmit="//xajax_saveBannerAllAndDefault(); return false;">
	<div id="tabledatalist" class="wrap">
	{/if}
		<ul id="table" style="width:890px;">
		<li class="tabletop" style="width:40px;"><a href="#" onclick="javascript:xajax_getPartnerSiteList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','id','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.race.lb_id}</a>{if $req.list.sort.field eq 'id'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:180px;"><a href="#" onclick="javascript:xajax_getPartnerSiteList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','site_name','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.race.lb_sitename}</a>{if $req.list.sort.field eq 'site_name'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:170px;"><a href="#" onclick="javascript:xajax_getPartnerSiteList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','domain','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.race.lb_domain}</a>{if $req.list.sort.field eq 'domain'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:100px;"><a href="#" onclick="javascript:xajax_getPartnerSiteList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','point','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.race.lb_point}</a>{if $req.list.sort.field eq 'point'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:100px;"><a href="#" onclick="javascript:xajax_getPartnerSiteList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','max_time','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.race.lb_maxtime}</a>{if $req.list.sort.field eq 'max_time'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:70px;">Suspend</li>
		<li class="tabletop" style="width:190px;">Record Operate</li>
		<div style="clear:both;"></div>
		{if $req.list.list}
		{foreach from=$req.list.list item=l}
		
		<li style="width:40px;">{$l.id}</a></li>
		<li style="width:180px;">{$l.site_name}</li>
		<li style="width:170px;">{$l.domain}</li>
		<li style="width:100px;">{$l.point}</li>
		<li style="width:100px;">{$l.max_time}</li>
		<li style="width:70px;">{if $l.deleted}yes{else}no{/if}</li>
		<li style="width:190px;">        
        <input type="button" class="hbutton" value="{$lang.but.edit}" onclick="javascript:location.href='/admin/?act=race&cp=add_partner_site&sid={$l.id}';">
        {if $l.deleted != '1'}
        <input type="button" class="hbutton" value="Manage Question" onclick="javascript:location.href='/admin/?act=race&cp=question&sid={$l.id}&back=1';">
        {/if}
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

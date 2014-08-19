<link href="css/global.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
{literal}
function popcontactwin(sid, qid) {
	window.open("/soc.php?cp=qpreview&sid=" + sid + "&qid=" + qid, "emailstore","width=1024,height=768,scrollbars=yes,status=yes");
}
{/literal}
</script>
{if $req.display eq ''}
	{if !$req.nofull}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:720px;">
	<ul>
	<form id="searchForm" name="searchForm" method="post" action="" onSubmit="javascript:xajax_getQuestionListSearch(xajax.getFormValues('searchForm')); return false;">
	</li>
     <li id="lable" style="white-space: nowrap;">{$lang.race.lb_sitename}</li>
	<li id="input2" style="width:200px;">
		<select name="site_id" style="max-width:200px">
        <option value="">Select Site Name</option> 
        {foreach from=$req.site_list item=l} 
            <option value="{$l.id}" {if ($req.info.site_id eq $l.id) or ($req.sid eq $l.id)}selected{/if}>{$l.site_name}</option>
        {/foreach}
        </select>
	</li>
	
	<li id="lable">Suspended</li>
	<li id="input2" style="width:200px;">
	<select name="deleted">
		<option value="">Select Suspend</option>
		<option value="1">Yes</option>
		<option value="0">No</option>
	</select>
	</li>
	<li id="lable">Question</li>
	<li id="input2" style="width:200px;">
	<input type="text" class="inputB" name="question" style="width:200px;"/>
	</li>
	
	<li id="lable"></li>
	<li id="input2" style="width:250px; "><table cellspacing="0" cellpadding="0"><tr><td><input name="search" class="hbutton" type="submit" id="search" value="  {$lang.but.search}  " /></td><td>&nbsp;<input class="hbutton" type="button" value="Create New Question" onclick="javascript:location.href='/admin/?act=race&cp=add_question{if $req.sid}&sid={$req.sid}{/if}{if $req.back}&back={$req.back}{/if}';" /></td></tr></table></li>
    
   
	</form>
	</ul>
	<div style="clear:both;"></div>
	</div>
	<form id="mainForm" name="mainForm" enctype="multipart/form-data" method="post" action="?act=adv&amp;cp=all" onsubmit="//xajax_saveBannerAllAndDefault(); return false;">
	<div id="tabledatalist" class="wrap">
	{/if}
		<ul id="table" style="width:930px;">
		<li class="tabletop" style="width:40px;"><a href="#" onclick="javascript:xajax_getQuestionList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','id','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.race.lb_id}</a>{if $req.list.sort.field eq 'id'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:180px;"><a href="#" onclick="javascript:xajax_getQuestionList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','question','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Question</a>{if $req.list.sort.field eq 'question'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:150px;"><a href="#" onclick="javascript:xajax_getQuestionList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','site_name','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.race.lb_sitename}</a>{if $req.list.sort.field eq 'site_name'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:130px;"><a href="#" onclick="javascript:xajax_getQuestionList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','domain','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.race.lb_domain}</a>{if $req.list.sort.field eq 'domain'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:70px;">Type</li>
		<li class="tabletop" style="width:70px;">Suspend</li>
		<li class="tabletop" style="width:250px;">Record Operate</li>
		<div style="clear:both;"></div>
		{if $req.list.list}
		{foreach from=$req.list.list item=l}
		
		<li style="width:40px;">{$l.id}</a></li>
		<li style="width:180px;">{$l.question}</li>
		<li style="width:150px;">{$l.site_name}</li>
		<li style="width:130px;">{$l.domain}</li>
		<li style="width:70px;">{$l.type}</li>
		<li style="width:70px;">{if $l.deleted}yes{else}no{/if}</li>
		<li style="width:250px;">        
        <input type="button" class="hbutton" value="{$lang.but.edit}" onclick="javascript:location.href='/admin/?act=race&cp=add_question&sid={$l.site_id}&qid={$l.id}&back=1';">
        {if $l.deleted != '1'}
        <input type="button" class="hbutton" value="Manage Answer" onclick="javascript:location.href='/admin/?act=race&cp=answer&sid={$l.site_id}&qid={$l.id}&back=1{if $req.back}&f=1{/if}';">
        <input type="button" class="hbutton" value="Preview" onclick="javascript:popcontactwin({$l.site_id},{$l.id});">
        {/if}
        </li>
		<label>
		</label>
		{/foreach}
		<li style="width:930px; height:30px; background:#ffffff; float:none">{$req.list.links.all}</li>
		{else}
		<li style="width:930px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
		{/if}
		</ul>
	{if !$req.nofull }	</div>
    {if $req.back || $req.f}
    <div style="width:930px; height:30px; background:#ffffff;"><input type="button" class="hbutton" value="{$lang.but.back}" onclick="javascript:location.href='/admin/?act=race&cp=partner_site&sid={$req.sid}&back=1';"></div>
    {/if}
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

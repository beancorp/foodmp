<link href="css/global.css" rel="stylesheet" type="text/css">
{literal}
<script type="text/javascript" language="javascript">	
	function changesite(obj){
		$.post('/admin/index.php',{act:'race',cp:'getsitelist',site:obj.value},function(data){$('#question_id').html(data);$('#question_id').val('');});
	}
</script>
{/literal}
{if $req.display eq ''}
	{if !$req.nofull}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:720px;">
	<ul>
	<form id="searchForm" name="searchForm" method="post" action="" onSubmit="javascript:xajax_getAnswerListSearch(xajax.getFormValues('searchForm')); return false;">
	</li>
     <li id="lable" style="white-space: nowrap;">{$lang.race.lb_sitename}</li>
	<li id="input2" style="width:200px;">
		<select name="site_id" id="site_id" onChange="changesite(this);" style="max-width:200px;">
        <option value="">Select Site Name</option>
        {foreach from=$req.site_list item=l}
            <option value="{$l.id}" {if ($req.info.site_id eq $l.id) or ($req.sid eq $l.id)}selected{/if}>{$l.site_name}</option>
        {/foreach}
        </select>
	</li>
    
     <li id="lable" style="white-space: nowrap;">Question</li>
	<li id="input2" style="width:200px;">
		<select name="question_id" id="question_id" style="width: 280px;">
        <option value="">Select Question</option>
        {foreach from=$req.question_list item=l}
            <option value="{$l.id}" {if ($req.info.question_id eq $l.id) or ($req.qid eq $l.id)}selected{/if}>{$l.question}</option>
        {/foreach}
        </select>
	</li>
	
	<li id="lable">Answer</li>
	<li id="input2" style="width:200px;">
	<input type="text" class="inputB" name="answer" style="width:187px;"/>
	</li>
	
	<li id="lable">Suspended</li>
	<li id="input2" style="width:200px;">
	<select name="deleted">
		<option value="">Select Suspend</option>
		<option value="1">Yes</option>
		<option value="0">No</option>
	</select>
	</li>
    
	<li id="lable"></li>
	<li id="input2" style="width:250px; "><table cellspacing="0" cellpadding="0"><tr><td><input name="search" class="hbutton" type="submit" id="search" value="  {$lang.but.search}  " /></td><td>&nbsp;<input class="hbutton" type="button" value="Create New Answer" onclick="javascript:location.href='/admin/?act=race&cp=add_answer{if $req.sid}&sid={$req.sid}{/if}{if $req.qid}&qid={$req.qid}{/if}{if $req.back}&back={$req.back}{/if}';" /></td></tr></table></li>
    
   
	</form>
	</ul>
	<div style="clear:both;"></div>
	</div>
	<form id="mainForm" name="mainForm" enctype="multipart/form-data" method="post" action="?act=adv&amp;cp=all" onsubmit="//xajax_saveBannerAllAndDefault(); return false;">
	<div id="tabledatalist" class="wrap">
	{/if}
		<ul id="table" style="width:830px;">
		<li class="tabletop" style="width:40px;"><a href="#" onclick="javascript:xajax_getAnswerList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','id','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.race.lb_id}</a>{if $req.list.sort.field eq 'id'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:130px;"><a href="#" onclick="javascript:xajax_getAnswerList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','answer','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Answer</a>{if $req.list.sort.field eq 'answer'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:180px;"><a href="#" onclick="javascript:xajax_getAnswerList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','question','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Question</a>{if $req.list.sort.field eq 'question'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:170px;"><a href="#" onclick="javascript:xajax_getAnswerList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','site_name','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.race.lb_sitename}</a>{if $req.list.sort.field eq 'site_name'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:70px;"><a href="#" onclick="javascript:xajax_getAnswerList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','status','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Correct</a>{if $req.list.sort.field eq 'status'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:70px;">Suspend</li>
		<li class="tabletop" style="width:130px;">Record Operate</li>
		<div style="clear:both;"></div>
		{if $req.list.list}
		{foreach from=$req.list.list item=l}
		
		<li style="width:40px;">{$l.id}</a></li>
		<li style="width:130px;">{$l.answer}</li>
		<li style="width:180px;">{$l.question}</li>
		<li style="width:170px;">{$l.site_name}</li>
		<li style="width:70px;">{if $l.status}yes{else}no{/if}</li>
		<li style="width:70px;">{if $l.deleted}yes{else}no{/if}</li>
		<li style="width:130px;">  
        <input type="button" class="hbutton" value="{$lang.but.edit}" onclick="javascript:location.href='/admin/?act=race&cp=add_answer{if $req.sid}&sid={$req.sid}{/if}{if $req.qid}&qid={$req.qid}{/if}&aid={$l.id}{if $req.back}&back={$req.back}{/if}';">
        </li>
		<label>
		</label>
		{/foreach}
		<li style="width:821px; height:30px; background:#ffffff;">{$req.list.links.all}</li>
		{else}
		<li style="width:821px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
		{/if}
		</ul>
	{if !$req.nofull }	</div>
        
    {if $req.back}
    <div class="clear"></div>
    <div style="width:821px; height:30px; background:#ffffff; text-align:center"><input type="button" class="hbutton" value="{$lang.but.back}" onclick="javascript:location.href='/admin/?act=race&cp=question&sid={$req.sid}&qid={$req.qid}{if $req.f}&back=1{/if}';"></div>
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

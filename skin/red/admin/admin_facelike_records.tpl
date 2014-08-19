<link href="css/global.css" rel="stylesheet" type="text/css">
{if $req.display eq ''}
	{if !$req.nofull}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:720px;">
    <ul>
	<form id="searchForm" name="searchForm" method="post" action="" onSubmit="javascript:xajax_getFacelikeRecordsSearch(xajax.getFormValues('searchForm')); return false;">
    <input type="hidden" name="StoreID" value="{$req.StoreID}" />
     <li id="lable" style="white-space: nowrap;">{$lang.payment.fromdate}</li>
	<li id="input2" style="width:200px;">
	<input name="fromDate" type="text" id="fromDate" size="15" value=""  readonly ><a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.searchForm.fromDate);return false;" HIDEFOCUS><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" alt=""></a>
	</li>	
	
	<li id="lable">{$lang.payment.todate}</li>
	<li id="input2" style="width:200px;">
	<input name="toDate" type="text" id="toDate" size="15" value=""  readonly ><a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.searchForm.toDate);return false;" HIDEFOCUS><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" alt=""></a>
	</li>
    
	<li id="lable">Type</li>
	<li id="input2" style="width:200px;">
	<select name="num" id="num" style="width:134px;">
        <option value="">All</option>
        <option value="1">Like</option>
        <option value="-1">Unlike</option>
    </select>
	</li>

	<li id="lable"></li>
	<li id="input2" style="width:200px; "><table cellspacing="0" cellpadding="0"><tr><td><input name="search" class="hbutton" type="submit" id="search" value="  {$lang.but.search}  " /></td></tr></table></li>
    
   
	</form>
	</ul>
	<div style="clear:both;"></div>
	</div>
	<form id="mainForm" name="mainForm" enctype="multipart/form-data" method="post" action="?act=adv&amp;cp=all" onsubmit="//xajax_saveBannerAllAndDefault(); return false;">
	<div id="tabledatalist" class="wrap">
	{/if}
		<ul id="table" style="width:830px;">
		<li class="tabletop" style="width:150px;"><a href="#" onclick="javascript:xajax_getFacelikeRecords('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','bu_name','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">User</a>{if $req.list.sort.field eq 'bu_name'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:150px;"><a href="#" onclick="javascript:xajax_getFacelikeRecords('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','bu_nickname','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.store.lb_nickname}</a>{if $req.list.sort.field eq 'bu_nickname'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<!--<li class="tabletop" style="width:200px;"><a href="#" onclick="javascript:xajax_getFacelikeRecords('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','bu_email','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Email</a>{if $req.list.sort.field eq 'bu_email'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>-->
		<li class="tabletop" style="width:340px;"><a href="#" onclick="javascript:xajax_getFacelikeRecords('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','url','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Url</a>{if $req.list.sort.field eq 'url'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:90px;"><a href="#" onclick="javascript:xajax_getFacelikeRecords('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','timestamp','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Date</a>{if $req.list.sort.field eq 'timestamp'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:50px;"><a href="#" onclick="javascript:xajax_getFacelikeRecords('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','num','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Type</a>{if $req.list.sort.field eq 'num'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<div style="clear:both;"></div>
		{if $req.list.list}
		{foreach from=$req.list.list item=l}
		<li style="width:150px;">{if $l.bu_name}{$l.bu_name}{else}Null{/if}</li>
		<li style="width:150px;">{if $l.bu_nickname}{$l.bu_nickname}{else}Null{/if}</li>
		<!--<li style="width:200px;">{$l.bu_email}</li>-->
		<li style="width:340px; overflow:hidden"><a href="{$l.url}" target="_blank">{$l.url}</a></li>
		<li style="width:90px;">{$l.timestamp|date_format:$req.list.PBDateFormat}</li>
		<li style="width:50px;">{if $l.num eq '1'}Like{else}Unlike{/if}</li>
		<label>
		</label>
		{/foreach}
        <div style="clear:both;"></div>
		<li style="width:100%; height:30px; background:#ffffff;">{$req.list.links.all}</li>
		{else}
		<li style="width:100%; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
		{/if}
        <div style="clear:both;"></div>
    	<li style="width:100%; height:30px; background:#ffffff;"> <input name="back" type="button" class="hbutton" id="back" value="{$lang.but.back}" onClick="javascript:location.href='?act=facelike'"></li>
		</ul>
	{if !$req.nofull }	</div>
	<input name="searchparam" type="hidden" id="searchparam" value='{$req.list.searchparam}' />
	<input name="pageno" type="hidden" id="pageno" value="{$req.list.pageno}"/>
	</form>
   
	</div>
    <div class="clear"></div>
	{/if}

{elseif $req.display eq 'suburb'}

<select name="suburb">
<option value="">Select Suburb  </option>
{foreach from=$req.suburb item=l}
<option value="{$l.suburb}">{$l.suburb} </option>
{/foreach}
</select>

	

{/if}

<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.php" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
<link href="css/global.css" rel="stylesheet" type="text/css">
{if $req.display eq ''}
	{if !$req.nofull}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:720px;">
	<ul>
	<form id="searchForm" name="searchForm" method="post" action="" onSubmit="javascript:xajax_getFacelikeListSearch(xajax.getFormValues('searchForm')); return false;">
	<li id="lable">{$lang.store.state}</li>
	<li id="input2" style="width:200px;">
	<select name="state" id="bu_state" style="width:200px;" onChange="javascript: xajax_getSuburbList(this.options[this.selectedIndex].value,'suburb_div');">
	<option value="">Select State  </option>
	{foreach from=$req.state item=l}
	<option value="{$l.id}">{$l.description}  ({$l.stateName}) </option>
	{/foreach}
	</select>
	</li>
	
	<li id="lable">{$lang.store.suburb}</li>
	<li id="input2" style="width:200px;"><div id="suburb_div">
	<select name="suburb" style="width:200px;">
	<option value="">Select Suburb  </option>
	{foreach from=$req.suburb item=l}
	<option value="{$l.bu_suburb}">{$l.bu_suburb}&nbsp;</option>
	{/foreach}
	</select></div>
	</li>
	
	<li id="lable">{$lang.labelAttribute}</li>
	<li id="input2" style="width:200px;">
	<select name="attribute" id="attribute">
	<option value="">Select Type  </option>
	{foreach from=$lang.seller.attribute item=l key=k}
	<option value="{$k}">{$l.text}</option>
	{/foreach}
	</select>
	</li>
    
	<li id="lable">Type</li>
	<li id="input2" style="width:200px;">
	<select name="num" id="num" style="width:134px;">
        <option value="">All</option>
        <option value="1">Like</option>
        <option value="-1">Unlike</option>
    </select>
	</li>
    
     <li id="lable" style="white-space: nowrap; *margin-left:-7px;">Username(email)</li>
	<li id="input2" style="width:200px;">
	<input type="text" value="" class="inputB" name="bu_email" style="width:200px;"/>
	</li>
	
	<li id="lable">Website Name</li>
	<li id="input2" style="width:200px;">
	<input type="text" class="inputB" name="bu_name" style="width:200px;"/>
	</li>
	
    <li id="lable" style="white-space: nowrap; ">{$lang.payment.fromdate}</li>
	<li id="input2" style="width:200px;" >
	<input name="fromDate" type="text" id="fromDate" size="15" value="" class="inputB"  readonly ><a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.searchForm.fromDate);return false;" HIDEFOCUS><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" alt=""></a>
	</li>	
	
	<li id="lable">{$lang.payment.todate}</li>
	<li id="input2" style="width:200px;">
	<input name="toDate" type="text" id="toDate" size="15" value="" class="inputB"  readonly ><a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.searchForm.toDate);return false;" HIDEFOCUS><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" alt=""></a>
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
		<li class="tabletop" style="width:140px;"><a href="#" onclick="javascript:xajax_getFacelikeList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','bu_name','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.store.lb_name}</a>{if $req.list.sort.field eq 'bu_name'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:140px;"><a href="#" onclick="javascript:xajax_getFacelikeList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','bu_nickname','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.store.lb_nickname}</a>{if $req.list.sort.field eq 'bu_nickname'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:210px;"><a href="#" onclick="javascript:xajax_getFacelikeList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','bu_email','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Email</a>{if $req.list.sort.field eq 'bu_email'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:70px;"><a href="#" onclick="javascript:xajax_getFacelikeList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','flike','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Like</a>{if $req.list.sort.field eq 'flike'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:70px;"><a href="#" onclick="javascript:xajax_getFacelikeList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','funlike','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Unlike</a>{if $req.list.sort.field eq 'funlike'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:70px;"><a href="#" onclick="javascript:xajax_getFacelikeList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','total','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Total</a>{if $req.list.sort.field eq 'total'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:70px;">{$lang.but.view}</li>
		<div style="clear:both;"></div>
		{if $req.list.list}
		{foreach from=$req.list.list item=l}
		<li style="width:140px;">{$l.bu_name}</li>
		<li style="width:140px;">{$l.bu_nickname}</li>
		<li style="width:210px;">{$l.bu_email}</li>
		<li style="width:70px;">{$l.flike}</li>
		<li style="width:70px;">{$l.funlike}</li>
		<li style="width:70px;">{$l.total}</li>
		<li style="width:70px;">        
        <input type="button" class="hbutton" value="{$lang.but.view}" onclick="javascript:location.href='/admin/?act=facelike&cp=records&StoreID={$l.StoreID}';">
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

<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
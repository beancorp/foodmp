<link href="css/global.css" rel="stylesheet" type="text/css">
{if $req.display eq ''}
	{if !$req.nofull}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:720px;">
	<ul>
{*
	<form id="searchForm" name="searchForm" method="post" action="" onSubmit="javascript:xajax_getDuplicateListSearch(xajax.getFormValues('searchForm')); return false;">
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
	
	<li id="lable">{$lang.store.refID}</li>
	<li id="input2" style="width:200px;">
	<input type="text" value="" class="inputB" name="refferID"/>
	</li>
     <li id="lable" style="white-space: nowrap; *margin-left:-7px;">Username(email)</li>
	<li id="input2" style="width:200px;">
	<input type="text" value="" class="inputB" name="bu_email" style="width:200px;"/>
	</li>
	
	<li id="lable">Suspended</li>
	<li id="input2" style="width:200px;">
	<select name="suspend">
		<option value="">Select Suspend</option>
		<option value="1">Yes</option>
		<option value="0">No</option>
	</select>
	</li>
	<li id="lable">Website Name</li>
	<li id="input2" style="width:200px;">
	<input type="text" class="inputB" name="bu_name" style="width:200px;"/>
	</li>
	
	<li id="lable"></li>
	<li id="input2" style="width:200px; "><table cellspacing="0" cellpadding="0"><tr><td><input name="search" class="hbutton" type="submit" id="search" value="  {$lang.but.search}  " /></td><td>&nbsp;<input class="hbutton" type="button" value="Create New User" onclick="javascript:location.href='/admin/?act=store&cp=adduser';" /></td></tr></table></li>
    
   
	</form>
*}
	</ul>
	<div style="clear:both;"></div>
	</div>
	<form id="mainForm" name="mainForm" enctype="multipart/form-data" method="post" action="?act=adv&amp;cp=all" onsubmit="//xajax_saveBannerAllAndDefault(); return false;">
	<div id="tabledatalist" class="wrap">
	{/if}
		<ul id="table" style="width:960px;">
		<li class="tabletop" style="width:40px;">#</li>
		<li class="tabletop" style="width:200px;"><a href="#" onclick="javascript:xajax_getDuplicateList('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','bu_name','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Store Name</a>{if $req.list.sort.field eq 'bu_name'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:350px;">Store Address</li>
		<li class="tabletop" style="width:220px;">Category</li>
		<li class="tabletop" style="width:110px;">Record Operate</li>
		<div style="clear:both;"></div>
		
            {if $req.list.list}
		{foreach from=$req.list.list item=l name=dpList}
                   {assign var='duplicateItems' value=$objAdminStore->getDuplicateSubList($l)}
                   {if $duplicateItems}
                    {assign var='count' value=$count+1}
                    <li class="main-item" style="width:40px;"><a href='storeLogin.php?StoreID={$l.StoreID}' target='_blank'>{$count}</a></li>
                    <li class="main-item" style="width:200px;">{$l.bu_name}</li>
                    <li class="main-item" style="width:350px;">{$l.bu_address}</li>
                    <li class="main-item" style="width:220px;">{$lang.seller.attribute.5.subattrib[$l.subAttrib]}</li>
                    <li class="main-item" style="width:110px;">
                        <input name="button1" type="button" class="hbutton" value="View" onclick="javascript:window.open('{$smarty.const.SOC_HTTP_HOST}{$l.bu_urlstring}')">
                    </li>
                        {foreach from=$duplicateItems item=sl name=dpSubList}
                         <li class="sub-item" style="width:40px;"></li>
                         <li class="sub-item" style="width:200px;">{$sl.bu_name}</li>
                         <li class="sub-item" style="width:350px;">{$sl.bu_address}</li>
                         <li class="sub-item" style="width:220px;">{$lang.seller.attribute.5.subattrib[$sl.subAttrib]}</li>
                         <li class="sub-item" style="width:110px;">
                        <input name="button3" type="button" class="hbutton" value="View" onclick="javascript:window.open('{$smarty.const.SOC_HTTP_HOST}{$sl.bu_urlstring}')">
                        <input name="button2" type="button" class="hbutton" value="{$lang.but.delete}" onclick="javascript:if(confirm('{$lang.pub_clew.delete}')) xajax_deleteDuplicateList('{$sl.StoreID}');">
                        </li>
                        {/foreach}
                        <div style="clear:both;"></div>
                    {/if}

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

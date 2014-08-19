<link href="css/global.css" rel="stylesheet" type="text/css">
{if $req.display eq ''}
	{if !$req.nofull}
    {literal}
    	<script language="javascript">
		function changeRenewform(id){
			var edval = document.getElementById('renew_'+id).innerHTML;
			document.getElementById('renew_'+id).innerHTML = "<input type='text'  style='width:80px;' id='renew_date_"+id+"' name='renew_date_"+id+"' maxlength='12' value='"+edval+"' readonly='true'/> <a href='javascript:void(0)' onclick='if(self.gfPop)gfPop.fPopCalendar(document.mainForm.renew_date_"+id+");return false;' hidefocus='HIDEFOCUS'><img align='absmiddle' src='/include/cal/calbtn.gif' width='34' height='22' border='0' /></a>";
			document.getElementById('renew_opt_'+id).innerHTML = "<input type='button' class='hbutton' value='Save' onclick='javascript:xajax_renewuser("+id+",document.getElementById(\"renew_date_"+id+"\").value,\""+edval+"\")'/> <input type='button' class='hbutton' value='Cancel' onclick='cancel_form("+id+",\""+edval+"\")'/>";
		}
		function cancel_form(id,inhtml){
			document.getElementById('renew_'+id).innerHTML = inhtml;
			document.getElementById('renew_opt_'+id).innerHTML = "<input type='button' class='hbutton' value='Renew' onclick='changeRenewform(\""+id+"\")'>";
		}
		</script>
    {/literal}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:720px;">
	<ul>
	<form id="searchForm" name="searchForm" method="post" action="" onSubmit="javascript:xajax_getStoreListSearch2(xajax.getFormValues('searchForm')); return false;">
	<li id="lable">{$lang.store.state}</li>
	<li id="input2" style="width:200px;">
	<select name="state" style="width:200px;" id="bu_state" onChange="javascript: xajax_getSuburbList(this.options[this.selectedIndex].value,'suburb_div');">
	<option value="">Select State  </option>
	{foreach from=$req.state item=l}
	<option value="{$l.id}">{$l.description}  ({$l.stateName}) </option>
	{/foreach}
	</select>
	</li>
	
	<li id="lable">{$lang.store.suburb}</li>
	<li id="input2" style="width:200px;"><div id="suburb_div">
	<select name="suburb" style="width:200px;">
	<option value="">Select City </option>
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
    {if $k ne '0'}
	<option value="{$k}">{$l.text}</option>
    {/if}
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
	<li id="input2" style="width:200px; "><table cellspacing="0" cellpadding="0"><tr><td><input name="search" class="hbutton" type="submit" id="search" value="  {$lang.but.search}  " /></td><td>&nbsp;</td></tr></table></li>
	</form>
	</ul>
	<div style="clear:both;"></div>
	</div>
	<form id="mainForm" name="mainForm" enctype="multipart/form-data" method="post" action="?act=adv&amp;cp=all" onsubmit="//xajax_saveBannerAllAndDefault(); return false;">
	<div id="tabledatalist" class="wrap">
	{/if}
		<ul id="table" style="width:900px;">
		<li class="tabletop" style="width:70px;">{$lang.store.lb_login}</li>
		<li class="tabletop" style="width:150px;"><a href="#" onclick="javascript:xajax_getStoreList2('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','bu_name','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.store.lb_name}</a>{if $req.list.sort.field eq 'bu_name'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:140px;"><a href="#" onclick="javascript:xajax_getStoreList2('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','bu_nickname','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.store.lb_nickname}</a>{if $req.list.sort.field eq 'bu_nickname'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:100px;"><a href="#" onclick="javascript:xajax_getStoreList2('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','launch_date','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.store.lb_date}</a>{if $req.list.sort.field eq 'launch_date'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
		<li class="tabletop" style="width:100px;"><a href="#" onclick="javascript:xajax_getStoreList2('{$req.list.sort.page}',xajax.getFormValues('mainForm'),'{$req.list.sort.notold}','ref_name','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.store.refID}</a>{if $req.list.sort.field eq 'ref_name'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
        <li class="tabletop" style="width:150px;">Expire Date</li>
		<li class="tabletop" style="width:150px;">Operation</li>
		<div style="clear:both;"></div>
		{if $req.list.list}
		{foreach from=$req.list.list item=l}
		
		<li style="width:70px; height:23px;"><a href='storeLogin.php?StoreID={$l.StoreID}' target='_blank'><img src="../images/log-in.gif" border=0></a></li>
		<li style="width:150px; height:23px;">{$l.bu_name}</li>
		<li style="width:140px; height:23px;">{$l.bu_nickname}</li>
		<li style="width:100px; height:23px;">{if $l.launch_date}{$l.DateAdd}{/if}</li>
		<li style="width:100px; height:23px;">{$l.ref_name}</li>
        <li style="width:150px; height:23px;" id="renew_{$l.StoreID}">{if $l.attribute eq 5}{$l.renewalDate|date_format:"$PBDateFormat"}{else}{$l.product_renewal_date|date_format:"$PBDateFormat"}{/if}</li>
		<li style="width:150px; height:23px;" id="renew_opt_{$l.StoreID}">        
        <input type="button" class="hbutton" value="Renew" onclick="changeRenewform('{$l.StoreID}')">
        </li>
		<label>
		</label>
		{/foreach}
		<li style="width:890px; height:30px; background:#ffffff;">{$req.list.links.all}</li>
		{else}
		<li style="width:900px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
		{/if}
		</ul>
	{if !$req.nofull }	</div>
	<input name="searchparam" type="hidden" id="searchparam" value='{$req.list.searchparam}' />
	<input name="pageno" type="hidden" id="pageno" value="{$req.list.pageno}"/>
	</form>
	</div>
	{/if}
{if !$req.nofull }
  <iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
{/if}
{elseif $req.display eq 'suburb'}

<select name="suburb">
<option value="">Select City  </option>
{foreach from=$req.suburb item=l}
<option value="{$l.suburb}">{$l.suburb} </option>
{/foreach}
</select>

{/if}
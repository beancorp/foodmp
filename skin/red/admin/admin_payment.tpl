<link href="css/global.css" rel="stylesheet" type="text/css">
{if $req.display eq ''}
	{if !$req.nofull}
	<div id="ajaxmessage" class="publc_clew">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:720px;">
	  <form name="mainSearch" id="mainSearch" method="post" action="" onSubmit="javascript:xajax_paymentDetailsDateWiseReportsSearch(xajax.getFormValues('mainSearch'));return false;">
	<ul>
	<li id="lable">{$lang.payment.fromdate}</li>
	<li id="input2"><input name="fromDate" type="text" id="fromDate" size="15" value=""  readonly >
					<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.mainSearch.fromDate);return false;" HIDEFOCUS><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" alt=""></a></li>
	<li id="lable">{$lang.payment.todate}</li>
	<li id="input2"><input name="toDate" type="text" id="toDate" size="15" value=""  readonly >
					<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.mainSearch.toDate);return false;" HIDEFOCUS><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" alt=""></a></li>
	<br><br>
	<li id="lable"></li>
	<li id="input">
		<input type="hidden" value=""  name="listType" id="listType"/>
		<input type="submit" class="hbutton" name="submitButton" value=" {$lang.but.search} " onclick="xajax.$('listType').value='';">
		<input type="submit" class="hbutton" name="submitButton" value=" {$lang.payment.lastmonth} " onclick="xajax.$('listType').value='1';">
	</li>
	</ul>
	</form>
	</div>
	<form id="mainForm" name="mainForm">
	<div id="tabledatalist" >
	{/if}
		<ul id="table" style="width:720px;">
		<li style="height:30px; background:#ffffff; width:720px;">{$req.list.message}</li>
		<li class="tabletop" style="width:90px;">{$lang.payment.orderid}</li>
		<li class="tabletop" style="width:250px;">{$lang.payment.storename}</li>
		<li class="tabletop" style="width:100px;">{$lang.payment.amount}</li>
		<li class="tabletop" style="width:250px;">{$lang.payment.orderdate}</li>
		{if $req.list.list}
		{foreach from=$req.list.list item=l}
		<li style="width:90px;">{$l.OrderID}</li>
		<li style="width:250px;">{$l.bu_name}</li>
		<li style="width:100px;">{$l.amount}</li>
		<li style="width:250px;">{$l.orderDate}</li>
		{/foreach}
		<li style="width:720px; height:30px; background:#ffffff;">{$req.list.links.all}</li>
		{else}
		<li style="width:720px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
		{/if}
		</ul>
	{if !$req.nofull }
	</div>
	<input name="searchparam" type="hidden" id="searchparam" value="{$req.list.searchparam}" />
	<input name="pageno" type="hidden" id="pageno" value="{$req.list.pageno}"/>
	</form>
	</div>
	<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	{/if}
	
{* Store wise Reports*}
{elseif $req.display eq 'storerep'}

	{if !$req.nofull}
	<div id="ajaxmessage" class="publc_clew">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:720px;">
	  <form name="mainSearch" id="mainSearch" method="post" action="" onSubmit="javascript:xajax_paymentDetailsDateWiseReportsSearchStore(xajax.getFormValues('mainSearch'));return false;">
	<ul>
	<li id="lable">{$lang.payment.storename}</li>
	<li id="input2" style="width:560px;">
	  <select name="store">
	{if $req.list.store}
		{foreach from=$req.list.store item=l}
	    <option value="{$l.StoreID}">{$l.bu_name}</option>
		{/foreach}
	{/if}
	  </select>
	</li>
	<li id="lable">{$lang.payment.fromdate}</li>
	<li id="input2"><input name="fromDate" type="text" id="fromDate" size="15" value=""  readonly >
					<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.mainSearch.fromDate);return false;" HIDEFOCUS><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" alt=""></a></li>
	<li id="lable">{$lang.payment.todate}</li>
	<li id="input2"><input name="toDate" type="text" id="toDate" size="15" value=""  readonly >
					<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.mainSearch.toDate);return false;" HIDEFOCUS><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" alt=""></a></li>
	<br><br>
	<li id="lable"></li>
	<li id="input">
		<input type="hidden" value=""  name="listType" id="listType"/>
		<input type="submit" class="hbutton" name="submitButton" value=" {$lang.but.search} " onclick="xajax.$('listType').value='';">
	</li>
	</ul>
	</form>
	</div>
	<form id="mainForm" name="mainForm">
	<div id="tabledatalist" >
	{/if}
		<ul id="table" style="width:720px;">
		<li style="height:30px; background:#ffffff; width:720px;">{$req.list.message}</li>
		<li class="tabletop" style="width:90px;">{$lang.payment.orderid}</li>
		<li class="tabletop" style="width:250px;">{$lang.payment.storename}</li>
		<li class="tabletop" style="width:100px;">{$lang.payment.amount}</li>
		<li class="tabletop" style="width:250px;">{$lang.payment.orderdate}</li>
		{if $req.list.list}
		{foreach from=$req.list.list item=l}
		<li style="width:90px;">{$l.OrderID}</li>
		<li style="width:250px;">{$l.bu_name}</li>
		<li style="width:100px;">{$l.amount}</li>
		<li style="width:250px;">{$l.OrderDate}</li>
		{/foreach}
		<li style="width:720px; height:30px; background:#ffffff;">{$req.list.links.all}</li>
		{else}
		<li style="width:720px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
		{/if}
		</ul>
	{if !$req.nofull }
	</div>
	<input name="searchparam" type="hidden" id="searchparam" value="{$req.list.searchparam}" />
	<input name="pageno" type="hidden" id="pageno" value="{$req.list.pageno}"/>
	</form>
	</div>
	<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	{/if}

{* Store/category wise Reports *}
{elseif $req.display eq 'catrep'}

{* Ad. wise Reports *}
{elseif $req.display eq 'adrep'}

{* Gift wise Reports *}
{elseif $req.display eq 'giftrep'}

{* Referrals Reports *}
{elseif $req.display eq 'refrep'}

{/if}
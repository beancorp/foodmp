{if !$req.nofull}
<div align="center" style="border-bottom-color:#999999;">
<form id="mainForm" name="mainForm" method="post" action="/admin/?act=email&cp=reportsubscribe" onSubmit="">
<div style="padding:10px 0;"><input type="submit" name='act_report' value="Export" class="hbutton" /></div>
<div id="tabledatalist" >
{/if}
{if $req.controlPage eq 'showemail'}
	<ul id="table" style="width:720px;">
	<li class="tabletop" style="width:200px;">{$lang.email.lb_storename}</li>
	<li class="tabletop" style="width:500px;">{$lang.email.lb_mailids}</li>
	{if $req.list.list}
	{foreach from=$req.list.list item=l}
	<li style="width:200px;">{$l.bu_name}</li>
	<li style="width:500px;"><a href="#">{$l.email}</a></li>
	{/foreach}
	{else}
	<li style="width:700px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
	{/if}
	<li style="width:700px; height:30px; background:#ffffff; text-align:left"><input name="back" type="button" class="hbutton" id="back" value="{$lang.but.back}" onClick="javascript:xajax_StoreWiseEmailReportList(xajax.$('pageno').value,'tabledatalist');"></li>
	</ul>
	
{else}

	<ul id="table" style="width:720px;">
	<li class="tabletop" style="width:500px;">{$lang.email.lb_emailaddress}</li>
	<li class="tabletop" style="width:200px;">{$lang.email.lb_subscribedate}</li>
	{if $req.list.list}
	{foreach from=$req.list.list item=l}
	<li style="width:500px;">{$l.email}</li>
	<li style="width:200px;">{$l.subscribe_date|date_format:"$PBDateFormat"}</li>
	
	{/foreach}
	<li style="width:500px; height:30px; background:#ffffff;">{$req.list.links.all}</li>
	<li style="width:200px; height:30px; background:#ffffff;"></li>
	{else}
	<li style="width:700px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
	{/if}
	</ul>
{/if}
{if !$req.nofull}
</div>
<input name="pageno" type="hidden" id="pageno" value="{$req.list.pageno}"/>
</form>
</div>
{/if}
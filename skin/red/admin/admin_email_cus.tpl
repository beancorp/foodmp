<link href="css/global.css" rel="stylesheet" type="text/css">
{if !$req.nofull}
<div align="center" style="border-bottom-color:#999999;">
<form id="mainForm" name="mainForm" method="post" action="" onSubmit="">
<div id="tabledatalist" >
{/if}
{if $req.controlPage eq 'showemail'}
	<ul id="table" style="width:720px;">
	<li class="tabletop" style="width:200px;">{$lang.email.lb_customername}</li>
	<li class="tabletop" style="width:500px;">{$lang.email.lb_mailids}</li>
	{if $req.list.list}
	{foreach from=$req.list.list item=l}
	<li style="width:200px;">{$l.bu_name}</li>
	<li style="width:500px;"><a href="#">{$l.email}</a></li>
	{/foreach}
	{else}
	<li style="width:700px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
	{/if}
	<li style="width:700px; height:30px; background:#ffffff; text-align:left"><input name="back" type="button" class="hbutton" id="back" value="{$lang.but.back}" onClick="javascript:xajax_CustomerWiseEmailReportList(xajax.$('pageno').value);"></li>
	</ul>
	
{else}

	<ul id="table" style="width:720px;">
	<li class="tabletop" style="width:600px;">{$lang.email.lb_customername}</li>
	<li class="tabletop" style="width:100px;">{$lang.email.lb_mailids}</li>
	{if $req.list.list}
	{foreach from=$req.list.list item=l}
	<li style="width:600px;">{$l.bu_name}</li>
	<li style="width:100px;"><a href="#" onclick="javascript:xajax_CustomerWiseEmailReportListShow('{$l.storeid}');">{$l.records}</a></li>
	
	{/foreach}
	<li style="width:600px; height:30px; background:#ffffff;">{$req.list.links.all}</li>
	<li style="width:100px; height:30px; background:#ffffff;"></li>
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
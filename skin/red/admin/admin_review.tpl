<link href="css/global.css" rel="stylesheet" type="text/css">
{if $req.display eq 'expset'}
	<div align="center" style="border-bottom-color:#999999;">
	<div id="ajaxmessage" class="publc_clew">{$req.input.title}</div>
	<form id="mainForm" name="mainForm" method="post" action="" onSubmit="javascript:xajax_saveReviewsExpriy(xajax.getFormValues('mainform')) ; return false;">
	<div id="input-table" style="width:720px;">

	<ul>
	<li id="lable" style="width:250px;">{$lang.review.lb_real}</li>
	<li id="input2" style="width:450px;"><input name="setreal" id="setreal" type="text" size="4" maxlength="4" style="width:180px;" value="{$req.list.real}"></li>
	<li id="lable" style="width:250px;">{$lang.review.lb_free}</li>
	<li id="input2" style="width:450px;"><input name="setfree" id="setfree" type="text" size="4" maxlength="4" style="width:180px;" value="{$req.list.free}"></li><br>
	<li id="lable" style="width:250px;"></li>
	<li id="input" style="width:450px;">
		<input type="submit" class="hbutton" name="submitButton" value=" {$lang.but.save} ">
	</li>
	</ul>
</div>
<input name="pageno" type="hidden" id="pageno" value="{$req.list.pageno}"/>
</form>
</div>

{elseif $req.display eq 'details'}

	{if !$req.nofull}
	<div id="ajaxmessage" class="publc_clew">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:720px;">
	  <form name="mainSearch" id="mainSearch" method="post" action="" onSubmit="javascript:xajax_reviewsSearch(xajax.getFormValues('mainSearch'));return false;">
	<ul>
	<li id="lable">{$lang.main.lb_cus_name}</li>
	<li id="input2" style="width:580px;"><input name="searchstore" type="text" id="searchstore" value="" size="25" maxlength="50">
	<input type="submit" class="hbutton" name="submitButton" value=" {$lang.but.search} ">
	<input type="submit" name="Submit" value=" {$lang.but.clean} " class=hbutton onClick="javascript:this.form.searchstore.value='';"></li>
	</ul>
	<span id="space"></span>
	</form>
	</div>
	<form id="mainForm" name="mainForm" method="post" action="" onSubmit="javascript: xajax_reviewsUpdateOption(xajax.getFormValues('mainForm')); return false;" >
	<div id="tabledatalist">
	{/if}
		<ul id="review_table" style="width:720px;">

		{if $req.list.list}
		
		{foreach from=$req.list.list item=l}
		<li id="lable" style="width:280px; text-align:right">{$lang.email.lb_storename}: </li>
		<li id="input" style="width:420px; text-align:left;"><a href="#" onclick="javascript: xajax.$('searchstore').value='{$l.storename}'; xajax_reviewsSearch(xajax.getFormValues('mainSearch'));"> {$l.bu_name}</a></li>
		
		<li style="width:250px; text-align:left"><input name="delete" class="hbutton" type="button" value="{$lang.but.delete}" onclick="javascript: if(confirm('{$lang.pub_clew.delete}')) xajax_reviewsDelete('{$l.review_id}')"/>
		<input name="Update" class="hbutton" type="button" value="{$lang.but.update}" onclick="javascript: xajax_reviewsUpdate('{$l.review_id}')"/> {$l.rating}
		</li>
		<li style="width:450px;">{$l.fdate} by {$l.bu_nickname} from {$l.bu_suburb}, {$l.description|truncate:200}</li>
		
		<li style="width:700px;">{$l.content|truncate:200}</li>
		<li style="width:700px;">{$l.owner_del}</li>
		
		{if $l.sublist}
			<ul style="width:650px; margin-left:30px;">
			<li style="width:650px; text-align:left;">Comments:</li>
			  {foreach from=$l.sublist item=ls}
			  	{if $ls.level eq 1 }
			  		<li style="width:650px; text-align:left; color:red;"><input name="delete" class="hbutton" type="button" value="{$lang.but.delete}" onclick="javascript:if(confirm('{$lang.pub_clew.delete}')) xajax_reviewsDelete('{$ls.review_id}')"/>
		<input name="Update" class="hbutton" type="button" value="{$lang.but.update}" onclick="javascript: xajax_reviewsUpdate('{$ls.review_id}');"/>  {$ls.fdate} by {if $ls.bu_nickname}{$ls.bu_nickname}{else}{$ls.bu_name}{/if} : {$ls.bu_nickname} from {$ls.bu_suburb}, {$ls.description|truncate:200}</li>
			  {else}
			  <li style="width:650px; text-align:left;"><input name="delete" class="hbutton" type="button" value="{$lang.but.delete}" onclick="javascript:if(confirm('{$lang.pub_clew.delete}')) xajax_reviewsDelete('{$ls.review_id}')"/>
		<input name="Update" class="hbutton" type="button" value="{$lang.but.update}" onclick="javascript: xajax_reviewsUpdate('{$ls.review_id}');"/>  {$ls.fdate} by {if $ls.bu_nickname}{$ls.bu_nickname}{else}{$ls.bu_name}{/if} : {$ls.bu_nickname} from {$ls.bu_suburb}, {$ls.description|truncate:200}</li>
			{/if}
		{/foreach}
			</ul>
		{/if}
		<span id="space"></span>
		{/foreach}
		<li style="width:600px; height:30px; background:#ffffff; text-align:center;">{$req.list.links.all}</li>
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
	{/if}

{elseif $req.display eq 'update'}
	<ul id="review_table" style="width:720px;">
		<li id="input2" style="width:700px;">Message: </li>
		<li id="input2" style="width:700px;"><textarea name=message cols=60 rows=15>{$req.list.content}</textarea></li>
<br>
		<li id="lable" style="width:250px;"></li>
		<li id="input" style="width:450px;"><input name="review_id" type="hidden" value="{$req.list.review_id}" />
			<input type="submit" class="hbutton" name="submitButton" value=" {$lang.but.save} ">
			<input name="reset" type="reset" class="hbutton" value="{$lang.but.reset}">
	<input name="back" type="button" class="hbutton" value="{$lang.but.back}" onClick="javascript:xajax_getReviewsList(xajax.$('pageno').value, xajax.$('searchparam').value);">
		</li>
		</ul>
{/if}
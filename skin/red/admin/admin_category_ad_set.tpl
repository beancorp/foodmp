<link href="css/global.css" rel="stylesheet" type="text/css" />
<div id="content" align="center">
	<div id="categoryList" align="left">
	<ul>
		{if $req.list }
		{foreach from=$req.list item=l}
		<li onclick="xajax_displayCategoryItem('{$l.id}',xajax.getFormValues('mainForm'),'mainForm');" style="cursor:hand;" title="{$l.name}"><a href="#">{$l.name}</a></li>
		{/foreach}
		{/if}
	</ul>
	</div>
	
	<div id="categoryRssSet">
	
	<form id="mainForm" name="mainForm" action="" onsubmit="javascript:autoChangeEdit(this,'ad_left','ad_right','ad_bottom');xajax_saveCategoryAds(xajax.getFormValues('mainForm'));return false;" method="post">
	<ul>
	<div id="ajaxmessage" ></div>
	
	<li id="lable" style="width:15%;">{$lang.cgad.lb_ad_left}</li>
	<li id="input" style="width:83%;">{$req.ad_left}</li>
	
	<li id="lable" style="width:15%;">{$lang.cgad.lb_ad_right}</li>
	<li id="input" style="width:83%;">{$req.ad_right}</li>
	
	<li id="lable" style="width:15%;">{$lang.cgad.lb_ad_bottom}</li>
	<li id="input" style="width:83%;">{$req.ad_bottom}</li>
	
	<li id="lable" style="width:15%;">{$lang.cgad.lb_state}</li>
	<li id="input" style="width:83%;">{foreach from=$lang.cgad.state item=l}<input type="radio" name="state" class="input-none-border" id="state" value="{$l.value}" {if $l.default || $req.state eq {$l.value}} checked="checked" {/if}/>{$l.name}
	  {/foreach}
	</li><br />
	<li id="input" style="width:90%; height:30px; text-align:center;"><input type="hidden" id="cgid" name="cgid" value="{$req.cgid}"/><input type="submit" class="hbutton" name="submitButton" id="submitButton" disabled="disabled" value="Save And Update"/></li>
	</ul>
	</form>
	</div>
</div>
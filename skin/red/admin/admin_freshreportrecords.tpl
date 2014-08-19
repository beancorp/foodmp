<link href="css/global.css" rel="stylesheet" type="text/css">

<div id="list_sub">
{if $req.cmslist }
<ul>
{foreach from=$req.cmslist item=l }
<li style="cursor:hand; padding:10px 0;" title="{$l.title}"><a style="float:left; padding-right:10px;" onclick="xajax_displayFreshProduceReportCMSItem('{$l.id}');" href="#">{$l.updated|date_format:"$PBDateFormat"}</a><a onclick="if(window.confirm('Are you sure you want to delete?')) xajax_delFreshProduceReportCMSItem('{$l.id}');" style="float:left;" href="#" title="delete" style="line-height:15px; height:15px; padding:0 10px;"><img src="/skin/red/images/icon-deletes.gif" /></a></li>
{/foreach}
</ul>
{/if}
</div>

<div id="ajaxmessage" class="publc_clew">{$req.input.title}</div>

<div id="list_input">
<form id="mainForm" name="mainForm" method="post" action="" onSubmit="javascript:autoChangeEdit(this,'content'); xajax_saveCMSItem(xajax.getFormValues('mainForm')); return false;">
<ul id="input-table">
<li><span class="publc_clew" style="font-size:12px; font-weight:normal;">{$lang.EditerClew}</span><br />
</li>
<li>{$req.input.content}</li>
</ul>
<div align="center"><input name="title" type="hidden" id="title" value="{$req.input.title}">
  <!--<input name="id" type="hidden" id="id" value="{$req.input.id}">
  <input style="float:left; margin-left:300px;" type="submit" class="hbutton" id="submitButton" name="submitButton" value=" {$lang.but.save} ">-->
  <div id="reminder" style="float: left; padding-left: 10px;"></div>
</div>

</form>
</div>

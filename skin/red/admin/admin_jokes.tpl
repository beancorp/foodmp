<link href="css/global.css" rel="stylesheet" type="text/css">
<script language="javascript">
{literal}
function deletejokes(){
	if(confirm('Are you sure you want to delete?')){
		var id = document.getElementById('id').value;
		xajax_deleteJokesItem(id);
	}
}
{/literal}
</script>
<div id="list_sub">
{if $req.cmslist }
<ul>
{foreach from=$req.cmslist item=l }
<li onclick="xajax_displayJokesItem('{$l.id}');" style="cursor:hand;" title="{$l.title}"><a href="#">{$l.title}</a></li>
{/foreach}
</ul>
{/if}
</div>

<div id="ajaxmessage" class="publc_clew">{$req.input.title}</div>

<div id="list_input">
<form id="mainForm" name="mainForm" method="post" action="" onSubmit="javascript:xajax_saveJokesItem(xajax.getFormValues('mainForm')); return false;">
<ul id="input-table">

<li><table cellspacing="5">
	<tr><td>Joke title:</td><td><input name="title" style="width:400px;" type="text" id="title" value=""></td></tr>
	<tr><td>Joke content:</td><td><textarea name="answer" id="answer" style="width:400px; height:400px;"></textarea></td></tr>
	<tr><td colspan="2"><table width="100%" cellpadding="0" cellspacing="0"><tr><td width="145px"><span id="linknew" style="display:none;"><a href="/admin/?act=main&cp=jokes">Add a new jokes</a></span>&nbsp;</td><td align="right"><input type="button" class="hbutton" onclick="deletejokes();" id="delbut" style="display:none" value="Delete"/>&nbsp;<input type="submit" class="hbutton" id="submitButton" name="submitButton" value=" {$lang.but.save} "></td></tr></table></td></tr>
	</table>
</li>
</ul>
<input name="id" type="hidden" id="id" value=""/>
</form>
</div>

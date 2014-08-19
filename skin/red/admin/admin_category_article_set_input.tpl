<div id="ajaxmessage" align="center"></div><form id="mainForm" name="mainForm" action="" onsubmit="javascript:autoChangeEdit(this,'content'); xajax_saveCategoryArticle(xajax.getFormValues('mainForm'));return false;"  method="post">
<table width="100%" border="0" cellpadding="0" cellspacing="1">
  <tr>
    <td width="19%" height="25" align="right" class="txt"><strong>{$lang.cgart.lb_title}:&nbsp; </strong></td>
    <td width="81%"><input name="title" type="text" id="title" class="clsTextField" value="{$req.article.title}" size="80" maxlength="200"></td>
  </tr>
  <tr>
    <td height="25" align="right" valign="top" class="txt"><strong>{$lang.cgart.lb_context}:&nbsp;</strong></td>
    <td><textarea name="context" class="clsTextField" cols="80" rows="4" wrap="virtual" id="context">{$req.article.context}</textarea></td>
  </tr>
  <tr>
    <td height="25" align="right" valign="top" class="txt"><strong>{$lang.cgart.lb_content}:&nbsp;</strong></td>
    <td>{$req.article.content}</td>
  </tr>
  <tr>
    <td height="25" align="right" class="txt"><strong>{$lang.cgart.lb_state}:&nbsp; </strong></td>
    <td>{foreach from=$lang.cgart.state item=l}<input type="radio" name="state" id="state" value="{$l.value}" {if $l.default || $req.article.state == $l.value}} checked="checked" {/if}/>{$l.name}
	  {/foreach}</td>
  </tr>
  <tr>
    <td height="37" align="right"><input name="cgid" type="hidden" id="cgid" value="{$req.article.cgid}">
      <input name="id" type="hidden" id="id" value="{$req.article.id}"></td>
    <td>
      <input name="submitButton" type="submit" class="hbutton" id="submitButton" value="Save"> 
      <input type="button" class="hbutton" name="Submit2" value="Back" onClick="javascript:location.href='?act=pro&amp;cp=catartset&amp;cgid={$req.article.cgid}&amp;p={$req.article.page}';">
      <input type="button" class="hbutton" name="Submit3" value="Add New Article" onClick="javascript:location.href='?act=pro&amp;cp=catartset&amp;op=edit&amp;cgid={$req.article.cgid}&amp;p={$req.article.page}';"></td>
  </tr> 
</table>
</form>
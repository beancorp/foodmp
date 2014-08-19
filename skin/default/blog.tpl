<link href="css/style.css" rel="stylesheet" type="text/css">
<table width="96%" align="center" border="0" cellpadding="0" cellspacing="2">
  <tr>
    <td align="right">{$req.new}</td>
  </tr>
  {foreach from=$req.blogitem item=bloglist key=key}
  <tr>
    <td width="100%" valign="top" {if $key is div by 2} bgcolor="#e5d3a0"{/if}><table width="100%" border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td width="*" height="26" colspan="2" valign="bottom"><a style="color:#000000" href="soc.php?cp=blogpage&StoreID={$bloglist.StoreID}&bid={$bloglist.blog_id}&pageid=1"><font class="blogTitle"><strong>{$bloglist.subject}</strong></font></a><span style="text-align:justify"> &nbsp; {if $bloglist.approval>0}({$bloglist.approval} new comments for your approval){/if}</span><br /></td>
	  </tr>
      <tr>
        <td width="*" style="text-align:justify">{$bloglist.content}</td>
        <td width="100" align="center" rowspan="2">{if $bloglist.image1 != ''}<img align="baseline" valign="top" width="80" src="{$bloglist.image1}" />{/if}</td>
      </tr>
	  <tr>
        <td height="25"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="20%">{$bloglist.modify_date}</td>
            <td width="30%">{$bloglist.more}&nbsp;&nbsp;</td>
            <td width="25%">&nbsp;</td>
            <td width="25%">{if $bloglist.comment==0}{$bloglist.comment} Comment{else}{$bloglist.commentLink}{/if}</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  {/foreach}
  <tr>
	<td width="*" height="20" colspan="2" valign="bottom">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">{$req.navi}</td>
  </tr>
</table>

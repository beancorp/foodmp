<table width="595" border="0" cellpadding="0" cellspacing="2">
  <tr>
    <td width="50%" height="276" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      {foreach from=$req item=commentlist}
      <tr>
        <td width="33%" height="26" valign="bottom"><a href="soc.php?cp=blogpage&id={$commentlist.blog_id}"><strong>{$commentlist.subject}</strong></a>&nbsp;[Publish Date:{$commentlist.publish_date}&nbsp;&nbsp;&nbsp;Modify Date:{$commentlist.modify_date}]&nbsp;</td>
	  </tr>
	  <tr>
        <td><hr size="1" color="#CCCCCC" /></td>
      </tr>
      <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;{$commentlist.content}&nbsp;</td>
      </tr>
      {/foreach}
    </table>{$edit}<br />{$navi}</td>
  </tr>
</table>
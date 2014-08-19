<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<h2 style="width:482px;background:#{$req.template.bgcolor}">Properties <a href="javascript:history.go(-1);void(0);">Back</a></h2>

<ul id="item-list8">
{foreach from=$req.items.product item=l} 
    <li style="height:141px;">
	<div style="height:31px;" class="clear">
	<table width="100%" height="31" border="0" cellpadding="0" cellspacing="0" class="clear">
  <tr>
    <td width="386" height="31" bgcolor="#eeeeee">&nbsp;&nbsp;{$l.suburbName|upper}</td>
    <td width="3" bgcolor="#FFFFFF"><img id="space" src="/images/spacer.gif" width="3px" height="1px" class="clear"/></td>
<td width="31" align="center" valign="bottom" bgcolor="#eeeeee" style="height:31px;"><img id="space" src="/skin/red/estate/images/list-type-{$l.property}.jpg" width="31" height="31" /></td>
	<td width="3" bgcolor="#FFFFFF"><img id="space" src="/images/spacer.gif" width="3px" height="1px" class="clear"/></td>
    <td width="31" align="center" valign="bottom" bgcolor="#eeeeee" style="background:url(/skin/red/estate/images/list-bedroom.jpg)"><samp id="listNum">{$l.bedroom|default:'-':true:6}</samp></td>
    <td width="3" bgcolor="#FFFFFF"><img id="space" src="/images/spacer.gif" width="3px" height="1px" style="clear:both"/></td>
    <td width="31" align="center" valign="bottom" bgcolor="#eeeeee" style="background:url(/skin/red/estate/images/list-bathroom.jpg)"><samp id="listNum">{$l.bathroom|default:'-':true:6}</samp></td>
    <td width="3" bgcolor="#FFFFFF"><img id="space" src="/images/spacer.gif" width="3px" height="1px" style="clear:both"/></td>
    <td width="31" align="center" valign="bottom" bgcolor="#eeeeee" style="background:url(/skin/red/estate/images/list-carspace.jpg)"><samp id="listNum">{$l.carspaces|default:'-':true:6}</samp></td>
  </tr>
</table>
	</div>
	
	
	
	<div style=" float:left; width:79px; padding-top:5px; padding-bottom:10px; padding-right:10px;">
	  <a href="javascript:popSliding('{$l.StoreID}','{$l.pid}','{$l.simage.text}')"><img border="0" src="{$l.simage.text}" width="{$l.simage.width}" height="{$l.simage.height}" /></a>
	</div>
      <div id="context" style="width:265px;">
	  <a href="{if $l.is_auction=='yes'}/soc.php?cp=disauction&amp;StoreID={$l.StoreID}&amp;proid={$l.pid}{else}/{$req.info.url_bu_name}/{$l.url_item_name}{/if}" style="font-weight:bold;margin-top:5px;padding:0px;">{$l.item_name|truncate:60:"..."}</a>
	  {$l.suburbName}, {$l.stateName}{if $l.location neq ''}, { $l.location|truncate:20:'...'}{/if} <br />
	  {truncate content="`$l.content`" length="70"}
	  <a href="/{$req.info.url_bu_name}" class="arrowgrey">{$req.info.bu_name|truncate:45:'...'}</a>
	  </div>
	  {if $l.category < 4 }<em style=" float:left; padding-top:5px; width:110px;font-weight:bold; font-size:14px;">${$l.price|number_format}{if $l.priceMethod > 0}<br/>{valueOfArray arrValue=$lang.val.priceMethod value=$l.priceMethod}{/if}</em>{/if}
	</li>
{/foreach}
</ul>
  <div id="paging" style="width:480px;background:#{$req.template.bgcolor};"><strong><a href="/soc.php?cp=disprolist&StoreID={$req.info.StoreID}">All Properties</a>&nbsp;&nbsp;({$req.itemNumbers})</strong> </div>

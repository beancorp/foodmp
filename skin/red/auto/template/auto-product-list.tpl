<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<h2 style="width:482px;background:#{$req.template.bgcolor}">Auto <a href="javascript:history.go(-1);void(0);">Back</a></h2>
{*popup_init src="/include/overlib/overlib.js"*}

<ul id="item-list8">
{foreach from=$req.items.product item=l} 
    <li>
	<div style=" float:left; width:79px; padding-top:5px; padding-bottom:10px; padding-right:10px;">
	  <a href="javascript:popUp('{$l.bimage.text}')" {*popup sticky=true caption="Private" hauto='true' vauto='true'
text="<img src='/images/250x187.jpg'/>" snapx=10 snapy=10*}><img border="0" src="{$l.simage.text}" width="{$l.simage.width}" height="{$l.simage.height}" /></a>
	</div>
      <div id="context" style="width:265px;">
	  <a href="{if $l.is_auction=='yes'}/soc.php?cp=disauction&StoreID={$l.StoreID}&proid={$l.pid}{else}/{$req.info.url_bu_name}/{$l.url_item_name}{/if}" style="margin-top:5px;padding:0;">{$l.item_name|truncate:32:"..."}</a>
	  {$l.year} {$lang.tt.year}, {$l.makeName}, {$l.modelName}
	  {if $l.transmission neq ''}, {$lang.val.transmission[$l.transmission]}{/if}
	  {if $l.pattern neq ''}, {$lang.val.pattern[$l.pattern]}{/if}
	  {if $l.color neq ''}, {$lang.tt.color}: {$l.color}{/if}
	  {if $l.kms neq ''}, {$l.kms}kms{/if}
	  <a href="/{$req.info.url_bu_name}" class="arrowgrey">{$req.info.bu_name|truncate:45:'...'}</a>
	  </div>
	  <em style=" float:left;font-weight:bold; font-size:14px; padding-top:5px; width:110px;">{if $l.price > 0}${$l.price|number_format}{/if}</em>
	</li>
{/foreach}
</ul>
  <div id="paging" style="width:480px;background:#{$req.template.bgcolor};"><strong><a href="/soc.php?cp=disprolist&StoreID={$req.info.StoreID}">All Cars</a>&nbsp;&nbsp;({$req.itemNumbers})</strong> </div>

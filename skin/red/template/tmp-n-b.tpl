<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
{include_php file='include/jssppopup.php'}
  <h2 class="safarispace" style="background:#{$req.template.bgcolor};">Item Detail <a href="javascript:history.go(-1)">Back</a></h2>
	
<ul id="item-list8">
{foreach from=$req.items.product item=l}
{if $l.pid}  
    <li>
	<div style=" float:left; width:79px; padding:5px 10px 10px 0;">
	  <a href="javascript:popSliding('{$l.StoreID}','{$l.pid}','{$l.simage.text}')"><img border="0" src="{$l.simage.text}" width="{$l.simage.width}" height="{$l.simage.height}" /></a>
	</div>
      <div id="context" style=" width:260px;float:left"><a style="margin-top:2px;padding:0;" href="/{$req.info.url_bu_name}/{$l.url_item_name}">{$l.item_name|truncate:32:"..."}</a>{truncate content="`$l.description`" length="90"} <a href="/{$req.info.url_bu_name}" class="arrowgrey">{$req.info.bu_name|truncate:45:'...'}</a></div>
      
      <table cellpadding="0" cellspacing="0" width="110">
      	<tr><td>
      	<div style="position:relative;width:110px;height:26px;">
      	<strong style="position:absolute;left:0;top:0;" class="{if $l.is_auction eq 'yes'}auctionit{else}buyit{/if}">
		{if $l.is_auction eq 'yes'}${$l.cur_price|number_format:2}{else}
		${$l.price|number_format:2}{/if}</strong>
		</div>
		</td></tr>
		
		{if $l.is_auction eq 'no'}<tr><td><em style="font-weight:bold; font-size:14px;">{if $l.non}{$lang.non[$l.non]}{/if}</em></td></tr>{/if}
      		
      	{if $l.is_auction eq 'yes'}
      	<tr><td>
		 <span class="{if $l.is_auction eq 'yes' && $l.end_stamp<600}auctionhurg{else}buyitnow{/if}">
		     {if $l.is_auction eq 'yes' && $l.end_stamp<600}&lt;&nbsp;&nbsp;{$l.end_stamp|timeup}{elseif $l.is_auction eq 'yes'}{$l.end_stamp|timeup}{/if}
		 </span>
		 </td></tr>
		 {/if}

	      {if !($smarty.session.attribute eq 3 && $smarty.session.subAttrib eq 3)}
	      	{if $smarty.session.attribute neq "" && $smarty.session.attribute neq 4}
	      		{if $l.is_auction eq 'no'}
	      		<tr><td>
	      	 <div><a href="javascript:checkaddtoWishlist('{$l.pid}','{$smarty.session.ShopID}','{$l.item_name|replace:"'":"\'"}');"><img src="/skin/red/images/add-to-wishlist.gif" style="margin:10px 0 0 0;"/></a></div></td></tr>
	      	 	{/if}
	      	{/if}
	      {/if}
         </table>
      	
      	
      	
   
      
	  </li>
{/if}
{/foreach}
</ul>
{include file='wishlist/wishlist_link.tpl' isline="yes"}
  <div id="paging" style="background:#{$req.template.bgcolor}; width:487px;"><strong><a href="soc.php?cp=disprolist&StoreID={$req.info.StoreID}">All Items</a>&nbsp;&nbsp;({$req.itemNumbers})</strong> </div>

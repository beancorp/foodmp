{include file="searchpanel.tpl"}

	<h2 style="color:#777;">{$req.counter|default:'0'} item(s) found from your search</h2>

	{if $req.counter ne 0}
    <ul id="search-list">
    	{if $req.page ne 'state'}
	    	{foreach from=$req.products item=product}
	         <li><a href="/{$product.website_name}/{$product.url_item_name}">{$product.img_link}</a>
	        	<div id="context"><a href="/{$product.website_name}/{$product.url_item_name}">{$product.item_name}</a>
	        	{$product.description|truncate:200:"..."}
	        	<a href="/{$product.bu_urlstring}" class="arrowgrey">{$product.bu_name}</a></div>
	        	<strong>{if $product.price > 0}${$product.price}{/if}</strong>
	            <span>{$product.bu_suburb}</span>	        
	         </li>
	        {/foreach}
        {else}
	    	{foreach from=$req.products item=product}

	    	{if $product.item_name ne ''}
	    		{if $product.flag eq 1}
	    		<!--<h2 class="adminTitle" style="margin:2px;">{$product.name}</h2>-->
	    		<h2 style="font-size:14px; color: #392f7e; padding: 0px; font-weight: bolder; height:20px; line-height:24px;">{$product.name}</h2>
	    		{/if}
				
				<li>
				<table width="100%" height="31" border="0" cellpadding="0" cellspacing="0" style="height:31px;">
				  <tr>
					<td bgcolor="#eeeeee">&nbsp;&nbsp;{$product.suburbName|upper}&nbsp;</td>
					<td width="3" bgcolor="#FFFFFF"><img id="space" src="/images/spacer.gif" width="3px" height="1px" class="clear"/></td>
					<td width="31" align="center" valign="bottom" bgcolor="#eeeeee" style="height:31px;"><img id="space" src="/skin/red/estate/images/list-type-{$product.property}.jpg" width="31" height="31" /></td>
					<td width="3" bgcolor="#FFFFFF"><img id="space" src="/images/spacer.gif" width="3px" height="1px" class="clear"/></td>
					<td width="31" align="center" valign="bottom" bgcolor="#eeeeee" style="background:url(/skin/red/estate/images/list-bedroom.jpg)"><samp id="listNum">{$product.bedroom|default:'-':true:6}</samp></td>
					<td width="3" bgcolor="#FFFFFF"><img id="space" src="/images/spacer.gif" width="3px" height="1px" style="clear:both"/></td>
					<td width="31" align="center" valign="bottom" bgcolor="#eeeeee" style="background:url(/skin/red/estate/images/list-bathroom.jpg)"><samp id="listNum">{$product.bathroom|default:'-':true:6}</samp></td>
					<td width="3" bgcolor="#FFFFFF"><img id="space" src="/images/spacer.gif" width="3px" height="1px" style="clear:both"/></td>
					<td width="31" align="center" valign="bottom" bgcolor="#eeeeee" style="background:url(/skin/red/estate/images/list-carspace.jpg)"><samp id="listNum">{$product.carspaces|default:'-':true:6}</samp></td>
				  </tr>
				</table>
				
		        <a href="/{$product.website_name}/{$product.url_item_name}"><img src="{$product.simage.text}" alt="{$product.bu_name}" title="{$product.bu_name}" width="{$product.simage.width}" height="{$product.simage.height}" border="0" /></a>
		        	<div id="context" style=" width:480px;"><a href="/{$product.website_name}/{$product.url_item_name}">{$product.item_name}</a>
		        	{$product.suburbName}, {$product.stateName}{if $product.location neq ''}, { $product.location}{/if} <br />
	  				{truncate content="`$product.content`" length="150" etc=''}
					<a href="/{$product.website_name}" class="arrowgrey">{$product.bu_name}</a>
					</div>
		        	{if $product.category < 4 }<strong class="estate">{if $product.price >0}${$product.price|number_format}{/if}{if $product.priceMethod > 0}<br/>{valueOfArray arrValue=$lang.val.priceMethod value=$product.priceMethod}{/if}{if $product.solded}<br/><img src="/skin/red/images/sold_icon.gif" border="0" />{/if}</strong>{/if}
		            </li>
					<div style="clear:both; height:10px;"></div>
		        {/if}
	        {/foreach}
        {/if}
	</ul>
	{/if}
	
	<br>
	<br>
    
	<div id="paging-wide">
		&nbsp;{$req.linkStr}
    </div>

<div style="clear:both; width:auto; margin-bottom:15px; margin-top:10px;">
{include file="../seller_context.tpl" search_type="estate"}
</div>

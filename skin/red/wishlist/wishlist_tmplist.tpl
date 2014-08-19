<div style="width:100%; background:#FFF">
			<ul id="wishlist_prolist">
            	{if $req.product}
				{foreach from=$req.product item=pl key=k}
                	{if $k==0}
                    <li class="firstli">
                    {else}
                    <li>
                    {/if}
                    	<div id="listimg"><a href="javascript:popwishSliding('{$pl.StoreID}','{$pl.pid}','{$pl.simage.text}')"><img src="{$pl.simage.text}" width="{$pl.simage.width}" height="{$pl.simage.height}" border="0"/></a></div>
                    	<div id="listcontent">
                            <div style="width:100%;" class="protitle"><a href="/{$pl.bu_urlstring}/wishlist/{$pl.url_item_name}">{$pl.item_name|truncate:40}</a></div>
                            <div style="width:100%">{$pl.description|strip_tags|truncate:100}</div>
                            <div style="width:100%; padding-top:5px;"><a href="/{$pl.bu_urlstring}/wishlist"  class="arrowgrey">{$pl.bu_name}</a></div>
                        </div>
                    	<div id="listprice">{if $pl.price}{if $pl.protype neq 1}${$pl.price|number_format:2}{if !$pl.fotgive}<br/><img src="/skin/red/images/sold_wish_icon.png"/>{/if}{/if}{/if}</div>
                	</li>
                {/foreach}
                {/if} 
            </ul>
            <div style="clear:both;"></div>


 <div style="width:100%; text-align:right; padding-top:10px;">
 <table width="100%" cellspacing="0"><tr><td align="left" style="padding-left:10px;">(Total items: {$procount})</td>
            	<td><span style="padding-right:20px;">{$req.product.pagination}{$req.product.0.linkStr}</span></td></tr></table>
 </div>
</div>
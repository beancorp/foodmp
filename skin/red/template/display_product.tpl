{include_php file='include/jssppopup.php'}
<h2  class="safarispace" style="background-color:#{$templateInfo.bgcolor};">Item Detail <a href="javascript: history.go(-1)">Back</a></h2>
            
{foreach from=$req.items.product item=l}  
	<div id="item-details" class="item-details">

		<div class="item-specs">
			<ul style="margin:0px; padding:0px;">
				<li><div style="float:right; margin-right:5px; width:240px; display:inline;">
			<div align="center"><a href="javascript:popSliding('{$l.StoreID}','{$l.pid}','{$l.images.mainImage.0.sname.text}');"><img border="0" src="{$l.image_name.name}" width="231" /></a></div>
			<div style="clear:both"></div>
			  <div id="thumbwrapper" style="position:relative;{if $l.imagesCount < 10}border:hidden;border-color:#FFF{/if}">
				  {if $l.images.imagesCount > 4}
					<div style="position:absolute; top:32px; z-index:999; left:-3px;"><a href="Javascript:;" onmouseover="Move_right(); return false;" onmouseout="Move_stop();"><img src="/skin/red/images/left.gif"  /></a></div>
					<div style="position:absolute; top:32px; left:226px; z-index:999;"><a href="Javascript:;" onmouseover="Move_left(); return false;" onmouseout="Move_stop();"><img src="/skin/red/images/right.gif" /></a></div>
					{/if}
					<div id="scroll_wrap">
					 <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
							  <tr>
								<td id="scroll_item1">
							<table border="0" cellspacing="3" cellpadding="0" align="center" style='width:100%;+width:auto;'>
								<tr>
								{foreach from=$l.images.subImage item=il}
								{if $il.sname.text neq '/images/79x79.jpg'}
								<td width="76" align="center"><a href="javascript:popSliding('{$l.StoreID}','{$l.pid}','{$il.sname.text}');"><img border="0" src="{$il.sname.text}" width="76" /></a></td>
								{/if}
								{/foreach}
								</tr>
						</table></td>
						<td id="scroll_item2"></td>
					  </tr>
					</table>
				</div>
			  </div>
			  



		</div><h1 class="TextFS14" style="width: auto; display: inline;margin:0; font-weight:bold; clear:left; font-size:12px;font-weight:bold;color:#777777">{$l.item_name}</h1></li>
        		<li>
             <div style="float:left; padding-top: 10px; padding-bottom:20px; width:225px;">
                <fb:like href="{$smarty.const.SOC_HTTP_HOST}{if $l.is_auction=='yes'}soc.php?cp=disauction&StoreID={$l.StoreID}&proid={$l.pid}{else}{$req.info.url_bu_name}/{$l.url_item_name}{/if}" send="false" width="200" show_faces="true" font="arial"></fb:like>
                </div></li>
				{if $l.pro_tags}<li>Tags:&nbsp;&nbsp;{$l.pro_tags}</li>{/if}
				{if $l.isattachment eq '1'}
				<li><span>Type:</span>Downloadable product</li>
				{/if}
				<li><span>Product Code:</span>{$l.p_code}</li>
				{if $l.isattachment eq '0'}
				<li>Domestic Shipping Method:<br/>
				<div style="padding:5px;width:230px;">			
				{foreach from=$l.deliveryMethod|explode:"|" item=opcl key=oplk}
                {if $req.info.bu_delivery_text[$opcl] neq ""}
    				{if $opcl eq '5'}<strong>{/if}{$req.info.bu_delivery_text[$opcl]}{if $opcl eq '5'}</strong>{/if} 
                    (Fee:${if $l.postage}{foreach from=$l.postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}{else}0.00{/if})<br/>{/if}
    			
    			{/foreach}
				</div>
				</li>
				{if $l.isoversea}
				<li>Overseas Shipping Method:
				  <div style="padding:5px;width:230px;">
				{foreach from=$l.oversea_deliveryMethod|explode:"|" item=opcl key=oplk}
    		{if $req.info.bu_delivery_text[$opcl] neq ""}{if $opcl eq '5'}<strong>{/if}{$req.info.bu_delivery_text[$opcl]}{if $opcl eq '5'}</strong>{/if} 
            (Fee:${if $l.oversea_postage}{foreach from=$l.oversea_postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}{else}0.00{/if})<br/>{/if}
    	{/foreach}
    			</div>
				</li>
				{/if}
				<li><span>In Stock Quantity:</span>{$l.stockQuantity}</li>		
				{/if}
				<li class="price"><span>Price:</span><span style="width:150px;">${$l.price}{if $l.unit ne ''} <em style="font-weigth:normal;font-size:14px;">{$l.unit}</em>{/if} {if $l.non}&nbsp;{$lang.non[$l.non]}{/if}</span></li>
				{if $l.isattachment eq '1'}<li style="font-size:10px; color:#FF0000">The file can only be downloaded once and must be downloaded within 24 hours.
				</li>{/if}
				<li style="padding-top:20px;" class="desc">
					{if $req.isuserbuy eq 'yes'}
						<a href="/downloadatt.php?pid={$l.pid}"><img src="/skin/red/images/download.gif"/></a>
					{else}
					{if $l.on_sale == 'yes'}
						{if $l.stockQuantity > 0}
							{if $req.is_customer}
								<a href="{$soc_https_host}soc.php?cp=buy&StoreID={$l.StoreID}&pid={$l.pid}">{if $l.isattachment eq '1'}<img src="/skin/red/images/buttons/attachment_buy.gif" border="0" />{else}<img src="/skin/red/images/bu-buynow.gif" border="0" />{/if}</a>
							{else}<a href="javascript:{if $UserID==$req.info.StoreID}alert('Sorry, you can not buy items of your own website.'){else}location.href='/soc.php?cp=login&from=buy&reurl={$smarty.const.SOC_HTTP_HOST}{if $l.is_auction=='yes'}soc.php?cp=disauction&StoreID={$l.StoreID}&proid={$l.pid}{else}{$req.info.url_bu_name}/{$l.url_item_name}'{/if}{/if}">{if $l.isattachment eq '1'}<img src="/skin/red/images/buttons/attachment_buy.gif" border="0" />{else}<img src="/skin/red/images/bu-buynow.gif" border="0" />{/if}</a>
							{/if}
							{if $l.non}<samp style="padding-left:30px;"><a href="javascript:{if $smarty.session.ShopID}popNewOffer('{$l.StoreID}','{$l.pid}'){else}location.href='/soc.php?cp=login&from=buy&reurl={$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$l.url_item_name}'{/if};void(0);"><img src="/skin/red/images/buttons/bu-makeanoffer_purple.gif" border="0" /></a></samp>{/if}
						{else}
							<img src="/skin/red/images/sold_icon.gif" border="0" />
						{/if}
					{elseif $l.on_sale == 'no'}
						<img src="/skin/red/images/bu-onsalesoon.gif" border="0" />
					{elseif $l.on_sale == 'contact'}
						<a href="{if $req.is_customer}javascript:popcontactwin();{else}javascript:tipRedirect();{/if}"><img src="/skin/red/images/contactUsNow.png" width="65" height="30" border="0" /></a>
						{if $l.stockQuantity > 0 and $l.non}
						<samp style="padding-left:30px;"><a href="javascript:{if $smarty.session.ShopID}popNewOffer('{$l.StoreID}','{$l.pid}'){else}location.href='/soc.php?cp=login&from=buy&reurl={$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$l.url_item_name}'{/if};void(0);"><img src="/skin/red/images/buttons/bu-makeanoffer_purple.gif" border="0" /></a></samp>
						{/if}
					{else}
						<img src="/skin/red/images/sold_icon.gif" border="0" />
					{/if}
					{/if}
                         
            {if !($smarty.session.attribute eq 3 && $smarty.session.subAttrib eq 3)}
            	{if $smarty.session.attribute neq "" && $smarty.session.attribute neq 4}
            	<a href="javascript:checkaddtoWishlist('{$l.pid}','{$smarty.session.ShopID}','{$l.item_name|replace:"'":"\'"}');"><img src="/skin/red/images/add-to-wishlist.gif" style="margin:10px 0 0 5px;"/></a>
            	{include file='wishlist/wishlist_link.tpl'}
            	{/if}
            {/if}
                <div class="clear"></div>
					<br />
					<br />
                    {if $l.youtubevideo neq ""&& $sellerhome neq "1"}
                    <div style="width:490px; height:300px;">
                    <object width="490" height="300">
                        <param name="movie" value="{$l.youtubevideo}"></param>
                        <param name="allowFullScreen" value="true"></param>
                        <param name="allowscriptaccess" value="always"></param>
                        <param name="wmode" value="transparent" />
                        <embed src="{$l.youtubevideo}" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="490" height="300" wmode="transparent"></embed>
     				</object>
                    </div>
                    <br/>
                    {/if}
					<div class="TextFS14 TextJustify" style="padding-left: 0px; padding-right: 10px;word-wrap: break-word;">{$l.description}</div>
			  </li>
			</ul>
		</div>
		

   		
   	</div>
<script type="text/javascript">
var speed = 30;
var scroll_wrap=document.getElementById("scroll_wrap");
var scroll_item1=document.getElementById("scroll_item1");
var scroll_item2=document.getElementById("scroll_item2");
{if $l.images.imagesCount > 3}
scroll_item2.innerHTML = scroll_item1.innerHTML;
{/if}

{literal}
function Marquee_left(){
    if(scroll_item2.offsetWidth-scroll_wrap.scrollLeft<=0)
        scroll_wrap.scrollLeft-=scroll_item1.offsetWidth
    else{
        scroll_wrap.scrollLeft = scroll_wrap.scrollLeft + 5;
    }
}

function Marquee_right(){
    if(scroll_wrap.scrollLeft<=0)
        scroll_wrap.scrollLeft+=scroll_item1.offsetWidth
    else{
        scroll_wrap.scrollLeft = scroll_wrap.scrollLeft - 5;
    }
}
var Myaction;
//var Myaction = setInterval(Marquee_left,speed);
//var MyMar_right=setInterval(Marquee_right,speed);
function Move_left() {
    clearInterval(Myaction);
    Myaction = setInterval(Marquee_left,speed);
}
function Move_right() {
    clearInterval(Myaction);
    Myaction=setInterval(Marquee_right,speed);
}
function Move_stop() {
    clearInterval(Myaction);
}
{/literal}
</script>

{/foreach}
        <span class="price"></span>